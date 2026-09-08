<?php

namespace Tests\Feature\Core;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Core\Services\TrashService;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Kotak sampah.
 *
 * Lima tabel memakai penghapusan lunak, tetapi tidak ada satu pun layar yang
 * menampilkan isinya — sehingga penghapusan terasa permanen bagi petugas dan
 * pemulihan hanya mungkin lewat tinker. Berkas ini menjaga jalan pulangnya.
 */
class TrashTest extends TestCase
{
    use RefreshDatabase;

    public static function trashTypes(): array
    {
        return [
            'katalog' => ['katalog', 'catalog.delete'],
            'item fisik' => ['item', 'collections.delete'],
            'anggota' => ['anggota', 'members.delete'],
            'aset digital' => ['aset-digital', 'digital_assets.delete'],
            'pengguna' => ['pengguna', 'users.delete'],
        ];
    }

    #[Test]
    #[DataProvider('trashTypes')]
    public function each_tab_opens_for_someone_who_may_delete_that_kind(string $type, string $permission): void
    {
        $this->actingAsUserWith([$permission]);

        $this->get(route('admin.trash.index', ['type' => $type]))->assertOk();
    }

    /**
     * Melihat isi kotak sampah sama saja melihat data yang dihapus, jadi
     * wewenangnya harus sama dengan wewenang menghapusnya — bukan sekadar
     * "boleh membuka halaman kotak sampah".
     */
    #[Test]
    #[DataProvider('trashTypes')]
    public function a_tab_is_refused_to_someone_who_may_not_delete_that_kind(string $type, string $_permission): void
    {
        // Punya izin hapus untuk jenis LAIN, sehingga lolos gerbang route.
        $this->actingAsUserWith(['users.delete']);

        if ($type === 'pengguna') {
            $this->markTestSkipped('Jenis ini justru yang diizinkan pada pengguna uji.');
        }

        $this->get(route('admin.trash.index', ['type' => $type]))->assertForbidden();
    }

    #[Test]
    public function a_guest_is_sent_to_the_login_page(): void
    {
        $this->get(route('admin.trash.index'))->assertRedirect(route('auth.login'));
    }

    #[Test]
    public function an_unknown_type_yields_a_404(): void
    {
        $this->actingAsUserWith(['catalog.delete']);

        $this->get(route('admin.trash.index', ['type' => 'entah']))->assertNotFound();
    }

    // ── Isi kotak sampah ───────────────────────────────────────────────

    #[Test]
    public function only_deleted_rows_appear(): void
    {
        $this->actingAsUserWith(['members.delete']);
        Member::factory()->create(['name' => 'Anggota Masih Aktif']);
        Member::factory()->create(['name' => 'Anggota Sudah Dihapus'])->delete();

        $this->get(route('admin.trash.index', ['type' => 'anggota']))
            ->assertOk()
            ->assertSee('Anggota Sudah Dihapus')
            ->assertDontSee('Anggota Masih Aktif');
    }

    #[Test]
    public function the_tab_badges_count_each_kind_separately(): void
    {
        $this->actingAsUserWith(['members.delete', 'catalog.delete']);
        Member::factory()->count(2)->create()->each->delete();
        BibliographicRecord::factory()->create()->delete();

        $counts = app(TrashService::class)->counts();

        $this->assertSame(2, $counts['anggota']);
        $this->assertSame(1, $counts['katalog']);
        $this->assertSame(0, $counts['pengguna']);
    }

    // ── Pemulihan ──────────────────────────────────────────────────────

    #[Test]
    public function restoring_a_member_brings_them_back(): void
    {
        $this->actingAsUserWith(['members.delete']);
        $member = Member::factory()->create(['name' => 'Hasan Basri']);
        $member->delete();

        $this->post(route('admin.trash.restore', ['type' => 'anggota', 'id' => $member->id]))
            ->assertRedirect(route('admin.trash.index', ['type' => 'anggota']))
            ->assertSessionHas('success');

        $this->assertNotNull(Member::find($member->id));
        $this->assertSame('Hasan Basri', Member::find($member->id)->name);
    }

    #[Test]
    public function restoring_a_catalogue_record_brings_back_its_relations(): void
    {
        $this->actingAsUserWith(['catalog.delete']);
        $record = BibliographicRecord::factory()->withAuthor()->create();
        $record->delete();

        $this->post(route('admin.trash.restore', ['type' => 'katalog', 'id' => $record->id]));

        $restored = BibliographicRecord::find($record->id);
        $this->assertNotNull($restored);
        $this->assertCount(1, $restored->authors);
    }

    /**
     * Memulihkan eksemplar yang induknya masih terhapus akan menghasilkan item
     * yang menggantung pada judul yang tidak terlihat di mana pun — keadaan
     * yang lebih membingungkan daripada tetap berada di kotak sampah.
     */
    #[Test]
    public function an_item_whose_parent_is_still_deleted_cannot_be_restored(): void
    {
        $this->actingAsUserWith(['collections.delete']);
        $record = BibliographicRecord::factory()->create();
        $item = PhysicalItem::factory()->available()->create(['bibliographic_record_id' => $record->id]);
        $item->delete();
        $record->delete();

        $this->from(route('admin.trash.index', ['type' => 'item']))
            ->post(route('admin.trash.restore', ['type' => 'item', 'id' => $item->id]))
            ->assertSessionHas('error');

        $this->assertNull(PhysicalItem::find($item->id));
    }

    #[Test]
    public function the_item_can_be_restored_once_its_parent_is_back(): void
    {
        $this->actingAsUserWith(['collections.delete', 'catalog.delete']);
        $record = BibliographicRecord::factory()->create();
        $item = PhysicalItem::factory()->available()->create(['bibliographic_record_id' => $record->id]);
        $item->delete();
        $record->delete();

        $this->post(route('admin.trash.restore', ['type' => 'katalog', 'id' => $record->id]));
        $this->post(route('admin.trash.restore', ['type' => 'item', 'id' => $item->id]))
            ->assertSessionHas('success');

        $this->assertNotNull(PhysicalItem::find($item->id));
    }

    #[Test]
    public function a_digital_asset_whose_record_is_still_deleted_cannot_be_restored(): void
    {
        $this->actingAsUserWith(['digital_assets.delete']);
        $record = BibliographicRecord::factory()->create();
        $asset = DigitalAsset::factory()->create(['bibliographic_record_id' => $record->id]);
        $asset->delete();
        $record->delete();

        $this->from(route('admin.trash.index', ['type' => 'aset-digital']))
            ->post(route('admin.trash.restore', ['type' => 'aset-digital', 'id' => $asset->id]))
            ->assertSessionHas('error');

        $this->assertNull(DigitalAsset::find($asset->id));
    }

    #[Test]
    public function restoring_is_written_to_the_audit_log(): void
    {
        $petugas = $this->actingAsUserWith(['members.delete']);
        $member = Member::factory()->create();
        $member->delete();

        $this->post(route('admin.trash.restore', ['type' => 'anggota', 'id' => $member->id]));

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'core',
            'subject_type' => Member::class,
            'subject_id' => $member->id,
            'causer_id' => $petugas->id,
        ]);
    }

    #[Test]
    public function restoring_needs_the_delete_permission_for_that_kind(): void
    {
        $this->actingAsUserWith(['users.delete']);
        $member = Member::factory()->create();
        $member->delete();

        $this->post(route('admin.trash.restore', ['type' => 'anggota', 'id' => $member->id]))
            ->assertForbidden();

        $this->assertNull(Member::find($member->id));
    }

    #[Test]
    public function restoring_a_row_that_was_never_deleted_yields_a_404(): void
    {
        $this->actingAsUserWith(['members.delete']);
        $member = Member::factory()->create();

        $this->post(route('admin.trash.restore', ['type' => 'anggota', 'id' => $member->id]))
            ->assertNotFound();
    }

    /**
     * Baris yang dihapus lunak tetap memegang identitasnya, sehingga data baru
     * tidak dapat memakainya ulang. Itu perilaku yang benar untuk barcode
     * perpustakaan, dan halaman ini yang menjelaskan ke mana barcodenya pergi.
     */
    #[Test]
    public function the_page_explains_why_a_deleted_identifier_cannot_be_reused(): void
    {
        $this->actingAsUserWith(['collections.delete']);

        $this->get(route('admin.trash.index', ['type' => 'item']))
            ->assertOk()
            ->assertSee('tidak dapat dipakai ulang');
    }

    #[Test]
    public function a_deleted_user_can_be_restored(): void
    {
        $this->actingAsUserWith(['users.delete']);
        $user = User::factory()->create(['name' => 'Fikri Maulana']);
        $user->delete();

        $this->post(route('admin.trash.restore', ['type' => 'pengguna', 'id' => $user->id]))
            ->assertSessionHas('success');

        $this->assertSame('Fikri Maulana', User::find($user->id)?->name);
    }
}
