<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleManagementService
{
    public function getPaginatedRoles(array $filters): LengthAwarePaginator
    {
        return Role::withCount('permissions', 'users')
            ->keyword($filters['keyword'] ?? null)
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function createRole(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);

        activity('role_management')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role baru dibuat: '.$role->name);

        return $role;
    }

    public function updateRole(Role $role, array $data): Role
    {
        $role->update(['name' => $data['name']]);

        activity('role_management')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role diperbarui: '.$role->name);

        return $role;
    }

    public function deleteRole(Role $role): void
    {
        activity('role_management')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role dihapus: '.$role->name);

        $role->delete();
    }

    public function assignPermissions(Role $role, array $permissionIds): Role
    {
        DB::transaction(function () use ($role, $permissionIds) {
            $role->syncPermissions($permissionIds);

            activity('role_management')
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->withProperties(['permission_count' => count($permissionIds)])
                ->log('Permission role diperbarui');
        });

        return $role->load('permissions');
    }
}
