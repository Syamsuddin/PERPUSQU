<?php

namespace App\Modules\Reporting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon $snapshot_date
 * @property int $titles_total
 * @property int $titles_public
 * @property int $items_total
 * @property int $items_available
 * @property int $items_loaned
 * @property int $members_total
 * @property int $members_active
 * @property int $members_blocked
 * @property int $loans_active
 * @property int $loans_overdue
 * @property int $digital_assets_total
 * @property int $digital_assets_public
 * @property int $loans_created
 * @property int $loans_returned
 * @property int $fines_raised_amount
 * @property int $fines_outstanding_amount
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> since(\DateTimeInterface $from)
 */
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
