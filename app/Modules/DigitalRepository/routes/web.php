<?php

use App\Modules\DigitalRepository\Http\Controllers\DigitalAssetController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/digital-assets')->name('admin.digital-assets.')->group(function () {

    Route::get('/', [DigitalAssetController::class, 'index'])->name('index')->middleware('permission:digital_assets.view');
    Route::get('create', [DigitalAssetController::class, 'create'])->name('create')->middleware('permission:digital_assets.create');
    Route::post('/', [DigitalAssetController::class, 'store'])->name('store')->middleware('permission:digital_assets.create');
    Route::get('{digital_asset}', [DigitalAssetController::class, 'show'])->name('show')->middleware('permission:digital_assets.view_detail');
    Route::get('{digital_asset}/edit', [DigitalAssetController::class, 'edit'])->name('edit')->middleware('permission:digital_assets.update');
    Route::put('{digital_asset}', [DigitalAssetController::class, 'update'])->name('update')->middleware('permission:digital_assets.update');
    Route::delete('{digital_asset}', [DigitalAssetController::class, 'destroy'])->name('destroy')->middleware('permission:digital_assets.delete');

    // Publication state
    Route::post('{digital_asset}/publish', [DigitalAssetController::class, 'publish'])->name('publish')->middleware('permission:digital_assets.publish');
    Route::post('{digital_asset}/unpublish', [DigitalAssetController::class, 'unpublish'])->name('unpublish')->middleware('permission:digital_assets.unpublish');
    Route::post('{digital_asset}/archive', [DigitalAssetController::class, 'archive'])->name('archive')->middleware('permission:digital_assets.update');

    // OCR
    Route::post('{digital_asset}/ocr', [DigitalAssetController::class, 'runOcr'])->name('ocr')->middleware('permission:digital_assets.run_ocr');

    // Preview
    Route::get('{digital_asset}/preview', [DigitalAssetController::class, 'preview'])->name('preview')->middleware('permission:digital_assets.preview');
});
