<?php

namespace Tests\Feature\Catalog;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Services\CatalogPublicationService;
use App\Modules\MasterData\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Siklus hidup publikasi katalog. Guard metadata minimum diuji di sini karena
 * `canPublish()` menghitung pengarang lewat relasi — butuh basis data.
 */
class CatalogPublicationTest extends TestCase
{
    use RefreshDatabase;

    private CatalogPublicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CatalogPublicationService::class);
        $this->actingAsUserWith(['catalog.publish', 'catalog.unpublish']);
    }

    #[Test]
    #[DataProvider('publishableStates')]
    public function it_publishes_a_record_that_meets_the_minimum_metadata(string $startingStatus): void
    {
        $record = BibliographicRecord::factory()->withAuthor()->create(['publication_status' => $startingStatus]);

        $this->service->publish($record);

        $this->assertSame('published', $record->fresh()->publication_status);
    }

    public static function publishableStates(): array
    {
        return [['draft'], ['unpublished']];
    }

    #[Test]
    public function it_refuses_to_publish_a_record_without_an_author(): void
    {
        $record = BibliographicRecord::factory()->create(['publication_status' => 'draft']);

        try {
            $this->service->publish($record);
            $this->fail('Record tanpa pengarang seharusnya tidak bisa terbit.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('Minimal satu pengarang wajib terdaftar.', $e->getMessage());
        }

        $this->assertSame('draft', $record->fresh()->publication_status);
    }

    #[Test]
    public function it_refuses_to_publish_an_archived_record(): void
    {
        $record = BibliographicRecord::factory()->withAuthor()->archived()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->service->publish($record);
    }

    #[Test]
    public function it_lists_every_missing_requirement_at_once(): void
    {
        $record = BibliographicRecord::factory()->archived()->create();
        $record->forceFill(['title' => ''])->saveQuietly();

        try {
            $this->service->publish($record->fresh());
            $this->fail('Publikasi seharusnya ditolak.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('Judul wajib diisi.', $e->getMessage());
            $this->assertStringContainsString('Minimal satu pengarang wajib terdaftar.', $e->getMessage());
            $this->assertStringContainsString('Record yang sudah diarsipkan', $e->getMessage());
        }
    }

    #[Test]
    public function it_unpublishes_a_published_record(): void
    {
        $record = BibliographicRecord::factory()->withAuthor()->published()->create();

        $this->service->unpublish($record);

        $this->assertSame('unpublished', $record->fresh()->publication_status);
    }

    #[Test]
    public function it_refuses_to_unpublish_a_draft(): void
    {
        $record = BibliographicRecord::factory()->create(['publication_status' => 'draft']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transisi status tidak valid: draft → unpublished');

        $this->service->unpublish($record);
    }

    #[Test]
    #[DataProvider('archivableStates')]
    public function it_archives_a_record_from_any_live_state(string $startingStatus): void
    {
        $record = BibliographicRecord::factory()->create(['publication_status' => $startingStatus]);

        $this->service->archive($record);

        $this->assertSame('archived', $record->fresh()->publication_status);
    }

    public static function archivableStates(): array
    {
        return [['draft'], ['published'], ['unpublished']];
    }

    #[Test]
    public function it_refuses_to_archive_a_record_twice(): void
    {
        $record = BibliographicRecord::factory()->archived()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->service->archive($record);
    }

    #[Test]
    public function it_reactivates_an_archived_record_back_to_draft(): void
    {
        $record = BibliographicRecord::factory()->archived()->create();

        $this->service->reactivate($record);

        $this->assertSame('draft', $record->fresh()->publication_status);
    }

    #[Test]
    public function it_refuses_to_reactivate_a_record_that_is_not_archived(): void
    {
        $record = BibliographicRecord::factory()->published()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->service->reactivate($record);
    }

    /**
     * Rangkaian penuh draft → terbit → tarik → terbit lagi → arsip → reaktivasi,
     * untuk memastikan tidak ada keadaan yang menjadi jalan buntu.
     */
    #[Test]
    public function a_record_can_travel_the_whole_publication_cycle(): void
    {
        $record = BibliographicRecord::factory()->withAuthor()->create(['publication_status' => 'draft']);

        $this->service->publish($record);
        $this->assertSame('published', $record->fresh()->publication_status);

        $this->service->unpublish($record->fresh());
        $this->assertSame('unpublished', $record->fresh()->publication_status);

        $this->service->publish($record->fresh());
        $this->assertSame('published', $record->fresh()->publication_status);

        $this->service->archive($record->fresh());
        $this->assertSame('archived', $record->fresh()->publication_status);

        $this->service->reactivate($record->fresh());
        $this->assertSame('draft', $record->fresh()->publication_status);
    }

    #[Test]
    public function publishing_writes_an_audit_entry(): void
    {
        $record = BibliographicRecord::factory()->withAuthor()->create(['publication_status' => 'draft']);

        $this->service->publish($record);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'catalog',
            'subject_type' => BibliographicRecord::class,
            'subject_id' => $record->id,
        ]);
    }

    #[Test]
    public function a_record_with_several_authors_still_satisfies_the_guard(): void
    {
        $record = BibliographicRecord::factory()->create(['publication_status' => 'draft']);
        $record->authors()->attach(Author::factory()->count(3)->create()->pluck('id'));

        $this->service->publish($record);

        $this->assertSame('published', $record->fresh()->publication_status);
    }
}
