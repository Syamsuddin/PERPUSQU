<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'normalized_name' => Str::lower($name),
            'notes' => null,
            'is_active' => true,
        ];
    }
}
