<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Publisher;
use Illuminate\Pagination\LengthAwarePaginator;

class PublisherService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Publisher::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Publisher
    {
        $publisher = Publisher::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($publisher)->log('Penerbit dibuat: ' . $publisher->name);
        return $publisher;
    }

    public function update(Publisher $publisher, array $data): Publisher
    {
        $publisher->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($publisher)->log('Penerbit diperbarui: ' . $publisher->name);
        return $publisher;
    }

    public function delete(Publisher $publisher): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($publisher)->log('Penerbit dihapus: ' . $publisher->name);
        $publisher->delete();
    }
}
