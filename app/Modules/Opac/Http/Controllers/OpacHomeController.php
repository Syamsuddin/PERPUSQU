<?php

namespace App\Modules\Opac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\Opac\Services\OpacSearchService;

class OpacHomeController extends Controller
{
    public function __construct(protected OpacSearchService $searchService) {}

    public function index()
    {
        $latestRecords = $this->searchService->getLatestRecords(8);
        $stats = $this->searchService->getPublicStats();
        $collectionTypes = CollectionType::active()->orderBy('name')->get();

        return view('modules.opac.home', compact('latestRecords', 'stats', 'collectionTypes'));
    }
}
