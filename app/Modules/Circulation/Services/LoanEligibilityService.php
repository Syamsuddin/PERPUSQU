<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Member\Models\Member;
use App\Modules\Member\Support\MemberEligibilityResolver;

class LoanEligibilityService
{
    protected int $maxActiveLoans = 5;

    public function check(Member $member): array
    {
        $errors = [];

        if (! $member->is_active) {
            $errors[] = 'Anggota tidak aktif.';
        }
        if ($member->is_blocked) {
            $errors[] = 'Anggota sedang diblokir: '.($member->blocked_reason ?? '');
        }
        if (! MemberEligibilityResolver::canBorrow($member)) {
            $errors[] = 'Anggota tidak memenuhi syarat peminjaman.';
        }

        $activeLoansCount = $member->loans()->where('loan_status', 'active')->count();
        if ($activeLoansCount >= $this->maxActiveLoans) {
            $errors[] = "Batas pinjaman aktif tercapai ({$activeLoansCount}/{$this->maxActiveLoans}).";
        }

        $outstandingFines = $member->fines()->where('status', 'outstanding')->sum('amount');
        if ($outstandingFines > 0) {
            $errors[] = 'Memiliki denda belum lunas: Rp '.number_format($outstandingFines, 0, ',', '.');
        }

        return $errors;
    }

    public function isEligible(Member $member): bool
    {
        return empty($this->check($member));
    }
}
