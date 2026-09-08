<?php

namespace App\Modules\Core\Http\Middleware;

use App\Modules\Core\Services\SystemSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menutup katalog publik (OPAC dan halaman depan) selama masa pemeliharaan.
 *
 * Sengaja TIDAK memakai `php artisan down`: perintah itu menutup seluruh
 * aplikasi termasuk area admin, sehingga petugas yang menyalakannya lewat
 * antarmuka tidak akan punya jalan untuk mematikannya lagi. Di sini hanya
 * permukaan publik yang tertutup, dan pengguna yang sudah masuk tetap dapat
 * membuka OPAC untuk memeriksa hasil pekerjaan sebelum katalog dibuka kembali.
 */
class EnsurePublicCatalogueIsOpen
{
    public function __construct(
        protected SystemSettings $settings,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->settings->maintenanceMode() || $request->user()) {
            return $next($request);
        }

        return response()
            ->view('maintenance', ['appName' => $this->settings->appName()], 503)
            ->header('Retry-After', '3600');
    }
}
