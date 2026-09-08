<?php

namespace App\Modules\Circulation\Support;

/**
 * Perkalian hari keterlambatan dengan tarif harian — dan hanya itu.
 * Tarifnya datang sebagai argumen dari OperationalRules.
 *
 * Seluruhnya bilangan bulat: rupiah tidak punya satuan pecahan yang dipakai,
 * dan menjaganya tetap integer berarti tidak ada nilai uang yang pernah
 * melewati float — tempat kesalahan pembulatan menumpuk tanpa terlihat.
 */
class FineAmountCalculator
{
    public static function calculate(int $lateDays, int $perDayRate): int
    {
        return max(0, $lateDays) * max(0, $perDayRate);
    }
}
