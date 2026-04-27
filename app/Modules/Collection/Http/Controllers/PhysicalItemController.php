<?php

namespace App\Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Services\PhysicalItemService;
use App\Modules\Collection\Services\PhysicalItemStatusService;
use App\Modules\Collection\Support\PhysicalItemStateGuard;
use App\Modules\Collection\Http\Requests\StorePhysicalItemRequest;
use App\Modules\Collection\Http\Requests\UpdatePhysicalItemRequest;
use App\Modules\Collection\Http\Requests\ChangePhysicalItemStatusRequest;
use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Http\Request;

class PhysicalItemController extends Controller
{
    public function __construct(
        protected PhysicalItemService $service,
        protected PhysicalItemStatusService $statusService,
    ) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        $rackLocations = RackLocation::active()->orderBy('name')->get();
        $itemConditions = ItemCondition::active()->orderBy('name')->get();
        return view('modules.collection.items.index', compact('items', 'rackLocations', 'itemConditions'));
    }

    public function create()
    {
        return view('modules.collection.items.create', $this->getFormData());
    }

    public function store(StorePhysicalItemRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.collections.items.index')->with('success', 'Item fisik berhasil ditambahkan.');
    }

    public function show(PhysicalItem $item)
    {
        $item = $this->service->findWithRelations($item->id);
        $allowedTransitions = PhysicalItemStateGuard::allowedTransitions($item->item_status);
        return view('modules.collection.items.show', compact('item', 'allowedTransitions'));
    }

    public function edit(PhysicalItem $item)
    {
        return view('modules.collection.items.edit', array_merge(
            $this->getFormData(),
            ['item' => $item]
        ));
    }

    public function update(UpdatePhysicalItemRequest $request, PhysicalItem $item)
    {
        $this->service->update($item, $request->validated());
        return redirect()->route('admin.collections.items.index')->with('success', 'Item fisik berhasil diperbarui.');
    }

    public function destroy(PhysicalItem $item)
    {
        try {
            $this->service->delete($item);
            return redirect()->route('admin.collections.items.index')->with('success', 'Item fisik berhasil dihapus.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus(ChangePhysicalItemStatusRequest $request, PhysicalItem $item)
    {
        try {
            $this->statusService->changeStatus($item, $request->new_status, $request->reason);
            return redirect()->route('admin.collections.items.show', $item)->with('success', 'Status item berhasil diubah.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function history(Request $request, PhysicalItem $item)
    {
        $histories = $this->service->getStatusHistory($item, $request->all());
        return view('modules.collection.items.history', compact('item', 'histories'));
    }

    protected function getFormData(): array
    {
        return [
            'bibliographicRecords' => BibliographicRecord::orderBy('title')->get(),
            'rackLocations' => RackLocation::active()->orderBy('name')->get(),
            'itemConditions' => ItemCondition::active()->orderBy('name')->get(),
        ];
    }
}
