<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\RackLocation;
use Illuminate\Pagination\LengthAwarePaginator;

class RackLocationService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return RackLocation::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): RackLocation
    {
        $rl = RackLocation::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($rl)->log('Lokasi Rak dibuat: ' . $rl->name);
        return $rl;
    }

    public function update(RackLocation $rackLocation, array $data): RackLocation
    {
        $rackLocation->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($rackLocation)->log('Lokasi Rak diperbarui: ' . $rackLocation->name);
        return $rackLocation;
    }

    public function delete(RackLocation $rackLocation): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($rackLocation)->log('Lokasi Rak dihapus: ' . $rackLocation->name);
        $rackLocation->delete();
    }
}
