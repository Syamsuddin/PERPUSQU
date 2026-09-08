<?php

namespace App\Modules\Circulation\Services;

use App\Modules\Core\Services\OperationalRules;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Support\MemberEligibilityResolver;

class LoanEligibilityService
{
    public function __construct(
        protected OperationalRules $rules,
    ) {}

    public function check(Member $member): array
    {
        $errors = [];

        // Syarat keanggotaan aktif dan tidak diblokir dapat dimatikan lewat
        // halaman Aturan Operasional; perpustakaan yang mengizinkan pinjam
        // tanpa syarat itu tidak lagi perlu perubahan kode.
        if ($this->rules->requiresActiveMember() && ! $member->is_active) {
            $errors[] = 'Anggota tidak aktif.';
        }
        if ($this->rules->requiresUnblockedMember() && $member->is_blocked) {
            $errors[] = 'Anggota sedang diblokir: '.($member->blocked_reason ?? '');
        }
        // Ringkasan status turunan (17_WORKFLOW_STATE_MACHINE.md §10.1). Hanya
        // bermakna bila kedua syarat di atas menyala; bila salah satunya
        // dimatikan, resolver akan menolak anggota yang justru dianggap layak
        // oleh kebijakan yang sedang berlaku.
        if ($this->rules->requiresActiveMember()
            && $this->rules->requiresUnblockedMember()
            && ! MemberEligibilityResolver::canBorrow($member)) {
            $errors[] = 'Anggota tidak memenuhi syarat peminjaman.';
        }

        $maxActiveLoans = $this->rules->maxActiveLoans();
        $activeLoansCount = $member->loans()->where('loan_status', 'active')->count();
        if ($activeLoansCount >= $maxActiveLoans) {
            $errors[] = "Batas pinjaman aktif tercapai ({$activeLoansCount}/{$maxActiveLoans}).";
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
