<?php

namespace App\Modules\Identity\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $guard_name = 'web';

    // ── Scopes ───────────────────────────────────────────

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where('name', 'like', "%{$keyword}%");
    }
}
