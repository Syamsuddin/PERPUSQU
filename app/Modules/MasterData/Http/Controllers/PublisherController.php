<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Services\PublisherService;
use App\Modules\MasterData\Http\Requests\StorePublisherRequest;
use App\Modules\MasterData\Http\Requests\UpdatePublisherRequest;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct(protected PublisherService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.publishers.index', compact('items'));
    }

    public function create() { return view('modules.master_data.publishers.create'); }

    public function store(StorePublisherRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function edit(Publisher $publisher)
    {
        return view('modules.master_data.publishers.edit', compact('publisher'));
    }

    public function update(UpdatePublisherRequest $request, Publisher $publisher)
    {
        $this->service->update($publisher, $request->validated());
        return redirect()->route('admin.master-data.publishers.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        $this->service->delete($publisher);
        return redirect()->route('admin.master-data.publishers.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}
