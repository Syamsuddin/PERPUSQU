<?php

namespace App\Modules\Circulation\Support;

/**
 * Perkalian hari keterlambatan dengan tarif harian — dan hanya itu.
 * Tarifnya datang sebagai argumen dari OperationalRules.
 */
class FineAmountCalculator
{
    public static function calculate(int $lateDays, float $perDayRate): float
    {
        return max(0, $lateDays) * max(0, $perDayRate);
    }
}
