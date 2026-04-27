<?php

namespace App\Modules\DigitalRepository\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\DigitalRepository\Http\Requests\StoreDigitalAssetRequest;
use App\Modules\DigitalRepository\Http\Requests\UpdateDigitalAssetRequest;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Services\DigitalAssetService;
use App\Modules\DigitalRepository\Services\DigitalAssetUploadService;
use App\Modules\DigitalRepository\Services\OcrProcessingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DigitalAssetController extends Controller
{
    public function __construct(
        protected DigitalAssetService $service,
        protected DigitalAssetUploadService $uploadService,
        protected OcrProcessingService $ocrService,
    ) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());

        return view('modules.digital-repository.index', compact('items'));
    }

    public function create()
    {
        $records = BibliographicRecord::orderBy('title')->get();

        return view('modules.digital-repository.create', compact('records'));
    }

    public function store(StoreDigitalAssetRequest $request)
    {
        $this->authorize('create', DigitalAsset::class);

        $asset = $this->uploadService->upload($request->file('file'), $request->validated());

        return redirect()->route('admin.digital-assets.index')->with('success', 'Aset digital berhasil diunggah.');
    }

    public function show(DigitalAsset $digital_asset)
    {
        $this->authorize('view', $digital_asset);

        $asset = $this->service->findWithRelations($digital_asset->id);

        return view('modules.digital-repository.show', compact('asset'));
    }

    public function edit(DigitalAsset $digital_asset)
    {
        $records = BibliographicRecord::orderBy('title')->get();

        return view('modules.digital-repository.edit', ['asset' => $digital_asset, 'records' => $records]);
    }

    public function update(UpdateDigitalAssetRequest $request, DigitalAsset $digital_asset)
    {
        $this->authorize('update', $digital_asset);

        $data = $request->validated();

        // Handle file replacement
        if ($request->hasFile('replacement_file')) {
            $this->uploadService->replaceFile($digital_asset, $request->file('replacement_file'));
            unset($data['replacement_file']);
        }

        $this->service->updateMetadata($digital_asset, $data);

        return redirect()->route('admin.digital-assets.show', $digital_asset)->with('success', 'Aset digital berhasil diperbarui.');
    }

    public function destroy(DigitalAsset $digital_asset)
    {
        $this->authorize('delete', $digital_asset);

        $this->service->delete($digital_asset);

        return redirect()->route('admin.digital-assets.index')->with('success', 'Aset digital berhasil dihapus.');
    }

    public function publish(DigitalAsset $digital_asset)
    {
        $this->authorize('publish', $digital_asset);

        try {
            $this->service->publish($digital_asset);

            return back()->with('success', 'Aset berhasil dipublikasikan.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function unpublish(DigitalAsset $digital_asset)
    {
        try {
            $this->service->unpublish($digital_asset);

            return back()->with('success', 'Aset berhasil di-unpublish.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function archive(DigitalAsset $digital_asset)
    {
        $this->service->archive($digital_asset);

        return back()->with('success', 'Aset berhasil diarsipkan.');
    }

    public function runOcr(DigitalAsset $digital_asset)
    {
        $this->authorize('runOcr', $digital_asset);

        try {
            $this->ocrService->requestOcr($digital_asset);

            return back()->with('success', 'OCR berhasil diminta. Proses akan berjalan di background.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function preview(DigitalAsset $digital_asset)
    {
        // Authorization: Check if user has access to this file
        $this->authorizeFileAccess($digital_asset);

        // Get file path
        $filePath = $this->uploadService->getFilePath($digital_asset);
        if (! $filePath) {
            Log::warning('File preview failed: File not found', [
                'asset_id' => $digital_asset->id,
                'file_path' => $digital_asset->file_path,
                'user_id' => auth()->id(),
            ]);
            abort(404, 'File tidak ditemukan.');
        }

        // Security: Validate file path to prevent directory traversal
        $this->validateSecureFilePath($filePath, $digital_asset);

        // Security: Sanitize filename for Content-Disposition header
        $safeFilename = $this->sanitizeFilename($digital_asset->original_file_name);

        // Return file with security headers
        return response()->file($filePath, [
            'Content-Type' => $digital_asset->mime_type,
            'Content-Disposition' => 'inline; filename="'.$safeFilename.'"',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Authorize file access based on user permissions and file visibility.
     *
     * @throws AuthorizationException
     */
    protected function authorizeFileAccess(DigitalAsset $asset): void
    {
        // Public files can be accessed by anyone (even guests)
        if ($asset->is_public && $asset->publication_status === 'published') {
            return;
        }

        // Non-public files require authentication
        if (! auth()->check()) {
            abort(403, 'Anda harus login untuk mengakses file ini.');
        }

        // Check embargo status
        if ($asset->is_embargoed && $asset->embargo_until && now()->lt($asset->embargo_until)) {
            // Only uploader or users with special permission can access embargoed files
            $canAccessEmbargoed = auth()->user()->can('digital_assets.access_embargoed');
            if (auth()->id() !== $asset->uploaded_by && ! $canAccessEmbargoed) {
                $embargoDate = $asset->embargo_until->format('d/m/Y');
                abort(403, 'File ini sedang dalam embargo hingga '.$embargoDate);
            }
        }

        // Check if user has general access permission
        if (! auth()->user()->can('digital_assets.view')) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
        }
    }

    /**
     * Validate file path to prevent directory traversal attacks.
     *
     * @throws HttpException
     */
    protected function validateSecureFilePath(string $filePath, DigitalAsset $asset): void
    {
        // Get real path (resolves symlinks and relative paths)
        $realPath = realpath($filePath);

        if ($realPath === false) {
            Log::error('File path validation failed: realpath() returned false', [
                'asset_id' => $asset->id,
                'file_path' => $filePath,
                'user_id' => auth()->id(),
            ]);
            abort(403, 'Invalid file path.');
        }

        // Get allowed base path
        $allowedBasePath = realpath(Storage::disk('local')->path('digital_assets'));

        if ($allowedBasePath === false) {
            Log::error('Security check failed: Cannot resolve base storage path', [
                'storage_path' => Storage::disk('local')->path('digital_assets'),
            ]);
            abort(500, 'Storage configuration error.');
        }

        // Ensure file is within allowed directory
        if (strpos($realPath, $allowedBasePath) !== 0) {
            Log::alert('SECURITY: Directory traversal attempt detected', [
                'asset_id' => $asset->id,
                'requested_path' => $filePath,
                'real_path' => $realPath,
                'allowed_base' => $allowedBasePath,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            abort(403, 'Access denied: Invalid file path.');
        }

        // Additional check: Verify file matches asset record
        if (basename($realPath) !== $asset->file_name) {
            Log::warning('File path mismatch detected', [
                'asset_id' => $asset->id,
                'expected_filename' => $asset->file_name,
                'actual_filename' => basename($realPath),
                'user_id' => auth()->id(),
            ]);
            abort(403, 'File integrity check failed.');
        }
    }

    /**
     * Sanitize filename to prevent header injection and XSS.
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove any control characters and special chars that could cause issues
        $filename = preg_replace('/[^\w\s\-\.\(\)]/', '_', $filename);

        // Remove any leading/trailing dots or spaces
        $filename = trim($filename, '. ');

        // Limit length
        if (strlen($filename) > 255) {
            $filename = substr($filename, 0, 255);
        }

        // If sanitization resulted in empty filename, use default
        if (empty($filename)) {
            $filename = 'document.pdf';
        }

        return $filename;
    }
}
