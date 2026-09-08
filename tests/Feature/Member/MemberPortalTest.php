<?php

namespace Tests\Feature\Member;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Portal layanan mandiri anggota.
 *
 * Sebelum ini peran "Anggota Perpustakaan" punya izin untuk melihat pinjaman
 * dan dendanya sendiri tanpa satu pun route yang melayaninya: anggota yang
 * berhasil masuk langsung mendarat di halaman 403.
 */
class MemberPortalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Anggota dengan akun tertaut — bentuk yang sesungguhnya dipakai di lapangan.
     */
    private function signedInMember(array $attributes = []): Member
    {
        $user = $this->userWith(['own_loans.view', 'own_loans.view_history', 'own_fines.view']);
        $this->actingAs($user);

        return Member::factory()->create($attributes + ['user_id' => $user->id]);
    }

    public static function portalRoutes(): array
    {
        return [
            'pinjaman' => ['member.portal.loans'],
            'riwayat' => ['member.portal.history'],
            'denda' => ['member.portal.fines'],
        ];
    }

    #[Test]
    #[DataProvider('portalRoutes')]
    public function a_member_can_open_every_portal_page(string $routeName): void
    {
        $this->signedInMember();

        $this->get(route($routeName))->assertOk();
    }

    #[Test]
    #[DataProvider('portalRoutes')]
    public function a_guest_is_sent_to_the_login_page(string $routeName): void
    {
        $this->get(route($routeName))->assertRedirect(route('auth.login'));
    }

    #[Test]
    #[DataProvider('portalRoutes')]
    public function a_signed_in_user_without_the_permission_is_refused(string $routeName): void
    {
        $this->actingAs($this->userWithoutPermissions());

        $this->get(route($routeName))->assertForbidden();
    }

    #[Test]
    public function the_loan_page_shows_only_active_loans_of_the_signed_in_member(): void
    {
        $member = $this->signedInMember();
        $active = $this->activeLoan($member);
        $active->physicalItem->bibliographicRecord->update(['title' => 'Buku Yang Saya Pinjam']);

        $returned = $this->activeLoan($member);
        $returned->physicalItem->bibliographicRecord->update(['title' => 'Buku Yang Sudah Saya Kembalikan']);
        $returned->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $response = $this->get(route('member.portal.loans'));

        $response->assertOk();
        $response->assertSee('Buku Yang Saya Pinjam');
        $response->assertDontSee('Buku Yang Sudah Saya Kembalikan');
    }

    /**
     * Batas yang paling penting di portal ini: apa pun yang tampil harus milik
     * anggota yang sedang masuk. Tidak ada parameter anggota di URL, jadi tidak
     * ada id yang bisa ditebak — tetapi kuerinya tetap harus dibuktikan.
     */
    #[Test]
    public function a_member_never_sees_another_members_loans(): void
    {
        $member = $this->signedInMember();
        $this->activeLoan($member)->physicalItem->bibliographicRecord->update(['title' => 'Pinjaman Saya Sendiri']);

        $orangLain = Member::factory()->create(['name' => 'Anggota Lain']);
        $this->activeLoan($orangLain)->physicalItem->bibliographicRecord->update(['title' => 'Pinjaman Orang Lain']);

        $this->get(route('member.portal.loans'))
            ->assertOk()
            ->assertSee('Pinjaman Saya Sendiri')
            ->assertDontSee('Pinjaman Orang Lain');

        $this->get(route('member.portal.history'))
            ->assertOk()
            ->assertDontSee('Pinjaman Orang Lain');
    }

    #[Test]
    public function a_member_never_sees_another_members_fines(): void
    {
        $member = $this->signedInMember();
        Fine::factory()->forMember($member)->outstanding()->create(['notes' => 'Denda milik saya']);
        Fine::factory()->forMember(Member::factory()->create())->outstanding()->create(['notes' => 'Denda orang lain']);

        $this->get(route('member.portal.fines'))
            ->assertOk()
            ->assertSee('Denda milik saya')
            ->assertDontSee('Denda orang lain');
    }

    #[Test]
    public function the_history_page_shows_returned_loans_too(): void
    {
        $member = $this->signedInMember();
        $loan = $this->activeLoan($member);
        $loan->physicalItem->bibliographicRecord->update(['title' => 'Buku Lama']);
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $this->get(route('member.portal.history'))->assertOk()->assertSee('Buku Lama');
    }

    #[Test]
    public function the_fine_page_totals_only_the_outstanding_amount(): void
    {
        $member = $this->signedInMember();
        Fine::factory()->forMember($member)->outstanding()->create(['amount' => 3000]);
        Fine::factory()->forMember($member)->outstanding()->create(['amount' => 4500]);
        Fine::factory()->forMember($member)->settled()->create(['amount' => 100000]);

        $this->get(route('member.portal.fines'))
            ->assertOk()
            ->assertViewHas('outstanding', fn ($total) => (float) $total === 7500.0);
    }

    /**
     * Akun tanpa tautan bukan pelanggaran hak akses — izinnya benar, datanya
     * yang belum disiapkan. Yang pantas muncul adalah penjelasan, bukan 403.
     */
    #[Test]
    public function an_account_with_no_member_record_gets_an_explanation(): void
    {
        $this->actingAs($this->userWith(['own_loans.view']));

        $this->get(route('member.portal.loans'))
            ->assertOk()
            ->assertSee('belum tertaut ke data keanggotaan');
    }

    #[Test]
    public function the_portal_pages_render_when_the_member_has_nothing_yet(): void
    {
        $this->signedInMember();

        $this->get(route('member.portal.loans'))->assertOk()->assertSee('Tidak ada pinjaman yang sedang berjalan');
        $this->get(route('member.portal.history'))->assertOk()->assertSee('Belum ada riwayat peminjaman');
        $this->get(route('member.portal.fines'))->assertOk()->assertSee('Tidak ada catatan denda');
    }

    // ── Peran seperti yang dikirim ke produksi ─────────────────────────

    /**
     * Alur utuh yang sebelumnya berakhir di 403: anggota hasil seeder masuk,
     * lalu benar-benar sampai ke halamannya.
     */
    #[Test]
    public function a_seeded_member_reaches_their_portal_instead_of_a_403(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
        $this->flushPermissionCache();

        $user = User::factory()->create();
        $user->assignRole('Anggota Perpustakaan');
        $this->flushPermissionCache();
        Member::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user->fresh());

        $this->get('/')->assertRedirect(route('member.portal.loans'));
        $this->get(route('member.portal.loans'))->assertOk();
        $this->get(route('member.portal.history'))->assertOk();
        $this->get(route('member.portal.fines'))->assertOk();
    }

    #[Test]
    public function a_seeded_member_still_cannot_reach_the_staff_area(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
        $this->flushPermissionCache();

        $user = User::factory()->create();
        $user->assignRole('Anggota Perpustakaan');
        $this->flushPermissionCache();

        $this->actingAs($user->fresh());

        $this->get(route('admin.dashboard.index'))->assertForbidden();
        $this->get(route('admin.members.index'))->assertForbidden();
        $this->get(route('admin.circulation.loans.active'))->assertForbidden();
    }

    /**
     * Izin untuk modul yang tidak ada adalah janji yang tidak dapat ditepati.
     */
    #[Test]
    public function the_member_role_no_longer_holds_permissions_without_a_feature(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);

        $held = Role::findByName('Anggota Perpustakaan', 'web')
            ->permissions->pluck('name');

        foreach (['own_reservations.view', 'own_reservations.create', 'own_reservations.cancel', 'opac.search', 'opac.view_detail'] as $gone) {
            $this->assertFalse($held->contains($gone), "Izin {$gone} masih dipegang tetapi tidak punya fitur.");
        }
    }
}
