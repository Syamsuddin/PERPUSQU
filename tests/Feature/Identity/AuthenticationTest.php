<?php

namespace Tests\Feature\Identity;

use App\Modules\Identity\Models\User;
use App\Modules\Identity\Services\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function user(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'username' => 'pustakawan',
            'email' => 'pustakawan@perpusqu.test',
            'password' => Hash::make('rahasia123'),
            'is_active' => true,
        ], $attributes));
    }

    #[Test]
    public function the_login_page_is_reachable_by_a_guest(): void
    {
        $this->get(route('auth.login'))->assertOk();
    }

    /**
     * Middleware `guest` menendang user yang sudah masuk ke root, dan root
     * meneruskannya ke dashboard. Yang diuji adalah tujuan akhirnya: pengguna
     * yang sudah login tidak pernah melihat kembali formulir login.
     */
    #[Test]
    public function a_signed_in_user_never_sees_the_login_form_again(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('auth.login'))
            ->assertRedirect('/')
            ->assertSessionMissing('errors');

        $this->followingRedirects()
            ->get(route('auth.login'))
            ->assertOk()
            ->assertDontSee('name="password"', false);
    }

    #[Test]
    public function a_user_can_sign_in_with_a_username(): void
    {
        $user = $this->user();

        $this->post(route('auth.login.attempt'), [
            'login' => 'pustakawan',
            'password' => 'rahasia123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Tujuan setelah login mengikuti wewenang, bukan selalu dashboard admin.
     * Sebelumnya alamatnya ditulis mati, sehingga anggota perpustakaan —
     * yang tidak punya `core.view_dashboard` — selalu mendarat di halaman 403
     * tepat setelah berhasil masuk.
     */
    #[Test]
    public function staff_land_on_the_dashboard_after_signing_in(): void
    {
        $user = $this->user();
        $this->grant($user, ['core.view_dashboard']);

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123'])
            ->assertRedirect(route('admin.dashboard.index'));
    }

    #[Test]
    public function a_library_member_lands_on_their_own_portal(): void
    {
        $user = $this->user();
        $this->grant($user, ['own_loans.view']);

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123'])
            ->assertRedirect(route('member.portal.loans'));
    }

    #[Test]
    public function a_user_with_neither_still_lands_somewhere_they_may_go(): void
    {
        $this->user();

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123'])
            ->assertRedirect(route('opac.home'));
    }

    /**
     * @param  list<string>  $permissions
     */
    private function grant(User $user, array $permissions): void
    {
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->syncPermissions($permissions);
        $this->flushPermissionCache();
    }

    #[Test]
    public function a_user_can_sign_in_with_an_email_address(): void
    {
        $user = $this->user();

        $this->post(route('auth.login.attempt'), [
            'login' => 'pustakawan@perpusqu.test',
            'password' => 'rahasia123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Identitas login tidak boleh peka huruf besar-kecil; layanan menormalkan
     * lewat strtolower(trim(...)).
     */
    #[Test]
    public function the_login_identifier_is_case_insensitive_and_trimmed(): void
    {
        $user = $this->user();

        $this->post(route('auth.login.attempt'), [
            'login' => '  PUSTAKAWAN@PERPUSQU.TEST  ',
            'password' => 'rahasia123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function a_wrong_password_is_refused_without_revealing_which_half_was_wrong(): void
    {
        $this->user();

        $response = $this->from(route('auth.login'))->post(route('auth.login.attempt'), [
            'login' => 'pustakawan',
            'password' => 'password-salah',
        ]);

        $response->assertRedirect(route('auth.login'));
        $response->assertSessionHasErrors(['login' => 'Username/email atau password salah.']);
        $this->assertGuest();
    }

    #[Test]
    public function an_unknown_account_gets_the_same_message_as_a_wrong_password(): void
    {
        $response = $this->post(route('auth.login.attempt'), [
            'login' => 'tidak-ada',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors(['login' => 'Username/email atau password salah.']);
        $this->assertGuest();
    }

    /**
     * Akun nonaktif punya kredensial yang benar tetapi tetap harus ditolak —
     * dan sesinya tidak boleh tertinggal dalam keadaan setengah masuk.
     */
    #[Test]
    public function an_inactive_account_is_refused_and_left_signed_out(): void
    {
        $this->user(['is_active' => false]);

        $response = $this->post(route('auth.login.attempt'), [
            'login' => 'pustakawan',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors(['login' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        $this->assertGuest();
    }

    #[Test]
    public function a_successful_sign_in_stamps_the_last_login_time(): void
    {
        Carbon::setTestNow('2026-09-01 07:45:00');
        $user = $this->user(['last_login_at' => null]);

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123']);

        $this->assertSame('2026-09-01 07:45:00', $user->fresh()->last_login_at->toDateTimeString());
    }

    #[Test]
    public function a_successful_sign_in_is_written_to_the_audit_log(): void
    {
        $user = $this->user();

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'description' => 'Login berhasil',
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Session fixation: id sesi harus berganti setelah login berhasil.
     */
    #[Test]
    public function the_session_id_is_regenerated_after_signing_in(): void
    {
        $this->user();
        $this->get(route('auth.login'));
        $before = session()->getId();

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123']);

        $this->assertNotSame($before, session()->getId());
    }

    #[Test]
    public function the_form_requires_both_fields(): void
    {
        $this->post(route('auth.login.attempt'), [])->assertSessionHasErrors(['login', 'password']);
        $this->assertGuest();
    }

    #[Test]
    public function a_password_shorter_than_six_characters_is_rejected_before_hitting_the_database(): void
    {
        $this->user();

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'abc'])
            ->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * Setelah lima percobaan gagal, akun dikunci sementara — percobaan keenam
     * ditolak bahkan dengan password yang BENAR.
     */
    #[Test]
    public function six_failed_attempts_lock_the_account_even_for_the_correct_password(): void
    {
        $this->user();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'salah'.$i]);
        }

        $response = $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123']);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
        $this->assertStringContainsString(
            'Terlalu banyak percobaan login',
            session('errors')->first('login')
        );
    }

    #[Test]
    public function a_successful_sign_in_clears_the_failed_attempt_counter(): void
    {
        $this->user();
        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'salah']);
        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'salah']);

        $this->post(route('auth.login.attempt'), ['login' => 'pustakawan', 'password' => 'rahasia123']);
        $this->assertAuthenticated();

        $service = app(AuthenticationService::class);
        $this->assertSame(5, $service->getRemainingAttempts('pustakawan'));
        $this->assertFalse($service->isLockedOut('pustakawan'));
    }

    #[Test]
    public function signing_out_ends_the_session_and_returns_to_the_landing_page(): void
    {
        $this->actingAs($this->user());

        $response = $this->post(route('auth.logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    #[Test]
    public function signing_out_is_written_to_the_audit_log(): void
    {
        $user = $this->user();
        $this->actingAs($user);

        $this->post(route('auth.logout'));

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'description' => 'Logout',
            'causer_id' => $user->id,
        ]);
    }

    #[Test]
    public function a_guest_cannot_sign_out(): void
    {
        $this->post(route('auth.logout'))->assertRedirect(route('auth.login'));
    }

    protected function tearDown(): void
    {
        RateLimiter::clear('login-attempt:'.sha1('pustakawan|127.0.0.1'));
        parent::tearDown();
    }
}
