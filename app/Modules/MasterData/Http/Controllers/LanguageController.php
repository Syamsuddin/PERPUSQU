<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Services\LanguageService;
use App\Modules\MasterData\Http\Requests\StoreLanguageRequest;
use App\Modules\MasterData\Http\Requests\UpdateLanguageRequest;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct(protected LanguageService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.languages.index', compact('items'));
    }

    public function create() { return view('modules.master_data.languages.create'); }

    public function store(StoreLanguageRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.languages.index')->with('success', 'Bahasa berhasil ditambahkan.');
    }

    public function edit(Language $language)
    {
        return view('modules.master_data.languages.edit', compact('language'));
    }

    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $this->service->update($language, $request->validated());
        return redirect()->route('admin.master-data.languages.index')->with('success', 'Bahasa berhasil diperbarui.');
    }

    public function destroy(Language $language)
    {
        $this->service->delete($language);
        return redirect()->route('admin.master-data.languages.index')->with('success', 'Bahasa berhasil dihapus.');
    }
}
