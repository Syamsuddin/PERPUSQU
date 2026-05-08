<?php

use App\Modules\Catalog\Http\Controllers\BibliographicRecordController;
use App\Modules\Catalog\Http\Controllers\QuickEntryController;
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

    // ── Quick Entry: 2 Jalur Koleksi ──────────────────────────────────────
    Route::get('quick-entry', [QuickEntryController::class, 'index'])->name('quick-entry.index')->middleware('permission:catalog.create');
    Route::get('quick-entry/cetak', [QuickEntryController::class, 'createPrint'])->name('quick-entry.cetak.create')->middleware('permission:catalog.create');
    Route::post('quick-entry/cetak', [QuickEntryController::class, 'storePrint'])->name('quick-entry.cetak.store')->middleware('permission:catalog.create');
    Route::get('quick-entry/ebook', [QuickEntryController::class, 'createEbook'])->name('quick-entry.ebook.create')->middleware('permission:catalog.create');
    Route::post('quick-entry/ebook', [QuickEntryController::class, 'storeEbook'])->name('quick-entry.ebook.store')->middleware('permission:catalog.create');

    // ── Bulk Import (Excel) ──────────────────────────────────────────────
    Route::get('bulk-import', [\App\Modules\Catalog\Http\Controllers\BulkImportController::class, 'index'])->name('bulk-import.index')->middleware('permission:catalog.create');
    Route::get('bulk-import/template', [\App\Modules\Catalog\Http\Controllers\BulkImportController::class, 'downloadTemplate'])->name('bulk-import.template')->middleware('permission:catalog.create');
    Route::post('bulk-import/preview', [\App\Modules\Catalog\Http\Controllers\BulkImportController::class, 'preview'])->name('bulk-import.preview')->middleware('permission:catalog.create');
    Route::post('bulk-import/process', [\App\Modules\Catalog\Http\Controllers\BulkImportController::class, 'process'])->name('bulk-import.process')->middleware('permission:catalog.create');
});

