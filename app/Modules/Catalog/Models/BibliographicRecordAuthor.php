<?php

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BibliographicRecordAuthor extends Pivot
{
    protected $table = 'bibliographic_record_authors';

    public $timestamps = false;
}
