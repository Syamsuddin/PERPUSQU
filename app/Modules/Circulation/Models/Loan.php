<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'member_id', 'physical_item_id', 'loan_date', 'due_date',
        'returned_at', 'loan_status', 'loaned_by', 'closed_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'datetime',
            'due_date' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function physicalItem(): BelongsTo
    {
        return $this->belongsTo(PhysicalItem::class);
    }

    public function loanedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loaned_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(LoanRenewal::class);
    }

    public function returnTransaction(): HasOne
    {
        return $this->hasOne(ReturnTransaction::class);
    }

    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('loan_status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('loan_status', 'active')->where('due_date', '<', now());
    }
}
