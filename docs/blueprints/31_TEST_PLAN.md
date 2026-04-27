# 31_TEST_PLAN.md

## 1. Nama Dokumen

Test Plan Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint rencana pengujian sistem

### 2.3 Status Dokumen

Resmi, acuan wajib perencanaan, pelaksanaan, pencatatan, dan evaluasi pengujian PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan rencana pengujian resmi PERPUSQU agar seluruh modul, proses bisnis, integrasi, keamanan, file, search, OCR, reporting, import export, dan UI diverifikasi secara sistematis sebelum sistem dinyatakan siap digunakan. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, tester, administrator sistem, dan user penguji agar tidak ada fitur penting yang lolos tanpa uji, tidak ada celah antar modul yang terlewat, dan seluruh pengujian tetap konsisten dengan seluruh blueprint yang telah ditetapkan sebelumnya.

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

Aturan wajib:

1. Test plan harus menguji seluruh proses inti yang telah didefinisikan.
2. Test plan harus mengacu pada schema, service, route, workflow, dan security yang sudah resmi.
3. Tidak boleh ada skenario uji yang mengasumsikan fitur di luar scope fase 1 seolah sudah tersedia.
4. Hasil pengujian harus dapat ditelusuri ke blueprint terkait.
5. Semua area sensitif wajib punya pengujian yang jelas.
6. Test plan ini menjadi induk bagi dokumen 32_TEST_SCENARIO.md dan checklist pengujian berikutnya.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. tujuan pengujian
2. ruang lingkup pengujian
3. strategi pengujian
4. jenis pengujian
5. prioritas pengujian
6. objek yang diuji
7. lingkungan pengujian
8. data uji
9. kriteria masuk dan keluar pengujian
10. peran tim pengujian
11. alat bantu pengujian
12. pelaporan defect
13. hubungan test plan dengan dokumen lain
14. risiko dan mitigasi pengujian

## 5. Tujuan Pengujian

Tujuan utama pengujian PERPUSQU adalah:

1. memastikan fungsi inti sesuai PRD dan SRS
2. memastikan alur bisnis sesuai workflow state machine
3. memastikan validasi input bekerja sesuai aturan
4. memastikan role, permission, dan policy bekerja benar
5. memastikan OPAC publik hanya menampilkan data yang sah
6. memastikan file digital, OCR, search, dan reporting berjalan konsisten
7. memastikan import dan export sesuai aturan
8. memastikan keamanan dasar terpenuhi
9. memastikan tidak ada missing link antar menu, route, view, controller, service, model, dan tabel utama

## 6. Sasaran Kualitas yang Ingin Dicapai

Pengujian PERPUSQU harus menilai kualitas berikut:

1. correctness
2. consistency
3. completeness
4. security
5. usability
6. traceability
7. operability
8. maintainability readiness

## 7. Definisi Fase Uji

Fase uji resmi dibagi menjadi:

1. unit test
2. feature test
3. integration test
4. UI functional test
5. security test dasar
6. import export test
7. OCR and search test
8. smoke test
9. UAT

## 8. Ruang Lingkup Uji Fase 1

Ruang lingkup uji fase 1 mencakup seluruh modul inti:

1. authentication dan access control
2. core settings
3. master data
4. catalog
5. collection
6. member
7. circulation
8. digital repository
9. OPAC publik
10. reporting
11. import export
12. audit dan monitoring dasar

## 9. Area yang Belum Wajib Diuji sebagai Fitur Aktif

Karena belum termasuk scope fase 1, area berikut tidak wajib diuji sebagai fitur aktif:

1. SSO
2. RFID
3. payment gateway denda
4. WhatsApp gateway
5. SMS gateway
6. mobile app backend khusus
7. usage analytics digital detail
8. integrasi SIAKAD real time
9. antivirus terintegrasi penuh
10. push notification

Aturan:

1. area di atas cukup dicatat sebagai out of scope
2. tidak boleh dinilai gagal hanya karena fitur tersebut belum ada

## 10. Strategi Umum Pengujian

Strategi pengujian resmi PERPUSQU adalah:

1. uji dari domain inti ke domain pendukung
2. uji dari backend rule ke UI behavior
3. uji dari data valid ke data salah
4. uji dari state normal ke state exception
5. uji dari role berhak ke role tidak berhak
6. uji dari data publik ke data privat
7. uji dari proses sinkron ke proses async

## 11. Prinsip Eksekusi Pengujian

Prinsip eksekusi pengujian:

1. uji yang paling kritis dikerjakan lebih dulu
2. hasil uji harus dapat diulang
3. setiap defect harus punya bukti
4. setiap perbaikan penting harus diuji ulang
5. tidak cukup hanya uji happy path
6. negative scenario wajib ada
7. state transition wajib diuji
8. permission test wajib diuji
9. file dan storage test wajib diuji
10. search dan OCR test wajib diuji

## 12. Objek Uji Utama

Objek uji utama yang harus selalu menjadi fokus:

1. route
2. controller
3. request validation
4. service layer
5. model relation
6. database constraints
7. workflow states
8. permission rules
9. UI action visibility
10. search behavior
11. file access behavior
12. import export behavior
13. audit trail behavior

## 13. Jenis Pengujian Resmi

### 13.1 Unit Test

Fokus:

1. service methods
2. helper domain
3. policy logic
4. error mapping
5. search document builder
6. OCR support utility
7. import row validator
8. export file builder

Tujuan:
memastikan logika kecil dan terisolasi bekerja benar

### 13.2 Feature Test

Fokus:

1. endpoint web
2. request validation
3. auth dan permission
4. response behavior
5. database write
6. flash message behavior
7. redirect behavior

Tujuan:
memastikan fitur end to end tingkat aplikasi berjalan sesuai desain

### 13.3 Integration Test

Fokus:

1. storage dengan digital asset
2. search indexing dengan catalog
3. OCR dengan digital repository
4. circulation dengan member dan item
5. reporting dengan query agregasi
6. import dengan member dan master data
7. export dengan reporting dan storage
8. queue dengan status domain

Tujuan:
memastikan modul dan layanan pendukung saling terhubung benar

### 13.4 UI Functional Test

Fokus:

1. halaman utama
2. form utama
3. tabel dan filter
4. badge status
5. tombol aksi
6. empty state
7. page error
8. OPAC flow

Tujuan:
memastikan perilaku halaman sesuai UI UX standard dan view map

### 13.5 Security Test Dasar

Fokus:

1. login protection
2. permission enforcement
3. policy enforcement
4. CSRF
5. file private access control
6. OPAC public visibility
7. API internal protection
8. export restriction
9. import restriction
10. safe error exposure

Tujuan:
memastikan kontrol keamanan minimum fase 1 berjalan

### 13.6 Import Export Test

Fokus:

1. import anggota
2. validasi template
3. partial success
4. export laporan
5. filter consistency
6. temp file handling

### 13.7 OCR and Search Test

Fokus:

1. upload asset digital
2. OCR status transition
3. reindex trigger
4. OPAC result visibility
5. search ranking dasar
6. public preview restriction

### 13.8 Smoke Test

Fokus:

1. fungsi paling kritis setelah build atau deploy
2. akses route utama
3. login
4. CRUD inti
5. circulation inti
6. OPAC search
7. asset preview dasar

### 13.9 UAT

Fokus:

1. penerimaan user operasional
2. kesesuaian proses kerja
3. kenyamanan penggunaan
4. konsistensi laporan
5. kelayakan go live

## 14. Prioritas Pengujian

Prioritas dibagi menjadi 3 tingkat:

### 14.1 Prioritas P1

Wajib lolos sebelum dianggap siap uji lanjut.

Area:

1. login dan logout
2. role dan permission
3. menu visibility
4. create dan update master data inti
5. create dan publish bibliographic record
6. create physical item
7. create member
8. loan, return, renew
9. upload digital asset
10. preview asset privat dan publik
11. OPAC search
12. reporting dashboard dasar
13. audit log untuk aksi sensitif
14. validation rules inti
15. security access denial inti

### 14.2 Prioritas P2

Wajib diuji sebelum UAT final.

Area:

1. import anggota
2. export laporan
3. OCR flow
4. reindex flow
5. digital access report
6. notification rules utama
7. queue retry
8. empty states dan error pages

### 14.3 Prioritas P3

Diuji setelah inti stabil.

Area:

1. tuning search relevance
2. advanced reporting behavior
3. email opsional
4. chart ringan
5. housekeeping flow
6. performance scenario terbatas

## 15. Lingkup Uji per Modul

### 15.1 Identity and Access

Harus diuji:

1. login sukses
2. login gagal
3. akun inactive ditolak
4. reset password
5. update role permission
6. update user roles
7. unauthorized access denied

### 15.2 Core

Harus diuji:

1. update institution profile
2. update operational settings
3. pengaruh settings ke circulation
4. pengaruh settings ke OCR dan upload limit

### 15.3 Master Data

Harus diuji:

1. create
2. update
3. unique constraint
4. relation to dependent modules
5. deactivate or in use restriction

### 15.4 Catalog

Harus diuji:

1. create record
2. update record
3. publish record
4. unpublish record
5. archive record
6. author dan subject relation
7. visibility to OPAC

### 15.5 Collection

Harus diuji:

1. create item
2. update item
3. change item status
4. item availability summary
5. sync dengan circulation

### 15.6 Member

Harus diuji:

1. create member
2. update member
3. block member
4. unblock member
5. eligibility behavior
6. faculty dan study program relation

### 15.7 Circulation

Harus diuji:

1. loan
2. return
3. renew
4. overdue logic
5. fine creation
6. blocked member rejection
7. unavailable item rejection

### 15.8 Digital Repository

Harus diuji:

1. upload asset
2. replace asset
3. publish asset
4. unpublish asset
5. access rules
6. embargo
7. internal preview
8. public preview
9. OCR request
10. reindex request

### 15.9 OPAC

Harus diuji:

1. home page
2. search result
3. filter result
4. record detail
5. public preview
6. empty result
7. access denied public behavior

### 15.10 Reporting

Harus diuji:

1. dashboard summary
2. collection report
3. member report
4. circulation report
5. fine report
6. popular collection report
7. digital repository report
8. filter consistency
9. export consistency

### 15.11 Import Export

Harus diuji:

1. import member valid file
2. import invalid file
3. duplicate handling
4. export semua laporan resmi
5. file naming
6. temp file cleanup

### 15.12 Audit and Monitoring

Harus diuji:

1. audit event creation
2. audit list view access
3. audit detail access
4. queue retry action
5. filtering audit log

## 16. Jenis Data Uji

Data uji resmi dibagi menjadi:

1. data valid normal
2. data valid batas
3. data tidak valid
4. data konflik
5. data privat
6. data publik
7. data state exception
8. data integrasi

## 17. Dataset Uji Minimum

Dataset uji minimum yang harus disiapkan:

1. 3 user internal dengan role berbeda
2. 1 super admin
3. 1 admin perpustakaan
4. 1 pustakawan
5. 1 petugas sirkulasi
6. 1 operator repositori
7. 1 pimpinan perpustakaan
8. 5 master data author
9. 5 subject
10. 3 publisher
11. 3 collection type
12. 3 language
13. 3 classification
14. 3 rack location
15. 3 item condition
16. 2 faculty
17. 4 study program
18. 10 member campuran status
19. 10 bibliographic record campuran status
20. 20 physical item campuran status
21. 10 digital asset campuran status publikasi dan OCR
22. 10 loan campuran active, returned, cancelled
23. 5 fine campuran status
24. 1 file import valid
25. 1 file import rusak
26. 1 asset PDF text based
27. 1 asset PDF scanned
28. 1 asset embargoed

## 18. Strategi Data Uji

Strategi data uji:

1. gunakan seed dasar dari 15_SEED.sql
2. tambahkan seed test fixture khusus
3. pisahkan data untuk happy path dan negative path
4. gunakan data yang mencerminkan state machine
5. hindari data acak yang tidak relevan dengan domain

## 19. Lingkungan Pengujian

Lingkungan uji minimum:

1. local development environment
2. staging or test environment
3. production smoke validation terbatas, setelah deploy dan tanpa merusak data

### 19.1 Local

Tujuan:

1. unit test
2. feature test
3. integration test awal

### 19.2 Staging

Tujuan:

1. full feature validation
2. UAT
3. smoke test sebelum go live
4. OCR dan search integration test lebih realistis

### 19.3 Production

Tujuan:

1. smoke test pasca deploy
2. validasi route utama
3. validasi environment configuration
4. validasi layanan pendukung aktif

Aturan:

1. production test tidak boleh merusak data nyata
2. gunakan akun dan data uji yang disiapkan khusus

## 20. Kebutuhan Lingkungan Uji

Agar pengujian lengkap, environment uji ideal harus memiliki:

1. MySQL aktif
2. Redis aktif
3. queue worker aktif
4. Meilisearch aktif
5. storage private dan public aktif
6. OCR dependency aktif
7. mail dummy atau mail testing, bila email diuji
8. HTTPS pada staging, bila memungkinkan

## 21. Entry Criteria Pengujian

Sebuah modul boleh masuk fase uji bila:

1. blueprint modul terkait sudah final
2. migration dan schema dasar sudah sinkron
3. request validation sudah tersedia
4. service inti sudah tersedia
5. route dan controller sudah tersedia
6. permission dasar sudah tersedia
7. UI minimum sudah bisa dibuka, untuk uji UI
8. data uji minimum sudah siap

## 22. Exit Criteria Pengujian

Sebuah modul dianggap lolos fase uji bila:

1. seluruh skenario P1 lulus
2. tidak ada defect blocker
3. defect high sudah ditangani atau diputuskan dengan jelas
4. hasil uji security dasar lulus
5. state transition penting lulus
6. role permission test lulus
7. audit dan error handling inti lulus
8. smoke test modul lulus

## 23. Definisi Status Defect

Status defect yang direkomendasikan:

1. open
2. in progress
3. fixed
4. retest
5. closed
6. deferred
7. rejected

## 24. Severity Defect

Severity defect dibagi menjadi:

### 24.1 Blocker

Contoh:

1. login gagal total
2. loan merusak data inti
3. asset privat bocor publik
4. search publik menampilkan data private
5. export memuat data sensitif

### 24.2 High

Contoh:

1. publish record gagal
2. return tidak menghitung denda benar
3. permission salah pada route penting
4. import gagal baca template yang sah

### 24.3 Medium

Contoh:

1. badge status salah
2. filter laporan tidak konsisten
3. pesan notifikasi salah
4. audit value kurang lengkap

### 24.4 Low

Contoh:

1. teks UI typo
2. alignment minor
3. urutan kolom kurang ideal
4. pesan empty state kurang halus

## 25. Klasifikasi Test Case Menurut Risiko

Test case wajib ditandai menurut risiko:

1. security critical
2. business critical
3. operational critical
4. data integrity critical
5. usability support
6. reporting accuracy
7. integration stability

## 26. Metode Pengujian per Layer

### 26.1 Database Layer

Uji:

1. foreign key behavior
2. unique constraint
3. soft delete behavior
4. enum compatibility
5. index behavior dasar

### 26.2 Service Layer

Uji:

1. business rule
2. state transition
3. side effect
4. audit trigger
5. error code mapping

### 26.3 Controller Layer

Uji:

1. auth
2. permission
3. response
4. redirect
5. flash message

### 26.4 View Layer

Uji:

1. component visibility
2. badge
3. table and filter
4. form error display
5. empty state
6. route link consistency

### 26.5 Integration Layer

Uji:

1. MySQL
2. storage
3. queue
4. OCR
5. search
6. export file
7. import file

## 27. Test Coverage Minimum yang Diinginkan

Coverage minimum yang diinginkan secara praktis:

1. seluruh use case inti P1 teruji
2. seluruh role utama diuji minimal sekali pada area hak akses masing masing
3. seluruh state penting diuji transisinya
4. seluruh validation request utama diuji
5. seluruh service domain utama diuji
6. seluruh laporan utama diuji rumus utamanya
7. seluruh jalur asset digital utama diuji

Catatan:

1. angka persentase coverage code tidak dijadikan satu satunya target utama
2. fokus utama adalah coverage risiko dan proses bisnis

## 28. Strategi Unit Test

Unit test diprioritaskan untuk:

1. policy access
2. member eligibility logic
3. loan due date calculation
4. fine calculation
5. record publish guard
6. item status transition
7. digital asset access rule evaluator
8. OCR result normalizer
9. search index document builder
10. import row validator
11. error code resolver

## 29. Strategi Feature Test

Feature test diprioritaskan untuk:

1. login and logout
2. CRUD master data utama
3. create and publish record
4. create item
5. create member
6. block member
7. loan
8. return
9. renew
10. upload asset
11. public preview allowed and denied
12. import member
13. export report
14. audit log access
15. queue retry route

## 30. Strategi Integration Test

Integration test diprioritaskan untuk:

1. upload asset ke private storage
2. OCR worker membaca asset
3. OCR success menyimpan OcrText
4. OCR success memicu reindex
5. search result berubah setelah publish or unpublish
6. circulation mengubah item status
7. return menghasilkan fine bila terlambat
8. import memetakan faculty dan study program
9. export menghasilkan file sesuai filter
10. OPAC hanya melihat data publik

## 31. Strategi Security Test Dasar

Security test minimum harus mencakup:

1. route admin tanpa login ditolak
2. route sensitif tanpa permission ditolak
3. asset privat tidak bisa diakses langsung
4. asset publik embargoed tidak preview publik
5. search publik tidak mengandung record draft
6. search publik tidak mengandung OCR privat
7. import tanpa permission ditolak
8. export tanpa permission ditolak
9. audit log tanpa permission ditolak
10. API internal tanpa auth ditolak
11. error tidak membocorkan stack trace
12. CSRF berlaku pada form admin

## 32. Strategi UI Functional Test

UI functional test harus memeriksa:

1. layout admin konsisten
2. sidebar mengikuti role
3. header dan breadcrumb benar
4. table filter dan pagination bekerja
5. form error muncul di field yang benar
6. badge status sesuai state
7. tombol aksi mengikuti permission dan state
8. OPAC flow mudah dipakai
9. page error dan empty state tampil benar

## 33. Strategi OCR dan Search Test

Uji OCR dan search harus memeriksa:

1. PDF text based diproses
2. PDF scanned diproses
3. OCR gagal mengubah status ke failed
4. retry OCR bekerja
5. publish record memunculkan hasil di OPAC
6. unpublish record menghilangkan hasil dari OPAC
7. asset privat tidak memberi preview publik
8. asset publik memberi preview jika rule mengizinkan
9. exact title match muncul
10. ISBN match muncul
11. author match muncul
12. subject match muncul
13. search engine failure ditangani aman

## 34. Strategi Reporting Test

Reporting test harus memeriksa:

1. dashboard cards sesuai query
2. collection report sesuai schema
3. member report sesuai status
4. circulation report sesuai active and overdue logic
5. fine report sesuai nominal dan status
6. popular collection sesuai jumlah loan
7. digital repository report sesuai OCR dan index status
8. export mengikuti filter aktif
9. empty state laporan aman

## 35. Strategi Import Export Test

Import export test harus memeriksa:

1. format file
2. header file
3. duplicate in file
4. duplicate in database
5. partial success import
6. export file naming
7. export file content
8. temp file handling
9. audit event import export
10. permission import export

## 36. Strategi Audit dan Error Handling Test

Audit dan error test harus memeriksa:

1. action sensitif mencatat audit
2. old_values dan new_values tersanitasi
3. error code sesuai domain
4. flash message sesuai error atau success
5. API error response konsisten
6. log sensitif tidak bocor ke UI

## 37. Peran dan Tanggung Jawab Pengujian

### 37.1 Developer

Tanggung jawab:

1. unit test
2. feature test inti
3. fix defect
4. regression awal

### 37.2 Reviewer Teknis

Tanggung jawab:

1. review hasil uji
2. verifikasi konsistensi dengan blueprint
3. review defect kritis
4. review jalur integrasi

### 37.3 Tester atau QA

Tanggung jawab:

1. menjalankan test scenario
2. mencatat defect
3. melakukan retest
4. menyiapkan laporan hasil uji

### 37.4 User UAT

Tanggung jawab:

1. menguji alur operasional nyata
2. menilai kelayakan pakai
3. memberi catatan proses kerja
4. menyetujui atau menunda go live

### 37.5 Admin Sistem

Tanggung jawab:

1. menyiapkan environment uji
2. menyiapkan service pendukung
3. membantu validasi deploy dan smoke test

## 38. Artefak Pengujian yang Harus Dihasilkan

Artefak minimum:

1. test plan
2. test scenario
3. test case execution result
4. defect list
5. retest evidence
6. smoke test result
7. UAT result
8. sign off readiness, bila diterapkan

## 39. Bukti Pengujian

Bukti uji dapat berupa:

1. screenshot
2. response capture
3. log excerpt aman
4. database assertion
5. generated file sample
6. audit record sample
7. queue result sample
8. search result sample

Aturan:

1. bukti tidak boleh membocorkan data sensitif
2. bukti harus cukup untuk verifikasi ulang

## 40. Alat Bantu Pengujian yang Direkomendasikan

Alat bantu yang direkomendasikan:

1. PHPUnit atau Pest untuk automated test
2. Laravel test utilities
3. database seeding dan factory
4. dummy storage untuk test
5. mock queue bila diperlukan
6. Meilisearch test environment
7. mail testing tool atau log mailer
8. browser manual test untuk UI
9. spreadsheet defect tracker, bila diperlukan

## 41. Strategi Otomasi Pengujian

Prioritas otomasi:

1. service logic kritis
2. feature route kritis
3. permission dan policy
4. circulation workflow
5. digital asset workflow
6. search visibility
7. import export kritis

Yang boleh manual lebih dulu:

1. UI visual refinement
2. chart ringan
3. minor responsive check
4. copy text review

## 42. Regression Testing

Regression wajib dilakukan setelah:

1. perubahan schema utama
2. perubahan workflow circulation
3. perubahan access control
4. perubahan storage logic
5. perubahan OCR logic
6. perubahan indexing logic
7. perubahan export logic
8. perubahan permission matrix

Regression minimum mencakup area P1.

## 43. Smoke Testing

Smoke test dijalankan:

1. setelah build stabil
2. setelah deploy ke staging
3. setelah deploy ke production

Area smoke minimum:

1. login
2. dashboard
3. master data basic
4. create record
5. create item
6. create member
7. loan
8. return
9. upload asset
10. OPAC search
11. report open
12. audit log open

## 44. UAT Strategy

UAT harus melibatkan peran nyata atau simulasi peran nyata:

1. admin perpustakaan
2. pustakawan
3. petugas sirkulasi
4. operator repositori
5. pimpinan perpustakaan
6. pengguna OPAC publik, untuk flow dasar

Fokus UAT:

1. kesesuaian proses operasional
2. kemudahan pakai
3. konsistensi hasil
4. kelengkapan halaman utama
5. kelayakan go live

## 45. Entry Criteria UAT

UAT dimulai bila:

1. seluruh skenario P1 lulus
2. defect blocker = 0
3. defect high pada area inti sudah terkendali
4. smoke test staging lulus
5. environment staging stabil
6. data uji representatif siap

## 46. Exit Criteria UAT

UAT dianggap selesai bila:

1. user penguji menyetujui alur inti
2. tidak ada defect blocker terbuka
3. defect high yang tersisa sudah diputuskan penanganannya
4. hasil UAT terdokumentasi
5. rekomendasi go live atau perbaikan lanjutan jelas

## 47. Risiko Pengujian

Risiko utama pengujian:

1. blueprint belum diturunkan ke struktur file implementasi
2. layanan pendukung belum siap di staging
3. data uji tidak representatif
4. search atau OCR sulit diuji tanpa environment lengkap
5. export async sulit diverifikasi tanpa queue stabil
6. role testing tidak lengkap
7. regression terlewat setelah fix cepat
8. defect hanya diuji di UI tanpa cek database dan audit

## 48. Mitigasi Risiko Pengujian

Mitigasi yang disarankan:

1. susun TREE.md dan TRACEABILITY_MATRIX.md
2. siapkan env staging lengkap
3. siapkan dataset uji standar
4. buat checklist smoke dan UAT terpisah
5. pakai seed test khusus
6. wajib regression pada area terdampak
7. verifikasi database, audit, dan UI sekaligus untuk kasus kritis
8. gunakan test scenario rinci pada dokumen 32

## 49. Hubungan dengan Dokumen Turunan

Dokumen ini menjadi induk bagi:

1. 32_TEST_SCENARIO.md
2. 45_SMOKE_TEST_CHECKLIST.md
3. 46_UAT_CHECKLIST.md

Hubungan:

1. Test Plan menjelaskan strategi dan ruang lingkup
2. Test Scenario merinci kasus uji
3. Smoke Test Checklist memuat uji cepat minimum
4. UAT Checklist memuat uji penerimaan user

## 50. Hubungan dengan Blueprint Implementasi

Dokumen ini harus dipakai bersama:

1. 37_CODING_STANDARD.md
2. 38_TREE.md
3. 39_TRACEABILITY_MATRIX.md
4. 41_BACKEND_CHECKLIST.md
5. 42_FRONTEND_CHECKLIST.md

Alasan:

1. pengujian harus tahu struktur file
2. pengujian harus tahu jejak menu sampai tabel
3. pengujian harus tahu implementasi yang dianggap lengkap

## 51. Matriks Jenis Uji ke Modul

| Modul | Unit | Feature | Integration | UI | Security | UAT |
|---|---|---|---|---|---|---|
| Identity and Access | ya | ya | ya | ya | ya | ya |
| Core | ya | ya | ya | ya | ya | ya |
| Master Data | ya | ya | ya | ya | ya | ya |
| Catalog | ya | ya | ya | ya | ya | ya |
| Collection | ya | ya | ya | ya | ya | ya |
| Member | ya | ya | ya | ya | ya | ya |
| Circulation | ya | ya | ya | ya | ya | ya |
| Digital Repository | ya | ya | ya | ya | ya | ya |
| OPAC | ya | ya | ya | ya | ya | ya |
| Reporting | ya | ya | ya | ya | ya | ya |
| Import Export | ya | ya | ya | ya | ya | ya |
| Audit and Monitoring | ya | ya | ya | ya | ya | ya |

## 52. Kriteria Go Live dari Sudut Pengujian

Sistem layak dipertimbangkan go live bila:

1. seluruh area P1 lulus
2. seluruh route inti terbukti berfungsi
3. role dan permission inti lulus
4. file privat aman
5. OPAC publik aman
6. OCR dan reindex setidaknya berjalan pada kasus dasar
7. import anggota lulus
8. export laporan lulus
9. audit log sensitif lulus
10. smoke test staging lulus
11. UAT inti diterima

## 53. Anti Pattern Pengujian yang Dilarang

Pengujian tidak boleh:

1. hanya menguji happy path
2. hanya menguji UI tanpa cek database dan state
3. hanya menguji admin penuh tanpa role lain
4. menganggap fitur aman tanpa negative test
5. menguji OPAC tanpa cek visibilitas publik
6. menguji export tanpa cek isi file
7. menguji import tanpa cek partial success dan duplicate
8. menguji OCR tanpa cek status gagal
9. menguji search tanpa cek record non publik
10. menandai siap go live tanpa smoke test dan UAT

## 54. Prioritas Dokumen Lanjutan yang Bergantung pada Test Plan

Setelah dokumen ini, dokumen yang wajib segera ditulis adalah:

1. 32_TEST_SCENARIO.md
2. 45_SMOKE_TEST_CHECKLIST.md
3. 46_UAT_CHECKLIST.md

Dokumen yang sangat membantu:

1. 38_TREE.md
2. 39_TRACEABILITY_MATRIX.md
3. 41_BACKEND_CHECKLIST.md
4. 42_FRONTEND_CHECKLIST.md

## 55. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 32_TEST_SCENARIO.md
2. 33_DEPLOYMENT_GUIDE.md
3. 34_ENV_CONFIGURATION.md
4. 35_BACKUP_AND_RECOVERY.md
5. 36_PERFORMANCE_GUIDE.md
6. 37_CODING_STANDARD.md
7. 38_TREE.md
8. 39_TRACEABILITY_MATRIX.md
9. 41_BACKEND_CHECKLIST.md
10. 42_FRONTEND_CHECKLIST.md
11. 45_SMOKE_TEST_CHECKLIST.md
12. 46_UAT_CHECKLIST.md

Aturan:

1. Test Scenario wajib merinci seluruh strategi di dokumen ini
2. Deployment Guide wajib memasukkan langkah smoke test
3. Env Configuration wajib menyiapkan dependency test environment
4. Performance Guide wajib menurunkan test performa terbatas
5. Checklists harus memakai test plan ini sebagai dasar verifikasi

## 56. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. seluruh modul inti tercakup
2. seluruh jenis uji utama tercakup
3. seluruh area sensitif tercakup
4. prioritas P1, P2, dan P3 jelas
5. entry dan exit criteria jelas
6. hubungan dengan blueprint sebelumnya jelas
7. tidak ada fitur luar scope fase 1 yang diperlakukan wajib

## 57. Kesimpulan

Dokumen Test Plan ini menetapkan rencana pengujian resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 30. Dokumen ini memastikan bahwa seluruh fungsi inti, alur bisnis, keamanan, search, OCR, file, reporting, import export, OPAC, dan integrasi utama diuji secara sistematis sebelum sistem dinyatakan siap digunakan. Semua pengujian PERPUSQU wajib merujuk dokumen ini.

END OF 31_TEST_PLAN.md
