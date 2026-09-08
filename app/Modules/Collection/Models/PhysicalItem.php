<?php

namespace App\Modules\Collection\Models;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Core\Contracts\Restorable;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Models\RackLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $bibliographic_record_id
 * @property int|null $rack_location_id
 * @property int|null $item_condition_id
 * @property string $barcode
 * @property string|null $inventory_code
 * @property Carbon|null $acquisition_date
 * @property string $item_status
 * @property string|null $notes
 * @property-read BibliographicRecord $bibliographicRecord
 * @property-read RackLocation|null $rackLocation
 * @property-read ItemCondition|null $itemCondition
 * @property-read Collection<int, PhysicalItemStatusHistory> $statusHistories
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> keyword(?string $keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static> available()
 */
class PhysicalItem extends Model implements Restorable
{
    use HasFactory, SoftDeletes;

    protected $table = 'physical_items';

    protected $fillable = [
        'bibliographic_record_id', 'rack_location_id', 'item_condition_id',
        'barcode', 'inventory_code', 'acquisition_date', 'item_status', 'notes',
    ];

    protected function casts(): array
    {
        return ['acquisition_date' => 'date'];
    }

    public function bibliographicRecord(): BelongsTo
    {
        return $this->belongsTo(BibliographicRecord::class)->withTrashed();
    }

    public function rackLocation(): BelongsTo
    {
        return $this->belongsTo(RackLocation::class);
    }

    public function itemCondition(): BelongsTo
    {
        return $this->belongsTo(ItemCondition::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(PhysicalItemStatusHistory::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('item_status', 'available');
    }

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where(fn ($q) => $q->where('barcode', 'like', "%{$keyword}%")->orWhere('inventory_code', 'like', "%{$keyword}%"));
    }
}
