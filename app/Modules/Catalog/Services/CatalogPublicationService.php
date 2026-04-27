<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Support\BibliographicRecordStateGuard;
use InvalidArgumentException;

class CatalogPublicationService
{
    /**
     * Publish a record: DRAFT/UNPUBLISHED → PUBLISHED
     */
    public function publish(BibliographicRecord $record): BibliographicRecord
    {
        // Check guard rules for publish
        $errors = BibliographicRecordStateGuard::canPublish($record);
        if (! empty($errors)) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        BibliographicRecordStateGuard::assertTransition($record->publication_status, 'published');

        $record->update(['publication_status' => 'published']);

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->withProperties(['from' => $record->getOriginal('publication_status'), 'to' => 'published'])
            ->log('Katalog dipublikasikan: '.$record->title);

        return $record;
    }

    /**
     * Unpublish a record: PUBLISHED → UNPUBLISHED
     */
    public function unpublish(BibliographicRecord $record): BibliographicRecord
    {
        BibliographicRecordStateGuard::assertTransition($record->publication_status, 'unpublished');

        $record->update(['publication_status' => 'unpublished']);

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->withProperties(['from' => 'published', 'to' => 'unpublished'])
            ->log('Katalog di-unpublish: '.$record->title);

        return $record;
    }

    /**
     * Archive a record: DRAFT/PUBLISHED/UNPUBLISHED → ARCHIVED
     */
    public function archive(BibliographicRecord $record): BibliographicRecord
    {
        BibliographicRecordStateGuard::assertTransition($record->publication_status, 'archived');

        $record->update(['publication_status' => 'archived']);

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->withProperties(['from' => $record->getOriginal('publication_status'), 'to' => 'archived'])
            ->log('Katalog diarsipkan: '.$record->title);

        return $record;
    }

    /**
     * Reactivate an archived record: ARCHIVED → DRAFT
     */
    public function reactivate(BibliographicRecord $record): BibliographicRecord
    {
        BibliographicRecordStateGuard::assertTransition($record->publication_status, 'draft');

        $record->update(['publication_status' => 'draft']);

        activity('catalog')
            ->causedBy(auth()->user())
            ->performedOn($record)
            ->withProperties(['from' => 'archived', 'to' => 'draft'])
            ->log('Katalog direaktivasi: '.$record->title);

        return $record;
    }
}
