<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menyiapkan kunci repositori digital dan umum sebelum kode mulai membacanya.
 *
 * Berbeda dengan aturan sirkulasi, kunci-kunci ini belum pernah menentukan
 * apa pun, jadi tidak ada perilaku lama yang perlu diabadikan — kecuali satu:
 * `maintenance_mode`. Selama ini nilainya diabaikan sepenuhnya. Begitu
 * middleware EnsurePublicCatalogueIsOpen aktif, sebuah nilai `true` yang
 * terlanjur mengendap di basis data akan menutup katalog publik pada detik
 * penerapan, tanpa ada yang memutuskannya. Karena itu nilainya dipaksa `false`;
 * pengelola yang memang ingin menutup katalog dapat menyalakannya dari halaman
 * Aturan Operasional.
 */
return new class extends Migration
{
    /**
     * Nilai yang mencerminkan perilaku sebelum kuncinya disambungkan:
     * batas unggah 50 MB (angka tetap di StoreDigitalAssetRequest) dan
     * pratinjau publik terbuka.
     */
    private const RULES = [
        ['key' => 'asset_max_upload_size_mb', 'value' => '50', 'type' => 'integer', 'group_name' => 'digital', 'is_public' => false],
        ['key' => 'ocr_enabled', 'value' => 'false', 'type' => 'boolean', 'group_name' => 'digital', 'is_public' => false],
        ['key' => 'public_preview_enabled', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'digital', 'is_public' => true],
        ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group_name' => 'general', 'is_public' => false],
    ];

    public function up(): void
    {
        $existing = DB::table('system_settings')->pluck('key')->all();
        $now = now();

        $missing = array_values(array_filter(
            self::RULES,
            fn (array $rule) => ! in_array($rule['key'], $existing, true)
        ));

        if ($missing !== []) {
            DB::table('system_settings')->insert(array_map(
                fn (array $rule) => $rule + ['created_at' => $now, 'updated_at' => $now],
                $missing
            ));
        }

        // Satu-satunya nilai yang ditimpa, dan alasannya ada di docblock kelas.
        DB::table('system_settings')
            ->where('key', 'maintenance_mode')
            ->update(['value' => 'false', 'updated_at' => $now]);
    }

    public function down(): void
    {
        // Tidak ada yang dikembalikan: kunci-kunci ini sudah lebih dulu
        // didaftarkan SystemSettingSeeder, dan menghapusnya justru akan
        // menghilangkan pengaturan yang mungkin sudah disesuaikan pengelola.
    }
};
