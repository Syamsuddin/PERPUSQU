<?php

namespace App\Modules\Catalog\Models;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibliographicRecord extends Model
{
    protected $table = 'bibliographic_records';

    protected $fillable = [
        'title', 'slug', 'publisher_id', 'language_id', 'classification_id',
        'collection_type_id', 'publication_year', 'isbn', 'edition',
        'keywords', 'abstract', 'cover_path', 'publication_status',
        'is_public', 'metadata_json',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'metadata_json' => 'array',
            'publication_year' => 'integer',
        ];
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(CollectionType::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'bibliographic_record_authors', 'bibliographic_record_id', 'author_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'bibliographic_record_subjects', 'bibliographic_record_id', 'subject_id');
    }

    public function physicalItems(): HasMany
    {
        return $this->hasMany(PhysicalItem::class);
    }

    public function digitalAssets(): HasMany
    {
        return $this->hasMany(DigitalAsset::class);
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

        return $query->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('isbn', 'like', "%{$keyword}%")->orWhere('keywords', 'like', "%{$keyword}%"));
    }
}
