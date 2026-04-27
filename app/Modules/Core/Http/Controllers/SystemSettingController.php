<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Services\OperationalRuleService;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function __construct(protected OperationalRuleService $ruleService) {}

    public function edit()
    {
        $settings = $this->ruleService->getOperationalRules();
        return view('modules.core.system_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'loan_default_days' => 'required|integer|min:1|max:365',
            'loan_max_active_loans' => 'required|integer|min:1|max:50',
            'loan_max_renewal_count' => 'required|integer|min:0|max:10',
            'fine_daily_amount' => 'required|numeric|min:0|max:99999999.99',
        ]);

        $this->ruleService->updateOperationalRules($request->only([
            'loan_default_days', 'loan_max_active_loans',
            'loan_max_renewal_count', 'fine_daily_amount',
        ]));

        return back()->with('success', 'Aturan operasional berhasil diperbarui.');
    }
}
