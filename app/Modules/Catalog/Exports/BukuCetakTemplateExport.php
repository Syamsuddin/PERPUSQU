<?php

namespace App\Modules\Catalog\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Modules\Catalog\Exports\Sheets\DataBukuSheet;
use App\Modules\Catalog\Exports\Sheets\ReferensiSheet;

class BukuCetakTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DataBukuSheet(),
            new ReferensiSheet()
        ];
    }
}
