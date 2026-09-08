<?php

namespace Tests\Unit\Member;

use App\Modules\Member\Models\Member;
use App\Modules\Member\Support\MemberEligibilityResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Status anggota diturunkan dari dua flag (is_active, is_blocked). Keempat
 * kombinasinya diuji penuh — 17_WORKFLOW_STATE_MACHINE.md §10.1.
 */
class MemberEligibilityResolverTest extends TestCase
{
    private function member(bool $isActive, bool $isBlocked): Member
    {
        return new Member(['is_active' => $isActive, 'is_blocked' => $isBlocked]);
    }

    public static function flagCombinations(): array
    {
        return [
            'aktif & tidak diblokir' => [true, false, 'active_ready', true],
            'aktif tapi diblokir' => [true, true, 'active_blocked', false],
            'nonaktif & tidak diblokir' => [false, false, 'inactive_unblocked', false],
            'nonaktif & diblokir' => [false, true, 'inactive_blocked', false],
        ];
    }

    #[Test]
    #[DataProvider('flagCombinations')]
    public function it_derives_the_state_from_both_flags(bool $isActive, bool $isBlocked, string $expectedState, bool $_canBorrow): void
    {
        $this->assertSame($expectedState, MemberEligibilityResolver::derivedState($this->member($isActive, $isBlocked)));
    }

    /**
     * Hanya ACTIVE_READY yang boleh meminjam. Anggota aktif yang diblokir
     * adalah jebakan paling mudah terlewat, jadi dikunci eksplisit.
     */
    #[Test]
    #[DataProvider('flagCombinations')]
    public function only_an_active_and_unblocked_member_may_borrow(bool $isActive, bool $isBlocked, string $_state, bool $canBorrow): void
    {
        $this->assertSame($canBorrow, MemberEligibilityResolver::canBorrow($this->member($isActive, $isBlocked)));
    }

    #[Test]
    public function it_labels_every_derived_state_in_indonesian(): void
    {
        $this->assertSame('Aktif', MemberEligibilityResolver::stateLabel('active_ready'));
        $this->assertSame('Aktif (Diblokir)', MemberEligibilityResolver::stateLabel('active_blocked'));
        $this->assertSame('Nonaktif', MemberEligibilityResolver::stateLabel('inactive_unblocked'));
        $this->assertSame('Nonaktif (Diblokir)', MemberEligibilityResolver::stateLabel('inactive_blocked'));
    }

    #[Test]
    public function it_falls_back_to_a_capitalised_label_for_an_unknown_state(): void
    {
        $this->assertSame('Entah', MemberEligibilityResolver::stateLabel('entah'));
        $this->assertSame('light', MemberEligibilityResolver::stateBadgeClass('entah'));
    }

    #[Test]
    public function it_labels_every_member_type_in_indonesian(): void
    {
        $expected = [
            'student' => 'Mahasiswa',
            'lecturer' => 'Dosen',
            'staff' => 'Staf',
            'alumni' => 'Alumni',
            'guest' => 'Tamu',
        ];

        foreach ($expected as $type => $label) {
            $this->assertSame($label, MemberEligibilityResolver::typeLabel($type));
        }
    }

    #[Test]
    public function it_gives_each_derived_state_a_distinct_badge(): void
    {
        $badges = array_map(
            MemberEligibilityResolver::stateBadgeClass(...),
            ['active_ready', 'active_blocked', 'inactive_unblocked', 'inactive_blocked']
        );

        $this->assertSame(['success', 'danger', 'secondary', 'dark'], $badges);
    }
}
