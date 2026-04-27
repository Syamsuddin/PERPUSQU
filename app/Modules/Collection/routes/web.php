<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Collection\Http\Controllers\PhysicalItemController;

Route::middleware('auth')->prefix('admin/collections')->name('admin.collections.')->group(function () {

    // Standard CRUD
    Route::get('items', [PhysicalItemController::class, 'index'])->name('items.index')->middleware('permission:collections.view');
    Route::get('items/create', [PhysicalItemController::class, 'create'])->name('items.create')->middleware('permission:collections.create');
    Route::post('items', [PhysicalItemController::class, 'store'])->name('items.store')->middleware('permission:collections.create');
    Route::get('items/{item}', [PhysicalItemController::class, 'show'])->name('items.show')->middleware('permission:collections.view');
    Route::get('items/{item}/edit', [PhysicalItemController::class, 'edit'])->name('items.edit')->middleware('permission:collections.update');
    Route::put('items/{item}', [PhysicalItemController::class, 'update'])->name('items.update')->middleware('permission:collections.update');
    Route::delete('items/{item}', [PhysicalItemController::class, 'destroy'])->name('items.destroy')->middleware('permission:collections.delete');

    // Status management & history
    Route::post('items/{item}/status', [PhysicalItemController::class, 'changeStatus'])->name('items.change_status')->middleware('permission:collections.update');
    Route::get('items/{item}/history', [PhysicalItemController::class, 'history'])->name('items.history')->middleware('permission:collections.view');
});
