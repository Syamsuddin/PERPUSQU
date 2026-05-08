<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Http\Requests\QuickEntryPrintRequest;
use App\Modules\Catalog\Http\Requests\QuickEntryEbookRequest;
use App\Modules\Catalog\Services\BibliographicRecordService;
use App\Modules\Collection\Services\PhysicalItemService;
use App\Modules\DigitalRepository\Services\DigitalAssetUploadService;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuickEntryController extends Controller
{
    public function __construct(
        protected BibliographicRecordService $catalogService,
        protected PhysicalItemService $itemService,
        protected DigitalAssetUploadService $uploadService,
    ) {}

    /**
     * Landing: pilih jalur
     */
    public function index()
    {
        return view('modules.catalog.quick_entry.index');
    }

    // ─── JALUR 1: BUKU CETAK ───────────────────────────────────────────────

    public function createPrint()
    {
        return view('modules.catalog.quick_entry.print', $this->getBaseFormData() + [
            'rackLocations'  => RackLocation::active()->orderBy('name')->get(),
            'itemConditions' => ItemCondition::active()->orderBy('name')->get(),
            // Paksa collection_type = Buku (ID 1, code BUKU)
            'defaultTypeId'  => CollectionType::where('code', 'BUKU')->value('id') ?? 1,
        ]);
    }

    public function storePrint(QuickEntryPrintRequest $request)
    {
        $this->authorize('create', \App\Modules\Catalog\Models\BibliographicRecord::class);

        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            // 1. Buat Katalog
            $catalogData = $this->extractCatalogData($validated);
            $record = $this->catalogService->create($catalogData);

            // 2. Buat Item Fisik (sesuai jumlah eksemplar)
            $qty       = (int) ($validated['qty'] ?? 1);
            $prefix    = strtoupper(Str::random(3));
            $condId    = $validated['item_condition_id'] ?? null;
            $rackId    = $validated['rack_location_id'] ?? null;
            $acqDate   = $validated['acquisition_date'] ?? null;

            for ($i = 1; $i <= $qty; $i++) {
                $barcode = 'BK-' . $prefix . '-' . str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . now()->format('ymd');
                $this->itemService->create([
                    'bibliographic_record_id' => $record->id,
                    'rack_location_id'        => $rackId,
                    'item_condition_id'        => $condId,
                    'barcode'                  => $barcode,
                    'inventory_code'           => null,
                    'acquisition_date'         => $acqDate,
                    'item_status'              => 'available',
                    'notes'                    => null,
                ]);
            }

            return $record;
        });

        return redirect()
            ->route('admin.catalog.records.index')
            ->with('success', 'Koleksi buku cetak berhasil ditambahkan beserta item fisiknya.');
    }

    // ─── JALUR 2: E-BOOK ───────────────────────────────────────────────────

    public function createEbook()
    {
        return view('modules.catalog.quick_entry.ebook', $this->getBaseFormData() + [
            // Paksa collection_type = E-Book (code EBOOK)
            'defaultTypeId' => CollectionType::where('code', 'EBOOK')->value('id') ?? 9,
        ]);
    }

    public function storeEbook(QuickEntryEbookRequest $request)
    {
        $this->authorize('create', \App\Modules\Catalog\Models\BibliographicRecord::class);

        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            // 1. Buat Katalog
            $catalogData = $this->extractCatalogData($validated);
            $record = $this->catalogService->create($catalogData);

            // 2. Upload & simpan aset digital
            $this->uploadService->upload($request->file('file'), [
                'bibliographic_record_id' => $record->id,
                'asset_type'              => 'ebook',
                'title'                   => $validated['title'],
                'description'             => $validated['ebook_description'] ?? null,
                'publication_status'      => $validated['publication_status'] ?? 'draft',
                'is_public'               => $validated['is_public'] ?? false,
                'is_embargoed'            => false,
                'embargo_until'           => null,
            ]);

            return $record;
        });

        return redirect()
            ->route('admin.catalog.records.index')
            ->with('success', 'Koleksi E-Book beserta file digital berhasil ditambahkan.');
    }

    // ─── HELPERS ───────────────────────────────────────────────────────────

    protected function extractCatalogData(array $validated): array
    {
        return [
            'title'              => $validated['title'],
            'publisher_id'       => $validated['publisher_id'] ?? null,
            'language_id'        => $validated['language_id'] ?? null,
            'classification_id'  => $validated['classification_id'] ?? null,
            'collection_type_id' => $validated['collection_type_id'],
            'publication_year'   => $validated['publication_year'] ?? null,
            'isbn'               => $validated['isbn'] ?? null,
            'edition'            => $validated['edition'] ?? null,
            'keywords'           => $validated['keywords'] ?? null,
            'abstract'           => $validated['abstract'] ?? null,
            'cover'              => $validated['cover'] ?? null,
            'is_public'          => $validated['is_public'] ?? false,
            'author_ids'         => $validated['author_ids'],
            'subject_ids'        => $validated['subject_ids'] ?? [],
        ];
    }

    protected function getBaseFormData(): array
    {
        return [
            'authors'         => Author::active()->orderBy('name')->get(),
            'publishers'      => Publisher::active()->orderBy('name')->get(),
            'languages'       => Language::active()->orderBy('name')->get(),
            'classifications' => Classification::active()->orderBy('code')->get(),
            'collectionTypes' => CollectionType::active()->orderBy('name')->get(),
            'subjects'        => Subject::active()->orderBy('name')->get(),
        ];
    }
}
