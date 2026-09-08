<?php

namespace App\Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanReminder extends Model
{
    use HasFactory;

    /** Jatuh tempo sudah dekat. */
    public const KIND_DUE_SOON = 'due_soon';

    /** Jatuh tempo hari ini. */
    public const KIND_DUE_TODAY = 'due_today';

    /** Sudah melewati jatuh tempo. */
    public const KIND_OVERDUE = 'overdue';

    public const KINDS = [self::KIND_DUE_SOON, self::KIND_DUE_TODAY, self::KIND_OVERDUE];

    protected $table = 'loan_reminders';

    protected $fillable = ['loan_id', 'kind', 'channel', 'sent_at'];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
