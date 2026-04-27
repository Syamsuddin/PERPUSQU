<?php

namespace App\Modules\Member\Services;

use App\Modules\Member\Models\Member;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return Member::with(['faculty', 'studyProgram'])
            ->withCount('loans')
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['member_type']), fn ($q) => $q->where('member_type', $filters['member_type']))
            ->when(isset($filters['faculty_id']), fn ($q) => $q->where('faculty_id', $filters['faculty_id']))
            ->when(isset($filters['study_program_id']), fn ($q) => $q->where('study_program_id', $filters['study_program_id']))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->when(isset($filters['is_blocked']), fn ($q) => $q->where('is_blocked', $filters['is_blocked']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Member
    {
        $data['is_active'] = $data['is_active'] ?? true;
        $data['is_blocked'] = false;

        $member = Member::create($data);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->log('Anggota didaftarkan: '.$member->name);

        return $member;
    }

    public function update(Member $member, array $data): Member
    {
        $member->update($data);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->log('Anggota diperbarui: '.$member->name);

        return $member;
    }

    public function delete(Member $member): void
    {
        if ($member->loans()->where('loan_status', 'active')->exists()) {
            throw new \InvalidArgumentException('Anggota dengan pinjaman aktif tidak dapat dihapus.');
        }

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->log('Anggota dihapus: '.$member->name);

        $member->delete();
    }

    public function activate(Member $member): Member
    {
        $member->update(['is_active' => true]);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties(['action' => 'activate'])
            ->log('Anggota diaktifkan: '.$member->name);

        return $member;
    }

    public function deactivate(Member $member): Member
    {
        if ($member->loans()->where('loan_status', 'active')->exists()) {
            throw new \InvalidArgumentException('Anggota dengan pinjaman aktif tidak dapat dinonaktifkan.');
        }

        $member->update(['is_active' => false]);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties(['action' => 'deactivate'])
            ->log('Anggota dinonaktifkan: '.$member->name);

        return $member;
    }

    public function findWithRelations(int $id): Member
    {
        return Member::with(['faculty', 'studyProgram'])
            ->withCount(['loans', 'fines'])
            ->findOrFail($id);
    }
}
