<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = ['name', 'normalized_name', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function bibliographicRecords(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Modules\Catalog\Models\BibliographicRecord::class,
            'bibliographic_record_authors',
            'author_id',
            'bibliographic_record_id'
        );
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where('name', 'like', "%{$keyword}%");
    }
}
