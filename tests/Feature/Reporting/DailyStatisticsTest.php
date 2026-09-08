<?php

namespace Tests\Feature\Reporting;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Member\Models\Member;
use App\Modules\Reporting\Models\DailyStatistic;
use App\Modules\Reporting\Services\DailyStatisticsService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Potret statistik harian.
 *
 * Modul laporan sebelumnya hanya dapat menjawab "berapa sekarang", tidak pernah
 * "bagaimana perkembangannya", karena tidak ada yang menyimpan keadaan kemarin.
 */
class DailyStatisticsTest extends TestCase
{
    use RefreshDatabase;

    private function service(): DailyStatisticsService
    {
        return app(DailyStatisticsService::class);
    }

    #[Test]
    public function it_records_the_state_of_the_collection(): void
    {
        BibliographicRecord::factory()->count(2)->published()->create();
        BibliographicRecord::factory()->draft()->create();
        $record = BibliographicRecord::query()->first();
        PhysicalItem::factory()->count(3)->available()->create(['bibliographic_record_id' => $record->id]);
        PhysicalItem::factory()->loaned()->create(['bibliographic_record_id' => $record->id]);
        Member::factory()->count(4)->create();
        Member::factory()->blocked()->create();

        $snapshot = $this->service()->capture();

        $this->assertSame(3, $snapshot->titles_total);
        $this->assertSame(2, $snapshot->titles_public);
        $this->assertSame(4, $snapshot->items_total);
        $this->assertSame(3, $snapshot->items_available);
        $this->assertSame(1, $snapshot->items_loaned);
        $this->assertSame(5, $snapshot->members_total);
        $this->assertSame(1, $snapshot->members_blocked);
    }

    /**
     * Angka arus dihitung dari rentang tanggalnya sendiri, bukan dari keadaan
     * saat potret diambil — itu yang membuatnya tetap benar kapan pun dihitung.
     */
    #[Test]
    public function flow_metrics_count_only_what_happened_on_that_day(): void
    {
        Carbon::setTestNow('2026-06-10 09:00:00');
        $this->activeLoan(null, null, ['loan_date' => '2026-06-09 10:00:00']);
        $this->activeLoan(null, null, ['loan_date' => '2026-06-09 20:00:00']);
        $this->activeLoan(null, null, ['loan_date' => '2026-06-08 10:00:00']);

        $snapshot = $this->service()->capture(Carbon::parse('2026-06-09'));

        $this->assertSame(2, $snapshot->loans_created, 'hanya pinjaman pada tanggal itu yang dihitung');
        $this->assertSame('2026-06-09', $snapshot->snapshot_date->toDateString());
    }

    #[Test]
    public function it_records_fine_amounts_as_whole_rupiah(): void
    {
        Carbon::setTestNow('2026-06-10 09:00:00');
        Fine::factory()->outstanding()->create(['amount' => 7500, 'created_at' => '2026-06-09 11:00:00']);
        Fine::factory()->settled()->create(['amount' => 2000, 'created_at' => '2026-06-09 11:00:00']);

        $snapshot = $this->service()->capture(Carbon::parse('2026-06-09'));

        $this->assertSame(9500, $snapshot->fines_raised_amount, 'arus denda mencakup seluruh denda yang terbit hari itu');
        $this->assertSame(7500, $snapshot->fines_outstanding_amount, 'stok denda hanya yang belum lunas');
    }

    /**
     * Penjadwal yang berjalan dua kali tidak boleh menggandakan baris.
     */
    #[Test]
    public function capturing_the_same_day_twice_overwrites_rather_than_duplicates(): void
    {
        $date = Carbon::parse('2026-06-09');
        $this->service()->capture($date);

        Member::factory()->count(3)->create();
        $this->service()->capture($date);

        $this->assertDatabaseCount('daily_statistics', 1);
        $this->assertSame(3, DailyStatistic::query()->firstOrFail()->members_total);
    }

    #[Test]
    public function without_an_explicit_date_it_captures_yesterday(): void
    {
        Carbon::setTestNow('2026-06-10 00:05:00');

        $this->assertSame('2026-06-09', $this->service()->capture()->snapshot_date->toDateString());
    }

    // ── Deret dan selisih ──────────────────────────────────────────────

    #[Test]
    public function the_series_returns_points_in_chronological_order(): void
    {
        Carbon::setTestNow('2026-06-10 09:00:00');
        DailyStatistic::factory()->on('2026-06-08')->create(['loans_created' => 5]);
        DailyStatistic::factory()->on('2026-06-09')->create(['loans_created' => 9]);
        DailyStatistic::factory()->on('2026-06-07')->create(['loans_created' => 2]);

        $series = $this->service()->series('loans_created', 30);

        $this->assertSame(['2026-06-07', '2026-06-08', '2026-06-09'], $series->pluck('date')->all());
        $this->assertSame([2, 5, 9], $series->pluck('value')->all());
    }

    #[Test]
    public function the_series_ignores_snapshots_beyond_the_window(): void
    {
        Carbon::setTestNow('2026-06-10 09:00:00');
        DailyStatistic::factory()->on('2026-06-09')->create();
        DailyStatistic::factory()->on('2026-01-01')->create();

        $this->assertCount(1, $this->service()->series('loans_created', 30));
    }

    #[Test]
    public function an_unknown_metric_fails_loudly(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Metrik statistik tidak dikenal: jumlah_kucing');

        $this->service()->series('jumlah_kucing');
    }

    #[Test]
    public function the_change_is_measured_against_the_most_recent_snapshot(): void
    {
        DailyStatistic::factory()->on('2026-06-08')->create(['members_total' => 10]);
        DailyStatistic::factory()->on('2026-06-09')->create(['members_total' => 12]);

        $this->assertSame(3, $this->service()->changeSinceLastSnapshot('members_total', 15));
        $this->assertSame(-2, $this->service()->changeSinceLastSnapshot('members_total', 10));
    }

    /**
     * Instalasi baru belum punya pembanding. Mengembalikan 0 akan terbaca
     * sebagai "tidak ada perubahan", padahal yang benar adalah "belum diketahui".
     */
    #[Test]
    public function without_any_snapshot_the_change_is_unknown_not_zero(): void
    {
        $this->assertNull($this->service()->changeSinceLastSnapshot('members_total', 15));
    }

    // ── Perintah dan jadwal ────────────────────────────────────────────

    #[Test]
    public function the_command_captures_and_reports(): void
    {
        Member::factory()->count(2)->create();

        $this->artisan('library:capture-daily-statistics')
            ->expectsOutputToContain('Potret')
            ->assertSuccessful();

        $this->assertSame(2, DailyStatistic::query()->firstOrFail()->members_total);
    }

    #[Test]
    public function the_command_accepts_an_explicit_date(): void
    {
        $this->artisan('library:capture-daily-statistics', ['--date' => '2026-06-01'])->assertSuccessful();

        // Dibandingkan lewat model, bukan nilai kolom mentah: SQLite dan MySQL
        // menuliskan kolom tanggal dengan format yang berbeda.
        $this->assertSame(
            '2026-06-01',
            DailyStatistic::query()->firstOrFail()->snapshot_date->toDateString()
        );
    }

    #[Test]
    public function the_capture_is_scheduled_daily(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($e) => str_contains($e->command ?? '', 'library:capture-daily-statistics'));

        $this->assertNotNull($event, 'Potret statistik tidak terjadwal.');
        $this->assertSame('5 0 * * *', $event->expression);
        $this->assertTrue($event->withoutOverlapping);
    }

    // ── Pemakaian di layar ─────────────────────────────────────────────

    #[Test]
    public function the_dashboard_shows_the_change_since_the_last_snapshot(): void
    {
        DailyStatistic::factory()->on(now()->subDay()->toDateString())->create(['members_total' => 2]);
        Member::factory()->count(5)->create();

        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('admin.dashboard.index'))
            ->assertOk()
            ->assertSee('sejak potret terakhir');
    }

    /**
     * Sebelum ada potret pertama, dashboard tidak boleh menampilkan selisih
     * apa pun — bukan menampilkan nol.
     */
    #[Test]
    public function the_dashboard_shows_no_change_before_the_first_snapshot(): void
    {
        Member::factory()->count(5)->create();
        $this->actingAsUserWith(['core.view_dashboard']);

        $this->get(route('admin.dashboard.index'))
            ->assertOk()
            ->assertDontSee('sejak potret terakhir');
    }

    #[Test]
    public function the_circulation_report_explains_itself_when_there_is_no_history_yet(): void
    {
        $this->actingAsUserWith(['reports.view_circulation']);

        $this->get(route('admin.reports.index', ['tab' => 'circulation']))
            ->assertOk()
            ->assertSee('Belum ada potret statistik harian');
    }

    #[Test]
    public function the_circulation_report_draws_the_trend_once_snapshots_exist(): void
    {
        DailyStatistic::factory()->on(now()->subDays(2)->toDateString())->create(['loans_created' => 4]);
        DailyStatistic::factory()->on(now()->subDay()->toDateString())->create(['loans_created' => 7]);

        $this->actingAsUserWith(['reports.view_circulation']);

        $this->get(route('admin.reports.index', ['tab' => 'circulation']))
            ->assertOk()
            ->assertSee('Tren 30 Hari Terakhir')
            ->assertDontSee('Belum ada potret statistik harian');
    }
}
