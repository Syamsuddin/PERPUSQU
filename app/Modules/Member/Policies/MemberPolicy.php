<?php

namespace App\Modules\Member\Policies;

use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;

class MemberPolicy
{
    /**
     * Determine if the user can view any members.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('members.view');
    }

    /**
     * Determine if the user can view the member.
     */
    public function view(User $user, Member $member): bool
    {
        return $user->can('members.view');
    }

    /**
     * Determine if the user can create members.
     */
    public function create(User $user): bool
    {
        return $user->can('members.create');
    }

    /**
     * Determine if the user can update the member.
     */
    public function update(User $user, Member $member): bool
    {
        return $user->can('members.update');
    }

    /**
     * Determine if the user can delete the member.
     */
    public function delete(User $user, Member $member): bool
    {
        // Cannot delete member with active loans
        if ($member->loans()->where('loan_status', 'active')->exists()) {
            return false;
        }

        return $user->can('members.delete');
    }

    /**
     * Determine if the user can activate the member.
     */
    public function activate(User $user, Member $member): bool
    {
        return $user->can('members.activate');
    }

    /**
     * Determine if the user can deactivate the member.
     */
    public function deactivate(User $user, Member $member): bool
    {
        return $user->can('members.deactivate') || $user->can('members.activate');
    }

    /**
     * Determine if the user can block the member.
     */
    public function block(User $user, Member $member): bool
    {
        return $user->can('members.block') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can unblock the member.
     */
    public function unblock(User $user, Member $member): bool
    {
        return $user->can('members.block') || $user->hasRole('Super Admin');
    }

    /**
     * Determine if the user can view member's loan history.
     */
    public function viewLoanHistory(User $user, Member $member): bool
    {
        return $user->can('members.view') || $user->can('circulation.view');
    }

    /**
     * Determine if the user can view member's fine history.
     */
    public function viewFineHistory(User $user, Member $member): bool
    {
        return $user->can('members.view') || $user->can('circulation.manage_fines');
    }

    /**
     * Determine if the user can export member data.
     */
    public function export(User $user): bool
    {
        return $user->can('members.export') || $user->hasRole('Super Admin');
    }
}
