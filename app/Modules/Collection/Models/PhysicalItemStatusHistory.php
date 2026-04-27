<?php

namespace App\Modules\Collection\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalItemStatusHistory extends Model
{
    const UPDATED_AT = null;
    protected $table = 'physical_item_status_histories';

    protected $fillable = [
        'physical_item_id', 'old_status', 'new_status', 'changed_by', 'reason',
    ];

    public function physicalItem(): BelongsTo { return $this->belongsTo(PhysicalItem::class); }
    public function changedByUser(): BelongsTo { return $this->belongsTo(\App\Modules\Identity\Models\User::class, 'changed_by'); }
}
