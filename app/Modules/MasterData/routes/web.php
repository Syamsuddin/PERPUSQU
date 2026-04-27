<?php

use Illuminate\Support\Facades\Route;
use App\Modules\MasterData\Http\Controllers\AuthorController;
use App\Modules\MasterData\Http\Controllers\PublisherController;
use App\Modules\MasterData\Http\Controllers\LanguageController;
use App\Modules\MasterData\Http\Controllers\ClassificationController;
use App\Modules\MasterData\Http\Controllers\SubjectController;
use App\Modules\MasterData\Http\Controllers\CollectionTypeController;
use App\Modules\MasterData\Http\Controllers\RackLocationController;
use App\Modules\MasterData\Http\Controllers\FacultyController;
use App\Modules\MasterData\Http\Controllers\StudyProgramController;
use App\Modules\MasterData\Http\Controllers\ItemConditionController;

Route::middleware('auth')->prefix('admin/master-data')->name('admin.master-data.')->group(function () {

    // Authors
    Route::get('authors', [AuthorController::class, 'index'])->name('authors.index')->middleware('permission:authors.view');
    Route::get('authors/create', [AuthorController::class, 'create'])->name('authors.create')->middleware('permission:authors.create');
    Route::post('authors', [AuthorController::class, 'store'])->name('authors.store')->middleware('permission:authors.create');
    Route::get('authors/{author}/edit', [AuthorController::class, 'edit'])->name('authors.edit')->middleware('permission:authors.update');
    Route::put('authors/{author}', [AuthorController::class, 'update'])->name('authors.update')->middleware('permission:authors.update');
    Route::delete('authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy')->middleware('permission:authors.delete');

    // Publishers
    Route::get('publishers', [PublisherController::class, 'index'])->name('publishers.index')->middleware('permission:publishers.view');
    Route::get('publishers/create', [PublisherController::class, 'create'])->name('publishers.create')->middleware('permission:publishers.create');
    Route::post('publishers', [PublisherController::class, 'store'])->name('publishers.store')->middleware('permission:publishers.create');
    Route::get('publishers/{publisher}/edit', [PublisherController::class, 'edit'])->name('publishers.edit')->middleware('permission:publishers.update');
    Route::put('publishers/{publisher}', [PublisherController::class, 'update'])->name('publishers.update')->middleware('permission:publishers.update');
    Route::delete('publishers/{publisher}', [PublisherController::class, 'destroy'])->name('publishers.destroy')->middleware('permission:publishers.delete');

    // Languages
    Route::get('languages', [LanguageController::class, 'index'])->name('languages.index')->middleware('permission:languages.view');
    Route::get('languages/create', [LanguageController::class, 'create'])->name('languages.create')->middleware('permission:languages.create');
    Route::post('languages', [LanguageController::class, 'store'])->name('languages.store')->middleware('permission:languages.create');
    Route::get('languages/{language}/edit', [LanguageController::class, 'edit'])->name('languages.edit')->middleware('permission:languages.update');
    Route::put('languages/{language}', [LanguageController::class, 'update'])->name('languages.update')->middleware('permission:languages.update');
    Route::delete('languages/{language}', [LanguageController::class, 'destroy'])->name('languages.destroy')->middleware('permission:languages.delete');

    // Classifications
    Route::get('classifications', [ClassificationController::class, 'index'])->name('classifications.index')->middleware('permission:classifications.view');
    Route::get('classifications/create', [ClassificationController::class, 'create'])->name('classifications.create')->middleware('permission:classifications.create');
    Route::post('classifications', [ClassificationController::class, 'store'])->name('classifications.store')->middleware('permission:classifications.create');
    Route::get('classifications/{classification}/edit', [ClassificationController::class, 'edit'])->name('classifications.edit')->middleware('permission:classifications.update');
    Route::put('classifications/{classification}', [ClassificationController::class, 'update'])->name('classifications.update')->middleware('permission:classifications.update');
    Route::delete('classifications/{classification}', [ClassificationController::class, 'destroy'])->name('classifications.destroy')->middleware('permission:classifications.delete');

    // Subjects
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index')->middleware('permission:subjects.view');
    Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create')->middleware('permission:subjects.create');
    Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store')->middleware('permission:subjects.create');
    Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit')->middleware('permission:subjects.update');
    Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update')->middleware('permission:subjects.update');
    Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy')->middleware('permission:subjects.delete');

    // Collection Types
    Route::get('collection-types', [CollectionTypeController::class, 'index'])->name('collection-types.index')->middleware('permission:collection_types.view');
    Route::get('collection-types/create', [CollectionTypeController::class, 'create'])->name('collection-types.create')->middleware('permission:collection_types.create');
    Route::post('collection-types', [CollectionTypeController::class, 'store'])->name('collection-types.store')->middleware('permission:collection_types.create');
    Route::get('collection-types/{collectionType}/edit', [CollectionTypeController::class, 'edit'])->name('collection-types.edit')->middleware('permission:collection_types.update');
    Route::put('collection-types/{collectionType}', [CollectionTypeController::class, 'update'])->name('collection-types.update')->middleware('permission:collection_types.update');
    Route::delete('collection-types/{collectionType}', [CollectionTypeController::class, 'destroy'])->name('collection-types.destroy')->middleware('permission:collection_types.delete');

    // Rack Locations
    Route::get('rack-locations', [RackLocationController::class, 'index'])->name('rack-locations.index')->middleware('permission:rack_locations.view');
    Route::get('rack-locations/create', [RackLocationController::class, 'create'])->name('rack-locations.create')->middleware('permission:rack_locations.create');
    Route::post('rack-locations', [RackLocationController::class, 'store'])->name('rack-locations.store')->middleware('permission:rack_locations.create');
    Route::get('rack-locations/{rackLocation}/edit', [RackLocationController::class, 'edit'])->name('rack-locations.edit')->middleware('permission:rack_locations.update');
    Route::put('rack-locations/{rackLocation}', [RackLocationController::class, 'update'])->name('rack-locations.update')->middleware('permission:rack_locations.update');
    Route::delete('rack-locations/{rackLocation}', [RackLocationController::class, 'destroy'])->name('rack-locations.destroy')->middleware('permission:rack_locations.delete');

    // Faculties
    Route::get('faculties', [FacultyController::class, 'index'])->name('faculties.index')->middleware('permission:faculties.view');
    Route::get('faculties/create', [FacultyController::class, 'create'])->name('faculties.create')->middleware('permission:faculties.create');
    Route::post('faculties', [FacultyController::class, 'store'])->name('faculties.store')->middleware('permission:faculties.create');
    Route::get('faculties/{faculty}/edit', [FacultyController::class, 'edit'])->name('faculties.edit')->middleware('permission:faculties.update');
    Route::put('faculties/{faculty}', [FacultyController::class, 'update'])->name('faculties.update')->middleware('permission:faculties.update');
    Route::delete('faculties/{faculty}', [FacultyController::class, 'destroy'])->name('faculties.destroy')->middleware('permission:faculties.delete');

    // Study Programs
    Route::get('study-programs', [StudyProgramController::class, 'index'])->name('study-programs.index')->middleware('permission:study_programs.view');
    Route::get('study-programs/create', [StudyProgramController::class, 'create'])->name('study-programs.create')->middleware('permission:study_programs.create');
    Route::post('study-programs', [StudyProgramController::class, 'store'])->name('study-programs.store')->middleware('permission:study_programs.create');
    Route::get('study-programs/{studyProgram}/edit', [StudyProgramController::class, 'edit'])->name('study-programs.edit')->middleware('permission:study_programs.update');
    Route::put('study-programs/{studyProgram}', [StudyProgramController::class, 'update'])->name('study-programs.update')->middleware('permission:study_programs.update');
    Route::delete('study-programs/{studyProgram}', [StudyProgramController::class, 'destroy'])->name('study-programs.destroy')->middleware('permission:study_programs.delete');

    // Item Conditions
    Route::get('item-conditions', [ItemConditionController::class, 'index'])->name('item-conditions.index')->middleware('permission:item_conditions.view');
    Route::get('item-conditions/create', [ItemConditionController::class, 'create'])->name('item-conditions.create')->middleware('permission:item_conditions.create');
    Route::post('item-conditions', [ItemConditionController::class, 'store'])->name('item-conditions.store')->middleware('permission:item_conditions.create');
    Route::get('item-conditions/{itemCondition}/edit', [ItemConditionController::class, 'edit'])->name('item-conditions.edit')->middleware('permission:item_conditions.update');
    Route::put('item-conditions/{itemCondition}', [ItemConditionController::class, 'update'])->name('item-conditions.update')->middleware('permission:item_conditions.update');
    Route::delete('item-conditions/{itemCondition}', [ItemConditionController::class, 'destroy'])->name('item-conditions.destroy')->middleware('permission:item_conditions.delete');
});
