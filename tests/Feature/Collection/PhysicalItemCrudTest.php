<?php

namespace Tests\Feature\Collection;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PhysicalItemCrudTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'bibliographic_record_id' => BibliographicRecord::factory()->create()->id,
            'barcode' => 'BC-BARU-0001',
            'inventory_code' => 'INV-BARU-0001',
            'item_status' => 'available',
        ], $overrides);
    }

    #[Test]
    public function creating_an_item_also_opens_its_status_history(): void
    {
        $petugas = $this->actingAsUserWith(['collections.create', 'collections.view']);

        $response = $this->post(route('admin.collections.items.store'), $this->validPayload());

        $response->assertRedirect(route('admin.collections.items.index'));
        $item = PhysicalItem::firstWhere('barcode', 'BC-BARU-0001');
        $this->assertNotNull($item);
        $this->assertDatabaseHas('physical_item_status_histories', [
            'physical_item_id' => $item->id,
            'old_status' => null,
            'new_status' => 'available',
            'changed_by' => $petugas->id,
            'reason' => 'Item baru dibuat',
        ]);
    }

    /**
     * Status `loaned` hanya boleh lahir dari transaksi peminjaman. Formulir
     * penciptaan item menolaknya lewat aturan validasi.
     */
    #[Test]
    public function an_item_cannot_be_created_directly_as_loaned(): void
    {
        $this->actingAsUserWith(['collections.create']);

        $response = $this->post(route('admin.collections.items.store'), $this->validPayload(['item_status' => 'loaned']));

        $response->assertSessionHasErrors('item_status');
        $this->assertDatabaseCount('physical_items', 0);
    }

    #[Test]
    public function a_barcode_must_be_unique(): void
    {
        $this->actingAsUserWith(['collections.create', 'collections.view']);
        PhysicalItem::factory()->create(['barcode' => 'BC-KEMBAR']);

        $response = $this->post(route('admin.collections.items.store'), $this->validPayload(['barcode' => 'BC-KEMBAR']));

        $response->assertSessionHasErrors('barcode');
    }

    #[Test]
    public function the_form_requires_a_parent_record_a_barcode_and_a_status(): void
    {
        $this->actingAsUserWith(['collections.create']);

        $this->post(route('admin.collections.items.store'), [])
            ->assertSessionHasErrors(['bibliographic_record_id', 'barcode', 'item_status']);
    }

    /**
     * Status hanya boleh berubah lewat endpoint perubahan status agar riwayatnya
     * ikut tercatat; formulir edit biasa harus mengabaikannya.
     */
    #[Test]
    public function the_edit_form_cannot_change_the_item_status(): void
    {
        $this->actingAsUserWith(['collections.update', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create();

        $this->put(route('admin.collections.items.update', $item), [
            'bibliographic_record_id' => $item->bibliographic_record_id,
            'barcode' => $item->barcode,
            'item_status' => 'lost',
            'notes' => 'Catatan baru',
        ]);

        $item->refresh();
        $this->assertSame('available', $item->item_status);
        $this->assertSame('Catatan baru', $item->notes);
    }

    #[Test]
    public function a_status_change_goes_through_the_dedicated_endpoint(): void
    {
        $this->actingAsUserWith(['collections.update', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create();

        $this->post(route('admin.collections.items.change_status', $item), [
            'new_status' => 'damaged',
            'reason' => 'Terkena air',
        ])->assertRedirect(route('admin.collections.items.show', $item))->assertSessionHas('success');

        $this->assertSame('damaged', $item->fresh()->item_status);
    }

    #[Test]
    public function an_invalid_status_change_flashes_an_error_instead_of_crashing(): void
    {
        $this->actingAsUserWith(['collections.update', 'collections.view']);
        $item = PhysicalItem::factory()->lost()->create();

        $this->from(route('admin.collections.items.show', $item))
            ->post(route('admin.collections.items.change_status', $item), ['new_status' => 'loaned'])
            ->assertSessionHas('error');

        $this->assertSame('lost', $item->fresh()->item_status);
    }

    #[Test]
    public function the_status_change_endpoint_rejects_an_unknown_status(): void
    {
        $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->available()->create();

        $this->post(route('admin.collections.items.change_status', $item), ['new_status' => 'menguap'])
            ->assertSessionHasErrors('new_status');
    }

    #[Test]
    public function a_loaned_item_cannot_be_deleted(): void
    {
        $this->actingAsUserWith(['collections.delete', 'collections.view']);
        $item = PhysicalItem::factory()->loaned()->create();

        $this->from(route('admin.collections.items.show', $item))
            ->delete(route('admin.collections.items.destroy', $item))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('physical_items', ['id' => $item->id, 'deleted_at' => null]);
    }

    /**
     * Penghapusan bersifat lunak: baris tetap ada dengan `deleted_at` terisi,
     * hilang dari seluruh kueri, dan dapat dipulihkan.
     */
    #[Test]
    public function deleting_an_item_soft_deletes_it(): void
    {
        $this->actingAsUserWith(['collections.delete', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create();

        $this->delete(route('admin.collections.items.destroy', $item))->assertSessionHas('success');

        $this->assertSoftDeleted('physical_items', ['id' => $item->id]);
        $this->assertNull(PhysicalItem::find($item->id));
        $this->assertSame(0, PhysicalItem::count());
    }

    /**
     * Riwayat status adalah bukti audit dan sekaligus bagian dari item yang
     * dipulihkan. Ia harus selamat dari penghapusan.
     */
    #[Test]
    public function deleting_an_item_keeps_its_status_history(): void
    {
        $this->actingAsUserWith(['collections.delete', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create();
        $item->statusHistories()->create(['old_status' => null, 'new_status' => 'available']);

        $this->delete(route('admin.collections.items.destroy', $item));

        $this->assertDatabaseCount('physical_item_status_histories', 1);
        $this->assertSame($item->id, PhysicalItem::withTrashed()->find($item->id)->statusHistories()->first()->physical_item_id);
    }

    #[Test]
    public function a_soft_deleted_item_can_be_restored_with_its_history_intact(): void
    {
        $this->actingAsUserWith(['collections.delete', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create();
        $item->statusHistories()->create(['old_status' => null, 'new_status' => 'available']);
        $this->delete(route('admin.collections.items.destroy', $item));

        PhysicalItem::withTrashed()->find($item->id)->restore();

        $restored = PhysicalItem::find($item->id);
        $this->assertNotNull($restored);
        $this->assertSame('available', $restored->item_status);
        $this->assertCount(1, $restored->statusHistories);
    }

    /**
     * Inilah alasan utama penghapusan harus lunak: item yang pernah dipinjam
     * ditahan foreign key `restrictOnDelete` dari tabel `loans`. Dulu petugas
     * menerima error basis data; sekarang penghapusan berhasil dan riwayat
     * pinjaman tetap dapat menyebut item tersebut.
     */
    #[Test]
    public function an_item_with_loan_history_can_be_deleted_without_breaking_that_history(): void
    {
        $this->actingAsUserWith(['collections.delete', 'collections.view']);
        $loan = $this->activeLoan();
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);
        $item = $loan->physicalItem->fresh();
        $item->update(['item_status' => 'available']);

        $this->delete(route('admin.collections.items.destroy', $item))->assertSessionHas('success');

        $this->assertSoftDeleted('physical_items', ['id' => $item->id]);
        $this->assertSame($item->barcode, $loan->fresh()->physicalItem?->barcode, 'riwayat pinjaman kehilangan itemnya');
    }

    /**
     * Barcode adalah unique index tanpa syarat `deleted_at`, jadi item yang
     * dihapus lunak tetap memegang barcodenya. Validasi harus mencerminkan
     * batasan basis data itu, bukan menjanjikan sesuatu yang akan ditolak MySQL.
     */
    #[Test]
    public function a_soft_deleted_item_still_holds_its_barcode(): void
    {
        $this->actingAsUserWith(['collections.create', 'collections.delete', 'collections.view']);
        $item = PhysicalItem::factory()->available()->create(['barcode' => 'BC-DIPAKAI-1']);
        $this->delete(route('admin.collections.items.destroy', $item));

        $this->post(route('admin.collections.items.store'), $this->validPayload(['barcode' => 'BC-DIPAKAI-1']))
            ->assertSessionHasErrors('barcode');
    }

    #[Test]
    public function the_collection_screens_render_for_an_authorised_user(): void
    {
        $this->actingAsUserWith(['collections.view', 'collections.create', 'collections.update']);
        $item = PhysicalItem::factory()->available()->create();

        $this->get(route('admin.collections.items.index'))->assertOk();
        $this->get(route('admin.collections.items.create'))->assertOk();
        $this->get(route('admin.collections.items.show', $item))->assertOk()->assertSee($item->barcode);
        $this->get(route('admin.collections.items.edit', $item))->assertOk();
        $this->get(route('admin.collections.items.history', $item))->assertOk();
    }

    #[Test]
    public function the_index_can_be_filtered_by_barcode_and_status(): void
    {
        $this->actingAsUserWith(['collections.view']);
        PhysicalItem::factory()->available()->create(['barcode' => 'BC-TERSEDIA-1']);
        PhysicalItem::factory()->damaged()->create(['barcode' => 'BC-RUSAK-1']);

        $this->get(route('admin.collections.items.index', ['keyword' => 'BC-RUSAK']))
            ->assertOk()->assertSee('BC-RUSAK-1')->assertDontSee('BC-TERSEDIA-1');

        $this->get(route('admin.collections.items.index', ['item_status' => 'available']))
            ->assertOk()->assertSee('BC-TERSEDIA-1')->assertDontSee('BC-RUSAK-1');
    }

    #[Test]
    public function a_viewer_cannot_create_update_or_delete_items(): void
    {
        $this->actingAsUserWith(['collections.view']);
        $item = PhysicalItem::factory()->available()->create();

        $this->get(route('admin.collections.items.create'))->assertForbidden();
        $this->post(route('admin.collections.items.store'), $this->validPayload())->assertForbidden();
        $this->delete(route('admin.collections.items.destroy', $item))->assertForbidden();
        $this->post(route('admin.collections.items.change_status', $item), ['new_status' => 'damaged'])->assertForbidden();
    }
}
