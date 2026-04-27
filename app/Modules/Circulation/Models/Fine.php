<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $table = 'fines';

    protected $fillable = [
        'loan_id', 'member_id', 'fine_type', 'amount', 'late_days', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'late_days' => 'integer'];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeOutstanding($query)
    {
        return $query->where('status', 'outstanding');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
