<?php

use App\Modules\Reporting\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])
        ->name('index')
        ->middleware('permission:reports.view_collections|reports.view_members|reports.view_circulation|reports.view_fines');
});
