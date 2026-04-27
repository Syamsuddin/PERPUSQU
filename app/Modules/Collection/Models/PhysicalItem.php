<?php

namespace App\Modules\Collection\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalItem extends Model
{
    protected $table = 'physical_items';

    protected $fillable = [
        'bibliographic_record_id', 'rack_location_id', 'item_condition_id',
        'barcode', 'inventory_code', 'acquisition_date', 'item_status', 'notes',
    ];

    protected function casts(): array
    {
        return ['acquisition_date' => 'date'];
    }

    public function bibliographicRecord(): BelongsTo { return $this->belongsTo(\App\Modules\Catalog\Models\BibliographicRecord::class); }
    public function rackLocation(): BelongsTo { return $this->belongsTo(\App\Modules\MasterData\Models\RackLocation::class); }
    public function itemCondition(): BelongsTo { return $this->belongsTo(\App\Modules\MasterData\Models\ItemCondition::class); }
    public function statusHistories(): HasMany { return $this->hasMany(PhysicalItemStatusHistory::class); }
    public function loans(): HasMany { return $this->hasMany(\App\Modules\Circulation\Models\Loan::class); }

    public function scopeAvailable($query) { return $query->where('item_status', 'available'); }
    public function scopeKeyword($query, ?string $keyword)
    {
        if (!$keyword) return $query;
        return $query->where(fn ($q) => $q->where('barcode', 'like', "%{$keyword}%")->orWhere('inventory_code', 'like', "%{$keyword}%"));
    }
}
