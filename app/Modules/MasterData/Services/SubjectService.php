<?php

namespace App\Modules\MasterData\Services;

use App\Modules\MasterData\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;

class SubjectService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Subject::keyword($filters['keyword'] ?? null)
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Subject
    {
        $subject = Subject::create($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($subject)->log('Subjek dibuat: ' . $subject->name);
        return $subject;
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        activity('master_data')->causedBy(auth()->user())->performedOn($subject)->log('Subjek diperbarui: ' . $subject->name);
        return $subject;
    }

    public function delete(Subject $subject): void
    {
        activity('master_data')->causedBy(auth()->user())->performedOn($subject)->log('Subjek dihapus: ' . $subject->name);
        $subject->delete();
    }
}
