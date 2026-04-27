<?php

namespace App\Modules\Circulation\Policies;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Identity\Models\User;

class LoanPolicy
{
    /**
     * Determine if the user can view any loans.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('circulation.view');
    }

    /**
     * Determine if the user can view the loan.
     */
    public function view(User $user, Loan $loan): bool
    {
        return $user->can('circulation.view');
    }

    /**
     * Determine if the user can create loans (checkout).
     */
    public function create(User $user): bool
    {
        return $user->can('circulation.checkout');
    }

    /**
     * Determine if the user can process returns.
     */
    public function return(User $user, Loan $loan): bool
    {
        // Only active loans can be returned
        if ($loan->loan_status !== 'active') {
            return false;
        }

        return $user->can('circulation.return');
    }

    /**
     * Determine if the user can renew the loan.
     */
    public function renew(User $user, Loan $loan): bool
    {
        // Only active loans can be renewed
        if ($loan->loan_status !== 'active') {
            return false;
        }

        return $user->can('circulation.renew');
    }

    /**
     * Determine if the user can manage fines.
     */
    public function manageFines(User $user): bool
    {
        return $user->can('circulation.manage_fines');
    }

    /**
     * Determine if the user can waive fines.
     */
    public function waiveFines(User $user, Loan $loan): bool
    {
        // Only users with specific permission can waive fines
        return $user->can('circulation.waive_fines') || $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can view loan history.
     */
    public function viewHistory(User $user): bool
    {
        return $user->can('circulation.view_history') || $user->can('circulation.view');
    }

    /**
     * Determine if the user can force return (override normal rules).
     */
    public function forceReturn(User $user, Loan $loan): bool
    {
        return $user->can('circulation.force_return') || $user->hasRole('super-admin');
    }
}
