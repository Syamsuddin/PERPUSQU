<?php

namespace App\Modules\DigitalRepository\Services;

use App\Modules\Core\Services\SystemSettings;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class DigitalAssetUploadService
{
    protected string $disk = 'local';

    protected string $basePath = 'digital_assets';

    public function __construct(
        protected SystemSettings $settings,
    ) {}

    /**
     * Batas ukuran juga ditegakkan di sini, bukan hanya di FormRequest:
     * unggahan yang datang dari job atau perintah artisan tidak melewati
     * lapisan validasi HTTP sama sekali.
     */
    protected function assertWithinSizeLimit(UploadedFile $file): void
    {
        $limitBytes = $this->settings->maxUploadSizeMb() * 1024 * 1024;

        if ($file->getSize() > $limitBytes) {
            throw new InvalidArgumentException(
                'Ukuran file melebihi batas '.$this->settings->maxUploadSizeMb().' MB.'
            );
        }
    }

    public function upload(UploadedFile $file, array $metadata): DigitalAsset
    {
        // Validate file upload
        if (! $file->isValid()) {
            Log::error('File upload validation failed', [
                'original_name' => $file->getClientOriginalName(),
                'error' => $file->getErrorMessage(),
            ]);
            throw new InvalidArgumentException('File upload tidak valid.');
        }

        $this->assertWithinSizeLimit($file);

        // Check disk space before upload
        $diskSpace = $this->checkDiskSpace($file->getSize());
        if (! $diskSpace['available']) {
            Log::error('Insufficient disk space for upload', [
                'required' => $file->getSize(),
                'available' => $diskSpace['free_space'],
                'user_id' => auth()->id(),
            ]);
            throw new InvalidArgumentException('Ruang penyimpanan tidak cukup. Hubungi administrator.');
        }

        // Security: Verify MIME type matches expected PDF
        $this->validateSecureMimeType($file, ['application/pdf']);

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileName = Str::uuid().'.'.$extension;
        $filePath = $this->basePath.'/'.now()->format('Y/m');

        // Verify storage path is writable before opening a transaction.
        if (! $this->isStorageWritable()) {
            Log::error('Storage path is not writable', [
                'disk' => $this->disk,
                'path' => Storage::disk($this->disk)->path($this->basePath),
                'user_id' => auth()->id(),
            ]);
            throw new InvalidArgumentException('Folder penyimpanan tidak dapat ditulis. Hubungi administrator.');
        }

        // Path file yang sudah tertulis ke disk. Dilacak di luar transaksi
        // karena rollback database tidak dapat membatalkan penulisan disk.
        $storedPath = null;

        try {
            return DB::transaction(function () use ($file, $filePath, $fileName, $originalName, $extension, $mimeType, $fileSize, $metadata, &$storedPath) {
                $storedPath = $file->storeAs($filePath, $fileName, $this->disk);

                if (! $storedPath) {
                    Log::error('File storage failed', [
                        'filename' => $fileName,
                        'path' => $filePath,
                        'disk' => $this->disk,
                        'user_id' => auth()->id(),
                    ]);
                    throw new InvalidArgumentException('Gagal menyimpan file ke server.');
                }

                // Calculate checksum
                $checksum = hash_file('sha256', $file->getRealPath());

                $asset = DigitalAsset::create([
                    'bibliographic_record_id' => $metadata['bibliographic_record_id'],
                    'asset_type' => $metadata['asset_type'],
                    'file_name' => $fileName,
                    'original_file_name' => $originalName,
                    'file_path' => $storedPath,
                    'mime_type' => $mimeType,
                    'file_extension' => $extension,
                    'file_size' => $fileSize,
                    'checksum' => $checksum,
                    'title' => $metadata['title'] ?? null,
                    'description' => $metadata['description'] ?? null,
                    'publication_status' => $metadata['publication_status'] ?? 'draft',
                    'is_public' => $metadata['is_public'] ?? false,
                    'is_embargoed' => $metadata['is_embargoed'] ?? false,
                    'embargo_until' => $metadata['embargo_until'] ?? null,
                    'ocr_status' => 'not_requested',
                    'index_status' => 'pending',
                    'uploaded_by' => auth()->id(),
                    'uploaded_at' => now(),
                ]);

                activity('digital_repository')
                    ->causedBy(auth()->user())
                    ->performedOn($asset)
                    ->withProperties(['file' => $originalName, 'size' => $fileSize])
                    ->log('Aset digital diunggah: '.$originalName);

                return $asset;
            });
        } catch (\Throwable $e) {
            Log::error('Digital asset upload transaction failed', [
                'original_name' => $originalName,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // Transaksi dirollback, tetapi file sudah terlanjur tertulis:
            // hapus agar tidak menjadi file yatim yang menghabiskan disk.
            $this->deleteStoredFile($storedPath, ['original_name' => $originalName]);

            throw $e;
        }
    }

    public function replaceFile(DigitalAsset $asset, UploadedFile $file): DigitalAsset
    {
        // Validate file upload
        if (! $file->isValid()) {
            Log::error('File replacement validation failed', [
                'asset_id' => $asset->id,
                'error' => $file->getErrorMessage(),
            ]);
            throw new InvalidArgumentException('File upload tidak valid.');
        }

        $this->assertWithinSizeLimit($file);

        // Check disk space
        $diskSpace = $this->checkDiskSpace($file->getSize());
        if (! $diskSpace['available']) {
            Log::error('Insufficient disk space for file replacement', [
                'asset_id' => $asset->id,
                'required' => $file->getSize(),
                'available' => $diskSpace['free_space'],
            ]);
            throw new InvalidArgumentException('Ruang penyimpanan tidak cukup. Hubungi administrator.');
        }

        // Security: Verify MIME type matches expected PDF
        $this->validateSecureMimeType($file, ['application/pdf']);

        // Verify storage path is writable before touching anything.
        if (! $this->isStorageWritable()) {
            Log::error('Storage path is not writable', [
                'asset_id' => $asset->id,
                'disk' => $this->disk,
                'path' => Storage::disk($this->disk)->path($this->basePath),
                'user_id' => auth()->id(),
            ]);
            throw new InvalidArgumentException('Folder penyimpanan tidak dapat ditulis. Hubungi administrator.');
        }

        $oldFilePath = $asset->file_path;
        $storedPath = null;

        try {
            // Tulis file baru lebih dulu. File lama baru dihapus setelah baris
            // database menunjuk ke file baru, sehingga rollback tidak pernah
            // menyisakan aset yang menunjuk ke file yang sudah terhapus.
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid().'.'.$extension;
            $filePath = $this->basePath.'/'.now()->format('Y/m');
            $storedPath = $file->storeAs($filePath, $fileName, $this->disk);

            if (! $storedPath) {
                Log::error('Failed to store replacement file', [
                    'asset_id' => $asset->id,
                    'original_name' => $file->getClientOriginalName(),
                ]);
                throw new InvalidArgumentException('Gagal menyimpan file pengganti ke server.');
            }

            $checksum = hash_file('sha256', $file->getRealPath());

            DB::transaction(function () use ($file, $asset, $fileName, $storedPath, $extension, $checksum) {
                $asset->update([
                    'file_name' => $fileName,
                    'original_file_name' => $file->getClientOriginalName(),
                    'file_path' => $storedPath,
                    'mime_type' => $file->getMimeType(),
                    'file_extension' => $extension,
                    'file_size' => $file->getSize(),
                    'checksum' => $checksum,
                    'ocr_status' => 'not_requested',
                    'index_status' => 'pending',
                ]);

                activity('digital_repository')
                    ->causedBy(auth()->user())
                    ->performedOn($asset)
                    ->log('File aset digital diganti: '.$file->getClientOriginalName());
            });
        } catch (\Throwable $e) {
            Log::error('Digital asset replacement transaction failed', [
                'asset_id' => $asset->id,
                'original_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            // File lama masih utuh; buang file baru yang gagal dipakai.
            $this->deleteStoredFile($storedPath, ['asset_id' => $asset->id]);

            throw $e;
        }

        // Aman menghapus file lama: baris database sudah menunjuk ke file baru.
        if ($oldFilePath && $oldFilePath !== $asset->file_path) {
            $this->deleteStoredFile($oldFilePath, ['asset_id' => $asset->id]);
        }

        return $asset;
    }

    /**
     * Hapus file dari disk secara best-effort. Kegagalan hanya dicatat di log
     * agar tidak membatalkan operasi yang sudah berhasil di database.
     */
    protected function deleteStoredFile(?string $path, array $context = []): void
    {
        if (! $path) {
            return;
        }

        try {
            $disk = Storage::disk($this->disk);

            if ($disk->exists($path) && ! $disk->delete($path)) {
                Log::warning('Failed to delete stored file', $context + ['file_path' => $path]);
            }
        } catch (\Throwable $e) {
            Log::warning('Stored file deletion exception', $context + [
                'file_path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getFilePath(DigitalAsset $asset): ?string
    {
        $path = Storage::disk($this->disk)->path($asset->file_path);

        return file_exists($path) ? $path : null;
    }

    /**
     * Validate file MIME type securely by checking binary content.
     *
     * @throws InvalidArgumentException
     */
    protected function validateSecureMimeType(UploadedFile $file, array $allowedMimeTypes): void
    {
        $clientMimeType = $file->getMimeType();
        $actualMimeType = $this->getActualMimeType($file->getRealPath());

        // Check client-reported MIME type
        if (! in_array($clientMimeType, $allowedMimeTypes)) {
            Log::warning('File upload rejected: Invalid client MIME type', [
                'client_mime' => $clientMimeType,
                'allowed' => $allowedMimeTypes,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            throw new InvalidArgumentException(
                'Tipe file tidak diizinkan. Hanya mendukung: '.implode(', ', $allowedMimeTypes)
            );
        }

        // Check actual binary MIME type
        if (! $actualMimeType || ! in_array($actualMimeType, $allowedMimeTypes)) {
            Log::warning('File upload rejected: Binary MIME type mismatch', [
                'client_mime' => $clientMimeType,
                'actual_mime' => $actualMimeType,
                'allowed' => $allowedMimeTypes,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            throw new InvalidArgumentException(
                'Konten file tidak valid atau tidak sesuai dengan format yang diizinkan.'
            );
        }

        // Extra check: ensure consistency
        if ($clientMimeType !== $actualMimeType) {
            Log::warning('File upload rejected: MIME type mismatch detected', [
                'client_mime' => $clientMimeType,
                'actual_mime' => $actualMimeType,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            throw new InvalidArgumentException(
                'Deteksi ketidaksesuaian tipe file. Kemungkinan file tidak valid atau rusak.'
            );
        }
    }

    /**
     * Check if storage path is writable.
     */
    protected function isStorageWritable(): bool
    {
        try {
            $storagePath = Storage::disk($this->disk)->path($this->basePath);

            // Ensure directory exists
            if (! is_dir($storagePath)) {
                @mkdir($storagePath, 0755, true);
            }

            return is_writable($storagePath);
        } catch (\Exception $e) {
            Log::error('Storage writability check failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check available disk space.
     *
     * @return array ['available' => bool, 'free_space' => bytes]
     */
    protected function checkDiskSpace(int $requiredBytes): array
    {
        try {
            $path = Storage::disk($this->disk)->path('');
            $freeSpace = disk_free_space($path);

            if ($freeSpace === false) {
                Log::warning('Could not determine free disk space');

                // Assume we have space if we can't determine
                return ['available' => true, 'free_space' => PHP_INT_MAX];
            }

            // Keep 10% of disk free as buffer
            $bufferSpace = $freeSpace * 0.1;
            $available = ($freeSpace - $bufferSpace) >= $requiredBytes;

            return [
                'available' => $available,
                'free_space' => $freeSpace,
            ];
        } catch (\Exception $e) {
            Log::error('Disk space check failed', [
                'error' => $e->getMessage(),
            ]);

            // Assume we have space if we can't check
            return ['available' => true, 'free_space' => PHP_INT_MAX];
        }
    }

    /**
     * Get actual MIME type from file binary content using finfo.
     */
    protected function getActualMimeType(string $filePath): ?string
    {
        if (! file_exists($filePath)) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            Log::error('Failed to open finfo for MIME type detection', [
                'file_path' => $filePath,
            ]);

            return null;
        }

        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType ?: null;
    }
}
