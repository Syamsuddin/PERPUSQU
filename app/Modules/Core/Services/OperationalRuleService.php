<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\SystemSetting;

class OperationalRuleService
{
    public function getOperationalRules(): array
    {
        return SystemSetting::all()->pluck('value', 'key')->toArray();
    }

    public function updateOperationalRules(array $data): void
    {
        foreach ($data as $key => $value) {
            SystemSetting::where('key', $key)->update(['value' => (string) $value]);
        }

        activity('core')
            ->causedBy(auth()->user())
            ->withProperties(['updated_keys' => array_keys($data)])
            ->log('Aturan operasional diperbarui');
    }
}
