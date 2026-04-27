<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Models\StudyProgram;
use App\Modules\MasterData\Services\StudyProgramService;
use App\Modules\MasterData\Http\Requests\StoreStudyProgramRequest;
use App\Modules\MasterData\Http\Requests\UpdateStudyProgramRequest;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    public function __construct(protected StudyProgramService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        $faculties = Faculty::active()->orderBy('name')->get();
        return view('modules.master_data.study_programs.index', compact('items', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::active()->orderBy('name')->get();
        return view('modules.master_data.study_programs.create', compact('faculties'));
    }

    public function store(StoreStudyProgramRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.study-programs.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(StudyProgram $studyProgram)
    {
        $faculties = Faculty::active()->orderBy('name')->get();
        return view('modules.master_data.study_programs.edit', compact('studyProgram', 'faculties'));
    }

    public function update(UpdateStudyProgramRequest $request, StudyProgram $studyProgram)
    {
        $this->service->update($studyProgram, $request->validated());
        return redirect()->route('admin.master-data.study-programs.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(StudyProgram $studyProgram)
    {
        $this->service->delete($studyProgram);
        return redirect()->route('admin.master-data.study-programs.index')->with('success', 'Program Studi berhasil dihapus.');
    }
}
