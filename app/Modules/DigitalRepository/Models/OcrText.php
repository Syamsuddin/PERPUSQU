<?php

namespace App\Modules\DigitalRepository\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OcrText extends Model
{
    protected $table = 'ocr_texts';

    protected $fillable = ['digital_asset_id', 'extracted_text', 'ocr_status', 'ocr_engine', 'ocr_processed_at'];

    protected function casts(): array
    {
        return ['ocr_processed_at' => 'datetime'];
    }

    public function digitalAsset(): BelongsTo
    {
        return $this->belongsTo(DigitalAsset::class);
    }
}
