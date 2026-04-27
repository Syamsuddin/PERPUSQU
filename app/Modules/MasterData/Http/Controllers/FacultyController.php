<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Services\FacultyService;
use App\Modules\MasterData\Http\Requests\StoreFacultyRequest;
use App\Modules\MasterData\Http\Requests\UpdateFacultyRequest;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function __construct(protected FacultyService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.faculties.index', compact('items'));
    }

    public function create() { return view('modules.master_data.faculties.create'); }

    public function store(StoreFacultyRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.faculties.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Faculty $faculty) { return view('modules.master_data.faculties.edit', compact('faculty')); }

    public function update(UpdateFacultyRequest $request, Faculty $faculty)
    {
        $this->service->update($faculty, $request->validated());
        return redirect()->route('admin.master-data.faculties.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Faculty $faculty)
    {
        $this->service->delete($faculty);
        return redirect()->route('admin.master-data.faculties.index')->with('success', 'Fakultas berhasil dihapus.');
    }
}
