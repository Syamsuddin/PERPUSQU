<?php

namespace App\Modules\DigitalRepository\Services;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Pagination\LengthAwarePaginator;

class DigitalAssetService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return DigitalAsset::with(['bibliographicRecord', 'uploadedBy'])
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['asset_type']), fn ($q) => $q->where('asset_type', $filters['asset_type']))
            ->when(isset($filters['publication_status']), fn ($q) => $q->where('publication_status', $filters['publication_status']))
            ->when(isset($filters['is_public']), fn ($q) => $q->where('is_public', $filters['is_public']))
            ->when(isset($filters['ocr_status']), fn ($q) => $q->where('ocr_status', $filters['ocr_status']))
            ->when(isset($filters['index_status']), fn ($q) => $q->where('index_status', $filters['index_status']))
            ->when(isset($filters['bibliographic_record_id']), fn ($q) => $q->where('bibliographic_record_id', $filters['bibliographic_record_id']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function findWithRelations(int $id): DigitalAsset
    {
        return DigitalAsset::with(['bibliographicRecord', 'uploadedBy', 'accessRules', 'ocrText'])
            ->findOrFail($id);
    }

    public function updateMetadata(DigitalAsset $asset, array $data): DigitalAsset
    {
        $asset->update($data);

        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('Metadata aset digital diperbarui: '.($asset->title ?: $asset->original_file_name));

        return $asset;
    }

    public function publish(DigitalAsset $asset): DigitalAsset
    {
        if ($asset->publication_status === 'published') {
            throw new \InvalidArgumentException('Aset sudah dipublikasikan.');
        }
        $asset->update(['publication_status' => 'published']);

        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('Aset digital dipublikasikan: '.($asset->title ?: $asset->original_file_name));

        return $asset;
    }

    public function unpublish(DigitalAsset $asset): DigitalAsset
    {
        if ($asset->publication_status !== 'published') {
            throw new \InvalidArgumentException('Hanya aset yang dipublikasikan yang dapat di-unpublish.');
        }
        $asset->update(['publication_status' => 'unpublished']);

        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('Aset digital di-unpublish: '.($asset->title ?: $asset->original_file_name));

        return $asset;
    }

    public function archive(DigitalAsset $asset): DigitalAsset
    {
        $asset->update(['publication_status' => 'archived']);

        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('Aset digital diarsipkan: '.($asset->title ?: $asset->original_file_name));

        return $asset;
    }

    public function delete(DigitalAsset $asset): void
    {
        // Soft delete — file remains on storage
        activity('digital_repository')
            ->causedBy(auth()->user())
            ->performedOn($asset)
            ->log('Aset digital dihapus: '.($asset->title ?: $asset->original_file_name));

        $asset->delete();
    }
}
