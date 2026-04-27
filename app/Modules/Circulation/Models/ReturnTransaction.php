<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Identity\Models\User;
use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnTransaction extends Model
{
    protected $table = 'return_transactions';

    protected $fillable = [
        'loan_id', 'physical_item_id', 'returned_at', 'returned_by',
        'returned_condition_id', 'late_days', 'fine_amount', 'notes',
    ];

    protected function casts(): array
    {
        return ['returned_at' => 'datetime', 'fine_amount' => 'decimal:2', 'late_days' => 'integer'];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function physicalItem(): BelongsTo
    {
        return $this->belongsTo(PhysicalItem::class);
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function returnedCondition(): BelongsTo
    {
        return $this->belongsTo(ItemCondition::class, 'returned_condition_id');
    }
}
