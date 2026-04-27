<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group_name',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    // ── Scopes ───────────────────────────────────────────

    public function scopeGroup($query, ?string $group)
    {
        if (!$group) return $query;
        return $query->where('group_name', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    // ── Helpers ──────────────────────────────────────────

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::byKey($key)->first();
        return $setting ? $setting->value : $default;
    }
}
