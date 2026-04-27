<?php

namespace App\Modules\Catalog\Support;

use App\Modules\Catalog\Models\BibliographicRecord;
use InvalidArgumentException;

class BibliographicRecordStateGuard
{
    /**
     * Valid state transitions per 17_WORKFLOW_STATE_MACHINE.md §11.4
     */
    protected static array $transitions = [
        'draft' => ['published', 'archived'],
        'published' => ['unpublished', 'archived'],
        'unpublished' => ['published', 'archived'],
        'archived' => ['draft', 'unpublished'], // reactivate
    ];

    /**
     * Check if a transition is valid
     */
    public static function canTransition(string $from, string $to): bool
    {
        return isset(self::$transitions[$from]) && in_array($to, self::$transitions[$from]);
    }

    /**
     * Assert the transition is valid, throw if not
     */
    public static function assertTransition(string $from, string $to): void
    {
        if (! self::canTransition($from, $to)) {
            throw new InvalidArgumentException(
                "Transisi status tidak valid: {$from} → {$to}"
            );
        }
    }

    /**
     * Guard for publish: minimum metadata must be met
     * Per §11.5: title, collection_type_id, min 1 author, status != archived
     */
    public static function canPublish(BibliographicRecord $record): array
    {
        $errors = [];

        if (empty($record->title)) {
            $errors[] = 'Judul wajib diisi.';
        }

        if (empty($record->collection_type_id)) {
            $errors[] = 'Jenis koleksi wajib diisi.';
        }

        if ($record->authors()->count() === 0) {
            $errors[] = 'Minimal satu pengarang wajib terdaftar.';
        }

        if ($record->publication_status === 'archived') {
            $errors[] = 'Record yang sudah diarsipkan harus direaktivasi terlebih dahulu.';
        }

        return $errors;
    }

    /**
     * Get allowed actions for current state
     */
    public static function allowedActions(string $currentStatus): array
    {
        return match ($currentStatus) {
            'draft' => ['edit', 'publish', 'archive'],
            'published' => ['edit', 'unpublish', 'archive'],
            'unpublished' => ['edit', 'publish', 'archive'],
            'archived' => ['reactivate'],
            default => [],
        };
    }
}
