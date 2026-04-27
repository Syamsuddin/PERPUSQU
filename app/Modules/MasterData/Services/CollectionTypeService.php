<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\CollectionType;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionTypeService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return CollectionType::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): CollectionType
    {
        $ct = CollectionType::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($ct)->log('Jenis Koleksi dibuat: ' . $ct->name);
        return $ct;
    }

    public function update(CollectionType $collectionType, array $data): CollectionType
    {
        $collectionType->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($collectionType)->log('Jenis Koleksi diperbarui: ' . $collectionType->name);
        return $collectionType;
    }

    public function delete(CollectionType $collectionType): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($collectionType)->log('Jenis Koleksi dihapus: ' . $collectionType->name);
        $collectionType->delete();
    }
}
