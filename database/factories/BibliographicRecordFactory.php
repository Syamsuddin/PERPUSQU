<?php

namespace Database\Factories;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BibliographicRecord>
 */
class BibliographicRecordFactory extends Factory
{
    protected $model = BibliographicRecord::class;

    public function definition(): array
    {
        $title = Str::title(fake()->words(4, true));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'publisher_id' => null,
            'language_id' => null,
            'classification_id' => null,
            'collection_type_id' => CollectionType::factory(),
            'publication_year' => fake()->numberBetween(1990, 2026),
            'isbn' => fake()->unique()->isbn13(),
            'edition' => 'Cetakan '.fake()->numberBetween(1, 5),
            'keywords' => implode(', ', fake()->words(3)),
            'abstract' => fake()->paragraph(),
            'cover_path' => null,
            'publication_status' => 'draft',
            'is_public' => false,
            'metadata_json' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['publication_status' => 'draft']);
    }

    /**
     * Record yang benar-benar terlihat di OPAC: published DAN is_public.
     */
    public function published(): static
    {
        return $this->state(fn () => [
            'publication_status' => 'published',
            'is_public' => true,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['publication_status' => 'unpublished']);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['publication_status' => 'archived']);
    }

    /**
     * Published tapi ditandai non-publik — harus tetap tersembunyi di OPAC.
     */
    public function internal(): static
    {
        return $this->state(fn () => [
            'publication_status' => 'published',
            'is_public' => false,
        ]);
    }

    /**
     * Guard publikasi mewajibkan minimal satu pengarang.
     */
    public function withAuthor(?Author $author = null): static
    {
        return $this->afterCreating(function (BibliographicRecord $record) use ($author) {
            $record->authors()->attach(
                ($author ?? Author::factory()->create())->id,
                ['author_order' => 1, 'created_at' => now(), 'updated_at' => now()]
            );
        });
    }

    public function fullyLinked(): static
    {
        return $this->state(fn () => [
            'publisher_id' => Publisher::factory(),
            'language_id' => Language::factory(),
            'classification_id' => Classification::factory(),
        ]);
    }
}
