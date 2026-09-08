<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\Classification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classification>
 */
class ClassificationFactory extends Factory
{
    protected $model = Classification::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'code' => (string) fake()->unique()->numberBetween(1, 999999),
            'name' => fake()->words(2, true),
            'is_active' => true,
        ];
    }

    public function childOf(Classification $parent): static
    {
        return $this->state(fn () => ['parent_id' => $parent->id]);
    }
}
