<?php

namespace App\Modules\Circulation\Support;

use Illuminate\Support\Carbon;

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
    /**
     * Tipe Carbon disebut eksplisit, bukan \DateTime: `addDays()` adalah metode
     * Carbon. Dengan type hint \DateTime, memanggilnya memakai objek DateTime
     * biasa akan lolos pemeriksaan tetapi fatal saat berjalan.
     */
    public static function calculate(int $loanPeriodDays, ?Carbon $loanDate = null): Carbon
    {
        $base = $loanDate ?? now();

        return $base->copy()->addDays(max(0, $loanPeriodDays));
    }

    public static function calculateRenewal(Carbon $currentDueDate, int $renewalPeriodDays): Carbon
    {
        return $currentDueDate->copy()->addDays(max(0, $renewalPeriodDays));
    }
}
