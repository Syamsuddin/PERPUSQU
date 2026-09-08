<?php

namespace Tests\Feature\Collection;

use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Services\PhysicalItemStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Perubahan status item harus selalu meninggalkan jejak. Riwayat inilah yang
 * dipakai untuk menelusuri koleksi hilang atau rusak, jadi diuji sekeras
 * perubahan statusnya sendiri.
 */
class PhysicalItemStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    private PhysicalItemStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PhysicalItemStatusService::class);
    }

    #[Test]
    #[DataProvider('validTransitions')]
    public function it_applies_a_valid_transition_and_records_it(string $from, string $to): void
    {
        $petugas = $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->status($from)->create();

        $this->service->changeStatus($item, $to, 'Hasil stock opname');

        $this->assertSame($to, $item->fresh()->item_status);
        $this->assertDatabaseHas('physical_item_status_histories', [
            'physical_item_id' => $item->id,
            'old_status' => $from,
            'new_status' => $to,
            'changed_by' => $petugas->id,
            'reason' => 'Hasil stock opname',
        ]);
    }

    public static function validTransitions(): array
    {
        return [
            'tersedia → rusak' => ['available', 'damaged'],
            'tersedia → perbaikan' => ['available', 'repair'],
            'tersedia → nonaktif' => ['available', 'inactive'],
            'rusak → perbaikan' => ['damaged', 'repair'],
            'perbaikan → tersedia' => ['repair', 'available'],
            'hilang → tersedia (koreksi)' => ['lost', 'available'],
            'nonaktif → tersedia' => ['inactive', 'available'],
        ];
    }

    #[Test]
    #[DataProvider('invalidTransitions')]
    public function it_refuses_an_invalid_transition_and_records_nothing(string $from, string $to): void
    {
        $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->status($from)->create();

        try {
            $this->service->changeStatus($item, $to);
            $this->fail("Transisi {$from} → {$to} seharusnya ditolak.");
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('Transisi status tidak valid', $e->getMessage());
        }

        $this->assertSame($from, $item->fresh()->item_status);
        $this->assertDatabaseCount('physical_item_status_histories', 0);
    }

    public static function invalidTransitions(): array
    {
        return [
            'hilang → dipinjam' => ['lost', 'loaned'],
            'hilang → rusak' => ['lost', 'damaged'],
            'rusak → dipinjam' => ['damaged', 'loaned'],
            'perbaikan → dipinjam' => ['repair', 'loaned'],
            'nonaktif → dipinjam' => ['inactive', 'loaned'],
            'tersedia → tersedia' => ['available', 'available'],
        ];
    }

    /**
     * Item yang sedang dipinjam tidak boleh dinonaktifkan diam-diam — satu-satunya
     * jalan keluar adalah lewat proses pengembalian atau pelaporan rusak/hilang.
     */
    #[Test]
    public function a_loaned_item_cannot_be_deactivated_outside_the_circulation_flow(): void
    {
        $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->loaned()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->service->changeStatus($item, 'inactive');
    }

    #[Test]
    #[DataProvider('loanedExits')]
    public function a_loaned_item_may_still_be_reported_returned_damaged_or_lost(string $to): void
    {
        $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->loaned()->create();

        $this->service->changeStatus($item, $to, 'Dilaporkan petugas');

        $this->assertSame($to, $item->fresh()->item_status);
    }

    public static function loanedExits(): array
    {
        return [['available'], ['damaged'], ['repair'], ['lost']];
    }

    #[Test]
    public function it_writes_an_audit_entry_with_both_statuses(): void
    {
        $petugas = $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->available()->create();

        $this->service->changeStatus($item, 'damaged', 'Halaman sobek');

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'collection',
            'subject_type' => PhysicalItem::class,
            'subject_id' => $item->id,
            'causer_id' => $petugas->id,
        ]);
    }

    #[Test]
    public function consecutive_changes_build_a_chain_of_history(): void
    {
        $this->actingAsUserWith(['collections.update']);
        $item = PhysicalItem::factory()->available()->create();

        $this->service->changeStatus($item, 'damaged');
        $this->service->changeStatus($item->fresh(), 'repair');
        $this->service->changeStatus($item->fresh(), 'available');

        $chain = $item->statusHistories()->orderBy('id')->get()
            ->map(fn ($h) => $h->old_status.'→'.$h->new_status)
            ->all();

        $this->assertSame(['available→damaged', 'damaged→repair', 'repair→available'], $chain);
    }
}
