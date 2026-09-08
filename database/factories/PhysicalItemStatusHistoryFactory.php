<?php

namespace Database\Factories;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Models\PhysicalItemStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PhysicalItemStatusHistory>
 */
class PhysicalItemStatusHistoryFactory extends Factory
{
    protected $model = PhysicalItemStatusHistory::class;

    public function definition(): array
    {
        return [
            'physical_item_id' => PhysicalItem::factory(),
            'old_status' => 'available',
            'new_status' => 'loaned',
            'reason' => fake()->sentence(),
            'changed_by' => null,
            'created_at' => now(),
        ];
    }
}
