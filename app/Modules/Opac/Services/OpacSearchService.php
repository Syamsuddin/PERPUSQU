<?php

namespace App\Modules\Opac\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Pencarian katalog publik.
 *
 * Sebelumnya seluruh pencarian memakai `LIKE '%kata%'`, yang tidak dapat
 * memakai indeks apa pun sehingga selalu memindai seluruh tabel — sementara
 * indeks FULLTEXT `ft_bibliographic_records_search` yang dibangun migrasi tidak
 * pernah disentuh satu kueri pun. Hasilnya juga tidak pernah diurutkan menurut
 * relevansi, melainkan menurut tanggal input.
 *
 * Sekarang pencarian berjalan dua tahap:
 *
 *  1. FULLTEXT (MySQL) — memakai indeks, dan mengurutkan menurut relevansi
 *     sehingga judul yang paling cocok muncul lebih dulu.
 *  2. Bila tahap pertama tidak menghasilkan apa pun — atau basis datanya bukan
 *     MySQL — pencarian jatuh ke pencocokan substring seperti sebelumnya.
 *
 * Tahap kedua bukan sekadar kehati-hatian. FULLTEXT MySQL mengabaikan token
 * lebih pendek dari `innodb_ft_min_token_size` (bawaan 3) dan seluruh kata
 * dalam daftar stopword, serta tidak mencakup nama pengarang maupun ISBN yang
 * berada di luar indeks. Tanpa cadangan itu, pencarian yang dulu berhasil bisa
 * mendadak tidak menemukan apa-apa — kemunduran yang jauh lebih merugikan
 * daripada kueri yang lambat.
 */
class OpacSearchService
{
    /**
     * Panjang token minimum yang diindeks InnoDB (`innodb_ft_min_token_size`).
     * Kata yang lebih pendek tidak pernah masuk indeks, jadi pencarian yang
     * hanya berisi kata pendek langsung diarahkan ke jalur cadangan.
     */
    private const MIN_TOKEN_LENGTH = 3;

    /** Kolom yang tercakup indeks ft_bibliographic_records_search. */
    private const FULLTEXT_COLUMNS = ['title', 'keywords', 'abstract'];

    public function search(array $filters): LengthAwarePaginator
    {
        $keyword = trim((string) ($filters['keyword'] ?? ''));
        $perPage = $filters['per_page'] ?? 12;

        if ($keyword === '') {
            return $this->baseQuery($filters)->latest('created_at')->paginate($perPage);
        }

        if ($tokens = $this->fullTextTokens($keyword)) {
            $results = $this->relevanceRanked($this->baseQuery($filters), $tokens)->paginate($perPage);

            if ($results->total() > 0) {
                return $results;
            }
        }

        return $this->substringMatch($this->baseQuery($filters), $keyword)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Filter dan relasi yang berlaku untuk kedua jalur pencarian.
     */
    protected function baseQuery(array $filters): Builder
    {
        return BibliographicRecord::with(['authors', 'publisher', 'language', 'classification', 'collectionType'])
            ->published()
            ->public()
            ->when(! empty($filters['collection_type_id']), fn ($q) => $q->where('collection_type_id', $filters['collection_type_id']))
            ->when(! empty($filters['language_id']), fn ($q) => $q->where('language_id', $filters['language_id']))
            ->when(! empty($filters['classification_id']), fn ($q) => $q->where('classification_id', $filters['classification_id']))
            ->when(! empty($filters['publication_year']), fn ($q) => $q->where('publication_year', $filters['publication_year']));
    }

    /**
     * Ekspresi boolean mode untuk kata kunci, atau null bila jalur FULLTEXT
     * tidak dapat dipakai.
     *
     * Boolean mode dipilih, bukan natural language mode, karena mode alami
     * mengabaikan kata yang muncul di lebih dari separuh baris — pada katalog
     * kecil hampir setiap kata memenuhi syarat itu, sehingga pencarian justru
     * mengembalikan kosong.
     */
    protected function fullTextTokens(string $keyword): ?string
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return null;
        }

        // Operator boolean mode (+ - * " ( ) ~ < > @) yang datang dari pengguna
        // akan mengubah arti kueri atau membuatnya gagal sama sekali —
        // pencarian "C++" tidak boleh menjatuhkan halaman.
        $cleaned = preg_replace('/[+\-*"()~<>@]+/u', ' ', $keyword) ?? '';

        $tokens = collect(preg_split('/\s+/u', trim($cleaned), -1, PREG_SPLIT_NO_EMPTY) ?: [])
            ->filter(fn (string $token) => mb_strlen($token) >= self::MIN_TOKEN_LENGTH);

        if ($tokens->isEmpty()) {
            return null;
        }

        // Akhiran `*` memberi pencocokan awalan kata: "tafs" menemukan "tafsir".
        return $tokens->map(fn (string $token) => $token.'*')->implode(' ');
    }

    /**
     * Jalur FULLTEXT: memakai indeks, hasil diurutkan menurut relevansi.
     */
    protected function relevanceRanked(Builder $query, string $tokens): Builder
    {
        return $query
            ->whereFullText(self::FULLTEXT_COLUMNS, $tokens, ['mode' => 'boolean'])
            ->orderByRaw($this->matchExpression().' desc', [$tokens])
            ->orderByDesc('created_at');
    }

    /**
     * Ekspresi MATCH untuk pengurutan, ditulis sama seperti yang dihasilkan
     * `whereFullText` sehingga daftar kolomnya hanya ada di satu tempat.
     *
     * Catatan hasil pengukuran: MySQL 8.4 tetap melaporkan `Ft_hints: no_ranking`
     * dan `Using filesort` untuk kueri ini. Itu wajar — selama ada pengurutan
     * sekunder (`created_at`), skor relevansi memang dihitung terpisah dari
     * tahap penyaringan. Yang penting sudah tercapai: penyaringannya berjalan
     * lewat indeks (`type=fulltext`), bukan memindai seluruh tabel.
     */
    protected function matchExpression(): string
    {
        $columns = collect(self::FULLTEXT_COLUMNS)->map(fn (string $c) => "`{$c}`")->implode(', ');

        return "match ({$columns}) against (? in boolean mode)";
    }

    /**
     * Jalur cadangan: perilaku pencarian sebelum indeks dipakai. Lebih lambat,
     * tetapi mencakup ISBN dan nama pengarang yang berada di luar indeks
     * FULLTEXT, serta kata-kata yang terlalu pendek untuk diindeks.
     */
    protected function substringMatch(Builder $query, string $keyword): Builder
    {
        return $query->where(fn ($sub) => $sub
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('isbn', 'like', "%{$keyword}%")
            ->orWhere('keywords', 'like', "%{$keyword}%")
            ->orWhereHas('authors', fn ($aq) => $aq->where('name', 'like', "%{$keyword}%"))
        );
    }

    /**
     * Get a single public record with full relations (no internal data)
     */
    public function findPublicRecord(int $id): ?BibliographicRecord
    {
        return BibliographicRecord::with([
            'authors', 'subjects', 'publisher', 'language', 'classification', 'collectionType',
            'physicalItems' => fn ($q) => $q->select('id', 'bibliographic_record_id', 'item_status', 'item_condition_id', 'rack_location_id'),
            'digitalAssets' => fn ($q) => $q->where('publication_status', 'published')->where('is_public', true)
                ->select('id', 'bibliographic_record_id', 'asset_type', 'title', 'original_file_name', 'file_size', 'mime_type'),
        ])
            ->published()
            ->public()
            ->find($id);
    }

    /**
     * Get latest public records for homepage
     */
    public function getLatestRecords(int $limit = 8): Collection
    {
        return BibliographicRecord::with(['authors', 'publisher'])
            ->published()
            ->public()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get stats for homepage
     */
    public function getPublicStats(): array
    {
        return [
            'total_titles' => BibliographicRecord::published()->public()->count(),
            'total_items' => PhysicalItem::where('item_status', 'available')->count(),
            'total_digital' => DigitalAsset::where('publication_status', 'published')->where('is_public', true)->count(),
        ];
    }
}
