<?php

namespace Database\Factories;

use App\Modules\Core\Models\SystemSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SystemSetting>
 */
class SystemSettingFactory extends Factory
{
    protected $model = SystemSetting::class;

    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2, false),
            'value' => (string) fake()->numberBetween(1, 100),
            'type' => 'integer',
            'group_name' => 'general',
            'is_public' => false,
        ];
    }
}
