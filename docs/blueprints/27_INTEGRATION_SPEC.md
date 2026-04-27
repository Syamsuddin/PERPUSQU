`
 27_INTEGRATION_SPEC.md

## 1. Nama Dokumen

Integration Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi integrasi internal, integrasi layanan pendukung, dan integrasi eksternal terbatas

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan integrasi antar modul, integrasi dengan layanan pendukung, integrasi dengan layanan infrastruktur, dan batasan integrasi eksternal fase 1

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan spesifikasi resmi integrasi pada PERPUSQU agar seluruh hubungan antar modul, antar service, antar storage, antar search engine, antar queue worker, dan antar jalur akses digital berjalan konsisten, aman, dan selaras dengan seluruh blueprint yang telah ditetapkan sebelumnya. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar integrasi yang dibangun tidak liar, tidak memotong service layer, tidak menabrak permission dan workflow, dan tidak memaksakan integrasi eksternal yang belum didukung pada fase 1.

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

Aturan wajib:

1. Semua integrasi wajib tunduk pada arsitektur monolith modular.
2. Semua integrasi wajib melewati service layer yang benar.
3. Tidak boleh ada integrasi langsung dari view ke database.
4. Tidak boleh ada integrasi eksternal yang mengabaikan keamanan, audit, dan validasi.
5. Semua integrasi fase 1 harus realistis terhadap stack dan schema yang sudah disepakati.
6. Integrasi eksternal yang belum didukung wajib dinyatakan sebagai future scope, bukan dianggap sudah tersedia.
7. Semua integrasi yang memengaruhi file, OCR, search, queue, dan reporting wajib konsisten dengan dokumen terkait.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. definisi integrasi pada PERPUSQU
2. prinsip umum integrasi
3. klasifikasi integrasi
4. integrasi antar modul internal
5. integrasi dengan layanan pendukung
6. integrasi dengan storage
7. integrasi dengan search engine
8. integrasi dengan OCR
9. integrasi dengan queue
10. integrasi dengan email opsional
11. integrasi dengan reporting dan export
12. integrasi API internal
13. integrasi publik terbatas untuk OPAC
14. integrasi eksternal fase berikutnya
15. batasan dan anti pattern integrasi
16. kebutuhan testing integrasi

## 5. Definisi Integrasi di PERPUSQU

Integrasi pada PERPUSQU berarti hubungan resmi dan terkontrol antara:

1. modul internal dalam satu aplikasi monolith
2. aplikasi dengan layanan pendukung seperti MySQL, Redis, Meilisearch, dan object storage
3. proses backend dengan worker queue
4. aplikasi dengan layanan email opsional
5. aplikasi dengan endpoint API internal atau publik terbatas
6. aplikasi dengan sistem eksternal masa depan yang belum menjadi kewajiban fase 1

Integrasi tidak berarti:

1. koneksi liar antar controller
2. query silang tanpa service
3. akses file langsung dari frontend ke storage privat
4. pemanggilan layanan eksternal tanpa batasan scope dan keamanan

## 6. Prinsip Umum Integrasi

Prinsip resmi integrasi PERPUSQU adalah:

1. modular
2. terstruktur
3. aman
4. dapat diaudit
5. mudah dipelihara
6. tidak berlebihan
7. selaras dengan scope fase 1
8. bertahap untuk perluasan masa depan

## 7. Sasaran Integrasi Fase 1

Sasaran integrasi fase 1 adalah:

1. memastikan modul inti saling terhubung dengan benar
2. memastikan file digital, OCR, search, dan reporting terhubung stabil
3. memastikan OPAC publik dapat membaca data yang tepat
4. memastikan sirkulasi memengaruhi status item dan denda dengan konsisten
5. memastikan export dan import berjalan melalui jalur resmi
6. memastikan layanan pendukung dapat dipakai tanpa memecah arsitektur monolith

## 8. Klasifikasi Integrasi Resmi

Integrasi resmi dibagi menjadi:

1. integrasi internal antar modul
2. integrasi aplikasi dengan infrastruktur pendukung
3. integrasi aplikasi dengan layanan processing
4. integrasi aplikasi dengan API internal
5. integrasi aplikasi dengan API publik terbatas
6. integrasi eksternal masa depan

## 9. Integrasi Internal Antar Modul

Integrasi internal antar modul adalah integrasi paling penting pada fase 1.

Modul inti yang saling terhubung:

1. Identity and Access
2. Core
3. Master Data
4. Catalog
5. Collection
6. Member
7. Circulation
8. Digital Repository
9. OPAC
10. Reporting
11. Audit and Monitoring
12. Profile

Aturan:

1. modul saling terhubung melalui service, model, event proses, dan query yang sah
2. controller satu modul tidak boleh menjadi jalur utama memanggil controller modul lain
3. integrasi data lintas modul wajib jelas kepemilikan datanya

## 10. Prinsip Kepemilikan Domain

Setiap modul memiliki domain utama.

### 10.1 Identity and Access

Pemilik domain:

1. user
2. role
3. permission

### 10.2 Core

Pemilik domain:

1. institution profile
2. system settings

### 10.3 Master Data

Pemilik domain:

1. author
2. publisher
3. language
4. classification
5. subject
6. collection type
7. rack location
8. faculty
9. study program
10. item condition

### 10.4 Catalog

Pemilik domain:

1. bibliographic record
2. author relation
3. subject relation

### 10.5 Collection

Pemilik domain:

1. physical item
2. item status history

### 10.6 Member

Pemilik domain:

1. member

### 10.7 Circulation

Pemilik domain:

1. loan
2. loan renewal
3. return transaction
4. fine

### 10.8 Digital Repository

Pemilik domain:

1. digital asset
2. digital asset access rule
3. OCR text

### 10.9 Reporting

Pemilik domain:

1. query agregasi
2. dashboard metrics
3. export outputs

### 10.10 Audit

Pemilik domain:

1. activity log
2. queue monitor summary, bila diaktifkan

## 11. Aturan Integrasi Antar Modul Internal

Aturan umum:

1. modul pemilik data menyediakan service domain
2. modul lain mengonsumsi service domain, bukan memanipulasi data sembarang
3. validasi format dilakukan di request
4. validasi bisnis final dilakukan di service domain
5. audit dicatat di service proses utama

## 12. Matriks Integrasi Internal Utama

| Sumber Modul | Target Modul | Tujuan Integrasi |
|---|---|---|
| Catalog | Master Data | mengambil author, subject, publisher, language, classification, collection type |
| Collection | Catalog | mengaitkan item fisik ke bibliographic record |
| Member | Master Data | mengaitkan anggota ke faculty dan study program |
| Circulation | Member | memeriksa eligibility anggota |
| Circulation | Collection | memeriksa status item fisik |
| Circulation | Catalog | menampilkan judul record dari item |
| Circulation | Core | membaca aturan operasional pinjam dan denda |
| Digital Repository | Catalog | mengaitkan asset digital ke bibliographic record |
| Digital Repository | Core | membaca setting OCR dan file |
| Digital Repository | Search | memicu reindex setelah OCR atau perubahan akses |
| OPAC | Catalog | membaca detail bibliographic record publik |
| OPAC | Collection | membaca summary ketersediaan fisik |
| OPAC | Digital Repository | membaca summary asset digital publik |
| Reporting | semua modul domain | membangun agregasi laporan |
| Audit | semua modul | mencatat aksi penting |

## 13. Integrasi Catalog dengan Master Data

Catalog harus terintegrasi dengan master data berikut:

1. author
2. publisher
3. language
4. classification
5. subject
6. collection type

Aturan:

1. create atau update record tidak boleh memakai nilai liar di luar master data kecuali memang field bebas
2. lookup master data dapat menggunakan API internal lookup
3. relasi author dan subject diproses lewat service katalog
4. perubahan author atau subject yang memengaruhi pencarian harus memicu reindex

## 14. Integrasi Collection dengan Catalog

Collection terhubung ke catalog melalui `bibliographic_record_id`.

Tujuan:

1. item fisik selalu milik satu bibliographic record
2. record dapat memiliki banyak item fisik
3. perubahan item memengaruhi summary ketersediaan pada OPAC dan search

Aturan:

1. item fisik tidak boleh berdiri tanpa record induk
2. item create dan update wajib memanggil service collection yang membaca record sah
3. perubahan status item wajib memicu status history dan reindex summary

## 15. Integrasi Member dengan Master Data

Member terhubung ke:

1. faculty
2. study program

Aturan:

1. import anggota wajib memetakan faculty_code dan study_program_code ke master data
2. anggota guest boleh tanpa relasi fakultas dan program studi
3. konsistensi fakultas dan program studi diperiksa di service member atau import service

## 16. Integrasi Circulation dengan Member

Circulation wajib memeriksa status member sebelum transaksi.

Data yang dipakai:

1. is_active
2. is_blocked
3. member_type
4. jumlah pinjaman aktif
5. aturan operasional sistem

Aturan:

1. hanya ACTIVE_READY yang boleh meminjam
2. service circulation tidak boleh hanya mengandalkan UI
3. ringkasan eligibility boleh dipanggil lewat API internal, tetapi validasi final tetap di service loan

## 17. Integrasi Circulation dengan Collection

Circulation wajib memeriksa status item fisik.

Data yang dipakai:

1. item_status
2. barcode
3. inventory_code, bila perlu
4. active loan state

Aturan:

1. hanya item AVAILABLE yang boleh dipinjam
2. saat pinjam berhasil, item berubah ke LOANED
3. saat kembali, item berubah sesuai kondisi kembali
4. perubahan status item wajib sinkron dengan state loan

## 18. Integrasi Circulation dengan Core

Circulation membaca aturan dari `system_settings`.

Setting utama:

1. loan_default_days
2. loan_max_active_loans
3. loan_max_renewal_count
4. allow_renewal
5. require_active_member
6. require_unblocked_member
7. fine_daily_amount

Aturan:

1. setting hanya dibaca dari service, bukan hardcoded di controller
2. perubahan setting harus langsung memengaruhi evaluasi proses baru
3. setting tidak mengubah histori transaksi lampau secara retroaktif kecuali ada kebijakan khusus

## 19. Integrasi Digital Repository dengan Catalog

Setiap DigitalAsset wajib terkait ke satu BibliographicRecord.

Tujuan:

1. aset digital muncul di detail koleksi
2. OPAC dapat menampilkan status digital
3. search dapat membangun summary hibrid

Aturan:

1. asset digital tidak menjadi entitas publik yang berdiri sendiri di fase 1
2. asset tanpa record induk tidak sah
3. publish asset dan access rule harus konsisten dengan record induk dan visibilitasnya

## 20. Integrasi Digital Repository dengan Storage

Digital Repository sangat tergantung pada storage policy.

Komponen yang terhubung:

1. DigitalAssetUploadService
2. private_assets storage
3. public_assets untuk cover atau derivative publik, bila ada
4. AssetStreamingService
5. DigitalAssetAccessService

Aturan:

1. file PDF utama selalu di private storage
2. preview publik tetap lewat aplikasi
3. metadata file_path tidak boleh dianggap sebagai izin akses
4. replacement file harus me-reset proses OCR dan indexing sesuai aturan

## 21. Integrasi Digital Repository dengan OCR

Setelah upload asset digital:

1. sistem memeriksa setting OCR
2. sistem memeriksa kelayakan OCR
3. bila layak, dispatch OCR job
4. hasil OCR disimpan ke OcrText
5. SearchIndexService dipicu bila perlu

Aturan:

1. OCR tidak berjalan di controller
2. OCR tidak mengganti file utama
3. hasil OCR privat tidak otomatis menjadi publik

## 22. Integrasi Digital Repository dengan Search

Digital Repository memengaruhi search melalui:

1. publication_status asset
2. is_public asset
3. is_embargoed
4. embargo_until
5. access rules
6. OCR success

Aturan:

1. perubahan asset yang memengaruhi visibilitas harus memicu reindex
2. SearchIndexService harus membangun dokumen index dari bibliographic record, bukan dari asset mentah
3. asset privat tidak boleh membocorkan OCR ke index publik

## 23. Integrasi OPAC dengan Domain Internal

OPAC adalah lapisan publik yang mengonsumsi data dari:

1. Catalog
2. Collection
3. Digital Repository
4. Core, untuk institution profile
5. Search layer

Tujuan:

1. menampilkan pencarian hibrid
2. menampilkan detail koleksi publik
3. menampilkan ketersediaan fisik
4. menampilkan preview digital publik bila sah

Aturan:

1. OPAC tidak boleh mengakses data internal sensitif
2. OPAC harus membaca lewat service publik yang sudah disaring
3. OPAC tidak boleh bypass access rule digital asset

## 24. Integrasi Reporting dengan Semua Modul Domain

Reporting terintegrasi dengan beberapa modul domain sebagai sumber data.

Sumber utama:

1. Catalog
2. Collection
3. Member
4. Circulation
5. Digital Repository
6. Core, untuk aturan presentasi tertentu

Aturan:

1. reporting tidak memiliki data master sendiri
2. reporting hanya mengonsumsi dan mengagregasi
3. rumus laporan harus selaras dengan state machine, schema, dan reporting spec

## 25. Integrasi Audit dengan Semua Modul

Audit terintegrasi secara horizontal ke semua modul.

Fungsi utama:

1. mencatat aksi penting
2. mendukung pelacakan aktivitas
3. mendukung diagnosa operasional
4. mendukung kontrol administratif

Aturan:

1. audit dicatat di service proses
2. notifikasi tidak menggantikan audit
3. event sensitif seperti block member, publish record, upload asset, run OCR, reindex, export, dan import harus dapat dicatat

## 26. Integrasi dengan MySQL

MySQL adalah sumber kebenaran utama.

Semua modul terintegrasi ke MySQL melalui:

1. model Eloquent
2. query builder
3. service layer
4. transaction database

Aturan:

1. MySQL adalah source of truth
2. layanan lain seperti search dan storage tidak boleh menjadi pengganti sumber kebenaran
3. perubahan data inti harus berkomitmen ke MySQL lebih dulu sebelum sinkronisasi layanan lain bila memungkinkan

## 27. Integrasi dengan Redis

Redis dipakai sebagai pendukung untuk:

1. queue backend
2. cache ringan
3. session optimization, bila dipilih
4. signal antar proses ringan bila diperlukan

Aturan:

1. Redis bukan sumber kebenaran bisnis
2. kegagalan Redis tidak boleh menyebabkan korupsi data MySQL
3. cache Redis harus mudah dihapus dan dibangun ulang

## 28. Integrasi dengan Queue Worker

Queue worker merupakan komponen integrasi penting untuk:

1. OCR processing
2. reindex job
3. export async, bila diaktifkan
4. email opsional async
5. housekeeping task tertentu, bila diaktifkan

Aturan:

1. semua job berat harus masuk queue
2. UI tidak menunggu job berat secara blocking
3. status proses harus bisa dibaca dari field status atau log
4. queue retry harus tercatat

## 29. Integrasi dengan Meilisearch

Meilisearch dipakai untuk:

1. OPAC search
2. search suggestion publik, bila diaktifkan
3. lookup atau internal search tertentu, bila diputuskan

Aturan:

1. dokumen index dibangun dari MySQL
2. hydration final tetap dari MySQL
3. Meilisearch tidak menyimpan data privat publik secara liar
4. perubahan record atau asset yang relevan harus memicu reindex
5. pengaturan searchable dan filterable harus mengacu dokumen 21

## 30. Integrasi dengan Object Storage

Object storage dipakai untuk:

1. logo institusi
2. cover record
3. PDF asset digital
4. file export sementara, bila dipakai di private storage
5. temp atau derived file sesuai kebijakan

Aturan:

1. public_assets dan private_assets harus dipisahkan
2. access file tidak boleh bergantung pada path mentah
3. aplikasi membangun URL atau stream secara terkontrol
4. object storage harus dapat dipakai baik di local development maupun production

## 31. Integrasi dengan Tesseract OCR

Tesseract OCR dipakai sebagai engine OCR utama fase 1.

Peran:

1. membaca raster image dari PDF scanned
2. menghasilkan extracted_text
3. membantu pencarian melalui OcrText

Aturan:

1. Tesseract dipanggil lewat service pendukung OCR
2. hasil OCR masuk ke OcrText
3. temp image diproses di local temp
4. Tesseract bukan layanan yang diakses langsung oleh controller atau view

## 32. Integrasi dengan PDF.js

PDF.js dipakai untuk:

1. preview asset digital internal
2. preview asset digital publik yang sah

Aturan:

1. PDF.js hanya viewer
2. PDF.js tidak menentukan hak akses
3. file tetap harus di-stream atau disediakan lewat jalur aman
4. preview publik harus lulus DigitalAssetAccessService

## 33. Integrasi dengan Email Opsional

Email bukan inti sistem, tetapi dapat diaktifkan secara terbatas untuk:

1. report export selesai
2. OCR failed massal, opsional
3. indexing failed massal, opsional
4. notifikasi operasional tertentu ke admin

Aturan:

1. email tidak wajib aktif saat awal go live
2. email tetap memakai queue bila async
3. kegagalan email tidak boleh merusak proses utama

## 34. Integrasi dengan API Internal

API internal resmi sudah didefinisikan pada dokumen 20.

Integrasi API internal dipakai untuk:

1. lookup master data
2. lookup bibliographic record
3. lookup members
4. item availability summary
5. member eligibility summary
6. dashboard counters ringan
7. dispatch OCR
8. dispatch reindex

Aturan:

1. API internal bukan jalur utama semua fitur
2. API internal hanya untuk kebutuhan frontend dinamis atau operasi terukur
3. API internal memakai service yang sama dengan web route

## 35. Integrasi dengan API Publik Terbatas

API publik terbatas hanya dipertimbangkan untuk:

1. OPAC suggestion
2. public record summary
3. public asset preview metadata

Aturan:

1. semua data harus publik dan aman
2. tidak boleh mengembalikan file_path privat
3. tidak boleh mengembalikan data internal
4. rate limit harus dipertimbangkan

## 36. Integrasi Import Anggota

Import anggota terintegrasi dengan:

1. Member module
2. Master Data module, untuk faculty dan study program
3. Validation rules
4. Notification rules
5. Audit log
6. temp storage untuk file import

Aturan:

1. import hanya untuk anggota
2. import memakai MemberImportService
3. import tidak boleh langsung menulis lewat controller
4. file import bersifat sementara
5. hasil import harus punya summary sukses dan gagal

## 37. Integrasi Export Laporan

Export laporan terintegrasi dengan:

1. Reporting services
2. Notification rules
3. temp export storage
4. optional email
5. audit log
6. permission reports.export

Aturan:

1. export harus memakai filter aktif
2. export tidak boleh memuat data di luar laporan
3. export dapat sinkron atau async
4. file export harus dikelola sesuai storage policy

## 38. Integrasi Notification dengan Modul Lain

Notification terintegrasi dengan:

1. validation
2. workflow state
3. dashboard alerts
4. OCR and indexing events
5. circulation process
6. import and export process

Aturan:

1. notifikasi adalah umpan balik, bukan penyimpan histori utama
2. notifikasi harus selaras dengan role
3. notifikasi publik tidak boleh membocorkan data internal

## 39. Integrasi Security dengan Semua Layanan

Semua integrasi wajib tunduk pada kebijakan keamanan.

Area yang wajib dijaga:

1. auth dan permission
2. private file access
3. API internal
4. API publik
5. storage secrets
6. queue dan worker
7. export file access
8. temp file handling

Aturan:

1. tidak ada integrasi yang berjalan tanpa kontrol permission bila menyentuh data internal
2. kredensial layanan eksternal harus di env, bukan hardcoded
3. access token dan credential tidak boleh tampil di UI atau log biasa

## 40. Integrasi Deployment dan Environment

Integrasi layanan bergantung pada environment configuration.

Environment utama:

1. APP
2. MySQL
3. Redis
4. Meilisearch
5. S3 compatible storage atau MinIO
6. OCR engine
7. Mail, opsional

Aturan:

1. semua endpoint, host, key, dan secret harus berasal dari environment
2. konfigurasi per environment harus konsisten
3. local, staging, dan production dapat berbeda host tetapi tidak beda prinsip integrasi

## 41. Integrasi yang Wajib pada Fase 1

Integrasi berikut wajib tersedia secara nyata pada fase 1:

1. Laravel ke MySQL
2. Laravel ke Redis
3. Laravel ke queue worker
4. Laravel ke object storage
5. Laravel ke Meilisearch
6. Laravel ke Tesseract OCR
7. Laravel ke PDF.js viewer
8. antar modul internal melalui service layer

## 42. Integrasi yang Opsional pada Fase 1

Integrasi berikut opsional pada fase 1:

1. email async
2. public API suggestion
3. export async
4. dashboard counters via internal API
5. report export history persisten

## 43. Integrasi yang Tidak Termasuk Fase 1

Integrasi berikut tidak termasuk fase 1 dan tidak boleh diasumsikan sudah ada:

1. SSO kampus
2. sinkronisasi otomatis SIAKAD
3. sinkronisasi LDAP
4. RFID gate
5. RFID tag reader
6. payment gateway untuk denda
7. WhatsApp gateway
8. SMS gateway
9. DSpace integration
10. PMB atau SIM akademik real time integration
11. Dukcapil integration
12. mobile app backend dedicated
13. public open API penuh

## 44. Rencana Integrasi Eksternal Masa Depan

Walau tidak wajib di fase 1, blueprint ini menyiapkan arah masa depan.

### 44.1 Integrasi SIAKAD

Potensi:

1. sinkron anggota mahasiswa dan dosen
2. sinkron fakultas dan program studi
3. sinkron identitas akademik

Status:

1. future scope
2. tidak diaktifkan pada fase 1

### 44.2 Integrasi SSO

Potensi:

1. login tunggal untuk pengguna internal kampus

Status:

1. future scope

### 44.3 Integrasi RFID

Potensi:

1. identifikasi item fisik
2. inventaris cepat
3. sirkulasi berbasis RFID

Status:

1. future scope

### 44.4 Integrasi Repositori Eksternal

Potensi:

1. sinkron metadata karya ilmiah
2. federasi akses repositori

Status:

1. future scope

## 45. Integrasi Event dan Trigger

Trigger integrasi utama pada fase 1:

1. create or update bibliographic record -> reindex
2. change physical item status -> reindex summary
3. upload digital asset -> OCR and reindex decision
4. OCR success -> reindex
5. publish or unpublish asset -> reindex
6. update access rule -> reindex
7. import anggota -> member domain write and audit
8. export laporan -> reporting and storage

Aturan:

1. trigger tidak boleh ditaruh liar di controller
2. service utama menjadi pemilik trigger domain
3. job queue digunakan untuk proses berat

## 46. Kontrak Integrasi Antar Service

Integrasi antar service harus sederhana dan jelas.

Contoh:

1. LoanTransactionService memanggil MemberBlockingService atau membaca MemberService
2. LoanTransactionService memanggil PhysicalItemService atau PhysicalItemStatusService
3. ReturnProcessingService memanggil FineCalculationService
4. DigitalAssetService memanggil SearchIndexService
5. OcrProcessingService memanggil SearchIndexService
6. ReportExportService memanggil report service spesifik

Aturan:

1. service tidak saling mengunci membabi buta
2. hindari circular dependency
3. bila integrasi terlalu kompleks, ekstrak helper atau domain support service

## 47. Aturan Transaction Boundary

Beberapa integrasi memerlukan boundary transaksi yang jelas.

### 47.1 Wajib Transaction Database

1. create loan dan ubah item status
2. return loan, create return transaction, create fine bila perlu, ubah item status
3. create digital asset metadata setelah file tersimpan
4. import anggota per baris atau per batch kecil sesuai strategi

### 47.2 Tidak Wajib Satu Transaksi Penuh

1. OCR processing end to end
2. reindex Meilisearch
3. email send
4. async export

Aturan:

1. proses non database external boleh eventual consistent
2. status harus mencerminkan jika proses lanjutan gagal

## 48. Error Handling Integrasi

Jika satu layanan pendukung gagal:

### 48.1 MySQL gagal

1. proses inti gagal
2. user menerima pesan aman
3. log teknis dicatat

### 48.2 Storage gagal

1. upload atau stream gagal aman
2. metadata tidak boleh dianggap sukses bila file belum aman

### 48.3 Meilisearch gagal

1. data inti tetap disimpan di MySQL
2. reindex ditandai failed atau pending
3. pencarian dapat terpengaruh sementara

### 48.4 OCR gagal

1. asset utama tetap ada
2. OCR status menjadi failed
3. metadata katalog tetap tersedia

### 48.5 Email gagal

1. proses utama tetap dianggap sukses bila domain utama sudah selesai
2. log teknis dicatat
3. email dapat di-retry

## 49. Monitoring Integrasi

Monitoring minimum yang direkomendasikan:

1. queue health
2. OCR failure count
3. index failure count
4. export failure count
5. storage file not found count, bila diaktifkan
6. API internal failure rate, bila perlu

Sumber monitoring:

1. Laravel Horizon
2. Laravel Telescope
3. Laravel Pulse
4. dashboard internal ringan
5. activity logs
6. application logs

## 50. Konfigurasi Integrasi yang Direkomendasikan

Konfigurasi utama yang harus tersedia di environment:

1. database host, port, database, username, password
2. redis host, port, password
3. meilisearch host, key
4. storage endpoint, bucket, key, secret, region
5. OCR binary path atau OCR command config
6. mail host, port, username, password, from address, bila dipakai
7. queue connection

Aturan:

1. semua secret di env
2. tidak ada hardcode credential
3. local dummy config boleh berbeda, tetapi struktur env tetap sama

## 51. Testing Requirement Integrasi

Pengujian minimum integrasi wajib mencakup:

1. create loan mengubah loan dan item status secara konsisten
2. return loan membuat return transaction dan memperbarui item status
3. update catalog memicu reindex
4. upload asset memicu metadata save dan keputusan OCR
5. OCR success memicu save OcrText dan reindex
6. asset private tidak preview publik
7. OPAC hanya menampilkan record publik
8. import anggota memetakan faculty dan study program dengan benar
9. export laporan mengikuti filter aktif
10. dashboard membaca angka yang konsisten dari domain terkait
11. queue retry tidak merusak state domain
12. integrasi storage gagal ditangani aman
13. integrasi search gagal tidak merusak data inti di MySQL
14. notification message tetap konsisten pada failure integration

## 52. Anti Pattern yang Dilarang

Integrasi tidak boleh:

1. memanggil database langsung dari view
2. memanggil controller dari controller lain sebagai jalur bisnis utama
3. membocorkan private storage path ke frontend
4. menjadikan search engine sebagai source of truth
5. menjalankan OCR di request web sinkron panjang
6. mengirim email langsung dari controller tanpa service
7. menulis data import massal tanpa validasi per baris
8. membuat circular dependency antar service
9. mengaktifkan integrasi eksternal yang belum punya blueprint dan security rule
10. mencampur data publik dan privat pada API publik

## 53. Prioritas Implementasi Integrasi

### Prioritas P1

1. integrasi antar modul internal
2. integrasi MySQL
3. integrasi Redis queue
4. integrasi object storage
5. integrasi Meilisearch
6. integrasi OCR
7. integrasi OPAC ke search dan detail domain
8. integrasi reporting dan export

### Prioritas P2

1. integrasi email opsional
2. internal API lookup lebih lengkap
3. export async
4. monitoring dashboard ringan

### Prioritas P3

1. SIAKAD future integration
2. SSO future integration
3. RFID future integration
4. external repository future integration

## 54. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 28_SECURITY_POLICY.md
2. 29_AUDIT_LOG_SPEC.md
3. 30_ERROR_CODE.md
4. 31_TEST_PLAN.md
5. 32_TEST_SCENARIO.md
6. 33_DEPLOYMENT_GUIDE.md
7. 34_ENV_CONFIGURATION.md
8. 35_BACKUP_AND_RECOVERY.md
9. 36_PERFORMANCE_GUIDE.md
10. 38_TREE.md
11. 39_TRACEABILITY_MATRIX.md
12. 41_BACKEND_CHECKLIST.md
13. 42_FRONTEND_CHECKLIST.md
14. 45_SMOKE_TEST_CHECKLIST.md
15. 46_UAT_CHECKLIST.md

Aturan:

1. security policy harus mengamankan semua titik integrasi
2. audit log spec harus menangkap event integrasi penting
3. deployment guide harus menjelaskan konfigurasi semua layanan pendukung
4. env configuration harus memetakan semua variable integrasi
5. performance guide harus mempertimbangkan queue, search, storage, dan OCR
6. traceability matrix harus memetakan setiap integrasi kritis ke service, route, dan tabel terkait

## 55. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. semua integrasi wajib fase 1 sudah terpetakan
2. batas integrasi eksternal fase 1 sudah jujur dan jelas
3. hubungan antar modul internal selaras dengan arsitektur modular
4. integrasi OCR, storage, search, queue, dan reporting sudah sesuai dokumen terkait
5. tidak ada integrasi yang membypass service layer
6. data publik dan privat sudah dibedakan jelas
7. semua layanan pendukung yang dipilih sesuai stack teknologi resmi

## 56. Kesimpulan

Dokumen Integration Specification ini menetapkan fondasi integrasi resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 26. Dokumen ini memastikan bahwa modul internal, storage, OCR, search, queue, reporting, API internal, OPAC publik, dan layanan pendukung lain saling terhubung melalui jalur yang aman, jelas, dan terukur, tanpa menyimpang dari prinsip monolith modular yang telah disepakati. Semua implementasi integrasi PERPUSQU wajib merujuk dokumen ini.

END OF 27_INTEGRATION_SPEC.md
