<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RackLocation extends Model
{
    protected $table = 'rack_locations';
    protected $fillable = ['code', 'name', 'floor', 'room', 'description', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }

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
