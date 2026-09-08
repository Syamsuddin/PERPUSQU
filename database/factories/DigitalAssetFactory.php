<?php

namespace Database\Factories;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DigitalAsset>
 */
class DigitalAssetFactory extends Factory
{
    protected $model = DigitalAsset::class;

    public function definition(): array
    {
        $uuid = (string) Str::uuid();

        return [
            'bibliographic_record_id' => BibliographicRecord::factory(),
            'asset_type' => 'ebook',
            'file_name' => $uuid.'.pdf',
            'original_file_name' => fake()->words(3, true).'.pdf',
            'file_path' => 'digital_assets/'.now()->format('Y/m').'/'.$uuid.'.pdf',
            'mime_type' => 'application/pdf',
            'file_extension' => 'pdf',
            'file_size' => fake()->numberBetween(10_000, 5_000_000),
            'checksum' => hash('sha256', $uuid),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'publication_status' => 'draft',
            'is_public' => false,
            'is_embargoed' => false,
            'embargo_until' => null,
            'ocr_status' => 'not_requested',
            'index_status' => 'pending',
            'uploaded_by' => null,
            'uploaded_at' => now(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'publication_status' => 'published',
            'is_public' => true,
        ]);
    }

    /**
     * Embargo yang masih berlaku — preview publik harus ditolak.
     */
    public function embargoed(): static
    {
        return $this->state(fn () => [
            'is_embargoed' => true,
            'embargo_until' => now()->addMonth(),
        ]);
    }

    /**
     * Embargo yang sudah lewat — preview publik kembali diizinkan.
     */
    public function embargoExpired(): static
    {
        return $this->state(fn () => [
            'is_embargoed' => true,
            'embargo_until' => now()->subDay(),
        ]);
    }
}
