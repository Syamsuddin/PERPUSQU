<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Core\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'loan_default_days', 'value' => '14', 'type' => 'integer', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'loan_max_active_loans', 'value' => '5', 'type' => 'integer', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'loan_max_renewal_count', 'value' => '2', 'type' => 'integer', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'allow_renewal', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'require_active_member', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'require_unblocked_member', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'fine_daily_amount', 'value' => '1000', 'type' => 'numeric', 'group_name' => 'circulation', 'is_public' => false],
            ['key' => 'asset_max_upload_size_mb', 'value' => '50', 'type' => 'integer', 'group_name' => 'digital', 'is_public' => false],
            ['key' => 'ocr_enabled', 'value' => 'false', 'type' => 'boolean', 'group_name' => 'digital', 'is_public' => false],
            ['key' => 'public_preview_enabled', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'digital', 'is_public' => true],
            ['key' => 'app_name', 'value' => 'PERPUSQU', 'type' => 'string', 'group_name' => 'general', 'is_public' => true],
            ['key' => 'app_version', 'value' => '1.0.0', 'type' => 'string', 'group_name' => 'general', 'is_public' => true],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group_name' => 'general', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
