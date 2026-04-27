<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Services\ItemConditionService;
use App\Modules\MasterData\Http\Requests\StoreItemConditionRequest;
use App\Modules\MasterData\Http\Requests\UpdateItemConditionRequest;
use Illuminate\Http\Request;

class ItemConditionController extends Controller
{
    public function __construct(protected ItemConditionService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.item_conditions.index', compact('items'));
    }

    public function create() { return view('modules.master_data.item_conditions.create'); }

    public function store(StoreItemConditionRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.item-conditions.index')->with('success', 'Kondisi Item berhasil ditambahkan.');
    }

    public function edit(ItemCondition $itemCondition) { return view('modules.master_data.item_conditions.edit', compact('itemCondition')); }

    public function update(UpdateItemConditionRequest $request, ItemCondition $itemCondition)
    {
        $this->service->update($itemCondition, $request->validated());
        return redirect()->route('admin.master-data.item-conditions.index')->with('success', 'Kondisi Item berhasil diperbarui.');
    }

    public function destroy(ItemCondition $itemCondition)
    {
        $this->service->delete($itemCondition);
        return redirect()->route('admin.master-data.item-conditions.index')->with('success', 'Kondisi Item berhasil dihapus.');
    }
}
