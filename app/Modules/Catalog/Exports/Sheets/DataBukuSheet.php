<?php

namespace App\Modules\Catalog\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataBukuSheet implements FromArray, WithTitle, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['Pemrograman Laravel', 'Budi Santoso, Ahmad Fauzi', 'Informatika', '2023', '978-602-1234-56-7', 'Indonesia', '005.13', 'Pemrograman, Web', 'laravel, php, web', 3, 'Rak A-01', 'Baik', '2024-01-15', 'Buku ini membahas...'],
        ];
    }

    public function headings(): array
    {
        return [
            'Judul (Wajib)',
            'Pengarang (Pisah Koma) (Wajib)',
            'Penerbit',
            'Tahun Terbit',
            'ISBN',
            'Bahasa',
            'Klasifikasi (DDC)',
            'Subjek (Pisah Koma)',
            'Kata Kunci',
            'Jumlah Eksemplar (Wajib)',
            'Lokasi Rak',
            'Kondisi',
            'Tanggal Pengadaan (YYYY-MM-DD)',
            'Abstrak'
        ];
    }

    public function title(): string
    {
        return 'DATA_BUKU';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E3A5F']]],
        ];
    }
}
