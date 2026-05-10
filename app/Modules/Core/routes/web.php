<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Http\Controllers\DashboardController;
use App\Modules\Core\Http\Controllers\InstitutionProfileController;
use App\Modules\Core\Http\Controllers\SystemSettingController;

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard.index')
        ->middleware('permission:core.view_dashboard');

    // Settings - Institution Profile
    Route::get('/admin/settings/institution-profile', [InstitutionProfileController::class, 'edit'])
        ->name('admin.settings.institution_profile.edit')
        ->middleware('permission:core.view_institution_profile');
    Route::put('/admin/settings/institution-profile', [InstitutionProfileController::class, 'update'])
        ->name('admin.settings.institution_profile.update')
        ->middleware('permission:core.update_institution_profile');

    // Settings - Operational Rules
    Route::get('/admin/settings/operational-rules', [SystemSettingController::class, 'edit'])
        ->name('admin.settings.operational_rules.edit')
        ->middleware('permission:core.view_operational_rules');
    Route::put('/admin/settings/operational-rules', [SystemSettingController::class, 'update'])
        ->name('admin.settings.operational_rules.update')
        ->middleware('permission:core.update_operational_rules');

    // Guides
    Route::get('/admin/guides/superadmin', function () {
        return view('modules.core.guides.superadmin');
    })->name('admin.guides.superadmin');

    Route::get('/admin/guides/pustakawan', function () {
        return view('modules.core.guides.pustakawan');
    })->name('admin.guides.pustakawan');
});
