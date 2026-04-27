<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    protected $table = 'study_programs';
    protected $fillable = ['faculty_id', 'code', 'name', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function faculty(): BelongsTo { return $this->belongsTo(Faculty::class); }
    public function members(): HasMany { return $this->hasMany(\App\Modules\Member\Models\Member::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(fn ($q) => $q->where('code', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%"));
    }
}
