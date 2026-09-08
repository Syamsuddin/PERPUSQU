<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Services\LoanEligibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Gerbang kelayakan pinjam. Setiap alasan penolakan diuji terisolasi supaya
 * kegagalan menunjuk ke satu aturan, lalu diuji bersamaan untuk memastikan
 * layanan mengumpulkan seluruh alasan, bukan berhenti di yang pertama.
 */
class LoanEligibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanEligibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LoanEligibilityService::class);
    }

    #[Test]
    public function an_active_unblocked_member_without_debt_is_eligible(): void
    {
        $member = $this->eligibleMember();

        $this->assertSame([], $this->service->check($member));
        $this->assertTrue($this->service->isEligible($member));
    }

    #[Test]
    public function an_inactive_member_is_rejected(): void
    {
        $member = $this->eligibleMember(['is_active' => false]);

        $errors = $this->service->check($member);

        $this->assertContains('Anggota tidak aktif.', $errors);
        $this->assertFalse($this->service->isEligible($member));
    }

    #[Test]
    public function a_blocked_member_is_rejected_with_the_blocking_reason(): void
    {
        $member = $this->eligibleMember([
            'is_blocked' => true,
            'blocked_reason' => 'Merusak koleksi',
            'blocked_at' => now(),
        ]);

        $errors = $this->service->check($member);

        $this->assertContains('Anggota sedang diblokir: Merusak koleksi', $errors);
    }

    #[Test]
    public function a_member_at_the_active_loan_ceiling_is_rejected(): void
    {
        $member = $this->eligibleMember();

        for ($i = 0; $i < 5; $i++) {
            $this->activeLoan($member);
        }

        $this->assertContains('Batas pinjaman aktif tercapai (5/5).', $this->service->check($member));
    }

    /**
     * Batasnya lima pinjaman aktif — anggota dengan empat masih boleh meminjam.
     * Off-by-one di sini berarti anggota kehilangan satu hak pinjam.
     */
    #[Test]
    public function a_member_one_below_the_ceiling_is_still_eligible(): void
    {
        $member = $this->eligibleMember();

        for ($i = 0; $i < 4; $i++) {
            $this->activeLoan($member);
        }

        $this->assertTrue($this->service->isEligible($member));
    }

    /**
     * Pinjaman yang sudah dikembalikan tidak boleh ikut dihitung ke kuota.
     */
    #[Test]
    public function returned_loans_do_not_count_towards_the_ceiling(): void
    {
        $member = $this->eligibleMember();

        for ($i = 0; $i < 6; $i++) {
            $this->activeLoan($member)->update(['loan_status' => 'returned', 'returned_at' => now()]);
        }

        $this->assertTrue($this->service->isEligible($member));
    }

    #[Test]
    public function an_outstanding_fine_blocks_further_borrowing(): void
    {
        $member = $this->eligibleMember();
        Fine::factory()->forMember($member)->outstanding()->create(['amount' => 7500]);

        $this->assertContains('Memiliki denda belum lunas: Rp 7.500', $this->service->check($member));
    }

    #[Test]
    public function settled_and_waived_fines_do_not_block_borrowing(): void
    {
        $member = $this->eligibleMember();
        Fine::factory()->forMember($member)->settled()->create(['amount' => 10000]);
        Fine::factory()->forMember($member)->waived()->create(['amount' => 20000]);

        $this->assertTrue($this->service->isEligible($member));
    }

    /**
     * Anggota bermasalah ganda harus melihat seluruh alasannya sekaligus,
     * bukan memperbaiki satu lalu ditolak lagi karena alasan berikutnya.
     */
    #[Test]
    public function it_reports_every_failing_rule_at_once(): void
    {
        $member = $this->eligibleMember([
            'is_active' => false,
            'is_blocked' => true,
            'blocked_reason' => 'Denda menumpuk',
        ]);
        Fine::factory()->forMember($member)->outstanding()->create(['amount' => 3000]);

        $errors = $this->service->check($member);

        $this->assertContains('Anggota tidak aktif.', $errors);
        $this->assertContains('Anggota sedang diblokir: Denda menumpuk', $errors);
        $this->assertContains('Anggota tidak memenuhi syarat peminjaman.', $errors);
        $this->assertContains('Memiliki denda belum lunas: Rp 3.000', $errors);
    }
}
