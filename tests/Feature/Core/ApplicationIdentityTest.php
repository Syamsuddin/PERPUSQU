<?php

namespace Tests\Feature\Core;

use App\Modules\Core\Models\InstitutionProfile;
use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Nama dan versi aplikasi. Sebelumnya merek "GIBTHA LIBRARY" ditulis tangan di
 * empat layout dan halaman depan, sehingga instalasi lain harus menyunting
 * Blade untuk mengganti namanya.
 */
class ApplicationIdentityTest extends TestCase
{
    use RefreshDatabase;

    private function setAppName(string $name): void
    {
        SystemSetting::query()->updateOrCreate(['key' => 'app_name'], ['value' => $name]);
        app(SystemSettings::class)->refresh();
    }

    #[Test]
    public function the_app_name_reaches_the_public_catalogue(): void
    {
        $this->setAppName('Perpustakaan Nusantara');

        $this->get(route('opac.home'))->assertOk()->assertSee('Perpustakaan Nusantara');
        $this->get(route('opac.search'))->assertOk()->assertSee('Perpustakaan Nusantara');
    }

    #[Test]
    public function the_app_name_reaches_the_admin_area_and_the_login_page(): void
    {
        $this->setAppName('Perpustakaan Nusantara');

        $this->get(route('auth.login'))->assertOk()->assertSee('Perpustakaan Nusantara');

        $this->actingAsUserWith(['core.view_dashboard']);
        $this->get(route('admin.dashboard.index'))->assertOk()->assertSee('Perpustakaan Nusantara');
    }

    /**
     * Halaman depan lebih memilih nama perpustakaan dari Profil Institusi bila
     * ada; nama aplikasi menjadi cadangannya, bukan angka mati di Blade.
     */
    #[Test]
    public function the_landing_page_prefers_the_institution_name_and_falls_back_to_the_app_name(): void
    {
        $this->setAppName('Perpustakaan Nusantara');

        $this->get('/')->assertOk()->assertSee('Perpustakaan Nusantara');

        InstitutionProfile::factory()->create(['library_name' => 'Perpustakaan Gibtha Jaya']);

        $this->get('/')->assertOk()->assertSee('Perpustakaan Gibtha Jaya');
    }

    /**
     * Tanpa pengaturan, namanya mengikuti APP_NAME — bukan merek satu
     * instalasi tertentu yang tertanam di kode.
     */
    #[Test]
    public function without_a_setting_the_name_follows_the_application_config(): void
    {
        SystemSetting::query()->where('key', 'app_name')->delete();
        app(SystemSettings::class)->refresh();
        config(['app.name' => 'Perpustakaan Cadangan']);

        $this->assertSame('Perpustakaan Cadangan', app(SystemSettings::class)->appName());
        $this->get(route('opac.home'))->assertOk()->assertSee('Perpustakaan Cadangan');
    }

    #[Test]
    public function an_administrator_can_rename_the_application_from_the_settings_page(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules', 'core.view_dashboard',
        ]);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'app_name' => 'Perpustakaan Baru',
        ]))->assertSessionHasNoErrors();

        $this->get(route('admin.dashboard.index'))->assertOk()->assertSee('Perpustakaan Baru');
    }

    #[Test]
    public function the_application_name_cannot_be_left_blank(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'app_name' => '',
        ]))->assertSessionHasErrors('app_name');
    }

    // ── Versi ──────────────────────────────────────────────────────────

    #[Test]
    public function the_version_is_shown_in_the_admin_and_public_footers(): void
    {
        SystemSetting::query()->updateOrCreate(['key' => 'app_version'], ['value' => '2.4.1']);
        app(SystemSettings::class)->refresh();

        $this->get(route('opac.home'))->assertOk()->assertSee('v2.4.1');

        $this->actingAsUserWith(['core.view_dashboard']);
        $this->get(route('admin.dashboard.index'))->assertOk()->assertSee('v2.4.1');
    }

    #[Test]
    public function the_version_falls_back_to_a_default_when_it_was_never_stored(): void
    {
        SystemSetting::query()->where('key', 'app_version')->delete();
        app(SystemSettings::class)->refresh();

        $this->assertSame('1.0.0', app(SystemSettings::class)->appVersion());
    }

    /**
     * Versi menggambarkan kode yang terpasang, bukan kebijakan. Halaman
     * pengaturan menampilkannya tetapi tidak menerimanya sebagai masukan —
     * kalau bisa disunting, angkanya akan berbohong tentang apa yang berjalan.
     */
    #[Test]
    public function the_version_is_displayed_but_never_accepted_from_the_form(): void
    {
        SystemSetting::query()->updateOrCreate(['key' => 'app_version'], ['value' => '2.4.1']);
        app(SystemSettings::class)->refresh();
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->get(route('admin.settings.operational_rules.edit'))
            ->assertOk()
            ->assertSee('2.4.1');

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'app_version' => '9.9.9',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('2.4.1', app(SystemSettings::class)->appVersion());
    }
}
