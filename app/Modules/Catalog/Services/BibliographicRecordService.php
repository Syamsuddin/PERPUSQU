<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BibliographicRecordService
{
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

        // Handle cover upload
        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            $data['cover_path'] = $data['cover']->store('catalog/covers', 'public');
            unset($data['cover']);
        }

        $authorIds = $data['author_ids'] ?? [];
        $subjectIds = $data['subject_ids'] ?? [];
        unset($data['author_ids'], $data['subject_ids']);

        $record = BibliographicRecord::create($data);

        // Sync pivot relations
        if (! empty($authorIds)) {
            $record->authors()->sync($authorIds);
        }
        if (! empty($subjectIds)) {
            $record->subjects()->sync($subjectIds);
        }

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog dibuat: '.$record->title);

        return $record;
    }

    public function update(BibliographicRecord $record, array $data): BibliographicRecord
    {
        // Handle cover upload
        if (isset($data['cover']) && $data['cover'] instanceof UploadedFile) {
            // Delete old cover if exists
            if ($record->cover_path && \Storage::disk('public')->exists($record->cover_path)) {
                \Storage::disk('public')->delete($record->cover_path);
            }
            $data['cover_path'] = $data['cover']->store('catalog/covers', 'public');
            unset($data['cover']);
        }

        $authorIds = $data['author_ids'] ?? [];
        $subjectIds = $data['subject_ids'] ?? [];
        unset($data['author_ids'], $data['subject_ids']);

        $record->update($data);

        // Sync pivot relations
        $record->authors()->sync($authorIds);
        $record->subjects()->sync($subjectIds);

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog diperbarui: '.$record->title);

        return $record;
    }

    public function delete(BibliographicRecord $record): void
    {
        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->log('Katalog dihapus: '.$record->title);

        // Delete cover
        if ($record->cover_path && \Storage::disk('public')->exists($record->cover_path)) {
            \Storage::disk('public')->delete($record->cover_path);
        }

        $record->authors()->detach();
        $record->subjects()->detach();
        $record->delete();
    }

    public function findWithRelations(int $id): BibliographicRecord
    {
        return BibliographicRecord::with([
            'publisher', 'language', 'classification', 'collectionType',
            'authors', 'subjects', 'physicalItems', 'digitalAssets',
        ])->findOrFail($id);
    }
}
