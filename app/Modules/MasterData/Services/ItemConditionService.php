<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemConditionService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return ItemCondition::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->orderBy('severity_level')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): ItemCondition
    {
        $ic = ItemCondition::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($ic)->log('Kondisi Item dibuat: ' . $ic->name);
        return $ic;
    }

    public function update(ItemCondition $itemCondition, array $data): ItemCondition
    {
        $itemCondition->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($itemCondition)->log('Kondisi Item diperbarui: ' . $itemCondition->name);
        return $itemCondition;
    }

    public function delete(ItemCondition $itemCondition): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($itemCondition)->log('Kondisi Item dihapus: ' . $itemCondition->name);
        $itemCondition->delete();
    }
}
