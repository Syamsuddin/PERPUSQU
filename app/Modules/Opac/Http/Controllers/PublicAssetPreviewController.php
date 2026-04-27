<?php

namespace App\Modules\Opac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Opac\Services\PublicAssetPreviewService;

class PublicAssetPreviewController extends Controller
{
    public function __construct(protected PublicAssetPreviewService $previewService) {}

    public function preview(int $id)
    {
        $asset = DigitalAsset::findOrFail($id);

        if (!$this->previewService->canPreview($asset)) {
            abort(403, 'Aset ini tidak tersedia untuk akses publik.');
        }

        $filePath = $this->previewService->getStreamPath($asset);
        if (!$filePath) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($filePath, [
            'Content-Type' => $asset->mime_type,
            'Content-Disposition' => 'inline; filename="' . $asset->original_file_name . '"',
        ]);
    }
}
