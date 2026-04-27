<?php

namespace App\Modules\Circulation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Services\FineService;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function __construct(
        protected FineService $fineService,
    ) {}

    public function index(Request $request)
    {
        $items = $this->fineService->getPaginated($request->all());
        $summary = $this->fineService->getSummary();

        return view('modules.circulation.fines.index', compact('items', 'summary'));
    }

    public function settle(Fine $fine)
    {
        try {
            $this->fineService->settle($fine);

            return back()->with('success', 'Denda berhasil dilunasi.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function waive(Fine $fine)
    {
        try {
            $this->fineService->waive($fine);

            return back()->with('success', 'Denda berhasil dihapuskan.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
