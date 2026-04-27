<?php

namespace App\Modules\Circulation\Support;

class FineAmountCalculator
{
    protected static float $perDayRate = 1000.00; // Rp 1.000 per hari keterlambatan

    public static function calculate(int $lateDays): float
    {
        return max(0, $lateDays) * self::$perDayRate;
    }

    public static function perDayRate(): float
    {
        return self::$perDayRate;
    }
}
