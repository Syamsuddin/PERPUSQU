<?php

namespace Tests\Feature\Core;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Core\Services\DashboardWidgetService;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_widgets_count_each_part_of_the_collection(): void
    {
        $user = $this->actingAsUserWith(['core.view_dashboard']);

        // Item dan aset digital selalu menggantung pada satu record induk;
        // dibuat eksplisit agar jumlah katalog tidak ikut membengkak.
        $records = BibliographicRecord::factory()->count(3)->create();
        PhysicalItem::factory()->count(2)->available()->create(['bibliographic_record_id' => $records[0]->id]);
        PhysicalItem::factory()->damaged()->create(['bibliographic_record_id' => $records[0]->id]);
        Member::factory()->count(4)->create();
        DigitalAsset::factory()->count(2)->create(['bibliographic_record_id' => $records[1]->id]);

        $widgets = app(DashboardWidgetService::class)->buildDashboardForUser($user);

        $this->assertSame(3, $widgets['total_catalog']);
        $this->assertSame(3, $widgets['total_items']);
        $this->assertSame(4, $widgets['total_members']);
        $this->assertSame(2, $widgets['total_digital_assets']);
        $this->assertSame(2, $widgets['available_items'], 'item rusak tidak dihitung sebagai tersedia');
    }

    /**
     * Pinjaman terlambat harus muncul di dua penghitung sekaligus: masih aktif
     * DAN sudah lewat tempo. Kalau `overdue` mengecualikan yang aktif, angka di
     * dashboard akan diam-diam menyembunyikan tunggakan.
     */
    #[Test]
    public function an_overdue_loan_counts_as_both_active_and_overdue(): void
    {
        $user = $this->actingAsUserWith(['core.view_dashboard']);
        $this->activeLoan(null, null, ['due_date' => now()->addDays(5)]);
        $this->overdueLoan(4);

        $widgets = app(DashboardWidgetService::class)->buildDashboardForUser($user);

        $this->assertSame(2, $widgets['active_loans']);
        $this->assertSame(1, $widgets['overdue_loans']);
    }

    #[Test]
    public function a_returned_loan_leaves_both_counters(): void
    {
        $user = $this->actingAsUserWith(['core.view_dashboard']);
        $loan = $this->overdueLoan(3);
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);

        $widgets = app(DashboardWidgetService::class)->buildDashboardForUser($user);

        $this->assertSame(0, $widgets['active_loans']);
        $this->assertSame(0, $widgets['overdue_loans']);
    }

    #[Test]
    public function the_recent_lists_are_capped_at_five_and_newest_first(): void
    {
        $user = $this->actingAsUserWith(['core.view_dashboard']);
        foreach (range(1, 7) as $i) {
            BibliographicRecord::factory()->create(['created_at' => now()->subMinutes(10 - $i)]);
        }

        $widgets = app(DashboardWidgetService::class)->buildDashboardForUser($user);

        $this->assertCount(5, $widgets['recent_catalogs']);
        $timestamps = $widgets['recent_catalogs']->pluck('created_at')->all();
        $this->assertSame($timestamps, collect($timestamps)->sortDesc()->values()->all());
    }

    #[Test]
    public function the_dashboard_renders_on_an_empty_installation(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('admin.dashboard.index'))->assertOk();
    }

    #[Test]
    public function the_dashboard_renders_with_data(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);
        $this->activeLoan();
        BibliographicRecord::factory()->withAuthor()->create();

        $this->get(route('admin.dashboard.index'))->assertOk();
    }

    #[Test]
    public function the_dashboard_requires_its_own_permission(): void
    {
        $this->actingAsUserWith(['catalog.view']);

        $this->get(route('admin.dashboard.index'))->assertForbidden();
    }

    #[Test]
    public function a_guest_is_shown_the_landing_page_at_the_root(): void
    {
        $this->get('/')->assertOk();
    }

    #[Test]
    public function a_signed_in_user_is_forwarded_from_the_root_to_the_dashboard(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get('/')->assertRedirect(route('admin.dashboard.index'));
    }
}
