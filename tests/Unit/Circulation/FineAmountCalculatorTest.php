<?php

namespace Tests\Unit\Circulation;

use App\Modules\Circulation\Support\FineAmountCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tarif harian datang dari `system_settings`; di sini hanya perkaliannya.
 *
 * Seluruhnya bilangan bulat. Rupiah tidak punya satuan pecahan yang dipakai,
 * dan menjaganya tetap integer berarti tidak ada nilai uang yang pernah
 * melewati float.
 */
class FineAmountCalculatorTest extends TestCase
{
    public static function lateDayScenarios(): array
    {
        return [
            'belum terlambat' => [0, 1000, 0],
            'terlambat 1 hari' => [1, 1000, 1000],
            'terlambat 7 hari' => [7, 1000, 7000],
            'terlambat 30 hari' => [30, 1000, 30000],
            'tarif lain' => [3, 2500, 7500],
            'tarif besar' => [365, 25000, 9125000],
        ];
    }

    #[Test]
    #[DataProvider('lateDayScenarios')]
    public function it_multiplies_late_days_by_the_daily_rate(int $lateDays, int $rate, int $expected): void
    {
        $this->assertSame($expected, FineAmountCalculator::calculate($lateDays, $rate));
    }

    /**
     * Pengembalian lebih awal menghasilkan selisih hari negatif. Denda harus
     * berhenti di nol, bukan berubah menjadi kredit bagi anggota.
     */
    #[Test]
    public function it_never_produces_a_negative_fine_for_an_early_return(): void
    {
        $this->assertSame(0, FineAmountCalculator::calculate(-3, 1000));
    }

    /**
     * Tarif nol adalah kebijakan yang sah: perpustakaan yang tidak menarik
     * denda keterlambatan.
     */
    #[Test]
    public function a_zero_rate_means_no_fine_at_all(): void
    {
        $this->assertSame(0, FineAmountCalculator::calculate(30, 0));
    }

    #[Test]
    public function a_negative_rate_cannot_turn_a_fine_into_a_refund(): void
    {
        $this->assertSame(0, FineAmountCalculator::calculate(5, -1000));
    }
}
