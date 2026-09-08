<?php

namespace Database\Factories;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\LoanRenewal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoanRenewal>
 */
class LoanRenewalFactory extends Factory
{
    protected $model = LoanRenewal::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'old_due_date' => now()->addDays(1),
            'new_due_date' => now()->addDays(8),
            'renewed_by' => null,
            'notes' => null,
            'created_at' => now(),
        ];
    }
}
