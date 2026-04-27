<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Profile\Http\Controllers\ProfileController;

Route::middleware('auth')->prefix('admin/profile')->name('admin.profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show')->middleware('permission:own_profile.view');
    Route::put('/', [ProfileController::class, 'update'])->name('update')->middleware('permission:own_profile.update');
    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('password.edit')->middleware('permission:own_password.change');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update')->middleware('permission:own_password.change');
});
