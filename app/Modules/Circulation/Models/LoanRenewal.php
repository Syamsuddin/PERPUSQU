<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRenewal extends Model
{
    protected $table = 'loan_renewals';

    public $timestamps = false;

    protected $fillable = ['loan_id', 'old_due_date', 'new_due_date', 'renewed_by', 'notes'];

    protected function casts(): array
    {
        return ['old_due_date' => 'datetime', 'new_due_date' => 'datetime', 'created_at' => 'datetime'];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}
