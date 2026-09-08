<?php

namespace Database\Factories;

use App\Modules\Reporting\Models\DailyStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyStatistic>
 */
class DailyStatisticFactory extends Factory
{
    protected $model = DailyStatistic::class;

    public function definition(): array
    {
        return [
            'snapshot_date' => now()->subDay()->toDateString(),
            'titles_total' => 0,
            'titles_public' => 0,
            'items_total' => 0,
            'items_available' => 0,
            'items_loaned' => 0,
            'members_total' => 0,
            'members_active' => 0,
            'members_blocked' => 0,
            'loans_active' => 0,
            'loans_overdue' => 0,
            'digital_assets_total' => 0,
            'digital_assets_public' => 0,
            'loans_created' => 0,
            'loans_returned' => 0,
            'fines_raised_amount' => 0,
            'fines_outstanding_amount' => 0,
        ];
    }

    public function on(string $date): static
    {
        return $this->state(fn () => ['snapshot_date' => $date]);
    }
}
