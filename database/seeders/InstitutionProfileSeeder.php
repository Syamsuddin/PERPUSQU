<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Core\Models\InstitutionProfile;

class InstitutionProfileSeeder extends Seeder
{
    public function run(): void
    {
        InstitutionProfile::firstOrCreate(
            ['id' => 1],
            [
                'institution_name' => 'Universitas Contoh Nusantara',
                'library_name' => 'Perpustakaan Pusat GIBTHA LIBRARY',
                'address' => 'Jl. Pendidikan No. 1, Kota Ilmu, Provinsi Cerdas 12345',
                'phone' => '(021) 555-0100',
                'email' => 'perpustakaan@ucn.ac.id',
                'website' => 'https://perpustakaan.ucn.ac.id',
                'about_text' => 'Perpustakaan Pusat Universitas Contoh Nusantara menyediakan layanan informasi dan koleksi akademik untuk mendukung kegiatan pendidikan, penelitian, dan pengabdian masyarakat.',
            ]
        );
    }
}
