<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Support\DueDateCalculator;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Models\PhysicalItemStatusHistory;
use App\Modules\Member\Models\Member;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LoanTransactionService
{
    public function __construct(
        protected LoanEligibilityService $eligibility,
    ) {}

    public function createLoan(int $memberId, string $barcode, ?string $notes = null): Loan
    {
        $member = Member::findOrFail($memberId);
        $item = PhysicalItem::where('barcode', $barcode)->first();

        if (! $item) {
            throw new InvalidArgumentException("Item dengan barcode '{$barcode}' tidak ditemukan.");
        }

        // Check eligibility
        $errors = $this->eligibility->check($member);
        if (! empty($errors)) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        // Check item available
        if ($item->item_status !== 'available') {
            throw new InvalidArgumentException("Item '{$barcode}' tidak tersedia (status: {$item->item_status}).");
        }

        return DB::transaction(function () use ($member, $item, $notes) {
            $loanDate = now();
            $dueDate = DueDateCalculator::calculate($member->member_type, $loanDate);

            // Create loan
            $loan = Loan::create([
                'member_id' => $member->id,
                'physical_item_id' => $item->id,
                'loan_date' => $loanDate,
                'due_date' => $dueDate,
                'loan_status' => 'active',
                'loaned_by' => auth()->id(),
                'notes' => $notes,
            ]);

            // Update item status to loaned
            $item->update(['item_status' => 'loaned']);

            // Record item status history
            PhysicalItemStatusHistory::create([
                'physical_item_id' => $item->id,
                'old_status' => 'available',
                'new_status' => 'loaned',
                'changed_by' => auth()->id(),
                'reason' => 'Dipinjam oleh '.$member->name.' (Loan #'.$loan->id.')',
            ]);

            activity('circulation')
                ->causedBy(auth()->user())
                ->performedOn($loan)
                ->withProperties(['member' => $member->name, 'barcode' => $item->barcode])
                ->log("Peminjaman baru: {$item->barcode} → {$member->name}");

            return $loan;
        });
    }

    public function getActiveLoans(array $filters = [])
    {
        return Loan::with(['member', 'physicalItem.bibliographicRecord', 'loanedBy'])
            ->where('loan_status', 'active')
            ->when(isset($filters['keyword']), function ($q) use ($filters) {
                $q->whereHas('member', fn ($mq) => $mq->keyword($filters['keyword']))
                    ->orWhereHas('physicalItem', fn ($iq) => $iq->keyword($filters['keyword']));
            })
            ->when(isset($filters['member_id']), fn ($q) => $q->where('member_id', $filters['member_id']))
            ->when(isset($filters['is_overdue']) && $filters['is_overdue'], fn ($q) => $q->where('due_date', '<', now()))
            ->when(isset($filters['from_date']), fn ($q) => $q->whereDate('loan_date', '>=', $filters['from_date']))
            ->when(isset($filters['to_date']), fn ($q) => $q->whereDate('loan_date', '<=', $filters['to_date']))
            ->latest('loan_date')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getHistory(array $filters = [])
    {
        return Loan::with(['member', 'physicalItem.bibliographicRecord', 'loanedBy', 'closedBy'])
            ->when(isset($filters['keyword']), function ($q) use ($filters) {
                $q->whereHas('member', fn ($mq) => $mq->keyword($filters['keyword']))
                    ->orWhereHas('physicalItem', fn ($iq) => $iq->keyword($filters['keyword']));
            })
            ->when(isset($filters['member_id']), fn ($q) => $q->where('member_id', $filters['member_id']))
            ->when(isset($filters['from_date']), fn ($q) => $q->whereDate('loan_date', '>=', $filters['from_date']))
            ->when(isset($filters['to_date']), fn ($q) => $q->whereDate('loan_date', '<=', $filters['to_date']))
            ->latest('loan_date')
            ->paginate($filters['per_page'] ?? 15);
    }
}
