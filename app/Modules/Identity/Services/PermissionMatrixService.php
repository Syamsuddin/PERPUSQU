<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Models\Permission;

class PermissionMatrixService
{
    public function getPermissionsGroupedByModule(): array
    {
        $permissions = Permission::orderBy('name')->get();

        $grouped = [];
        foreach ($permissions as $perm) {
            $parts = explode('.', $perm->name, 2);
            $module = $parts[0] ?? 'other';
            $grouped[$module][] = $perm;
        }

        ksort($grouped);

        return $grouped;
    }
}
