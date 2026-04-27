<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Classification;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassificationService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Classification::with('parent')
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->orderBy('code')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Classification
    {
        $classification = Classification::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($classification)->log('Klasifikasi dibuat: ' . $classification->name);
        return $classification;
    }

    public function update(Classification $classification, array $data): Classification
    {
        $classification->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($classification)->log('Klasifikasi diperbarui: ' . $classification->name);
        return $classification;
    }

    public function delete(Classification $classification): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($classification)->log('Klasifikasi dihapus: ' . $classification->name);
        $classification->delete();
    }
}
