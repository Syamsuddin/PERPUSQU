<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemCondition>
 */
class ItemConditionFactory extends Factory
{
    protected $model = ItemCondition::class;

    public function definition(): array
    {
        return [
            'code' => 'KND'.fake()->unique()->numberBetween(100, 99999),
            'name' => 'Kondisi '.fake()->unique()->words(2, true),
            'severity_level' => fake()->numberBetween(1, 5),
            'is_active' => true,
        ];
    }
}
