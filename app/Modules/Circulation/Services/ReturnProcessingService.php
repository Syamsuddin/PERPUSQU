<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\ReturnTransaction;
use App\Modules\Circulation\Support\FineAmountCalculator;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Models\PhysicalItemStatusHistory;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReturnProcessingService
{
    public function processReturn(string $barcode, ?int $returnedConditionId = null, ?string $notes = null): array
    {
        $item = PhysicalItem::where('barcode', $barcode)->first();
        if (! $item) {
            throw new InvalidArgumentException("Item dengan barcode '{$barcode}' tidak ditemukan.");
        }

        $loan = Loan::where('physical_item_id', $item->id)
            ->where('loan_status', 'active')
            ->first();

        if (! $loan) {
            throw new InvalidArgumentException("Tidak ada pinjaman aktif untuk item '{$barcode}'.");
        }

        return DB::transaction(function () use ($loan, $item, $returnedConditionId, $notes) {
            $returnedAt = now();
            $lateDays = max(0, (int) $loan->due_date->diffInDays($returnedAt, false));
            $fineAmount = $lateDays > 0 ? FineAmountCalculator::calculate($lateDays) : 0;

            // Create return transaction
            $return = ReturnTransaction::create([
                'loan_id' => $loan->id,
                'physical_item_id' => $item->id,
                'returned_at' => $returnedAt,
                'returned_by' => auth()->id(),
                'returned_condition_id' => $returnedConditionId,
                'late_days' => $lateDays,
                'fine_amount' => $fineAmount,
                'notes' => $notes,
            ]);

            // Close loan
            $loan->update([
                'loan_status' => 'returned',
                'returned_at' => $returnedAt,
                'closed_by' => auth()->id(),
            ]);

            // Update item status back to available
            $newItemStatus = 'available';
            $item->update(['item_status' => $newItemStatus]);

            // Record item status history
            PhysicalItemStatusHistory::create([
                'physical_item_id' => $item->id,
                'old_status' => 'loaned',
                'new_status' => $newItemStatus,
                'changed_by' => auth()->id(),
                'reason' => 'Dikembalikan dari pinjaman #'.$loan->id,
            ]);

            // Create fine if overdue
            $fine = null;
            if ($fineAmount > 0) {
                $fine = Fine::create([
                    'loan_id' => $loan->id,
                    'member_id' => $loan->member_id,
                    'fine_type' => 'overdue',
                    'amount' => $fineAmount,
                    'late_days' => $lateDays,
                    'status' => 'outstanding',
                    'notes' => "Keterlambatan {$lateDays} hari",
                ]);
            }

            activity('circulation')
                ->causedBy(auth()->user())
                ->performedOn($loan)
                ->withProperties([
                    'barcode' => $item->barcode,
                    'late_days' => $lateDays,
                    'fine_amount' => $fineAmount,
                ])
                ->log("Pengembalian: {$item->barcode}".($lateDays > 0 ? " (terlambat {$lateDays} hari)" : ''));

            return [
                'return' => $return,
                'loan' => $loan,
                'fine' => $fine,
                'late_days' => $lateDays,
                'fine_amount' => $fineAmount,
            ];
        });
    }
}
