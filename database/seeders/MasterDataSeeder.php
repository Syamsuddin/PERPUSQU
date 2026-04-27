<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Models\Classification;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Languages
        $languages = [
            ['code' => 'id', 'name' => 'Indonesia', 'is_active' => true],
            ['code' => 'en', 'name' => 'Inggris', 'is_active' => true],
            ['code' => 'ar', 'name' => 'Arab', 'is_active' => true],
            ['code' => 'zh', 'name' => 'Mandarin', 'is_active' => true],
            ['code' => 'ja', 'name' => 'Jepang', 'is_active' => true],
            ['code' => 'de', 'name' => 'Jerman', 'is_active' => true],
            ['code' => 'fr', 'name' => 'Prancis', 'is_active' => true],
        ];
        foreach ($languages as $lang) {
            Language::firstOrCreate(['code' => $lang['code']], $lang);
        }

        // Collection Types
        $types = [
            ['code' => 'BUKU', 'name' => 'Buku', 'is_active' => true],
            ['code' => 'SKRIP', 'name' => 'Skripsi', 'is_active' => true],
            ['code' => 'TESIS', 'name' => 'Tesis', 'is_active' => true],
            ['code' => 'DISER', 'name' => 'Disertasi', 'is_active' => true],
            ['code' => 'JURNAL', 'name' => 'Jurnal', 'is_active' => true],
            ['code' => 'MODUL', 'name' => 'Modul', 'is_active' => true],
            ['code' => 'PROSID', 'name' => 'Prosiding', 'is_active' => true],
            ['code' => 'LAPKP', 'name' => 'Laporan KP/PKL', 'is_active' => true],
            ['code' => 'EBOOK', 'name' => 'E-Book', 'is_active' => true],
        ];
        foreach ($types as $type) {
            CollectionType::firstOrCreate(['code' => $type['code']], $type);
        }

        // Item Conditions
        $conditions = [
            ['code' => 'BAIK', 'name' => 'Baik', 'severity_level' => 1, 'is_active' => true],
            ['code' => 'RUSRIN', 'name' => 'Rusak Ringan', 'severity_level' => 3, 'is_active' => true],
            ['code' => 'RUSBER', 'name' => 'Rusak Berat', 'severity_level' => 7, 'is_active' => true],
            ['code' => 'HILANG', 'name' => 'Hilang', 'severity_level' => 10, 'is_active' => true],
        ];
        foreach ($conditions as $cond) {
            ItemCondition::firstOrCreate(['code' => $cond['code']], $cond);
        }

        // Base Classifications (DDC - simplified)
        $classifications = [
            ['code' => '000', 'name' => 'Karya Umum', 'is_active' => true],
            ['code' => '100', 'name' => 'Filsafat dan Psikologi', 'is_active' => true],
            ['code' => '200', 'name' => 'Agama', 'is_active' => true],
            ['code' => '300', 'name' => 'Ilmu Sosial', 'is_active' => true],
            ['code' => '400', 'name' => 'Bahasa', 'is_active' => true],
            ['code' => '500', 'name' => 'Ilmu Murni', 'is_active' => true],
            ['code' => '600', 'name' => 'Teknologi dan Ilmu Terapan', 'is_active' => true],
            ['code' => '700', 'name' => 'Kesenian dan Olahraga', 'is_active' => true],
            ['code' => '800', 'name' => 'Sastra', 'is_active' => true],
            ['code' => '900', 'name' => 'Sejarah dan Geografi', 'is_active' => true],
        ];
        foreach ($classifications as $cls) {
            Classification::firstOrCreate(['code' => $cls['code']], $cls);
        }
    }
}
