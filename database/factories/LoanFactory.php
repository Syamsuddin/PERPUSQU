<?php

namespace Database\Factories;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Member\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $loanDate = now()->subDays(3);

        return [
            'member_id' => Member::factory(),
            'physical_item_id' => PhysicalItem::factory()->loaned(),
            'loan_date' => $loanDate,
            'due_date' => (clone $loanDate)->addDays(14),
            'returned_at' => null,
            'loan_status' => 'active',
            'loaned_by' => null,
            'closed_by' => null,
            'notes' => null,
        ];
    }

    /**
     * Jatuh tempo sudah lewat $days hari — pinjaman masih aktif.
     */
    public function overdue(int $days = 5): static
    {
        return $this->state(fn () => [
            'loan_date' => now()->subDays(14 + $days),
            'due_date' => now()->subDays($days),
            'loan_status' => 'active',
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn () => [
            'loan_status' => 'returned',
            'returned_at' => now(),
        ]);
    }
}
