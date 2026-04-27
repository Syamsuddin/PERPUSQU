<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Faculty;
use Illuminate\Pagination\LengthAwarePaginator;

class FacultyService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Faculty::withCount('studyPrograms')
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Faculty
    {
        $faculty = Faculty::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($faculty)->log('Fakultas dibuat: ' . $faculty->name);
        return $faculty;
    }

    public function update(Faculty $faculty, array $data): Faculty
    {
        $faculty->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($faculty)->log('Fakultas diperbarui: ' . $faculty->name);
        return $faculty;
    }

    public function delete(Faculty $faculty): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($faculty)->log('Fakultas dihapus: ' . $faculty->name);
        $faculty->delete();
    }
}
