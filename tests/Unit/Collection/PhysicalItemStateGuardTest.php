<?php

namespace Tests\Unit\Collection;

use App\Modules\Collection\Support\PhysicalItemStateGuard;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Mesin status item fisik (17_WORKFLOW_STATE_MACHINE.md §12.3). Matriks
 * transisi diuji lengkap — setiap pasangan status yang tidak terdaftar sebagai
 * sah harus ditolak, bukan hanya beberapa contoh yang dipilih.
 */
class PhysicalItemStateGuardTest extends TestCase
{
    private const STATUSES = ['available', 'loaned', 'damaged', 'lost', 'repair', 'inactive'];

    private const ALLOWED = [
        'available' => ['loaned', 'damaged', 'repair', 'inactive', 'lost'],
        'loaned' => ['available', 'damaged', 'repair', 'lost'],
        'damaged' => ['repair', 'inactive', 'available'],
        'lost' => ['available'],
        'repair' => ['available', 'damaged', 'inactive'],
        'inactive' => ['available', 'repair'],
    ];

    public static function everyStatusPair(): iterable
    {
        foreach (self::STATUSES as $from) {
            foreach (self::STATUSES as $to) {
                yield "{$from} → {$to}" => [$from, $to, in_array($to, self::ALLOWED[$from], true)];
            }
        }
    }

    #[Test]
    #[DataProvider('everyStatusPair')]
    public function it_decides_every_transition_according_to_the_state_machine(string $from, string $to, bool $expected): void
    {
        $this->assertSame($expected, PhysicalItemStateGuard::canTransition($from, $to));
    }

    /**
     * Tidak ada status yang boleh "berpindah" ke dirinya sendiri: itu menandakan
     * perubahan status tanpa perubahan nyata, dan akan mengotori riwayat item.
     */
    #[Test]
    public function it_rejects_a_transition_from_a_status_to_itself(): void
    {
        foreach (self::STATUSES as $status) {
            $this->assertFalse(
                PhysicalItemStateGuard::canTransition($status, $status),
                "status {$status} seharusnya tidak bisa berpindah ke dirinya sendiri"
            );
        }
    }

    #[Test]
    public function it_rejects_an_unknown_status(): void
    {
        $this->assertFalse(PhysicalItemStateGuard::canTransition('entah', 'available'));
        $this->assertFalse(PhysicalItemStateGuard::canTransition('available', 'entah'));
        $this->assertSame([], PhysicalItemStateGuard::allowedTransitions('entah'));
    }

    #[Test]
    public function it_throws_with_the_offending_pair_in_the_message(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transisi status tidak valid: lost → loaned');

        PhysicalItemStateGuard::assertTransition('lost', 'loaned');
    }

    #[Test]
    public function it_stays_silent_for_a_valid_transition(): void
    {
        PhysicalItemStateGuard::assertTransition('available', 'loaned');

        $this->assertTrue(true, 'transisi sah tidak boleh melempar exception');
    }

    #[Test]
    public function it_lists_the_allowed_next_statuses(): void
    {
        $this->assertEqualsCanonicalizing(
            ['available', 'damaged', 'repair', 'lost'],
            PhysicalItemStateGuard::allowedTransitions('loaned')
        );
    }

    /**
     * Transisi koreksi (mis. barang hilang ternyata ketemu) sah secara mesin
     * status, tetapi ditandai admin-only agar UI dapat membatasinya.
     */
    #[Test]
    public function it_flags_correction_transitions_as_admin_only(): void
    {
        $this->assertTrue(PhysicalItemStateGuard::isAdminOnly('lost', 'available'));
        $this->assertTrue(PhysicalItemStateGuard::isAdminOnly('damaged', 'available'));
        $this->assertTrue(PhysicalItemStateGuard::isAdminOnly('inactive', 'repair'));
        $this->assertTrue(PhysicalItemStateGuard::isAdminOnly('available', 'lost'));

        $this->assertFalse(PhysicalItemStateGuard::isAdminOnly('available', 'loaned'));
        $this->assertFalse(PhysicalItemStateGuard::isAdminOnly('loaned', 'available'));
    }

    #[Test]
    public function every_admin_only_transition_is_also_a_valid_transition(): void
    {
        foreach (self::ALLOWED as $from => $_) {
            foreach (self::STATUSES as $to) {
                if (PhysicalItemStateGuard::isAdminOnly($from, $to)) {
                    $this->assertTrue(
                        PhysicalItemStateGuard::canTransition($from, $to),
                        "{$from} → {$to} ditandai admin-only tetapi bukan transisi sah"
                    );
                }
            }
        }
    }

    #[Test]
    public function it_labels_and_badges_every_known_status(): void
    {
        $expected = [
            'available' => ['Tersedia', 'success'],
            'loaned' => ['Dipinjam', 'primary'],
            'damaged' => ['Rusak', 'danger'],
            'lost' => ['Hilang', 'dark'],
            'repair' => ['Perbaikan', 'warning'],
            'inactive' => ['Nonaktif', 'secondary'],
        ];

        foreach ($expected as $status => [$label, $badge]) {
            $this->assertSame($label, PhysicalItemStateGuard::statusLabel($status));
            $this->assertSame($badge, PhysicalItemStateGuard::statusBadgeClass($status));
        }
    }
}
