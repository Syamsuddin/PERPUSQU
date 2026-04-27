<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Subject;
use App\Modules\MasterData\Services\SubjectService;
use App\Modules\MasterData\Http\Requests\StoreSubjectRequest;
use App\Modules\MasterData\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.subjects.index', compact('items'));
    }

    public function create() { return view('modules.master_data.subjects.create'); }

    public function store(StoreSubjectRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.subjects.index')->with('success', 'Subjek berhasil ditambahkan.');
    }

    public function edit(Subject $subject) { return view('modules.master_data.subjects.edit', compact('subject')); }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $this->service->update($subject, $request->validated());
        return redirect()->route('admin.master-data.subjects.index')->with('success', 'Subjek berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $this->service->delete($subject);
        return redirect()->route('admin.master-data.subjects.index')->with('success', 'Subjek berhasil dihapus.');
    }
}
