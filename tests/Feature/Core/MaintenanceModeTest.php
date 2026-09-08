<?php

namespace Tests\Feature\Core;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Mode pemeliharaan menutup katalog publik saja.
 *
 * Ini perbedaan yang disengaja dari `php artisan down`, yang menutup seluruh
 * aplikasi termasuk area admin — saklar yang dinyalakan lewat antarmuka tidak
 * boleh mengunci orang yang harus mematikannya kembali.
 */
class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    private function closeCatalogue(bool $closed = true): void
    {
        SystemSetting::query()->updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $closed ? 'true' : 'false']
        );
        app(SystemSettings::class)->refresh();
    }

    public static function publicRoutes(): array
    {
        return [
            'beranda' => ['/'],
            'opac' => ['/opac'],
            'pencarian' => ['/opac/search'],
            'tentang' => ['/opac/about'],
            'bantuan' => ['/opac/help'],
        ];
    }

    #[Test]
    #[DataProvider('publicRoutes')]
    public function the_public_pages_are_open_while_maintenance_is_off(string $url): void
    {
        $this->get($url)->assertOk();
    }

    #[Test]
    #[DataProvider('publicRoutes')]
    public function a_guest_meets_the_maintenance_page_while_maintenance_is_on(string $url): void
    {
        $this->closeCatalogue();

        $response = $this->get($url);

        $response->assertStatus(503);
        $response->assertSee('Sedang Dalam Pemeliharaan');
        $response->assertHeader('Retry-After');
    }

    /**
     * Petugas yang sudah masuk tetap dapat membuka OPAC, sehingga hasil
     * pekerjaan dapat diperiksa sebelum katalog dibuka kembali.
     */
    #[Test]
    #[DataProvider('publicRoutes')]
    public function a_signed_in_user_can_still_reach_the_public_pages(string $url): void
    {
        $this->closeCatalogue();
        $this->actingAsUserWith(['core.view_dashboard']);

        $response = $this->get($url);

        $this->assertContains($response->getStatusCode(), [200, 302], "URL {$url} tidak dapat diakses pengguna yang sudah masuk.");
        $response->assertDontSee('Sedang Dalam Pemeliharaan');
    }

    /**
     * Jalan keluarnya harus tetap terbuka: halaman login dan area admin —
     * termasuk halaman yang mematikan saklar ini — tidak ikut tertutup.
     */
    #[Test]
    public function the_way_back_in_stays_open(): void
    {
        $this->closeCatalogue();

        $this->get(route('auth.login'))->assertOk();

        $this->actingAsUserWith(['core.view_dashboard', 'core.view_operational_rules']);
        $this->get(route('admin.dashboard.index'))->assertOk();
        $this->get(route('admin.settings.operational_rules.edit'))->assertOk();
    }

    #[Test]
    public function a_record_detail_page_is_closed_to_guests_too(): void
    {
        $record = BibliographicRecord::factory()->published()->withAuthor()->create();
        $this->closeCatalogue();

        $this->get(route('opac.record.show', $record->id))->assertStatus(503);
    }

    #[Test]
    public function the_catalogue_reopens_when_the_switch_is_turned_off(): void
    {
        $this->closeCatalogue();
        $this->get(route('opac.home'))->assertStatus(503);

        $this->closeCatalogue(false);

        $this->get(route('opac.home'))->assertOk();
    }

    /**
     * Alur sesungguhnya: pengelola menutup dan membuka katalog lewat halaman
     * Aturan Operasional, bukan dengan menyunting basis data.
     */
    #[Test]
    public function an_administrator_can_close_and_reopen_the_catalogue_from_the_settings_page(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'maintenance_mode' => 1,
        ]))->assertSessionHasNoErrors();

        $this->assertTrue(app(SystemSettings::class)->maintenanceMode());

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'maintenance_mode' => 0,
        ]));

        $this->assertFalse(app(SystemSettings::class)->maintenanceMode());
    }

    /**
     * Selama ini `maintenance_mode` tidak berpengaruh apa pun, sehingga nilai
     * `true` bisa saja mengendap di basis data tanpa disadari. Migrasi
     * memaksanya `false` agar penerapan middleware ini tidak menutup katalog
     * yang sedang melayani pengunjung.
     */
    #[Test]
    public function the_release_migration_leaves_the_catalogue_open(): void
    {
        $this->assertSame('false', SystemSetting::firstWhere('key', 'maintenance_mode')?->value);
        $this->assertFalse(app(SystemSettings::class)->maintenanceMode());
    }
}
