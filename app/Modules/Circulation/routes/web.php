<?php

use App\Modules\Circulation\Http\Controllers\FineController;
use App\Modules\Circulation\Http\Controllers\LoanController;
use App\Modules\Circulation\Http\Controllers\ReturnTransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/circulation')->name('admin.circulation.')->group(function () {

    // Loan: Create & Store
    Route::get('loans/create', [LoanController::class, 'create'])->name('loans.create')->middleware('permission:circulation.process_loan');
    Route::post('loans', [LoanController::class, 'store'])->name('loans.store')->middleware('permission:circulation.process_loan');

    // Active loans
    Route::get('loans/active', [LoanController::class, 'active'])->name('loans.active')->middleware('permission:circulation.view_active_loans');

    // History
    Route::get('loans/history', [LoanController::class, 'history'])->name('loans.history')->middleware('permission:circulation.view_history');

    // Loan detail & renewal
    Route::get('loans/{loan}', [LoanController::class, 'show'])->name('loans.show')->middleware('permission:circulation.view_active_loans');
    Route::post('loans/{loan}/renew', [LoanController::class, 'renew'])->name('loans.renew')->middleware('permission:circulation.process_renewal');

    // Return
    Route::get('returns/create', [ReturnTransactionController::class, 'create'])->name('returns.create')->middleware('permission:circulation.process_return');
    Route::post('returns', [ReturnTransactionController::class, 'store'])->name('returns.store')->middleware('permission:circulation.process_return');

    // Fines
    Route::get('fines', [FineController::class, 'index'])->name('fines.index')->middleware('permission:circulation.view_fines');
    Route::post('fines/{fine}/settle', [FineController::class, 'settle'])->name('fines.settle')->middleware('permission:circulation.view_fines');
    Route::post('fines/{fine}/waive', [FineController::class, 'waive'])->name('fines.waive')->middleware('permission:circulation.view_fines');
});
