# 40_PAGE_BUILD_PRIORITY.md

## 1. Nama Dokumen

Page Build Priority Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint prioritas pembangunan halaman dan urutan implementasi UI aplikasi

### 2.3 Status Dokumen

Resmi, acuan wajib urutan implementasi page, modul UI, dan alur pembangunan frontend backend terhubung

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan prioritas resmi pembangunan halaman PERPUSQU agar AI Agent, developer, reviewer, dan tester memiliki urutan kerja yang jelas saat membangun aplikasi. Dokumen ini memastikan halaman yang dibuat lebih dulu adalah halaman yang paling penting untuk login, role access, master data dasar, katalog, item fisik, anggota, sirkulasi, asset digital, OPAC, reporting, audit, dan profile, sesuai seluruh blueprint yang sudah disusun sebelumnya. Dokumen ini juga mencegah terjadinya halaman yatim, halaman tanpa backend, halaman tanpa permission, atau halaman yang dibangun terlalu dini padahal modul dasarnya belum siap.

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
37. 37_CODING_STANDARD.md
38. 38_TREE.md
39. 39_TRACEABILITY_MATRIX.md

Aturan wajib:

1. Urutan pembangunan halaman harus mengikuti dependency domain.
2. Halaman tidak boleh dibangun lebih dulu jika backend inti yang dibutuhkannya belum ada.
3. Halaman write action wajib dibangun bersama request validation, service, permission, audit, dan error handling terkait.
4. Halaman publik OPAC tidak boleh dibangun final tanpa public visibility rule yang jelas.
5. Halaman reporting tidak boleh didahulukan dari data source inti yang menjadi dasarnya.
6. Halaman audit dan export tidak boleh didahulukan dari proses domain yang menghasilkan data audit dan report.
7. Prioritas dokumen ini menjadi acuan backend checklist, frontend checklist, smoke test, dan UAT.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip prioritas halaman
2. klasifikasi prioritas
3. dependency antar halaman
4. tahap pembangunan page
5. daftar page per tahap
6. page yang boleh paralel
7. page yang harus serial
8. urutan integrasi frontend dan backend
9. urutan build untuk AI Agent
10. urutan build untuk tim kecil
11. daftar page yang kritis untuk smoke test
12. daftar page yang kritis untuk UAT

## 5. Prinsip Umum Prioritas Page

Prinsip resmi prioritas pembangunan halaman PERPUSQU adalah:

1. bangun fondasi dulu
2. bangun halaman operasional inti lebih dulu
3. bangun halaman publik setelah data publik siap
4. bangun report setelah transaksi inti siap
5. bangun halaman audit setelah aksi sensitif sudah menghasilkan jejak
6. bangun page pelengkap paling akhir
7. hindari membangun banyak halaman cantik tanpa alur backend yang berfungsi

## 6. Tujuan Prioritas Pembangunan

Urutan prioritas ini bertujuan untuk:

1. memastikan tim fokus ke nilai bisnis tertinggi lebih dulu
2. memastikan coding lebih mudah diuji bertahap
3. memastikan UI tidak mendahului domain model
4. memastikan smoke test awal cepat dilakukan
5. memastikan UAT dapat dimulai lebih cepat untuk alur inti
6. mengurangi risiko broken link antar page

## 7. Definisi Page dalam Dokumen Ini

Yang dimaksud page dalam dokumen ini meliputi:

1. halaman penuh admin
2. halaman penuh publik OPAC
3. halaman list
4. halaman form create edit
5. halaman detail
6. halaman preview khusus
7. halaman report
8. halaman audit
9. halaman autentikasi
10. halaman error khusus

Tidak semua modal kecil diperlakukan sebagai page mandiri, tetapi modal yang kritis tetap dihitung dalam dependency halaman.

## 8. Klasifikasi Prioritas Resmi

Prioritas dibagi menjadi:

1. P0, fondasi teknis page
2. P1, page inti wajib ada agar sistem hidup
3. P2, page operasional utama fase 1
4. P3, page pendukung penting
5. P4, page pelengkap dan penyempurna

## 9. Definisi P0

P0 adalah fondasi yang harus siap sebelum membangun page operasional.

Cakupan:

1. layout admin
2. layout auth
3. layout OPAC
4. login page
5. dashboard minimal
6. sidebar role based
7. flash message component
8. empty state component
9. status badge component
10. page header component
11. error pages dasar

## 10. Definisi P1

P1 adalah page minimum yang harus ada agar sistem bisa dipakai untuk uji alur inti.

Cakupan utama:

1. login
2. dashboard admin
3. user management dasar
4. role permission dasar
5. institution profile
6. system settings
7. master data inti
8. bibliographic record list create edit
9. physical item list create edit
10. member list create edit
11. loan return renew operasional
12. digital asset list create edit preview privat
13. OPAC home search detail
14. report dashboard inti

## 11. Definisi P2

P2 adalah page penting yang melengkapi proses inti fase 1.

Cakupan:

1. import anggota
2. access rule asset
3. OCR status detail
4. queue monitor ringkas
5. report report rinci
6. export actions penuh
7. audit log list dan detail
8. profile user
9. public asset preview flow final

## 12. Definisi P3

P3 adalah page pendukung penting setelah inti stabil.

Cakupan:

1. help page OPAC
2. about page OPAC
3. master data detail yang jarang dipakai
4. popular collection report refinement
5. digital repository report refinement
6. additional filters and quality pages

## 13. Definisi P4

P4 adalah page pelengkap dan penyempurna.

Cakupan:

1. micro refinement UI
2. additional insight pages non critical
3. visual polish lanjutan
4. optional monitor pages bila diperlukan

## 14. Aturan Dependency Page

Sebuah page hanya boleh dibangun jika dependency utamanya siap.

Dependency minimum:

1. route ada
2. controller ada
3. request validation ada untuk write page
4. service ada
5. model dan tabel siap
6. permission atau policy siap
7. view map siap
8. traceability item ada

## 15. Dependency Antar Modul

Dependency domain utama:

1. login dan access dulu sebelum page admin lain
2. core settings dulu sebelum circulation dan OCR rule final
3. master data dulu sebelum catalog, member, dan collection
4. catalog dulu sebelum physical item dan digital asset
5. member dan collection dulu sebelum circulation
6. digital asset dulu sebelum OCR detail dan preview publik
7. data transaksi dulu sebelum reporting
8. domain action dulu sebelum audit list bermakna

## 16. Strategi Pembangunan Umum

Strategi yang direkomendasikan:

1. bangun vertical slice kecil tetapi utuh
2. selesaikan page inti satu modul sampai usable
3. lalu lanjut ke modul berikutnya
4. setelah domain inti hidup, bangun report dan audit
5. setelah itu baru page pelengkap publik lanjutan

## 17. Tahap Pembangunan Resmi

Tahap resmi yang direkomendasikan:

1. Tahap 0, fondasi UI dan auth
2. Tahap 1, core dan access admin
3. Tahap 2, master data inti
4. Tahap 3, catalog
5. Tahap 4, collection
6. Tahap 5, member
7. Tahap 6, circulation
8. Tahap 7, digital repository
9. Tahap 8, OPAC publik
10. Tahap 9, reporting
11. Tahap 10, import export dan audit
12. Tahap 11, profile, help, dan final polish

## 18. Tahap 0, Fondasi UI dan Auth

Page yang wajib dibangun pada tahap ini:

1. auth/login.blade.php
2. layouts/auth.blade.php
3. layouts/admin.blade.php
4. layouts/opac.blade.php
5. components/admin/_flash_message.blade.php
6. components/admin/_page_header.blade.php
7. components/admin/_empty_state.blade.php
8. components/admin/_status_badge.blade.php
9. errors/403.blade.php
10. errors/404.blade.php
11. errors/419.blade.php
12. errors/429.blade.php
13. errors/500.blade.php

Tujuan:

1. aplikasi bisa login
2. admin layout siap
3. OPAC layout siap
4. komponen dasar UI siap dipakai lintas modul

Smoke value:
tinggi

UAT value:
sedang

## 19. Tahap 1, Core dan Access Admin

Page prioritas:

1. modules/core/dashboard/index.blade.php
2. modules/identity/users/index.blade.php
3. modules/identity/users/create.blade.php
4. modules/identity/users/edit.blade.php
5. modules/identity/users/show.blade.php
6. modules/identity/roles/index.blade.php
7. modules/identity/roles/create.blade.php
8. modules/identity/roles/edit.blade.php
9. modules/core/institution_profile/edit.blade.php
10. modules/core/system_settings/edit.blade.php

Urutan build yang direkomendasikan:

1. dashboard
2. users index
3. users create edit
4. roles index
5. roles edit permission
6. institution profile
7. system settings

Alasan:

1. role access harus siap sebelum page lain dibuka bertahap
2. system settings memengaruhi banyak modul berikutnya
3. dashboard memberi titik masuk sistem

## 20. Tahap 2, Master Data Inti

Page prioritas:

1. modules/master_data/authors/index.blade.php
2. modules/master_data/publishers/index.blade.php
3. modules/master_data/languages/index.blade.php
4. modules/master_data/classifications/index.blade.php
5. modules/master_data/subjects/index.blade.php
6. modules/master_data/collection_types/index.blade.php
7. modules/master_data/rack_locations/index.blade.php
8. modules/master_data/faculties/index.blade.php
9. modules/master_data/study_programs/index.blade.php

Urutan build yang direkomendasikan:

1. authors
2. subjects
3. publishers
4. classifications
5. languages
6. collection types
7. rack locations
8. faculties
9. study programs

Alasan:

1. catalog sangat bergantung pada authors, subjects, publisher, classification, language, collection type
2. member bergantung pada faculties dan study programs
3. collection bergantung pada rack locations

## 21. Tahap 3, Catalog

Page prioritas:

1. modules/catalog/records/index.blade.php
2. modules/catalog/records/create.blade.php
3. modules/catalog/records/edit.blade.php
4. modules/catalog/records/show.blade.php
5. modules/catalog/records/_form.blade.php
6. modules/catalog/records/_table.blade.php

Urutan build yang direkomendasikan:

1. index
2. create
3. edit
4. show
5. publish unpublish archive actions pada index dan show

Alasan:

1. catalog adalah pusat hubungan item fisik dan asset digital
2. tanpa record, item fisik dan asset digital tidak punya induk
3. OPAC publik juga bergantung pada data catalog

## 22. Tahap 4, Collection

Page prioritas:

1. modules/collection/items/index.blade.php
2. modules/collection/items/create.blade.php
3. modules/collection/items/edit.blade.php
4. modules/collection/items/show.blade.php
5. modules/collection/items/_status_modal.blade.php

Urutan build yang direkomendasikan:

1. index
2. create
3. edit
4. status change modal
5. show

Alasan:

1. circulation tidak bisa jalan tanpa item fisik
2. item status adalah bagian penting alur pinjam dan kembali

## 23. Tahap 5, Member

Page prioritas:

1. modules/member/members/index.blade.php
2. modules/member/members/create.blade.php
3. modules/member/members/edit.blade.php
4. modules/member/members/show.blade.php
5. modules/member/members/_block_modal.blade.php

Urutan build yang direkomendasikan:

1. index
2. create
3. edit
4. block unblock modal
5. show

Alasan:

1. circulation memerlukan member valid
2. import anggota baru efektif jika page manual sudah jelas lebih dulu

## 24. Tahap 6, Circulation

Page prioritas operasional:

1. page list pinjaman operasional
2. page atau panel peminjaman baru
3. page atau panel pengembalian
4. action renew
5. list denda operasional dasar

Catatan penting:

1. pada TREE saat ini belum dibuat folder view modul sirkulasi terpisah
2. implementasi final sangat dianjurkan menambahkan folder:
   1. resources/views/modules/circulation/loans
   2. resources/views/modules/circulation/returns
   3. resources/views/modules/circulation/fines
3. ini bukan konflik, tetapi penyempurnaan yang logis dari kebutuhan operasional dan traceability

Struktur view yang direkomendasikan untuk coding:

1. modules/circulation/loans/index.blade.php
2. modules/circulation/loans/create.blade.php
3. modules/circulation/loans/_form.blade.php
4. modules/circulation/returns/index.blade.php
5. modules/circulation/returns/_form.blade.php
6. modules/circulation/fines/index.blade.php

Urutan build yang direkomendasikan:

1. peminjaman
2. pengembalian
3. renew
4. fines list

Alasan:

1. ini adalah inti operasional perpustakaan
2. smoke test sistem sangat bergantung pada alur ini

## 25. Tahap 7, Digital Repository

Page prioritas:

1. modules/digital_repository/assets/index.blade.php
2. modules/digital_repository/assets/create.blade.php
3. modules/digital_repository/assets/edit.blade.php
4. modules/digital_repository/assets/show.blade.php
5. modules/digital_repository/assets/preview.blade.php
6. modules/digital_repository/assets/_access_rule_form.blade.php
7. modules/digital_repository/assets/_ocr_status_card.blade.php

Urutan build yang direkomendasikan:

1. index
2. create
3. edit
4. show
5. preview privat
6. access rule form
7. OCR status action
8. reindex action

Alasan:

1. digital repository adalah inti konsep perpustakaan hibrid
2. tetapi dibangun setelah catalog karena butuh induk record
3. dibangun setelah auth, master, dan catalog agar flow lengkap

## 26. Tahap 8, OPAC Publik

Page prioritas:

1. opac/home.blade.php
2. opac/search/index.blade.php
3. opac/records/show.blade.php
4. public asset preview flow
5. components/opac/_record_card.blade.php
6. components/opac/_search_filter_bar.blade.php
7. components/opac/_search_empty_state.blade.php

Urutan build yang direkomendasikan:

1. home
2. search result
3. detail record
4. public preview
5. suggestion publik bila diaktifkan

Alasan:

1. OPAC publik harus dibangun setelah record publik dan asset publik sudah bisa disaring dengan aman
2. jika dibangun terlalu dini, risiko kebocoran visibilitas lebih tinggi

## 27. Tahap 9, Reporting

Page prioritas:

1. modules/reporting/dashboard.blade.php
2. modules/reporting/collections/index.blade.php
3. modules/reporting/members/index.blade.php
4. modules/reporting/circulation/index.blade.php
5. modules/reporting/fines/index.blade.php
6. modules/reporting/popular_collections/index.blade.php
7. modules/reporting/digital_access/index.blade.php

Urutan build yang direkomendasikan:

1. dashboard
2. circulation report
3. member report
4. collection report
5. fine report
6. popular collection
7. digital access

Alasan:

1. dashboard dan circulation report paling bernilai untuk operasional
2. digital access report bergantung pada asset dan OCR yang lebih matang

## 28. Tahap 10, Import, Export, dan Audit

Page prioritas:

1. modules/member/imports/index.blade.php
2. report export action pada semua report page
3. modules/audit/logs/index.blade.php
4. modules/audit/logs/show.blade.php

Urutan build yang direkomendasikan:

1. import anggota
2. export report
3. audit log list
4. audit detail

Alasan:

1. import anggota membantu bootstrap data
2. export report dibutuhkan setelah report inti hidup
3. audit page baru bermakna setelah banyak aksi sensitif sudah tercatat

## 29. Tahap 11, Profile dan Penyempurnaan

Page prioritas:

1. modules/profile/edit.blade.php
2. opac/about.blade.php
3. opac/help.blade.php
4. queue monitor page bila diputuskan ditampilkan
5. polish error pages
6. polish responsive states

Urutan build yang direkomendasikan:

1. profile
2. help page
3. about page
4. monitor page opsional
5. final polish

Alasan:

1. value operasionalnya lebih rendah dibanding page inti
2. cocok dibangun setelah inti stabil

## 30. Daftar Page P1 Wajib Ada Sebelum UAT Awal

Page P1 minimum:

1. login
2. dashboard admin
3. users index
4. roles index edit
5. institution profile edit
6. system settings edit
7. authors index
8. subjects index
9. publishers index
10. classifications index
11. languages index
12. collection types index
13. rack locations index
14. faculties index
15. study programs index
16. catalog record index
17. catalog record create
18. catalog record edit
19. collection item index
20. collection item create
21. member index
22. member create
23. loan operasional page
24. return operasional page
25. digital asset index
26. digital asset create
27. digital asset preview privat
28. OPAC home
29. OPAC search
30. OPAC record detail
31. report dashboard
32. circulation report

## 31. Daftar Page P2 Wajib Ada Sebelum Go Live Penuh Fase 1

Page P2 minimum:

1. member import page
2. digital asset show
3. digital asset access rule form
4. digital asset OCR status card
5. OPAC public preview flow
6. member report
7. collection report
8. fine report
9. popular collection report
10. digital access report
11. audit log index
12. audit log detail
13. profile edit

## 32. Daftar Page P3 dan P4

Page pelengkap:

1. OPAC about
2. OPAC help
3. additional queue monitor UI
4. page minor support lainnya
5. refinement partial dan summary page

## 33. Urutan Pembangunan untuk AI Agent

Urutan kerja AI Agent yang direkomendasikan:

1. bangun layout dan auth
2. bangun identity dan core
3. bangun master data
4. bangun catalog
5. bangun collection
6. bangun member
7. bangun circulation
8. bangun digital repository
9. bangun OPAC
10. bangun reporting
11. bangun import export
12. bangun audit
13. bangun profile dan final polish

Aturan:

1. setiap batch harus selesai sampai usable
2. jangan lompat ke report jika transaksi inti belum hidup
3. jangan lompat ke OPAC final jika publish dan visibility belum aman

## 34. Urutan Pembangunan untuk Tim Kecil

Jika tim kecil terdiri dari 2 sampai 4 orang, pembagian yang disarankan:

### Jalur A

1. auth
2. identity
3. core
4. master data

### Jalur B

1. catalog
2. collection
3. member

### Jalur C

1. circulation
2. digital repository
3. OPAC

### Jalur D

1. reporting
2. import export
3. audit
4. profile dan polish

Aturan:

1. Jalur C tidak boleh final sebelum Jalur B cukup matang
2. Jalur D tidak boleh final sebelum data domain inti hidup

## 35. Page yang Boleh Dibangun Paralel

Page yang boleh paralel setelah fondasi siap:

1. master data authors, subjects, publishers, languages, classifications
2. faculties dan study programs
3. users dan roles
4. collection item page dan member page
5. collection report dan member report, setelah data dasarnya siap
6. OPAC about dan help, pada tahap akhir

## 36. Page yang Harus Dibangun Serial

Page yang sebaiknya serial:

1. login sebelum seluruh admin page
2. role permission sebelum verifikasi akses modul lain
3. catalog sebelum item fisik
4. catalog sebelum digital asset
5. member dan item sebelum circulation
6. asset publish dan access rule sebelum public preview
7. domain data dulu sebelum report
8. action domain dulu sebelum audit page

## 37. Dependency Page Detail per Modul

### Identity

1. users index sebelum create edit lebih ideal
2. roles index sebelum edit permission

### Core

1. dashboard bisa dibangun cepat
2. system settings harus tersedia sebelum uji circulation dan OCR final

### Catalog

1. create edit dulu
2. show dan publish action berikutnya
3. OPAC bergantung pada publish

### Collection

1. item create edit dulu
2. status change berikutnya
3. circulation bergantung padanya

### Member

1. create edit dulu
2. block unblock berikutnya
3. circulation bergantung padanya

### Digital

1. create asset dulu
2. preview privat berikutnya
3. access rule dan OCR berikutnya
4. preview publik paling akhir

## 38. Matriks Prioritas per Modul

| Modul | Prioritas Modul | Alasan |
|---|---|---|
| Auth | P0 | titik masuk sistem |
| Identity and Access | P1 | dasar kontrol seluruh halaman |
| Core | P1 | pengaturan sistem dan identitas perpustakaan |
| Master Data | P1 | fondasi katalog, member, dan collection |
| Catalog | P1 | pusat data bibliografis |
| Collection | P1 | syarat sirkulasi |
| Member | P1 | syarat sirkulasi |
| Circulation | P1 | inti operasional |
| Digital Repository | P1 | inti perpustakaan hibrid |
| OPAC | P2 | penting publik, tetapi bergantung data aman |
| Reporting | P2 | penting manajerial, bergantung transaksi dan data inti |
| Import | P2 | penting percepatan operasional |
| Export | P2 | penting administrasi |
| Audit | P2 | penting pengawasan setelah aksi domain hidup |
| Profile | P3 | pendukung |
| OPAC Help About | P3 | pelengkap publik |

## 39. Matriks Prioritas per Jenis Page

| Jenis Page | Prioritas |
|---|---|
| Login | P0 |
| Dashboard admin | P1 |
| User and role management | P1 |
| Core settings | P1 |
| Master data CRUD | P1 |
| Catalog CRUD | P1 |
| Collection CRUD | P1 |
| Member CRUD | P1 |
| Circulation operational pages | P1 |
| Digital asset CRUD dan preview privat | P1 |
| OPAC home search detail | P2 |
| Reporting pages | P2 |
| Import export pages | P2 |
| Audit pages | P2 |
| Profile page | P3 |
| Help about public pages | P3 |

## 40. Kriteria Selesai per Page

Sebuah page dianggap selesai bila:

1. route berfungsi
2. permission bekerja
3. view tampil benar
4. form validation bekerja
5. service proses berjalan
6. error handling aman
7. audit tercatat bila sensitif
8. test scenario utama lulus
9. tidak ada broken link
10. UI sesuai standar

## 41. Kriteria Selesai per Tahap

Sebuah tahap dianggap selesai bila:

1. seluruh page pada tahap itu bisa dibuka
2. seluruh aksi inti tahap itu berjalan
3. role access untuk page tahap itu benar
4. page tidak hanya tampil, tetapi usable
5. skenario P1 pada tahap itu lulus

## 42. Risiko Jika Urutan Tidak Dipatuhi

Risiko utama:

1. page yatim tanpa backend
2. UI jadi banyak tetapi data inti belum ada
3. report dibangun dari data yang belum stabil
4. OPAC bocor data non publik
5. audit page kosong dan menyesatkan
6. test menjadi sulit karena dependency belum matang
7. AI Agent membuat file berulang dan tidak sinkron

## 43. Mitigasi Risiko

Mitigasi yang disarankan:

1. selalu cek traceability matrix sebelum membangun page
2. selalu cek tree resmi sebelum membuat file
3. bangun per batch kecil tetapi selesai
4. jalankan smoke test mini setiap selesai tahap
5. jangan pindah tahap jika tahap sebelumnya belum usable

## 44. Hubungan dengan Smoke Test

Page yang wajib masuk smoke test awal:

1. login
2. dashboard
3. author index
4. catalog create
5. item create
6. member create
7. loan page
8. return page
9. digital asset create
10. OPAC search
11. report dashboard

## 45. Hubungan dengan UAT

Page yang wajib siap sebelum UAT inti:

1. catalog create publish
2. item create status
3. member create block unblock
4. loan return renew
5. digital asset upload preview OCR
6. OPAC search detail preview
7. dashboard report
8. member report atau circulation report
9. export minimal satu report

## 46. Urutan Build Berdasarkan Nilai Bisnis

Jika fokus nilai bisnis tercepat, urutan yang disarankan:

1. auth
2. master data inti
3. catalog
4. item fisik
5. member
6. circulation
7. digital asset
8. OPAC
9. reporting
10. import export
11. audit

## 47. Urutan Build Berdasarkan Risiko Teknis

Jika fokus risiko teknis tertinggi dulu, urutan yang disarankan:

1. auth dan access
2. system settings
3. catalog publish flow
4. item status flow
5. member block flow
6. circulation flow
7. digital asset private preview
8. OCR dispatch
9. OPAC visibility
10. export safe file
11. audit page

## 48. Rekomendasi Sprint atau Batch Build

Contoh batch kerja yang direkomendasikan:

### Batch 1

1. login
2. dashboard
3. users
4. roles
5. institution profile
6. system settings

### Batch 2

1. authors
2. subjects
3. publishers
4. classifications
5. languages
6. collection types
7. rack locations
8. faculties
9. study programs

### Batch 3

1. catalog index create edit show
2. publish unpublish archive

### Batch 4

1. collection item pages
2. member pages

### Batch 5

1. circulation pages

### Batch 6

1. digital repository pages
2. preview privat
3. OCR actions

### Batch 7

1. OPAC home
2. OPAC search
3. OPAC detail
4. public preview

### Batch 8

1. reporting pages
2. export actions
3. import page
4. audit pages
5. profile
6. help and about

## 49. Daftar Page yang Nilai Uji Tertinggi

Page dengan nilai uji tertinggi:

1. login
2. role permission edit
3. system settings edit
4. catalog create publish
5. item create dan status change
6. member create dan block unblock
7. loan create
8. return create
9. digital asset upload
10. digital asset private preview
11. OPAC search
12. OPAC record detail
13. report dashboard
14. circulation report
15. import anggota
16. export report
17. audit log index

## 50. Daftar Page yang Nilai Risiko Keamanan Tertinggi

Page yang harus ditinjau paling ketat:

1. login
2. reset password modal atau page
3. role permission edit
4. system settings edit
5. digital asset private preview
6. digital asset access rule form
7. public asset preview
8. export report
9. audit log detail
10. API backed lookup pages

## 51. Daftar Page yang Nilai Integrasi Tertinggi

Page yang paling banyak menyentuh modul lain:

1. catalog create edit
2. member create edit
3. collection item create edit
4. loan page
5. return page
6. digital asset create show
7. OPAC search
8. report dashboard
9. digital access report
10. import anggota

## 52. Aturan Build Page Write Action

Setiap page write action wajib dibangun bersama:

1. request validation
2. service
3. permission
4. audit
5. error handling
6. success notification
7. test scenario utama

Ini berlaku untuk:

1. create edit users
2. create edit roles
3. create edit master data
4. create edit catalog
5. create edit items
6. create edit members
7. loan return renew
8. upload edit asset
9. import export

## 53. Aturan Build Page Read Only

Page read only tetap wajib punya:

1. route
2. permission bila internal
3. query efisien
4. empty state
5. pagination bila list
6. test scenario dasar

Ini berlaku untuk:

1. dashboard
2. report pages
3. audit list
4. OPAC search
5. OPAC detail
6. detail page admin

## 54. Hubungan dengan TREE

Tree file harus mengikuti urutan prioritas ini.

Aturan:

1. file untuk batch lebih awal harus dibuat lebih dulu
2. jangan membuat view report jauh lebih awal dari page transaksi
3. file sirkulasi yang belum lengkap di tree harus disempurnakan saat implementasi resmi agar alur operasional tetap utuh

## 55. Hubungan dengan Traceability Matrix

Setiap page pada dokumen ini wajib punya item linked pada traceability matrix.

Aturan:

1. jika page belum punya jejak lengkap, page belum boleh dianggap selesai
2. traceability dipakai sebagai alat cek missing link page

## 56. Hubungan dengan Backend Checklist

Backend checklist nantinya harus memverifikasi:

1. page P1 punya backend utuh
2. page write action tidak yatim
3. dependency service model tabel siap
4. page sensitif punya audit dan security

## 57. Hubungan dengan Frontend Checklist

Frontend checklist nantinya harus memverifikasi:

1. semua page P1 tampil sesuai UI standard
2. action button sesuai permission
3. empty state dan flash message benar
4. link antar page tidak putus
5. page P2 dan P3 dibangun setelah P1 stabil

## 58. Hubungan dengan Smoke Test Checklist

Smoke test wajib fokus lebih dulu pada page P1.

Aturan:

1. jika page P1 belum stabil, jangan lanjut polish P3 dan P4
2. smoke test page P1 harus lulus sebelum UAT awal

## 59. Hubungan dengan UAT Checklist

UAT wajib berfokus pada alur page berikut:

1. login
2. katalog
3. item
4. anggota
5. sirkulasi
6. asset digital
7. OPAC
8. laporan

## 60. Indikator Kesiapan Coding Berdasarkan Dokumen Ini

Tim dianggap siap coding bila:

1. route map sudah final
2. view map sudah final
3. tree sudah final
4. traceability matrix sudah linked untuk page P1
5. coding standard sudah final
6. urutan batch build sudah dipahami

## 61. Indikator Kesiapan Go Live Berdasarkan Dokumen Ini

Sistem layak masuk kandidat go live bila:

1. seluruh page P1 selesai dan lulus uji
2. page P2 utama selesai
3. smoke test page kritis lulus
4. UAT alur inti lulus
5. page sensitif aman
6. tidak ada broken link utama

## 62. Anti Pattern yang Dilarang

Tidak boleh:

1. membangun report lebih dulu sebelum transaksi inti
2. membangun OPAC final sebelum rule publik aman
3. membangun audit page sebelum aksi sensitif menghasilkan log
4. membangun page create tanpa validation dan service
5. membangun banyak halaman index tanpa action create edit yang benar
6. membangun page bagus secara visual tetapi belum usable
7. lompat ke P3 sementara P1 masih gagal

## 63. Prioritas Implementasi Dokumen Ini

Prioritas praktis yang direkomendasikan:

### Prioritas P1

1. Tahap 0
2. Tahap 1
3. Tahap 2
4. Tahap 3
5. Tahap 4
6. Tahap 5
7. Tahap 6
8. Tahap 7

### Prioritas P2

1. Tahap 8
2. Tahap 9
3. Tahap 10

### Prioritas P3

1. Tahap 11

## 64. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 41_BACKEND_CHECKLIST.md
2. 42_FRONTEND_CHECKLIST.md
3. 43_INTEGRATION_CHECKLIST.md
4. 44_SECURITY_HARDENING_CHECKLIST.md
5. 45_SMOKE_TEST_CHECKLIST.md
6. 46_UAT_CHECKLIST.md

Aturan:

1. backend checklist harus mengecek penyelesaian batch sesuai prioritas ini
2. frontend checklist harus mengecek page per tahap
3. smoke test harus memakai page P1
4. UAT harus memakai alur page inti yang disebut di sini

## 65. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. urutan page mengikuti dependency domain
2. page P1 benar benar page bernilai tertinggi
3. page report dan audit tidak didahulukan secara keliru
4. OPAC dibangun setelah visibility aman
5. circulation dibangun setelah item dan member siap
6. tree dan traceability selaras dengan prioritas page
7. dokumen ini realistis untuk AI Agent dan tim kecil

## 66. Kesimpulan

Dokumen Page Build Priority ini menetapkan urutan resmi pembangunan halaman PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 39. Dokumen ini memastikan bahwa halaman fondasi, halaman operasional inti, halaman publik, halaman reporting, dan halaman audit dibangun dalam urutan yang aman, logis, dan bernilai tinggi, sehingga implementasi coding lebih terarah, testing lebih mudah, dan risiko missing link dapat ditekan.

END OF 40_PAGE_BUILD_PRIORITY.md
