<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Services\CollectionTypeService;
use App\Modules\MasterData\Http\Requests\StoreCollectionTypeRequest;
use App\Modules\MasterData\Http\Requests\UpdateCollectionTypeRequest;
use Illuminate\Http\Request;

class CollectionTypeController extends Controller
{
    public function __construct(protected CollectionTypeService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.collection_types.index', compact('items'));
    }

    public function create() { return view('modules.master_data.collection_types.create'); }

    public function store(StoreCollectionTypeRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.collection-types.index')->with('success', 'Jenis Koleksi berhasil ditambahkan.');
    }

    public function edit(CollectionType $collectionType) { return view('modules.master_data.collection_types.edit', ['collectionType' => $collectionType]); }

    public function update(UpdateCollectionTypeRequest $request, CollectionType $collectionType)
    {
        $this->service->update($collectionType, $request->validated());
        return redirect()->route('admin.master-data.collection-types.index')->with('success', 'Jenis Koleksi berhasil diperbarui.');
    }

    public function destroy(CollectionType $collectionType)
    {
        $this->service->delete($collectionType);
        return redirect()->route('admin.master-data.collection-types.index')->with('success', 'Jenis Koleksi berhasil dihapus.');
    }
}
