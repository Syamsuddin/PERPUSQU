<?php

namespace Tests\Feature\Core;

use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Services\LoanEligibilityService;
use App\Modules\Circulation\Services\LoanRenewalService;
use App\Modules\Circulation\Services\ReturnProcessingService;
use App\Modules\Core\Models\SystemSetting;
use App\Modules\Core\Services\OperationalRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationalRulesTest extends TestCase
{
    use RefreshDatabase;

    // Aturan bawaan sudah tertulis ke `system_settings` oleh migrasi
    // 2026_09_08_000001, jadi tidak ada yang perlu disemai di sini.

    #[Test]
    public function the_rules_page_renders_with_the_current_values(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules']);

        $this->get(route('admin.settings.operational_rules.edit'))->assertOk();
    }

    #[Test]
    public function an_administrator_can_update_the_operational_rules(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->from(route('admin.settings.operational_rules.edit'))
            ->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
                'loan_default_days' => 21,
                'loan_max_active_loans' => 8,
                'loan_max_renewal_count' => 3,
                'fine_daily_amount' => 2500,
            ]))->assertSessionHas('success');

        $this->assertSame('21', SystemSetting::firstWhere('key', 'loan_default_days')->value);
        $this->assertSame('8', SystemSetting::firstWhere('key', 'loan_max_active_loans')->value);
        $this->assertSame('3', SystemSetting::firstWhere('key', 'loan_max_renewal_count')->value);
        $this->assertSame('2500', SystemSetting::firstWhere('key', 'fine_daily_amount')->value);
    }

    #[Test]
    #[DataProvider('invalidRuleValues')]
    public function it_rejects_values_outside_the_allowed_range(array $overrides, string $expectedField): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload($overrides))
            ->assertSessionHasErrors($expectedField);

        $this->assertSame('14', SystemSetting::firstWhere('key', 'loan_default_days')->value);
    }

    public static function invalidRuleValues(): array
    {
        return [
            'lama pinjam nol hari' => [['loan_default_days' => 0], 'loan_default_days'],
            'lama pinjam lebih dari setahun' => [['loan_default_days' => 400], 'loan_default_days'],
            'lama pinjam bukan angka' => [['loan_default_days' => 'dua minggu'], 'loan_default_days'],
            'batas pinjam nol' => [['loan_max_active_loans' => 0], 'loan_max_active_loans'],
            'batas pinjam berlebihan' => [['loan_max_active_loans' => 51], 'loan_max_active_loans'],
            'perpanjangan negatif' => [['loan_max_renewal_count' => -1], 'loan_max_renewal_count'],
            'denda negatif' => [['fine_daily_amount' => -100], 'fine_daily_amount'],
            'lama pinjam dosen nol' => [['loan_days_lecturer' => 0], 'loan_days_lecturer'],
            'lama pinjam tamu berlebihan' => [['loan_days_guest' => 400], 'loan_days_guest'],
            'lama perpanjangan nol' => [['loan_renewal_days' => 0], 'loan_renewal_days'],
        ];
    }

    #[Test]
    public function every_rule_field_is_required(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), [])
            ->assertSessionHasErrors([
                'loan_default_days', 'loan_days_student', 'loan_days_lecturer',
                'loan_days_staff', 'loan_days_alumni', 'loan_days_guest',
                'loan_renewal_days', 'loan_max_active_loans',
                'loan_max_renewal_count', 'fine_daily_amount',
                'asset_max_upload_size_mb', 'app_name',
            ]);
    }

    /**
     * Nol perpanjangan adalah kebijakan yang sah (perpustakaan menutup fitur
     * perpanjangan), jadi tidak boleh ikut tertolak oleh aturan `min`.
     */
    #[Test]
    public function zero_renewals_is_an_acceptable_policy(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_max_renewal_count' => 0,
            'fine_daily_amount' => 0,
        ]))->assertSessionHasNoErrors();

        $this->assertSame('0', SystemSetting::firstWhere('key', 'loan_max_renewal_count')->value);
    }

    #[Test]
    public function updating_the_rules_is_written_to_the_audit_log(): void
    {
        $admin = $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_default_days' => 20,
        ]));

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'core',
            'description' => 'Aturan operasional diperbarui',
            'causer_id' => $admin->id,
        ]);
    }

    #[Test]
    public function viewing_and_updating_need_separate_permissions(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules']);

        $this->get(route('admin.settings.operational_rules.edit'))->assertOk();
        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_default_days' => 30,
        ]))->assertForbidden();

        $this->assertSame('14', SystemSetting::firstWhere('key', 'loan_default_days')->value);
    }

    /**
     * Bukti bahwa aturan yang disimpan benar-benar mengubah perilaku sistem.
     * Sebelumnya keempat angka ini hanya mengendap di `system_settings`.
     */
    #[Test]
    public function the_saved_loan_period_governs_the_due_date_of_the_next_loan(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_loan', 'circulation.view_active_loans',
        ]);
        Carbon::setTestNow('2026-05-04 10:00:00');

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_days_lecturer' => 45,
        ]))->assertSessionHasNoErrors();

        $dosen = $this->eligibleMember(['member_type' => 'lecturer']);
        $item = $this->availableItem();

        $this->post(route('admin.circulation.loans.store'), [
            'member_id' => $dosen->id,
            'barcode' => $item->barcode,
        ])->assertSessionHas('success');

        $this->assertSame(
            now()->addDays(45)->toDateTimeString(),
            Loan::firstWhere('member_id', $dosen->id)->due_date->toDateTimeString()
        );
    }

    /**
     * Tiap jenis anggota punya angkanya sendiri — inilah yang hilang bila
     * `loan_default_days` disambungkan begitu saja.
     */
    #[Test]
    public function each_member_type_keeps_its_own_loan_period(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_loan', 'circulation.view_active_loans',
        ]);
        Carbon::setTestNow('2026-05-04 10:00:00');

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_days_student' => 10,
            'loan_days_lecturer' => 60,
        ]));

        foreach (['student' => 10, 'lecturer' => 60] as $type => $expectedDays) {
            $member = $this->eligibleMember(['member_type' => $type]);
            $this->post(route('admin.circulation.loans.store'), [
                'member_id' => $member->id,
                'barcode' => $this->availableItem()->barcode,
            ]);

            $this->assertSame(
                now()->addDays($expectedDays)->toDateTimeString(),
                Loan::firstWhere('member_id', $member->id)->due_date->toDateTimeString(),
                "lama pinjam {$type} tidak mengikuti pengaturan"
            );
        }
    }

    #[Test]
    public function the_saved_active_loan_ceiling_governs_eligibility(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_max_active_loans' => 2,
        ]));

        $member = $this->eligibleMember();
        $eligibility = app(LoanEligibilityService::class);

        $this->activeLoan($member);
        $this->assertTrue($eligibility->isEligible($member->fresh()));

        $this->activeLoan($member);
        $this->assertContains(
            'Batas pinjaman aktif tercapai (2/2).',
            $eligibility->check($member->fresh())
        );
    }

    #[Test]
    public function the_saved_fine_rate_governs_the_fine_raised_on_a_late_return(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_return',
        ]);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'fine_daily_amount' => 2500,
        ]));

        $loan = $this->overdueLoan(4);
        $result = app(ReturnProcessingService::class)->processReturn($loan->physicalItem->barcode);

        $this->assertSame(10000.0, $result['fine_amount']);
        $this->assertDatabaseHas('fines', ['loan_id' => $loan->id, 'amount' => 10000]);
    }

    /**
     * Tarif nol adalah kebijakan yang sah: keterlambatan tetap tercatat,
     * tetapi tidak menerbitkan tagihan.
     */
    #[Test]
    public function a_zero_fine_rate_records_the_lateness_without_raising_a_fine(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_return',
        ]);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'fine_daily_amount' => 0,
        ]));

        $loan = $this->overdueLoan(6);
        $result = app(ReturnProcessingService::class)->processReturn($loan->physicalItem->barcode);

        $this->assertSame(6, $result['late_days']);
        $this->assertSame(0.0, $result['fine_amount']);
        $this->assertDatabaseCount('fines', 0);
        $this->assertDatabaseHas('return_transactions', ['loan_id' => $loan->id, 'late_days' => 6]);
    }

    #[Test]
    public function the_saved_renewal_limit_and_period_govern_renewals(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_renewal',
        ]);
        Carbon::setTestNow('2026-07-01 09:00:00');

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_max_renewal_count' => 1,
            'loan_renewal_days' => 3,
        ]));

        $loan = $this->activeLoan(null, null, ['due_date' => '2026-07-05 09:00:00']);
        $renewal = app(LoanRenewalService::class);

        $renewal->renew($loan);
        $this->assertSame('2026-07-08 09:00:00', $loan->fresh()->due_date->toDateTimeString());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Batas perpanjangan tercapai (1/1).');
        $renewal->renew($loan->fresh());
    }

    #[Test]
    public function renewals_can_be_switched_off_entirely(): void
    {
        $this->actingAsUserWith([
            'core.view_operational_rules', 'core.update_operational_rules',
            'circulation.process_renewal', 'circulation.view_active_loans',
        ]);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'allow_renewal' => false,
        ]));

        $loan = $this->activeLoan(null, null, ['due_date' => now()->addDays(4)]);

        $this->from(route('admin.circulation.loans.show', $loan))
            ->post(route('admin.circulation.loans.renew', $loan))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('loan_renewals', 0);
    }

    /**
     * Perpustakaan yang tidak memberlakukan syarat keanggotaan aktif dapat
     * mematikannya tanpa perubahan kode.
     */
    #[Test]
    public function the_active_member_requirement_can_be_switched_off(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);
        $member = $this->eligibleMember(['is_active' => false]);
        $eligibility = app(LoanEligibilityService::class);

        $this->assertFalse($eligibility->isEligible($member));

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'require_active_member' => false,
        ]));

        $this->assertTrue($eligibility->isEligible($member->fresh()));
    }

    #[Test]
    public function the_unblocked_member_requirement_can_be_switched_off(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);
        $member = $this->eligibleMember(['is_blocked' => true, 'blocked_reason' => 'Uji']);
        $eligibility = app(LoanEligibilityService::class);

        $this->assertFalse($eligibility->isEligible($member));

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'require_unblocked_member' => false,
        ]));

        $this->assertTrue($eligibility->isEligible($member->fresh()));
    }

    /**
     * Kunci yang belum punya baris di basis data dulu gagal tersimpan tanpa
     * pesan apa pun, karena penyimpanan memakai update() alih-alih membuat baris.
     */
    #[Test]
    public function a_rule_that_has_no_row_yet_is_still_saved(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);
        SystemSetting::query()->delete();

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'loan_days_alumni' => 12,
        ]))->assertSessionHasNoErrors();

        $this->assertSame('12', SystemSetting::firstWhere('key', 'loan_days_alumni')?->value);
        $this->assertSame(12, app(OperationalRules::class)->loanPeriodDays('alumni'));
    }

    /**
     * Peramban tidak mengirim checkbox yang tidak dicentang. Ketiadaan sebuah
     * saklar karena itu harus dibaca sebagai "mati", bukan "biarkan seperti
     * sebelumnya" — kalau tidak, saklar yang sudah menyala mustahil dimatikan
     * lewat formulir.
     */
    #[Test]
    public function an_unchecked_switch_turns_the_policy_off(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $payload = $this->operationalRulePayload();
        unset($payload['allow_renewal']);

        $this->put(route('admin.settings.operational_rules.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('false', SystemSetting::firstWhere('key', 'allow_renewal')->value);
        $this->assertFalse(app(OperationalRules::class)->renewalAllowed());
    }

    /**
     * Bentuk yang benar-benar dikirim formulir: hidden "0" untuk saklar mati,
     * "1" untuk yang dicentang.
     */
    #[Test]
    public function the_switches_round_trip_through_the_form(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'allow_renewal' => '0',
            'require_active_member' => '1',
            'require_unblocked_member' => '0',
        ]))->assertSessionHasNoErrors();

        $rules = app(OperationalRules::class);
        $this->assertFalse($rules->renewalAllowed());
        $this->assertTrue($rules->requiresActiveMember());
        $this->assertFalse($rules->requiresUnblockedMember());

        // Formulir kembali menampilkan keadaan yang barusan disimpan.
        $this->get(route('admin.settings.operational_rules.edit'))
            ->assertOk()
            ->assertViewHas('settings', fn (array $settings) => $settings['allow_renewal'] === 'false'
                && $settings['require_active_member'] === 'true');
    }
}
