<?php

namespace App\Modules\Collection\Services;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Models\PhysicalItemStatusHistory;
use Illuminate\Pagination\LengthAwarePaginator;

class PhysicalItemService
{
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        return PhysicalItem::with(['bibliographicRecord', 'rackLocation', 'itemCondition'])
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['bibliographic_record_id']), fn ($q) => $q->where('bibliographic_record_id', $filters['bibliographic_record_id']))
            ->when(isset($filters['rack_location_id']), fn ($q) => $q->where('rack_location_id', $filters['rack_location_id']))
            ->when(isset($filters['item_condition_id']), fn ($q) => $q->where('item_condition_id', $filters['item_condition_id']))
            ->when(isset($filters['item_status']), fn ($q) => $q->where('item_status', $filters['item_status']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): PhysicalItem
    {
        // Block creating item with 'loaned' status directly
        if (($data['item_status'] ?? 'available') === 'loaned') {
            $data['item_status'] = 'available';
        }

        $item = PhysicalItem::create($data);

        // Record initial status history
        PhysicalItemStatusHistory::create([
            'physical_item_id' => $item->id,
            'old_status' => null,
            'new_status' => $item->item_status,
            'changed_by' => auth()->id(),
            'reason' => 'Item baru dibuat',
        ]);

        activity('collection')
            ->causedBy(auth()->user())
            ->performedOn($item)
            ->log('Item fisik dibuat: ' . $item->barcode);

        return $item;
    }

    public function update(PhysicalItem $item, array $data): PhysicalItem
    {
        // Don't allow direct status change via update, use changeStatus instead
        unset($data['item_status']);

        $item->update($data);

        activity('collection')
            ->causedBy(auth()->user())
            ->performedOn($item)
            ->log('Item fisik diperbarui: ' . $item->barcode);

        return $item;
    }

    public function delete(PhysicalItem $item): void
    {
        if ($item->item_status === 'loaned') {
            throw new \InvalidArgumentException('Item yang sedang dipinjam tidak dapat dihapus.');
        }

        activity('collection')
            ->causedBy(auth()->user())
            ->performedOn($item)
            ->log('Item fisik dihapus: ' . $item->barcode);

        $item->statusHistories()->delete();
        $item->delete();
    }

    public function findWithRelations(int $id): PhysicalItem
    {
        return PhysicalItem::with([
            'bibliographicRecord.authors', 'rackLocation', 'itemCondition',
            'statusHistories' => fn ($q) => $q->latest()->limit(50),
            'statusHistories.changedByUser',
        ])->findOrFail($id);
    }

    public function getStatusHistory(PhysicalItem $item, array $filters = []): LengthAwarePaginator
    {
        return $item->statusHistories()
            ->with('changedByUser')
            ->when(isset($filters['from_date']), fn ($q) => $q->whereDate('created_at', '>=', $filters['from_date']))
            ->when(isset($filters['to_date']), fn ($q) => $q->whereDate('created_at', '<=', $filters['to_date']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }
}
