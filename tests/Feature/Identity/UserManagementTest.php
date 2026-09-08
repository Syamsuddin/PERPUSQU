<?php

namespace Tests\Feature\Identity;

use App\Modules\Identity\Models\User;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // StoreUserRequest memakai Password::uncompromised(), yang memanggil
        // API HaveIBeenPwned lewat jaringan. Test tidak boleh bergantung pada
        // layanan luar, jadi pemeriksanya diganti dengan yang selalu meloloskan.
        $this->swap(UncompromisedVerifier::class, new class implements UncompromisedVerifier
        {
            public function verify($data): bool
            {
                return true;
            }
        });
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nur Aisyah',
            'username' => 'nur_aisyah',
            'email' => 'nur.aisyah@perpusqu.test',
            'password' => 'Kt#9pLm2Vx!Qz7',
            'role_ids' => [Role::findOrCreate('Pustakawan', 'web')->id],
        ], $overrides);
    }

    #[Test]
    public function an_administrator_can_create_a_user_with_a_role(): void
    {
        $this->actingAsUserWith(['users.create', 'users.view']);

        $this->post(route('admin.access.users.store'), $this->validPayload())
            ->assertRedirect(route('admin.access.users.index'))
            ->assertSessionHas('success');

        $user = User::firstWhere('username', 'nur_aisyah');
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Pustakawan'));
        $this->assertTrue($user->is_active);
    }

    /**
     * Username dan email disimpan dalam huruf kecil agar pencarian dan
     * pemeriksaan keunikan tidak bergantung pada cara petugas mengetik.
     */
    #[Test]
    public function the_username_and_email_are_stored_in_lower_case(): void
    {
        $this->actingAsUserWith(['users.create', 'users.view']);

        $this->post(route('admin.access.users.store'), $this->validPayload([
            'username' => 'Nur_Aisyah',
            'email' => 'Nur.Aisyah@PerpusQu.Test',
        ]));

        $this->assertDatabaseHas('users', [
            'username' => 'nur_aisyah',
            'email' => 'nur.aisyah@perpusqu.test',
        ]);
    }

    #[Test]
    public function the_password_is_stored_hashed_never_in_the_clear(): void
    {
        $this->actingAsUserWith(['users.create', 'users.view']);

        $this->post(route('admin.access.users.store'), $this->validPayload());

        $user = User::firstWhere('username', 'nur_aisyah');
        $this->assertNotSame('Kt#9pLm2Vx!Qz7', $user->password);
        $this->assertTrue(Hash::check('Kt#9pLm2Vx!Qz7', $user->password));
    }

    #[Test]
    #[DataProvider('weakPasswords')]
    public function a_weak_password_is_refused(string $password): void
    {
        $this->actingAsUserWith(['users.create']);

        $this->post(route('admin.access.users.store'), $this->validPayload(['password' => $password]))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['username' => 'nur_aisyah']);
    }

    public static function weakPasswords(): array
    {
        return [
            'terlalu pendek' => ['Ab1!xY'],
            'tanpa angka' => ['PasswordTanpaAngka!'],
            'tanpa simbol' => ['PasswordTanpa1Simbol'],
            'tanpa huruf besar' => ['kt#9plm2vx!qz7'],
        ];
    }

    #[Test]
    public function a_user_must_be_given_at_least_one_role(): void
    {
        $this->actingAsUserWith(['users.create']);

        $this->post(route('admin.access.users.store'), $this->validPayload(['role_ids' => []]))
            ->assertSessionHasErrors('role_ids');
    }

    #[Test]
    public function the_username_and_email_must_be_unique(): void
    {
        $this->actingAsUserWith(['users.create']);
        User::factory()->create(['username' => 'kembar', 'email' => 'kembar@perpusqu.test']);

        $this->post(route('admin.access.users.store'), $this->validPayload(['username' => 'kembar']))
            ->assertSessionHasErrors('username');
        $this->post(route('admin.access.users.store'), $this->validPayload(['email' => 'kembar@perpusqu.test']))
            ->assertSessionHasErrors('email');
    }

    #[Test]
    public function a_username_with_spaces_or_symbols_is_refused(): void
    {
        $this->actingAsUserWith(['users.create']);

        $this->post(route('admin.access.users.store'), $this->validPayload(['username' => 'nur aisyah']))
            ->assertSessionHasErrors('username');
    }

    /**
     * Formulir ubah data pengguna tidak boleh menjadi jalan pintas mengganti
     * password — itu punya endpoint sendiri yang tercatat di audit.
     */
    #[Test]
    public function the_edit_form_cannot_change_a_password(): void
    {
        $this->actingAsUserWith(['users.update', 'users.view']);
        $user = User::factory()->create(['password' => Hash::make('rahasia-lama-123')]);

        $this->put(route('admin.access.users.update', $user), [
            'name' => 'Nama Baru',
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'PasswordSelundupan1!',
        ]);

        $this->assertTrue(Hash::check('rahasia-lama-123', $user->fresh()->password));
        $this->assertSame('Nama Baru', $user->fresh()->name);
    }

    #[Test]
    public function an_administrator_can_reset_a_password(): void
    {
        $this->actingAsUserWith(['users.reset_password', 'users.view']);
        $user = User::factory()->create();

        $this->from(route('admin.access.users.show', $user))
            ->patch(route('admin.access.users.reset_password', $user), [
                'new_password' => 'PasswordBaru2026!',
                'new_password_confirmation' => 'PasswordBaru2026!',
            ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('PasswordBaru2026!', $user->fresh()->password));
    }

    #[Test]
    public function a_password_reset_needs_a_matching_confirmation(): void
    {
        $this->actingAsUserWith(['users.reset_password', 'users.view']);
        $user = User::factory()->create();
        $original = $user->password;

        $this->patch(route('admin.access.users.reset_password', $user), [
            'new_password' => 'PasswordBaru2026!',
            'new_password_confirmation' => 'BedaSendiri2026!',
        ])->assertSessionHasErrors('new_password');

        $this->assertSame($original, $user->fresh()->password);
    }

    #[Test]
    public function activating_toggles_the_account_state(): void
    {
        $this->actingAsUserWith(['users.activate', 'users.view']);
        $user = User::factory()->create(['is_active' => true]);

        $this->from(route('admin.access.users.show', $user))
            ->patch(route('admin.access.users.activate', $user))->assertSessionHas('success');
        $this->assertFalse($user->fresh()->is_active);

        $this->from(route('admin.access.users.show', $user))
            ->patch(route('admin.access.users.activate', $user));
        $this->assertTrue($user->fresh()->is_active);
    }

    /**
     * Menghapus akun sendiri akan mengunci administrator keluar dari sistem.
     */
    #[Test]
    public function an_administrator_cannot_delete_their_own_account(): void
    {
        $admin = $this->actingAsUserWith(['users.delete', 'users.view']);

        $this->from(route('admin.access.users.index'))
            ->delete(route('admin.access.users.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'deleted_at' => null]);
    }

    #[Test]
    public function deleting_another_user_soft_deletes_them(): void
    {
        $this->actingAsUserWith(['users.delete', 'users.view']);
        $other = User::factory()->create();

        $this->delete(route('admin.access.users.destroy', $other))->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $other->id]);
    }

    #[Test]
    public function roles_can_be_reassigned_and_the_previous_ones_are_dropped(): void
    {
        $this->actingAsUserWith(['user_roles.assign', 'users.view']);
        $user = User::factory()->create();
        $pustakawan = Role::findOrCreate('Pustakawan', 'web');
        $sirkulasi = Role::findOrCreate('Petugas Sirkulasi', 'web');
        $user->assignRole($pustakawan);

        $this->from(route('admin.access.users.show', $user))
            ->patch(route('admin.access.users.roles.update', $user), ['role_ids' => [$sirkulasi->id]])
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->hasRole('Petugas Sirkulasi'));
        $this->assertFalse($user->hasRole('Pustakawan'));
    }

    #[Test]
    public function a_user_cannot_be_left_without_any_role(): void
    {
        $this->actingAsUserWith(['user_roles.assign', 'users.view']);
        $user = User::factory()->create();

        $this->patch(route('admin.access.users.roles.update', $user), ['role_ids' => []])
            ->assertSessionHasErrors('role_ids');
    }

    #[Test]
    public function the_user_screens_render_for_an_authorised_administrator(): void
    {
        $this->actingAsUserWith(['users.view', 'users.create', 'users.update']);
        $user = User::factory()->create(['name' => 'Fikri Maulana']);

        $this->get(route('admin.access.users.index'))->assertOk();
        $this->get(route('admin.access.users.create'))->assertOk();
        $this->get(route('admin.access.users.show', $user))->assertOk()->assertSee('Fikri Maulana');
        $this->get(route('admin.access.users.edit', $user))->assertOk();
    }

    #[Test]
    public function a_user_without_access_permissions_is_locked_out_of_user_administration(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);
        $target = User::factory()->create();

        $this->get(route('admin.access.users.index'))->assertForbidden();
        $this->post(route('admin.access.users.store'), $this->validPayload())->assertForbidden();
        $this->delete(route('admin.access.users.destroy', $target))->assertForbidden();
        $this->patch(route('admin.access.users.roles.update', $target), ['role_ids' => [1]])->assertForbidden();
    }

    /**
     * Eskalasi hak: pengguna biasa tidak boleh mengangkat dirinya sendiri
     * menjadi Super Admin lewat endpoint pengelolaan role.
     */
    #[Test]
    public function a_user_cannot_grant_themselves_the_super_admin_role(): void
    {
        $user = $this->actingAsUserWith(['core.view_dashboard']);
        $superAdmin = Role::findOrCreate('Super Admin', 'web');

        $this->patch(route('admin.access.users.roles.update', $user), ['role_ids' => [$superAdmin->id]])
            ->assertForbidden();

        $this->assertFalse($user->fresh()->hasRole('Super Admin'));
    }
}
