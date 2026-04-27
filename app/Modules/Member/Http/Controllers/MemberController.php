<?php

namespace App\Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Models\StudyProgram;
use App\Modules\Member\Http\Requests\BlockMemberRequest;
use App\Modules\Member\Http\Requests\StoreMemberRequest;
use App\Modules\Member\Http\Requests\UpdateMemberRequest;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Services\MemberBlockingService;
use App\Modules\Member\Services\MemberService;
use App\Modules\Member\Support\MemberEligibilityResolver;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class MemberController extends Controller
{
    public function __construct(
        protected MemberService $service,
        protected MemberBlockingService $blockingService,
    ) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        $faculties = Faculty::active()->orderBy('name')->get();

        return view('modules.member.index', compact('items', 'faculties'));
    }

    public function create()
    {
        return view('modules.member.create', $this->getFormData());
    }

    public function store(StoreMemberRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil didaftarkan.');
    }

    public function show(Member $member)
    {
        $member = $this->service->findWithRelations($member->id);
        $derivedState = MemberEligibilityResolver::derivedState($member);

        return view('modules.member.show', compact('member', 'derivedState'));
    }

    public function edit(Member $member)
    {
        return view('modules.member.edit', array_merge(
            $this->getFormData(),
            ['member' => $member]
        ));
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $this->service->update($member, $request->validated());

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        try {
            $this->service->delete($member);

            return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function activate(Member $member)
    {
        $this->service->activate($member);

        return redirect()->route('admin.members.show', $member)->with('success', 'Anggota berhasil diaktifkan.');
    }

    public function deactivate(Member $member)
    {
        try {
            $this->service->deactivate($member);

            return redirect()->route('admin.members.show', $member)->with('success', 'Anggota berhasil dinonaktifkan.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function block(BlockMemberRequest $request, Member $member)
    {
        $this->blockingService->block($member, $request->blocked_reason);

        return redirect()->route('admin.members.show', $member)->with('success', 'Anggota berhasil diblokir.');
    }

    public function unblock(Member $member)
    {
        $this->blockingService->unblock($member);

        return redirect()->route('admin.members.show', $member)->with('success', 'Anggota berhasil di-unblock.');
    }

    public function history(Request $request, Member $member)
    {
        $activities = Activity::where('subject_type', Member::class)
            ->where('subject_id', $member->id)
            ->when($request->from_date, fn ($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn ($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->latest()
            ->paginate(15);

        return view('modules.member.history', compact('member', 'activities'));
    }

    protected function getFormData(): array
    {
        return [
            'faculties' => Faculty::active()->orderBy('name')->get(),
            'studyPrograms' => StudyProgram::active()->orderBy('name')->get(),
        ];
    }
}
