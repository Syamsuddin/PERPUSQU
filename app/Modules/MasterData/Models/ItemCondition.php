<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCondition extends Model
{
    protected $table = 'item_conditions';
    protected $fillable = ['code', 'name', 'severity_level', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean', 'severity_level' => 'integer']; }

    public function physicalItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Collection\Models\PhysicalItem::class);
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(fn ($q) => $q->where('code', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%"));
    }
}
