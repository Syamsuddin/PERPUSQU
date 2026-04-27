<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionType extends Model
{
    protected $table = 'collection_types';
    protected $fillable = ['name', 'code', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function bibliographicRecords(): HasMany
    {
        return $this->hasMany(\App\Modules\Catalog\Models\BibliographicRecord::class);
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(fn ($q) => $q->where('code', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%"));
    }
}
