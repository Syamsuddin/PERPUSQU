<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Services\RackLocationService;
use App\Modules\MasterData\Http\Requests\StoreRackLocationRequest;
use App\Modules\MasterData\Http\Requests\UpdateRackLocationRequest;
use Illuminate\Http\Request;

class RackLocationController extends Controller
{
    public function __construct(protected RackLocationService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.rack_locations.index', compact('items'));
    }

    public function create() { return view('modules.master_data.rack_locations.create'); }

    public function store(StoreRackLocationRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.rack-locations.index')->with('success', 'Lokasi Rak berhasil ditambahkan.');
    }

    public function edit(RackLocation $rackLocation) { return view('modules.master_data.rack_locations.edit', compact('rackLocation')); }

    public function update(UpdateRackLocationRequest $request, RackLocation $rackLocation)
    {
        $this->service->update($rackLocation, $request->validated());
        return redirect()->route('admin.master-data.rack-locations.index')->with('success', 'Lokasi Rak berhasil diperbarui.');
    }

    public function destroy(RackLocation $rackLocation)
    {
        $this->service->delete($rackLocation);
        return redirect()->route('admin.master-data.rack-locations.index')->with('success', 'Lokasi Rak berhasil dihapus.');
    }
}
