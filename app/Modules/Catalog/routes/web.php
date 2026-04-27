<?php

use App\Modules\Catalog\Http\Controllers\BibliographicRecordController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/catalog')->name('admin.catalog.')->group(function () {

    // Standard CRUD
    Route::get('records', [BibliographicRecordController::class, 'index'])->name('records.index')->middleware('permission:catalog.view');
    Route::get('records/create', [BibliographicRecordController::class, 'create'])->name('records.create')->middleware('permission:catalog.create');
    Route::post('records', [BibliographicRecordController::class, 'store'])->name('records.store')->middleware('permission:catalog.create');
    Route::get('records/{record}', [BibliographicRecordController::class, 'show'])->name('records.show')->middleware('permission:catalog.view');
    Route::get('records/{record}/edit', [BibliographicRecordController::class, 'edit'])->name('records.edit')->middleware('permission:catalog.update');
    Route::put('records/{record}', [BibliographicRecordController::class, 'update'])->name('records.update')->middleware('permission:catalog.update');
    Route::delete('records/{record}', [BibliographicRecordController::class, 'destroy'])->name('records.destroy')->middleware('permission:catalog.delete');

    // Publication State Transitions
    Route::post('records/{record}/publish', [BibliographicRecordController::class, 'publish'])->name('records.publish')->middleware('permission:catalog.publish');
    Route::post('records/{record}/unpublish', [BibliographicRecordController::class, 'unpublish'])->name('records.unpublish')->middleware('permission:catalog.unpublish');
    Route::post('records/{record}/archive', [BibliographicRecordController::class, 'archive'])->name('records.archive')->middleware('permission:catalog.publish');
    Route::post('records/{record}/reactivate', [BibliographicRecordController::class, 'reactivate'])->name('records.reactivate')->middleware('permission:catalog.publish');
});
