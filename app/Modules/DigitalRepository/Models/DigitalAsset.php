<?php

namespace App\Modules\DigitalRepository\Models;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $bibliographic_record_id
 * @property string $asset_type
 * @property string $file_name
 * @property string $original_file_name
 * @property string $file_path
 * @property string $mime_type
 * @property string $file_extension
 * @property int $file_size
 * @property string|null $checksum
 * @property string|null $title
 * @property string|null $description
 * @property string $publication_status
 * @property bool $is_public
 * @property bool $is_embargoed
 * @property Carbon|null $embargo_until
 * @property string $ocr_status
 * @property string $index_status
 * @property Carbon|null $uploaded_at
 * @property int|null $uploaded_by
 * @property-read string $file_size_formatted
 * @property-read BibliographicRecord $bibliographicRecord
 * @property-read User|null $uploadedBy
 */
class DigitalAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'digital_assets';

    protected $fillable = [
        'bibliographic_record_id', 'asset_type', 'file_name', 'original_file_name',
        'file_path', 'mime_type', 'file_extension', 'file_size', 'checksum',
        'title', 'description', 'publication_status', 'is_public', 'is_embargoed',
        'embargo_until', 'ocr_status', 'index_status', 'ocr_attempted_at',
        'last_indexed_at', 'uploaded_by', 'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_embargoed' => 'boolean',
            'embargo_until' => 'datetime',
            'ocr_attempted_at' => 'datetime',
            'last_indexed_at' => 'datetime',
            'uploaded_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    public function bibliographicRecord(): BelongsTo
    {
        // Aset digital tetap dapat menunjuk induk yang dihapus lunak,
        // sehingga layar pengelolaan aset tidak menampilkan judul kosong.
        return $this->belongsTo(BibliographicRecord::class)->withTrashed();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function accessRules(): HasMany
    {
        return $this->hasMany(DigitalAssetAccessRule::class);
    }

    public function ocrText(): HasOne
    {
        return $this->hasOne(OcrText::class);
    }

    public function scopePublished($query)
    {
        return $query->where('publication_status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where(fn ($q) => $q
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('original_file_name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%"));
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
