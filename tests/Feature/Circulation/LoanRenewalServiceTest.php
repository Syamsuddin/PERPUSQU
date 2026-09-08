<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Services\LoanRenewalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoanRenewalServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanRenewalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LoanRenewalService::class);
    }

    #[Test]
    public function it_pushes_the_due_date_seven_days_and_logs_the_renewal(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_renewal']);
        Carbon::setTestNow('2026-07-01 09:00:00');
        $loan = $this->activeLoan(null, null, ['due_date' => '2026-07-05 09:00:00']);

        $renewal = $this->service->renew($loan, 'Diperpanjang di loket');

        $this->assertSame('2026-07-12 09:00:00', $loan->fresh()->due_date->toDateTimeString());
        $this->assertDatabaseHas('loan_renewals', [
            'id' => $renewal->id,
            'loan_id' => $loan->id,
            'old_due_date' => '2026-07-05 09:00:00',
            'new_due_date' => '2026-07-12 09:00:00',
            'renewed_by' => $petugas->id,
            'notes' => 'Diperpanjang di loket',
        ]);
    }

    #[Test]
    public function a_second_renewal_extends_from_the_already_extended_due_date(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        Carbon::setTestNow('2026-07-01 09:00:00');
        $loan = $this->activeLoan(null, null, ['due_date' => '2026-07-05 09:00:00']);

        $this->service->renew($loan);
        $this->service->renew($loan->fresh());

        $this->assertSame('2026-07-19 09:00:00', $loan->fresh()->due_date->toDateTimeString());
        $this->assertSame(2, $loan->renewals()->count());
    }

    /**
     * Batasnya dua kali. Perpanjangan ketiga harus ditolak tanpa mengubah
     * tanggal jatuh tempo sama sekali.
     */
    #[Test]
    public function it_refuses_a_third_renewal(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        Carbon::setTestNow('2026-07-01 09:00:00');
        $loan = $this->activeLoan(null, null, ['due_date' => '2026-07-05 09:00:00']);
        $this->service->renew($loan);
        $this->service->renew($loan->fresh());
        $dueDateAfterTwo = $loan->fresh()->due_date->toDateTimeString();

        try {
            $this->service->renew($loan->fresh());
            $this->fail('Perpanjangan ketiga seharusnya ditolak.');
        } catch (InvalidArgumentException $e) {
            $this->assertSame('Batas perpanjangan tercapai (2/2).', $e->getMessage());
        }

        $this->assertSame($dueDateAfterTwo, $loan->fresh()->due_date->toDateTimeString());
        $this->assertSame(2, $loan->renewals()->count());
    }

    #[Test]
    public function it_refuses_to_renew_an_overdue_loan(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        $loan = $this->overdueLoan(2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pinjaman yang telah melewati jatuh tempo tidak dapat diperpanjang.');

        $this->service->renew($loan);
    }

    #[Test]
    public function it_refuses_to_renew_a_loan_that_is_already_returned(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        $loan = $this->activeLoan(null, null, ['due_date' => now()->addDays(3)]);
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hanya pinjaman aktif yang dapat diperpanjang.');

        $this->service->renew($loan->fresh());
    }

    #[Test]
    public function a_refused_renewal_writes_no_renewal_row(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        $loan = $this->overdueLoan(1);

        try {
            $this->service->renew($loan);
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->assertDatabaseCount('loan_renewals', 0);
    }
}
