<?php

namespace App\Modules\Collection\Models;

use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalItemStatusHistory extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'physical_item_status_histories';

    protected $fillable = [
        'physical_item_id', 'old_status', 'new_status', 'changed_by', 'reason',
    ];

    public function physicalItem(): BelongsTo
    {
        return $this->belongsTo(PhysicalItem::class)->withTrashed();
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
