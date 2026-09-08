<?php

namespace Tests\Feature\Core;

use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\OperationalRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pembaca aturan operasional. Yang diuji di sini adalah rantai penelusurannya:
 * kunci khusus → cadangan → nilai bawaan. Instalasi yang tabel pengaturannya
 * kosong harus berperilaku persis seperti sebelum pengaturan disambungkan —
 * itulah jaminan yang membuat penerapan perubahan ini aman.
 */
class OperationalRulesReaderTest extends TestCase
{
    use RefreshDatabase;

    private function rules(): OperationalRules
    {
        return app(OperationalRules::class);
    }

    /**
     * Migrasi 2026_09_08_000001 sudah mengisi tabel pengaturan saat basis data
     * uji dibangun. Untuk menguji nilai bawaan, tabel itu harus dikosongkan
     * secara sengaja — meniru instalasi yang migrasinya belum dijalankan.
     */
    private function clearSettings(): void
    {
        SystemSetting::query()->delete();
        $this->rules()->refresh();
    }

    private function set(string $key, string $value): void
    {
        SystemSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        $this->rules()->refresh();
    }

    public static function builtInLoanPeriods(): array
    {
        return [
            'mahasiswa' => ['student', 14],
            'dosen' => ['lecturer', 30],
            'staf' => ['staff', 14],
            'alumni' => ['alumni', 7],
            'tamu' => ['guest', 7],
        ];
    }

    #[Test]
    #[DataProvider('builtInLoanPeriods')]
    public function with_no_settings_at_all_it_returns_the_values_that_used_to_be_hardcoded(string $memberType, int $expected): void
    {
        $this->clearSettings();

        $this->assertSame($expected, $this->rules()->loanPeriodDays($memberType));
    }

    #[Test]
    public function with_no_settings_the_remaining_rules_also_match_the_old_constants(): void
    {
        $this->clearSettings();
        $rules = $this->rules();

        $this->assertSame(7, $rules->renewalPeriodDays());
        $this->assertSame(2, $rules->maxRenewals());
        $this->assertSame(5, $rules->maxActiveLoans());
        $this->assertSame(1000.0, $rules->fineDailyAmount());
        $this->assertTrue($rules->renewalAllowed());
        $this->assertTrue($rules->requiresActiveMember());
        $this->assertTrue($rules->requiresUnblockedMember());
    }

    #[Test]
    public function a_stored_value_overrides_the_built_in_default(): void
    {
        $this->clearSettings();
        $this->set('loan_days_lecturer', '45');

        $this->assertSame(45, $this->rules()->loanPeriodDays('lecturer'));
        $this->assertSame(14, $this->rules()->loanPeriodDays('student'), 'jenis lain tidak ikut berubah');
    }

    /**
     * `loan_default_days` adalah cadangan untuk jenis anggota yang belum punya
     * kuncinya sendiri — bukan pengganti seluruh tabel.
     */
    #[Test]
    public function the_default_period_only_covers_member_types_without_their_own_key(): void
    {
        $this->clearSettings();
        $this->set('loan_default_days', '21');
        $this->set('loan_days_lecturer', '30');

        $this->assertSame(30, $this->rules()->loanPeriodDays('lecturer'), 'kunci khusus menang atas cadangan');
        $this->assertSame(21, $this->rules()->loanPeriodDays('student'), 'tanpa kunci khusus, cadangan yang dipakai');
        $this->assertSame(21, $this->rules()->loanPeriodDays('peneliti_tamu'), 'jenis anggota tak dikenal ikut cadangan');
    }

    #[Test]
    public function an_unknown_member_type_falls_back_to_the_built_in_default_when_nothing_is_stored(): void
    {
        $this->clearSettings();

        $this->assertSame(14, $this->rules()->loanPeriodDays('peneliti_tamu'));
    }

    /**
     * Nilai kosong dianggap tidak ada, sehingga penelusuran lanjut ke tahap
     * berikutnya alih-alih memaknainya sebagai nol hari.
     */
    #[Test]
    public function an_empty_stored_value_is_treated_as_absent(): void
    {
        $this->clearSettings();
        $this->set('loan_days_lecturer', '');

        $this->assertSame(30, $this->rules()->loanPeriodDays('lecturer'), 'jatuh ke nilai bawaan');

        $this->set('loan_default_days', '21');

        $this->assertSame(21, $this->rules()->loanPeriodDays('lecturer'), 'jatuh ke cadangan bila cadangannya ada');
    }

    #[Test]
    #[DataProvider('booleanRepresentations')]
    public function boolean_rules_understand_the_way_settings_are_stored(string $stored, bool $expected): void
    {
        $this->clearSettings();
        $this->set('allow_renewal', $stored);

        $this->assertSame($expected, $this->rules()->renewalAllowed());
    }

    public static function booleanRepresentations(): array
    {
        return [
            'true' => ['true', true],
            'false' => ['false', false],
            'satu' => ['1', true],
            'nol' => ['0', false],
        ];
    }

    /**
     * Tabel pengaturan dibaca sekali saja; perubahan di tengah request baru
     * terlihat setelah refresh() — yang dipanggil OperationalRuleService
     * begitu pengaturan disimpan.
     */
    #[Test]
    public function the_settings_table_is_read_once_per_request(): void
    {
        $this->clearSettings();
        $rules = $this->rules();
        $this->assertSame(1000.0, $rules->fineDailyAmount());

        SystemSetting::query()->updateOrCreate(['key' => 'fine_daily_amount'], ['value' => '5000']);
        $this->assertSame(1000.0, $rules->fineDailyAmount(), 'masih memakai nilai yang sudah dibaca');

        $rules->refresh();
        $this->assertSame(5000.0, $rules->fineDailyAmount());
    }

    #[Test]
    public function it_is_shared_across_the_whole_request(): void
    {
        $this->assertSame(app(OperationalRules::class), app(OperationalRules::class));
    }

    #[Test]
    public function the_form_values_include_every_rule_even_the_ones_never_saved(): void
    {
        $this->clearSettings();
        $all = $this->rules()->all();

        foreach (OperationalRules::KEYS as $key) {
            $this->assertArrayHasKey($key, $all);
        }
        $this->assertSame(30, (int) $all['loan_days_lecturer']);
    }

    /**
     * Migrasi 2026_09_08_000001 adalah pengaman rilis: ia menuliskan angka yang
     * berlaku di kode SEBELUM sirkulasi mulai membaca dari tabel. Bila isinya
     * bergeser dari nilai bawaan, instalasi yang sudah berjalan akan berubah
     * perilaku diam-diam saat kode baru naik.
     */
    #[Test]
    public function the_release_migration_stores_the_values_the_code_used_to_hold(): void
    {
        $stored = SystemSetting::query()->pluck('value', 'key');

        foreach (OperationalRules::KEYS as $key) {
            $this->assertTrue($stored->has($key), "Kunci {$key} tidak ditulis migrasi.");
        }

        $this->assertSame('30', $stored['loan_days_lecturer']);
        $this->assertSame('14', $stored['loan_days_student']);
        $this->assertSame('7', $stored['loan_days_guest']);
        $this->assertSame('7', $stored['loan_renewal_days']);
        $this->assertSame('2', $stored['loan_max_renewal_count']);
        $this->assertSame('5', $stored['loan_max_active_loans']);
        $this->assertSame('1000', $stored['fine_daily_amount']);
    }

    /**
     * Dengan tabel hasil migrasi apa adanya, seluruh aturan harus sama persis
     * dengan nilai bawaan — inilah bukti bahwa penerapan perubahan ini tidak
     * mengubah perilaku sistem sedetik pun.
     */
    #[Test]
    public function a_freshly_migrated_installation_behaves_exactly_as_before(): void
    {
        $rules = $this->rules();

        $this->assertSame(14, $rules->loanPeriodDays('student'));
        $this->assertSame(30, $rules->loanPeriodDays('lecturer'));
        $this->assertSame(14, $rules->loanPeriodDays('staff'));
        $this->assertSame(7, $rules->loanPeriodDays('alumni'));
        $this->assertSame(7, $rules->loanPeriodDays('guest'));
        $this->assertSame(7, $rules->renewalPeriodDays());
        $this->assertSame(2, $rules->maxRenewals());
        $this->assertSame(5, $rules->maxActiveLoans());
        $this->assertSame(1000.0, $rules->fineDailyAmount());
        $this->assertTrue($rules->renewalAllowed());
    }
}
