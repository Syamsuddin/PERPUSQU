<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menuliskan aturan operasional yang selama ini tertanam di kode ke dalam
 * `system_settings`, SEBELUM sirkulasi mulai membaca dari tabel itu.
 *
 * Tanpa langkah ini, instalasi yang sudah berjalan akan berubah perilaku pada
 * detik penerapan: lama pinjam dosen (30 hari) akan jatuh ke `loan_default_days`
 * yang bernilai 14, dan instalasi yang pernah mengubah tarif denda akan
 * langsung memakai tarif itu tanpa ada yang memutuskan.
 *
 * Nilai di bawah sengaja ditulis apa adanya, bukan diambil dari
 * OperationalRules::DEFAULTS. Migrasi adalah catatan sejarah: tugasnya
 * mengabadikan perilaku pada saat ini, bukan mengikuti konstanta yang kelak
 * mungkin berubah.
 */
return new class extends Migration
{
    /**
     * Nilai yang berlaku di kode sebelum pengaturan disambungkan:
     * DueDateCalculator::$loanPeriods, $renewalPeriod, $maxRenewals;
     * LoanEligibilityService::$maxActiveLoans; FineAmountCalculator::$perDayRate.
     */
    private const RULES = [
        ['key' => 'loan_days_student', 'value' => '14', 'type' => 'integer'],
        ['key' => 'loan_days_lecturer', 'value' => '30', 'type' => 'integer'],
        ['key' => 'loan_days_staff', 'value' => '14', 'type' => 'integer'],
        ['key' => 'loan_days_alumni', 'value' => '7', 'type' => 'integer'],
        ['key' => 'loan_days_guest', 'value' => '7', 'type' => 'integer'],
        ['key' => 'loan_renewal_days', 'value' => '7', 'type' => 'integer'],
        ['key' => 'loan_default_days', 'value' => '14', 'type' => 'integer'],
        ['key' => 'loan_max_active_loans', 'value' => '5', 'type' => 'integer'],
        ['key' => 'loan_max_renewal_count', 'value' => '2', 'type' => 'integer'],
        ['key' => 'fine_daily_amount', 'value' => '1000', 'type' => 'numeric'],
        ['key' => 'allow_renewal', 'value' => 'true', 'type' => 'boolean'],
        ['key' => 'require_active_member', 'value' => 'true', 'type' => 'boolean'],
        ['key' => 'require_unblocked_member', 'value' => 'true', 'type' => 'boolean'],
    ];

    /**
     * Kunci yang benar-benar baru diperkenalkan migrasi ini. Hanya kunci-kunci
     * inilah yang boleh dihapus saat rollback; sisanya sudah ada sebelumnya
     * lewat SystemSettingSeeder dan bukan milik migrasi ini.
     */
    private const INTRODUCED_KEYS = [
        'loan_days_student',
        'loan_days_lecturer',
        'loan_days_staff',
        'loan_days_alumni',
        'loan_days_guest',
        'loan_renewal_days',
    ];

    public function up(): void
    {
        $existing = DB::table('system_settings')->pluck('key')->all();
        $now = now();

        $missing = array_values(array_filter(
            self::RULES,
            fn (array $rule) => ! in_array($rule['key'], $existing, true)
        ));

        if ($missing === []) {
            return;
        }

        // Nilai yang sudah pernah diatur administrator TIDAK disentuh; hanya
        // kunci yang belum ada yang diisi dengan nilai kode.
        DB::table('system_settings')->insert(array_map(fn (array $rule) => $rule + [
            'group_name' => 'circulation',
            'is_public' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ], $missing));
    }

    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', self::INTRODUCED_KEYS)->delete();
    }
};
