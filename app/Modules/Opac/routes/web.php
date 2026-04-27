<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Opac\Http\Controllers\OpacHomeController;
use App\Modules\Opac\Http\Controllers\OpacSearchController;
use App\Modules\Opac\Http\Controllers\OpacRecordController;
use App\Modules\Opac\Http\Controllers\PublicAssetPreviewController;

// OPAC Public Routes — NO auth required
Route::prefix('opac')->name('opac.')->group(function () {
    Route::get('/', [OpacHomeController::class, 'index'])->name('home');
    Route::get('search', [OpacSearchController::class, 'index'])->name('search');
    Route::get('record/{id}', [OpacRecordController::class, 'show'])->name('record.show');
    Route::get('asset/{id}/preview', [PublicAssetPreviewController::class, 'preview'])->name('asset.preview');

    // Static pages
    Route::view('about', 'modules.opac.about')->name('about');
    Route::view('help', 'modules.opac.help')->name('help');
});
