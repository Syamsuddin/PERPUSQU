<?php

namespace Database\Factories;

use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'member_number' => 'AGT'.fake()->unique()->numerify('########'),
            'member_type' => 'student',
            'identity_number' => fake()->unique()->numerify('##########'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'faculty_id' => null,
            'study_program_id' => null,
            'is_active' => true,
            'is_blocked' => false,
            'blocked_reason' => null,
            'blocked_at' => null,
            'notes' => null,
        ];
    }

    public function type(string $memberType): static
    {
        return $this->state(fn () => ['member_type' => $memberType]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function blocked(string $reason = 'Melanggar tata tertib'): static
    {
        return $this->state(fn () => [
            'is_blocked' => true,
            'blocked_reason' => $reason,
            'blocked_at' => now(),
        ]);
    }
}
