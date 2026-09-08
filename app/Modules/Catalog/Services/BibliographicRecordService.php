<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BibliographicRecordService
{
    /** Direktori penyimpanan cover pada disk publik. */
    protected const COVER_DIRECTORY = 'catalog/covers';

    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return BibliographicRecord::with(['publisher', 'language', 'collectionType', 'classification'])
            ->withCount(['authors', 'physicalItems'])
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['collection_type_id']), fn ($q) => $q->where('collection_type_id', $filters['collection_type_id']))
            ->when(isset($filters['language_id']), fn ($q) => $q->where('language_id', $filters['language_id']))
            ->when(isset($filters['publication_year']), fn ($q) => $q->where('publication_year', $filters['publication_year']))
            ->when(isset($filters['publication_status']), fn ($q) => $q->where('publication_status', $filters['publication_status']))
            ->when(isset($filters['is_public']), fn ($q) => $q->where('is_public', $filters['is_public']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): BibliographicRecord
    {
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        $data['publication_status'] = $data['publication_status'] ?? 'draft';
        // Jejak kepemilikan dicatat di sini, bukan diserahkan ke form:
        // BibliographicRecordPolicy memakainya untuk memutuskan siapa yang
        // boleh menyunting, jadi nilainya tidak boleh berasal dari input.
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $newCoverPath = null;
        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $newCoverPath = $this->storeCover($data['cover'], ['title' => $data['title']]);
            $data['cover_path'] = $newCoverPath;
            unset($data['cover']);
        }

        $authorIds = $data['author_ids'] ?? [];
        $subjectIds = $data['subject_ids'] ?? [];
        unset($data['author_ids'], $data['subject_ids']);

        try {
            $record = BibliographicRecord::create($data);

            // Sync pivot relations
            if (! empty($authorIds)) {
                $record->authors()->sync($authorIds);
            }
            if (! empty($subjectIds)) {
                $record->subjects()->sync($subjectIds);
            }
        } catch (\Throwable $e) {
            // Jangan tinggalkan file cover yatim bila penyimpanan record gagal.
            $this->deleteCover($newCoverPath, ['title' => $data['title'] ?? null]);

            throw $e;
        }

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog dibuat: '.$record->title);

        return $record;
    }

    public function update(BibliographicRecord $record, array $data): BibliographicRecord
    {
        $oldCoverPath = null;
        $newCoverPath = null;

        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            // Simpan cover baru lebih dulu; cover lama baru dihapus setelah
            // record berhasil diperbarui, agar rollback tidak menyisakan
            // baris yang menunjuk ke file yang sudah terhapus.
            $oldCoverPath = $record->cover_path;
            $newCoverPath = $this->storeCover($data['cover'], ['record_id' => $record->id]);
            $data['cover_path'] = $newCoverPath;
            unset($data['cover']);
        }

        $authorIds = $data['author_ids'] ?? [];
        $subjectIds = $data['subject_ids'] ?? [];
        unset($data['author_ids'], $data['subject_ids']);

        $data['updated_by'] = auth()->id();

        try {
            $record->update($data);

            // Sync pivot relations
            $record->authors()->sync($authorIds);
            $record->subjects()->sync($subjectIds);
        } catch (\Throwable $e) {
            $this->deleteCover($newCoverPath, ['record_id' => $record->id]);

            throw $e;
        }

        if ($oldCoverPath && $oldCoverPath !== $record->cover_path) {
            $this->deleteCover($oldCoverPath, ['record_id' => $record->id]);
        }

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog diperbarui: '.$record->title);

        return $record;
    }

    public function delete(BibliographicRecord $record): void
    {
        // Skema menyatakan physical_items dan digital_assets menahan induknya
        // lewat foreign key restrictOnDelete. Penghapusan lunak melewati
        // penjagaan itu di tingkat basis data, jadi aturannya ditegakkan di
        // sini — kalau tidak, eksemplar dan berkas akan menggantung pada judul
        // yang sudah tidak terlihat di mana pun.
        $itemCount = $record->physicalItems()->count();
        if ($itemCount > 0) {
            throw new \InvalidArgumentException(
                "Katalog masih memiliki {$itemCount} eksemplar fisik. Hapus atau pindahkan eksemplarnya terlebih dahulu."
            );
        }

        $assetCount = $record->digitalAssets()->count();
        if ($assetCount > 0) {
            throw new \InvalidArgumentException(
                "Katalog masih memiliki {$assetCount} aset digital. Hapus aset digitalnya terlebih dahulu."
            );
        }

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog dihapus: '.$record->title);

        // Relasi pengarang/subjek dan berkas cover sengaja dipertahankan:
        // penghapusan bersifat lunak, dan record yang dipulihkan harus kembali
        // utuh, bukan sebagai judul tanpa pengarang dan tanpa sampul.
        $record->delete();
    }

    public function findWithRelations(int $id): BibliographicRecord
    {
        $record = BibliographicRecord::with([
            'publisher', 'language', 'classification', 'collectionType',
            'authors', 'subjects', 'physicalItems',
        ])->findOrFail($id);

        // Load digitalAssets with error handling (they use SoftDeletes)
        try {
            $record->load('digitalAssets');
        } catch (\Throwable $e) {
            Log::warning('Error loading digital assets for record', [
                'record_id' => $id,
                'error' => $e->getMessage(),
            ]);
            // Ensure digitalAssets is an empty collection if there's an error
            $record->setRelation('digitalAssets', collect());
        }

        return $record;
    }

    /**
     * Simpan file cover ke disk publik dan kembalikan path relatifnya.
     *
     * @throws \InvalidArgumentException bila folder tujuan tidak dapat ditulis
     *                                   atau file gagal disimpan
     */
    protected function storeCover(UploadedFile $cover, array $context = []): string
    {
        $this->ensureStorageWritable();

        try {
            $coverPath = $cover->store(self::COVER_DIRECTORY, 'public');
        } catch (\Throwable $e) {
            Log::error('Cover storage exception', $context + [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // Pesan internal (berisi path server) hanya masuk log, tidak ke pengguna.
            throw new \InvalidArgumentException('Gagal menyimpan cover buku. Hubungi administrator.');
        }

        if (! $coverPath) {
            Log::error('Cover upload failed', $context + ['user_id' => auth()->id()]);

            throw new \InvalidArgumentException('Gagal mengunggah cover buku.');
        }

        return $coverPath;
    }

    /**
     * Pastikan direktori cover pada disk publik ada dan dapat ditulis.
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureStorageWritable(): void
    {
        try {
            $disk = Storage::disk('public');

            if (! $disk->directoryExists(self::COVER_DIRECTORY)) {
                $disk->makeDirectory(self::COVER_DIRECTORY);
            }

            $path = $disk->path(self::COVER_DIRECTORY);
            $writable = is_dir($path) && is_writable($path);
        } catch (\Throwable $e) {
            Log::error('Cover storage writability check failed', [
                'directory' => self::COVER_DIRECTORY,
                'error' => $e->getMessage(),
            ]);
            $writable = false;
        }

        if (! $writable) {
            throw new \InvalidArgumentException('Folder penyimpanan cover tidak dapat ditulis. Hubungi administrator.');
        }
    }

    /**
     * Hapus file cover secara best-effort. Kegagalan hanya dicatat di log
     * agar tidak membatalkan operasi yang sudah berhasil di database.
     */
    protected function deleteCover(?string $path, array $context = []): void
    {
        if (! $path) {
            return;
        }

        try {
            $disk = Storage::disk('public');

            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::warning('Failed to delete cover file', $context + ['cover_path' => $path]);
            }
        } catch (\Throwable $e) {
            Log::warning('Cover deletion exception', $context + [
                'cover_path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
