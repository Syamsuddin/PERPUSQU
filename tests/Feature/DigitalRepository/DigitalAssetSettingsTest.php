<?php

namespace Tests\Feature\DigitalRepository;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\DigitalRepository\Http\Requests\StoreDigitalAssetRequest;
use App\Modules\DigitalRepository\Http\Requests\UpdateDigitalAssetRequest;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Services\DigitalAssetUploadService;
use App\Modules\DigitalRepository\Services\OcrProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Dua aturan repositori digital yang kini datang dari Aturan Operasional:
 * batas ukuran unggahan dan saklar OCR.
 */
class DigitalAssetSettingsTest extends TestCase
{
    use RefreshDatabase;

    private string $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = storage_path('framework/testing/digital-settings');

        if (! is_dir($this->workspace)) {
            mkdir($this->workspace, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->workspace.'/*') ?: [] as $file) {
            @unlink($file);
        }
        parent::tearDown();
    }

    private function setSetting(string $key, string $value): void
    {
        SystemSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        app(SystemSettings::class)->refresh();
    }

    /**
     * PDF sungguhan dengan isi yang dipadatkan sampai ukuran tertentu; berkas
     * kosong hasil UploadedFile::fake() tidak pernah lolos pemeriksaan MIME biner.
     */
    private function pdfOfSize(int $bytes): UploadedFile
    {
        $path = $this->workspace.'/'.bin2hex(random_bytes(6)).'.pdf';
        $header = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n";
        file_put_contents($path, $header.str_repeat('0', max(0, $bytes - strlen($header) - 7))."\n%%EOF\n");

        return new UploadedFile($path, 'dokumen.pdf', 'application/pdf', null, true);
    }

    // ── Batas ukuran unggahan ──────────────────────────────────────────

    #[Test]
    public function the_upload_limit_in_the_validation_rules_follows_the_setting(): void
    {
        $this->setSetting('asset_max_upload_size_mb', '25');

        // FormRequest yang diresolusi container langsung menjalankan validasi,
        // jadi instansnya dibuat sendiri — yang diuji hanya aturannya.
        $this->assertContains('max:25600', (new StoreDigitalAssetRequest)->rules()['file']);
        $this->assertContains('max:25600', (new UpdateDigitalAssetRequest)->rules()['replacement_file']);
    }

    #[Test]
    public function the_error_message_names_the_configured_limit(): void
    {
        $this->setSetting('asset_max_upload_size_mb', '25');

        $this->assertSame(
            'Ukuran file maksimum 25 MB.',
            (new StoreDigitalAssetRequest)->messages()['file.max']
        );
    }

    /**
     * Layanan menegakkan batas yang sama, karena unggahan dari job atau
     * perintah artisan tidak melewati lapisan validasi HTTP.
     */
    #[Test]
    public function the_service_refuses_a_file_above_the_configured_limit(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $this->setSetting('asset_max_upload_size_mb', '1');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Ukuran file melebihi batas 1 MB.');

        app(DigitalAssetUploadService::class)->upload(
            $this->pdfOfSize(2 * 1024 * 1024),
            ['bibliographic_record_id' => BibliographicRecord::factory()->create()->id, 'asset_type' => 'ebook']
        );
    }

    #[Test]
    public function a_file_within_the_configured_limit_is_accepted(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $this->setSetting('asset_max_upload_size_mb', '1');

        $asset = app(DigitalAssetUploadService::class)->upload(
            $this->pdfOfSize(200 * 1024),
            ['bibliographic_record_id' => BibliographicRecord::factory()->create()->id, 'asset_type' => 'ebook']
        );

        $this->assertDatabaseHas('digital_assets', ['id' => $asset->id]);
    }

    /**
     * Menaikkan batas lewat pengaturan langsung membuka berkas yang tadinya
     * ditolak — tanpa perubahan kode.
     */
    #[Test]
    public function raising_the_limit_admits_a_file_that_was_refused_before(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $recordId = BibliographicRecord::factory()->create()->id;
        $service = app(DigitalAssetUploadService::class);

        $this->setSetting('asset_max_upload_size_mb', '1');
        try {
            $service->upload($this->pdfOfSize(2 * 1024 * 1024), ['bibliographic_record_id' => $recordId, 'asset_type' => 'ebook']);
            $this->fail('Berkas 2 MB seharusnya ditolak saat batasnya 1 MB.');
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->setSetting('asset_max_upload_size_mb', '5');
        $asset = $service->upload($this->pdfOfSize(2 * 1024 * 1024), ['bibliographic_record_id' => $recordId, 'asset_type' => 'ebook']);

        $this->assertDatabaseHas('digital_assets', ['id' => $asset->id]);
    }

    #[Test]
    public function the_replacement_path_honours_the_limit_too(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create', 'digital_assets.update']);
        $service = app(DigitalAssetUploadService::class);
        $this->setSetting('asset_max_upload_size_mb', '5');
        $asset = $service->upload(
            $this->pdfOfSize(100 * 1024),
            ['bibliographic_record_id' => BibliographicRecord::factory()->create()->id, 'asset_type' => 'ebook']
        );

        $this->setSetting('asset_max_upload_size_mb', '1');

        $this->expectException(InvalidArgumentException::class);
        $service->replaceFile($asset->fresh(), $this->pdfOfSize(2 * 1024 * 1024));
    }

    // ── Saklar OCR ─────────────────────────────────────────────────────

    /**
     * Tidak ada pemroses OCR yang berjalan selama saklarnya mati. Permintaan
     * ditolak di depan alih-alih menandai aset `queued` selamanya.
     */
    #[Test]
    public function ocr_is_refused_while_the_switch_is_off(): void
    {
        $this->actingAsUserWith(['digital_assets.run_ocr']);
        $this->setSetting('ocr_enabled', 'false');
        $asset = DigitalAsset::factory()->create();

        try {
            app(OcrProcessingService::class)->requestOcr($asset);
            $this->fail('Permintaan OCR seharusnya ditolak.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('OCR sedang dinonaktifkan', $e->getMessage());
        }

        $this->assertSame('not_requested', $asset->fresh()->ocr_status);
    }

    #[Test]
    public function ocr_is_queued_once_the_switch_is_on(): void
    {
        $this->actingAsUserWith(['digital_assets.run_ocr']);
        $this->setSetting('ocr_enabled', 'true');
        $asset = DigitalAsset::factory()->create();

        app(OcrProcessingService::class)->requestOcr($asset);

        $this->assertSame('queued', $asset->fresh()->ocr_status);
    }

    #[Test]
    public function the_ocr_endpoint_flashes_the_reason_instead_of_failing(): void
    {
        $this->actingAsUserWith(['digital_assets.view_detail', 'digital_assets.run_ocr']);
        $this->setSetting('ocr_enabled', 'false');
        $asset = DigitalAsset::factory()->create();

        $this->from(route('admin.digital-assets.show', $asset))
            ->post(route('admin.digital-assets.ocr', $asset))
            ->assertSessionHas('error');

        $this->assertSame('not_requested', $asset->fresh()->ocr_status);
    }

    #[Test]
    public function the_release_migration_leaves_ocr_off_and_the_limit_at_fifty_megabytes(): void
    {
        $settings = app(SystemSettings::class);

        $this->assertFalse($settings->ocrEnabled());
        $this->assertSame(50, $settings->maxUploadSizeMb());
        $this->assertSame(51200, $settings->maxUploadSizeKilobytes());
    }
}
