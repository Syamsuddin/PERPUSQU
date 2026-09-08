<?php

namespace Tests\Feature\Identity;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Test lain memakai izin yang dirakit sendiri agar kegagalan menunjuk ke satu
 * aturan. Berkas ini sebaliknya menjalankan SEDER YANG SESUNGGUHNYA dan
 * memeriksa bahwa peran seperti yang dikirim ke produksi benar-benar bisa
 * melakukan pekerjaannya. Izin yang terdaftar tetapi tidak pernah diberikan
 * kepada siapa pun adalah cacat yang tidak terlihat oleh test per-modul.
 */
class SeededRoleCapabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
        $this->flushPermissionCache();
    }

    private function userWithSeededRole(string $roleName): User
    {
        $user = User::factory()->create();
        $user->assignRole($roleName);
        $this->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * Alur kerja inti seorang pustakawan: memperbaiki deskripsi koleksi yang
     * dikatalog orang lain. Inilah yang sebelumnya selalu dijawab 403.
     */
    #[Test]
    public function a_seeded_librarian_can_edit_a_record_catalogued_by_someone_else(): void
    {
        $pustakawan = $this->userWithSeededRole('Pustakawan');
        $record = BibliographicRecord::factory()->withAuthor()->create([
            'title' => 'Judul Lama',
            'created_by' => User::factory()->create()->id,
        ]);

        $this->actingAs($pustakawan)
            ->put(route('admin.catalog.records.update', $record), [
                'title' => 'Judul Hasil Koreksi',
                'collection_type_id' => $record->collection_type_id,
                'author_ids' => $record->authors->pluck('id')->all(),
            ])
            ->assertRedirect(route('admin.catalog.records.index'))
            ->assertSessionHas('success');

        $this->assertSame('Judul Hasil Koreksi', $record->fresh()->title);
    }

    #[Test]
    public function a_seeded_librarian_can_catalogue_and_publish(): void
    {
        $pustakawan = $this->userWithSeededRole('Pustakawan');
        $record = BibliographicRecord::factory()->withAuthor()->create(['created_by' => $pustakawan->id]);

        $this->actingAs($pustakawan)->get(route('admin.catalog.records.create'))->assertOk();
        $this->actingAs($pustakawan)
            ->post(route('admin.catalog.records.publish', $record))
            ->assertSessionHas('success');

        $this->assertSame('published', $record->fresh()->publication_status);
    }

    #[Test]
    public function a_seeded_library_administrator_can_edit_any_record(): void
    {
        $admin = $this->userWithSeededRole('Admin Perpustakaan');
        $record = BibliographicRecord::factory()->withAuthor()->create([
            'created_by' => User::factory()->create()->id,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.catalog.records.update', $record), [
                'title' => 'Dikoreksi Admin',
                'collection_type_id' => $record->collection_type_id,
                'author_ids' => $record->authors->pluck('id')->all(),
            ])->assertSessionHas('success');

        $this->assertSame('Dikoreksi Admin', $record->fresh()->title);
    }

    /**
     * Batas peran tetap dijaga: petugas sirkulasi tidak boleh menyunting katalog.
     */
    #[Test]
    public function a_seeded_circulation_clerk_still_cannot_edit_the_catalogue(): void
    {
        $sirkulasi = $this->userWithSeededRole('Petugas Sirkulasi');
        $record = BibliographicRecord::factory()->withAuthor()->create();

        $this->actingAs($sirkulasi)->get(route('admin.catalog.records.index'))->assertOk();
        $this->actingAs($sirkulasi)
            ->put(route('admin.catalog.records.update', $record), [
                'title' => 'Diubah Diam-diam',
                'collection_type_id' => $record->collection_type_id,
                'author_ids' => $record->authors->pluck('id')->all(),
            ])->assertForbidden();

        $this->assertNotSame('Diubah Diam-diam', $record->fresh()->title);
    }

    #[Test]
    public function a_seeded_circulation_clerk_can_run_the_loan_desk(): void
    {
        $sirkulasi = $this->userWithSeededRole('Petugas Sirkulasi');
        $member = $this->eligibleMember();
        $item = $this->availableItem();

        $this->actingAs($sirkulasi)
            ->post(route('admin.circulation.loans.store'), [
                'member_id' => $member->id,
                'barcode' => $item->barcode,
            ])->assertSessionHas('success');

        $this->assertDatabaseHas('loans', ['member_id' => $member->id, 'loan_status' => 'active']);
    }

    /**
     * Setiap izin yang didaftarkan seharusnya sampai ke setidaknya satu peran.
     * Izin yatim menandakan seeder dan policy sudah tidak sejalan — persis
     * keadaan yang membuat `catalog.update_any` tidak berguna selama ini.
     */
    #[Test]
    public function no_registered_permission_is_left_unassigned(): void
    {
        $assigned = Role::with('permissions')
            ->get()
            ->flatMap(fn ($role) => $role->permissions->pluck('name'))
            ->unique();

        $orphaned = Permission::pluck('name')
            ->reject(fn ($name) => $assigned->contains($name))
            ->values()
            ->all();

        $this->assertSame(
            [],
            $orphaned,
            "Izin terdaftar yang tidak dipegang peran mana pun:\n- ".implode("\n- ", $orphaned)
        );
    }

    /**
     * Kebalikannya: peran tidak boleh menuntut izin yang tidak pernah
     * didaftarkan. Spatie akan melempar exception saat sinkronisasi, tetapi
     * pemeriksaan eksplisit menjelaskan penyebabnya lebih cepat.
     */
    #[Test]
    #[DataProvider('seededRoles')]
    public function every_seeded_role_holds_only_registered_permissions(string $roleName): void
    {
        $role = Role::findByName($roleName, 'web');
        $registered = Permission::pluck('name');

        $unknown = $role->permissions->pluck('name')->reject(fn ($n) => $registered->contains($n))->all();

        $this->assertSame([], $unknown, "Peran {$roleName} memegang izin tak terdaftar: ".implode(', ', $unknown));
    }

    public static function seededRoles(): array
    {
        return [
            ['Super Admin'],
            ['Admin Perpustakaan'],
            ['Pustakawan'],
            ['Petugas Sirkulasi'],
            ['Operator Repositori Digital'],
            ['Pimpinan Perpustakaan'],
            ['Anggota Perpustakaan'],
        ];
    }

    /**
     * Tombol meja sirkulasi dijaga @can dengan nama izin yang tidak pernah
     * didaftarkan (`circulation.create`, `.return`, `.renew`, `.fine`),
     * sehingga tak satu pun muncul bagi Petugas Sirkulasi — peran yang justru
     * dibuat untuk memakainya. Hanya Super Admin yang melihatnya, lewat
     * Gate::before. Test ini menjaga agar tombolnya tetap terlihat.
     */
    #[Test]
    public function a_seeded_circulation_clerk_sees_the_buttons_of_their_own_desk(): void
    {
        $sirkulasi = $this->userWithSeededRole('Petugas Sirkulasi');
        $loan = $this->activeLoan(null, null, ['due_date' => now()->addDays(5)]);
        $fine = Fine::factory()->outstanding()->create();

        $this->actingAs($sirkulasi)
            ->get(route('admin.circulation.loans.active'))
            ->assertOk()
            ->assertSee('Pinjam Baru')
            ->assertSee('Pengembalian');

        $this->actingAs($sirkulasi)
            ->get(route('admin.circulation.loans.show', $loan))
            ->assertOk()
            ->assertSee('Perpanjang');

        $this->actingAs($sirkulasi)
            ->get(route('admin.circulation.fines.index'))
            ->assertOk()
            ->assertSee(route('admin.circulation.fines.settle', $fine), false);
    }

    /**
     * OCR dijaga policy dengan nama izin `digital_assets.ocr`, sedangkan yang
     * didaftarkan adalah `digital_assets.run_ocr` — sehingga Operator
     * Repositori Digital tidak pernah bisa menjalankannya.
     */
    #[Test]
    public function a_seeded_digital_operator_can_request_ocr_once_it_is_enabled(): void
    {
        SystemSetting::query()
            ->updateOrCreate(['key' => 'ocr_enabled'], ['value' => 'true']);
        app(SystemSettings::class)->refresh();

        $operator = $this->userWithSeededRole('Operator Repositori Digital');
        $asset = DigitalAsset::factory()->create([
            'uploaded_by' => $operator->id,
        ]);

        $this->actingAs($operator)
            ->from(route('admin.digital-assets.show', $asset))
            ->post(route('admin.digital-assets.ocr', $asset))
            ->assertSessionHas('success');

        $this->assertSame('queued', $asset->fresh()->ocr_status);
    }
}
