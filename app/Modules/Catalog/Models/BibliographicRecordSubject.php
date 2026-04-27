<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BibliographicRecordSubject extends Pivot
{
    protected $table = 'bibliographic_record_subjects';

    public $timestamps = false;
}
