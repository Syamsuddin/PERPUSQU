<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BibliographicRecordAuthor extends Pivot
{
    use HasFactory;

    protected $table = 'bibliographic_record_authors';

    public $timestamps = false;
}
