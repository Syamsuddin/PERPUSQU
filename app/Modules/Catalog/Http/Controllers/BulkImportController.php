<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Catalog\Exports\BukuCetakTemplateExport;
use App\Modules\Catalog\Imports\BukuCetakImport;
use App\Modules\Catalog\Services\BibliographicRecordService;
use App\Modules\Collection\Services\PhysicalItemService;
use App\Modules\MasterData\Models\CollectionType;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\Catalog\Models\Author;
use App\Modules\Catalog\Models\Subject;

class BulkImportController extends Controller
{
    public function __construct(
        protected BibliographicRecordService $catalogService,
        protected PhysicalItemService $itemService
    ) {}

    public function index()
    {
        return view('modules.catalog.bulk_import.index');
    }

    public function downloadTemplate()
    {
        return Excel::download(new BukuCetakTemplateExport, 'Template_Import_Buku_Cetak.xlsx');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('excel_file');
        $path = $file->store('temp_imports');

        $import = new BukuCetakImport();
        Excel::import($import, $path);
        
        $rows = $import->data;
        $validatedRows = [];
        $totalValid = 0;
        $totalError = 0;

        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty($row['judul_wajib']) && empty($row['pengarang_pisah_koma_wajib'])) continue;

            $errors = [];

            // Required fields validation
            if (empty($row['judul_wajib'])) $errors[] = 'Judul wajib diisi.';
            if (empty($row['pengarang_pisah_koma_wajib'])) $errors[] = 'Pengarang wajib diisi.';
            if (empty($row['jumlah_eksemplar_wajib']) || !is_numeric($row['jumlah_eksemplar_wajib']) || $row['jumlah_eksemplar_wajib'] < 1) {
                $errors[] = 'Jumlah eksemplar wajib diisi (minimal 1).';
            }

            // Status
            $isValid = count($errors) === 0;
            if ($isValid) $totalValid++;
            else $totalError++;

            $validatedRows[] = [
                'row_number' => $index + 2, // Excel row starts at 2 (1 is header)
                'data' => $row,
                'is_valid' => $isValid,
                'errors' => $errors
            ];
        }

        return view('modules.catalog.bulk_import.preview', compact('validatedRows', 'totalValid', 'totalError', 'path'));
    }

    public function process(Request $request)
    {
        $path = $request->input('file_path');
        if (!Storage::exists($path)) {
            return redirect()->route('admin.catalog.bulk-import.index')->with('error', 'File import kadaluarsa atau tidak ditemukan. Silakan upload ulang.');
        }

        $import = new BukuCetakImport();
        Excel::import($import, $path);
        $rows = $import->data;

        $defaultTypeId = CollectionType::where('name', 'Buku')->value('id');
        $successCount = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                if (empty($row['judul_wajib']) || empty($row['pengarang_pisah_koma_wajib']) || empty($row['jumlah_eksemplar_wajib'])) {
                    continue; // Skip invalid rows
                }

                // 1. Process Authors
                $authorNames = array_map('trim', explode(',', $row['pengarang_pisah_koma_wajib']));
                $authorIds = [];
                foreach ($authorNames as $aName) {
                    if (!empty($aName)) {
                        $author = Author::firstOrCreate(['name' => $aName]);
                        $authorIds[] = $author->id;
                    }
                }

                // 2. Process Subjects
                $subjectIds = [];
                if (!empty($row['subjek_pisah_koma'])) {
                    $subjectNames = array_map('trim', explode(',', $row['subjek_pisah_koma']));
                    foreach ($subjectNames as $sName) {
                        if (!empty($sName)) {
                            $subject = Subject::firstOrCreate(['name' => $sName]);
                            $subjectIds[] = $subject->id;
                        }
                    }
                }

                // 3. Resolve Relationships
                $publisherId = null;
                if (!empty($row['penerbit'])) {
                    $publisher = Publisher::firstOrCreate(['name' => trim($row['penerbit'])]);
                    $publisherId = $publisher->id;
                }

                $languageId = null;
                if (!empty($row['bahasa'])) {
                    $language = Language::where('name', 'like', '%' . trim($row['bahasa']) . '%')->first();
                    $languageId = $language?->id;
                }

                $classificationId = null;
                if (!empty($row['klasifikasi_ddc'])) {
                    $classification = Classification::where('code', trim($row['klasifikasi_ddc']))->first();
                    $classificationId = $classification?->id;
                }

                $rackId = null;
                if (!empty($row['lokasi_rak'])) {
                    $rack = RackLocation::where('name', trim($row['lokasi_rak']))->first();
                    $rackId = $rack?->id;
                }

                $conditionId = null;
                if (!empty($row['kondisi'])) {
                    $condition = ItemCondition::where('name', trim($row['kondisi']))->first();
                    $conditionId = $condition?->id;
                }

                // 4. Create Catalog Record
                $catalogData = [
                    'title' => trim($row['judul_wajib']),
                    'collection_type_id' => $defaultTypeId,
                    'publisher_id' => $publisherId,
                    'language_id' => $languageId,
                    'classification_id' => $classificationId,
                    'publication_year' => $row['tahun_terbit'] ?? null,
                    'isbn' => $row['isbn'] ?? null,
                    'keywords' => $row['kata_kunci'] ?? null,
                    'abstract' => $row['abstrak'] ?? null,
                    'is_public' => true,
                    'publication_status' => 'published',
                    'author_ids' => $authorIds,
                    'subject_ids' => $subjectIds,
                ];

                $record = $this->catalogService->create($catalogData);

                // 5. Create Physical Items
                $qty = (int) $row['jumlah_eksemplar_wajib'];
                for ($i = 0; $i < $qty; $i++) {
                    $this->itemService->create([
                        'bibliographic_record_id' => $record->id,
                        'rack_location_id' => $rackId,
                        'item_condition_id' => $conditionId,
                        'acquisition_date' => $row['tanggal_pengadaan_yyyy_mm_dd'] ?? date('Y-m-d'),
                        'notes' => 'Import massal dari Excel',
                    ]);
                }

                $successCount++;
            }

            DB::commit();
            Storage::delete($path); // Cleanup

            return redirect()->route('admin.catalog.records.index')->with('success', "Berhasil mengimpor $successCount judul buku cetak beserta eksemplarnya.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk import error: ' . $e->getMessage());
            return redirect()->route('admin.catalog.bulk-import.index')->with('error', 'Terjadi kesalahan sistem saat memproses data: ' . $e->getMessage());
        }
    }
}
