<?php

namespace App\Modules\Circulation\Support;

/**
 * Aritmetika tanggal jatuh tempo — dan hanya itu.
 *
 * Kelas ini sengaja tidak tahu-menahu soal jenis anggota maupun kebijakan
 * perpustakaan. Lama pinjam datang sebagai argumen dari OperationalRules,
 * sehingga kebijakan punya satu rumah (tabel `system_settings`) dan perhitungan
 * tanggal tetap dapat diuji tanpa basis data.
 */
class DueDateCalculator
{
    public static function calculate(int $loanPeriodDays, ?\DateTime $loanDate = null): \DateTime
    {
        $base = $loanDate ?? now();

        return (clone $base)->addDays(max(0, $loanPeriodDays));
    }

    public static function calculateRenewal(\DateTime $currentDueDate, int $renewalPeriodDays): \DateTime
    {
        return (clone $currentDueDate)->addDays(max(0, $renewalPeriodDays));
    }
}
