<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('??').fake()->unique()->numberBetween(1, 9999),
            'name' => 'Bahasa '.fake()->unique()->word(),
            'is_active' => true,
        ];
    }
}
