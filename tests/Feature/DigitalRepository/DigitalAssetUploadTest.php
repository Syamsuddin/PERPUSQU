<?php

namespace Tests\Feature\DigitalRepository;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Services\DigitalAssetUploadService;
use App\Modules\Opac\Services\PublicAssetPreviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Unggahan aset digital adalah satu-satunya jalur di mana pengguna menulis
 * berkas ke server. Pemeriksaan tipe dilakukan dua kali — pada tipe yang
 * dilaporkan klien DAN pada isi biner — jadi keduanya diuji, termasuk berkas
 * yang menyamar sebagai PDF.
 */
class DigitalAssetUploadTest extends TestCase
{
    use RefreshDatabase;

    private DigitalAssetUploadService $service;

    private string $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DigitalAssetUploadService::class);
        $this->workspace = storage_path('framework/testing/digital-assets');

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

    /**
     * UploadedFile::fake()->create() menghasilkan berkas kosong yang terbaca
     * finfo sebagai `application/x-empty`, sehingga tidak pernah melewati
     * pemeriksaan biner. Test ini menulis header PDF sungguhan.
     */
    /**
     * @param  string  $clientName  nama yang "dilaporkan peramban"; sengaja
     *                              dipisahkan dari lokasi tulis agar nama
     *                              berbahaya tidak ikut membuat berkas nyata
     *                              di luar direktori kerja test.
     */
    private function realPdf(string $clientName = 'skripsi.pdf'): UploadedFile
    {
        $path = $this->workspace.'/'.bin2hex(random_bytes(8)).'.pdf';
        file_put_contents($path, "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n%%EOF\n");

        return new UploadedFile($path, $clientName, 'application/pdf', null, true);
    }

    private function disguisedFile(string $clientName = 'jahat.pdf'): UploadedFile
    {
        $path = $this->workspace.'/'.bin2hex(random_bytes(8)).'.bin';
        file_put_contents($path, "<?php echo 'ini bukan pdf'; ?>\n");

        // Klien mengaku PDF; hanya pemeriksaan biner yang bisa membongkarnya.
        return new UploadedFile($path, $clientName, 'application/pdf', null, true);
    }

    private function metadata(array $overrides = []): array
    {
        return array_merge([
            'bibliographic_record_id' => BibliographicRecord::factory()->create()->id,
            'asset_type' => 'ebook',
            'title' => 'Skripsi Digital',
        ], $overrides);
    }

    #[Test]
    public function it_stores_a_genuine_pdf_and_records_its_metadata(): void
    {
        Storage::fake('local');
        $uploader = $this->actingAsUserWith(['digital_assets.create']);

        $asset = $this->service->upload($this->realPdf(), $this->metadata());

        $this->assertDatabaseHas('digital_assets', [
            'id' => $asset->id,
            'original_file_name' => 'skripsi.pdf',
            'mime_type' => 'application/pdf',
            'file_extension' => 'pdf',
            'publication_status' => 'draft',
            'is_public' => false,
            'ocr_status' => 'not_requested',
            'index_status' => 'pending',
            'uploaded_by' => $uploader->id,
        ]);
        Storage::disk('local')->assertExists($asset->file_path);
    }

    /**
     * Nama berkas disimpan sebagai UUID, bukan nama asli — nama asli dari
     * pengguna tidak boleh pernah menjadi bagian dari jalur di disk.
     */
    #[Test]
    public function the_stored_file_name_is_a_uuid_not_the_users_file_name(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);

        // Nama dari pengguna memuat traversal direktori; yang tersimpan harus
        // tetap berupa UUID di dalam digital_assets/.
        $asset = $this->service->upload($this->realPdf('../../etc-passwd.pdf'), $this->metadata());

        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}\.pdf$/', $asset->file_name);
        $this->assertStringStartsWith('digital_assets/', $asset->file_path);
        $this->assertStringNotContainsString('..', $asset->file_path);
    }

    #[Test]
    public function it_records_a_sha256_checksum_of_the_stored_file(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $file = $this->realPdf();
        $expected = hash_file('sha256', $file->getRealPath());

        $asset = $this->service->upload($file, $this->metadata());

        $this->assertSame($expected, $asset->checksum);
        $this->assertSame(64, strlen($asset->checksum));
    }

    /**
     * Berkas yang mengaku PDF tetapi isinya bukan harus ditolak — inilah
     * lapisan yang menghentikan unggahan skrip yang dinamai ulang.
     */
    #[Test]
    public function it_rejects_a_file_whose_contents_are_not_really_a_pdf(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);

        $this->expectException(InvalidArgumentException::class);

        $this->service->upload($this->disguisedFile(), $this->metadata());
    }

    #[Test]
    public function a_rejected_upload_writes_neither_a_row_nor_a_file(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);

        try {
            $this->service->upload($this->disguisedFile(), $this->metadata());
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->assertDatabaseCount('digital_assets', 0);
        $this->assertEmpty(Storage::disk('local')->allFiles('digital_assets'));
    }

    #[Test]
    #[DataProvider('forbiddenMimeTypes')]
    public function it_rejects_file_types_other_than_pdf(string $name, string $mime, string $contents): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $path = $this->workspace.'/'.$name;
        file_put_contents($path, $contents);

        $this->expectException(InvalidArgumentException::class);

        $this->service->upload(new UploadedFile($path, $name, $mime, null, true), $this->metadata());
    }

    public static function forbiddenMimeTypes(): array
    {
        return [
            'teks biasa' => ['catatan.txt', 'text/plain', 'sekadar teks'],
            'html' => ['halaman.html', 'text/html', '<html><body>hai</body></html>'],
            'gambar png' => ['gambar.png', 'image/png', "\x89PNG\r\n\x1a\n"],
        ];
    }

    #[Test]
    public function replacing_a_file_updates_the_metadata_and_resets_the_processing_flags(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create', 'digital_assets.update']);
        $asset = $this->service->upload($this->realPdf('versi-1.pdf'), $this->metadata());
        $asset->update(['ocr_status' => 'completed', 'index_status' => 'indexed']);
        $originalPath = $asset->file_path;

        $this->service->replaceFile($asset->fresh(), $this->realPdf('versi-2.pdf'));

        $asset->refresh();
        $this->assertSame('versi-2.pdf', $asset->original_file_name);
        $this->assertNotSame($originalPath, $asset->file_path);
        $this->assertSame('not_requested', $asset->ocr_status);
        $this->assertSame('pending', $asset->index_status);
    }

    /**
     * Berkas lama harus benar-benar dibuang setelah penggantian, kalau tidak
     * penyimpanan akan terisi salinan yatim.
     */
    #[Test]
    public function replacing_a_file_removes_the_previous_one_from_disk(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create', 'digital_assets.update']);
        $asset = $this->service->upload($this->realPdf('versi-1.pdf'), $this->metadata());
        $originalPath = $asset->file_path;

        $this->service->replaceFile($asset->fresh(), $this->realPdf('versi-2.pdf'));

        Storage::disk('local')->assertMissing($originalPath);
        Storage::disk('local')->assertExists($asset->fresh()->file_path);
    }

    #[Test]
    public function a_failed_replacement_leaves_the_original_file_intact(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create', 'digital_assets.update']);
        $asset = $this->service->upload($this->realPdf('versi-1.pdf'), $this->metadata());
        $originalPath = $asset->file_path;

        try {
            $this->service->replaceFile($asset->fresh(), $this->disguisedFile());
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->assertSame($originalPath, $asset->fresh()->file_path);
        Storage::disk('local')->assertExists($originalPath);
    }

    #[Test]
    public function the_upload_is_written_to_the_audit_log(): void
    {
        Storage::fake('local');
        $uploader = $this->actingAsUserWith(['digital_assets.create']);

        $asset = $this->service->upload($this->realPdf(), $this->metadata());

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'digital_repository',
            'subject_type' => DigitalAsset::class,
            'subject_id' => $asset->id,
            'causer_id' => $uploader->id,
        ]);
    }

    #[Test]
    public function an_uploaded_asset_starts_private_and_unpublished(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);

        $asset = $this->service->upload($this->realPdf(), $this->metadata());

        $this->assertSame('draft', $asset->publication_status);
        $this->assertFalse($asset->is_public);
        $this->assertFalse(app(PublicAssetPreviewService::class)->canPreview($asset));
    }

    #[Test]
    public function the_file_path_is_unique_across_assets(): void
    {
        Storage::fake('local');
        $this->actingAsUserWith(['digital_assets.create']);
        $recordId = BibliographicRecord::factory()->create()->id;

        $first = $this->service->upload($this->realPdf('a.pdf'), $this->metadata(['bibliographic_record_id' => $recordId]));
        $second = $this->service->upload($this->realPdf('b.pdf'), $this->metadata(['bibliographic_record_id' => $recordId]));

        $this->assertNotSame($first->file_path, $second->file_path);
    }
}
