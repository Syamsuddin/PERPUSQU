<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Identity & Profile
            'users.view', 'users.create', 'users.update', 'users.delete',
            'users.activate', 'users.reset_password',
            'roles.view', 'roles.create', 'roles.update', 'roles.delete',
            'permissions.view', 'permissions.manage',
            'user_roles.assign',
            'own_profile.view', 'own_profile.update',
            'own_password.change',

            // Core
            'core.view_dashboard',
            'core.view_institution_profile', 'core.update_institution_profile',
            'core.view_operational_rules', 'core.update_operational_rules',

            // Master Data
            'authors.view', 'authors.create', 'authors.update', 'authors.delete',
            'publishers.view', 'publishers.create', 'publishers.update', 'publishers.delete',
            'languages.view', 'languages.create', 'languages.update', 'languages.delete',
            'classifications.view', 'classifications.create', 'classifications.update', 'classifications.delete',
            'subjects.view', 'subjects.create', 'subjects.update', 'subjects.delete',
            'collection_types.view', 'collection_types.create', 'collection_types.update', 'collection_types.delete',
            'rack_locations.view', 'rack_locations.create', 'rack_locations.update', 'rack_locations.delete',
            'faculties.view', 'faculties.create', 'faculties.update', 'faculties.delete',
            'study_programs.view', 'study_programs.create', 'study_programs.update', 'study_programs.delete',
            'item_conditions.view', 'item_conditions.create', 'item_conditions.update', 'item_conditions.delete',

            // Catalog
            'catalog.view', 'catalog.view_detail', 'catalog.create', 'catalog.update', 'catalog.delete',
            'catalog.publish', 'catalog.unpublish',

            // Collection
            'collections.view', 'collections.view_detail', 'collections.create', 'collections.update', 'collections.delete',
            'collections.change_status', 'collections.view_history',

            // Member
            'members.view', 'members.view_detail', 'members.create', 'members.update', 'members.delete',
            'members.activate', 'members.deactivate', 'members.block', 'members.unblock',
            'members.view_history', 'members.import',

            // Circulation
            'circulation.process_loan', 'circulation.process_return', 'circulation.process_renewal',
            'circulation.view_active_loans', 'circulation.view_history', 'circulation.view_fines',

            // Digital Repository
            'digital_assets.view', 'digital_assets.view_detail', 'digital_assets.create',
            'digital_assets.update', 'digital_assets.delete', 'digital_assets.preview',
            'digital_assets.publish', 'digital_assets.unpublish', 'digital_assets.manage_access',
            'digital_assets.run_ocr', 'digital_assets.reindex', 'digital_assets.download_private',

            // Reporting
            'reports.view_dashboard', 'reports.view_collections', 'reports.view_members',
            'reports.view_circulation', 'reports.view_fines', 'reports.view_popular_collections',
            'reports.view_digital_access', 'reports.export',

            // Audit
            'audit_logs.view', 'audit_logs.view_detail', 'audit_logs.export',
            'queue_monitor.view', 'queue_monitor.manage_retry',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
