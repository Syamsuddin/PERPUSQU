<?php

namespace App\Modules\DigitalRepository\Console;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Console\Command;

/**
 * Membersihkan penanda embargo yang masa berlakunya sudah lewat.
 *
 * Akses publik sendiri sudah benar tanpa perintah ini — PublicAssetPreviewService
 * membandingkan `embargo_until` dengan waktu sekarang. Yang diperbaiki di sini
 * adalah kejujuran layar pengelolaan: tanpa pembersihan, aset yang embargonya
 * sudah berakhir tetap tampil bertanda "Embargo" selamanya, sehingga pustakawan
 * mengira aksesnya masih tertutup padahal sudah terbuka.
 */
class ReleaseExpiredEmbargoesCommand extends Command
{
    protected $signature = 'library:release-expired-embargoes';

    protected $description = 'Lepas penanda embargo pada aset digital yang masa embargonya sudah lewat';

    public function handle(): int
    {
        $expired = DigitalAsset::query()
            ->where('is_embargoed', true)
            ->whereNotNull('embargo_until')
            ->where('embargo_until', '<=', now())
            ->get();

        foreach ($expired as $asset) {
            $asset->update(['is_embargoed' => false]);

            activity('digital_repository')
                ->performedOn($asset)
                ->withProperties(['embargo_until' => $asset->embargo_until?->toDateTimeString()])
                ->log('Embargo berakhir: '.($asset->title ?: $asset->original_file_name));
        }

        $this->info($expired->count().' aset dilepas dari embargo.');

        return self::SUCCESS;
    }
}
