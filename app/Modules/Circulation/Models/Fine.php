<?php

namespace App\Modules\Circulation\Models;

use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    use HasFactory;

    protected $table = 'fines';

    protected $fillable = [
        'loan_id', 'member_id', 'fine_type', 'amount', 'late_days', 'status', 'notes',
    ];

    protected function casts(): array
    {
        // Rupiah bulat: tidak ada satuan pecahan yang dipakai, dan cast
        // desimal membuat setiap nilai melewati konversi float.
        return ['amount' => 'integer', 'late_days' => 'integer'];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function member(): BelongsTo
    {
        // Denda tetap harus menyebut anggotanya walau keanggotaannya
        // sudah dihapus lunak — tagihan tidak ikut terhapus.
        return $this->belongsTo(Member::class)->withTrashed();
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
