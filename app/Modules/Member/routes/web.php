<?php

use App\Modules\Member\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/members')->name('admin.members.')->group(function () {

    Route::get('/', [MemberController::class, 'index'])->name('index')->middleware('permission:members.view');
    Route::get('create', [MemberController::class, 'create'])->name('create')->middleware('permission:members.create');
    Route::post('/', [MemberController::class, 'store'])->name('store')->middleware('permission:members.create');
    Route::get('{member}', [MemberController::class, 'show'])->name('show')->middleware('permission:members.view');
    Route::get('{member}/edit', [MemberController::class, 'edit'])->name('edit')->middleware('permission:members.update');
    Route::put('{member}', [MemberController::class, 'update'])->name('update')->middleware('permission:members.update');
    Route::delete('{member}', [MemberController::class, 'destroy'])->name('destroy')->middleware('permission:members.delete');

    // Activate / Deactivate
    Route::post('{member}/activate', [MemberController::class, 'activate'])->name('activate')->middleware('permission:members.update');
    Route::post('{member}/deactivate', [MemberController::class, 'deactivate'])->name('deactivate')->middleware('permission:members.update');

    // Block / Unblock
    Route::post('{member}/block', [MemberController::class, 'block'])->name('block')->middleware('permission:members.block');
    Route::post('{member}/unblock', [MemberController::class, 'unblock'])->name('unblock')->middleware('permission:members.unblock');

    // History
    Route::get('{member}/history', [MemberController::class, 'history'])->name('history')->middleware('permission:members.view');
});
