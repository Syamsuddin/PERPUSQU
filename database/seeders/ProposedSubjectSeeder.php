<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposedSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Umum Keislaman
            'Al-Qur\'an dan Tafsir',
            'Hadits dan Ulumul Hadits',
            'Aqidah dan Tauhid',
            'Akhlak dan Tasawuf',
            'Fiqh dan Ushul Fiqh',
            'Sejarah Kebudayaan Islam (SKI)',
            'Bahasa Arab (Umum)',
            'Studi Islam Kontemporer',
            
            // Tarbiyah - PAI
            'Metodologi Pengajaran PAI',
            'Kurikulum Pendidikan Islam',
            'Filsafat Pendidikan Islam',
            'Psikologi Agama',
            'Evaluasi Pembelajaran',
            
            // Tarbiyah - PBA
            'Linguistik Arab',
            'Sastra Arab',
            'Sintaksis Arab (Nahwu)',
            'Morfologi Arab (Shorof)',
            'Balaghah',
            'Metode Pengajaran Bahasa Asing',
            
            // Tarbiyah - MPI
            'Kepemimpinan Pendidikan Islam',
            'Administrasi Pendidikan',
            'Manajemen Strategis Lembaga Pendidikan',
            'Supervisi Pendidikan',
            'Tata Kelola Madrasah/Pesantren',
            'Sistem Penjaminan Mutu Pendidikan',
            
            // Syariah - Ekonomi Syariah
            'Perbankan Syariah',
            'Akuntansi Syariah',
            'Manajemen Zakat dan Wakaf',
            'E-Commerce Syariah',
            'Etika Bisnis Islam',
            'Pasar Modal Syariah',
            'Fiqh Muamalah',
            'Ekonomi Makro & Mikro Islam',
            
            // Metodologi & Pendukung
            'Metodologi Penelitian Pendidikan',
            'Metodologi Penelitian Hukum/Ekonomi Islam',
            'Statistik Pendidikan',
            'Karya Ilmiah - Panduan Penulisan',
            'Teknologi Pendidikan (E-Learning)',
            'Kewirausahaan',
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->updateOrInsert(
                ['name' => $subject],
                [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
