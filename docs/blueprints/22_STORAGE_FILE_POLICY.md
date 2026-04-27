# 22_STORAGE_FILE_POLICY.md

## 1. Nama Dokumen

Storage File Policy Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint kebijakan penyimpanan file, objek digital, dan akses file

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan storage policy, struktur direktori atau object key, klasifikasi file publik dan privat, aturan upload, aturan akses, integrasi database, preview file, dan keamanan penyimpanan

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan kebijakan resmi penyimpanan file pada PERPUSQU agar seluruh objek file seperti logo institusi, cover katalog, dan aset digital perpustakaan disimpan secara tertib, aman, konsisten, dan siap diintegrasikan dengan Laravel, MySQL, object storage, PDF.js, OCR, dan search indexing. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar tidak ada file yang disimpan secara liar, tidak ada kebocoran path storage, tidak ada kebingungan antara file publik dan privat, dan tidak ada penyimpangan dari blueprint sebelumnya.

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

Aturan wajib:

1. Semua file disimpan di storage, bukan di database.
2. Database hanya menyimpan metadata file dan path atau object key.
3. File publik dan privat wajib dipisahkan secara jelas.
4. File privat tidak boleh dibuka lewat URL mentah.
5. Semua akses file digital harus melewati service yang sah.
6. Semua proses upload dan akses file harus konsisten dengan rule akses dan state machine.
7. Kebijakan storage wajib mendukung object storage S3 compatible atau MinIO sebagai target utama.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. Prinsip umum storage file
2. Jenis file yang dikelola
3. Strategi storage resmi
4. Struktur disk dan object key
5. Klasifikasi file publik dan privat
6. Aturan upload
7. Aturan file metadata
8. Aturan akses preview dan download
9. Aturan cover dan logo
10. Aturan aset digital PDF
11. Aturan integrasi dengan database
12. Aturan integrasi dengan OCR
13. Aturan integrasi dengan search indexing
14. Aturan keamanan file
15. Aturan lifecycle file
16. Aturan backup dan pemulihan dasar
17. Aturan testing storage

## 5. Prinsip Umum Storage File

Prinsip resmi storage file PERPUSQU adalah:

1. File harus disimpan di media storage yang terkelola.
2. Metadata file harus tersimpan di database.
3. Path file tidak boleh menjadi sumber otorisasi.
4. Otorisasi file ditentukan oleh service dan rule bisnis.
5. Object key harus stabil dan terstruktur.
6. Nama file asli boleh disimpan sebagai metadata, tetapi tidak menjadi nama penyimpanan utama.
7. File publik dan privat harus dibedakan sejak desain.
8. Preview file harus aman dan terkontrol.
9. File harus siap diproses OCR dan indexing bila relevan.
10. Penghapusan file harus selaras dengan status record dan audit.

## 6. Jenis File yang Dikelola

Jenis file resmi fase 1 yang dikelola PERPUSQU:

1. Logo institusi
2. Cover bibliographic record
3. Aset digital utama, fokus PDF
4. File turunan opsional di fase berikutnya, misalnya thumbnail atau preview derivative

Fase 1 tidak mewajibkan:

1. Penyimpanan video
2. Penyimpanan audio
3. Penyimpanan ePub
4. Penyimpanan zip
5. File executable
6. File office beragam sebagai aset publik utama

## 7. Strategi Storage Resmi

Strategi storage resmi adalah:

1. Gunakan object storage S3 compatible atau MinIO sebagai target utama
2. Laravel Storage abstraction menjadi lapisan akses file
3. Path yang disimpan di database berbentuk object key atau storage path, bukan URL final publik
4. URL file publik dibangun oleh service atau helper terkontrol
5. File privat di-stream melalui controller atau service yang sah

Rincian target:

1. Primary target storage: MinIO atau S3 compatible
2. Fallback pengembangan lokal: local storage Laravel
3. Desain path harus tetap konsisten di semua environment

## 8. Peran Disk Storage

Disk storage yang direkomendasikan:

1. `public_assets`
2. `private_assets`
3. `local_temp`

### 8.1 public_assets

Dipakai untuk:

1. Logo institusi
2. Cover record
3. File yang benar benar boleh diakses publik langsung atau semi publik

### 8.2 private_assets

Dipakai untuk:

1. Aset digital utama PDF
2. File yang perlu pemeriksaan akses
3. File yang berpotensi dipreview publik melalui controller terproteksi
4. File internal yang tidak boleh memiliki URL mentah terbuka

### 8.3 local_temp

Dipakai untuk:

1. file sementara upload
2. proses OCR sementara
3. file kerja internal sementara

Aturan:

1. `local_temp` tidak boleh menjadi sumber file permanen
2. file sementara harus dibersihkan teratur

## 9. Klasifikasi File Menurut Akses

### 9.1 File Publik

File publik adalah file yang aman ditampilkan langsung atau semi langsung ke publik.

Contoh:

1. Logo institusi
2. Cover bibliographic record
3. Asset derivative publik di fase berikutnya, bila diaktifkan

### 9.2 File Privat

File privat adalah file yang aksesnya harus diperiksa terlebih dahulu.

Contoh:

1. PDF aset digital
2. PDF karya ilmiah
3. PDF scan buku
4. PDF jurnal internal

Aturan:

1. Walau suatu aset digital boleh dipreview publik, file aslinya tetap direkomendasikan disimpan di private storage
2. Akses preview publik tetap harus melewati layer aplikasi

## 10. Aturan Penyimpanan Metadata di Database

Metadata file yang disimpan di database minimal meliputi:

1. original_file_name
2. stored_file_name atau file_name internal
3. file_path atau object key
4. mime_type
5. file_extension
6. file_size
7. checksum
8. uploaded_by
9. uploaded_at
10. publication_status
11. access flags yang relevan

Aturan:

1. Database tidak menyimpan isi file biner
2. Database tidak menyimpan URL publik final sebagai sumber utama
3. URL final dibangun dinamis dari service

## 11. Object Key Naming Policy

Object key harus:

1. konsisten
2. mudah dikelola
3. tidak bentrok
4. tidak terlalu bergantung pada nama file asli
5. tetap bisa ditelusuri secara logis

Format umum yang direkomendasikan:
`{environment}/{module}/{entity}/{year}/{month}/{uuid_or_hash}.{ext}`

Contoh:

1. `prod/core/institution-profile/2026/04/logo-uuid.webp`
2. `prod/catalog/covers/2026/04/cover-uuid.jpg`
3. `prod/digital-assets/record-101/2026/04/asset-uuid.pdf`

Aturan:

1. Gunakan UUID atau token acak sebagai bagian nama file
2. Jangan memakai nama file asli mentah sebagai object key utama
3. Simpan original file name terpisah di database

## 12. Struktur Folder atau Object Key Resmi

### 12.1 Public Assets

Struktur yang direkomendasikan:

1. `core/institution-profile/logo/`
2. `catalog/covers/`

Contoh:

1. `prod/core/institution-profile/logo/logo-main-uuid.webp`
2. `prod/catalog/covers/2026/04/cover-101-uuid.jpg`

### 12.2 Private Assets

Struktur yang direkomendasikan:

1. `digital-assets/record-{record_id}/`
2. `digital-assets/asset-{asset_id}/ocr-temp/`
3. `digital-assets/asset-{asset_id}/derived/`, opsional fase berikutnya

Contoh:

1. `prod/digital-assets/record-101/2026/04/asset-300-uuid.pdf`
2. `prod/digital-assets/asset-300/ocr-temp/page-001.png`

### 12.3 Temp Area

Struktur yang direkomendasikan:

1. `temp/uploads/`
2. `temp/ocr/`
3. `temp/export/`

Aturan:

1. Temp area tidak boleh digunakan sebagai lokasi permanen
2. File temp harus bisa dibersihkan aman

## 13. Mapping File ke Model

| Jenis File | Model | Field Path Utama |
|---|---|---|
| logo institusi | InstitutionProfile | logo_path |
| cover record | BibliographicRecord | cover_path |
| aset digital PDF | DigitalAsset | file_path |
| file turunan OCR opsional | tidak wajib model khusus | dapat berupa temp storage atau metadata internal |

Catatan:

1. OcrText menyimpan hasil teks, bukan file utama
2. Bila derivative file disimpan permanen di fase berikutnya, schema dapat diperluas

## 14. Mapping File ke Service

Service resmi yang terkait storage:

1. InstitutionProfileService
2. BibliographicRecordService, untuk cover
3. DigitalAssetUploadService
4. DigitalAssetAccessService
5. AssetStreamingService
6. OcrProcessingService
7. SearchIndexService, hanya membaca metadata yang diperlukan, bukan mengelola file langsung

## 15. Aturan Upload Umum

Aturan upload umum:

1. File wajib lolos validasi request
2. File wajib diperiksa type dan size
3. File disimpan lewat service layer
4. Metadata file dicatat setelah file tersimpan berhasil
5. Jika metadata gagal setelah file tersimpan, service harus menangani rollback atau cleanup
6. Jika file gagal tersimpan, record metadata tidak boleh dianggap sukses

## 16. Aturan Upload Logo Institusi

Logo institusi memiliki kebijakan:

1. Jenis file: jpg, jpeg, png, webp
2. Ukuran maksimum: 2 MB
3. Lokasi storage: public_assets
4. Hanya satu logo aktif utama
5. Saat logo diganti, file lama dapat:
   1. dihapus
   2. diarsipkan
   3. atau ditandai obsolete
   sesuai kebijakan implementasi

Rekomendasi:

1. Fase 1 cukup simpan satu logo aktif
2. File lama boleh dibersihkan setelah update berhasil

## 17. Aturan Upload Cover Bibliographic Record

Cover bibliographic record memiliki kebijakan:

1. Jenis file: jpg, jpeg, png, webp
2. Ukuran maksimum: 4 MB
3. Lokasi storage: public_assets
4. Cover bukan file sensitif
5. Cover boleh diakses publik sebagai bagian OPAC jika record publik

Aturan:

1. Cover tidak boleh menggantikan identitas record
2. Jika cover diganti, metadata cover_path harus diperbarui
3. File cover lama harus dikelola secara aman

## 18. Aturan Upload Aset Digital Utama

Aset digital utama pada fase 1 memiliki kebijakan:

1. Jenis file utama: PDF
2. Ukuran maksimum default: 50 MB
3. Nilai maksimum dapat dibaca dari system settings
4. Lokasi storage: private_assets
5. File asli tidak dibuka langsung ke publik

Metadata wajib:

1. original_file_name
2. file_name internal
3. file_path
4. mime_type
5. extension
6. file_size
7. checksum
8. uploaded_by
9. uploaded_at

Aturan:

1. PDF karya ilmiah, e-book, modul, dan jurnal tetap disimpan privat
2. Akses preview publik dilakukan oleh aplikasi, bukan direct object link mentah
3. Semua upload aset digital harus melewati DigitalAssetUploadService

## 19. Validasi File Type

File type wajib diverifikasi melalui:

1. validasi request
2. pengecekan mime type
3. pengecekan ekstensi
4. pengecekan keamanan dasar di service

Fase 1:

1. aset digital hanya PDF
2. logo dan cover hanya image type resmi

## 20. Validasi File Size

Ukuran file wajib diverifikasi:

1. di request layer
2. di service bila nilai diambil dari setting dinamis

Batas default:

1. logo: 2 MB
2. cover: 4 MB
3. PDF: 50 MB

## 21. Aturan Checksum

Setiap aset digital utama wajib menyimpan checksum.

Tujuan:

1. deteksi duplikasi
2. verifikasi integritas
3. membantu debugging storage
4. membantu audit teknis

Rekomendasi:

1. gunakan SHA-256
2. simpan checksum di kolom `checksum`

Aturan:

1. checksum bukan pengganti validasi file type
2. checksum tidak boleh dibuka ke publik

## 22. Aturan Nama File Asli

Original file name:

1. disimpan di database
2. dipakai untuk informasi pengguna
3. tidak dipakai sebagai object key utama

Aturan:

1. nama asli boleh berisi spasi dan karakter tertentu, tetapi tetap harus disanitasi untuk penyimpanan metadata
2. nama asli tidak menjadi sumber keputusan keamanan

## 23. Aturan MIME Type dan Extension

Service wajib menyimpan:

1. mime_type aktual
2. file_extension aktual

Aturan:

1. mime_type dan extension harus konsisten
2. jika tidak konsisten, upload dapat ditolak
3. PDF harus memiliki mime type PDF yang sah

## 24. Aturan Preview Publik

Preview publik hanya boleh terjadi bila:

1. DigitalAsset publication_status = published
2. DigitalAsset is_public = true atau rule akses publik sah
3. embargo tidak aktif atau sudah lewat
4. DigitalAssetAccessService mengizinkan preview
5. request datang dari jalur resmi `opac.assets.preview` atau endpoint publik sah

Aturan:

1. preview publik tidak berarti file menjadi publik mentah
2. file tetap bisa disimpan di private_assets
3. viewer membaca stream aman dari aplikasi

## 25. Aturan Preview Privat Internal

Preview privat internal hanya boleh untuk user yang:

1. telah login bila diperlukan
2. memiliki permission sah
3. lolos policy resource
4. lolos pemeriksaan access service

Jalur resmi:

1. `admin.digital.assets.preview`
2. `assets.private.show`

## 26. Aturan Download Privat

Download privat hanya boleh bila:

1. user terautentikasi
2. permission atau policy mengizinkan
3. asset rule mengizinkan download
4. service menyetujui akses

Jalur resmi:

1. `assets.private.download`

Aturan:

1. download tidak boleh memakai URL storage mentah
2. semua download harus di-stream atau dilayani aman

## 27. Aturan URL File

Aturan resmi:

1. URL publik final tidak disimpan sebagai sumber utama di database
2. Database menyimpan path atau object key
3. URL atau stream path dibangun saat runtime

Untuk file publik ringan:

1. service boleh membangun URL terkontrol
2. tetap hindari hardcode domain pada database

Untuk file privat:

1. jangan pernah membocorkan object key mentah ke pengguna publik bila tidak perlu
2. akses harus melalui controller atau service

## 28. Aturan Laravel Disk Mapping

Disk yang direkomendasikan:

1. `public_assets`
   1. driver: s3 atau compatible
   2. visibility: public atau controlled public
2. `private_assets`
   1. driver: s3 atau compatible
   2. visibility: private
3. `local_temp`
   1. driver: local

Aturan:

1. mapping final berada pada konfigurasi Laravel
2. dokumen ini hanya menetapkan kebijakan arsitektural

## 29. Aturan Storage Path Exposure

Data berikut tidak boleh ditampilkan ke publik:

1. private object key mentah
2. internal bucket name
3. temp file path
4. path OCR temp
5. checksum
6. error storage detail

Data berikut boleh tampil bila aman:

1. cover image URL final terkontrol
2. logo institusi URL final terkontrol

## 30. Aturan Akses Berdasarkan State Machine

Kebijakan storage wajib tunduk pada 17_WORKFLOW_STATE_MACHINE.md.

Implikasi:

1. asset draft tidak boleh preview publik
2. asset unpublished tidak boleh preview publik
3. asset archived tidak boleh preview publik
4. asset public embargoed tidak boleh preview publik sampai embargo berakhir
5. record non publik tidak boleh mengekspose asset publik di OPAC

## 31. Aturan Lifecycle File

Lifecycle file resmi:

1. uploaded
2. stored
3. metadata recorded
4. processed, OCR atau indexing bila perlu
5. accessible sesuai rule
6. replaced atau archived
7. deleted atau retained sesuai kebijakan

### 31.1 Uploaded

File baru masuk proses upload.

### 31.2 Stored

File berhasil masuk storage target.

### 31.3 Metadata Recorded

Database berhasil menyimpan metadata.

### 31.4 Processed

File bisa masuk OCR, preview, atau indexing.

### 31.5 Accessible

File dapat diakses sesuai rule.

### 31.6 Replaced atau Archived

File diganti atau asset diarsipkan.

### 31.7 Deleted atau Retained

File dihapus fisik atau ditahan karena kebijakan audit.

## 32. Aturan Replace File

Saat file diganti:

1. file baru harus lolos validasi penuh
2. metadata baru harus ditulis
3. file lama harus ditandai obsolete
4. cleanup file lama dapat:
   1. langsung dihapus
   2. dijadwalkan
   3. ditahan singkat untuk rollback

Rekomendasi fase 1:

1. gunakan cleanup terkontrol setelah update sukses
2. jangan tinggalkan orphan file tanpa catatan

## 33. Aturan Delete File

Penghapusan file harus memperhatikan:

1. status record
2. kebutuhan audit
3. kebutuhan rollback
4. kebutuhan referensi search
5. kebutuhan OCR dan derivative

Aturan umum:

1. delete database record tidak otomatis berarti file fisik langsung dihapus saat itu juga
2. service harus menentukan strategi hapus
3. untuk soft delete, file dapat dipertahankan dulu
4. purge fisik bisa dijalankan terpisah

## 34. Aturan Soft Delete dan File Retention

Model yang memakai soft delete:

1. BibliographicRecord
2. PhysicalItem
3. Member
4. DigitalAsset
5. User, sesuai kebijakan

Implikasi storage:

1. file terkait DigitalAsset yang soft deleted tidak harus langsung dihapus fisik
2. file dapat dipertahankan sampai purge manual atau job cleanup
3. record yang tidak aktif tidak boleh lagi terpapar ke publik

## 35. Aturan Retention Fase 1

Retention minimum yang direkomendasikan:

1. file aktif disimpan selama record aktif
2. file obsolete hasil replace dapat ditahan singkat untuk rollback, misalnya 7 hari
3. file soft deleted dapat ditahan hingga proses housekeeping
4. temp file OCR dan upload dibersihkan cepat, misalnya 1 sampai 7 hari

Catatan:

1. nilai final retention dapat ditetapkan lagi pada dokumen backup dan recovery
2. fase 1 cukup menetapkan prinsip retensi aman

## 36. Aturan Temp File

Temp file dipakai untuk:

1. upload sementara
2. OCR processing
3. image conversion sementara
4. export sementara

Aturan:

1. temp file tidak boleh disimpan permanen
2. temp file harus dibersihkan dengan job housekeeping
3. temp file tidak boleh diindeks
4. temp file tidak boleh diakses publik

## 37. Aturan OCR dan Storage

OCR menggunakan file sumber dari private_assets.

Alur:

1. Asset PDF dibaca dari storage privat
2. File atau halaman diubah sementara ke temp area bila perlu
3. OCR menghasilkan teks
4. Hasil teks masuk ke tabel OcrText
5. temp OCR dibersihkan
6. reindex dipicu bila perlu

Aturan:

1. hasil OCR utama disimpan sebagai teks, bukan file utama baru
2. derivative OCR image tidak wajib permanen
3. file sementara OCR harus dibersihkan

## 38. Aturan Search dan Storage

Search indexing hanya memakai metadata file dan teks yang aman.

Aturan:

1. file_path tidak boleh masuk index publik
2. original_file_name tidak harus masuk index publik
3. cover_path boleh dipakai untuk tampilan publik bila record publik
4. OCR text hanya masuk index publik bila rule akses mengizinkan

## 39. Aturan Backup Storage

Backup storage harus mempertimbangkan:

1. object storage utama
2. metadata database
3. integritas relasi path dengan database
4. temp storage tidak wajib dibackup

Aturan fase 1:

1. object storage permanen harus dibackup atau direplikasi sesuai kemampuan infrastruktur
2. metadata database wajib menjadi pasangan backup file
3. restore file tanpa database atau sebaliknya dianggap tidak cukup

## 40. Aturan Restore File

Saat restore:

1. database dan storage harus selaras
2. checksum dapat dipakai verifikasi
3. file orphan harus dapat dideteksi
4. record dengan path hilang harus ditandai untuk perbaikan

## 41. Aturan Housekeeping

Housekeeping wajib mencakup:

1. pembersihan temp file
2. pembersihan obsolete file yang aman dihapus
3. deteksi orphan file
4. deteksi metadata file yang rusak
5. deteksi checksum mismatch bila diperlukan

Rekomendasi:

1. buat job berkala
2. logging housekeeping harus tersedia
3. aksi destruktif harus berhati hati

## 42. Aturan Keamanan Storage

Keamanan storage wajib mencakup:

1. private bucket atau private disk tidak boleh public list
2. access key storage tidak boleh disimpan di kode sumber
3. path sensitif tidak ditampilkan ke UI
4. upload file harus dibatasi type dan size
5. file name harus dinormalisasi
6. preview privat harus melalui aplikasi
7. akses publik hanya untuk file yang benar benar aman

## 43. Aturan Anti Malware Dasar

Fase 1 belum mewajibkan antivirus terintegrasi penuh, tetapi kebijakan dasar:

1. batasi jenis file
2. batasi hanya PDF untuk aset digital
3. tolak file executable
4. log file yang gagal validasi type
5. siapkan ruang integrasi scanning pada fase berikutnya

## 44. Aturan Integrity Check

Integrity check minimal:

1. file_path tidak null pada aset aktif
2. file_size lebih dari 0
3. checksum terisi pada aset digital utama
4. mime_type dan extension konsisten
5. file benar benar ada saat diakses

Jika tidak valid:

1. service mengembalikan error aman
2. log teknis dicatat
3. publik tidak melihat pesan teknis mentah

## 45. Aturan Error Handling Storage

Jika storage gagal:

1. upload harus gagal dengan aman
2. metadata tidak boleh dianggap sukses bila file belum aman tersimpan
3. user menerima pesan yang manusiawi
4. detail teknis masuk log aplikasi

Jika preview gagal:

1. jangan tampilkan path teknis
2. tampilkan pesan akses atau file tidak tersedia
3. audit atau log internal dapat dicatat sesuai kebutuhan

## 46. Aturan Permission dan Access Service

Akses file harus selalu dipusatkan pada:

1. DigitalAssetAccessService
2. AssetStreamingService

Aturan:

1. controller tidak mengambil keputusan akses mentah
2. service memeriksa publication status
3. service memeriksa is_public
4. service memeriksa embargo
5. service memeriksa access rules
6. service memeriksa permission atau policy untuk internal

## 47. Aturan File Publik pada OPAC

Yang boleh langsung tampil di OPAC:

1. cover
2. logo institusi
3. metadata preview aset publik
4. stream preview aset publik yang lolos rule

Yang tidak boleh langsung tampil:

1. private PDF URL
2. path object storage mentah
3. OCR temp
4. metadata teknis file

## 48. Aturan Penyimpanan Cover dan Logo di Search

Search index publik boleh menyimpan:

1. cover_path yang aman untuk tampilan
2. bukan bucket detail mentah yang sensitif
3. URL tampilan final dapat dibentuk saat hydration

Aturan:

1. cover hanya elemen display
2. cover tidak menjadi sumber akses utama file privat

## 49. Aturan Lokasi File Berdasarkan Modul

### 49.1 Core

1. logo institusi
2. kemungkinan file branding lain di fase lanjut

### 49.2 Catalog

1. cover bibliographic record

### 49.3 Digital Repository

1. file PDF utama
2. derivative file opsional
3. temp OCR

## 50. Aturan Naming Konvensi Contoh

### 50.1 Logo

`prod/core/institution-profile/logo/logo-{uuid}.webp`

### 50.2 Cover

`prod/catalog/covers/{year}/{month}/cover-{record_id}-{uuid}.jpg`

### 50.3 PDF Aset Digital

`prod/digital-assets/record-{record_id}/{year}/{month}/asset-{asset_id_or_temp_uuid}.pdf`

### 50.4 Temp OCR

`temp/ocr/asset-{asset_id}/{job_uuid}/page-001.png`

## 51. Aturan Implementasi di Laravel

Implementasi wajib mengikuti pola berikut:

1. upload diproses di service
2. service menyimpan ke disk yang sesuai
3. service menerima hasil object key
4. service menyimpan metadata ke database
5. controller hanya menerima hasil dari service
6. preview privat memakai streamed response
7. preview publik tetap melalui route aplikasi

## 52. Mapping Storage ke Controller

| Use Case | Controller |
|---|---|
| update logo institusi | InstitutionProfileController |
| upload cover melalui katalog | BibliographicRecordController |
| upload aset digital | DigitalAssetController |
| preview internal aset | DigitalAssetController |
| preview publik aset | AssetPreviewController |
| download privat aset | AssetAccessController |

## 53. Mapping Storage ke Route

| Route | Tujuan |
|---|---|
| admin.settings.institution_profile.update | update logo institusi bila ada |
| admin.catalog.records.store | create record dan cover opsional |
| admin.catalog.records.update | update cover opsional |
| admin.digital.assets.store | upload PDF |
| admin.digital.assets.preview | preview internal |
| assets.private.show | stream privat |
| assets.private.download | download privat |
| opac.assets.preview | preview publik |

## 54. Integrasi dengan API Contract

Endpoint API yang menyentuh storage harus mematuhi dokumen 20_API_CONTRACT.md.

Contoh:

1. public asset metadata tidak boleh mengembalikan file_path mentah
2. OCR dispatch tidak boleh mengembalikan path internal
3. internal lookup asset tidak harus mengembalikan object key sensitif
4. response file sebaiknya lewat route stream khusus, bukan JSON path mentah

## 55. Prioritas Implementasi Storage

### Prioritas P1

1. konfigurasi public_assets
2. konfigurasi private_assets
3. upload logo institusi
4. upload cover
5. upload PDF digital asset
6. preview privat aman
7. preview publik aman
8. metadata checksum

### Prioritas P2

1. cleanup obsolete file
2. temp OCR workflow
3. integrity check file existence
4. signed path atau controlled URL refinement bila dibutuhkan

### Prioritas P3

1. derivative file permanen
2. antivirus integration
3. lifecycle retention automation lanjutan
4. storage analytics

## 56. Aturan yang Tidak Boleh Dilanggar

Storage implementation tidak boleh:

1. menyimpan PDF di database blob
2. membuka private_assets langsung ke publik
3. menyimpan URL final publik sebagai sumber utama di database
4. mengandalkan frontend untuk memutuskan akses file
5. menyimpan file tanpa metadata yang cukup
6. membiarkan object key bentrok
7. membiarkan orphan file terus menumpuk tanpa strategi housekeeping
8. membocorkan bucket name atau path sensitif ke UI publik

## 57. Testing Requirement Storage

Pengujian minimum wajib mencakup:

1. upload logo berhasil
2. upload cover berhasil
3. upload PDF valid berhasil
4. upload non PDF ditolak
5. upload file terlalu besar ditolak
6. file metadata tersimpan konsisten
7. preview publik hanya untuk asset yang public accessible
8. preview privat ditolak untuk user tanpa akses
9. download privat ditolak untuk user tanpa akses
10. ganti cover membersihkan atau menandai file lama sesuai kebijakan
11. ganti asset digital memperbarui metadata dengan benar
12. OCR membaca file dari private storage
13. file_path tidak bocor di response publik
14. record unpublished tidak lagi memberi preview publik
15. asset embargoed tidak preview publik

## 58. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 23_OCR_AND_DIGITAL_PROCESSING.md
2. 25_REPORTING_SPEC.md
3. 28_SECURITY_POLICY.md
4. 29_AUDIT_LOG_SPEC.md
5. 30_ERROR_CODE.md
6. 31_TEST_PLAN.md
7. 32_TEST_SCENARIO.md
8. 33_DEPLOYMENT_GUIDE.md
9. 34_ENV_CONFIGURATION.md
10. 35_BACKUP_AND_RECOVERY.md
11. 38_TREE.md
12. 39_TRACEABILITY_MATRIX.md
13. 41_BACKEND_CHECKLIST.md
14. 42_FRONTEND_CHECKLIST.md
15. 45_SMOKE_TEST_CHECKLIST.md
16. 46_UAT_CHECKLIST.md

Aturan:

1. OCR processing harus memakai source file policy dari dokumen ini
2. security policy harus memperkuat private storage protection
3. deployment guide harus menjelaskan disk mapping dan bucket config
4. backup and recovery harus memetakan object storage dan metadata database secara bersamaan
5. frontend checklist harus memastikan tidak ada raw path bocor ke UI

## 59. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. semua jenis file utama sudah punya lokasi storage yang jelas
2. file publik dan privat sudah dipisahkan
3. database hanya menyimpan metadata dan path yang aman
4. preview dan download selalu melalui service yang sah
5. OCR dan search sudah terhubung secara benar dengan storage
6. tidak ada keputusan akses file yang hanya bergantung pada URL mentah
7. aturan upload sejalan dengan validation rules dan schema

## 60. Kesimpulan

Dokumen Storage File Policy ini menetapkan kebijakan resmi penyimpanan file PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 21. Dokumen ini memastikan bahwa logo, cover, dan aset digital disimpan dengan struktur yang tertib, metadata yang cukup, pemisahan akses publik dan privat yang tegas, serta integrasi yang aman dengan controller, service, OCR, search, dan OPAC. Semua implementasi storage file PERPUSQU wajib merujuk dokumen ini.

END OF 22_STORAGE_FILE_POLICY.md
