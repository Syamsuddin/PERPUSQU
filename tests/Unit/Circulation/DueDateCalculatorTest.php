<?php

namespace Tests\Unit\Circulation;

use App\Modules\Circulation\Support\DueDateCalculator;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Kalkulator ini hanya menjumlahkan hari. Kebijakan berapa harinya berada di
 * `system_settings` dan diuji di Tests\Feature\Core\OperationalRulesTest —
 * pemisahan itu yang membuat berkas ini tetap berjalan tanpa basis data.
 */
class DueDateCalculatorTest extends TestCase
{
    public static function loanPeriods(): array
    {
        return [
            'tujuh hari' => [7, '2026-03-08 09:30:00'],
            'empat belas hari' => [14, '2026-03-15 09:30:00'],
            'tiga puluh hari' => [30, '2026-03-31 09:30:00'],
            'melintasi pergantian tahun' => [365, '2027-03-01 09:30:00'],
        ];
    }

    #[Test]
    #[DataProvider('loanPeriods')]
    public function it_adds_the_given_number_of_days_to_the_loan_date(int $days, string $expected): void
    {
        $loanDate = Carbon::parse('2026-03-01 09:30:00');

        $dueDate = DueDateCalculator::calculate($days, $loanDate);

        $this->assertSame($expected, Carbon::instance($dueDate)->toDateTimeString());
    }

    /**
     * Jam peminjaman ikut terbawa: pinjaman sore hari jatuh tempo sore hari,
     * bukan tengah malam.
     */
    #[Test]
    public function it_preserves_the_time_of_day(): void
    {
        $dueDate = DueDateCalculator::calculate(14, Carbon::parse('2026-03-01 16:45:00'));

        $this->assertSame('16:45:00', Carbon::instance($dueDate)->format('H:i:s'));
    }

    #[Test]
    public function it_does_not_mutate_the_loan_date_it_was_given(): void
    {
        $loanDate = Carbon::parse('2026-03-01 09:30:00');

        DueDateCalculator::calculate(14, $loanDate);

        $this->assertSame('2026-03-01 09:30:00', $loanDate->toDateTimeString());
    }

    /**
     * Angka negatif tidak boleh menghasilkan jatuh tempo di masa lalu — itu
     * akan membuat pinjaman baru langsung berstatus terlambat.
     */
    #[Test]
    public function a_negative_period_is_treated_as_zero(): void
    {
        $loanDate = Carbon::parse('2026-03-01 09:30:00');

        $dueDate = DueDateCalculator::calculate(-5, $loanDate);

        $this->assertSame('2026-03-01 09:30:00', Carbon::instance($dueDate)->toDateTimeString());
    }

    #[Test]
    public function it_extends_a_renewal_from_the_current_due_date(): void
    {
        $currentDueDate = Carbon::parse('2026-03-15 17:00:00');

        $renewed = DueDateCalculator::calculateRenewal($currentDueDate, 7);

        $this->assertSame('2026-03-22 17:00:00', Carbon::instance($renewed)->toDateTimeString());
        $this->assertSame('2026-03-15 17:00:00', $currentDueDate->toDateTimeString(), 'tanggal asal tidak boleh ikut berubah');
    }

    #[Test]
    public function a_renewal_period_may_differ_from_the_loan_period(): void
    {
        $renewed = DueDateCalculator::calculateRenewal(Carbon::parse('2026-03-15 17:00:00'), 21);

        $this->assertSame('2026-04-05 17:00:00', Carbon::instance($renewed)->toDateTimeString());
    }
}
