<?php

namespace App\Modules\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Models\Role;
use App\Modules\Identity\Services\PermissionMatrixService;
use App\Modules\Identity\Services\RoleManagementService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleManagementService $roleService,
        protected PermissionMatrixService $permissionService
    ) {}

    public function index(Request $request)
    {
        $roles = $this->roleService->getPaginatedRoles($request->all());

        return view('modules.identity.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = $this->permissionService->getPermissionsGroupedByModule();

        return view('modules.identity.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100|unique:roles,name',
        ]);

        $role = $this->roleService->createRole($request->all());

        if ($request->filled('permission_ids')) {
            $this->roleService->assignPermissions($role, $request->permission_ids);
        }

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissionGroups = $this->permissionService->getPermissionsGroupedByModule();

        return view('modules.identity.roles.edit', compact('role', 'permissionGroups'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => "required|string|min:3|max:100|unique:roles,name,{$role->id}",
        ]);

        $this->roleService->updateRole($role, $request->all());

        if ($request->has('permission_ids')) {
            $this->roleService->assignPermissions($role, $request->permission_ids ?? []);
        }

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }
        $this->roleService->deleteRole($role);

        return redirect()->route('admin.access.roles.index')->with('success', 'Role berhasil dihapus.');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permission_ids' => 'required|array|min:1',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);
        $this->roleService->assignPermissions($role, $request->permission_ids);

        return back()->with('success', 'Permission role berhasil diperbarui.');
    }
}
