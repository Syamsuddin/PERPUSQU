<?php

namespace App\Modules\Member\Support;

use App\Modules\Member\Models\Member;

class MemberEligibilityResolver
{
    /**
     * Derived state per 17_WORKFLOW_STATE_MACHINE.md §10.1
     *
     * | is_active | is_blocked | Derived State        |
     * |-----------|-----------|----------------------|
     * | 1         | 0         | ACTIVE_READY         |
     * | 1         | 1         | ACTIVE_BLOCKED       |
     * | 0         | 0         | INACTIVE_UNBLOCKED   |
     * | 0         | 1         | INACTIVE_BLOCKED     |
     */
    public static function derivedState(Member $member): string
    {
        if ($member->is_active && ! $member->is_blocked) {
            return 'active_ready';
        }
        if ($member->is_active && $member->is_blocked) {
            return 'active_blocked';
        }
        if (! $member->is_active && ! $member->is_blocked) {
            return 'inactive_unblocked';
        }

        return 'inactive_blocked';
    }

    /**
     * Member eligible for loan only if ACTIVE_READY
     */
    public static function canBorrow(Member $member): bool
    {
        return $member->is_active && ! $member->is_blocked;
    }

    /**
     * Get Indonesian label for derived state
     */
    public static function stateLabel(string $state): string
    {
        return match ($state) {
            'active_ready' => 'Aktif',
            'active_blocked' => 'Aktif (Diblokir)',
            'inactive_unblocked' => 'Nonaktif',
            'inactive_blocked' => 'Nonaktif (Diblokir)',
            default => ucfirst($state),
        };
    }

    /**
     * Get badge class for derived state
     */
    public static function stateBadgeClass(string $state): string
    {
        return match ($state) {
            'active_ready' => 'success',
            'active_blocked' => 'danger',
            'inactive_unblocked' => 'secondary',
            'inactive_blocked' => 'dark',
            default => 'light',
        };
    }

    /**
     * Get member type label
     */
    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'alumni' => 'Alumni',
            'guest' => 'Tamu',
            default => ucfirst($type),
        };
    }
}
