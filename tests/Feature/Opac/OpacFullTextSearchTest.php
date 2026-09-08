<?php

namespace Tests\Feature\Opac;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Opac\Services\OpacSearchService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Jalur FULLTEXT — hanya berlaku di MySQL.
 *
 * Sengaja memakai DatabaseMigrations, bukan RefreshDatabase: indeks FULLTEXT
 * InnoDB tidak melihat baris yang masih berada dalam transaksi yang belum
 * di-commit, sehingga di bawah RefreshDatabase setiap MATCH akan mengembalikan
 * kosong dan test ini justru menguji jalur cadangan. Harganya migrasi ulang per
 * test, dan itu sebabnya berkas ini dijaga tetap kecil.
 */
class OpacFullTextSearchTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('Indeks FULLTEXT hanya ada di MySQL.');
        }
    }

    private function publish(string $title, array $attributes = []): BibliographicRecord
    {
        return BibliographicRecord::factory()->published()->withAuthor()->create(
            array_merge(['title' => $title], $attributes)
        );
    }

    /**
     * Inti perbaikan ini: indeks yang dibangun migrasi sejak awal tetapi tidak
     * pernah disentuh satu kueri pun, kini benar-benar dipakai.
     */
    #[Test]
    public function the_search_query_actually_uses_the_fulltext_index(): void
    {
        // Optimizer MySQL baru memilih jalur fulltext bila tabelnya cukup besar
        // untuk membuat pemindaian penuh terasa mahal. Pada tabel satu baris ia
        // memilih indeks lain — dan itu keputusan yang benar, bukan kegagalan.
        BibliographicRecord::factory()->count(40)->published()->create(['title' => 'Judul Biasa Sekali']);
        $this->publish('Tafsir Al Misbah Lengkap');

        $captured = null;
        DB::listen(function ($query) use (&$captured) {
            if (str_contains(strtolower($query->sql), 'match') && ! str_contains(strtolower($query->sql), 'count(')) {
                $captured = $query;
            }
        });

        app(OpacSearchService::class)->search(['keyword' => 'Tafsir']);

        $this->assertNotNull($captured, 'Pencarian tidak menghasilkan kueri MATCH sama sekali.');

        $plan = DB::select('EXPLAIN '.$captured->sql, $captured->bindings);

        $this->assertSame('fulltext', $plan[0]->type, 'Pencarian tidak berjalan lewat jalur fulltext.');
        $this->assertSame(
            'ft_bibliographic_records_search',
            $plan[0]->key,
            'Kueri pencarian tidak memakai indeks FULLTEXT — indeks itu kembali menganggur.'
        );
    }

    #[Test]
    public function a_prefix_finds_the_whole_word(): void
    {
        $this->publish('Tafsir Al Misbah');

        $results = app(OpacSearchService::class)->search(['keyword' => 'Tafs']);

        $this->assertSame(1, $results->total());
        $this->assertSame('Tafsir Al Misbah', $results->first()->title);
    }

    /**
     * Hasil diurutkan menurut relevansi, bukan menurut tanggal input.
     * Sebelumnya judul yang paling cocok bisa terkubur di halaman terakhir
     * hanya karena dimasukkan lebih dulu.
     */
    #[Test]
    public function results_come_back_ranked_by_relevance_not_by_date(): void
    {
        // Dimasukkan lebih dulu, dan paling cocok: dua kali kata "tafsir".
        $this->publish('Tafsir Ringkas', [
            'keywords' => 'tafsir',
            'abstract' => 'Kajian tafsir.',
            'created_at' => now()->subYear(),
        ]);

        // Dimasukkan paling akhir, dan paling lemah kecocokannya.
        $this->publish('Sejarah Peradaban', [
            'keywords' => 'tafsir',
            'created_at' => now(),
        ]);

        $results = app(OpacSearchService::class)->search(['keyword' => 'tafsir']);

        $this->assertSame(2, $results->total());
        $this->assertSame(
            'Tafsir Ringkas',
            $results->first()->title,
            'Hasil masih diurutkan menurut tanggal, bukan relevansi.'
        );
    }

    /**
     * Kata kunci yang tidak dapat dilayani indeks harus tetap menemukan
     * hasilnya lewat jalur cadangan — bukan mengembalikan kosong.
     */
    #[Test]
    public function a_keyword_outside_the_index_still_falls_back_and_finds_it(): void
    {
        $this->publish('Ensiklopedia Hadis', ['isbn' => '9789794334455']);

        $service = app(OpacSearchService::class);

        // ISBN berada di luar indeks FULLTEXT.
        $this->assertSame(1, $service->search(['keyword' => '9789794334455'])->total());

        // Kata terlalu pendek untuk diindeks InnoDB.
        $this->assertSame(1, $service->search(['keyword' => 'Ensiklopedia'])->total());
    }
}
