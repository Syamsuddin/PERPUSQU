<?php

namespace App\Modules\Circulation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Circulation\Http\Requests\StoreReturnRequest;
use App\Modules\Circulation\Services\ReturnProcessingService;
use App\Modules\MasterData\Models\ItemCondition;

class ReturnTransactionController extends Controller
{
    public function __construct(
        protected ReturnProcessingService $returnService,
    ) {}

    public function create()
    {
        $itemConditions = ItemCondition::active()->orderBy('name')->get();

        return view('modules.circulation.returns.create', compact('itemConditions'));
    }

    public function store(StoreReturnRequest $request)
    {
        try {
            $result = $this->returnService->processReturn(
                $request->barcode,
                $request->returned_condition_id,
                $request->notes,
            );

            $msg = "Pengembalian berhasil: {$request->barcode}.";
            if ($result['late_days'] > 0) {
                $msg .= " Terlambat {$result['late_days']} hari. Denda: Rp ".number_format($result['fine_amount'], 0, ',', '.');
            }

            return redirect()->route('admin.circulation.loans.active')->with('success', $msg);
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
