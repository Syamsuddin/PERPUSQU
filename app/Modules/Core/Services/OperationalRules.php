<?php

namespace App\Modules\Core\Services;

/**
 * Aturan operasional sirkulasi, dalam kosakata domainnya sendiri.
 *
 * Sebelumnya angka-angka ini tersebar sebagai properti statis di
 * DueDateCalculator, FineAmountCalculator, dan LoanEligibilityService, sementara
 * halaman Pengaturan menulis ke `system_settings` yang tidak pernah dibaca.
 * Kelas ini membalik arahnya: pengaturan yang menentukan, dan nilai bawaan di
 * SystemSettings::DEFAULTS hanya berlaku bila kuncinya belum ada.
 *
 * Pembacaan tabel didelegasikan ke SystemSettings supaya seluruh aplikasi
 * berbagi satu cache, bukan satu cache per modul.
 */
class OperationalRules
{
    /**
     * Kunci yang dimiliki halaman Aturan Operasional bagian sirkulasi.
     */
    public const KEYS = [
        'loan_default_days',
        'loan_days_student',
        'loan_days_lecturer',
        'loan_days_staff',
        'loan_days_alumni',
        'loan_days_guest',
        'loan_renewal_days',
        'loan_max_active_loans',
        'loan_max_renewal_count',
        'fine_daily_amount',
        'allow_renewal',
        'require_active_member',
        'require_unblocked_member',
    ];

    public function __construct(
        protected SystemSettings $settings,
    ) {}

    /**
     * Lama pinjam untuk satu jenis anggota.
     *
     * Urutan penelusuran: kunci khusus jenis anggota → `loan_default_days`
     * (cadangan untuk jenis yang belum punya kuncinya sendiri) → nilai bawaan.
     */
    public function loanPeriodDays(string $memberType): int
    {
        $specificKey = 'loan_days_'.$memberType;

        if ($this->settings->has($specificKey)) {
            return $this->settings->integer($specificKey);
        }

        if ($this->settings->has('loan_default_days')) {
            return $this->settings->integer('loan_default_days');
        }

        return (int) (SystemSettings::DEFAULTS[$specificKey] ?? SystemSettings::DEFAULTS['loan_default_days']);
    }

    public function renewalPeriodDays(): int
    {
        return $this->settings->integer('loan_renewal_days');
    }

    public function maxRenewals(): int
    {
        return $this->settings->integer('loan_max_renewal_count');
    }

    public function maxActiveLoans(): int
    {
        return $this->settings->integer('loan_max_active_loans');
    }

    public function fineDailyAmount(): int
    {
        return $this->settings->integer('fine_daily_amount');
    }

    public function renewalAllowed(): bool
    {
        return $this->settings->boolean('allow_renewal');
    }

    public function requiresActiveMember(): bool
    {
        return $this->settings->boolean('require_active_member');
    }

    public function requiresUnblockedMember(): bool
    {
        return $this->settings->boolean('require_unblocked_member');
    }

    /**
     * Aturan sirkulasi sebagai array — dipakai halaman pengaturan untuk mengisi
     * formulir, termasuk kunci yang belum pernah tersimpan.
     */
    public function all(): array
    {
        return $this->settings->only(self::KEYS);
    }

    public function refresh(): void
    {
        $this->settings->refresh();
    }
}
