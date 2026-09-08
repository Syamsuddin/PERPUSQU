<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\SystemSetting;

class OperationalRuleService
{
    /** Kunci repositori digital yang dikelola halaman Aturan Operasional. */
    public const DIGITAL_KEYS = [
        'asset_max_upload_size_mb',
        'ocr_enabled',
        'public_preview_enabled',
    ];

    /** Kunci umum yang dapat disunting. `app_version` sengaja di luar daftar. */
    public const GENERAL_KEYS = [
        'app_name',
        'maintenance_mode',
    ];

    /**
     * Saklar hidup/mati. Checkbox yang tidak dicentang tidak dikirim peramban,
     * jadi ketiadaannya harus dimaknai "mati" — bukan "biarkan seperti semula".
     */
    public const TOGGLE_KEYS = [
        'allow_renewal',
        'require_active_member',
        'require_unblocked_member',
        'ocr_enabled',
        'public_preview_enabled',
        'maintenance_mode',
    ];

    public function __construct(
        protected OperationalRules $rules,
        protected SystemSettings $settings,
    ) {}

    /**
     * Nilai untuk mengisi formulir. Diambil lewat OperationalRules agar kunci
     * yang belum pernah tersimpan tetap tampil dengan nilai bawaannya —
     * bukan kosong — sehingga formulir tidak pernah menyarankan angka lain
     * daripada yang sedang dipakai sistem.
     */
    public function getOperationalRules(): array
    {
        return $this->rules->all() + $this->settings->only(array_merge(
            self::DIGITAL_KEYS,
            self::GENERAL_KEYS,
            ['app_version'], // hanya ditampilkan, tidak dapat disunting
        ));
    }

    public function updateOperationalRules(array $data): void
    {
        foreach ($data as $key => $value) {
            // firstOrNew, bukan update(): kunci yang belum punya baris dulu
            // gagal tersimpan tanpa pesan apa pun. Tipe dan grup hanya diisi
            // saat baris dibuat, sehingga klasifikasi baris lama tidak tertimpa.
            $setting = SystemSetting::query()->firstOrNew(['key' => $key]);

            if (! $setting->exists) {
                $setting->fill($this->metadataFor($key));
            }

            $setting->value = (string) $value;
            $setting->save();
        }

        // Pengaturan sudah berubah; pembacaan berikutnya dalam request ini
        // tidak boleh memakai nilai lama yang masih ter-cache.
        $this->settings->refresh();

        activity('core')
            ->causedBy(auth()->user())
            ->withProperties(['updated_keys' => array_keys($data)])
            ->log('Aturan operasional diperbarui');
    }

    /**
     * Kolom pelengkap untuk baris yang baru dibuat saja.
     */
    protected function metadataFor(string $key): array
    {
        return [
            'type' => match (true) {
                in_array($key, self::TOGGLE_KEYS, true) => 'boolean',
                $key === 'fine_daily_amount' => 'numeric',
                $key === 'app_name' => 'string',
                default => 'integer',
            },
            'group_name' => match (true) {
                in_array($key, self::DIGITAL_KEYS, true) => 'digital',
                in_array($key, self::GENERAL_KEYS, true) => 'general',
                default => 'circulation',
            },
            // Nama aplikasi tampil di halaman publik, jadi boleh dibaca tanpa login.
            'is_public' => $key === 'app_name',
        ];
    }
}
