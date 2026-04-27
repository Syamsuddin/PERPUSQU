<?php

namespace App\Modules\DigitalRepository\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalAssetAccessRule extends Model
{
    protected $table = 'digital_asset_access_rules';

    protected $fillable = ['digital_asset_id', 'access_type', 'access_value', 'notes'];

    public function digitalAsset(): BelongsTo
    {
        return $this->belongsTo(DigitalAsset::class);
    }
}
