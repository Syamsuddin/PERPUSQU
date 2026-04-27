<?php

namespace App\Modules\DigitalRepository\Services;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class DigitalAssetUploadService
{
    protected string $disk = 'local';

    protected string $basePath = 'digital_assets';

    public function upload(UploadedFile $file, array $metadata): DigitalAsset
    {
        // Validate file upload
        if (! $file->isValid()) {
            throw new InvalidArgumentException('File upload tidak valid.');
        }

        // Security: Verify MIME type matches expected PDF
        $this->validateSecureMimeType($file, ['application/pdf']);

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileName = Str::uuid().'.'.$extension;
        $filePath = $this->basePath.'/'.now()->format('Y/m');

        // Store file
        $storedPath = $file->storeAs($filePath, $fileName, $this->disk);

        if (! $storedPath) {
            throw new InvalidArgumentException('Gagal menyimpan file.');
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
    }

    public function replaceFile(DigitalAsset $asset, UploadedFile $file): DigitalAsset
    {
        // Validate file upload
        if (! $file->isValid()) {
            throw new InvalidArgumentException('File upload tidak valid.');
        }

        // Security: Verify MIME type matches expected PDF
        $this->validateSecureMimeType($file, ['application/pdf']);

        // Delete old file
        if (Storage::disk($this->disk)->exists($asset->file_path)) {
            Storage::disk($this->disk)->delete($asset->file_path);
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid().'.'.$extension;
        $filePath = $this->basePath.'/'.now()->format('Y/m');
        $storedPath = $file->storeAs($filePath, $fileName, $this->disk);

        if (! $storedPath) {
            throw new InvalidArgumentException('Gagal menyimpan file pengganti.');
        }

        $checksum = hash_file('sha256', $file->getRealPath());

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

        return $asset;
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
