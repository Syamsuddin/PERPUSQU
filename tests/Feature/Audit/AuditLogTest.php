<?php

namespace Tests\Feature\Audit;

use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

/**
 * Jejak audit adalah bukti pertanggungjawaban. Yang diuji bukan hanya bahwa
 * halamannya terbuka, tetapi bahwa tindakan lintas modul benar-benar tercatat
 * lengkap dengan pelakunya.
 */
class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_audit_page_renders_for_an_authorised_viewer(): void
    {
        $this->actingAsUserWith(['audit_logs.view']);

        $this->get(route('admin.audit.index'))->assertOk();
    }

    #[Test]
    public function actions_across_modules_land_in_the_audit_log_with_their_actor(): void
    {
        $petugas = $this->actingAsUserWith([
            'audit_logs.view', 'members.block', 'circulation.process_loan',
        ]);
        $member = Member::factory()->create(['name' => 'Hafidz Anwar']);
        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Melanggar tata tertib']);

        $logs = Activity::where('causer_id', $petugas->id)->get();

        $this->assertGreaterThanOrEqual(1, $logs->count());
        $this->assertContains('member', $logs->pluck('log_name')->all());
    }

    #[Test]
    public function the_log_can_be_filtered_by_module_and_keyword(): void
    {
        $petugas = $this->actingAsUserWith(['audit_logs.view', 'members.block', 'collections.update']);
        $member = Member::factory()->create();
        $item = $this->availableItem();

        $this->post(route('admin.members.block', $member), ['blocked_reason' => 'Menghilangkan koleksi']);
        $this->post(route('admin.collections.items.change_status', $item), ['new_status' => 'damaged', 'reason' => 'Sampul lepas']);

        $this->get(route('admin.audit.index', ['log_name' => 'member']))
            ->assertOk()
            ->assertViewHas('logs', fn ($logs) => $logs->every(fn ($l) => $l->log_name === 'member'));

        $this->get(route('admin.audit.index', ['causer_id' => $petugas->id]))
            ->assertOk()
            ->assertViewHas('logs', fn ($logs) => $logs->every(fn ($l) => $l->causer_id === $petugas->id));
    }

    #[Test]
    public function the_module_filter_list_reflects_the_modules_that_actually_logged(): void
    {
        $this->actingAsUserWith(['audit_logs.view', 'members.block']);
        $this->post(route('admin.members.block', Member::factory()->create()), ['blocked_reason' => 'Alasan uji']);

        $this->get(route('admin.audit.index'))
            ->assertOk()
            ->assertViewHas('logNames', fn ($names) => $names->contains('member'));
    }

    #[Test]
    public function the_audit_page_renders_when_nothing_has_been_logged_yet(): void
    {
        $this->actingAsUserWith(['audit_logs.view']);

        $this->get(route('admin.audit.index'))->assertOk()->assertViewHas('logs', fn ($logs) => $logs->isEmpty());
    }

    #[Test]
    public function a_user_without_the_audit_permission_is_refused(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('admin.audit.index'))->assertForbidden();
    }
}
