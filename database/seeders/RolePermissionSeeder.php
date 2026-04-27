<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = Permission::pluck('name')->toArray();

        // Super Admin gets ALL permissions
        $superAdmin = Role::findByName('Super Admin', 'web');
        $superAdmin->syncPermissions($allPermissions);

        // Admin Perpustakaan — all except audit management
        $admin = Role::findByName('Admin Perpustakaan', 'web');
        $adminPerms = array_filter($allPermissions, fn ($p) =>
            !str_starts_with($p, 'queue_monitor.manage') &&
            $p !== 'audit_logs.export'
        );
        $admin->syncPermissions($adminPerms);

        // Pustakawan
        $pustakawan = Role::findByName('Pustakawan', 'web');
        $pustakawan->syncPermissions([
            'core.view_dashboard', 'own_profile.view', 'own_profile.update', 'own_password.change',
            'authors.view', 'authors.create', 'authors.update',
            'publishers.view', 'publishers.create', 'publishers.update',
            'languages.view', 'languages.create', 'languages.update',
            'classifications.view', 'classifications.create', 'classifications.update',
            'subjects.view', 'subjects.create', 'subjects.update',
            'collection_types.view', 'collection_types.create', 'collection_types.update',
            'rack_locations.view', 'rack_locations.create', 'rack_locations.update',
            'item_conditions.view',
            'faculties.view', 'study_programs.view',
            'catalog.view', 'catalog.view_detail', 'catalog.create', 'catalog.update',
            'catalog.publish', 'catalog.unpublish',
            'collections.view', 'collections.view_detail', 'collections.create', 'collections.update',
            'collections.change_status', 'collections.view_history',
            'members.view', 'members.view_detail',
            'circulation.view_active_loans', 'circulation.view_history',
            'digital_assets.view', 'digital_assets.view_detail',
            'reports.view_dashboard', 'reports.view_collections',
        ]);

        // Petugas Sirkulasi
        $sirkulasi = Role::findByName('Petugas Sirkulasi', 'web');
        $sirkulasi->syncPermissions([
            'core.view_dashboard', 'own_profile.view', 'own_profile.update', 'own_password.change',
            'catalog.view', 'catalog.view_detail',
            'collections.view', 'collections.view_detail',
            'members.view', 'members.view_detail', 'members.view_history',
            'circulation.process_loan', 'circulation.process_return', 'circulation.process_renewal',
            'circulation.view_active_loans', 'circulation.view_history', 'circulation.view_fines',
            'reports.view_dashboard', 'reports.view_circulation', 'reports.view_fines',
        ]);

        // Operator Repositori Digital
        $operator = Role::findByName('Operator Repositori Digital', 'web');
        $operator->syncPermissions([
            'core.view_dashboard', 'own_profile.view', 'own_profile.update', 'own_password.change',
            'catalog.view', 'catalog.view_detail',
            'digital_assets.view', 'digital_assets.view_detail', 'digital_assets.create',
            'digital_assets.update', 'digital_assets.delete', 'digital_assets.preview',
            'digital_assets.publish', 'digital_assets.unpublish', 'digital_assets.manage_access',
            'digital_assets.run_ocr', 'digital_assets.reindex', 'digital_assets.download_private',
            'authors.view', 'publishers.view', 'languages.view', 'classifications.view',
            'subjects.view', 'collection_types.view',
            'reports.view_dashboard', 'reports.view_digital_access',
        ]);

        // Pimpinan Perpustakaan
        $pimpinan = Role::findByName('Pimpinan Perpustakaan', 'web');
        $pimpinan->syncPermissions([
            'core.view_dashboard', 'own_profile.view', 'own_profile.update', 'own_password.change',
            'catalog.view', 'catalog.view_detail',
            'collections.view', 'collections.view_detail',
            'members.view', 'members.view_detail',
            'circulation.view_active_loans', 'circulation.view_history', 'circulation.view_fines',
            'digital_assets.view', 'digital_assets.view_detail',
            'reports.view_dashboard', 'reports.view_collections', 'reports.view_members',
            'reports.view_circulation', 'reports.view_fines', 'reports.view_popular_collections',
            'reports.view_digital_access', 'reports.export',
            'audit_logs.view', 'audit_logs.view_detail',
        ]);
    }
}
