<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Requests\StoreBibliographicRecordRequest;
use App\Modules\Catalog\Http\Requests\UpdateBibliographicRecordRequest;
use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Services\BibliographicRecordService;
use App\Modules\Catalog\Services\CatalogPublicationService;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\Subject;
use Illuminate\Http\Request;

class BibliographicRecordController extends Controller
{
    public function __construct(
        protected BibliographicRecordService $service,
        protected CatalogPublicationService $publicationService,
    ) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        $collectionTypes = CollectionType::active()->orderBy('name')->get();
        $languages = Language::active()->orderBy('name')->get();

        return view('modules.catalog.records.index', compact('items', 'collectionTypes', 'languages'));
    }

    public function create()
    {
        return view('modules.catalog.records.create', $this->getFormData());
    }

    public function store(StoreBibliographicRecordRequest $request)
    {
        $this->authorize('create', BibliographicRecord::class);

        $this->service->create($request->validated());

        return redirect()->route('admin.catalog.records.index')->with('success', 'Katalog berhasil ditambahkan.');
    }

    public function show(BibliographicRecord $record)
    {
        $this->authorize('view', $record);

        $record = $this->service->findWithRelations($record->id);

        return view('modules.catalog.records.show', compact('record'));
    }

    public function edit(BibliographicRecord $record)
    {
        $record->load('authors', 'subjects');

        return view('modules.catalog.records.edit', array_merge(
            $this->getFormData(),
            ['record' => $record]
        ));
    }

    public function update(UpdateBibliographicRecordRequest $request, BibliographicRecord $record)
    {
        $this->authorize('update', $record);

        $this->service->update($record, $request->validated());

        return redirect()->route('admin.catalog.records.index')->with('success', 'Katalog berhasil diperbarui.');
    }

    public function destroy(BibliographicRecord $record)
    {
        $this->authorize('delete', $record);

        $this->service->delete($record);

        return redirect()->route('admin.catalog.records.index')->with('success', 'Katalog berhasil dihapus.');
    }

    public function publish(BibliographicRecord $record)
    {
        $this->authorize('publish', $record);

        try {
            $this->publicationService->publish($record);

            return redirect()->route('admin.catalog.records.show', $record)->with('success', 'Katalog berhasil dipublikasikan.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function unpublish(BibliographicRecord $record)
    {
        try {
            $this->publicationService->unpublish($record);

            return redirect()->route('admin.catalog.records.show', $record)->with('success', 'Katalog berhasil di-unpublish.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function archive(BibliographicRecord $record)
    {
        try {
            $this->publicationService->archive($record);

            return redirect()->route('admin.catalog.records.show', $record)->with('success', 'Katalog berhasil diarsipkan.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reactivate(BibliographicRecord $record)
    {
        try {
            $this->publicationService->reactivate($record);

            return redirect()->route('admin.catalog.records.show', $record)->with('success', 'Katalog berhasil direaktivasi.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Common form data for create/edit views
     */
    protected function getFormData(): array
    {
        return [
            'authors' => Author::active()->orderBy('name')->get(),
            'publishers' => Publisher::active()->orderBy('name')->get(),
            'languages' => Language::active()->orderBy('name')->get(),
            'classifications' => Classification::active()->orderBy('code')->get(),
            'collectionTypes' => CollectionType::active()->orderBy('name')->get(),
            'subjects' => Subject::active()->orderBy('name')->get(),
        ];
    }
}
