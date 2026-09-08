<?php

namespace Tests\Feature\Catalog;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Subject;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BibliographicRecordCrudTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Fikih Muamalah Kontemporer',
            'collection_type_id' => CollectionType::factory()->create()->id,
            'author_ids' => [Author::factory()->create()->id],
            'publication_year' => 2024,
            'isbn' => '9786021234567',
        ], $overrides);
    }

    #[Test]
    public function a_cataloguer_can_create_a_record_with_authors_and_subjects(): void
    {
        $this->actingAsUserWith(['catalog.create', 'catalog.view']);
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $response = $this->post(route('admin.catalog.records.store'), $this->validPayload([
            'author_ids' => $authors->pluck('id')->all(),
            'subject_ids' => $subjects->pluck('id')->all(),
        ]));

        $response->assertRedirect(route('admin.catalog.records.index'));
        $response->assertSessionHas('success');

        $record = BibliographicRecord::firstWhere('title', 'Fikih Muamalah Kontemporer');
        $this->assertNotNull($record);
        $this->assertSame('draft', $record->publication_status, 'record baru selalu mulai sebagai draft');
        $this->assertCount(2, $record->authors);
        $this->assertCount(2, $record->subjects);
    }

    /**
     * Slug dipakai sebagai kunci unik. Dua record berjudul sama harus tetap
     * bisa disimpan tanpa bentrok.
     */
    #[Test]
    public function two_records_with_the_same_title_get_distinct_slugs(): void
    {
        $this->actingAsUserWith(['catalog.create', 'catalog.view']);

        $this->post(route('admin.catalog.records.store'), $this->validPayload());
        $this->post(route('admin.catalog.records.store'), $this->validPayload());

        $slugs = BibliographicRecord::pluck('slug');
        $this->assertCount(2, $slugs);
        $this->assertSame(2, $slugs->unique()->count());
    }

    #[Test]
    public function the_form_requires_a_title_a_collection_type_and_at_least_one_author(): void
    {
        $this->actingAsUserWith(['catalog.create']);

        $response = $this->post(route('admin.catalog.records.store'), []);

        $response->assertSessionHasErrors(['title', 'collection_type_id', 'author_ids']);
        $this->assertDatabaseCount('bibliographic_records', 0);
    }

    #[Test]
    public function the_form_rejects_an_empty_author_list(): void
    {
        $this->actingAsUserWith(['catalog.create']);

        $response = $this->post(route('admin.catalog.records.store'), $this->validPayload(['author_ids' => []]));

        $response->assertSessionHasErrors('author_ids');
    }

    #[Test]
    public function the_form_rejects_an_author_that_does_not_exist(): void
    {
        $this->actingAsUserWith(['catalog.create']);

        $response = $this->post(route('admin.catalog.records.store'), $this->validPayload(['author_ids' => [999999]]));

        $response->assertSessionHasErrors('author_ids.0');
    }

    #[Test]
    public function a_super_admin_can_update_a_record_and_replace_its_authors(): void
    {
        $this->actingAsSuperAdmin();
        $record = BibliographicRecord::factory()->withAuthor()->create(['title' => 'Judul Lama']);
        $newAuthor = Author::factory()->create();

        $response = $this->put(route('admin.catalog.records.update', $record), $this->validPayload([
            'title' => 'Judul Baru',
            'collection_type_id' => $record->collection_type_id,
            'author_ids' => [$newAuthor->id],
        ]));

        $response->assertRedirect(route('admin.catalog.records.index'));
        $record->refresh();
        $this->assertSame('Judul Baru', $record->title);
        $this->assertSame([$newAuthor->id], $record->authors->pluck('id')->all());
    }

    /**
     * Penghapusan bersifat lunak dan tidak merusak apa pun: relasi pengarang
     * serta subjek dipertahankan agar record yang dipulihkan kembali utuh.
     */
    #[Test]
    public function deleting_a_record_soft_deletes_it_and_keeps_its_relations(): void
    {
        $this->actingAsUserWith(['catalog.delete', 'catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        $record->subjects()->attach(Subject::factory()->create()->id);

        $this->delete(route('admin.catalog.records.destroy', $record))->assertSessionHas('success');

        $this->assertSoftDeleted('bibliographic_records', ['id' => $record->id]);
        $this->assertNull(BibliographicRecord::find($record->id));
        $this->assertDatabaseCount('bibliographic_record_authors', 1);
        $this->assertDatabaseCount('bibliographic_record_subjects', 1);
    }

    #[Test]
    public function a_soft_deleted_record_can_be_restored_complete_with_its_authors(): void
    {
        $this->actingAsUserWith(['catalog.delete', 'catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        $this->delete(route('admin.catalog.records.destroy', $record));

        BibliographicRecord::withTrashed()->find($record->id)->restore();

        $this->assertCount(1, BibliographicRecord::find($record->id)->authors);
    }

    /**
     * Record terbit hanya boleh dihapus Super Admin (lihat
     * a_published_record_cannot_be_deleted_by_a_non_super_admin), sehingga
     * skenario ini memang harus dijalankan sebagai Super Admin.
     */
    #[Test]
    public function a_soft_deleted_record_disappears_from_the_public_catalogue(): void
    {
        $this->actingAsSuperAdmin();
        $record = BibliographicRecord::factory()->published()->withAuthor()->create(['title' => 'Judul Ditarik']);

        $this->delete(route('admin.catalog.records.destroy', $record));

        $this->get(route('opac.search'))->assertOk()->assertDontSee('Judul Ditarik');
        $this->get(route('opac.record.show', $record->id))->assertNotFound();
    }

    /**
     * Skema menahan penghapusan induk lewat foreign key `restrictOnDelete` dari
     * physical_items dan digital_assets. Penghapusan lunak melewati penjagaan
     * itu di tingkat basis data, jadi aturannya ditegakkan layanan — dan harus
     * sampai ke petugas sebagai pesan, bukan error.
     */
    #[Test]
    public function a_record_that_still_has_copies_cannot_be_deleted(): void
    {
        $this->actingAsUserWith(['catalog.delete', 'catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        PhysicalItem::factory()->available()->create([
            'bibliographic_record_id' => $record->id,
        ]);

        $this->from(route('admin.catalog.records.show', $record))
            ->delete(route('admin.catalog.records.destroy', $record))
            ->assertSessionHas('error');

        $this->assertNotNull(BibliographicRecord::find($record->id));
    }

    #[Test]
    public function a_record_that_still_has_digital_assets_cannot_be_deleted(): void
    {
        $this->actingAsUserWith(['catalog.delete', 'catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        DigitalAsset::factory()->create([
            'bibliographic_record_id' => $record->id,
        ]);

        $this->from(route('admin.catalog.records.show', $record))
            ->delete(route('admin.catalog.records.destroy', $record))
            ->assertSessionHas('error');

        $this->assertNotNull(BibliographicRecord::find($record->id));
    }

    /**
     * Eksemplar yang sudah dihapus lunak tidak lagi menahan induknya.
     */
    #[Test]
    public function a_record_whose_copies_were_deleted_can_be_deleted(): void
    {
        $this->actingAsUserWith(['catalog.delete', 'catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        $item = PhysicalItem::factory()->available()->create([
            'bibliographic_record_id' => $record->id,
        ]);
        $item->delete();

        $this->delete(route('admin.catalog.records.destroy', $record))->assertSessionHas('success');

        $this->assertSoftDeleted('bibliographic_records', ['id' => $record->id]);
    }

    #[Test]
    public function the_catalog_screens_render_for_an_authorised_cataloguer(): void
    {
        $this->actingAsUserWith(['catalog.view', 'catalog.create', 'catalog.update']);
        $record = BibliographicRecord::factory()->withAuthor()->fullyLinked()->create();

        $this->get(route('admin.catalog.records.index'))->assertOk();
        $this->get(route('admin.catalog.records.create'))->assertOk();
        $this->get(route('admin.catalog.records.show', $record))->assertOk()->assertSee($record->title);
        $this->get(route('admin.catalog.records.edit', $record))->assertOk();
    }

    #[Test]
    public function the_index_can_be_filtered_by_keyword_and_publication_status(): void
    {
        $this->actingAsUserWith(['catalog.view']);
        BibliographicRecord::factory()->create(['title' => 'Ensiklopedia Hadis']);
        BibliographicRecord::factory()->published()->create(['title' => 'Atlas Sejarah Islam']);

        $this->get(route('admin.catalog.records.index', ['keyword' => 'Ensiklopedia']))
            ->assertOk()
            ->assertSee('Ensiklopedia Hadis')
            ->assertDontSee('Atlas Sejarah Islam');

        $this->get(route('admin.catalog.records.index', ['publication_status' => 'published']))
            ->assertOk()
            ->assertSee('Atlas Sejarah Islam')
            ->assertDontSee('Ensiklopedia Hadis');
    }

    #[Test]
    public function a_missing_record_yields_a_404(): void
    {
        $this->actingAsUserWith(['catalog.view']);

        $this->get(route('admin.catalog.records.show', 999999))->assertNotFound();
    }

    #[Test]
    public function a_viewer_without_write_permissions_cannot_create_update_or_delete(): void
    {
        $this->actingAsUserWith(['catalog.view']);
        $record = BibliographicRecord::factory()->create();

        $this->get(route('admin.catalog.records.create'))->assertForbidden();
        $this->post(route('admin.catalog.records.store'), $this->validPayload())->assertForbidden();
        $this->put(route('admin.catalog.records.update', $record), $this->validPayload())->assertForbidden();
        $this->delete(route('admin.catalog.records.destroy', $record))->assertForbidden();

        $this->assertDatabaseHas('bibliographic_records', ['id' => $record->id]);
    }

    #[Test]
    public function publication_endpoints_are_gated_by_their_own_permissions(): void
    {
        $this->actingAsUserWith(['catalog.view']);
        $record = BibliographicRecord::factory()->withAuthor()->create();

        $this->post(route('admin.catalog.records.publish', $record))->assertForbidden();

        $this->assertSame('draft', $record->fresh()->publication_status);
    }

    #[Test]
    public function a_cataloguer_can_publish_and_unpublish_from_the_endpoints(): void
    {
        $this->actingAsUserWith(['catalog.view', 'catalog.publish', 'catalog.unpublish']);
        $record = BibliographicRecord::factory()->withAuthor()->create();

        $this->post(route('admin.catalog.records.publish', $record))
            ->assertRedirect(route('admin.catalog.records.show', $record))
            ->assertSessionHas('success');
        $this->assertSame('published', $record->fresh()->publication_status);

        $this->post(route('admin.catalog.records.unpublish', $record))->assertSessionHas('success');
        $this->assertSame('unpublished', $record->fresh()->publication_status);
    }

    #[Test]
    public function publishing_an_incomplete_record_flashes_the_reason(): void
    {
        $this->actingAsUserWith(['catalog.view', 'catalog.publish']);
        $record = BibliographicRecord::factory()->create();

        $this->from(route('admin.catalog.records.show', $record))
            ->post(route('admin.catalog.records.publish', $record))
            ->assertSessionHas('error');

        $this->assertSame('draft', $record->fresh()->publication_status);
    }

    /**
     * Pustakawan adalah pengatalog utama. Ia memegang `catalog.update` (yang
     * dijaga route) plus `catalog.update_any` (yang dijaga policy), sehingga
     * boleh memperbaiki deskripsi koleksi siapa pun.
     */
    #[Test]
    public function a_librarian_can_edit_a_record_created_by_someone_else(): void
    {
        $penulisAsli = $this->userWithoutPermissions();
        $record = BibliographicRecord::factory()->withAuthor()->create([
            'title' => 'Judul Lama',
            'created_by' => $penulisAsli->id,
        ]);

        $pustakawan = $this->actingAsUserWith(['catalog.view', 'catalog.update', 'catalog.update_any']);

        $this->get(route('admin.catalog.records.edit', $record))->assertOk();
        $this->put(route('admin.catalog.records.update', $record), $this->validPayload([
            'title' => 'Judul Baru',
            'collection_type_id' => $record->collection_type_id,
        ]))->assertRedirect(route('admin.catalog.records.index'))->assertSessionHas('success');

        $record->refresh();
        $this->assertSame('Judul Baru', $record->title);
        $this->assertSame($penulisAsli->id, $record->created_by, 'pembuat asli tidak boleh ikut berubah');
        $this->assertSame($pustakawan->id, $record->updated_by);
    }

    /**
     * Tingkat izin yang lebih rendah: `catalog.update` saja hanya berlaku atas
     * record buatan pengguna itu sendiri.
     */
    #[Test]
    public function a_contributor_with_only_catalog_update_can_edit_their_own_record(): void
    {
        $kontributor = $this->actingAsUserWith(['catalog.view', 'catalog.create', 'catalog.update']);

        $this->post(route('admin.catalog.records.store'), $this->validPayload());
        $record = BibliographicRecord::firstWhere('title', 'Fikih Muamalah Kontemporer');
        $this->assertSame($kontributor->id, $record->created_by, 'pembuat record harus tercatat saat dibuat');

        $this->put(route('admin.catalog.records.update', $record), $this->validPayload([
            'title' => 'Fikih Muamalah Kontemporer (Revisi)',
            'collection_type_id' => $record->collection_type_id,
        ]))->assertSessionHas('success');

        $this->assertSame('Fikih Muamalah Kontemporer (Revisi)', $record->fresh()->title);
    }

    #[Test]
    public function a_contributor_with_only_catalog_update_cannot_edit_someone_elses_record(): void
    {
        $orangLain = $this->userWithoutPermissions();
        $record = BibliographicRecord::factory()->withAuthor()->create([
            'title' => 'Judul Milik Orang Lain',
            'created_by' => $orangLain->id,
        ]);

        $this->actingAsUserWith(['catalog.view', 'catalog.update']);

        $this->put(route('admin.catalog.records.update', $record), $this->validPayload([
            'collection_type_id' => $record->collection_type_id,
        ]))->assertForbidden();

        $this->assertSame('Judul Milik Orang Lain', $record->fresh()->title);
    }

    /**
     * Kepemilikan tidak boleh bisa dititipkan lewat formulir — kalau bisa,
     * siapa pun dapat mengaku sebagai pembuat record milik orang lain lalu
     * menyuntingnya dengan izin `catalog.update` biasa.
     */
    #[Test]
    public function the_creator_cannot_be_spoofed_through_the_form(): void
    {
        $korban = $this->userWithoutPermissions();
        $penyusup = $this->actingAsUserWith(['catalog.view', 'catalog.create', 'catalog.update']);

        $this->post(route('admin.catalog.records.store'), $this->validPayload(['created_by' => $korban->id]));

        $this->assertSame(
            $penyusup->id,
            BibliographicRecord::firstWhere('title', 'Fikih Muamalah Kontemporer')->created_by
        );
    }

    #[Test]
    public function the_permissions_referenced_by_the_policy_actually_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        foreach (['catalog.update', 'catalog.update_any', 'catalog.delete', 'catalog.delete_own'] as $permission) {
            $this->assertDatabaseHas('permissions', ['name' => $permission, 'guard_name' => 'web']);
        }
    }

    /**
     * Record yang sudah terbit hanya boleh dihapus Super Admin.
     */
    #[Test]
    public function a_published_record_cannot_be_deleted_by_a_non_super_admin(): void
    {
        $this->actingAsUserWith(['catalog.view', 'catalog.delete']);
        $record = BibliographicRecord::factory()->published()->create();

        $this->delete(route('admin.catalog.records.destroy', $record))->assertForbidden();

        $this->assertDatabaseHas('bibliographic_records', ['id' => $record->id]);
    }
}
