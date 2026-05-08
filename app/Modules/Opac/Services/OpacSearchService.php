<?php

namespace App\Modules\Opac\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use Illuminate\Pagination\LengthAwarePaginator;

class OpacSearchService
{
    /**
     * Search public catalog — only PUBLISHED + is_public records
     * Fallback: MySQL LIKE (Meilisearch integration ready)
     */
    public function search(array $filters): LengthAwarePaginator
    {
        return BibliographicRecord::with(['authors', 'publisher', 'language', 'classification', 'collectionType'])
            ->published()
            ->public()
            ->when(!empty($filters['keyword']), function ($q) use ($filters) {
                $kw = $filters['keyword'];
                $q->where(fn ($sub) => $sub
                    ->where('title', 'like', "%{$kw}%")
                    ->orWhere('isbn', 'like', "%{$kw}%")
                    ->orWhere('keywords', 'like', "%{$kw}%")
                    ->orWhereHas('authors', fn ($aq) => $aq->where('name', 'like', "%{$kw}%"))
                );
            })
            ->when(!empty($filters['collection_type_id']), fn ($q) => $q->where('collection_type_id', $filters['collection_type_id']))
            ->when(!empty($filters['language_id']), fn ($q) => $q->where('language_id', $filters['language_id']))
            ->when(!empty($filters['classification_id']), fn ($q) => $q->where('classification_id', $filters['classification_id']))
            ->when(!empty($filters['publication_year']), fn ($q) => $q->where('publication_year', $filters['publication_year']))
            ->latest('created_at')
            ->paginate($filters['per_page'] ?? 12);
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
    public function getLatestRecords(int $limit = 8): \Illuminate\Database\Eloquent\Collection
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
            'total_items' => \App\Modules\Collection\Models\PhysicalItem::where('item_status', 'available')->count(),
            'total_digital' => \App\Modules\DigitalRepository\Models\DigitalAsset::where('publication_status', 'published')->where('is_public', true)->count(),
        ];
    }
}
