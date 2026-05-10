<?php

use App\Modules\Audit\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:audit_logs.view'])->prefix('admin/audit')->name('admin.audit.')->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('index');
});
