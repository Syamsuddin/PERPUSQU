<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Services\LoanTransactionService;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Core\Services\OperationalRules;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoanTransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoanTransactionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LoanTransactionService::class);
    }

    #[Test]
    public function it_creates_an_active_loan_and_marks_the_item_as_loaned(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember();
        $item = $this->availableItem();

        $loan = $this->service->createLoan($member->id, $item->barcode, 'Diambil di loket');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'member_id' => $member->id,
            'physical_item_id' => $item->id,
            'loan_status' => 'active',
            'loaned_by' => $petugas->id,
            'notes' => 'Diambil di loket',
            'returned_at' => null,
        ]);
        $this->assertSame('loaned', $item->fresh()->item_status);
    }

    /**
     * Tanggal jatuh tempo dihitung dari jenis anggota. Salah di sini berarti
     * denda dihitung dari tanggal yang salah pula.
     */
    #[Test]
    #[DataProvider('memberTypes')]
    public function it_sets_the_due_date_from_the_member_type(string $memberType, int $expectedDays): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        Carbon::setTestNow('2026-05-04 10:00:00');

        $member = $this->eligibleMember(['member_type' => $memberType]);
        $loan = $this->service->createLoan($member->id, $this->availableItem()->barcode);

        $this->assertSame(
            now()->addDays($expectedDays)->toDateTimeString(),
            $loan->due_date->toDateTimeString()
        );
        $this->assertSame($expectedDays, app(OperationalRules::class)->loanPeriodDays($memberType));
    }

    public static function memberTypes(): array
    {
        return [
            'mahasiswa' => ['student', 14],
            'dosen' => ['lecturer', 30],
            'staf' => ['staff', 14],
            'alumni' => ['alumni', 7],
            'tamu' => ['guest', 7],
        ];
    }

    #[Test]
    public function it_records_the_item_status_change_in_history(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember(['name' => 'Halimah']);
        $item = $this->availableItem();

        $loan = $this->service->createLoan($member->id, $item->barcode);

        $this->assertDatabaseHas('physical_item_status_histories', [
            'physical_item_id' => $item->id,
            'old_status' => 'available',
            'new_status' => 'loaned',
            'changed_by' => $petugas->id,
            'reason' => "Dipinjam oleh Halimah (Loan #{$loan->id})",
        ]);
    }

    #[Test]
    public function it_writes_an_audit_entry_for_the_loan(): void
    {
        $petugas = $this->actingAsUserWith(['circulation.process_loan']);
        $item = $this->availableItem();

        $loan = $this->service->createLoan($this->eligibleMember()->id, $item->barcode);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'circulation',
            'subject_type' => Loan::class,
            'subject_id' => $loan->id,
            'causer_id' => $petugas->id,
        ]);
    }

    #[Test]
    public function it_rejects_an_unknown_barcode(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Item dengan barcode 'TIDAK-ADA' tidak ditemukan.");

        $this->service->createLoan($member->id, 'TIDAK-ADA');
    }

    #[Test]
    #[DataProvider('unavailableStatuses')]
    public function it_refuses_to_lend_an_item_that_is_not_available(string $status): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember();
        $item = $this->availableItem();
        $item->update(['item_status' => $status]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Item '{$item->barcode}' tidak tersedia (status: {$status}).");

        $this->service->createLoan($member->id, $item->barcode);
    }

    public static function unavailableStatuses(): array
    {
        return [['loaned'], ['damaged'], ['lost'], ['repair'], ['inactive']];
    }

    #[Test]
    public function it_refuses_to_lend_to_an_ineligible_member(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember(['is_blocked' => true, 'blocked_reason' => 'Denda menumpuk']);
        $item = $this->availableItem();

        try {
            $this->service->createLoan($member->id, $item->barcode);
            $this->fail('Peminjaman untuk anggota diblokir seharusnya ditolak.');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('Anggota sedang diblokir', $e->getMessage());
        }

        $this->assertDatabaseCount('loans', 0);
        $this->assertSame('available', $item->fresh()->item_status, 'item tidak boleh ikut berubah status saat pinjaman gagal');
    }

    /**
     * Gerbang kelayakan harus diperiksa SEBELUM apa pun ditulis. Kalau tidak,
     * item bisa tertinggal berstatus `loaned` tanpa baris pinjaman.
     */
    #[Test]
    public function a_rejected_loan_leaves_no_trace(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $member = $this->eligibleMember();
        Fine::factory()->forMember($member)->outstanding()->create(['amount' => 5000]);
        $item = $this->availableItem();

        try {
            $this->service->createLoan($member->id, $item->barcode);
        } catch (InvalidArgumentException) {
            // memang diharapkan gagal
        }

        $this->assertSame(0, Loan::where('physical_item_id', $item->id)->count());
        $this->assertDatabaseCount('physical_item_status_histories', 0);
        $this->assertSame('available', $item->fresh()->item_status);
    }

    /**
     * Basis data menjaga invarian "satu item hanya boleh punya satu pinjaman
     * aktif" lewat indeks unik parsial, bukan hanya lewat pengecekan aplikasi.
     */
    #[Test]
    public function the_database_forbids_two_active_loans_on_the_same_item(): void
    {
        $item = PhysicalItem::factory()->loaned()->create();
        Loan::factory()->create(['physical_item_id' => $item->id]);

        $this->expectException(QueryException::class);

        Loan::factory()->create(['physical_item_id' => $item->id]);
    }

    #[Test]
    public function an_item_may_be_lent_again_after_the_previous_loan_is_closed(): void
    {
        $this->actingAsUserWith(['circulation.process_loan']);
        $item = $this->availableItem();

        $first = $this->service->createLoan($this->eligibleMember()->id, $item->barcode);
        $first->update(['loan_status' => 'returned', 'returned_at' => now()]);
        $item->refresh()->update(['item_status' => 'available']);

        $second = $this->service->createLoan($this->eligibleMember()->id, $item->barcode);

        $this->assertNotSame($first->id, $second->id);
        $this->assertSame(2, Loan::where('physical_item_id', $item->id)->count());
    }

    #[Test]
    public function active_loans_can_be_filtered_to_only_the_overdue_ones(): void
    {
        $this->actingAsUserWith(['circulation.view_active_loans']);
        $this->overdueLoan(3);
        $this->activeLoan();

        $overdue = $this->service->getActiveLoans(['is_overdue' => true]);

        $this->assertCount(1, $overdue);
    }

    #[Test]
    public function the_active_loan_list_excludes_returned_loans(): void
    {
        $this->actingAsUserWith(['circulation.view_active_loans']);
        $this->activeLoan();
        $this->activeLoan()->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $this->assertCount(1, $this->service->getActiveLoans());
        $this->assertCount(2, $this->service->getHistory(), 'riwayat memuat pinjaman aktif maupun yang sudah kembali');
    }
}
