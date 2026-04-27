<?php

namespace App\Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $table = 'faculties';
    protected $fillable = ['code', 'name', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function studyPrograms(): HasMany { return $this->hasMany(StudyProgram::class); }
    public function members(): HasMany { return $this->hasMany(\App\Modules\Member\Models\Member::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(fn ($q) => $q->where('code', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%"));
    }
}
