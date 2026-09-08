<?php

namespace Database\Factories;

use App\Modules\Core\Models\InstitutionProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstitutionProfile>
 */
class InstitutionProfileFactory extends Factory
{
    protected $model = InstitutionProfile::class;

    public function definition(): array
    {
        return [
            'institution_name' => 'Institut '.fake()->words(2, true),
            'library_name' => 'Perpustakaan '.fake()->words(2, true),
            'address' => fake()->address(),
            'phone' => fake()->numerify('021#######'),
            'email' => fake()->safeEmail(),
            'website' => 'https://'.fake()->domainName(),
            'logo_path' => null,
            'about_text' => fake()->paragraph(),
        ];
    }
}
