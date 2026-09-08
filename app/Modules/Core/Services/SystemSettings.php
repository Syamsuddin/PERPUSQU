<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\SystemSetting;

/**
 * Pembaca tabel `system_settings` — satu-satunya tempat yang menyentuhnya.
 *
 * Aturan sirkulasi punya pembungkus sendiri (OperationalRules) karena
 * kosakatanya khas domain. Pengaturan umum dan repositori digital diakses
 * langsung lewat kelas ini. Keduanya berbagi satu cache, sehingga tabel tetap
 * dibaca sekali per request betapa pun banyak modul yang bertanya.
 */
class SystemSettings
{
    /**
     * Nilai bawaan untuk setiap kunci yang dikenal.
     *
     * Untuk aturan sirkulasi, angka-angka ini sama persis dengan yang dulu
     * tertanam di kode. Untuk pengaturan digital dan umum, ini adalah perilaku
     * yang berlaku sebelum kuncinya disambungkan: batas unggah 50 MB, OCR
     * mati, pratinjau publik hidup, dan katalog publik terbuka.
     */
    public const DEFAULTS = [
        // ── Sirkulasi ──────────────────────────────────────────────────
        'loan_default_days' => 14,
        'loan_days_student' => 14,
        'loan_days_lecturer' => 30,
        'loan_days_staff' => 14,
        'loan_days_alumni' => 7,
        'loan_days_guest' => 7,
        'loan_renewal_days' => 7,
        'loan_max_renewal_count' => 2,
        'loan_max_active_loans' => 5,
        'fine_daily_amount' => 1000,
        'allow_renewal' => true,
        'require_active_member' => true,
        'require_unblocked_member' => true,

        // ── Repositori digital ─────────────────────────────────────────
        'asset_max_upload_size_mb' => 50,
        'ocr_enabled' => false,
        'public_preview_enabled' => true,

        // ── Umum ───────────────────────────────────────────────────────
        // app_name bawaannya mengikuti APP_NAME agar kelas ini tidak memuat
        // merek satu instalasi tertentu; lihat appName().
        'app_name' => null,
        'app_version' => '1.0.0',
        'maintenance_mode' => false,
    ];

    /** @var array<string, string>|null */
    protected ?array $values = null;

    // ── Repositori digital ─────────────────────────────────────────────

    /**
     * Batas ukuran unggahan aset digital, dalam megabyte.
     */
    public function maxUploadSizeMb(): int
    {
        return max(1, $this->integer('asset_max_upload_size_mb'));
    }

    /**
     * Batas yang sama dalam kilobyte — satuan yang dipakai aturan validasi
     * `max:` milik Laravel untuk berkas.
     */
    public function maxUploadSizeKilobytes(): int
    {
        return $this->maxUploadSizeMb() * 1024;
    }

    public function ocrEnabled(): bool
    {
        return $this->boolean('ocr_enabled');
    }

    public function publicPreviewEnabled(): bool
    {
        return $this->boolean('public_preview_enabled');
    }

    // ── Umum ───────────────────────────────────────────────────────────

    /**
     * Nama yang tampil di judul tab, sidebar, dan header OPAC. Bila belum
     * pernah diatur, mengikuti APP_NAME dari konfigurasi aplikasi.
     */
    public function appName(): string
    {
        $stored = trim((string) $this->raw('app_name'));

        return $stored !== '' ? $stored : (string) config('app.name', 'PERPUSQU');
    }

    public function appVersion(): string
    {
        $stored = trim((string) $this->raw('app_version'));

        return $stored !== '' ? $stored : (string) self::DEFAULTS['app_version'];
    }

    /**
     * Menutup katalog publik untuk pengunjung. Area admin sengaja tidak ikut
     * tertutup — lihat EnsurePublicCatalogueIsOpen.
     */
    public function maintenanceMode(): bool
    {
        return $this->boolean('maintenance_mode');
    }

    // ── Pembacaan mentah ───────────────────────────────────────────────

    public function has(string $key): bool
    {
        $this->load();

        return array_key_exists($key, $this->values)
            && $this->values[$key] !== null
            && $this->values[$key] !== '';
    }

    public function raw(string $key): ?string
    {
        return $this->has($key) ? (string) $this->values[$key] : null;
    }

    public function integer(string $key): int
    {
        return $this->has($key) ? (int) $this->values[$key] : (int) (self::DEFAULTS[$key] ?? 0);
    }

    public function float(string $key): float
    {
        return $this->has($key) ? (float) $this->values[$key] : (float) (self::DEFAULTS[$key] ?? 0);
    }

    public function boolean(string $key): bool
    {
        return $this->has($key)
            ? filter_var($this->values[$key], FILTER_VALIDATE_BOOLEAN)
            : (bool) (self::DEFAULTS[$key] ?? false);
    }

    /**
     * Nilai berlaku untuk sekumpulan kunci — dipakai formulir pengaturan agar
     * kunci yang belum pernah tersimpan tetap tampil dengan nilai bawaannya.
     *
     * @param  list<string>  $keys
     * @return array<string, mixed>
     */
    public function only(array $keys): array
    {
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = match ($key) {
                'app_name' => $this->appName(),
                'app_version' => $this->appVersion(),
                default => $this->has($key) ? $this->values[$key] : self::DEFAULTS[$key] ?? null,
            };
        }

        return $values;
    }

    /**
     * Buang cache. Dipanggil setelah pengaturan disimpan agar pembacaan
     * berikutnya dalam request yang sama sudah memakai nilai baru.
     */
    public function refresh(): void
    {
        $this->values = null;
    }

    protected function load(): void
    {
        $this->values ??= SystemSetting::query()->pluck('value', 'key')->all();
    }
}
