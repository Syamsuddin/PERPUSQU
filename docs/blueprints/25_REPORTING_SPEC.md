# 25_REPORTING_SPEC.md

## 1. Nama Dokumen

Reporting Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi laporan, dashboard, metrik, agregasi data, dan ekspor

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan dashboard laporan, definisi indikator, query agregasi, aturan filter, hak akses laporan, ekspor laporan, dan batasan analitik fase 1

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan spesifikasi resmi seluruh laporan dan dashboard pada PERPUSQU agar data yang ditampilkan konsisten, terukur, dapat dipertanggungjawabkan, dan selaras dengan schema database, model, service layer, workflow state machine, UI UX, dan API contract yang telah disepakati. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar tidak ada metrik yang ambigu, tidak ada laporan yang mengambil data di luar schema, dan tidak ada dashboard yang menampilkan angka yang bertentangan dengan proses bisnis sistem.

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

Aturan wajib:

1. Semua laporan fase 1 harus dibangun dari schema yang sudah ada.
2. Tidak boleh ada metrik yang membutuhkan tabel baru tetapi diperlakukan seolah sudah tersedia.
3. Semua angka dashboard harus punya definisi rumus yang jelas.
4. Semua filter laporan harus mengikuti validation rules.
5. Semua laporan harus tunduk pada role dan permission matrix.
6. Semua ekspor laporan harus mengikuti service dan route resmi.
7. Analitik penggunaan digital yang belum didukung schema harus dinyatakan sebagai batasan fase 1.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. tujuan dan prinsip pelaporan
2. jenis laporan fase 1
3. definisi dashboard statistik
4. definisi setiap metrik utama
5. sumber data setiap laporan
6. filter dan parameter laporan
7. struktur output halaman laporan
8. aturan ekspor
9. role dan permission laporan
10. batasan analitik fase 1
11. aturan performa query laporan
12. aturan testing laporan

## 5. Prinsip Umum Reporting

Prinsip resmi pelaporan PERPUSQU adalah:

1. Akurat
2. Konsisten
3. Dapat dijelaskan rumusnya
4. Berbasis data nyata dalam database
5. Tidak menyesatkan
6. Relevan dengan kebutuhan operasional perpustakaan
7. Ringkas pada dashboard
8. Lebih detail pada halaman laporan
9. Tidak membocorkan data sensitif
10. Mudah diekspor

## 6. Sasaran Reporting Fase 1

Pelaporan fase 1 ditujukan untuk:

1. memantau jumlah koleksi
2. memantau jumlah item fisik
3. memantau jumlah anggota
4. memantau transaksi sirkulasi
5. memantau keterlambatan dan denda
6. memantau aset digital dan status pemrosesannya
7. memantau tren peminjaman koleksi
8. memberi ringkasan pimpinan dan admin perpustakaan

## 7. Kategori Laporan Resmi Fase 1

Kategori laporan resmi fase 1:

1. Dashboard Statistik
2. Laporan Koleksi
3. Laporan Anggota
4. Laporan Sirkulasi
5. Laporan Denda
6. Laporan Koleksi Populer
7. Laporan Digital Repository
8. Laporan Ekspor

## 8. Laporan yang Tidak Wajib pada Fase 1

Laporan berikut belum wajib pada fase 1:

1. analitik perilaku pencarian pengguna publik
2. analitik akses digital berbasis event view per pengguna
3. heatmap penggunaan koleksi
4. analitik per fakultas yang sangat rinci lintas sesi
5. predictive analytics
6. laporan acquisition
7. laporan vendor atau pembelian koleksi
8. laporan pembayaran denda rinci bila alur pembayaran belum diaktifkan

Catatan:

1. beberapa istilah tetap bisa disiapkan sebagai placeholder konsep, tetapi tidak boleh diperlakukan seolah datanya sudah tersedia
2. khusus digital access, fase 1 hanya mendukung laporan inventaris dan readiness aset digital, bukan usage log detail

## 9. Service dan Controller yang Wajib Terlibat

Service resmi untuk reporting sesuai blueprint sebelumnya:

1. ReportingDashboardService
2. CollectionReportService
3. MemberReportService
4. CirculationReportService
5. FineReportService
6. PopularCollectionReportService
7. DigitalAccessReportService
8. ReportExportService

Controller resmi:

1. DashboardReportController
2. CollectionReportController
3. MemberReportController
4. CirculationReportController
5. FineReportController
6. PopularCollectionReportController
7. DigitalAccessReportController

## 10. View Resmi Laporan

View map yang harus tersedia atau diturunkan dari dokumen sebelumnya:

1. `modules/reporting/dashboard.blade.php`
2. `modules/reporting/collections/index.blade.php`
3. `modules/reporting/members/index.blade.php`
4. `modules/reporting/circulation/index.blade.php`
5. `modules/reporting/fines/index.blade.php`
6. `modules/reporting/popular_collections/index.blade.php`
7. `modules/reporting/digital_access/index.blade.php`

Aturan:

1. semua view laporan memakai layout admin
2. semua halaman laporan memiliki filter bar, summary card, tabel, dan tombol export bila diizinkan

## 11. Route Resmi Laporan

Route resmi yang terkait laporan:

1. `admin.reports.dashboard`
2. `admin.reports.collections.index`
3. `admin.reports.collections.export`
4. `admin.reports.members.index`
5. `admin.reports.members.export`
6. `admin.reports.circulation.index`
7. `admin.reports.circulation.export`
8. `admin.reports.fines.index`
9. `admin.reports.fines.export`
10. `admin.reports.popular_collections.index`
11. `admin.reports.popular_collections.export`
12. `admin.reports.digital_access.index`
13. `admin.reports.digital_access.export`

## 12. Permission Resmi Laporan

Permission resmi laporan:

1. `reports.view_dashboard`
2. `reports.view_collections`
3. `reports.view_members`
4. `reports.view_circulation`
5. `reports.view_fines`
6. `reports.view_popular_collections`
7. `reports.view_digital_access`
8. `reports.export`

Aturan:

1. hanya role yang memiliki permission terkait yang boleh melihat halaman laporan
2. export hanya boleh jika `reports.export` aktif
3. pimpinan perpustakaan cenderung memiliki akses view, bukan edit

## 13. Prinsip Definisi Metrik

Setiap metrik wajib memiliki:

1. nama metrik
2. definisi
3. sumber tabel
4. field acuan
5. filter yang memengaruhi
6. rumus agregasi

Aturan:

1. istilah "total" harus benar benar total dari definisi tertentu
2. istilah "aktif" harus konsisten dengan state machine
3. istilah "tersedia" harus mengacu ke item_status = available
4. istilah "publik" harus mengacu ke publication_status dan is_public yang sah

## 14. Dashboard Statistik Utama

### 14.1 Tujuan Dashboard

Dashboard berfungsi sebagai ringkasan cepat, bukan laporan detail penuh.

### 14.2 Metrik Dashboard Umum Fase 1

Metrik umum yang boleh dipakai:

1. total bibliographic record
2. total item fisik
3. total item tersedia
4. total anggota
5. total anggota aktif
6. total pinjaman aktif
7. total pinjaman overdue
8. total denda outstanding
9. total aset digital
10. total aset digital published
11. total OCR failed
12. total index failed

### 14.3 Sumber Data Dashboard

1. `bibliographic_records`
2. `physical_items`
3. `members`
4. `loans`
5. `fines`
6. `digital_assets`

### 14.4 Rumus Metrik Dashboard

#### 14.4.1 Total Bibliographic Record

Rumus:
jumlah record pada `bibliographic_records` yang belum soft deleted

#### 14.4.2 Total Item Fisik

Rumus:
jumlah record pada `physical_items` yang belum soft deleted

#### 14.4.3 Total Item Tersedia

Rumus:
jumlah `physical_items` dengan `item_status = 'available'`

#### 14.4.4 Total Anggota

Rumus:
jumlah record pada `members` yang belum soft deleted

#### 14.4.5 Total Anggota Aktif

Rumus:
jumlah `members` dengan `is_active = 1`

#### 14.4.6 Total Pinjaman Aktif

Rumus:
jumlah `loans` dengan `loan_status = 'active'`

#### 14.4.7 Total Pinjaman Overdue

Rumus:
jumlah `loans` dengan `loan_status = 'active'` dan `due_date < NOW()`

#### 14.4.8 Total Denda Outstanding

Rumus:
jumlah `fines.amount` dengan `status = 'outstanding'`

#### 14.4.9 Total Aset Digital

Rumus:
jumlah `digital_assets` yang belum soft deleted

#### 14.4.10 Total Aset Digital Published

Rumus:
jumlah `digital_assets` dengan `publication_status = 'published'`

#### 14.4.11 Total OCR Failed

Rumus:
jumlah `digital_assets` dengan `ocr_status = 'failed'`

#### 14.4.12 Total Index Failed

Rumus:
jumlah `digital_assets` dengan `index_status = 'failed'`

## 15. Dashboard per Role

### 15.1 Super Admin

Fokus:

1. total user aktif, bila nanti diaktifkan di widget internal
2. total record
3. total item
4. total anggota
5. total pinjaman aktif
6. total OCR failed
7. total indexing failed

### 15.2 Admin Perpustakaan

Fokus:

1. total record
2. total item tersedia
3. total anggota aktif
4. total pinjaman aktif
5. total overdue
6. total denda outstanding
7. total aset digital published

### 15.3 Pustakawan

Fokus:

1. total record
2. total item
3. total aset digital
4. total OCR failed yang relevan
5. total katalog unpublished atau draft, bila diaktifkan

### 15.4 Petugas Sirkulasi

Fokus:

1. total pinjaman aktif
2. total overdue
3. total item tersedia
4. total anggota blocked
5. total denda outstanding

### 15.5 Operator Repositori Digital

Fokus:

1. total aset digital
2. total asset published
3. total OCR failed
4. total index failed
5. total asset dengan public preview

### 15.6 Pimpinan Perpustakaan

Fokus:

1. total record
2. total item
3. total anggota
4. total pinjaman aktif
5. total overdue
6. total aset digital
7. total denda outstanding

## 16. Laporan Koleksi

### 16.1 Tujuan

Menampilkan daftar dan ringkasan bibliographic record serta karakter koleksi.

### 16.2 Sumber Data

1. `bibliographic_records`
2. `collection_types`
3. `languages`
4. `classifications`
5. `publishers`
6. relasi author dan subject bila diperlukan untuk display

### 16.3 Filter Resmi

Sesuai validation rules:

1. `from_date`, opsional bila ingin berdasarkan created_at
2. `to_date`
3. `collection_type_id`
4. `language_id`
5. `publication_status`
6. `page`
7. `per_page`

Catatan:

1. karena schema tidak memiliki `published_at` terpisah, fase 1 dapat memakai `created_at` atau `updated_at` sebagai filter tanggal administratif bila dibutuhkan
2. definisi tanggal yang dipakai harus konsisten di UI dan query

### 16.4 Metrik Ringkasan

1. total record
2. total published record
3. total draft record
4. total unpublished record
5. total archived record
6. distribusi per jenis koleksi
7. distribusi per bahasa

### 16.5 Rumus Penting

1. total record = count bibliographic_records
2. total published = count where publication_status = published
3. total draft = count where publication_status = draft
4. total unpublished = count where publication_status = unpublished
5. total archived = count where publication_status = archived

### 16.6 Kolom Tabel Minimum

1. judul
2. jenis koleksi
3. bahasa
4. tahun terbit
5. penerbit
6. status publikasi
7. visibilitas publik
8. jumlah item fisik
9. jumlah aset digital

### 16.7 Aturan Query

1. agregasi jumlah item fisik memakai relasi `physical_items`
2. agregasi jumlah aset digital memakai relasi `digital_assets`
3. record soft deleted tidak ikut laporan normal

## 17. Laporan Anggota

### 17.1 Tujuan

Menampilkan daftar anggota dan ringkasan status keanggotaan.

### 17.2 Sumber Data

1. `members`
2. `faculties`
3. `study_programs`

### 17.3 Filter Resmi

1. `from_date`
2. `to_date`
3. `member_type`
4. `faculty_id`
5. `study_program_id`
6. `is_active`
7. `is_blocked`
8. `page`
9. `per_page`

### 17.4 Metrik Ringkasan

1. total anggota
2. total anggota aktif
3. total anggota nonaktif
4. total anggota blocked
5. distribusi anggota per tipe
6. distribusi anggota per fakultas

### 17.5 Rumus Penting

1. total anggota = count members
2. total aktif = count members where is_active = 1
3. total nonaktif = count members where is_active = 0
4. total blocked = count members where is_blocked = 1

### 17.6 Kolom Tabel Minimum

1. nomor anggota
2. nama
3. tipe anggota
4. fakultas
5. program studi
6. status aktif
7. status blocked
8. created_at atau tanggal registrasi administratif

### 17.7 Catatan

1. laporan anggota tidak memerlukan relasi ke user internal
2. role internal tidak masuk laporan ini

## 18. Laporan Sirkulasi

### 18.1 Tujuan

Menampilkan transaksi pinjam, kembali, dan status overdue.

### 18.2 Sumber Data

1. `loans`
2. `members`
3. `physical_items`
4. `bibliographic_records`
5. `return_transactions`
6. `loan_renewals`

### 18.3 Filter Resmi

1. `from_date`
2. `to_date`
3. `member_type`
4. `is_overdue`
5. `page`
6. `per_page`

Catatan:

1. periode utama dapat memakai `loan_date`
2. untuk analisis return dapat ditambah konteks `returned_at` di level query atau tampilan, tetapi fase 1 tetap sederhana

### 18.4 Metrik Ringkasan

1. total pinjaman
2. total pinjaman aktif
3. total pinjaman returned
4. total pinjaman cancelled
5. total overdue
6. total perpanjangan
7. total pengembalian

### 18.5 Rumus Penting

1. total pinjaman = count loans
2. total aktif = count loans where loan_status = active
3. total returned = count loans where loan_status = returned
4. total cancelled = count loans where loan_status = cancelled
5. total overdue = count loans where loan_status = active and due_date < NOW()
6. total perpanjangan = count loan_renewals
7. total pengembalian = count return_transactions

### 18.6 Kolom Tabel Minimum

1. nomor anggota
2. nama anggota
3. barcode item
4. judul record
5. tanggal pinjam
6. jatuh tempo
7. tanggal kembali
8. status pinjaman
9. overdue indicator
10. jumlah perpanjangan

### 18.7 Aturan Overdue

`is_overdue` adalah derived state:

1. loan_status = active
2. due_date < waktu sekarang

Tidak disimpan sebagai kolom permanen.

## 19. Laporan Denda

### 19.1 Tujuan

Menampilkan denda yang terbentuk dari keterlambatan atau sebab lain yang didukung schema.

### 19.2 Sumber Data

1. `fines`
2. `members`
3. `loans`

### 19.3 Filter Resmi

1. `from_date`
2. `to_date`
3. `fine_type`
4. `status`
5. `page`
6. `per_page`

### 19.4 Metrik Ringkasan

1. total nominal denda
2. total outstanding
3. total settled
4. total waived
5. total cancelled
6. total jumlah kasus denda

### 19.5 Rumus Penting

1. total nominal denda = sum all fines amount sesuai filter
2. total outstanding nominal = sum amount where status = outstanding
3. total settled nominal = sum amount where status = settled
4. total waived nominal = sum amount where status = waived
5. total cancelled nominal = sum amount where status = cancelled
6. total kasus = count fines

### 19.6 Kolom Tabel Minimum

1. anggota
2. loan id atau referensi pinjaman
3. jenis denda
4. amount
5. late_days
6. status
7. catatan
8. created_at

### 19.7 Batasan Fase 1

Karena belum ada alur pembayaran denda formal, maka:

1. laporan fokus pada status denda yang tersimpan
2. tidak ada laporan transaksi kas denda
3. status settled, waived, cancelled tetap didukung secara struktur bila nanti diaktifkan

## 20. Laporan Koleksi Populer

### 20.1 Tujuan

Menampilkan bibliographic record yang paling sering dipinjam.

### 20.2 Sumber Data

1. `loans`
2. `physical_items`
3. `bibliographic_records`
4. `collection_types`, bila diperlukan untuk display

### 20.3 Definisi Populer

Fase 1 mendefinisikan populer sebagai:
jumlah transaksi loan yang terkait dengan semua physical item milik satu bibliographic record dalam periode tertentu

### 20.4 Filter Resmi

1. `from_date`
2. `to_date`
3. `collection_type_id`
4. `page`
5. `per_page`

### 20.5 Metrik Ringkasan

1. total record yang pernah dipinjam
2. top record berdasarkan jumlah pinjaman
3. total transaksi loan dalam periode
4. distribusi populer per jenis koleksi, opsional

### 20.6 Rumus Penting

1. loan_count per record = count loans join physical_items join bibliographic_records group by bibliographic_record_id
2. sorting utama = loan_count desc

### 20.7 Kolom Tabel Minimum

1. judul
2. jenis koleksi
3. tahun terbit
4. total item fisik
5. jumlah transaksi pinjam
6. ranking

### 20.8 Batasan

1. popularitas fase 1 berbasis transaksi pinjam fisik, bukan view digital
2. bila nanti ada event log akses digital, definisi bisa diperluas

## 21. Laporan Digital Repository

### 21.1 Tujuan

Menampilkan inventaris aset digital dan status kesiapan digital repository.

### 21.2 Sumber Data

1. `digital_assets`
2. `bibliographic_records`
3. `ocr_texts`

### 21.3 Filter Resmi

1. `from_date`
2. `to_date`
3. `asset_type`
4. `publication_status`
5. `page`
6. `per_page`

### 21.4 Metrik Ringkasan

1. total aset digital
2. total aset digital published
3. total aset digital public
4. total asset dengan public preview
5. total OCR success
6. total OCR failed
7. total index indexed
8. total index failed
9. total asset embargoed

### 21.5 Rumus Penting

1. total aset = count digital_assets
2. total published = count digital_assets where publication_status = published
3. total public = count digital_assets where is_public = 1
4. total public preview = count asset yang lolos rule preview publik dasar
5. total OCR success = count digital_assets where ocr_status = success
6. total OCR failed = count digital_assets where ocr_status = failed
7. total indexed = count digital_assets where index_status = indexed
8. total index failed = count digital_assets where index_status = failed
9. total embargoed = count digital_assets where is_embargoed = 1 and embargo_until > NOW()

### 21.6 Kolom Tabel Minimum

1. judul asset
2. record induk
3. asset_type
4. publication_status
5. is_public
6. is_embargoed
7. embargo_until
8. ocr_status
9. index_status
10. uploaded_at

### 21.7 Batasan Fase 1

Karena schema belum memiliki log akses digital per user atau per view:

1. laporan ini bukan laporan usage analytics detail
2. istilah digital access pada fase 1 harus dimaknai sebagai readiness dan availability aset digital
3. tidak boleh menampilkan metrik jumlah preview atau jumlah download seolah datanya sudah tersedia

## 22. Definisi Digital Access Report pada Fase 1

Agar konsisten dengan service yang sudah ada, `DigitalAccessReportService` pada fase 1 didefinisikan sebagai service untuk:

1. melaporkan status ketersediaan dan kesiapan akses aset digital
2. melaporkan status publikasi aset
3. melaporkan status OCR dan indexing
4. melaporkan potensi preview publik

Bukan untuk:

1. menghitung jumlah klik preview
2. menghitung jumlah download aktual
3. menghitung unique user access

## 23. Aturan Filter Umum Laporan

Semua laporan harus mematuhi validation rules resmi.

Aturan umum:

1. `from_date` dan `to_date` opsional
2. `to_date` tidak boleh lebih kecil dari `from_date`
3. `per_page` hanya 10, 25, 50, 100
4. enum filter harus sesuai schema dan workflow
5. foreign key filter harus valid dan ada di tabel referensi

## 24. Aturan Periode Laporan

Periode laporan fase 1 umumnya berbasis:

1. `created_at`
2. `loan_date`
3. `returned_at`
4. `uploaded_at`

Aturan:

1. setiap halaman laporan harus menjelaskan tanggal acuan yang dipakai
2. jangan mencampur tanggal acuan tanpa label jelas
3. dashboard ringkas dapat bersifat current snapshot dan tidak selalu berbasis range tanggal

## 25. Aturan Snapshot dan Real Time

Pelaporan fase 1 dibagi menjadi:

### 25.1 Snapshot Current State

Contoh:

1. total item tersedia
2. total anggota aktif
3. total pinjaman aktif
4. total OCR failed

### 25.2 Historical Aggregate

Contoh:

1. jumlah pinjaman dalam periode
2. jumlah denda dalam periode
3. popular collections dalam periode
4. asset uploaded dalam periode

Aturan:

1. UI harus membedakan snapshot dan history
2. developer tidak boleh mencampur keduanya tanpa label

## 26. Aturan Output Halaman Laporan

Setiap halaman laporan admin wajib memiliki:

1. page header
2. filter bar
3. kartu ringkasan
4. tabel hasil
5. pagination
6. tombol export jika diizinkan
7. empty state bila data kosong

Aturan:

1. mengikuti 18_UI_UX_STANDARD.md
2. data agregat tampil di atas tabel
3. filter mempertahankan state saat pindah halaman

## 27. Aturan Grafik

Grafik pada fase 1 bersifat opsional dan ringan.

Grafik yang boleh dipakai:

1. distribusi koleksi per jenis
2. distribusi anggota per tipe
3. tren pinjaman per periode, bila cukup data dan relevan
4. status OCR dan indexing

Aturan:

1. grafik tidak menggantikan tabel
2. grafik harus sederhana
3. jika sumber data tidak cukup kuat, cukup tampilkan summary card dan tabel

## 28. Aturan Export Laporan

Ekspor laporan ditangani oleh `ReportExportService`.

### 28.1 Tujuan Export

1. memudahkan unduh data laporan
2. mendukung kebutuhan administrasi
3. memberi output yang konsisten dengan filter aktif

### 28.2 Format yang Direkomendasikan

1. xlsx
2. csv
3. pdf, opsional bertahap

### 28.3 Aturan Umum Export

1. hanya role dengan `reports.export` yang boleh export
2. filter aktif harus ikut ke hasil export
3. file export harus memakai nama file yang konsisten
4. proses export berat dapat dijalankan async
5. bila async dipakai, notifikasi mengikuti 24_NOTIFICATION_RULES.md

### 28.4 Penamaan File Export

Format yang direkomendasikan:
`perpusqu-{report_name}-{yyyyMMdd-HHmmss}.{ext}`

Contoh:

1. `perpusqu-collection-report-20260420-101500.xlsx`
2. `perpusqu-circulation-report-20260420-101500.csv`

### 28.5 ReportExportHistory

Bila fitur ini diaktifkan:

1. simpan exported_by
2. report_type
3. filter_payload
4. file_name
5. created_at

## 29. Aturan Data Scope per Role

Laporan harus disaring sesuai role dan permission.

### 29.1 Super Admin

Boleh melihat semua laporan sesuai permission.

### 29.2 Admin Perpustakaan

Boleh melihat seluruh laporan operasional perpustakaan.

### 29.3 Pustakawan

Boleh melihat laporan koleksi dan digital repository yang relevan.

### 29.4 Petugas Sirkulasi

Boleh melihat laporan sirkulasi dan denda yang relevan.

### 29.5 Operator Repositori Digital

Boleh melihat laporan digital repository.

### 29.6 Pimpinan Perpustakaan

Boleh melihat dashboard dan laporan agregat sesuai permission.

### 29.7 Pengguna OPAC Publik

Tidak memiliki akses ke halaman laporan admin.

## 30. Aturan Data Sensitif dalam Laporan

Laporan tidak boleh menampilkan:

1. password
2. token
3. checksum file
4. path storage privat
5. access rule internal detail yang sensitif
6. error_message OCR mentah ke role yang tidak berwenang
7. data internal user di luar kebutuhan laporan

## 31. Aturan Query dan Performa

Pelaporan harus efisien.

Aturan:

1. gunakan eager loading seperlunya
2. gunakan agregasi SQL di level query yang tepat
3. hindari loop query berulang
4. gunakan pagination untuk data list
5. untuk export besar, gunakan queue bila perlu
6. hindari membaca seluruh tabel tanpa filter bila datanya besar

### 31.1 Query Agregasi yang Direkomendasikan

1. count
2. sum
3. group by
4. join terkontrol
5. subquery ringan bila perlu

### 31.2 Anti Pattern Query

Tidak boleh:

1. menghitung agregat dengan loop PHP besar bila bisa di-SQL-kan
2. memuat semua record lalu baru dipotong
3. melakukan join berlebihan tanpa field yang jelas
4. menggabungkan data dari field yang tidak ada di schema

## 32. Aturan Sinkronisasi dengan Dashboard Alerts

Metrik pada dashboard alert harus konsisten dengan 24_NOTIFICATION_RULES.md.

Contoh:

1. total OCR failed di dashboard alert harus sama definisinya dengan laporan digital repository
2. total overdue di dashboard alert harus sama definisinya dengan laporan sirkulasi
3. total denda outstanding di dashboard alert harus sama definisinya dengan laporan denda

## 33. Aturan Sinkronisasi dengan Search dan OCR

Laporan digital repository boleh memuat:

1. OCR success count
2. OCR failed count
3. indexed count
4. failed index count

Namun:

1. tidak boleh menampilkan query search analytics detail bila belum ada schema pendukung
2. tidak boleh menampilkan jumlah akses preview aktual bila event log belum ada

## 34. Aturan API untuk Laporan

Sesuai 20_API_CONTRACT.md, fase 1 tidak mewajibkan public API pelaporan.

Untuk internal:

1. dashboard counters ringan boleh ada dalam API internal
2. halaman laporan utama tetap berbasis web
3. export dapat dipicu lewat web route
4. bila endpoint laporan internal dibuat, tetap harus tunduk pada contract JSON resmi

## 35. Aturan Error dan Empty State Laporan

Jika data kosong:

1. tampilkan empty state yang sopan
2. jangan tampilkan error teknis
3. beri saran ubah filter

Contoh:

1. Belum ada data yang sesuai filter.
2. Coba ubah periode atau filter laporan.

Jika export gagal:

1. tampilkan flash error
2. jangan tampilkan stack trace
3. log teknis dicatat internal

## 36. Aturan Notifikasi Terkait Laporan

Mengacu ke 24_NOTIFICATION_RULES.md.

Notifikasi yang relevan:

1. export berhasil dijadwalkan
2. export selesai, opsional email
3. export gagal
4. tidak ada data untuk diekspor
5. filter tidak valid

## 37. Aturan Audit Terkait Laporan

Aksi berikut dapat dicatat audit bila diaktifkan:

1. export laporan
2. full report generation async
3. akses laporan sensitif tertentu, bila dianggap perlu

Catatan:

1. view laporan biasa tidak harus selalu dicatat sebagai audit sensitif
2. export lebih layak dicatat karena menghasilkan artefak file

## 38. Report Catalog Resmi

### 38.1 Dashboard Report

Service:
`ReportingDashboardService`

Controller:
`DashboardReportController`

Output:

1. summary cards
2. optional chart ringan
3. role based metrics

### 38.2 Collection Report

Service:
`CollectionReportService`

Controller:
`CollectionReportController`

Output:

1. collection summary
2. collection list
3. export

### 38.3 Member Report

Service:
`MemberReportService`

Controller:
`MemberReportController`

Output:

1. member summary
2. member list
3. export

### 38.4 Circulation Report

Service:
`CirculationReportService`

Controller:
`CirculationReportController`

Output:

1. loan summary
2. overdue summary
3. circulation list
4. export

### 38.5 Fine Report

Service:
`FineReportService`

Controller:
`FineReportController`

Output:

1. fine nominal summary
2. fine list
3. export

### 38.6 Popular Collection Report

Service:
`PopularCollectionReportService`

Controller:
`PopularCollectionReportController`

Output:

1. ranking list
2. summary cards
3. export

### 38.7 Digital Access Report

Service:
`DigitalAccessReportService`

Controller:
`DigitalAccessReportController`

Output:

1. digital asset readiness summary
2. OCR and index status summary
3. asset list
4. export

## 39. Data Source Map per Report

| Laporan | Tabel Utama |
|---|---|
| Dashboard | bibliographic_records, physical_items, members, loans, fines, digital_assets |
| Koleksi | bibliographic_records, collection_types, languages, classifications, publishers |
| Anggota | members, faculties, study_programs |
| Sirkulasi | loans, members, physical_items, bibliographic_records, return_transactions, loan_renewals |
| Denda | fines, members, loans |
| Koleksi Populer | loans, physical_items, bibliographic_records |
| Digital Repository | digital_assets, bibliographic_records, ocr_texts |

## 40. Rumus Metrik Kunci Tambahan

### 40.1 Total Public Preview Asset

Rumus:
jumlah `digital_assets` yang:

1. publication_status = published
2. is_public = 1
3. tidak embargo aktif, atau embargo_until sudah lewat

Catatan:

1. access rule detail yang lebih kompleks dapat mempengaruhi realita akses
2. fase 1 boleh memakai rule dasar ini untuk summary awal bila belum ada evaluasi rule kompleks penuh di query agregat

### 40.2 Total Anggota Blocked

Rumus:
count members where is_blocked = 1

### 40.3 Total Record dengan Aset Digital

Rumus:
count distinct bibliographic_record_id pada digital_assets aktif

### 40.4 Total Record dengan Item Fisik

Rumus:
count distinct bibliographic_record_id pada physical_items aktif

## 41. Batasan Data dan Kejujuran Analitik

Dokumen ini secara tegas menetapkan:

1. tidak ada tabel event akses digital detail pada schema fase 1
2. tidak ada tabel page view OPAC detail pada schema fase 1
3. tidak ada tabel download log detail pada schema fase 1
4. karena itu, laporan "digital access" fase 1 tidak boleh diklaim sebagai laporan usage analytics real user
5. digital access report fase 1 hanya valid sebagai laporan kesiapan akses dan status aset digital

## 42. Skenario Laporan Utama

### 42.1 Skenario A, Admin Membuka Dashboard

1. admin membuka dashboard laporan
2. service mengambil metrik snapshot
3. sistem menampilkan summary cards
4. role menentukan widget yang tampil

### 42.2 Skenario B, Pustakawan Membuka Laporan Koleksi

1. pustakawan memilih filter jenis koleksi
2. service memproses filter
3. sistem menampilkan ringkasan dan tabel
4. user dapat export jika punya izin

### 42.3 Skenario C, Petugas Membuka Laporan Sirkulasi

1. petugas memilih periode
2. service menghitung total aktif, overdue, returned
3. tabel pinjaman tampil
4. export tersedia bila permission ada

### 42.4 Skenario D, Operator Melihat Laporan Digital Repository

1. operator membuka laporan digital
2. service menghitung total asset, OCR failed, indexed, failed index
3. tabel asset tampil
4. operator dapat fokus pada aset gagal proses

### 42.5 Skenario E, Pimpinan Export Laporan

1. pimpinan membuka laporan yang diizinkan
2. filter dipilih
3. export dipicu
4. service membuat file export
5. notifikasi mengikuti aturan sistem

## 43. Testing Requirement Laporan

Pengujian minimum wajib mencakup:

1. dashboard menampilkan angka sesuai rumus
2. laporan koleksi menghitung status publikasi dengan benar
3. laporan anggota menghitung active dan blocked dengan benar
4. laporan sirkulasi menghitung active dan overdue dengan benar
5. laporan denda menghitung nominal outstanding dengan benar
6. laporan koleksi populer mengurutkan berdasarkan jumlah loan
7. laporan digital repository menghitung OCR dan index status dengan benar
8. filter tanggal bekerja benar sesuai kolom acuan
9. filter enum menolak nilai tidak valid
10. pagination bekerja dan menjaga filter
11. export hanya tersedia untuk role dengan permission
12. export memakai filter aktif
13. empty state tampil bila data tidak ada
14. tidak ada data sensitif bocor di laporan
15. digital access report tidak mengklaim usage event yang belum ada tabelnya

## 44. Anti Pattern yang Dilarang

Pelaporan tidak boleh:

1. menampilkan metrik tanpa definisi jelas
2. menghitung angka dari field yang tidak ada di schema
3. mencampur snapshot dan history tanpa label
4. menampilkan usage analytics digital palsu
5. membuat query sangat berat di controller
6. mengabaikan pagination
7. mengekspos data sensitif
8. menampilkan angka dashboard yang berbeda rumus antar halaman
9. menganggap OCR success sama dengan public preview
10. menganggap digital asset published otomatis berarti dapat diakses publik

## 45. Prioritas Implementasi Reporting

### Prioritas P1

1. dashboard report
2. collection report
3. member report
4. circulation report
5. fine report

### Prioritas P2

1. popular collection report
2. digital repository report
3. export xlsx atau csv

### Prioritas P3

1. chart ringan
2. export async yang lebih lengkap
3. ringkasan email report ready
4. analitik lanjutan bila schema bertambah

## 46. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 28_SECURITY_POLICY.md
2. 29_AUDIT_LOG_SPEC.md
3. 30_ERROR_CODE.md
4. 31_TEST_PLAN.md
5. 32_TEST_SCENARIO.md
6. 33_DEPLOYMENT_GUIDE.md
7. 34_ENV_CONFIGURATION.md
8. 38_TREE.md
9. 39_TRACEABILITY_MATRIX.md
10. 41_BACKEND_CHECKLIST.md
11. 42_FRONTEND_CHECKLIST.md
12. 45_SMOKE_TEST_CHECKLIST.md
13. 46_UAT_CHECKLIST.md

Aturan:

1. security policy harus melindungi data laporan sensitif
2. audit log spec harus mengatur export event
3. test plan harus memasukkan uji rumus laporan
4. traceability matrix harus menghubungkan report service, controller, route, dan tabel sumber
5. checklist backend dan frontend harus memeriksa konsistensi filter, summary, tabel, dan export

## 47. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. semua laporan resmi sudah punya definisi tujuan
2. semua metrik utama sudah punya rumus
3. semua sumber data sesuai schema yang ada
4. tidak ada laporan usage digital fiktif
5. role dan permission laporan sudah jelas
6. filter laporan sudah sesuai validation rules
7. export laporan sudah punya aturan yang jelas
8. dashboard alert dan summary konsisten dengan notifikasi dan state machine

## 48. Kesimpulan

Dokumen Reporting Specification ini menetapkan fondasi resmi seluruh laporan dan dashboard PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 24. Dokumen ini memastikan bahwa dashboard, laporan koleksi, laporan anggota, laporan sirkulasi, laporan denda, laporan koleksi populer, dan laporan digital repository dibangun dari data yang benar benar tersedia dalam schema, dengan definisi metrik yang tegas, hak akses yang jelas, dan batasan analitik fase 1 yang jujur. Semua implementasi pelaporan PERPUSQU wajib merujuk dokumen ini.

END OF 25_REPORTING_SPEC.md
