<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\RackLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RackLocation>
 */
class RackLocationFactory extends Factory
{
    protected $model = RackLocation::class;

    public function definition(): array
    {
        return [
            'code' => 'RAK-'.fake()->unique()->numberBetween(100, 99999),
            'name' => 'Rak '.fake()->words(2, true),
            'floor' => (string) fake()->numberBetween(1, 4),
            'room' => 'Ruang '.fake()->randomLetter(),
            'description' => null,
            'is_active' => true,
        ];
    }
}
