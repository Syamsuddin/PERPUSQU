# 37_CODING_STANDARD.md

## 1. Nama Dokumen

Coding Standard Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint standar coding backend, frontend, database, integrasi, keamanan, dan kualitas implementasi

### 2.3 Status Dokumen

Resmi, acuan wajib penulisan kode sumber, struktur file, naming, style, boundary layer, kualitas implementasi, dan disiplin engineering PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan standar coding resmi PERPUSQU agar seluruh implementasi Laravel, PHP, Blade, query database, queue, OCR, search, file handling, reporting, import export, dan UI berjalan konsisten, mudah dipelihara, aman, dan selaras dengan seluruh blueprint yang telah disusun sebelumnya. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar tidak ada implementasi liar, tidak ada penamaan kacau, tidak ada shortcut yang menabrak service layer, dan tidak ada kode yang bertentangan dengan security policy, workflow state machine, error code, dan traceability sistem.

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
23. 23_OCR_AND_DIGITAL_PROCESSING.md
24. 24_NOTIFICATION_RULES.md
25. 25_REPORTING_SPEC.md
26. 26_IMPORT_EXPORT_SPEC.md
27. 27_INTEGRATION_SPEC.md
28. 28_SECURITY_POLICY.md
29. 29_AUDIT_LOG_SPEC.md
30. 30_ERROR_CODE.md
31. 31_TEST_PLAN.md
32. 32_TEST_SCENARIO.md
33. 33_DEPLOYMENT_GUIDE.md
34. 34_ENV_CONFIGURATION.md
35. 35_BACKUP_AND_RECOVERY.md
36. 36_PERFORMANCE_GUIDE.md

Aturan wajib:

1. Semua kode wajib mengikuti arsitektur monolith modular.
2. Semua kode wajib mengikuti naming resmi route, controller, service, model, dan tabel yang telah disepakati.
3. Semua aksi bisnis wajib melewati service layer.
4. Semua input tulis wajib melewati request validation.
5. Semua area sensitif wajib mengikuti security policy.
6. Semua error domain wajib mengikuti error code resmi.
7. Semua aksi penting wajib mempertimbangkan audit log.
8. Semua implementasi harus mudah diuji dan mudah ditelusuri.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip umum coding
2. standar struktur proyek
3. standar penamaan
4. standar PHP dan Laravel
5. standar controller
6. standar request validation
7. standar service layer
8. standar model dan relation
9. standar repository atau query pattern
10. standar route
11. standar Blade dan UI
12. standar JavaScript ringan
13. standar job, queue, dan scheduler
14. standar storage dan file handling
15. standar search dan OCR
16. standar reporting
17. standar import export
18. standar keamanan kode
19. standar logging dan audit
20. standar testing readiness
21. standar review kualitas

## 5. Prinsip Umum Coding

Prinsip resmi coding PERPUSQU adalah:

1. jelas
2. konsisten
3. modular
4. aman
5. mudah dibaca
6. mudah diuji
7. tidak berlebihan
8. dekat dengan domain bisnis
9. minim kejutan
10. mudah dirawat tim kecil

## 6. Prinsip Engineering Utama

Prinsip engineering yang wajib dipakai:

1. thin controller
2. fat service secukupnya
3. model tidak menjadi tempat seluruh logika bisnis
4. validation di request
5. authorization di middleware, permission, dan policy
6. transaksi database hanya pada unit kerja yang memang perlu
7. proses berat async
8. source of truth tetap MySQL
9. file tidak disimpan di database
10. error tidak bocor ke UI biasa

## 7. Bahasa dan Konvensi Umum

Bahasa dalam kode:

1. nama class, method, variable, constant, enum, dan file memakai Bahasa Inggris teknis
2. teks UI, notifikasi, dan label default memakai Bahasa Indonesia
3. komentar teknis boleh memakai Bahasa Indonesia atau Inggris, tetapi harus konsisten dan ringkas

Aturan:

1. jangan campur bahasa dalam satu identifier
2. hindari singkatan yang tidak jelas
3. hindari nama generik seperti data, temp, process, helper bila ada nama yang lebih spesifik

## 8. Arsitektur Kode Resmi

Arsitektur kode resmi mengikuti monolith modular Laravel.

Prinsip:

1. satu codebase
2. modul domain jelas
3. dependency antar modul terkontrol
4. route, controller, request, service, model, policy, job, dan view tersusun rapi
5. tidak memakai controller lintas modul sebagai jalur bisnis utama

## 9. Struktur Modul yang Direkomendasikan

Struktur modul yang direkomendasikan:

```text id="msh7q9"
app/
  Modules/
    Identity/
    Core/
    MasterData/
    Catalog/
    Collection/
    Member/
    Circulation/
    DigitalRepository/
    Reporting/
    Audit/
    Profile/
````

Di dalam modul, struktur umum yang direkomendasikan:

```text id="vc2rj1"
app/Modules/ModuleName/
  Http/
    Controllers/
    Requests/
  Models/
  Services/
  Policies/
  Jobs/
  Support/
  DTOs/
  Actions/
  Resources/
```

Catatan:

1. tidak semua modul wajib punya semua folder
2. folder hanya dibuat jika benar benar dipakai
3. struktur final harus tetap konsisten dengan 38_TREE.md saat ditulis nanti

## 10. Namespace dan PSR

Aturan namespace:

1. setiap class memakai namespace sesuai lokasi file
2. autoload mengikuti PSR-4
3. satu file satu class utama
4. nama file dan nama class harus sama

Contoh:

1. `App\Modules\Catalog\Http\Controllers\BibliographicRecordController`
2. `App\Modules\Circulation\Services\LoanTransactionService`

## 11. Standar Penamaan Umum

Aturan penamaan umum:

1. class memakai PascalCase
2. method memakai camelCase
3. variable memakai camelCase
4. constant memakai UPPER_SNAKE_CASE
5. table memakai snake_case plural
6. column memakai snake_case
7. route name memakai snake_case bertitik sesuai pola resmi
8. blade file memakai snake_case
9. migration file mengikuti konvensi Laravel standar
10. config key memakai snake_case atau lower dot notation sesuai konteks

## 12. Standar Penamaan Controller

Controller wajib:

1. memakai nama entitas atau tujuan yang jelas
2. diakhiri `Controller`
3. tidak memakai nama generik yang kabur

Contoh benar:

1. `UserController`
2. `BibliographicRecordController`
3. `DigitalAssetController`
4. `AssetPreviewController`
5. `DashboardReportController`

Contoh yang tidak disarankan:

1. `DataController`
2. `MainController`
3. `GeneralController`

## 13. Standar Penamaan Service

Service wajib:

1. memakai nama domain atau aksi yang jelas
2. diakhiri `Service`
3. tidak menjadi tempat semua hal acak

Contoh benar:

1. `AuthenticationService`
2. `LoanTransactionService`
3. `FineCalculationService`
4. `SearchIndexService`
5. `OcrProcessingService`
6. `ReportExportService`

## 14. Standar Penamaan Request

Request validation wajib:

1. diakhiri `Request`
2. menyebut aksi atau domain yang jelas

Contoh benar:

1. `StoreMemberRequest`
2. `UpdateMemberRequest`
3. `StoreDigitalAssetRequest`
4. `MemberImportRequest`
5. `CollectionReportExportRequest`

## 15. Standar Penamaan Job

Job queue wajib:

1. diakhiri `Job`
2. memakai nama aksi eksplisit

Contoh benar:

1. `ProcessDigitalAssetOcrJob`
2. `ReindexBibliographicRecordJob`
3. `SendOperationalEmailNotificationJob`

## 16. Standar Penamaan Policy

Policy wajib:

1. diakhiri `Policy`
2. satu policy per resource utama

Contoh:

1. `UserPolicy`
2. `BibliographicRecordPolicy`
3. `DigitalAssetPolicy`

## 17. Standar Penamaan DTO dan Support Class

DTO dan support class harus spesifik.

Contoh:

1. `LoanEligibilityResult`
2. `OcrResult`
3. `SearchDocumentPayload`
4. `ImportRowError`
5. `AuditValueSanitizer`

Hindari:

1. `Helper`
2. `Utils`
3. `Common`
   kecuali benar benar sangat terbatas dan jelas ruang lingkupnya

## 18. Standar Route Naming

Route name wajib mengikuti 09_ROUTE_MAP.md dan harus stabil.

Pola umum:

1. `admin.module.entity.index`
2. `admin.module.entity.create`
3. `admin.module.entity.store`
4. `admin.module.entity.edit`
5. `admin.module.entity.update`
6. `admin.module.entity.destroy`

Contoh:

1. `admin.catalog.records.index`
2. `admin.members.index`
3. `admin.digital.assets.store`
4. `admin.reports.members.export`
5. `opac.search`
6. `opac.records.show`

Aturan:

1. jangan ubah route name tanpa pembaruan route map
2. route name harus menjadi acuan link Blade
3. jangan hardcode URL di banyak tempat jika route name sudah ada

## 19. Standar View Naming

View naming wajib mengikuti view map resmi.

Contoh:

1. `modules/catalog/records/index.blade.php`
2. `modules/member/members/form.blade.php`
3. `modules/reporting/dashboard.blade.php`
4. `opac/search/index.blade.php`

Aturan:

1. satu view satu tanggung jawab halaman utama
2. partial atau komponen diberi nama jelas
3. jangan jadikan satu file Blade sangat panjang tanpa partial bila bisa dipecah wajar

## 20. Standar PHP Style

Standar PHP umum:

1. ikuti PSR-12
2. gunakan type declaration bila wajar
3. gunakan return type
4. gunakan strict typing bila project memutuskannya secara global dan konsisten
5. satu tanggung jawab per method
6. hindari method sangat panjang
7. hindari nested terlalu dalam
8. gunakan early return bila membantu kejelasan

## 21. Aturan Panjang Method

Rekomendasi:

1. method controller singkat
2. method service fokus pada satu use case
3. jika method sudah terlalu panjang, pecah ke method privat atau support class
4. method tidak boleh memuat validasi, query, transformasi, audit, dan notification campur tanpa struktur

## 22. Aturan Controller

Controller wajib:

1. tipis
2. menerima request tervalidasi
3. memanggil service yang sesuai
4. memetakan hasil ke response web atau API
5. tidak memuat query bisnis rumit
6. tidak memuat proses OCR, export berat, atau reindex langsung
7. tidak memutuskan rule domain rumit sendirian

Controller tidak boleh:

1. membangun seluruh logika bisnis
2. mengakses storage privat langsung tanpa service
3. memuat raw query besar
4. menulis audit manual berulang tanpa pola konsisten
5. melakukan branching domain yang rumit di dalam method

## 23. Pola Method Controller

Untuk web admin, pola umum:

1. `index`
2. `create`
3. `store`
4. `show`
5. `edit`
6. `update`
7. `destroy`

Untuk aksi domain khusus:

1. `publish`
2. `unpublish`
3. `archive`
4. `block`
5. `unblock`
6. `runOcr`
7. `reindex`
8. `preview`
9. `download`

Aturan:

1. aksi khusus tetap harus mengikuti route map dan service layer
2. nama method harus eksplisit

## 24. Aturan Request Validation

Semua request write wajib memakai Form Request.

Wajib untuk:

1. create
2. update
3. import
4. export request
5. filter yang kompleks atau sensitif
6. API payload internal yang penting

Aturan:

1. validasi format di Request
2. pesan field dapat ditulis di Request
3. authorization ringan dapat ditaruh di Request jika memang sesuai
4. validasi bisnis final tetap di service

## 25. Aturan Service Layer

Service layer adalah pusat logika domain.

Service wajib:

1. memproses use case
2. memanggil model dan relasi yang relevan
3. menjalankan business rule
4. menjalankan transaction bila diperlukan
5. memicu audit, queue, reindex, atau notifikasi sesuai kebutuhan
6. mengembalikan hasil yang jelas ke controller

Service tidak boleh:

1. menjadi tempat semua query tanpa struktur
2. memuat kode presentasi UI
3. melempar pesan teknis mentah ke user layer
4. melanggar workflow state machine

## 26. Aturan Transaction Boundary

Gunakan DB transaction hanya bila beberapa perubahan harus berhasil bersama.

Wajib transaction untuk contoh:

1. create loan plus update item status
2. return loan plus return transaction plus fine plus update item status
3. create digital asset metadata setelah file write sukses dan metadata save masih satu unit logis
4. perubahan role permission yang harus konsisten

Tidak perlu memaksa satu transaction untuk:

1. OCR end to end
2. reindex Meilisearch
3. email async
4. export async

## 27. Aturan Exception dan Domain Error

Gunakan error code resmi sebagai acuan.

Aturan:

1. service harus memakai error domain yang konsisten
2. controller memetakan ke flash message atau JSON response
3. error teknis penuh masuk log internal
4. jangan tampilkan exception mentah ke UI

Contoh:

1. loan ditolak member blocked -> `BUS_409_MEMBER_BLOCKED`
2. asset preview ditolak -> `SEC_403_PRIVATE_ASSET_ACCESS_DENIED`
3. OCR gagal -> `OCR_500_PROCESS_FAILED`

## 28. Aturan Model

Model Eloquent dipakai sebagai representasi data domain, bukan tempat seluruh logika aplikasi.

Model boleh memuat:

1. table name bila perlu
2. fillable atau guarded strategy yang jelas
3. casts
4. relation
5. scope ringan
6. accessor atau mutator ringan bila perlu

Model tidak boleh memuat:

1. logika bisnis panjang
2. query reporting besar
3. OCR logic
4. export builder logic
5. validasi request

## 29. Aturan Fillable dan Guarded

Pilih strategi mass assignment yang konsisten.

Rekomendasi:

1. gunakan `fillable` untuk field yang aman
2. field sensitif seperti role atau status khusus diproses eksplisit
3. jangan memakai `guarded = []` tanpa disiplin kuat

## 30. Aturan Casts

Gunakan casts untuk field yang memang perlu.

Contoh:

1. boolean flags
2. timestamps khusus
3. array JSON seperti old_values dan new_values
4. numeric values yang perlu cast konsisten

Aturan:

1. casts harus membantu kejelasan
2. jangan cast berlebihan tanpa manfaat

## 31. Aturan Relation

Relation model wajib:

1. diberi nama jelas
2. mengikuti domain
3. memakai singular atau plural yang tepat

Contoh:

1. `bibliographicRecord()`
2. `physicalItems()`
3. `digitalAssets()`
4. `loanRenewals()`
5. `accessRules()`

Aturan:

1. jangan gunakan nama relation ambigu
2. jangan ubah relation name sembarangan karena memengaruhi controller, service, view, dan eager loading

## 32. Aturan Query Scope

Gunakan local scope untuk filter domain ringan yang sering dipakai.

Contoh:

1. `published()`
2. `publiclyVisible()`
3. `active()`
4. `blocked()`
5. `overdue()`

Aturan:

1. scope ringan dan reusable boleh di model
2. query report kompleks tetap di report service atau query class

## 33. Aturan Query Pattern

Query harus:

1. jelas
2. aman
3. efisien
4. sesuai performance guide

Prinsip:

1. gunakan query builder atau Eloquent
2. eager load seperlunya
3. gunakan select field yang dibutuhkan
4. gunakan pagination untuk list
5. gunakan SQL aggregate untuk report

## 34. Aturan Repository Pattern

Repository pattern tidak wajib dipaksakan secara global.

Rekomendasi:

1. gunakan service plus query class secukupnya
2. buat repository hanya bila benar benar memberi manfaat nyata
3. jangan menambah layer kosong tanpa nilai

## 35. Aturan Query Class atau Support Query

Untuk reporting atau query kompleks, query class sangat dianjurkan.

Contoh:

1. `DashboardMetricsQuery`
2. `CirculationReportQuery`
3. `PopularCollectionQuery`
4. `AuditLogQuery`

Manfaat:

1. query lebih terstruktur
2. service tetap lebih bersih
3. mudah diuji

## 36. Aturan Database Migration

Migration wajib:

1. sesuai schema resmi
2. incremental
3. aman
4. tidak ambigu

Aturan:

1. nama migration jelas
2. foreign key jelas
3. index penting ditulis
4. rollback migration masuk akal
5. jangan ubah migration lama yang sudah dipakai production tanpa alasan besar
6. perubahan schema setelah stabil dilakukan lewat migration baru

## 37. Aturan Seeder

Seeder wajib:

1. terstruktur
2. idempotent sejauh mungkin untuk data referensi awal
3. tidak merusak data operasional

Contoh:

1. role seeder
2. permission seeder
3. system setting seeder
4. master data dasar seeder

Aturan:

1. seed awal untuk production dibatasi
2. test seed dipisah dari seed operasional
3. jangan jadikan seeder alat edit data harian

## 38. Aturan Policy dan Authorization

Authorization wajib berlapis.

Aturan:

1. middleware auth dan permission di route
2. policy pada resource sensitif
3. service boleh melakukan recheck rule domain
4. view hanya menampilkan aksi yang diizinkan, tetapi bukan satu satunya kontrol

## 39. Aturan Blade Template

Blade wajib:

1. bersih
2. ringan
3. tidak berisi query database
4. tidak berisi logika bisnis panjang
5. konsisten dengan UI standard

Blade boleh memuat:

1. loop ringan
2. condition ringan untuk tampilan
3. include atau komponen
4. route helper
5. old input dan error display

Blade tidak boleh memuat:

1. DB query
2. storage decision domain
3. permission decision kompleks yang seharusnya dari backend
4. perhitungan berat

## 40. Aturan UI Component

Komponen UI harus:

1. konsisten
2. reusable
3. sederhana
4. mengikuti standard admin dashboard

Komponen yang direkomendasikan:

1. page header
2. filter bar
3. data table
4. status badge
5. flash message
6. empty state
7. confirm modal
8. detail card
9. summary card

## 41. Aturan JavaScript

JavaScript pada fase 1 harus ringan dan terukur.

Aturan:

1. gunakan hanya bila perlu
2. jangan memindahkan logika bisnis ke frontend
3. hindari framework frontend besar jika tidak menjadi bagian stack resmi
4. script untuk datatable ringan, modal, preview, dan interaksi filter diperbolehkan
5. semua permission final tetap diputuskan backend

## 42. Aturan API Response

API wajib mengikuti 20_API_CONTRACT.md.

Aturan:

1. struktur JSON konsisten
2. `success`, `message`, `data`, `meta`, `errors` dipakai sesuai kontrak
3. error code masuk secara konsisten
4. jangan kirim field sensitif tak perlu
5. public API dan internal API dipisah jelas

## 43. Aturan Storage dan File Handling

File handling wajib mengikuti storage policy.

Aturan:

1. file disimpan lewat service
2. metadata disimpan di database
3. private file tidak diberi direct path terbuka
4. file upload diverifikasi
5. temp file dibersihkan
6. replace file harus memperbarui metadata dengan benar

## 44. Aturan OCR Coding

OCR coding wajib:

1. berjalan lewat service dan job
2. tidak di controller
3. tidak memblok request web
4. menyimpan hasil ke OcrText
5. membersihkan temp file
6. memicu reindex bila perlu

## 45. Aturan Search Coding

Search coding wajib:

1. membangun dokumen index dari data MySQL
2. memisahkan public index logic dan final hydration
3. menjaga data privat tidak bocor ke publik
4. memakai service dan support class yang jelas
5. tidak menjadikan search engine source of truth

## 46. Aturan Reporting Coding

Reporting code wajib:

1. memakai report service
2. memakai query agregasi SQL
3. memakai filter tervalidasi
4. memakai pagination
5. tidak memuat query berat langsung di controller
6. tidak mengklaim metrik yang tidak didukung schema

## 47. Aturan Import Coding

Import coding wajib:

1. validasi file
2. validasi header
3. validasi per baris
4. partial success terkontrol
5. summary hasil jelas
6. audit event tercatat
7. file temp dikelola

## 48. Aturan Export Coding

Export coding wajib:

1. memakai report service
2. mengikuti filter aktif
3. format konsisten
4. file name konsisten
5. file temp dikelola
6. export besar boleh async
7. audit event tercatat

## 49. Aturan Notification Coding

Notification coding wajib:

1. mengikuti notification rules
2. tidak menggantikan audit
3. tidak menulis pesan liar di controller tanpa pola
4. email opsional harus async
5. pesan user aman dan singkat

## 50. Aturan Audit Coding

Audit coding wajib:

1. mencatat aksi sensitif yang relevan
2. menyimpan old_values dan new_values secara sanitasi
3. tidak menyimpan secret
4. tidak menyimpan payload besar tak perlu
5. dilakukan di service proses utama atau lewat audit service terpusat

## 51. Aturan Logging

Logging teknis wajib:

1. relevan
2. aman
3. cukup untuk diagnosis
4. tidak membocorkan secret

Jangan log:

1. password
2. token sensitif
3. secret env
4. full private path bila tidak perlu
5. full OCR raw text panjang

## 52. Aturan Komentar Kode

Komentar boleh dipakai bila memberi nilai.

Komentar dianjurkan untuk:

1. rule domain yang tidak langsung jelas
2. alasan keputusan teknis
3. query yang rumit
4. side effect penting
5. workaround sementara yang sah dan terdokumentasi

Komentar tidak dianjurkan untuk:

1. menjelaskan hal yang sudah jelas dari nama method
2. menutupi kode buruk
3. menjelaskan langkah sepele per baris

## 53. Aturan TODO dan FIXME

TODO dan FIXME boleh dipakai terbatas.

Aturan:

1. harus spesifik
2. harus menyebut alasan
3. jangan dibiarkan menumpuk tanpa review
4. jangan menjadi alasan meninggalkan area inti setengah jadi

## 54. Aturan Konfigurasi

Aturan penting:

1. jangan hardcode secret
2. baca nilai dari config atau env
3. domain rule inti jangan liar di env tanpa dokumentasi
4. gunakan config file untuk grouping nilai yang memang teknis

## 55. Aturan Security Coding

Security coding wajib:

1. semua input tervalidasi
2. semua route sensitif dilindungi
3. file privat aman
4. output di Blade di-escape
5. CSRF pada form admin
6. permission dan policy dijalankan
7. error aman
8. direct object access dicek

## 56. Aturan Performance Coding

Ikuti performance guide.

Aturan minimum:

1. hindari N plus 1
2. paginasi wajib pada list besar
3. OCR async
4. export besar async bila perlu
5. query report di SQL
6. search hydration seperlunya
7. jangan load relasi besar tanpa alasan

## 57. Aturan Scheduler dan Command

Console command dan scheduler task wajib:

1. punya nama jelas
2. fokus pada satu tujuan
3. aman untuk dijalankan terjadwal
4. log cukup
5. tidak membocorkan secret

Contoh:

1. sync search settings
2. full reindex
3. cleanup temp files
4. cleanup old exports

## 58. Aturan Testability

Kode harus mudah diuji.

Aturan:

1. logic domain jangan menempel di controller
2. service jangan terlalu bergantung pada state global
3. gunakan dependency yang mudah di-mock bila perlu
4. hasil method sebaiknya deterministik untuk input yang sama
5. query class dan support class boleh dipisah untuk memudahkan test

## 59. Aturan Dependency

Dependency pihak ketiga harus:

1. relevan
2. aktif dirawat
3. tidak berlebihan
4. sesuai stack resmi
5. tidak menggandakan fungsi framework tanpa manfaat jelas

## 60. Aturan Frontend Text dan Message

Teks UI wajib:

1. Bahasa Indonesia
2. singkat
3. konsisten
4. aman
5. mengikuti notification rules dan error code mapping

## 61. Aturan Empty State dan Error Page

Implementasi UI wajib:

1. punya empty state yang jelas
2. error page aman
3. tidak menampilkan detail teknis mentah
4. tetap ramah pengguna

## 62. Aturan Form Coding

Form wajib:

1. memakai request validation
2. menampilkan old input dengan aman
3. menampilkan error field yang benar
4. mempertahankan nilai filter bila relevan
5. tidak menampilkan password lama
6. tidak menampilkan field tersembunyi yang dipakai sebagai sumber otorisasi

## 63. Aturan Table Coding

Table wajib:

1. konsisten dengan UI standard
2. punya search dan filter sesuai kebutuhan
3. pagination aktif
4. action buttons mengikuti permission dan state
5. kolom tidak berlebihan

## 64. Aturan Partial dan Component Blade

Partial harus:

1. bernama jelas
2. reusable
3. tidak punya dependency tersembunyi berlebihan
4. tidak melakukan query

Contoh:

1. `_filter_form.blade.php`
2. `_summary_cards.blade.php`
3. `_status_badge.blade.php`
4. `_row_actions.blade.php`

## 65. Aturan Naming untuk File Blade Partial

Gunakan prefix konsisten untuk partial.

Rekomendasi:

1. `_form.blade.php`
2. `_filter.blade.php`
3. `_table.blade.php`
4. `_modal.blade.php`
5. `_summary.blade.php`

## 66. Aturan Service Method Naming

Nama method service harus eksplisit.

Contoh:

1. `createMember`
2. `updateMember`
3. `blockMember`
4. `createLoan`
5. `processReturn`
6. `dispatchOcr`
7. `reindexRecord`
8. `exportMemberReport`

Hindari:

1. `saveData`
2. `process`
3. `handle`
   kecuali konteks class sudah sangat jelas dan method tetap sempit

## 67. Aturan DTO dan Result Object

Untuk proses kompleks, result object dianjurkan.

Contoh:

1. `LoanTransactionResult`
2. `ImportSummaryResult`
3. `OcrResult`
4. `ReportExportResult`

Manfaat:

1. controller lebih bersih
2. hasil proses lebih eksplisit
3. mudah diuji

## 68. Aturan Nilai Konstan dan Enum

Untuk status domain, gunakan enum atau constant yang konsisten sesuai kemampuan stack dan keputusan implementasi.

Domain yang cocok:

1. publication_status
2. item_status
3. loan_status
4. fine_status
5. ocr_status
6. index_status
7. member_type

Aturan:

1. jangan tebarkan string literal status di seluruh kode
2. pusatkan nilai status agar tidak typo
3. tetap selaras dengan schema dan workflow state machine

## 69. Aturan Magic Number

Hindari magic number.

Contoh yang harus dipusatkan:

1. batas per page
2. max file size
3. max active loans default bootstrap
4. retention hours
5. OCR batch size

Sumber nilai:

1. system settings untuk rule domain operasional
2. config atau env untuk nilai teknis
3. constants atau enum untuk nilai tetap kode

## 70. Aturan Commit dan Review

Walau bukan bagian kode runtime, disiplin review penting.

Rekomendasi:

1. satu commit fokus pada satu tema
2. perubahan schema dipisah jelas
3. reviewer memeriksa naming, service boundary, validation, authorization, audit, dan error handling
4. perubahan pada route, view, controller, service, dan schema harus tetap sinkron

## 71. Aturan Refactor

Refactor diperbolehkan bila:

1. meningkatkan kejelasan
2. menurunkan duplikasi
3. memperkuat testability
4. tidak merusak blueprint

Aturan:

1. jangan refactor liar yang memutus konsistensi route map atau view map
2. refactor harus mempertahankan perilaku domain
3. jalankan regression untuk area terdampak

## 72. Anti Pattern yang Dilarang

Dilarang keras:

1. query database di Blade
2. business rule besar di controller
3. hardcoded secret
4. direct file path expose untuk private asset
5. OCR sinkron di request web normal
6. report query berat di controller
7. skip request validation
8. skip permission atau policy
9. skip audit untuk aksi sensitif
10. string literal status tersebar tanpa pusat definisi
11. mass assignment liar
12. catch exception lalu diam tanpa log atau handling jelas
13. satu service class menjadi tempat semua domain
14. satu controller sangat besar tanpa struktur
15. view yang memuat logika berat

## 73. Checklist Kualitas Kode Minimum

Sebelum kode dianggap siap review, minimal harus memenuhi:

1. naming sesuai blueprint
2. route sesuai route map
3. request validation ada
4. service layer ada untuk aksi bisnis
5. permission atau policy ada
6. error code mapping masuk akal
7. audit dipertimbangkan
8. query tidak boros
9. UI mengikuti standard
10. test minimal disiapkan untuk area penting

## 74. Hubungan dengan Testing

Kode harus ditulis dengan asumsi akan diuji.

Area yang wajib mudah diuji:

1. service domain
2. request validation
3. policy
4. query report
5. OCR dispatch
6. import parser
7. export builder
8. access control

## 75. Hubungan dengan Tree dan Traceability

Dokumen ini akan diperkuat oleh:

1. 38_TREE.md
2. 39_TRACEABILITY_MATRIX.md

Aturan:

1. struktur file implementasi harus patuh ke TREE
2. hubungan menu, route, controller, service, model, dan view harus mudah ditelusuri
3. tidak boleh ada file yang tidak punya posisi logis

## 76. Hubungan dengan Checklists

Dokumen ini menjadi acuan utama untuk:

1. 41_BACKEND_CHECKLIST.md
2. 42_FRONTEND_CHECKLIST.md
3. 43_INTEGRATION_CHECKLIST.md
4. 44_SECURITY_HARDENING_CHECKLIST.md
5. 45_SMOKE_TEST_CHECKLIST.md
6. 46_UAT_CHECKLIST.md

## 77. Prioritas Implementasi Standar Coding

Prioritas P1:

1. naming conventions
2. thin controller
3. request validation
4. service layer discipline
5. security coding minimum
6. audit discipline
7. error handling discipline

Prioritas P2:

1. query class pattern
2. result object pattern
3. component Blade discipline
4. async job separation
5. config centralization

Prioritas P3:

1. deeper refactor conventions
2. internal tooling linting tambahan
3. advanced static analysis rules

## 78. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 38_TREE.md
2. 39_TRACEABILITY_MATRIX.md
3. 41_BACKEND_CHECKLIST.md
4. 42_FRONTEND_CHECKLIST.md
5. 43_INTEGRATION_CHECKLIST.md
6. 44_SECURITY_HARDENING_CHECKLIST.md
7. 45_SMOKE_TEST_CHECKLIST.md
8. 46_UAT_CHECKLIST.md

Aturan:

1. TREE harus mencerminkan struktur coding standard ini
2. Traceability Matrix harus mengikuti naming yang ditetapkan di sini
3. Backend dan frontend checklist harus memverifikasi kepatuhan standar ini
4. security checklist harus mengecek aturan coding aman
5. smoke test dan UAT akan lebih mudah jika kode mengikuti standar ini

## 79. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. standar naming jelas
2. boundary controller, request, service, model, dan view jelas
3. aturan untuk search, OCR, storage, reporting, import export, audit, dan error sudah masuk
4. security dan performance sudah diikat ke coding practice
5. anti pattern utama sudah dilarang
6. standar tetap realistis untuk tim kecil
7. selaras dengan semua blueprint PERPUSQU sebelumnya

## 80. Kesimpulan

Dokumen Coding Standard ini menetapkan standar resmi penulisan kode PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 36. Dokumen ini memastikan bahwa seluruh implementasi backend, frontend, database, file, OCR, search, reporting, import export, security, audit, dan error handling ditulis secara rapi, stabil, aman, dan mudah dipelihara. Semua coding PERPUSQU wajib merujuk dokumen ini.

END OF 37_CODING_STANDARD.md
