<?php

namespace Tests\Unit\Catalog;

use App\Modules\Catalog\Support\BibliographicRecordStateGuard;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Mesin status publikasi katalog (17_WORKFLOW_STATE_MACHINE.md §11.4).
 */
class BibliographicRecordStateGuardTest extends TestCase
{
    private const STATUSES = ['draft', 'published', 'unpublished', 'archived'];

    private const ALLOWED = [
        'draft' => ['published', 'archived'],
        'published' => ['unpublished', 'archived'],
        'unpublished' => ['published', 'archived'],
        'archived' => ['draft', 'unpublished'],
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
        $this->assertSame($expected, BibliographicRecordStateGuard::canTransition($from, $to));
    }

    /**
     * Arsip adalah keadaan terminal untuk publikasi: satu-satunya jalan keluar
     * adalah reaktivasi ke draft/unpublished, bukan langsung terbit.
     */
    #[Test]
    public function an_archived_record_cannot_go_straight_back_to_published(): void
    {
        $this->assertFalse(BibliographicRecordStateGuard::canTransition('archived', 'published'));
        $this->assertTrue(BibliographicRecordStateGuard::canTransition('archived', 'draft'));
    }

    #[Test]
    public function a_draft_cannot_be_unpublished_because_it_was_never_published(): void
    {
        $this->assertFalse(BibliographicRecordStateGuard::canTransition('draft', 'unpublished'));
    }

    #[Test]
    public function it_rejects_an_unknown_status(): void
    {
        $this->assertFalse(BibliographicRecordStateGuard::canTransition('entah', 'published'));
        $this->assertSame([], BibliographicRecordStateGuard::allowedActions('entah'));
    }

    #[Test]
    public function it_throws_with_the_offending_pair_in_the_message(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transisi status tidak valid: archived → published');

        BibliographicRecordStateGuard::assertTransition('archived', 'published');
    }

    #[Test]
    #[DataProvider('allowedActionCases')]
    public function it_lists_the_actions_available_in_each_state(string $status, array $expected): void
    {
        $this->assertSame($expected, BibliographicRecordStateGuard::allowedActions($status));
    }

    public static function allowedActionCases(): array
    {
        return [
            'draft' => ['draft', ['edit', 'publish', 'archive']],
            'published' => ['published', ['edit', 'unpublish', 'archive']],
            'unpublished' => ['unpublished', ['edit', 'publish', 'archive']],
            'archived hanya bisa direaktivasi' => ['archived', ['reactivate']],
        ];
    }
}
