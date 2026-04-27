# 23_OCR_AND_DIGITAL_PROCESSING.md

## 1. Nama Dokumen

OCR and Digital Processing Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi OCR, ekstraksi teks, dan pemrosesan aset digital

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan pipeline OCR, text extraction, preprocessing file PDF, status proses, queue orchestration, hasil ekstraksi, integrasi ke search, integrasi ke storage, dan pengamanan proses digital

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan spesifikasi resmi pemrosesan aset digital pada PERPUSQU, terutama untuk file PDF yang menjadi fokus fase 1. Dokumen ini menjadi acuan wajib agar alur unggah PDF, validasi file, penentuan kelayakan OCR, proses ekstraksi teks, penyimpanan hasil, reindex pencarian, monitoring, retry, dan error handling berjalan konsisten dengan seluruh blueprint sebelumnya.

## 3. Dokumen Acuan Wajib

Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md
5. 05_SRS.md
6. 06_USE_CASE.md
7. 07_ROLE_PERMISSION_MATRIX.md
8. 08_MENU_MAP.md
9. 09_ROUTE_MAP.md
10. 10_VIEW_MAP.md
11. 11_CONTROLLER_MAP.md
12. 12_SERVICE_LAYER.md
13. 13_MODEL_MAP.md
14. 14_SCHEMA.sql
15. 15_SEED.sql
16. 16_VALIDATION_RULES.md
17. 17_WORKFLOW_STATE_MACHINE.md
18. 18_UI_UX_STANDARD.md
19. 19_OPAC_UX_FLOW.md
20. 20_API_CONTRACT.md
21. 21_SEARCH_INDEXING_SPEC.md
22. 22_STORAGE_FILE_POLICY.md

Aturan wajib:

1. Aset digital utama fase 1 adalah PDF.
2. File utama aset digital tetap disimpan di private storage.
3. OCR tidak boleh dijalankan langsung dari controller.
4. Semua proses OCR harus melewati service dan job yang sah.
5. Hasil OCR disimpan sebagai teks, bukan mengganti file utama.
6. OCR hanya memperkaya pencarian, bukan mengganti metadata katalog.
7. OCR dan processing tidak boleh membocorkan file privat ke publik.
8. Reindex wajib mengikuti hasil proses OCR yang sah.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. Tujuan OCR dan digital processing
2. Jenis file yang diproses
3. Definisi aset digital fase 1
4. Arsitektur pemrosesan digital
5. Kelayakan OCR
6. Tahapan pipeline OCR
7. Status proses OCR
8. Penyimpanan hasil OCR
9. Integrasi OCR ke search indexing
10. Aturan visibilitas teks OCR
11. Aturan retry dan failure handling
12. Aturan queue dan worker
13. Aturan keamanan proses
14. Aturan housekeeping file sementara
15. Aturan monitoring
16. Aturan testing

## 5. Definisi Umum

### 5.1 Aset Digital

Aset digital adalah file digital yang terhubung ke BibliographicRecord dan disimpan sebagai DigitalAsset.

### 5.2 OCR

OCR adalah proses ekstraksi teks dari file PDF, terutama PDF hasil scan atau PDF yang membutuhkan pembacaan gambar menjadi teks.

### 5.3 Text Extraction

Text extraction adalah proses pengambilan teks dari file digital. Pada PERPUSQU, istilah OCR phase mencakup:

1. ekstraksi teks langsung dari PDF bila memungkinkan
2. OCR berbasis raster image bila PDF tidak memiliki teks yang cukup
3. penyatuan hasil teks akhir ke OcrText

### 5.4 Processing

Processing adalah seluruh rangkaian pasca unggah file, yang meliputi:

1. validasi teknis file
2. penyimpanan metadata
3. penentuan kelayakan OCR
4. dispatch queue OCR
5. penyimpanan hasil OCR
6. reindex pencarian
7. monitoring status proses

## 6. Tujuan OCR dan Digital Processing

Tujuan utama OCR dan processing pada PERPUSQU adalah:

1. Meningkatkan kualitas pencarian koleksi digital
2. Memungkinkan isi dokumen terpilih berkontribusi pada discovery
3. Menjaga metadata file digital tetap tertib
4. Memisahkan file utama, hasil teks, dan status proses secara jelas
5. Menyediakan fondasi pemrosesan digital yang aman dan dapat diulang

## 7. Prinsip Umum OCR dan Processing

Prinsip resmi OCR dan digital processing adalah:

1. File utama adalah sumber primer.
2. Metadata katalog tetap menjadi sumber discovery utama.
3. OCR hanya menambah nilai pencarian.
4. OCR bersifat asynchronous secara default.
5. Proses OCR tidak boleh mengganggu transaksi unggah utama.
6. Kegagalan OCR tidak boleh membuat aset digital utama gagal eksis bila unggah file sudah sah.
7. Hasil OCR harus dapat ditelusuri statusnya.
8. Hasil OCR harus dapat di-retry.
9. File sementara harus dibersihkan.
10. Proses harus tunduk pada aturan akses dan visibilitas publik.

## 8. Jenis File yang Diproses pada Fase 1

Fase 1 hanya memfokuskan digital processing pada:

1. PDF text based
2. PDF scanned
3. PDF mixed content

Jenis file yang belum menjadi fokus fase 1:

1. ePub
2. docx
3. xlsx
4. pptx
5. image standalone sebagai aset digital utama
6. audio
7. video

Catatan:

1. Logo dan cover image tidak masuk OCR pipeline.
2. PDF tetap menjadi satu-satunya aset digital utama yang diproses OCR pada fase 1.

## 9. Model dan Tabel yang Terlibat

Entity utama yang terlibat:

1. DigitalAsset
2. OcrText
3. BibliographicRecord
4. ActivityLog, secara audit
5. QueueMonitorSnapshot, opsional
6. ReportExportHistory, tidak terkait langsung

Field penting dari `digital_assets`:

1. id
2. bibliographic_record_id
3. file_path
4. mime_type
5. file_extension
6. publication_status
7. is_public
8. is_embargoed
9. embargo_until
10. ocr_status
11. index_status
12. uploaded_by
13. uploaded_at

Field penting dari `ocr_texts`:

1. digital_asset_id
2. source_type
3. extracted_text
4. extraction_status
5. extracted_at
6. error_message

## 10. Service dan Controller yang Wajib Terlibat

Service utama:

1. DigitalAssetUploadService
2. DigitalAssetService
3. DigitalAssetAccessService
4. OcrProcessingService
5. SearchIndexService
6. AssetStreamingService
7. AuditLogService
8. QueueMonitorService

Controller utama:

1. DigitalAssetController
2. AssetAccessController
3. AssetPreviewController
4. QueueMonitorController

Method terkait:

1. `DigitalAssetController@store`
2. `DigitalAssetController@update`
3. `DigitalAssetController@runOcr`
4. `DigitalAssetController@reindex`
5. `AssetPreviewController@show`
6. `AssetAccessController@privateShow`
7. `AssetAccessController@privateDownload`

## 11. Event Proses Digital Resmi

Event utama digital processing:

1. upload_asset
2. validate_file
3. store_file
4. record_metadata
5. dispatch_post_upload_jobs
6. request_ocr
7. start_ocr
8. complete_ocr_success
9. complete_ocr_failed
10. retry_ocr
11. mark_reindex_needed
12. queue_reindex
13. complete_index_success
14. complete_index_failed

## 12. Status OCR Resmi

Sesuai blueprint sebelumnya, status OCR resmi adalah:

1. not_requested
2. queued
3. processing
4. success
5. failed

Sumber utama:

1. `digital_assets.ocr_status`
2. `ocr_texts.extraction_status`

Aturan:

1. `digital_assets.ocr_status` adalah status operasional aset
2. `ocr_texts.extraction_status` adalah status hasil teks
3. Keduanya harus sinkron secara logis

## 13. Status Index Resmi

Status index resmi:

1. pending
2. queued
3. processing
4. indexed
5. failed

Sumber utama:

1. `digital_assets.index_status`

## 14. Kriteria Kelayakan OCR

Aset digital dinyatakan layak OCR bila memenuhi seluruh atau sebagian besar syarat berikut:

1. file ada di storage
2. mime_type adalah PDF
3. publication status tidak membatalkan kebutuhan pemrosesan internal
4. file size masih dalam batas operasional yang diterima sistem
5. file tidak rusak
6. asset belum sedang diproses OCR aktif
7. role dan aksi pengguna mengizinkan dispatch OCR

Kelayakan OCR tidak tergantung pada visibilitas publik. Asset privat tetap boleh di-OCR untuk kebutuhan internal, tetapi hasil OCR tidak otomatis masuk ke publik.

## 15. Kategori PDF menurut Processing

### 15.1 PDF Text Based

Karakter:

1. PDF sudah memiliki text layer
2. teks dapat diekstrak langsung tanpa raster OCR penuh

Perlakuan:

1. sistem boleh mencoba ekstraksi teks langsung terlebih dahulu
2. hasil dapat masuk OcrText dengan `source_type = extracted_text`

### 15.2 PDF Scanned

Karakter:

1. PDF berupa hasil scan gambar
2. teks tidak cukup tersedia

Perlakuan:

1. sistem meraster halaman ke image sementara
2. sistem menjalankan OCR per halaman atau batch
3. hasil disatukan menjadi extracted_text final

### 15.3 PDF Mixed

Karakter:

1. sebagian halaman text based
2. sebagian halaman scanned

Perlakuan:

1. implementasi fase 1 boleh memakai pendekatan sederhana
2. jika ekstraksi langsung kurang memadai, lakukan OCR penuh atau OCR fallback
3. strategi final ditentukan di OcrProcessingService

## 16. Strategi Processing Fase 1

Strategi praktis fase 1:

1. unggah file PDF ke private storage
2. simpan metadata file ke database
3. tentukan apakah OCR diaktifkan dari system settings
4. bila aktif dan asset layak, dispatch job OCR
5. worker membaca file dari private storage
6. worker mencoba ekstraksi teks
7. jika perlu, worker menjalankan raster OCR
8. worker menyimpan hasil ke tabel OcrText
9. worker menandai ocr_status
10. worker memicu reindex bila hasil OCR sah
11. worker membersihkan temp files

## 17. Pipeline Utama Pasca Unggah

### 17.1 Tahap 1, Upload dan Simpan File

Dilakukan oleh:

1. DigitalAssetUploadService

Langkah:

1. validasi request dan file
2. simpan file ke private_assets
3. hitung checksum
4. simpan metadata DigitalAsset
5. tandai index_status = pending
6. tandai ocr_status = not_requested
7. catat audit log unggah

### 17.2 Tahap 2, Keputusan Post Upload

Dilakukan oleh:

1. DigitalAssetUploadService
2. OcrProcessingService
3. SearchIndexService

Langkah:

1. evaluasi apakah OCR diaktifkan
2. evaluasi apakah asset layak OCR
3. dispatch OCR bila sesuai
4. dispatch reindex bila metadata perlu masuk index
5. set status awal queue

### 17.3 Tahap 3, OCR Processing

Dilakukan oleh:

1. OcrProcessingService
2. ProcessDigitalAssetOcrJob

Langkah:

1. update ocr_status ke queued
2. worker mengambil job
3. update ocr_status ke processing
4. baca file dari private storage
5. ekstraksi teks langsung atau OCR raster
6. simpan hasil ke OcrText
7. update ocr_status ke success atau failed
8. trigger reindex bila perlu

### 17.4 Tahap 4, Search Reindex

Dilakukan oleh:

1. SearchIndexService
2. ReindexBibliographicRecordJob

Langkah:

1. tandai index_status asset atau record terkait
2. queue reindex
3. proses indexing
4. update index_status

## 18. Detail Pipeline OCR

### 18.1 Input OCR

Input utama:

1. DigitalAsset id
2. file_path
3. mime_type
4. file_extension
5. bibliographic_record_id
6. access context, untuk menentukan penggunaan hasil pada publik atau internal

### 18.2 Resolusi File

OcrProcessingService harus:

1. memverifikasi DigitalAsset ada
2. memverifikasi file_path valid
3. memverifikasi file dapat dibaca dari private storage
4. menolak proses bila file tidak ditemukan

### 18.3 Text Extraction Step

Fase 1 boleh memakai strategi berikut:

1. coba ekstraksi teks langsung dari PDF terlebih dahulu
2. jika hasil kosong atau terlalu rendah, fallback ke OCR raster
3. bila gagal total, tandai failed

### 18.4 Raster OCR Step

Untuk scanned PDF:

1. konversi halaman ke image sementara
2. proses OCR per halaman
3. kumpulkan hasil teks
4. gabungkan ke satu extracted_text final

### 18.5 Post Processing Text

Setelah teks didapat:

1. normalisasi whitespace
2. hapus noise dasar yang tidak perlu
3. simpan hasil final sebagai extracted_text
4. jangan lakukan summarization isi dokumen di fase ini
5. jangan ubah makna isi secara semantik

## 19. Strategi Source Type OcrText

Kolom `source_type` pada `ocr_texts` memakai:

1. `ocr`
2. `extracted_text`
3. `manual`

Makna:

1. `ocr` untuk hasil raster OCR
2. `extracted_text` untuk teks langsung dari PDF
3. `manual` untuk koreksi manual fase berikutnya bila diaktifkan

Fase 1 fokus pada:

1. `ocr`
2. `extracted_text`

## 20. Penyimpanan Hasil OCR

Hasil OCR disimpan pada tabel `ocr_texts`.

Aturan:

1. satu DigitalAsset memiliki maksimal satu OcrText aktif utama pada fase 1
2. extracted_text disimpan sebagai LONGTEXT
3. extraction_status harus sinkron dengan hasil proses
4. error_message hanya untuk keperluan internal
5. extracted_at diisi saat sukses

Aturan tambahan:

1. hasil OCR tidak disimpan sebagai file PDF baru
2. hasil OCR tidak mengganti file utama
3. hasil OCR tidak wajib ditampilkan ke user publik

## 21. Aturan Update OcrText

Jika OCR dijalankan ulang:

1. hasil baru menggantikan extracted_text lama
2. extraction_status diperbarui
3. extracted_at diperbarui
4. error_message dibersihkan saat sukses
5. reindex dipicu ulang bila perlu

## 22. Aturan Visibilitas Hasil OCR

Hasil OCR memiliki 2 lapisan pemanfaatan:

### 22.1 Pemanfaatan Internal

Boleh untuk:

1. pencarian internal
2. indexing internal
3. monitoring pemrosesan
4. validasi kualitas OCR

### 22.2 Pemanfaatan Publik

Hanya boleh bila:

1. bibliographic record publik
2. digital asset publik
3. asset tidak embargo aktif untuk preview publik, bila kebijakan mengharuskan
4. access rules mengizinkan
5. SearchIndexService menyatakan teks aman masuk dokumen index publik

Aturan:

1. OCR text privat tidak boleh bocor ke index publik
2. error_message tidak boleh tampil publik
3. extracted_text penuh tidak wajib pernah ditampilkan ke UI publik

## 23. Aturan Integrasi OCR ke Search

Integrasi OCR ke search wajib mengacu ke 21_SEARCH_INDEXING_SPEC.md.

Aturan:

1. OCR hanya menambah field `ocr_public_text`
2. bobot OCR lebih rendah dari title, author, subject, ISBN
3. OCR hanya masuk index publik bila rule visibilitas lulus
4. OCR dapat tetap dipakai untuk search internal secara lebih luas bila diizinkan

Trigger reindex setelah OCR sukses:

1. update ocr_status = success
2. save OcrText
3. mark related record reindex needed
4. queue reindex

## 24. Aturan Asset Privasi dan OCR

Aset privat tetap dapat di-OCR untuk kebutuhan internal, tetapi:

1. hasil OCR tidak otomatis masuk OPAC publik
2. hasil OCR tidak boleh tampil di preview publik
3. SearchIndexService harus membedakan public OCR contribution dan internal OCR contribution

## 25. Aturan Embargo dan OCR

Asset embargoed:

1. tetap boleh diproses OCR secara internal
2. hasil OCR tidak boleh dipakai untuk preview publik selama embargo aktif
3. kontribusi OCR ke index publik mengikuti rule visibilitas yang berlaku

## 26. Aturan Failure Handling OCR

Jika OCR gagal:

1. digital_assets.ocr_status = failed
2. ocr_texts.extraction_status = failed, bila record OCR sudah ada
3. error_message dicatat di OcrText atau log teknis
4. asset digital utama tetap valid
5. record tetap bisa dicari lewat metadata katalog
6. retry diperbolehkan oleh role berwenang

## 27. Aturan Retry OCR

Retry OCR dilakukan bila:

1. status failed
2. file masih valid
3. user memiliki permission `digital_assets.run_ocr`
4. asset belum dalam kondisi diproses aktif

Saat retry:

1. status berubah ke queued
2. job baru dibuat
3. audit log dicatat
4. hasil baru menimpa hasil lama bila sukses

## 28. Aturan Queue OCR

OCR default dijalankan async melalui queue.

### 28.1 Queue Job Resmi

Job yang direkomendasikan:

1. `ProcessDigitalAssetOcrJob`

### 28.2 Input Job

Input minimum:

1. asset_id
2. request context minimal bila diperlukan

### 28.3 Aturan Job

1. job harus idempotent sebisa mungkin
2. job harus memeriksa ulang file existence
3. job harus memeriksa ulang status asset
4. job harus update status processing dengan aman
5. job harus menangani retry dengan benar

## 29. Aturan Concurrency

Untuk mencegah proses ganda:

1. satu DigitalAsset tidak boleh diproses OCR paralel secara liar
2. OcrProcessingService harus memeriksa status current
3. locking ringan atau guard status perlu diterapkan
4. duplicate dispatch harus ditekan

## 30. Aturan Processing Time

Karena OCR bisa berat:

1. proses harus lewat queue
2. UI tidak menunggu proses selesai secara blocking
3. status job ditampilkan di halaman asset
4. timeout worker harus disesuaikan dengan ukuran file yang realistis

## 31. Aturan Temp File OCR

OCR raster dapat memerlukan temp file.

Aturan:

1. temp file disimpan di `local_temp` atau lokasi temp yang sah
2. temp file tidak menjadi file permanen
3. temp file tidak diindeks
4. temp file dibersihkan setelah proses selesai atau gagal
5. temp file tidak boleh diakses publik

Contoh lokasi:

1. `temp/ocr/asset-{asset_id}/{job_uuid}/`

## 32. Aturan Housekeeping OCR

Housekeeping OCR wajib mencakup:

1. penghapusan temp image
2. penghapusan temp extraction artifacts
3. penghapusan file kerja yang tidak lagi dipakai
4. logging ringkas untuk failure cleanup bila perlu

## 33. Aturan Security Processing

Keamanan OCR dan digital processing wajib mencakup:

1. file sumber tetap di private storage
2. worker membaca file via storage abstraction
3. temp file tidak diekspos
4. error teknis tidak tampil ke pengguna umum
5. OCR job tidak boleh mengeksekusi file asing
6. hanya PDF yang diproses
7. access key storage tidak tertanam di kode

## 34. Aturan Quality Control Hasil OCR

Quality control minimal:

1. extracted_text tidak null saat success
2. extracted_text tidak kosong total, kecuali kasus tertentu yang dibolehkan
3. extracted_at terisi saat success
4. file sumber masih relevan
5. bila hasil sangat minim, implementasi boleh tetap menandai success dengan catatan kualitas rendah pada fase berikutnya, tetapi fase 1 cukup sukses atau gagal secara sederhana

## 35. Aturan Logging dan Audit

Audit wajib dicatat untuk:

1. dispatch OCR
2. retry OCR
3. perubahan status OCR penting bila sensitif
4. update access rule yang memengaruhi visibilitas hasil OCR ke search publik

Log teknis wajib dicatat untuk:

1. gagal baca file
2. gagal ekstraksi
3. gagal update status
4. gagal cleanup temp

Catatan:

1. Audit log fokus pada aksi pengguna dan perubahan penting
2. Log teknis fokus pada diagnosis proses

## 36. Aturan Monitoring UI

Halaman detail aset digital wajib menampilkan ringkasan berikut:

1. OCR status
2. Index status
3. waktu proses terakhir bila ada
4. tombol Run OCR bila diizinkan
5. tombol Reindex bila diizinkan
6. pesan error ringkas yang aman untuk internal bila perlu

Yang tidak perlu tampil ke UI publik:

1. temp path
2. stack trace
3. error teknis mentah
4. checksum
5. nama bucket

## 37. Aturan UI Berdasarkan Status OCR

### 37.1 not_requested

UI:

1. tampilkan badge belum diproses
2. tampilkan tombol Run OCR bila diizinkan

### 37.2 queued

UI:

1. tampilkan badge antre
2. tombol Run OCR dapat disembunyikan atau dinonaktifkan
3. tampilkan pesan proses menunggu antrean

### 37.3 processing

UI:

1. tampilkan badge diproses
2. tombol retry tidak tampil
3. tombol run ulang tidak aktif

### 37.4 success

UI:

1. tampilkan badge sukses
2. tampilkan waktu ekstraksi terakhir bila ada
3. tombol Run OCR ulang dapat tampil sebagai proses ulang bila diizinkan

### 37.5 failed

UI:

1. tampilkan badge gagal
2. tampilkan pesan ringkas aman
3. tampilkan tombol Retry OCR bila diizinkan

## 38. Aturan Reindex Pasca OCR

Setelah OCR sukses:

1. sistem menilai apakah hasil OCR boleh dipakai untuk index publik
2. sistem menandai record terkait perlu reindex
3. reindex dijalankan async
4. index_status diperbarui sesuai proses

Jika OCR gagal:

1. reindex dari OCR tidak dijalankan
2. metadata katalog tetap dapat diindex seperti biasa

## 39. Aturan Pemrosesan tanpa OCR

Untuk PDF text based:

1. sistem boleh langsung ekstrak teks
2. bila ekstraksi cukup, tidak wajib raster OCR penuh
3. source_type diisi `extracted_text`
4. alur status OCR tetap dipakai agar konsisten

## 40. Aturan Fallback Processing

Fallback yang diizinkan:

1. ekstraksi teks langsung gagal, lanjut OCR raster
2. OCR raster gagal, tandai failed
3. index tetap berjalan dari metadata katalog tanpa OCR

Fallback yang tidak diizinkan:

1. menampilkan teks lama sebagai hasil OCR baru tanpa jejak
2. membiarkan status sukses bila tidak ada hasil yang valid

## 41. Aturan Asset Update dan OCR

Jika file digital asset diganti:

1. checksum berubah
2. file_path bisa berubah
3. ocr_status harus direset sesuai kebijakan
4. hasil OcrText lama harus dianggap obsolete
5. OCR baru perlu dijadwalkan ulang bila diaktifkan
6. reindex perlu dipicu ulang

Rekomendasi fase 1:

1. saat replacement file, reset ocr_status ke not_requested atau queued sesuai setting
2. extracted_text lama dapat diganti saat OCR baru sukses

## 42. Aturan Asset Publish dan OCR

Jika asset berubah dari draft ke published:

1. hasil OCR internal yang sudah ada dapat dipakai untuk publik hanya bila rule visibilitas lulus
2. SearchIndexService harus menilai ulang dokumen index

Jika asset berubah dari published ke unpublished:

1. kontribusi OCR ke search publik harus ditarik kembali
2. reindex wajib dipicu

## 43. Aturan Access Rule Update dan OCR

Jika access rule berubah:

1. file utama tidak berubah
2. hasil OCR tidak berubah
3. tetapi kontribusi hasil OCR ke publik bisa berubah
4. karena itu reindex wajib dipicu bila perubahan akses memengaruhi visibilitas

## 44. Aturan Kinerja

Fase 1 harus realistis terhadap sumber daya server.

Aturan:

1. OCR tidak dijalankan sinkron pada request web normal
2. chunk atau page based OCR boleh dipakai secara internal
3. file sangat besar dapat diproses lebih lama tanpa memblok UI
4. concurrency worker harus disesuaikan dengan kapasitas VPS

## 45. Aturan Konfigurasi yang Direkomendasikan

Konfigurasi utama yang direkomendasikan:

1. `library.asset.ocr_enabled`
2. `library.asset.max_upload_size_mb`
3. `library.asset.allowed_mime_types`
4. `library.asset.allowed_extensions`

Konfigurasi tambahan yang direkomendasikan untuk implementasi:

1. `ocr.default_mode = queue`
2. `ocr.max_pages_per_batch`, opsional
3. `ocr.temp_retention_hours`, opsional
4. `ocr.retry_attempts`, opsional
5. `ocr.minimum_text_threshold`, opsional

Catatan:

1. Nilai detail dapat diatur di `.env` dan service config
2. fase 1 cukup menetapkan konsep, bukan angka final terlalu rinci

## 46. Struktur Kelas yang Direkomendasikan

Struktur yang direkomendasikan:

```text
app/
  Modules/
    DigitalRepository/
      Services/
        OcrProcessingService.php
      Jobs/
        ProcessDigitalAssetOcrJob.php
      Support/
        PdfTextExtractor.php
        PdfRasterizer.php
        OcrTextNormalizer.php
````

Aturan:

1. implementasi support class boleh berbeda
2. tanggung jawab harus tetap terpisah
3. controller tidak boleh memuat logika OCR langsung

## 47. Kontrak Method Service yang Direkomendasikan

### 47.1 OcrProcessingService

Method yang direkomendasikan:

1. `dispatchOcr(DigitalAsset $asset): void`
2. `runOcrNow(DigitalAsset $asset): OcrResult`
3. `storeOcrResult(DigitalAsset $asset, OcrResult $result): void`
4. `canProcessOcr(DigitalAsset $asset): bool`
5. `resetOcrStateForReplacement(DigitalAsset $asset): void`

### 47.2 SearchIndexService

Method terkait:

1. `dispatchReindexRecord(BibliographicRecord $record): void`
2. `reindexRecord(BibliographicRecord $record): void`
3. `removeRecordFromIndex(BibliographicRecord $record): void`

## 48. Kontrak OcrResult yang Direkomendasikan

Object hasil OCR yang direkomendasikan minimal memuat:

1. success
2. source_type
3. extracted_text
4. error_message
5. page_count, opsional
6. text_length, opsional

Contoh bentuk konseptual:

```json
{
  "success": true,
  "source_type": "ocr",
  "extracted_text": "isi teks hasil ekstraksi",
  "error_message": null
}
```

## 49. Mapping Proses ke Route dan API

### 49.1 Web Route

1. `admin.digital.assets.ocr.run`
2. `admin.digital.assets.reindex`
3. `admin.digital.assets.preview`

### 49.2 Internal API

1. `POST /api/internal/v1/digital-assets/{asset}/ocr`
2. `POST /api/internal/v1/digital-assets/{asset}/reindex`

Aturan:

1. route web dan API sama sama memakai service yang sama
2. response hanya berbeda format
3. aturan bisnis tidak boleh berbeda

## 50. Anti Pattern yang Dilarang

Implementasi OCR dan digital processing tidak boleh:

1. memproses PDF langsung di controller
2. menyimpan isi PDF di database blob
3. menyimpan file hasil OCR sebagai file utama pengganti
4. membocorkan temp file ke publik
5. mengindeks OCR text privat ke search publik
6. menandai success bila proses gagal
7. mengabaikan cleanup temp file
8. menjalankan OCR blocking panjang di request web normal
9. membangun payload search index langsung di controller OCR

## 51. Skenario Proses Utama

### 51.1 Skenario A, Upload PDF dan OCR Berhasil

1. Operator unggah PDF
2. File tersimpan di private_assets
3. Metadata DigitalAsset tersimpan
4. ocr_status = not_requested
5. sistem memutuskan OCR aktif
6. dispatch OCR
7. ocr_status = queued
8. worker proses file
9. ekstraksi berhasil
10. OcrText tersimpan
11. ocr_status = success
12. reindex dijalankan
13. index diperbarui

### 51.2 Skenario B, Upload PDF tetapi OCR Gagal

1. Operator unggah PDF
2. File tersimpan
3. Metadata tersimpan
4. OCR dijadwalkan
5. worker gagal ekstraksi
6. ocr_status = failed
7. OcrText error atau log teknis tercatat
8. metadata katalog tetap tersedia
9. operator dapat retry

### 51.3 Skenario C, Asset Privat Berhasil OCR

1. Asset internal diunggah
2. OCR sukses
3. hasil OCR tersimpan
4. hasil bisa dipakai internal
5. hasil tidak masuk search publik bila rule tidak mengizinkan

### 51.4 Skenario D, Replace PDF

1. asset lama diganti file baru
2. metadata file baru disimpan
3. hasil OCR lama dianggap obsolete
4. status direset
5. OCR baru dijadwalkan
6. hasil baru menggantikan yang lama
7. reindex dijalankan ulang

## 52. Testing Requirement OCR dan Processing

Pengujian minimum wajib mencakup:

1. upload PDF valid berhasil
2. upload non PDF ditolak
3. file tidak ditemukan menyebabkan OCR gagal aman
4. dispatch OCR mengubah status ke queued
5. worker mengubah queued ke processing
6. proses sukses mengubah processing ke success
7. proses gagal mengubah processing ke failed
8. OcrText tersimpan saat success
9. OcrText tidak merusak asset utama saat gagal
10. retry OCR dari failed berhasil kembali ke queued
11. replacement file me-reset status OCR
12. OCR text privat tidak masuk OPAC publik
13. OCR text publik yang sah memicu reindex
14. temp OCR dibersihkan setelah sukses
15. temp OCR dibersihkan atau ditangani setelah gagal
16. reindex tidak jalan bila OCR gagal dan tidak ada trigger lain
17. UI badge OCR mengikuti state yang benar
18. API OCR dispatch mengembalikan response aman dan konsisten

## 53. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 25_REPORTING_SPEC.md
2. 28_SECURITY_POLICY.md
3. 29_AUDIT_LOG_SPEC.md
4. 30_ERROR_CODE.md
5. 31_TEST_PLAN.md
6. 32_TEST_SCENARIO.md
7. 33_DEPLOYMENT_GUIDE.md
8. 34_ENV_CONFIGURATION.md
9. 35_BACKUP_AND_RECOVERY.md
10. 38_TREE.md
11. 39_TRACEABILITY_MATRIX.md
12. 41_BACKEND_CHECKLIST.md
13. 42_FRONTEND_CHECKLIST.md
14. 45_SMOKE_TEST_CHECKLIST.md
15. 46_UAT_CHECKLIST.md

Aturan:

1. reporting spec dapat memasukkan statistik OCR dan index status bila diperlukan
2. security policy harus mempertegas keamanan private file dan temp file
3. audit log spec harus mencatat aksi OCR sensitif
4. test plan harus memuat uji state, retry, dan visibilitas OCR
5. deployment guide harus menyiapkan worker dan dependencies OCR

## 54. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. OCR hanya memproses aset digital yang sah
2. file utama tetap berada di private storage
3. service dan job OCR sudah jelas
4. status OCR konsisten dengan state machine
5. hasil OCR disimpan pada OcrText, bukan file utama
6. kontribusi OCR ke search tunduk pada visibilitas publik
7. retry, failure, dan cleanup sudah diatur
8. tidak ada kebocoran file privat atau temp file ke UI publik

## 55. Kesimpulan

Dokumen OCR and Digital Processing Specification ini menetapkan fondasi resmi pemrosesan aset digital PERPUSQU secara lengkap dan selaras dengan blueprint 01 sampai 22. Dokumen ini memastikan bahwa file PDF sebagai aset digital utama dapat diunggah, diproses, diekstraksi teksnya, dipantau statusnya, diulang prosesnya saat gagal, dan dipakai untuk memperkaya pencarian secara aman tanpa mengganggu integritas katalog maupun keamanan file privat. Semua implementasi OCR, ekstraksi teks, queue processing, dan integrasi search pada PERPUSQU wajib merujuk dokumen ini.

END OF 23_OCR_AND_DIGITAL_PROCESSING.md
