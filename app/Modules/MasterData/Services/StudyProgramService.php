<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\StudyProgram;
use Illuminate\Pagination\LengthAwarePaginator;

class StudyProgramService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return StudyProgram::with('faculty')
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['faculty_id']), fn ($q) => $q->where('faculty_id', $filters['faculty_id']))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): StudyProgram
    {
        $sp = StudyProgram::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($sp)->log('Program Studi dibuat: ' . $sp->name);
        return $sp;
    }

    public function update(StudyProgram $studyProgram, array $data): StudyProgram
    {
        $studyProgram->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($studyProgram)->log('Program Studi diperbarui: ' . $studyProgram->name);
        return $studyProgram;
    }

    public function delete(StudyProgram $studyProgram): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($studyProgram)->log('Program Studi dihapus: ' . $studyProgram->name);
        $studyProgram->delete();
    }
}
