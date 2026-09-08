<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publisher>
 */
class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'city' => fake()->city(),
            'notes' => null,
            'is_active' => true,
        ];
    }
}
