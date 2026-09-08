<?php

namespace Database\Factories;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\LoanReminder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoanReminder>
 */
class LoanReminderFactory extends Factory
{
    protected $model = LoanReminder::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'kind' => LoanReminder::KIND_DUE_SOON,
            'channel' => 'mail',
            'sent_at' => now(),
        ];
    }

    public function kind(string $kind): static
    {
        return $this->state(fn () => ['kind' => $kind]);
    }
}
