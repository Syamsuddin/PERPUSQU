<?php

namespace App\Modules\Collection\Services;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Models\PhysicalItemStatusHistory;
use App\Modules\Collection\Support\PhysicalItemStateGuard;
use InvalidArgumentException;

class PhysicalItemStatusService
{
    /**
     * Change item status with guard validation and history recording
     */
    public function changeStatus(PhysicalItem $item, string $newStatus, ?string $reason = null): PhysicalItem
    {
        $oldStatus = $item->item_status;

        // Validate transition
        PhysicalItemStateGuard::assertTransition($oldStatus, $newStatus);

        // Block if loaned item is being changed without return process
        if ($oldStatus === 'loaned' && !in_array($newStatus, ['available', 'damaged', 'repair', 'lost'])) {
            throw new InvalidArgumentException('Item yang sedang dipinjam hanya dapat dikembalikan melalui proses sirkulasi.');
        }

        // Record history
        PhysicalItemStatusHistory::create([
            'physical_item_id' => $item->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'reason' => $reason,
        ]);

        // Update status
        $item->update(['item_status' => $newStatus]);

        activity('collection')
            ->causedBy(auth()->user())
            ->performedOn($item)
            ->withProperties(['from' => $oldStatus, 'to' => $newStatus, 'reason' => $reason])
            ->log("Status item {$item->barcode} diubah: {$oldStatus} → {$newStatus}");

        return $item;
    }
}
