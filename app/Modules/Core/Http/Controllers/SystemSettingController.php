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
        $validated = $request->validate([
            // Lama pinjam per jenis anggota. `loan_default_days` tetap ada
            // sebagai cadangan untuk jenis anggota di luar kelima ini.
            'loan_default_days' => 'required|integer|min:1|max:365',
            'loan_days_student' => 'required|integer|min:1|max:365',
            'loan_days_lecturer' => 'required|integer|min:1|max:365',
            'loan_days_staff' => 'required|integer|min:1|max:365',
            'loan_days_alumni' => 'required|integer|min:1|max:365',
            'loan_days_guest' => 'required|integer|min:1|max:365',

            'loan_renewal_days' => 'required|integer|min:1|max:365',
            'loan_max_active_loans' => 'required|integer|min:1|max:50',
            'loan_max_renewal_count' => 'required|integer|min:0|max:10',
            'fine_daily_amount' => 'required|numeric|min:0|max:99999999.99',

            'allow_renewal' => 'nullable|boolean',
            'require_active_member' => 'nullable|boolean',
            'require_unblocked_member' => 'nullable|boolean',

            // Repositori digital
            'asset_max_upload_size_mb' => 'required|integer|min:1|max:2048',
            'ocr_enabled' => 'nullable|boolean',
            'public_preview_enabled' => 'nullable|boolean',

            // Umum. `app_version` tidak ikut: nilainya menggambarkan kode yang
            // terpasang, bukan kebijakan yang boleh diubah administrator.
            'app_name' => 'required|string|min:2|max:100',
            'maintenance_mode' => 'nullable|boolean',
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'asset_max_upload_size_mb.max' => 'Batas unggah maksimum 2048 MB (2 GB).',
        ]);

        // Checkbox yang tidak dicentang tidak ikut terkirim; ketiadaannya
        // berarti "mati", bukan "biarkan seperti sebelumnya".
        foreach (OperationalRuleService::TOGGLE_KEYS as $toggle) {
            $validated[$toggle] = $request->boolean($toggle) ? 'true' : 'false';
        }

        $this->ruleService->updateOperationalRules($validated);

        return back()->with('success', 'Aturan operasional berhasil diperbarui.');
    }
}
