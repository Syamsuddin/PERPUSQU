<?php

namespace App\Modules\Catalog\Policies;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Identity\Models\User;

class BibliographicRecordPolicy
{
    /**
     * Determine if the user can view any bibliographic records.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catalog.view');
    }

    /**
     * Determine if the user can view the bibliographic record.
     */
    public function view(User $user, BibliographicRecord $record): bool
    {
        // User can view if they have catalog.view permission
        // OR if they created the record (and it's draft)
        return $user->can('catalog.view')
            || ($record->created_by === $user->id && $record->publication_status === 'draft');
    }

    /**
     * Determine if the user can create bibliographic records.
     */
    public function create(User $user): bool
    {
        return $user->can('catalog.create');
    }

    /**
     * Determine if the user can update the bibliographic record.
     */
    public function update(User $user, BibliographicRecord $record): bool
    {
        // Super admin can update anything
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // User with catalog.update can update their own drafts
        if ($user->can('catalog.update') && $record->created_by === $user->id) {
            return true;
        }

        // User with catalog.update_any can update any record
        if ($user->can('catalog.update_any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the bibliographic record.
     */
    public function delete(User $user, BibliographicRecord $record): bool
    {
        // Only super admin can delete published records
        if ($record->publication_status === 'published' && ! $user->hasRole('Super Admin')) {
            return false;
        }

        // User can delete if they have permission
        return $user->can('catalog.delete')
            || ($user->can('catalog.delete_own') && $record->created_by === $user->id);
    }

    /**
     * Determine if the user can publish the bibliographic record.
     */
    public function publish(User $user, BibliographicRecord $record): bool
    {
        return $user->can('catalog.publish');
    }

    /**
     * Determine if the user can unpublish the bibliographic record.
     */
    public function unpublish(User $user, BibliographicRecord $record): bool
    {
        return $user->can('catalog.unpublish');
    }

    /**
     * Determine if the user can archive the bibliographic record.
     */
    public function archive(User $user, BibliographicRecord $record): bool
    {
        return $user->can('catalog.publish'); // Same permission as publish
    }

    /**
     * Determine if the user can reactivate the bibliographic record.
     */
    public function reactivate(User $user, BibliographicRecord $record): bool
    {
        return $user->can('catalog.publish'); // Same permission as publish
    }
}
