<?php

namespace Tests\Feature\Reporting;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[DataProvider('reportTabs')]
    public function each_report_tab_renders(string $tab, string $permission): void
    {
        $this->actingAsUserWith([$permission]);
        BibliographicRecord::factory()->published()->create();
        Member::factory()->create();
        $this->overdueLoan(3);
        Fine::factory()->outstanding()->create();

        $this->get(route('admin.reports.index', ['tab' => $tab]))
            ->assertOk()
            ->assertViewHas('tab', $tab);
    }

    public static function reportTabs(): array
    {
        return [
            'koleksi' => ['collections', 'reports.view_collections'],
            'anggota' => ['members', 'reports.view_members'],
            'sirkulasi' => ['circulation', 'reports.view_circulation'],
            'denda' => ['fines', 'reports.view_fines'],
        ];
    }

    #[Test]
    public function every_tab_renders_on_an_empty_installation(): void
    {
        $this->actingAsUserWith(['reports.view_collections', 'reports.view_members', 'reports.view_circulation', 'reports.view_fines']);

        foreach (['collections', 'members', 'circulation', 'fines'] as $tab) {
            $this->get(route('admin.reports.index', ['tab' => $tab]))->assertOk();
        }
    }

    #[Test]
    public function an_unknown_tab_falls_back_to_the_collection_report(): void
    {
        $this->actingAsUserWith(['reports.view_collections']);

        $this->get(route('admin.reports.index', ['tab' => 'entah']))->assertOk();
    }

    /**
     * Route laporan dijaga izin bergaya `a|b|c`; satu izin saja sudah cukup
     * untuk masuk, dan itu memang yang diinginkan.
     */
    #[Test]
    public function a_single_reporting_permission_is_enough_to_open_the_page(): void
    {
        $this->actingAsUserWith(['reports.view_fines']);

        $this->get(route('admin.reports.index'))->assertOk();
    }

    #[Test]
    public function a_user_without_any_reporting_permission_is_refused(): void
    {
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('admin.reports.index'))->assertForbidden();
    }

    #[Test]
    public function the_year_filter_is_accepted(): void
    {
        $this->actingAsUserWith(['reports.view_circulation']);

        $this->get(route('admin.reports.index', ['tab' => 'circulation', 'year' => 2025]))
            ->assertOk()
            ->assertViewHas('year', 2025);
    }
}
