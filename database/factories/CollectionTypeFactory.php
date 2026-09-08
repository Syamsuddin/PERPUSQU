<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\CollectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CollectionType>
 */
class CollectionTypeFactory extends Factory
{
    protected $model = CollectionType::class;

    public function definition(): array
    {
        return [
            'code' => 'CT'.fake()->unique()->numberBetween(100, 99999),
            'name' => 'Jenis '.fake()->unique()->words(2, true),
            'is_active' => true,
        ];
    }
}
