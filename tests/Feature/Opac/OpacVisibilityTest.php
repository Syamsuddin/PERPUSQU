<?php

namespace Tests\Feature\Opac;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\MasterData\Models\Author;
use App\Modules\Opac\Services\OpacSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * OPAC adalah satu-satunya bagian aplikasi yang terbuka tanpa login. Aturannya
 * sempit dan mutlak: hanya record ber-status `published` DAN bertanda
 * `is_public` yang boleh terlihat. Kedua syarat diuji terpisah supaya kegagalan
 * salah satunya tidak tertutupi oleh yang lain.
 */
class OpacVisibilityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_public_pages_are_reachable_without_signing_in(): void
    {
        $this->get(route('opac.home'))->assertOk();
        $this->get(route('opac.search'))->assertOk();
        $this->get(route('opac.about'))->assertOk();
        $this->get(route('opac.help'))->assertOk();
    }

    #[Test]
    public function a_published_public_record_is_visible(): void
    {
        $record = BibliographicRecord::factory()->published()->withAuthor()->create(['title' => 'Tafsir Al-Misbah']);

        $this->get(route('opac.search'))->assertOk()->assertSee('Tafsir Al-Misbah');
        $this->get(route('opac.record.show', $record->id))->assertOk()->assertSee('Tafsir Al-Misbah');
    }

    #[Test]
    #[DataProvider('hiddenRecordStates')]
    public function a_record_that_is_not_both_published_and_public_stays_hidden(string $state): void
    {
        $record = BibliographicRecord::factory()->{$state}()->withAuthor()->create(['title' => 'Dokumen Internal']);

        $this->get(route('opac.search'))->assertOk()->assertDontSee('Dokumen Internal');
        $this->get(route('opac.home'))->assertOk()->assertDontSee('Dokumen Internal');
        $this->get(route('opac.record.show', $record->id))->assertNotFound();
    }

    public static function hiddenRecordStates(): array
    {
        return [
            'masih draft' => ['draft'],
            'sudah ditarik' => ['unpublished'],
            'sudah diarsipkan' => ['archived'],
            'terbit tapi ditandai non-publik' => ['internal'],
        ];
    }

    /**
     * Menerbitkan katalog TIDAK otomatis membukanya ke publik — `is_public`
     * adalah saklar terpisah. Perilaku ini mudah disalahpahami, jadi dikunci.
     */
    #[Test]
    public function publishing_alone_does_not_expose_a_record_to_the_public(): void
    {
        $this->actingAsUserWith(['catalog.view', 'catalog.publish']);
        $record = BibliographicRecord::factory()->withAuthor()->create([
            'title' => 'Belum Dibuka Publik',
            'publication_status' => 'draft',
            'is_public' => false,
        ]);

        $this->post(route('admin.catalog.records.publish', $record));

        $this->assertSame('published', $record->fresh()->publication_status);
        $this->assertFalse($record->fresh()->is_public);
        $this->get(route('opac.record.show', $record->id))->assertNotFound();
    }

    #[Test]
    public function search_matches_the_title_the_isbn_the_keywords_and_the_author(): void
    {
        $author = Author::factory()->create(['name' => 'Quraish Shihab']);
        BibliographicRecord::factory()->published()->withAuthor($author)->create([
            'title' => 'Wawasan Al-Quran',
            'isbn' => '9789794334455',
            'keywords' => 'tafsir, tematik',
        ]);
        BibliographicRecord::factory()->published()->withAuthor()->create(['title' => 'Buku Lain Sama Sekali']);

        foreach (['Wawasan', '9789794334455', 'tematik', 'Quraish'] as $keyword) {
            $this->get(route('opac.search', ['keyword' => $keyword]))
                ->assertOk()
                ->assertSee('Wawasan Al-Quran')
                ->assertDontSee('Buku Lain Sama Sekali');
        }
    }

    #[Test]
    public function search_never_reaches_across_the_visibility_boundary(): void
    {
        BibliographicRecord::factory()->draft()->create(['title' => 'Naskah Rahasia', 'keywords' => 'rahasia']);

        $this->get(route('opac.search', ['keyword' => 'rahasia']))->assertOk()->assertDontSee('Naskah Rahasia');
        $this->get(route('opac.search', ['keyword' => 'Naskah']))->assertOk()->assertDontSee('Naskah Rahasia');
    }

    /**
     * Halaman detail publik menampilkan ketersediaan, tetapi tidak boleh
     * membocorkan barcode atau kode inventaris yang dipakai petugas.
     */
    #[Test]
    public function the_public_detail_page_shows_availability_without_leaking_barcodes(): void
    {
        $record = BibliographicRecord::factory()->published()->withAuthor()->create();
        $available = PhysicalItem::factory()->available()->create([
            'bibliographic_record_id' => $record->id,
            'barcode' => 'BC-RAHASIA-001',
            'inventory_code' => 'INV-RAHASIA-001',
        ]);
        PhysicalItem::factory()->loaned()->create(['bibliographic_record_id' => $record->id]);

        $response = $this->get(route('opac.record.show', $record->id));

        $response->assertOk();
        $response->assertDontSee('BC-RAHASIA-001');
        $response->assertDontSee('INV-RAHASIA-001');
        $response->assertViewHas('availableCount', 1);
        $response->assertViewHas('totalItems', 2);
        $this->assertNotNull($available->id);
    }

    #[Test]
    public function the_public_stats_only_count_what_the_public_may_see(): void
    {
        BibliographicRecord::factory()->count(2)->published()->create();
        BibliographicRecord::factory()->draft()->create();
        BibliographicRecord::factory()->internal()->create();

        $stats = app(OpacSearchService::class)->getPublicStats();

        $this->assertSame(2, $stats['total_titles']);
    }

    #[Test]
    public function a_missing_record_id_yields_a_404(): void
    {
        $this->get(route('opac.record.show', 999999))->assertNotFound();
    }

    #[Test]
    public function the_home_page_lists_at_most_eight_recent_titles(): void
    {
        BibliographicRecord::factory()->count(10)->published()->create();

        $latest = app(OpacSearchService::class)->getLatestRecords(8);

        $this->assertCount(8, $latest);
    }

    #[Test]
    public function only_published_public_digital_assets_appear_on_the_detail_page(): void
    {
        $record = BibliographicRecord::factory()->published()->withAuthor()->create();
        DigitalAsset::factory()->published()->create([
            'bibliographic_record_id' => $record->id,
            'title' => 'Lampiran Terbuka',
        ]);
        DigitalAsset::factory()->create([
            'bibliographic_record_id' => $record->id,
            'title' => 'Lampiran Tertutup',
        ]);

        $response = $this->get(route('opac.record.show', $record->id));

        $response->assertOk();
        $this->assertSame(['Lampiran Terbuka'], $response->viewData('record')->digitalAssets->pluck('title')->all());
    }
}
