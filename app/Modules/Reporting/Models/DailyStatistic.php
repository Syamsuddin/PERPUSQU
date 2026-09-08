<?php

namespace App\Modules\Reporting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    use HasFactory;

    protected $table = 'daily_statistics';

    /** Angka yang menggambarkan keadaan pada saat potret diambil. */
    public const STOCK_METRICS = [
        'titles_total', 'titles_public',
        'items_total', 'items_available', 'items_loaned',
        'members_total', 'members_active', 'members_blocked',
        'loans_active', 'loans_overdue',
        'digital_assets_total', 'digital_assets_public',
        'fines_outstanding_amount',
    ];

    /** Angka yang menggambarkan apa yang terjadi selama hari itu. */
    public const FLOW_METRICS = [
        'loans_created', 'loans_returned', 'fines_raised_amount',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['snapshot_date' => 'date'];
    }

    public function scopeSince($query, \DateTimeInterface $from)
    {
        return $query->where('snapshot_date', '>=', $from);
    }
}
