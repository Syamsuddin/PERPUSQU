<?php

namespace App\Modules\Circulation\Support;

class DueDateCalculator
{
    /**
     * Default loan period by member type (in days)
     */
    protected static array $loanPeriods = [
        'student' => 14,
        'lecturer' => 30,
        'staff' => 14,
        'alumni' => 7,
        'guest' => 7,
    ];

    protected static int $defaultPeriod = 14;

    protected static int $renewalPeriod = 7;

    protected static int $maxRenewals = 2;

    public static function calculate(string $memberType, ?\DateTime $loanDate = null): \DateTime
    {
        $days = self::$loanPeriods[$memberType] ?? self::$defaultPeriod;
        $base = $loanDate ?? now();

        return (clone $base)->addDays($days);
    }

    public static function calculateRenewal(\DateTime $currentDueDate): \DateTime
    {
        return (clone $currentDueDate)->addDays(self::$renewalPeriod);
    }

    public static function maxRenewals(): int
    {
        return self::$maxRenewals;
    }

    public static function loanPeriodDays(string $memberType): int
    {
        return self::$loanPeriods[$memberType] ?? self::$defaultPeriod;
    }
}
