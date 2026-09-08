<?php

namespace Database\Factories;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\ReturnTransaction;
use App\Modules\Collection\Models\PhysicalItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReturnTransaction>
 */
class ReturnTransactionFactory extends Factory
{
    protected $model = ReturnTransaction::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'physical_item_id' => PhysicalItem::factory(),
            'returned_at' => now(),
            'returned_by' => null,
            'returned_condition_id' => null,
            'late_days' => 0,
            'fine_amount' => 0,
            'notes' => null,
        ];
    }
}
