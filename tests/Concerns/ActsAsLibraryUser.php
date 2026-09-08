<?php

namespace Tests\Concerns;

use App\Modules\Identity\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Helper otorisasi. Seluruh route admin PERPUSQU dijaga middleware
 * `permission:<nama>`, jadi hampir setiap feature test perlu user dengan
 * himpunan izin yang persis — bukan sekadar "user yang login".
 */
trait ActsAsLibraryUser
{
    /**
     * User dengan HANYA izin yang disebutkan. Dipakai untuk membuktikan
     * sebuah route benar-benar dijaga oleh izin tertentu, bukan lolos karena
     * user kebetulan punya izin lain.
     */
    protected function userWith(array $permissions, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $user->syncPermissions($permissions);
        $this->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * User tanpa izin sama sekali — baseline untuk uji penolakan akses.
     */
    protected function userWithoutPermissions(array $attributes = []): User
    {
        return $this->userWith([], $attributes);
    }

    /**
     * Super Admin lolos semua Gate lewat Gate::before di AppServiceProvider,
     * tanpa perlu satu pun baris permission.
     */
    protected function superAdmin(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));
        $this->flushPermissionCache();

        return $user->fresh();
    }

    /**
     * User dengan role bernama plus daftar izin yang melekat pada role itu.
     */
    protected function userWithRole(string $roleName, array $permissions = [], array $attributes = []): User
    {
        $role = Role::findOrCreate($roleName, 'web');

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        if ($permissions !== []) {
            $role->syncPermissions($permissions);
        }

        $user = User::factory()->create($attributes);
        $user->assignRole($role);
        $this->flushPermissionCache();

        return $user->fresh();
    }

    protected function actingAsSuperAdmin(array $attributes = []): User
    {
        $user = $this->superAdmin($attributes);
        $this->actingAs($user);

        return $user;
    }

    protected function actingAsUserWith(array $permissions, array $attributes = []): User
    {
        $user = $this->userWith($permissions, $attributes);
        $this->actingAs($user);

        return $user;
    }

    protected function flushPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
