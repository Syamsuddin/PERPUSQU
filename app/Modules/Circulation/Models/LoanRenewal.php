<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRenewal extends Model
{
    use HasFactory;

    protected $table = 'loan_renewals';

    public $timestamps = false;

    // `created_at` ikut fillable karena $timestamps dimatikan: Eloquent tidak
    // akan mengisinya sendiri, dan LoanRenewalService memang menyetelnya.
    // Tanpa baris ini nilainya dibuang diam-diam dan yang tersimpan adalah
    // jam server basis data (default useCurrent), bukan jam aplikasi.
    protected $fillable = ['loan_id', 'old_due_date', 'new_due_date', 'renewed_by', 'notes', 'created_at'];

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
