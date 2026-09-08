<?php

namespace App\Modules\MasterData\Models;

use App\Modules\Catalog\Models\BibliographicRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = ['name', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function bibliographicRecords(): BelongsToMany
    {
        return $this->belongsToMany(
            BibliographicRecord::class,
            'bibliographic_record_subjects',
            'subject_id',
            'bibliographic_record_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where('name', 'like', "%{$keyword}%");
    }
}
