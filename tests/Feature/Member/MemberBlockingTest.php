<?php

namespace Tests\Feature\Member;

use App\Modules\Circulation\Services\LoanEligibilityService;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class MemberBlockingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function blocking_stores_the_reason_and_the_moment(): void
    {
        $this->actingAsUserWith(['members.block', 'members.view']);
        Carbon::setTestNow('2026-08-17 10:00:00');
        $member = Member::factory()->create();

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Menghilangkan koleksi'])
            ->assertRedirect(route('admin.members.show', $member))
            ->assertSessionHas('success');

        $member->refresh();
        $this->assertTrue($member->is_blocked);
        $this->assertSame('Menghilangkan koleksi', $member->blocked_reason);
        $this->assertSame('2026-08-17 10:00:00', $member->blocked_at->toDateTimeString());
    }

    #[Test]
    public function blocking_requires_a_reason(): void
    {
        $this->actingAsUserWith(['members.block']);
        $member = Member::factory()->create();

        $this->post(route('admin.members.block', $member), [])->assertSessionHasErrors('blocked_reason');
        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'ab'])->assertSessionHasErrors('blocked_reason');

        $this->assertFalse($member->fresh()->is_blocked);
    }

    #[Test]
    public function unblocking_clears_the_reason_and_the_timestamp(): void
    {
        $this->actingAsUserWith(['members.unblock', 'members.view']);
        $member = Member::factory()->blocked('Denda menumpuk')->create();

        $this->post(route('admin.members.unblock', $member))->assertSessionHas('success');

        $member->refresh();
        $this->assertFalse($member->is_blocked);
        $this->assertNull($member->blocked_reason);
        $this->assertNull($member->blocked_at);
    }

    /**
     * Pemblokiran harus segera menutup hak pinjam, bukan sekadar menandai
     * anggota di layar daftar.
     */
    #[Test]
    public function a_blocked_member_immediately_loses_the_right_to_borrow(): void
    {
        $this->actingAsUserWith(['members.block']);
        $member = Member::factory()->create();
        $eligibility = app(LoanEligibilityService::class);

        $this->assertTrue($eligibility->isEligible($member));

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Melanggar tata tertib']);

        $this->assertFalse($eligibility->isEligible($member->fresh()));
    }

    #[Test]
    public function an_active_but_blocked_member_stays_active(): void
    {
        $this->actingAsUserWith(['members.block']);
        $member = Member::factory()->create();

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Peringatan pertama']);

        $member->refresh();
        $this->assertTrue($member->is_active, 'blokir tidak sama dengan nonaktif');
        $this->assertTrue($member->is_blocked);
    }

    #[Test]
    public function blocking_and_unblocking_are_recorded_in_the_member_history(): void
    {
        $petugas = $this->actingAsUserWith(['members.block', 'members.unblock']);
        $member = Member::factory()->create();

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Melanggar tata tertib']);
        $this->post(route('admin.members.unblock', $member));

        $this->assertSame(2, Activity::query()
            ->where('subject_type', Member::class)
            ->where('subject_id', $member->id)
            ->where('causer_id', $petugas->id)
            ->count());
    }

    #[Test]
    public function a_user_without_the_block_permission_cannot_block_or_unblock(): void
    {
        $this->actingAsUserWith(['members.view']);
        $member = Member::factory()->create();

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Sepihak'])->assertForbidden();
        $this->post(route('admin.members.unblock', $member))->assertForbidden();

        $this->assertFalse($member->fresh()->is_blocked);
    }
}
