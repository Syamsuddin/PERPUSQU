# 03_ARSITEKTUR_MODULAR.md

## 1. Nama Dokumen
Arsitektur Modular Sistem Informasi Perpustakaan Hibrid Kampus

## 2. Tujuan Dokumen
Dokumen ini menetapkan rancangan arsitektur modular resmi untuk Sistem Informasi Perpustakaan Hibrid Kampus. Dokumen ini menjadi acuan utama dalam menyusun struktur folder, pembagian modul, tanggung jawab layer aplikasi, pola alur data, integrasi komponen pendukung, dan batas antarmuka antarbagian sistem.

Dokumen ini disusun agar:
1. Tim pengembang memiliki struktur kerja yang rapi.
2. Kode aplikasi tidak tumbuh secara acak.
3. Setiap domain bisnis perpustakaan dipisah secara logis.
4. Pengembangan oleh tim kecil tetap terkontrol.
5. Sistem mudah diuji, dirawat, dan dikembangkan.

## 3. Prinsip Arsitektur
Arsitektur aplikasi yang dipilih adalah monolith modular.

Definisi operasional:
1. Seluruh sistem dibangun dalam satu codebase Laravel.
2. Seluruh modul berjalan dalam satu aplikasi utama.
3. Seluruh transaksi utama tersimpan pada satu database MySQL utama.
4. Tiap domain bisnis dipisah dalam modul yang jelas.
5. Komponen pendukung seperti Redis, Meilisearch, OCR, dan storage file diperlakukan sebagai layanan pendukung eksternal.
6. Tidak ada pemisahan menjadi microservices pada fase awal.

Prinsip utama yang wajib dipatuhi:
1. Separation of concern.
2. Single source of truth untuk data inti.
3. Modul dipisah berdasarkan domain bisnis, bukan berdasarkan jenis file semata.
4. Dependensi antar modul harus terbatas dan terkontrol.
5. Semua akses data harus melalui service layer atau repository yang disetujui.
6. Semua proses bisnis penting harus terlacak melalui audit log.
7. Modul harus tetap bisa berkembang tanpa merusak modul lain.

## 4. Tujuan Arsitektur Modular
Arsitektur modular ini dipilih untuk mencapai sasaran berikut:
1. Menjaga codebase tetap bersih.
2. Mempermudah pembagian tugas tim.
3. Mengurangi konflik perubahan kode.
4. Mempermudah pengujian per domain.
5. Mempermudah identifikasi bug.
6. Menjaga kesinambungan antara kebutuhan bisnis dan implementasi teknis.
7. Menyediakan jalur evolusi sistem yang realistis.

## 5. Gambaran Umum Arsitektur
Sistem terdiri atas:
1. Satu aplikasi web utama berbasis Laravel.
2. Satu database MySQL untuk data inti.
3. Satu Redis untuk cache, queue, dan session.
4. Satu Meilisearch untuk indeks pencarian katalog.
5. Satu object storage untuk file digital.
6. Satu web server Nginx sebagai pintu masuk HTTP dan HTTPS.
7. Satu layer worker queue untuk job latar belakang.
8. Satu pipeline OCR dan indexing untuk dokumen digital.

Secara logis, sistem dibagi menjadi:
1. Presentation Layer
2. Application Layer
3. Domain Service Layer
4. Data Access Layer
5. Infrastructure Layer

## 6. Pola Layer Aplikasi

### 6.1 Presentation Layer
Tanggung jawab:
1. Menyajikan halaman web.
2. Menangani input pengguna.
3. Menampilkan validasi dan feedback.
4. Mengarahkan request ke controller atau komponen Livewire.
5. Menampilkan hasil query yang telah diproses di service layer.

Komponen:
1. Blade views
2. Livewire components
3. Form request validation
4. Route definitions
5. Middleware web

Batasan:
1. Tidak boleh memuat logika bisnis berat.
2. Tidak boleh berisi query database kompleks langsung di view.
3. Tidak boleh menangani orkestrasi proses lintas modul.

### 6.2 Application Layer
Tanggung jawab:
1. Menangani use case.
2. Mengatur alur proses dari request ke domain service.
3. Menjalankan otorisasi.
4. Menyusun respons ke UI atau API.
5. Menjembatani interaksi antar layer.

Komponen:
1. Controllers
2. Livewire actions
3. Application services
4. DTO sederhana bila diperlukan
5. Policies dan authorization gate

Batasan:
1. Tidak menyimpan query kompleks yang menyebar.
2. Tidak menjadi tempat penumpukan semua aturan bisnis.
3. Tidak langsung mengakses storage eksternal tanpa service.

### 6.3 Domain Service Layer
Tanggung jawab:
1. Menangani aturan bisnis inti.
2. Menangani transaksi lintas entity.
3. Menangani proses bisnis perpustakaan.
4. Menjaga konsistensi aturan modul.
5. Menjadi pusat logika domain.

Komponen:
1. Service classes
2. Domain actions
3. Business validators
4. Policy helpers
5. Transaction handlers

Contoh:
1. Proses peminjaman koleksi
2. Proses pengembalian dan hitung denda
3. Proses unggah dan publikasi koleksi digital
4. Proses sinkronisasi data anggota
5. Proses indexing katalog ke Meilisearch

### 6.4 Data Access Layer
Tanggung jawab:
1. Menyimpan dan mengambil data dari MySQL.
2. Mengelola query relasional.
3. Mengelola eager loading dan pagination.
4. Menjaga efisiensi akses data.

Komponen:
1. Eloquent models
2. Query builders
3. Repository bila diperlukan
4. Scopes
5. Data mappers bila diperlukan

Batasan:
1. Tidak memuat logika UI.
2. Tidak memuat logika bisnis kompleks lintas domain.
3. Tidak memanggil layanan eksternal langsung.

### 6.5 Infrastructure Layer
Tanggung jawab:
1. Mengelola koneksi ke Redis.
2. Mengelola koneksi ke Meilisearch.
3. Mengelola filesystem dan object storage.
4. Menjalankan queue worker.
5. Menjalankan OCR.
6. Menjalankan scheduler.
7. Menjalankan logging dan monitoring.

Komponen:
1. Redis
2. Meilisearch
3. MinIO atau S3 compatible storage
4. Supervisor
5. Tesseract OCR
6. Nginx
7. Laravel queue, scheduler, log, cache

## 7. Daftar Modul Inti
Modul inti aplikasi ditetapkan sebagai berikut:

1. Core
2. Identity and Access
3. Master Data
4. Catalog
5. Collection
6. Circulation
7. Member
8. Digital Repository
9. OPAC
10. Acquisition
11. Reporting
12. Integration
13. Notification
14. Audit and Monitoring

Setiap modul memiliki batas tanggung jawab yang tegas.

## 8. Definisi Tiap Modul

### 8.1 Modul Core
Fungsi utama:
1. Konfigurasi sistem
2. Profil institusi
3. Tahun operasional
4. Parameter umum aplikasi
5. Data referensi global
6. Dashboard ringkas sistem

Sub domain:
1. App settings
2. Campus profile
3. General configuration
4. System preferences

Ketergantungan:
1. Dipakai semua modul lain

Larangan:
1. Tidak menjadi tempat semua logic umum yang tidak jelas
2. Hanya memuat hal yang benar-benar lintas sistem

### 8.2 Modul Identity and Access
Fungsi utama:
1. Login
2. Logout
3. Session management
4. Role
5. Permission
6. User management
7. Password reset
8. Access control

Sub domain:
1. Users
2. Roles
3. Permissions
4. User-role binding
5. User profile

Ketergantungan:
1. Dipakai semua modul yang butuh autentikasi dan otorisasi

### 8.3 Modul Master Data
Fungsi utama:
1. Pengarang
2. Penerbit
3. Bahasa
4. Kategori subjek
5. Klasifikasi
6. Lokasi rak
7. Program studi
8. Fakultas
9. Jenis koleksi
10. Kondisi item

Tujuan:
1. Menyediakan referensi bersama
2. Menjaga konsistensi data input

Ketergantungan:
1. Dipakai Catalog, Collection, Member, Reporting, Acquisition

### 8.4 Modul Catalog
Fungsi utama:
1. Pencatatan data bibliografis
2. Metadata karya
3. ISBN
4. Judul
5. Pengarang
6. Subjek
7. Kata kunci
8. Edisi
9. Bahasa
10. Deskripsi fisik
11. Sinopsis
12. Cover

Objek utama:
1. Bibliographic Record

Batasan:
1. Modul ini mengelola deskripsi karya
2. Modul ini tidak menyimpan transaksi pinjam
3. Modul ini tidak menyimpan status eksemplar detail

Relasi utama:
1. Satu bibliographic record dapat memiliki banyak item fisik
2. Satu bibliographic record dapat memiliki banyak digital asset

### 8.5 Modul Collection
Fungsi utama:
1. Mengelola eksemplar fisik
2. Barcode atau kode inventaris
3. Nomor induk inventaris
4. Lokasi rak
5. Kondisi
6. Status item
7. Histori item
8. Penandaan item hilang, rusak, atau diperbaiki

Objek utama:
1. Physical Item

Batasan:
1. Collection berfokus pada copy fisik
2. Data bibliografis utama tetap berada di Catalog

### 8.6 Modul Circulation
Fungsi utama:
1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Reservasi
5. Denda
6. Keterlambatan
7. Blokir anggota
8. Histori transaksi

Objek utama:
1. Loan
2. Return
3. Fine
4. Hold or Reservation

Aturan:
1. Seluruh transaksi wajib berbasis item fisik yang valid
2. Status item harus berubah mengikuti transaksi
3. Denda dihitung sesuai aturan konfigurasi

### 8.7 Modul Member
Fungsi utama:
1. Data anggota perpustakaan
2. Status aktif atau nonaktif
3. Kategori anggota
4. Sinkronisasi data mahasiswa atau dosen
5. Riwayat transaksi anggota
6. Status blokir
7. Nomor anggota

Kategori anggota minimal:
1. Mahasiswa
2. Dosen
3. Tenaga kependidikan
4. Alumni bila diaktifkan
5. Tamu bila diaktifkan

### 8.8 Modul Digital Repository
Fungsi utama:
1. Upload file digital
2. Metadata file digital
3. Keterkaitan dengan bibliographic record
4. Preview dokumen
5. Hak akses unduh
6. OCR
7. Pengindeksan isi dokumen
8. Embargo dokumen
9. Status publikasi

Objek utama:
1. Digital Asset
2. Digital Access Rule
3. OCR Text
4. File Version

Aturan:
1. File disimpan di object storage
2. Metadata file disimpan di MySQL
3. Teks OCR disimpan untuk indexing dan pencarian
4. Akses file dibatasi oleh role dan aturan repository

### 8.9 Modul OPAC
Fungsi utama:
1. Pencarian publik
2. Filter pencarian
3. Halaman detail koleksi
4. Status ketersediaan
5. Informasi lokasi rak
6. Informasi akses digital
7. Rekomendasi sederhana
8. Bookmark bila diaktifkan

Karakteristik:
1. Bersifat publik sebagian
2. Bersifat cepat dan ringan
3. Membaca data dari Catalog, Collection, Digital Repository, dan Meilisearch

### 8.10 Modul Acquisition
Fungsi utama:
1. Usulan pengadaan
2. Pengadaan buku
3. Penerimaan koleksi baru
4. Registrasi item baru
5. Sumber pengadaan
6. Hibah
7. Pembelian
8. Rekap pengadaan

Status:
1. Modul ini dapat aktif penuh pada fase 2
2. Pada fase 1 dapat dibatasi pada pencatatan dasar

### 8.11 Modul Reporting
Fungsi utama:
1. Statistik koleksi
2. Statistik anggota
3. Statistik transaksi
4. Koleksi populer
5. Keterlambatan
6. Denda
7. Akses koleksi digital
8. Pertumbuhan koleksi
9. Rekap dashboard pimpinan

Sumber data:
1. MySQL
2. Agregasi query
3. Data index bila relevan
4. Cache untuk laporan berat

### 8.12 Modul Integration
Fungsi utama:
1. Integrasi SIAKAD
2. Import CSV atau Excel
3. Sinkronisasi data anggota
4. Sinkronisasi akun pengguna
5. API internal
6. API eksternal bila diaktifkan

Prinsip:
1. Semua integrasi melewati service layer
2. Tidak ada modul bisnis yang terhubung langsung ke sumber luar tanpa adapter

### 8.13 Modul Notification
Fungsi utama:
1. Notifikasi jatuh tempo
2. Notifikasi keterlambatan
3. Notifikasi reservasi siap ambil
4. Notifikasi unggah koleksi digital
5. Notifikasi sistem

Channel tahap awal:
1. In-app notification
2. Email

Channel fase lanjutan:
1. WhatsApp gateway
2. SMS bila dibutuhkan

### 8.14 Modul Audit and Monitoring
Fungsi utama:
1. Audit log aktivitas penting
2. Log perubahan data kritis
3. Log autentikasi
4. Log transaksi
5. Monitoring job queue
6. Monitoring error

Objek audit minimal:
1. Login dan logout
2. Tambah, ubah, hapus data utama
3. Peminjaman dan pengembalian
4. Upload dan hapus file digital
5. Perubahan role
6. Perubahan konfigurasi sistem

## 9. Hubungan Antar Modul
Relasi antar modul ditetapkan sebagai berikut:

1. Core dipakai oleh semua modul.
2. Identity and Access dipakai oleh semua modul yang memerlukan hak akses.
3. Master Data dipakai oleh Catalog, Collection, Member, Reporting, dan Acquisition.
4. Catalog menjadi induk bagi Collection dan Digital Repository.
5. Collection menjadi sumber item fisik untuk Circulation.
6. Member menjadi sumber aktor transaksi untuk Circulation.
7. OPAC membaca data dari Catalog, Collection, Digital Repository, dan indeks Meilisearch.
8. Reporting membaca agregasi dari Catalog, Collection, Member, Circulation, dan Digital Repository.
9. Integration dapat menulis ke Member, Identity, dan Master Data melalui service terkontrol.
10. Audit and Monitoring menerima event dari seluruh modul.

## 10. Aturan Dependensi Modul
Aturan dependensi resmi:

1. Modul tidak boleh saling memanggil controller modul lain.
2. Modul boleh memakai service modul lain hanya bila disetujui secara arsitektural.
3. Relasi data lintas modul harus dijaga tetap jelas.
4. Integrasi lintas modul harus melalui application service atau event.
5. OPAC tidak boleh memodifikasi data inti transaksi.
6. Reporting tidak boleh menjadi sumber data utama operasional.
7. Tidak boleh membuat circular dependency antar service modul.

## 11. Struktur Folder yang Disarankan
Struktur folder logis yang disarankan di dalam codebase Laravel adalah sebagai berikut:

```text
app/
  Modules/
    Core/
      Http/
        Controllers/
        Requests/
        Livewire/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      Support/
      routes/
        web.php
        api.php
    Identity/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    MasterData/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      DTO/
      routes/
    Catalog/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    Collection/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    Circulation/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    Member/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    DigitalRepository/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      Events/
      Listeners/
      DTO/
      routes/
    OPAC/
      Http/
      Models/
      Services/
      Livewire/
      DTO/
      routes/
    Acquisition/
      Http/
      Models/
      Services/
      Policies/
      Jobs/
      DTO/
      routes/
    Reporting/
      Http/
      Services/
      DTO/
      routes/
    Integration/
      Http/
      Services/
      Jobs/
      DTO/
      routes/
    Notification/
      Services/
      Jobs/
      Events/
      Listeners/
    Audit/
      Services/
      Jobs/
      Listeners/
bootstrap/
config/
database/
  migrations/
  seeders/
resources/
  views/
    layouts/
    components/
    modules/
      core/
      identity/
      master-data/
      catalog/
      collection/
      circulation/
      member/
      digital-repository/
      opac/
      acquisition/
      reporting/
      integration/
routes/
storage/
````

## 12. Pola Routing

Pola routing wajib konsisten.

Aturan:

1. Setiap modul memiliki file route sendiri.
2. Route web admin dipisah dari route OPAC publik.
3. Route API dipisah dari route web.
4. Prefix route harus konsisten per modul.
5. Nama route harus mengikuti pola yang seragam.

Contoh pola nama route:

1. admin.catalog.records.index
2. admin.catalog.records.create
3. admin.catalog.records.store
4. admin.collection.items.index
5. admin.circulation.loans.store
6. admin.members.students.index
7. opac.search.index
8. opac.records.show

## 13. Pola Naming Modul dan Kelas

Standar penamaan:

### 13.1 Modul

Gunakan PascalCase.
Contoh:

1. Catalog
2. Collection
3. Circulation
4. DigitalRepository

### 13.2 Model

Gunakan singular PascalCase.
Contoh:

1. BibliographicRecord
2. PhysicalItem
3. Loan
4. Member
5. DigitalAsset

### 13.3 Service

Gunakan pola nama berbasis aksi atau domain.
Contoh:

1. CatalogSearchService
2. LoanTransactionService
3. ReturnProcessingService
4. DigitalAssetUploadService
5. MemberSyncService

### 13.4 Policy

Contoh:

1. BibliographicRecordPolicy
2. PhysicalItemPolicy
3. LoanPolicy
4. DigitalAssetPolicy

### 13.5 View

Gunakan snake atau kebab case yang konsisten.
Contoh:

1. resources/views/modules/catalog/records/index.blade.php
2. resources/views/modules/circulation/loans/form.blade.php

## 14. Alur Request Umum

Alur request standar aplikasi adalah:

1. User mengakses route.
2. Middleware memeriksa autentikasi dan otorisasi.
3. Controller atau Livewire component menerima request.
4. Form request melakukan validasi input.
5. Controller memanggil application service atau domain service.
6. Service memproses aturan bisnis.
7. Model atau repository mengambil atau menyimpan data.
8. Jika perlu, service mendorong job ke queue.
9. Jika perlu, service mengirim event.
10. Listener mencatat audit log atau notifikasi.
11. Controller mengembalikan response ke view atau redirect.
12. View menampilkan hasil ke user.

## 15. Alur Pencarian OPAC

Alur pencarian OPAC ditetapkan sebagai berikut:

1. User memasukkan kata kunci pada halaman OPAC.
2. Request diterima oleh modul OPAC.
3. OPAC memanggil CatalogSearchService.
4. Service membaca indeks dari Meilisearch.
5. Service mengambil identifier record hasil pencarian.
6. Service mengambil detail utama dari MySQL.
7. Service menggabungkan data bibliografis, status item, dan ketersediaan digital.
8. Hasil diformat untuk tampilan daftar.
9. User membuka detail record.
10. Sistem menampilkan metadata, lokasi rak, jumlah eksemplar, dan akses digital.

Prinsip:

1. Search engine menjadi pintu relevansi dan kecepatan.
2. MySQL tetap menjadi sumber data final.

## 16. Alur Peminjaman Koleksi

Alur bisnis peminjaman item fisik:

1. Petugas membuka modul Circulation.
2. Petugas memilih anggota aktif.
3. Sistem memverifikasi status anggota.
4. Petugas memindai barcode item.
5. Sistem memverifikasi item tersedia.
6. LoanTransactionService memeriksa batas pinjam.
7. Service memeriksa denda aktif atau blokir.
8. Jika lolos, sistem membuat record loan.
9. Status item diubah menjadi dipinjam.
10. Tanggal jatuh tempo dihitung.
11. Audit log dicatat.
12. Notifikasi jatuh tempo dijadwalkan.

## 17. Alur Pengembalian dan Denda

Alur bisnis pengembalian:

1. Petugas memindai item yang dikembalikan.
2. Sistem menemukan transaksi pinjam aktif.
3. ReturnProcessingService menghitung selisih hari.
4. Jika terlambat, denda dihitung berdasar konfigurasi.
5. Status pinjaman ditutup.
6. Status item dikembalikan menjadi tersedia atau diperiksa bila rusak.
7. Audit log dicatat.
8. Histori transaksi anggota diperbarui.

## 18. Alur Unggah Koleksi Digital

Alur bisnis koleksi digital:

1. Petugas memilih bibliographic record atau membuat record baru.
2. Petugas mengunggah file digital.
3. Sistem memvalidasi mime type, ukuran, dan hak akses.
4. File disimpan ke object storage.
5. Metadata file disimpan ke MySQL.
6. Job OCR dan indexing dikirim ke queue bila diperlukan.
7. Hasil OCR disimpan.
8. Meilisearch diperbarui.
9. Audit log dicatat.
10. Akses file dikendalikan oleh policy.

## 19. Pola Komunikasi Antar Modul

Pola komunikasi resmi:

1. Sinkron langsung via service call untuk proses sinkron.
2. Event dan listener untuk proses samping.
3. Queue job untuk proses berat atau tidak perlu real time.

Contoh sinkron:

1. Circulation memanggil MemberValidationService
2. OPAC memanggil CatalogSearchService
3. DigitalRepository memanggil CatalogRecordResolverService

Contoh event:

1. LoanCreated
2. LoanReturned
3. DigitalAssetUploaded
4. UserRoleChanged
5. BibliographicRecordUpdated

Contoh job:

1. RunOcrOnDigitalAssetJob
2. IndexBibliographicRecordJob
3. SendDueDateReminderJob
4. GenerateCoverThumbnailJob
5. ImportMembersJob

## 20. Event Driven Side Effects

Semua efek samping dianjurkan memakai event.

Manfaat:

1. Controller lebih ringan
2. Service lebih fokus
3. Audit dan notifikasi lebih terpisah
4. Ekstensi fitur lebih mudah

Contoh:

1. Setelah LoanCreated, listener mencatat audit log dan menjadwalkan notifikasi.
2. Setelah DigitalAssetUploaded, listener memicu OCR dan indexing.
3. Setelah MemberSynced, listener memperbarui cache referensi.

## 21. Kebijakan Data dan Kepemilikan Data

Kepemilikan data ditetapkan sebagai berikut:

1. Catalog memiliki bibliographic_records.
2. Collection memiliki physical_items.
3. Circulation memiliki loans, returns, fines, holds.
4. Member memiliki member profiles dan status anggota.
5. DigitalRepository memiliki digital_assets, access_rules, ocr_texts.
6. Identity memiliki users, roles, permissions.
7. Core memiliki settings dan reference global.
8. Audit memiliki activity_logs.

Aturan:

1. Satu tabel hanya memiliki satu pemilik domain utama.
2. Modul lain boleh membaca melalui relasi atau service.
3. Perubahan lintas domain harus melalui service yang sah.

## 22. Integrasi dengan Komponen Eksternal

### 22.1 MySQL

Fungsi:

1. Data utama transaksi dan metadata
2. Sumber kebenaran utama

### 22.2 Redis

Fungsi:

1. Cache
2. Queue
3. Session
4. Rate limit

### 22.3 Meilisearch

Fungsi:

1. Indeks pencarian
2. Typo tolerance
3. Filtering
4. Relevansi

### 22.4 Object Storage

Fungsi:

1. Menyimpan PDF, gambar, cover, scan, file karya ilmiah

### 22.5 Tesseract OCR

Fungsi:

1. Mengambil teks dari scan
2. Menambah kualitas pencarian dokumen

### 22.6 PDF.js

Fungsi:

1. Menampilkan preview dokumen digital

## 23. Kebijakan Transaksi Database

Aturan transaksi:

1. Semua proses bisnis penting harus memakai database transaction.
2. Peminjaman dan pengembalian wajib atomic.
3. Perubahan status item dan transaksi pinjam harus berada dalam satu transaction boundary.
4. Proses upload file dan simpan metadata harus dirancang aman terhadap kegagalan parsial.
5. Bila file gagal tersimpan, metadata tidak boleh dianggap final.
6. Bila indexing gagal, data utama tetap sah, lalu job retry digunakan.

## 24. Kebijakan Queue dan Asynchronous Process

Proses berikut wajib menggunakan queue:

1. OCR dokumen
2. Indexing ke Meilisearch
3. Import data besar
4. Pengiriman notifikasi email
5. Pembuatan thumbnail
6. Rekap statistik berat bila diperlukan

Proses berikut sebaiknya sinkron:

1. Simpan data master kecil
2. Peminjaman normal
3. Pengembalian normal
4. Perubahan role
5. Ubah metadata bibliografis dasar

## 25. Kebijakan Cache

Objek yang layak di-cache:

1. Setting sistem
2. Data master yang jarang berubah
3. Facet pencarian tertentu
4. Statistik dashboard ringan
5. Permission map user

Aturan:

1. Cache key harus bernama jelas.
2. Cache harus dibersihkan saat sumber data berubah.
3. Jangan cache data transaksi kritis terlalu lama.

## 26. Kebijakan Keamanan Arsitektur

Aturan keamanan pada level arsitektur:

1. Semua route admin wajib berada di balik autentikasi.
2. Setiap aksi penting wajib melalui authorization policy.
3. File digital privat tidak boleh diakses dengan URL langsung publik.
4. Download file harus melalui pemeriksaan hak akses.
5. Semua input wajib divalidasi di server side.
6. Audit log wajib aktif untuk aksi kritis.
7. Queue worker, Redis, dan Meilisearch tidak dibuka ke publik.
8. Modul Integration wajib memverifikasi sumber data eksternal.
9. Error detail tidak boleh ditampilkan di production.
10. Aksi hapus kritis dianjurkan memakai soft delete bila cocok.

## 27. Kebijakan Logging dan Audit

Tingkat logging:

1. Application log
2. Security log
3. Transaction log
4. Activity log
5. Queue failure log
6. Integration log

Aktivitas yang wajib diaudit:

1. Login dan logout
2. Gagal login berulang
3. Perubahan role
4. Tambah, ubah, hapus bibliographic record
5. Tambah, ubah, hapus physical item
6. Peminjaman dan pengembalian
7. Upload, ubah, hapus file digital
8. Perubahan konfigurasi sistem
9. Import massal
10. Sinkronisasi data anggota

## 28. Kebijakan Uji Modular

Setiap modul harus dapat diuji minimal pada level berikut:

1. Unit test untuk service penting
2. Feature test untuk route utama
3. Policy test untuk otorisasi
4. Integration test untuk proses lintas modul penting
5. UI test dasar untuk halaman kritis

Prioritas test:

1. Identity and Access
2. Catalog
3. Collection
4. Circulation
5. Digital Repository
6. OPAC

## 29. Kebijakan Evolusi Arsitektur

Arsitektur ini dirancang untuk tumbuh bertahap.

Evolusi yang diizinkan:

1. Memisahkan search server dari app server
2. Memisahkan object storage dari app server
3. Menambah worker khusus OCR
4. Menambah API publik terbatas
5. Menambah SSO kampus
6. Menambah modul akuisisi penuh
7. Menambah analitik lanjutan

Evolusi yang tidak boleh dilakukan tanpa redesign menyeluruh:

1. Menambah framework frontend kedua besar di codebase yang sama
2. Membuat dua sumber data transaksi utama
3. Membiarkan tiap modul punya pola sendiri tanpa standar
4. Memindahkan logika bisnis inti ke view atau controller

## 30. Pola Implementasi Tim Kecil

Untuk tim kecil kampus, pola kerja yang dianjurkan:

1. Satu analis atau lead developer menjaga konsistensi modul.
2. Satu backend developer dapat menangani 2 sampai 3 modul per fase.
3. Satu frontend developer fokus pada Blade, Livewire, Bootstrap, dan konsistensi layout.
4. Satu QA atau tester internal memverifikasi alur utama.
5. Dokumentasi route, view, schema, dan workflow wajib tumbuh bersama kode.

Strategi pengerjaan:

1. Bangun Core dan Identity lebih dulu.
2. Lanjut Catalog dan Master Data.
3. Lanjut Collection dan Member.
4. Lanjut Circulation.
5. Lanjut Digital Repository.
6. Lanjut OPAC.
7. Lanjut Reporting dan Integration.

## 31. Kesimpulan Arsitektur

Arsitektur monolith modular untuk Sistem Informasi Perpustakaan Hibrid Kampus adalah pilihan paling realistis untuk lingkungan kampus dengan tim pengembang kecil sampai menengah. Arsitektur ini memberi keseimbangan antara kesederhanaan operasional dan kerapian domain. Dengan pembagian modul yang jelas, layer aplikasi yang tegas, dan integrasi pendukung yang terkontrol, sistem dapat dibangun secara bertahap tanpa kehilangan kualitas struktur.

Dokumen ini menjadi acuan resmi bagi penyusunan PRD, SRS, schema database, menu map, route map, controller map, service layer, workflow state machine, serta rencana implementasi coding.

END OF 03_ARSITEKTUR_MODULAR.md