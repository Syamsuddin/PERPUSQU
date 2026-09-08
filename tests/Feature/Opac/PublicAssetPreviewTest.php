<?php

namespace Tests\Feature\Opac;

use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Opac\Services\PublicAssetPreviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Gerbang akses berkas publik. Embargo di sini adalah janji hukum kepada
 * penulis, jadi setiap kombinasi flag diuji.
 */
class PublicAssetPreviewTest extends TestCase
{
    use RefreshDatabase;

    private PublicAssetPreviewService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PublicAssetPreviewService::class);
    }

    #[Test]
    public function a_published_public_asset_without_embargo_may_be_previewed(): void
    {
        $asset = DigitalAsset::factory()->published()->create();

        $this->assertTrue($this->service->canPreview($asset));
    }

    #[Test]
    public function a_draft_asset_may_not_be_previewed(): void
    {
        $this->assertFalse($this->service->canPreview(DigitalAsset::factory()->create()));
    }

    #[Test]
    public function a_published_but_non_public_asset_may_not_be_previewed(): void
    {
        $asset = DigitalAsset::factory()->create([
            'publication_status' => 'published',
            'is_public' => false,
        ]);

        $this->assertFalse($this->service->canPreview($asset));
    }

    #[Test]
    public function an_asset_under_a_live_embargo_may_not_be_previewed(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoed()->create();

        $this->assertFalse($this->service->canPreview($asset));
    }

    /**
     * Embargo yang tanggalnya sudah lewat tidak lagi menutup akses, walaupun
     * penanda `is_embargoed` masih menyala.
     */
    #[Test]
    public function an_expired_embargo_no_longer_blocks_the_preview(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoExpired()->create();

        $this->assertTrue($this->service->canPreview($asset));
    }

    #[Test]
    public function an_embargo_flag_without_a_date_does_not_block_the_preview(): void
    {
        $asset = DigitalAsset::factory()->published()->create([
            'is_embargoed' => true,
            'embargo_until' => null,
        ]);

        $this->assertTrue($this->service->canPreview($asset));
    }

    #[Test]
    public function the_preview_endpoint_refuses_a_private_asset_with_403(): void
    {
        $asset = DigitalAsset::factory()->create();

        $this->get(route('opac.asset.preview', $asset->id))->assertForbidden();
    }

    #[Test]
    public function the_preview_endpoint_refuses_an_embargoed_asset_with_403(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoed()->create();

        $this->get(route('opac.asset.preview', $asset->id))->assertForbidden();
    }

    /**
     * Aset yang boleh diakses tetapi berkasnya hilang dari disk harus menjawab
     * 404 — bukan membocorkan jalur server lewat pesan error.
     */
    #[Test]
    public function an_allowed_asset_whose_file_is_missing_yields_a_404(): void
    {
        $asset = DigitalAsset::factory()->published()->create(['file_path' => 'digital_assets/hilang.pdf']);

        $this->get(route('opac.asset.preview', $asset->id))->assertNotFound();
    }

    #[Test]
    public function an_allowed_asset_with_a_real_file_is_streamed_inline(): void
    {
        $asset = DigitalAsset::factory()->published()->create([
            'file_path' => 'digital_assets/contoh.pdf',
            'mime_type' => 'application/pdf',
            'original_file_name' => 'panduan-perpustakaan.pdf',
        ]);
        Storage::disk('local')->put('digital_assets/contoh.pdf', '%PDF-1.4 konten uji');

        $response = $this->get(route('opac.asset.preview', $asset->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('panduan-perpustakaan.pdf', $response->headers->get('Content-Disposition'));

        Storage::disk('local')->delete('digital_assets/contoh.pdf');
    }

    #[Test]
    public function a_missing_asset_id_yields_a_404(): void
    {
        $this->get(route('opac.asset.preview', 999999))->assertNotFound();
    }

    #[Test]
    public function a_soft_deleted_asset_is_no_longer_reachable(): void
    {
        $asset = DigitalAsset::factory()->published()->create();
        $asset->delete();

        $this->get(route('opac.asset.preview', $asset->id))->assertNotFound();
    }

    /**
     * Saklar tingkat instalasi: menutup pratinjau untuk SELURUH aset sekaligus,
     * tanpa perlu mengubah status publikasi satu per satu. Berguna saat ada
     * masalah lisensi atau keluhan hak cipta yang harus ditangani cepat.
     */
    #[Test]
    public function turning_off_public_preview_closes_every_asset_at_once(): void
    {
        $asset = DigitalAsset::factory()->published()->create();
        $this->assertTrue($this->service->canPreview($asset));

        $this->disablePublicPreview();

        $this->assertFalse($this->service->canPreview($asset->fresh()));
        $this->get(route('opac.asset.preview', $asset->id))->assertForbidden();
    }

    #[Test]
    public function an_otherwise_available_file_stops_being_served_when_preview_is_off(): void
    {
        $asset = DigitalAsset::factory()->published()->create([
            'file_path' => 'digital_assets/tersedia.pdf',
        ]);
        Storage::disk('local')->put('digital_assets/tersedia.pdf', '%PDF-1.4 konten uji');

        $this->get(route('opac.asset.preview', $asset->id))->assertOk();

        $this->disablePublicPreview();

        $this->get(route('opac.asset.preview', $asset->id))->assertForbidden();

        Storage::disk('local')->delete('digital_assets/tersedia.pdf');
    }

    #[Test]
    public function the_release_migration_leaves_public_preview_on(): void
    {
        $this->assertTrue(app(SystemSettings::class)->publicPreviewEnabled());
    }

    private function disablePublicPreview(): void
    {
        SystemSetting::query()->updateOrCreate(
            ['key' => 'public_preview_enabled'],
            ['value' => 'false']
        );
        app(SystemSettings::class)->refresh();
    }
}
