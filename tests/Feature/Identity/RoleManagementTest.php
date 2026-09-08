<?php

namespace Tests\Feature\Identity;

use App\Modules\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_administrator_can_create_a_role_with_permissions(): void
    {
        $this->actingAsUserWith(['roles.create', 'roles.view']);
        $permissions = collect(['catalog.view', 'catalog.create'])
            ->map(fn ($name) => Permission::findOrCreate($name, 'web'));

        $this->post(route('admin.access.roles.store'), [
            'name' => 'Asisten Katalog',
            'permission_ids' => $permissions->pluck('id')->all(),
        ])->assertRedirect(route('admin.access.roles.index'))->assertSessionHas('success');

        $role = Role::findByName('Asisten Katalog', 'web');
        $this->assertEqualsCanonicalizing(
            ['catalog.view', 'catalog.create'],
            $role->permissions->pluck('name')->all()
        );
    }

    #[Test]
    public function a_role_name_must_be_unique(): void
    {
        $this->actingAsUserWith(['roles.create']);
        Role::findOrCreate('Pustakawan', 'web');

        $this->post(route('admin.access.roles.store'), ['name' => 'Pustakawan'])
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function a_role_name_is_required_and_has_a_minimum_length(): void
    {
        $this->actingAsUserWith(['roles.create']);

        $this->post(route('admin.access.roles.store'), [])->assertSessionHasErrors('name');
        $this->post(route('admin.access.roles.store'), ['name' => 'ab'])->assertSessionHasErrors('name');
    }

    /**
     * Menyunting izin sebuah role harus langsung mengubah apa yang boleh
     * dilakukan penggunanya — cache izin Spatie tidak boleh menahan perubahan.
     */
    #[Test]
    public function changing_a_roles_permissions_immediately_changes_what_its_users_may_do(): void
    {
        $this->actingAsUserWith(['permissions.manage', 'roles.view']);
        $role = Role::findOrCreate('Asisten Katalog', 'web');
        $catalogView = Permission::findOrCreate('catalog.view', 'web');
        $member = User::factory()->create();
        $member->assignRole($role);

        $this->assertFalse($member->fresh()->can('catalog.view'));

        $this->from(route('admin.access.roles.edit', $role))
            ->patch(route('admin.access.roles.permissions.update', $role), ['permission_ids' => [$catalogView->id]])
            ->assertSessionHas('success');

        $this->flushPermissionCache();
        $this->assertTrue($member->fresh()->can('catalog.view'));
    }

    #[Test]
    public function a_permission_update_must_name_at_least_one_permission(): void
    {
        $this->actingAsUserWith(['permissions.manage', 'roles.view']);
        $role = Role::findOrCreate('Asisten Katalog', 'web');

        $this->patch(route('admin.access.roles.permissions.update', $role), ['permission_ids' => []])
            ->assertSessionHasErrors('permission_ids');
    }

    #[Test]
    public function a_permission_update_rejects_an_unknown_permission_id(): void
    {
        $this->actingAsUserWith(['permissions.manage', 'roles.view']);
        $role = Role::findOrCreate('Asisten Katalog', 'web');

        $this->patch(route('admin.access.roles.permissions.update', $role), ['permission_ids' => [999999]])
            ->assertSessionHasErrors('permission_ids.0');
    }

    /**
     * Role yang masih dipakai tidak boleh lenyap begitu saja — penggunanya
     * akan kehilangan seluruh hak akses tanpa jejak.
     */
    #[Test]
    public function a_role_still_assigned_to_someone_cannot_be_deleted(): void
    {
        $this->actingAsUserWith(['roles.delete', 'roles.view']);
        $role = Role::findOrCreate('Asisten Katalog', 'web');
        User::factory()->create()->assignRole($role);

        $this->from(route('admin.access.roles.index'))
            ->delete(route('admin.access.roles.destroy', $role))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    #[Test]
    public function an_unused_role_can_be_deleted(): void
    {
        $this->actingAsUserWith(['roles.delete', 'roles.view']);
        $role = Role::findOrCreate('Role Sementara', 'web');

        $this->delete(route('admin.access.roles.destroy', $role))->assertSessionHas('success');

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    #[Test]
    public function the_role_and_permission_screens_render_for_an_authorised_administrator(): void
    {
        $this->actingAsUserWith(['roles.view', 'roles.create', 'roles.update', 'permissions.view']);
        Permission::findOrCreate('catalog.view', 'web');
        $role = Role::findOrCreate('Pustakawan', 'web');

        $this->get(route('admin.access.roles.index'))->assertOk();
        $this->get(route('admin.access.roles.create'))->assertOk();
        $this->get(route('admin.access.roles.edit', $role))->assertOk();
        $this->get(route('admin.access.permissions.index'))->assertOk();
    }

    #[Test]
    public function a_user_without_role_permissions_cannot_manage_roles(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);
        $role = Role::findOrCreate('Pustakawan', 'web');

        $this->get(route('admin.access.roles.index'))->assertForbidden();
        $this->post(route('admin.access.roles.store'), ['name' => 'Role Selundupan'])->assertForbidden();
        $this->delete(route('admin.access.roles.destroy', $role))->assertForbidden();
        $this->patch(route('admin.access.roles.permissions.update', $role), ['permission_ids' => [1]])->assertForbidden();

        $this->assertDatabaseMissing('roles', ['name' => 'Role Selundupan']);
    }

    /**
     * Gate::before di AppServiceProvider memberi Super Admin akses penuh tanpa
     * satu pun baris permission. Ini pintasan yang kuat, jadi diuji eksplisit.
     */
    #[Test]
    public function a_super_admin_passes_every_gate_without_holding_any_permission(): void
    {
        $superAdmin = $this->actingAsSuperAdmin();

        $this->assertSame([], $superAdmin->getAllPermissions()->pluck('name')->all());
        $this->assertTrue($superAdmin->can('catalog.view'));
        $this->assertTrue($superAdmin->can('permission-yang-tidak-pernah-didaftarkan'));

        $this->get(route('admin.access.roles.index'))->assertOk();
        $this->get(route('admin.access.users.index'))->assertOk();
    }
}
