<?php

namespace Tests\Feature\Scheduling;

use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Opac\Services\PublicAssetPreviewService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

/**
 * Pekerjaan terjadwal dan pendaftarannya.
 *
 * Perintah yang benar tetapi tidak pernah terjadwal sama tidak bergunanya
 * dengan perintah yang tidak ada, jadi jadwalnya sendiri ikut diuji.
 */
class ScheduledMaintenanceTest extends TestCase
{
    use RefreshDatabase;

    // ── Pelepasan embargo ──────────────────────────────────────────────

    #[Test]
    public function an_expired_embargo_flag_is_released(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoExpired()->create();

        $this->artisan('library:release-expired-embargoes')->assertSuccessful();

        $this->assertFalse($asset->fresh()->is_embargoed);
    }

    #[Test]
    public function an_embargo_still_running_is_left_alone(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoed()->create();

        $this->artisan('library:release-expired-embargoes')->assertSuccessful();

        $this->assertTrue($asset->fresh()->is_embargoed);
    }

    #[Test]
    public function an_embargo_without_an_end_date_is_left_alone(): void
    {
        $asset = DigitalAsset::factory()->published()->create([
            'is_embargoed' => true,
            'embargo_until' => null,
        ]);

        $this->artisan('library:release-expired-embargoes')->assertSuccessful();

        $this->assertTrue($asset->fresh()->is_embargoed, 'embargo tanpa batas waktu bukan embargo yang kedaluwarsa');
    }

    /**
     * Akses publik sudah benar tanpa perintah ini — yang diperbaiki adalah
     * kejujuran layar pengelolaan, supaya pustakawan tidak mengira akses masih
     * tertutup padahal sudah terbuka.
     */
    #[Test]
    public function the_public_access_was_already_correct_before_the_flag_was_tidied(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoExpired()->create();

        $this->assertTrue(app(PublicAssetPreviewService::class)->canPreview($asset));

        $this->artisan('library:release-expired-embargoes');

        $this->assertTrue(app(PublicAssetPreviewService::class)->canPreview($asset->fresh()));
    }

    #[Test]
    public function releasing_an_embargo_is_written_to_the_audit_log(): void
    {
        $asset = DigitalAsset::factory()->published()->embargoExpired()->create(['title' => 'Skripsi Terbuka']);

        $this->artisan('library:release-expired-embargoes');

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'digital_repository',
            'subject_type' => DigitalAsset::class,
            'subject_id' => $asset->id,
        ]);
    }

    #[Test]
    public function the_command_runs_cleanly_when_there_is_nothing_to_release(): void
    {
        $this->artisan('library:release-expired-embargoes')
            ->expectsOutputToContain('0 aset')
            ->assertSuccessful();
    }

    // ── Pendaftaran jadwal ─────────────────────────────────────────────

    /**
     * @return array<string, array{string, string}>
     */
    public static function scheduledCommands(): array
    {
        return [
            'pengingat jatuh tempo' => ['library:send-loan-reminders', '0 7 * * *'],
            'pelepasan embargo' => ['library:release-expired-embargoes', '0 1 * * *'],
            'pembersihan jejak audit' => ['activitylog:clean', '0 2 * * 1'],
        ];
    }

    #[Test]
    #[DataProvider('scheduledCommands')]
    public function the_maintenance_work_is_actually_scheduled(string $command, string $expression): void
    {
        $events = collect(app(Schedule::class)->events())
            ->filter(fn ($event) => str_contains($event->command ?? '', $command));

        $this->assertTrue($events->isNotEmpty(), "Perintah {$command} tidak terjadwal sama sekali.");
        $this->assertSame(
            $expression,
            $events->first()->expression,
            "Jadwal {$command} berubah tanpa disengaja."
        );
    }

    /**
     * Pekerjaan terjadwal biasanya berjalan di beberapa server sekaligus dan
     * dapat bertumpuk bila jalannya lambat. Tanpa kedua penjaga ini, satu
     * anggota bisa menerima pengingat yang sama beberapa kali.
     */
    #[Test]
    #[DataProvider('scheduledCommands')]
    public function every_scheduled_command_is_protected_from_overlap(string $command, string $_expression): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($e) => str_contains($e->command ?? '', $command));

        $this->assertNotNull($event);
        $this->assertTrue($event->withoutOverlapping, "{$command} dapat bertumpuk dengan dirinya sendiri.");
        $this->assertTrue($event->onOneServer, "{$command} dapat berjalan di beberapa server sekaligus.");
    }

    /**
     * Jejak audit adalah satu-satunya catatan pertanggungjawaban, jadi
     * retensinya harus panjang dan disebut eksplisit — konfigurasi paket
     * tidak dipublikasikan di proyek ini.
     */
    #[Test]
    public function the_audit_log_retention_is_stated_explicitly(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($e) => str_contains($e->command ?? '', 'activitylog:clean'));

        $this->assertStringContainsString('--days=730', $event->command);
    }

    #[Test]
    public function the_audit_log_cleanup_keeps_recent_entries_and_drops_old_ones(): void
    {
        Carbon::setTestNow('2026-05-10 02:00:00');
        activity('core')->log('Peristiwa baru');
        activity('core')->log('Peristiwa lama');

        Activity::query()
            ->where('description', 'Peristiwa lama')
            ->update(['created_at' => now()->subDays(800)]);

        $this->artisan('activitylog:clean --days=730 --force')->assertSuccessful();

        $this->assertDatabaseHas('activity_log', ['description' => 'Peristiwa baru']);
        $this->assertDatabaseMissing('activity_log', ['description' => 'Peristiwa lama']);
    }
}
