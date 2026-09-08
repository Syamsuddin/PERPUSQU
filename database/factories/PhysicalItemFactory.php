<?php

namespace Database\Factories;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PhysicalItem>
 */
class PhysicalItemFactory extends Factory
{
    protected $model = PhysicalItem::class;

    public function definition(): array
    {
        return [
            'bibliographic_record_id' => BibliographicRecord::factory(),
            'rack_location_id' => null,
            'item_condition_id' => null,
            'barcode' => 'BC'.fake()->unique()->numerify('##########'),
            'inventory_code' => 'INV'.fake()->unique()->numerify('##########'),
            'acquisition_date' => fake()->dateTimeBetween('-5 years')->format('Y-m-d'),
            'item_status' => 'available',
            'notes' => null,
        ];
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['item_status' => $status]);
    }

    public function available(): static
    {
        return $this->status('available');
    }

    public function loaned(): static
    {
        return $this->status('loaned');
    }

    public function damaged(): static
    {
        return $this->status('damaged');
    }

    public function lost(): static
    {
        return $this->status('lost');
    }
}
