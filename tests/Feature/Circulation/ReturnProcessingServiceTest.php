<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Services\LoanEligibilityService;
use App\Modules\Circulation\Services\ReturnProcessingService;
use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pengembalian adalah satu transaksi dengan lima efek: catat pengembalian,
 * tutup pinjaman, bebaskan item, catat riwayat status, dan terbitkan denda
 * bila terlambat. Semua diuji, termasuk yang TIDAK boleh terjadi saat tepat waktu.
 */
class ReturnProcessingServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReturnProcessingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ReturnProcessingService::class);
    }

    #[Test]
    public function it_closes_the_loan_and_frees_the_item(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_return']);
        Carbon::setTestNow('2026-06-10 08:00:00');
        $loan = $this->activeLoan();
        $item = $loan->physicalItem;

        $result = $this->service->processReturn($item->barcode, null, 'Kondisi baik');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'loan_status' => 'returned',
            'returned_at' => '2026-06-10 08:00:00',
            'closed_by' => $petugas->id,
        ]);
        $this->assertSame('available', $item->fresh()->item_status);
        $this->assertDatabaseHas('return_transactions', [
            'loan_id' => $loan->id,
            'physical_item_id' => $item->id,
            'returned_by' => $petugas->id,
            'late_days' => 0,
            'fine_amount' => 0,
            'notes' => 'Kondisi baik',
        ]);
        $this->assertNull($result['fine']);
    }

    #[Test]
    public function an_on_time_return_creates_no_fine(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->activeLoan(null, null, ['due_date' => now()->addDays(3)]);

        $result = $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertSame(0, $result['late_days']);
        $this->assertSame(0, $result['fine_amount']);
        $this->assertDatabaseCount('fines', 0);
    }

    /**
     * Dikembalikan persis pada hari jatuh tempo masih tepat waktu.
     */
    #[Test]
    public function a_return_on_the_due_date_itself_is_not_late(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        Carbon::setTestNow('2026-06-10 08:00:00');
        $loan = $this->activeLoan(null, null, ['due_date' => now()]);

        $result = $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertSame(0, $result['late_days']);
        $this->assertDatabaseCount('fines', 0);
    }

    #[Test]
    #[DataProvider('lateReturns')]
    public function a_late_return_raises_an_outstanding_fine(int $lateDays, float $expectedAmount): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan($lateDays);

        $result = $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertSame($lateDays, $result['late_days']);
        $this->assertSame($expectedAmount, $result['fine_amount']);
        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'member_id' => $loan->member_id,
            'fine_type' => 'overdue',
            'amount' => $expectedAmount,
            'late_days' => $lateDays,
            'status' => 'outstanding',
            'notes' => "Keterlambatan {$lateDays} hari",
        ]);
    }

    public static function lateReturns(): array
    {
        return [
            'terlambat 1 hari' => [1, 1000.0],
            'terlambat 3 hari' => [3, 3000.0],
            'terlambat 14 hari' => [14, 14000.0],
        ];
    }

    #[Test]
    public function the_late_days_are_mirrored_on_the_return_transaction(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan(4);

        $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertDatabaseHas('return_transactions', [
            'loan_id' => $loan->id,
            'late_days' => 4,
            'fine_amount' => 4000,
        ]);
    }

    #[Test]
    public function it_records_the_status_change_back_to_available(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->activeLoan();

        $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertDatabaseHas('physical_item_status_histories', [
            'physical_item_id' => $loan->physical_item_id,
            'old_status' => 'loaned',
            'new_status' => 'available',
            'changed_by' => $petugas->id,
            'reason' => 'Dikembalikan dari pinjaman #'.$loan->id,
        ]);
    }

    #[Test]
    public function it_stores_the_condition_the_item_came_back_in(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $condition = ItemCondition::factory()->create(['name' => 'Sampul robek']);
        $loan = $this->activeLoan();

        $this->service->processReturn($loan->physicalItem->barcode, $condition->id);

        $this->assertDatabaseHas('return_transactions', [
            'loan_id' => $loan->id,
            'returned_condition_id' => $condition->id,
        ]);
    }

    #[Test]
    public function it_rejects_an_unknown_barcode(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Item dengan barcode 'TIDAK-ADA' tidak ditemukan.");

        $this->service->processReturn('TIDAK-ADA');
    }

    #[Test]
    public function it_rejects_an_item_that_is_not_on_loan(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $item = $this->availableItem();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Tidak ada pinjaman aktif untuk item '{$item->barcode}'.");

        $this->service->processReturn($item->barcode);
    }

    /**
     * Pengembalian kedua atas item yang sama harus ditolak, bukan menerbitkan
     * denda kedua atau menutup pinjaman yang sudah tertutup.
     */
    #[Test]
    public function the_same_item_cannot_be_returned_twice(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan(2);
        $barcode = $loan->physicalItem->barcode;

        $this->service->processReturn($barcode);

        $this->expectException(InvalidArgumentException::class);
        $this->service->processReturn($barcode);
    }

    #[Test]
    public function a_second_return_attempt_does_not_duplicate_the_fine(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan(2);
        $barcode = $loan->physicalItem->barcode;
        $this->service->processReturn($barcode);

        try {
            $this->service->processReturn($barcode);
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->assertSame(1, Fine::where('loan_id', $loan->id)->count());
        $this->assertDatabaseCount('return_transactions', 1);
    }

    #[Test]
    public function it_writes_an_audit_entry_for_the_return(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->activeLoan();

        $this->service->processReturn($loan->physicalItem->barcode);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'circulation',
            'subject_id' => $loan->id,
            'causer_id' => $petugas->id,
        ]);
    }

    /**
     * Denda yang terbit langsung mengunci hak pinjam anggota berikutnya —
     * rantai pengembalian-terlambat → denda → blokir pinjam diuji utuh.
     */
    #[Test]
    public function a_fine_from_a_late_return_blocks_the_member_from_borrowing_again(): void
    {
        $this->actingAsUserWith(['circulation.process_loan', 'circulation.process_return']);
        $member = $this->eligibleMember();
        $loan = $this->overdueLoan(3, $member);

        $this->service->processReturn($loan->physicalItem->barcode);

        $errors = app(LoanEligibilityService::class)->check($member->fresh());
        $this->assertContains('Memiliki denda belum lunas: Rp 3.000', $errors);
    }
}
