<?php

namespace Database\Factories;

use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    protected $model = StudyProgram::class;

    public function definition(): array
    {
        return [
            'faculty_id' => Faculty::factory(),
            'code' => 'PS'.fake()->unique()->numberBetween(100, 99999),
            'name' => 'Program Studi '.fake()->words(2, true),
            'is_active' => true,
        ];
    }
}
