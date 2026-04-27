<?php

namespace App\Modules\DigitalRepository\Policies;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Models\User;

class DigitalAssetPolicy
{
    /**
     * Determine if the user can view any digital assets.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('digital_assets.view');
    }

    /**
     * Determine if the user can view the digital asset.
     */
    public function view(User $user, DigitalAsset $asset): bool
    {
        // Public published assets can be viewed by anyone with digital_assets.view permission
        if ($asset->is_public && $asset->publication_status === 'published') {
            return $user->can('digital_assets.view');
        }

        // Super admin can view everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Uploader can view their own assets
        if ($asset->uploaded_by === $user->id) {
            return true;
        }

        // User with special permission can view all
        if ($user->can('digital_assets.view_all')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create digital assets.
     */
    public function create(User $user): bool
    {
        return $user->can('digital_assets.create');
    }

    /**
     * Determine if the user can update the digital asset.
     */
    public function update(User $user, DigitalAsset $asset): bool
    {
        // Super admin can update anything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Uploader can update their own assets
        if ($user->can('digital_assets.update') && $asset->uploaded_by === $user->id) {
            return true;
        }

        // User with update_any permission can update any asset
        if ($user->can('digital_assets.update_any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the digital asset.
     */
    public function delete(User $user, DigitalAsset $asset): bool
    {
        // Only super admin can delete published assets
        if ($asset->publication_status === 'published' && ! $user->hasRole('super-admin')) {
            return false;
        }

        // User can delete their own unpublished assets
        if ($user->can('digital_assets.delete') && $asset->uploaded_by === $user->id) {
            return true;
        }

        // Super admin can delete anything
        return $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can publish the digital asset.
     */
    public function publish(User $user, DigitalAsset $asset): bool
    {
        return $user->can('digital_assets.publish');
    }

    /**
     * Determine if the user can unpublish the digital asset.
     */
    public function unpublish(User $user, DigitalAsset $asset): bool
    {
        return $user->can('digital_assets.publish'); // Same permission
    }

    /**
     * Determine if the user can archive the digital asset.
     */
    public function archive(User $user, DigitalAsset $asset): bool
    {
        return $user->can('digital_assets.publish'); // Same permission
    }

    /**
     * Determine if the user can run OCR on the digital asset.
     */
    public function runOcr(User $user, DigitalAsset $asset): bool
    {
        return $user->can('digital_assets.ocr');
    }

    /**
     * Determine if the user can access embargoed assets.
     */
    public function accessEmbargoed(User $user, DigitalAsset $asset): bool
    {
        // Uploader can always access their own embargoed assets
        if ($asset->uploaded_by === $user->id) {
            return true;
        }

        // User with special permission can access embargoed assets
        return $user->can('digital_assets.access_embargoed');
    }

    /**
     * Determine if the user can download the digital asset file.
     */
    public function download(User $user, DigitalAsset $asset): bool
    {
        // Same rules as view
        return $this->view($user, $asset);
    }
}
