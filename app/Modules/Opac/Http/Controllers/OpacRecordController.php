<?php

namespace App\Modules\Opac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Opac\Services\OpacSearchService;

class OpacRecordController extends Controller
{
    public function __construct(protected OpacSearchService $searchService) {}

    public function show(int $id)
    {
        $record = $this->searchService->findPublicRecord($id);
        if (!$record) {
            abort(404, 'Katalog tidak ditemukan atau tidak tersedia untuk publik.');
        }

        // Count availability — NO barcode/inventory exposed
        $availableCount = $record->physicalItems->where('item_status', 'available')->count();
        $totalItems = $record->physicalItems->count();

        return view('modules.opac.record', compact('record', 'availableCount', 'totalItems'));
    }
}
