<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionProfile extends Model
{
    protected $table = 'institution_profiles';

    protected $fillable = [
        'institution_name',
        'library_name',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'about_text',
    ];

    // ── Helpers ──────────────────────────────────────────

    public static function current(): ?self
    {
        return static::first();
    }
}
