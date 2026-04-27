<?php

namespace App\Modules\Opac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Opac\Services\OpacSearchService;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Language;
use Illuminate\Http\Request;

class OpacSearchController extends Controller
{
    public function __construct(protected OpacSearchService $searchService) {}

    public function index(Request $request)
    {
        $results = $this->searchService->search($request->all());
        $collectionTypes = CollectionType::active()->orderBy('name')->get();
        $languages = Language::active()->orderBy('name')->get();

        return view('modules.opac.search', compact('results', 'collectionTypes', 'languages'));
    }
}
