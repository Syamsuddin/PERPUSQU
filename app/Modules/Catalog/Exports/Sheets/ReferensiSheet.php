<?php

namespace App\Modules\Catalog\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\ItemCondition;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReferensiSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $publishers = Publisher::pluck('name')->toArray();
        $languages = Language::pluck('name')->toArray();
        $racks = RackLocation::active()->pluck('name')->toArray();
        $conditions = ItemCondition::active()->pluck('name')->toArray();

        $max = max(count($publishers), count($languages), count($racks), count($conditions));

        $data = [];
        for ($i = 0; $i < $max; $i++) {
            $data[] = [
                $publishers[$i] ?? '',
                $languages[$i] ?? '',
                $racks[$i] ?? '',
                $conditions[$i] ?? '',
            ];
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Penerbit', 'Bahasa', 'Lokasi Rak', 'Kondisi'];
    }

    public function title(): string
    {
        return 'REFERENSI';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4A5568']]],
        ];
    }
}
