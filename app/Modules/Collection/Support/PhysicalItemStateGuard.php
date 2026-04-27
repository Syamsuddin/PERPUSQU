<?php

namespace App\Modules\Collection\Support;

use InvalidArgumentException;

class PhysicalItemStateGuard
{
    /**
     * Valid state transitions per 17_WORKFLOW_STATE_MACHINE.md §12.3
     */
    protected static array $transitions = [
        'available' => ['loaned', 'damaged', 'repair', 'inactive', 'lost'],
        'loaned' => ['available', 'damaged', 'repair', 'lost'],
        'damaged' => ['repair', 'inactive', 'available'],
        'lost' => ['available'],
        'repair' => ['available', 'damaged', 'inactive'],
        'inactive' => ['available', 'repair'],
    ];

    /**
     * Transitions that require special authorization (admin correction)
     */
    protected static array $adminOnlyTransitions = [
        'lost' => ['available'],
        'damaged' => ['available'],
        'inactive' => ['repair'],
        'available' => ['lost'],
    ];

    public static function canTransition(string $from, string $to): bool
    {
        return isset(self::$transitions[$from]) && in_array($to, self::$transitions[$from]);
    }

    public static function assertTransition(string $from, string $to): void
    {
        if (!self::canTransition($from, $to)) {
            throw new InvalidArgumentException(
                "Transisi status tidak valid: {$from} → {$to}"
            );
        }
    }

    public static function isAdminOnly(string $from, string $to): bool
    {
        return isset(self::$adminOnlyTransitions[$from]) && in_array($to, self::$adminOnlyTransitions[$from]);
    }

    /**
     * Get allowed next statuses from current state
     */
    public static function allowedTransitions(string $currentStatus): array
    {
        return self::$transitions[$currentStatus] ?? [];
    }

    /**
     * Get CSS class for status badge
     */
    public static function statusBadgeClass(string $status): string
    {
        return match ($status) {
            'available' => 'success',
            'loaned' => 'primary',
            'damaged' => 'danger',
            'lost' => 'dark',
            'repair' => 'warning',
            'inactive' => 'secondary',
            default => 'light',
        };
    }

    /**
     * Get Indonesian label for status
     */
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'available' => 'Tersedia',
            'loaned' => 'Dipinjam',
            'damaged' => 'Rusak',
            'lost' => 'Hilang',
            'repair' => 'Perbaikan',
            'inactive' => 'Nonaktif',
            default => ucfirst($status),
        };
    }
}
