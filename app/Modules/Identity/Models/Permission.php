<?php

namespace App\Modules\Identity\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $guard_name = 'web';

    // ── Scopes ───────────────────────────────────────────

    public function scopeByModule($query, ?string $module)
    {
        if (! $module) {
            return $query;
        }

        return $query->where('name', 'like', "{$module}.%");
    }

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where('name', 'like', "%{$keyword}%");
    }
}
