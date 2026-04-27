<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Services\ClassificationService;
use App\Modules\MasterData\Http\Requests\StoreClassificationRequest;
use App\Modules\MasterData\Http\Requests\UpdateClassificationRequest;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function __construct(protected ClassificationService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.classifications.index', compact('items'));
    }

    public function create()
    {
        $parents = Classification::orderBy('code')->get();
        return view('modules.master_data.classifications.create', compact('parents'));
    }

    public function store(StoreClassificationRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.classifications.index')->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function edit(Classification $classification)
    {
        $parents = Classification::where('id', '!=', $classification->id)->orderBy('code')->get();
        return view('modules.master_data.classifications.edit', compact('classification', 'parents'));
    }

    public function update(UpdateClassificationRequest $request, Classification $classification)
    {
        $this->service->update($classification, $request->validated());
        return redirect()->route('admin.master-data.classifications.index')->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    public function destroy(Classification $classification)
    {
        $this->service->delete($classification);
        return redirect()->route('admin.master-data.classifications.index')->with('success', 'Klasifikasi berhasil dihapus.');
    }
}
