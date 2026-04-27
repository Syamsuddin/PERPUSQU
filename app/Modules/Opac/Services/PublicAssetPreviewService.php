<?php

namespace App\Modules\Opac\Services;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Support\Facades\Storage;

class PublicAssetPreviewService
{
    /**
     * Check if a digital asset is publicly accessible for preview
     */
    public function canPreview(DigitalAsset $asset): bool
    {
        // Must be published + public
        if ($asset->publication_status !== 'published' || !$asset->is_public) {
            return false;
        }

        // Check embargo
        if ($asset->is_embargoed && $asset->embargo_until && $asset->embargo_until->isFuture()) {
            return false;
        }

        return true;
    }

    public function getStreamPath(DigitalAsset $asset): ?string
    {
        $path = Storage::disk('local')->path($asset->file_path);
        return file_exists($path) ? $path : null;
    }
}
