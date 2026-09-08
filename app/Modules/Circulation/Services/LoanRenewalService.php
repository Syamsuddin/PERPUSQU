<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Models\LoanRenewal;
use App\Modules\Circulation\Support\DueDateCalculator;
use App\Modules\Core\Services\OperationalRules;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LoanRenewalService
{
    public function __construct(
        protected OperationalRules $rules,
    ) {}

    public function renew(Loan $loan, ?string $notes = null): LoanRenewal
    {
        // Perpanjangan dapat dimatikan seluruhnya lewat halaman Aturan
        // Operasional; diperiksa paling awal agar pesannya jelas menyebut
        // kebijakan, bukan batas hitungan.
        if (! $this->rules->renewalAllowed()) {
            throw new InvalidArgumentException('Perpanjangan pinjaman sedang tidak diizinkan.');
        }

        if ($loan->loan_status !== 'active') {
            throw new InvalidArgumentException('Hanya pinjaman aktif yang dapat diperpanjang.');
        }

        // Check max renewals
        $maxRenewals = $this->rules->maxRenewals();
        $renewalCount = $loan->renewals()->count();
        if ($renewalCount >= $maxRenewals) {
            throw new InvalidArgumentException("Batas perpanjangan tercapai ({$renewalCount}/{$maxRenewals}).");
        }

        // Check overdue — some policies disallow renewal if overdue
        if ($loan->due_date->isPast()) {
            throw new InvalidArgumentException('Pinjaman yang telah melewati jatuh tempo tidak dapat diperpanjang.');
        }

        return DB::transaction(function () use ($loan, $notes) {
            $oldDueDate = $loan->due_date;
            $newDueDate = DueDateCalculator::calculateRenewal($oldDueDate, $this->rules->renewalPeriodDays());

            $renewal = LoanRenewal::create([
                'loan_id' => $loan->id,
                'old_due_date' => $oldDueDate,
                'new_due_date' => $newDueDate,
                'renewed_by' => auth()->id(),
                'notes' => $notes,
                'created_at' => now(),
            ]);

            $loan->update(['due_date' => $newDueDate]);

            activity('circulation')
                ->causedBy(auth()->user())
                ->performedOn($loan)
                ->withProperties(['old_due_date' => $oldDueDate->format('Y-m-d'), 'new_due_date' => $newDueDate->format('Y-m-d')])
                ->log('Perpanjangan pinjaman #'.$loan->id);

            return $renewal;
        });
    }
}
