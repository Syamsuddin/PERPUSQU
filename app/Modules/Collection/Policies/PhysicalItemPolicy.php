<?php

namespace App\Modules\Collection\Policies;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Identity\Models\User;

class PhysicalItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('collections.view');
    }

    public function view(User $user, PhysicalItem $item): bool
    {
        return $user->can('collections.view');
    }

    public function create(User $user): bool
    {
        return $user->can('collections.create');
    }

    public function update(User $user, PhysicalItem $item): bool
    {
        return $user->can('collections.update') || $user->hasRole('Super Admin');
    }

    public function delete(User $user, PhysicalItem $item): bool
    {
        // Cannot delete if loaned
        if ($item->item_status === 'loaned') {
            return false;
        }

        return $user->can('collections.delete') || $user->hasRole('Super Admin');
    }

    public function changeStatus(User $user, PhysicalItem $item): bool
    {
        return $user->can('collections.change_status') || $user->hasRole('Super Admin');
    }
}
