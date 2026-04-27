# 10_VIEW_MAP.md

## 1. Nama Dokumen
View Map Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint peta view dan halaman aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan halaman, layout, navigasi, komponen UI, route target, controller response, dan traceability

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan seluruh view, halaman, komponen visual utama, dan relasi antarhalaman dalam aplikasi PERPUSQU. Dokumen ini menjadi acuan wajib agar tidak ada menu tanpa halaman, tidak ada route tanpa target view, dan tidak ada halaman tanpa dasar use case, permission, serta modul yang jelas.

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

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Semua halaman harus mengikuti route map.
5. Semua halaman harus mengikuti role permission matrix.
6. Semua halaman harus diturunkan dari use case dan menu map.
7. Tidak boleh ada halaman utama tanpa route, controller, dan permission yang jelas.
8. Tidak boleh ada halaman admin yang terbuka untuk publik tanpa autentikasi.
9. Tidak boleh ada halaman publik yang menampilkan data yang belum dipublikasikan.

## 4. Prinsip Umum Perancangan View
Prinsip view PERPUSQU adalah:

1. Satu route utama harus memiliki satu target halaman yang jelas.
2. Satu halaman harus mendukung satu tujuan kerja utama.
3. Halaman daftar wajib memiliki search, filter, pagination, dan informasi jumlah data bila relevan.
4. Halaman detail wajib menampilkan konteks data secara lengkap.
5. Halaman form wajib menampilkan validasi dan pesan error yang jelas.
6. Halaman transaksi sirkulasi wajib dioptimalkan untuk kecepatan operasional.
7. Halaman OPAC wajib sederhana, cepat, dan fokus pada pencarian.
8. Halaman admin wajib memakai layout dashboard yang konsisten.
9. Halaman publik wajib memakai layout publik yang ringan.
10. Halaman tidak boleh memuat logika bisnis berat di view.

## 5. Struktur Layout Resmi
PERPUSQU memiliki layout utama berikut:

1. Layout Admin
2. Layout Auth
3. Layout OPAC Publik
4. Komponen Partial Bersama

## 6. Daftar Layout Utama

### 6.1 Layout Admin
Nama File View:
`resources/views/layouts/admin.blade.php`

Dipakai oleh:
1. Dashboard
2. Master Data
3. Katalog
4. Koleksi Fisik
5. Anggota
6. Sirkulasi
7. Repositori Digital
8. Laporan
9. Audit dan Monitoring
10. Pengaturan Sistem
11. Manajemen Akses
12. Profil Pengguna

Komponen minimal:
1. Sidebar kiri
2. Header atas
3. Breadcrumb
4. Area content
5. Notifikasi flash
6. Footer
7. Modal global opsional
8. Slot action page header

### 6.2 Layout Auth
Nama File View:
`resources/views/layouts/auth.blade.php`

Dipakai oleh:
1. Halaman login

Komponen minimal:
1. Branding PERPUSQU
2. Form container
3. Pesan error global
4. Footer ringan

### 6.3 Layout OPAC Publik
Nama File View:
`resources/views/layouts/opac.blade.php`

Dipakai oleh:
1. Beranda OPAC
2. Hasil pencarian
3. Detail koleksi
4. Tentang perpustakaan
5. Bantuan pencarian

Komponen minimal:
1. Header publik
2. Search bar utama
3. Content area
4. Footer publik

## 7. Daftar Komponen Partial Bersama
Komponen partial bersama yang wajib tersedia:

1. `resources/views/components/alerts/success.blade.php`
2. `resources/views/components/alerts/error.blade.php`
3. `resources/views/components/forms/input-text.blade.php`
4. `resources/views/components/forms/input-select.blade.php`
5. `resources/views/components/forms/input-textarea.blade.php`
6. `resources/views/components/forms/input-file.blade.php`
7. `resources/views/components/forms/input-date.blade.php`
8. `resources/views/components/forms/input-checkbox.blade.php`
9. `resources/views/components/tables/data-table-header.blade.php`
10. `resources/views/components/tables/data-table-pagination.blade.php`
11. `resources/views/components/tables/empty-state.blade.php`
12. `resources/views/components/cards/stat-card.blade.php`
13. `resources/views/components/layouts/breadcrumb.blade.php`
14. `resources/views/components/layouts/page-header.blade.php`
15. `resources/views/components/modals/confirm-delete.blade.php`
16. `resources/views/components/modals/confirm-action.blade.php`
17. `resources/views/components/badges/status-badge.blade.php`

Catatan:
1. Nama komponen dapat disesuaikan teknis saat implementasi.
2. Konsep dan tujuan komponen wajib dipertahankan.

## 8. Klasifikasi View
View dalam PERPUSQU dibagi menjadi:

1. Page view
2. Partial view
3. Modal view
4. Export view bila diperlukan
5. Error view
6. Auth view
7. OPAC public view

## 9. Aturan Penamaan View
Aturan penamaan file view:

1. Gunakan huruf kecil
2. Gunakan folder per modul
3. Gunakan nama file yang menjelaskan fungsi halaman
4. Gunakan pola index, create, edit, show, form, partial
5. Halaman admin diletakkan di `resources/views/modules`
6. Halaman publik OPAC diletakkan di `resources/views/modules/opac`

Contoh:
1. `resources/views/modules/catalog/records/index.blade.php`
2. `resources/views/modules/catalog/records/create.blade.php`
3. `resources/views/modules/catalog/records/edit.blade.php`
4. `resources/views/modules/catalog/records/show.blade.php`
5. `resources/views/modules/opac/search/index.blade.php`

## 10. Daftar View Area Auth

### 10.1 Halaman Login
Kode View:
VW-AUTH-001

Nama File:
`resources/views/auth/login.blade.php`

Layout:
`layouts/auth`

Route:
`auth.login`

Use Case:
`UC-IDA-001 Login ke sistem`

Permission:
Publik internal

Elemen utama:
1. Logo atau nama PERPUSQU
2. Judul login
3. Input email atau username
4. Input password
5. Tombol masuk
6. Pesan error login
7. Informasi singkat institusi

## 11. Daftar View Area Dashboard

### 11.1 Dashboard Admin
Kode View:
VW-DASH-001

Nama File:
`resources/views/modules/dashboard/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.dashboard.index`

Menu:
`MNU-DASH-001`

Use Case:
1. UC-CORE-004
2. UC-REP-001

Permission:
`core.view_dashboard`

Elemen utama:
1. Page header
2. Breadcrumb
3. Kartu statistik sesuai role
4. Quick action sesuai role
5. Ringkasan aktivitas terbaru
6. Ringkasan koleksi
7. Ringkasan transaksi
8. Ringkasan aset digital
9. Shortcut OPAC

## 12. Daftar View Modul Master Data

### 12.1 Struktur Standar View Master Data
Setiap entitas master data minimal memiliki:
1. Halaman daftar
2. Halaman form tambah
3. Halaman form edit

Bila diputuskan efisien pada implementasi:
1. Form tambah dan edit dapat berbagi partial form
2. Create dan edit dapat memakai satu partial `_form.blade.php`

### 12.2 Pengarang

#### 12.2.1 Daftar Pengarang
Kode View:
VW-MAS-001

Nama File:
`resources/views/modules/master-data/authors/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.master_data.authors.index`

Menu:
`MNU-MAS-001`

Use Case:
`UC-MAS-001`

Elemen utama:
1. Page header
2. Tombol tambah
3. Tabel daftar pengarang
4. Search
5. Filter status bila ada
6. Pagination
7. Aksi edit
8. Aksi hapus atau nonaktif

#### 12.2.2 Form Tambah Pengarang
Kode View:
VW-MAS-002

Nama File:
`resources/views/modules/master-data/authors/create.blade.php`

Route:
`admin.master_data.authors.create`

Elemen utama:
1. Form nama pengarang
2. Form keterangan bila ada
3. Tombol simpan
4. Tombol kembali

#### 12.2.3 Form Edit Pengarang
Kode View:
VW-MAS-003

Nama File:
`resources/views/modules/master-data/authors/edit.blade.php`

Route:
`admin.master_data.authors.edit`

Elemen utama:
1. Form nama pengarang
2. Status aktif
3. Tombol update
4. Tombol kembali

### 12.3 Penerbit

#### 12.3.1 Daftar Penerbit
Kode View:
VW-MAS-004

Nama File:
`resources/views/modules/master-data/publishers/index.blade.php`

Route:
`admin.master_data.publishers.index`

#### 12.3.2 Form Tambah Penerbit
Kode View:
VW-MAS-005

Nama File:
`resources/views/modules/master-data/publishers/create.blade.php`

Route:
`admin.master_data.publishers.create`

#### 12.3.3 Form Edit Penerbit
Kode View:
VW-MAS-006

Nama File:
`resources/views/modules/master-data/publishers/edit.blade.php`

Route:
`admin.master_data.publishers.edit`

### 12.4 Bahasa

#### 12.4.1 Daftar Bahasa
Kode View:
VW-MAS-007

Nama File:
`resources/views/modules/master-data/languages/index.blade.php`

Route:
`admin.master_data.languages.index`

#### 12.4.2 Form Tambah Bahasa
Kode View:
VW-MAS-008

Nama File:
`resources/views/modules/master-data/languages/create.blade.php`

Route:
`admin.master_data.languages.create`

#### 12.4.3 Form Edit Bahasa
Kode View:
VW-MAS-009

Nama File:
`resources/views/modules/master-data/languages/edit.blade.php`

Route:
`admin.master_data.languages.edit`

### 12.5 Klasifikasi

#### 12.5.1 Daftar Klasifikasi
Kode View:
VW-MAS-010

Nama File:
`resources/views/modules/master-data/classifications/index.blade.php`

Route:
`admin.master_data.classifications.index`

#### 12.5.2 Form Tambah Klasifikasi
Kode View:
VW-MAS-011

Nama File:
`resources/views/modules/master-data/classifications/create.blade.php`

Route:
`admin.master_data.classifications.create`

#### 12.5.3 Form Edit Klasifikasi
Kode View:
VW-MAS-012

Nama File:
`resources/views/modules/master-data/classifications/edit.blade.php`

Route:
`admin.master_data.classifications.edit`

### 12.6 Subjek

#### 12.6.1 Daftar Subjek
Kode View:
VW-MAS-013

Nama File:
`resources/views/modules/master-data/subjects/index.blade.php`

Route:
`admin.master_data.subjects.index`

#### 12.6.2 Form Tambah Subjek
Kode View:
VW-MAS-014

Nama File:
`resources/views/modules/master-data/subjects/create.blade.php`

Route:
`admin.master_data.subjects.create`

#### 12.6.3 Form Edit Subjek
Kode View:
VW-MAS-015

Nama File:
`resources/views/modules/master-data/subjects/edit.blade.php`

Route:
`admin.master_data.subjects.edit`

### 12.7 Jenis Koleksi

#### 12.7.1 Daftar Jenis Koleksi
Kode View:
VW-MAS-016

Nama File:
`resources/views/modules/master-data/collection-types/index.blade.php`

Route:
`admin.master_data.collection_types.index`

#### 12.7.2 Form Tambah Jenis Koleksi
Kode View:
VW-MAS-017

Nama File:
`resources/views/modules/master-data/collection-types/create.blade.php`

Route:
`admin.master_data.collection_types.create`

#### 12.7.3 Form Edit Jenis Koleksi
Kode View:
VW-MAS-018

Nama File:
`resources/views/modules/master-data/collection-types/edit.blade.php`

Route:
`admin.master_data.collection_types.edit`

### 12.8 Lokasi Rak

#### 12.8.1 Daftar Lokasi Rak
Kode View:
VW-MAS-019

Nama File:
`resources/views/modules/master-data/rack-locations/index.blade.php`

Route:
`admin.master_data.rack_locations.index`

#### 12.8.2 Form Tambah Lokasi Rak
Kode View:
VW-MAS-020

Nama File:
`resources/views/modules/master-data/rack-locations/create.blade.php`

Route:
`admin.master_data.rack_locations.create`

#### 12.8.3 Form Edit Lokasi Rak
Kode View:
VW-MAS-021

Nama File:
`resources/views/modules/master-data/rack-locations/edit.blade.php`

Route:
`admin.master_data.rack_locations.edit`

### 12.9 Fakultas

#### 12.9.1 Daftar Fakultas
Kode View:
VW-MAS-022

Nama File:
`resources/views/modules/master-data/faculties/index.blade.php`

Route:
`admin.master_data.faculties.index`

#### 12.9.2 Form Tambah Fakultas
Kode View:
VW-MAS-023

Nama File:
`resources/views/modules/master-data/faculties/create.blade.php`

Route:
`admin.master_data.faculties.create`

#### 12.9.3 Form Edit Fakultas
Kode View:
VW-MAS-024

Nama File:
`resources/views/modules/master-data/faculties/edit.blade.php`

Route:
`admin.master_data.faculties.edit`

### 12.10 Program Studi

#### 12.10.1 Daftar Program Studi
Kode View:
VW-MAS-025

Nama File:
`resources/views/modules/master-data/study-programs/index.blade.php`

Route:
`admin.master_data.study_programs.index`

#### 12.10.2 Form Tambah Program Studi
Kode View:
VW-MAS-026

Nama File:
`resources/views/modules/master-data/study-programs/create.blade.php`

Route:
`admin.master_data.study_programs.create`

#### 12.10.3 Form Edit Program Studi
Kode View:
VW-MAS-027

Nama File:
`resources/views/modules/master-data/study-programs/edit.blade.php`

Route:
`admin.master_data.study_programs.edit`

### 12.11 Kondisi Item

#### 12.11.1 Daftar Kondisi Item
Kode View:
VW-MAS-028

Nama File:
`resources/views/modules/master-data/item-conditions/index.blade.php`

Route:
`admin.master_data.item_conditions.index`

#### 12.11.2 Form Tambah Kondisi Item
Kode View:
VW-MAS-029

Nama File:
`resources/views/modules/master-data/item-conditions/create.blade.php`

Route:
`admin.master_data.item_conditions.create`

#### 12.11.3 Form Edit Kondisi Item
Kode View:
VW-MAS-030

Nama File:
`resources/views/modules/master-data/item-conditions/edit.blade.php`

Route:
`admin.master_data.item_conditions.edit`

## 13. Daftar View Modul Katalog

### 13.1 Daftar Katalog
Kode View:
VW-CAT-001

Nama File:
`resources/views/modules/catalog/records/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.catalog.records.index`

Menu:
`MNU-CAT-001`

Use Case:
1. UC-CAT-003
2. UC-CAT-004

Elemen utama:
1. Search judul, ISBN, pengarang, subjek
2. Filter jenis koleksi
3. Filter bahasa
4. Filter tahun
5. Filter status publikasi
6. Tabel daftar bibliographic record
7. Tombol tambah katalog
8. Aksi lihat detail
9. Aksi edit
10. Aksi publish atau unpublish

### 13.2 Form Tambah Katalog
Kode View:
VW-CAT-002

Nama File:
`resources/views/modules/catalog/records/create.blade.php`

Route:
`admin.catalog.records.create`

Menu:
`MNU-CAT-002`

Use Case:
`UC-CAT-001`

Elemen utama:
1. Form metadata bibliografi
2. Input judul
3. Input ISBN
4. Multi select pengarang
5. Select penerbit
6. Input tahun terbit
7. Select bahasa
8. Multi select subjek
9. Select klasifikasi
10. Select jenis koleksi
11. Input edisi
12. Textarea sinopsis
13. Upload cover
14. Tombol simpan
15. Tombol simpan dan lanjut item
16. Tombol batal

### 13.3 Detail Katalog
Kode View:
VW-CAT-003

Nama File:
`resources/views/modules/catalog/records/show.blade.php`

Route:
`admin.catalog.records.show`

Menu:
`MNU-CAT-003`

Use Case:
`UC-CAT-003`

Elemen utama:
1. Ringkasan bibliographic record
2. Metadata lengkap
3. Daftar pengarang
4. Daftar subjek
5. Informasi item fisik terkait
6. Informasi aset digital terkait
7. Status publikasi
8. Tombol edit
9. Tombol tambah item
10. Tombol unggah aset digital

### 13.4 Form Edit Katalog
Kode View:
VW-CAT-004

Nama File:
`resources/views/modules/catalog/records/edit.blade.php`

Route:
`admin.catalog.records.edit`

Menu:
`MNU-CAT-004`

Use Case:
`UC-CAT-002`

Elemen utama:
1. Form metadata bibliografi
2. Nilai data lama
3. Upload ganti cover
4. Tombol update
5. Tombol batal

## 14. Daftar View Modul Koleksi Fisik

### 14.1 Daftar Item
Kode View:
VW-COL-001

Nama File:
`resources/views/modules/collections/items/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.collections.items.index`

Menu:
`MNU-COL-001`

Use Case:
1. UC-COL-003
2. UC-COL-004

Elemen utama:
1. Search barcode, kode inventaris, judul
2. Filter lokasi rak
3. Filter status item
4. Filter kondisi item
5. Tabel item
6. Tombol tambah item
7. Aksi detail
8. Aksi edit
9. Aksi ubah status

### 14.2 Form Tambah Item
Kode View:
VW-COL-002

Nama File:
`resources/views/modules/collections/items/create.blade.php`

Route:
`admin.collections.items.create`

Menu:
`MNU-COL-002`

Use Case:
`UC-COL-001`

Elemen utama:
1. Pilih bibliographic record
2. Input barcode
3. Input kode inventaris
4. Pilih lokasi rak
5. Pilih kondisi item
6. Pilih status awal
7. Tombol simpan

### 14.3 Detail Item
Kode View:
VW-COL-003

Nama File:
`resources/views/modules/collections/items/show.blade.php`

Route:
`admin.collections.items.show`

Menu:
`MNU-COL-003`

Use Case:
`UC-COL-003`

Elemen utama:
1. Informasi item
2. Relasi bibliographic record
3. Lokasi rak
4. Status
5. Kondisi
6. Riwayat transaksi singkat
7. Tombol edit
8. Tombol histori

### 14.4 Form Edit Item
Kode View:
VW-COL-004

Nama File:
`resources/views/modules/collections/items/edit.blade.php`

Route:
`admin.collections.items.edit`

Menu:
`MNU-COL-004`

Use Case:
1. UC-COL-002
2. UC-COL-005

Elemen utama:
1. Form edit item
2. Ubah lokasi rak
3. Ubah kondisi
4. Ubah status
5. Tombol update

### 14.5 Histori Item
Kode View:
VW-COL-005

Nama File:
`resources/views/modules/collections/items/history.blade.php`

Route:
`admin.collections.items.history`

Menu:
`MNU-COL-005`

Use Case:
`UC-COL-006`

Elemen utama:
1. Ringkasan item
2. Riwayat perubahan status
3. Riwayat pinjam kembali
4. Riwayat audit singkat

## 15. Daftar View Modul Anggota

### 15.1 Daftar Anggota
Kode View:
VW-MEM-001

Nama File:
`resources/views/modules/members/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.members.index`

Menu:
`MNU-MEM-001`

Use Case:
1. UC-MEM-003
2. UC-MEM-004

Elemen utama:
1. Search nama, nomor anggota, identitas internal
2. Filter kategori anggota
3. Filter status aktif
4. Filter blokir
5. Tabel anggota
6. Tombol tambah anggota
7. Aksi detail
8. Aksi edit
9. Aksi blokir atau buka blokir

### 15.2 Form Tambah Anggota
Kode View:
VW-MEM-002

Nama File:
`resources/views/modules/members/create.blade.php`

Route:
`admin.members.create`

Menu:
`MNU-MEM-002`

Use Case:
`UC-MEM-001`

Elemen utama:
1. Form identitas anggota
2. Pilih kategori anggota
3. Input nomor anggota
4. Pilih fakultas
5. Pilih program studi
6. Input kontak
7. Status aktif
8. Tombol simpan

### 15.3 Detail Anggota
Kode View:
VW-MEM-003

Nama File:
`resources/views/modules/members/show.blade.php`

Route:
`admin.members.show`

Menu:
`MNU-MEM-003`

Use Case:
`UC-MEM-003`

Elemen utama:
1. Ringkasan profil anggota
2. Status aktif
3. Status blokir
4. Histori pinjaman singkat
5. Tombol edit
6. Tombol histori

### 15.4 Form Edit Anggota
Kode View:
VW-MEM-004

Nama File:
`resources/views/modules/members/edit.blade.php`

Route:
`admin.members.edit`

Menu:
`MNU-MEM-004`

Use Case:
1. UC-MEM-002
2. UC-MEM-005
3. UC-MEM-006

Elemen utama:
1. Form edit anggota
2. Status aktif atau nonaktif
3. Status blokir
4. Tombol update

### 15.5 Histori Anggota
Kode View:
VW-MEM-005

Nama File:
`resources/views/modules/members/history.blade.php`

Route:
`admin.members.history`

Menu:
`MNU-MEM-005`

Use Case:
`UC-MEM-007`

Elemen utama:
1. Ringkasan anggota
2. Daftar histori pinjaman
3. Daftar keterlambatan dan denda
4. Riwayat perubahan status anggota

## 16. Daftar View Modul Sirkulasi

### 16.1 Halaman Peminjaman
Kode View:
VW-CIR-001

Nama File:
`resources/views/modules/circulation/loans/create.blade.php`

Layout:
`layouts/admin`

Route:
`admin.circulation.loans.create`

Menu:
`MNU-CIR-001`

Use Case:
`UC-CIR-001`

Elemen utama:
1. Cari atau pilih anggota
2. Informasi status anggota
3. Input atau scan barcode item
4. Daftar item yang akan dipinjam
5. Informasi batas pinjam
6. Tanggal jatuh tempo
7. Tombol proses pinjam
8. Notifikasi error operasional

### 16.2 Halaman Pengembalian
Kode View:
VW-CIR-002

Nama File:
`resources/views/modules/circulation/returns/create.blade.php`

Route:
`admin.circulation.returns.create`

Menu:
`MNU-CIR-002`

Use Case:
`UC-CIR-002`

Elemen utama:
1. Input atau scan barcode item
2. Informasi pinjaman aktif
3. Tanggal jatuh tempo
4. Jumlah hari terlambat
5. Denda
6. Kondisi item saat kembali
7. Tombol proses pengembalian

### 16.3 Halaman Perpanjangan
Kode View:
VW-CIR-003

Nama File:
`resources/views/modules/circulation/renewals/index.blade.php`

Route:
`admin.circulation.renewals.index`

Menu:
`MNU-CIR-003`

Use Case:
`UC-CIR-003`

Elemen utama:
1. Daftar pinjaman yang dapat diperpanjang
2. Search anggota atau item
3. Informasi jatuh tempo lama
4. Tombol perpanjang
5. Informasi jatuh tempo baru

### 16.4 Daftar Pinjaman Aktif
Kode View:
VW-CIR-004

Nama File:
`resources/views/modules/circulation/active-loans/index.blade.php`

Route:
`admin.circulation.active_loans.index`

Menu:
`MNU-CIR-004`

Use Case:
`UC-CIR-004`

Elemen utama:
1. Search anggota atau item
2. Filter keterlambatan
3. Filter kategori anggota
4. Tabel pinjaman aktif
5. Aksi lihat detail
6. Aksi perpanjang bila diizinkan

### 16.5 Detail Pinjaman
Kode View:
VW-CIR-005

Nama File:
`resources/views/modules/circulation/loans/show.blade.php`

Route:
`admin.circulation.loans.show`

Use Case:
`UC-CIR-004`

Elemen utama:
1. Detail anggota
2. Detail item
3. Tanggal pinjam
4. Tanggal jatuh tempo
5. Status pinjaman
6. Riwayat perpanjangan

### 16.6 Histori Sirkulasi
Kode View:
VW-CIR-006

Nama File:
`resources/views/modules/circulation/history/index.blade.php`

Route:
`admin.circulation.history.index`

Menu:
`MNU-CIR-005`

Use Case:
`UC-CIR-005`

Elemen utama:
1. Search transaksi
2. Filter periode
3. Filter petugas
4. Filter kategori anggota
5. Tabel histori transaksi

### 16.7 Denda dan Keterlambatan
Kode View:
VW-CIR-007

Nama File:
`resources/views/modules/circulation/fines/index.blade.php`

Route:
`admin.circulation.fines.index`

Menu:
`MNU-CIR-006`

Use Case:
`UC-CIR-006`

Elemen utama:
1. Filter periode
2. Filter status denda
3. Daftar keterlambatan
4. Daftar denda
5. Ringkasan total

## 17. Daftar View Modul Repositori Digital

### 17.1 Daftar Aset Digital
Kode View:
VW-DIG-001

Nama File:
`resources/views/modules/digital-repository/assets/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.digital.assets.index`

Menu:
`MNU-DIG-001`

Use Case:
`UC-DIG-003`

Elemen utama:
1. Search judul atau nama file
2. Filter status publikasi
3. Filter OCR status
4. Filter jenis file
5. Tabel aset digital
6. Tombol unggah
7. Aksi detail
8. Aksi edit

### 17.2 Form Unggah Aset Digital
Kode View:
VW-DIG-002

Nama File:
`resources/views/modules/digital-repository/assets/create.blade.php`

Route:
`admin.digital.assets.create`

Menu:
`MNU-DIG-002`

Use Case:
`UC-DIG-001`

Elemen utama:
1. Pilih bibliographic record
2. Upload file
3. Metadata file
4. Hak akses awal
5. Status publikasi
6. Embargo dasar bila aktif
7. Tombol simpan

### 17.3 Detail Aset Digital
Kode View:
VW-DIG-003

Nama File:
`resources/views/modules/digital-repository/assets/show.blade.php`

Route:
`admin.digital.assets.show`

Menu:
`MNU-DIG-003`

Use Case:
1. UC-DIG-003
2. UC-DIG-004

Elemen utama:
1. Ringkasan file
2. Metadata file
3. Relasi bibliographic record
4. Status publikasi
5. Status OCR
6. Preview PDF atau file
7. Tombol edit
8. Tombol OCR
9. Tombol reindex

### 17.4 Form Edit Aset Digital
Kode View:
VW-DIG-004

Nama File:
`resources/views/modules/digital-repository/assets/edit.blade.php`

Route:
`admin.digital.assets.edit`

Menu:
`MNU-DIG-004`

Use Case:
1. UC-DIG-002
2. UC-DIG-005
3. UC-DIG-006

Elemen utama:
1. Update metadata
2. Update rule akses
3. Publish atau unpublish
4. Status embargo
5. Tombol update

### 17.5 Halaman OCR dan Indexing
Kode View:
VW-DIG-005

Nama File:
`resources/views/modules/digital-repository/assets/processing.blade.php`

Route:
Terhubung dari detail aset atau daftar aset

Menu:
`MNU-DIG-005`

Use Case:
1. UC-DIG-007
2. UC-DIG-008

Elemen utama:
1. Daftar aset dengan status OCR
2. Tombol run OCR
3. Tombol reindex
4. Status proses
5. Log hasil proses ringkas

## 18. Daftar View Modul Laporan

### 18.1 Dashboard Statistik
Kode View:
VW-REP-001

Nama File:
`resources/views/modules/reports/dashboard.blade.php`

Layout:
`layouts/admin`

Route:
`admin.reports.dashboard`

Menu:
`MNU-REP-001`

Use Case:
`UC-REP-001`

Elemen utama:
1. Kartu statistik
2. Grafik tren
3. Ringkasan koleksi
4. Ringkasan sirkulasi
5. Ringkasan akses digital

### 18.2 Laporan Koleksi
Kode View:
VW-REP-002

Nama File:
`resources/views/modules/reports/collections/index.blade.php`

Route:
`admin.reports.collections.index`

Menu:
`MNU-REP-002`

Use Case:
`UC-REP-002`

Elemen utama:
1. Filter periode
2. Filter jenis koleksi
3. Tabel laporan
4. Ringkasan total
5. Tombol export

### 18.3 Laporan Anggota
Kode View:
VW-REP-003

Nama File:
`resources/views/modules/reports/members/index.blade.php`

Route:
`admin.reports.members.index`

Menu:
`MNU-REP-003`

Use Case:
`UC-REP-003`

### 18.4 Laporan Sirkulasi
Kode View:
VW-REP-004

Nama File:
`resources/views/modules/reports/circulation/index.blade.php`

Route:
`admin.reports.circulation.index`

Menu:
`MNU-REP-004`

Use Case:
`UC-REP-004`

### 18.5 Laporan Denda
Kode View:
VW-REP-005

Nama File:
`resources/views/modules/reports/fines/index.blade.php`

Route:
`admin.reports.fines.index`

Menu:
`MNU-REP-005`

Use Case:
`UC-REP-005`

### 18.6 Laporan Koleksi Populer
Kode View:
VW-REP-006

Nama File:
`resources/views/modules/reports/popular-collections/index.blade.php`

Route:
`admin.reports.popular_collections.index`

Menu:
`MNU-REP-006`

Use Case:
`UC-REP-006`

### 18.7 Laporan Akses Digital
Kode View:
VW-REP-007

Nama File:
`resources/views/modules/reports/digital-access/index.blade.php`

Route:
`admin.reports.digital_access.index`

Menu:
`MNU-REP-007`

Use Case:
`UC-REP-007`

Catatan untuk seluruh halaman laporan:
1. Wajib ada filter periode
2. Wajib ada ringkasan total
3. Tombol export tampil jika role memiliki permission `reports.export`

## 19. Daftar View Modul Audit dan Monitoring

### 19.1 Audit Log
Kode View:
VW-AUD-001

Nama File:
`resources/views/modules/audit/logs/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.audit.logs.index`

Menu:
`MNU-AUD-001`

Use Case:
1. UC-AUD-001
2. UC-AUD-002

Elemen utama:
1. Filter tanggal
2. Filter user
3. Filter modul
4. Filter aksi
5. Tabel audit log
6. Aksi lihat detail

### 19.2 Detail Audit Log
Kode View:
VW-AUD-002

Nama File:
`resources/views/modules/audit/logs/show.blade.php`

Route:
`admin.audit.logs.show`

Use Case:
`UC-AUD-002`

Elemen utama:
1. Waktu kejadian
2. User
3. Modul
4. Aksi
5. Objek data
6. Perubahan sebelum dan sesudah bila tersedia

### 19.3 Monitoring Queue
Kode View:
VW-AUD-003

Nama File:
`resources/views/modules/audit/queue-monitor/index.blade.php`

Route:
`admin.audit.queue_monitor.index`

Menu:
`MNU-AUD-002`

Use Case:
`UC-AUD-003`

Elemen utama:
1. Status job
2. Filter status
3. Daftar job gagal
4. Tombol retry bila diizinkan

## 20. Daftar View Modul Pengaturan Sistem

### 20.1 Profil Institusi
Kode View:
VW-SET-001

Nama File:
`resources/views/modules/settings/institution-profile/edit.blade.php`

Layout:
`layouts/admin`

Route:
`admin.settings.institution_profile.edit`

Menu:
`MNU-SET-001`

Use Case:
`UC-CORE-001`

Elemen utama:
1. Nama institusi
2. Nama perpustakaan
3. Alamat
4. Kontak
5. Logo bila ada
6. Tombol update

### 20.2 Aturan Operasional
Kode View:
VW-SET-002

Nama File:
`resources/views/modules/settings/operational-rules/edit.blade.php`

Route:
`admin.settings.operational_rules.edit`

Menu:
`MNU-SET-002`

Use Case:
`UC-CORE-003`

Elemen utama:
1. Lama pinjam default
2. Denda per hari
3. Batas pinjam
4. Status aturan operasional lain
5. Tombol update

## 21. Daftar View Modul Manajemen Akses

### 21.1 Daftar Pengguna
Kode View:
VW-ACC-001

Nama File:
`resources/views/modules/access/users/index.blade.php`

Layout:
`layouts/admin`

Route:
`admin.access.users.index`

Menu:
`MNU-ACC-001`

Use Case:
`UC-IDA-003`

Elemen utama:
1. Search user
2. Filter role
3. Filter status aktif
4. Tabel user
5. Tombol tambah user
6. Aksi detail
7. Aksi edit
8. Aksi reset password
9. Aksi aktivasi

### 21.2 Form Tambah Pengguna
Kode View:
VW-ACC-002

Nama File:
`resources/views/modules/access/users/create.blade.php`

Route:
`admin.access.users.create`

### 21.3 Detail Pengguna
Kode View:
VW-ACC-003

Nama File:
`resources/views/modules/access/users/show.blade.php`

Route:
`admin.access.users.show`

### 21.4 Form Edit Pengguna
Kode View:
VW-ACC-004

Nama File:
`resources/views/modules/access/users/edit.blade.php`

Route:
`admin.access.users.edit`

### 21.5 Daftar Role
Kode View:
VW-ACC-005

Nama File:
`resources/views/modules/access/roles/index.blade.php`

Route:
`admin.access.roles.index`

Menu:
`MNU-ACC-002`

Use Case:
`UC-IDA-004`

Elemen utama:
1. Tabel role
2. Tombol tambah role
3. Aksi edit
4. Aksi kelola permission

### 21.6 Form Tambah Role
Kode View:
VW-ACC-006

Nama File:
`resources/views/modules/access/roles/create.blade.php`

Route:
`admin.access.roles.create`

### 21.7 Form Edit Role
Kode View:
VW-ACC-007

Nama File:
`resources/views/modules/access/roles/edit.blade.php`

Route:
`admin.access.roles.edit`

### 21.8 Daftar Permission
Kode View:
VW-ACC-008

Nama File:
`resources/views/modules/access/permissions/index.blade.php`

Route:
`admin.access.permissions.index`

Menu:
`MNU-ACC-003`

Use Case:
`UC-IDA-005`

Elemen utama:
1. Daftar permission
2. Filter modul
3. Matriks role ke permission
4. Tombol update permission role

## 22. Daftar View Modul Profil Pengguna Internal

### 22.1 Profil Saya
Kode View:
VW-PROF-001

Nama File:
`resources/views/modules/profile/show.blade.php`

Layout:
`layouts/admin`

Route:
`admin.profile.show`

Use Case:
`UC-IDA-008`

Elemen utama:
1. Data profil
2. Tombol edit profil
3. Tombol ubah password

### 22.2 Edit Profil Saya
Kode View:
VW-PROF-002

Nama File:
`resources/views/modules/profile/edit.blade.php`

Route:
`admin.profile.update`

### 22.3 Ubah Password Saya
Kode View:
VW-PROF-003

Nama File:
`resources/views/modules/profile/change-password.blade.php`

Route:
`admin.profile.password.edit`

Use Case:
`UC-IDA-007`

Elemen utama:
1. Password lama
2. Password baru
3. Konfirmasi password baru
4. Tombol simpan

## 23. Daftar View OPAC Publik

### 23.1 Beranda OPAC
Kode View:
VW-OPA-001

Nama File:
`resources/views/modules/opac/home.blade.php`

Layout:
`layouts/opac`

Route:
`opac.home`

Menu:
`MNU-OPA-001`

Use Case:
`UC-OPA-001`

Elemen utama:
1. Hero search
2. Statistik ringkas koleksi publik bila ditampilkan
3. Shortcut kategori
4. Informasi singkat perpustakaan

### 23.2 Hasil Pencarian OPAC
Kode View:
VW-OPA-002

Nama File:
`resources/views/modules/opac/search/index.blade.php`

Route:
`opac.search.index`

Menu:
`MNU-OPA-002`

Use Case:
1. UC-OPA-001
2. UC-OPA-002

Elemen utama:
1. Search bar
2. Filter jenis koleksi
3. Filter bahasa
4. Filter tahun
5. Hasil pencarian
6. Pagination
7. Empty state

### 23.3 Detail Koleksi OPAC
Kode View:
VW-OPA-003

Nama File:
`resources/views/modules/opac/records/show.blade.php`

Route:
`opac.records.show`

Menu:
`MNU-OPA-003`

Use Case:
1. UC-OPA-003
2. UC-OPA-004

Elemen utama:
1. Cover
2. Metadata bibliografi
3. Ketersediaan item fisik
4. Lokasi rak
5. Ketersediaan aset digital
6. Tombol preview jika diizinkan

### 23.4 Preview Aset Publik
Kode View:
VW-OPA-004

Nama File:
`resources/views/modules/opac/assets/preview.blade.php`

Route:
`opac.assets.preview`

Menu:
`MNU-OPA-004`

Use Case:
`UC-OPA-005`

Elemen utama:
1. Viewer PDF.js atau tampilan file
2. Metadata singkat
3. Tombol kembali

### 23.5 Tentang Perpustakaan
Kode View:
VW-OPA-005

Nama File:
`resources/views/modules/opac/about.blade.php`

Route:
`opac.about`

Menu:
`MNU-OPA-005`

### 23.6 Bantuan Pencarian
Kode View:
VW-OPA-006

Nama File:
`resources/views/modules/opac/help.blade.php`

Route:
`opac.help`

Menu:
`MNU-OPA-006`

## 24. Daftar View Error

### 24.1 Error 403
Kode View:
VW-ERR-001

Nama File:
`resources/views/errors/403.blade.php`

Tujuan:
Menampilkan akses ditolak

### 24.2 Error 404
Kode View:
VW-ERR-002

Nama File:
`resources/views/errors/404.blade.php`

Tujuan:
Menampilkan halaman tidak ditemukan

### 24.3 Error 419
Kode View:
VW-ERR-003

Nama File:
`resources/views/errors/419.blade.php`

Tujuan:
Menampilkan session kedaluwarsa

### 24.4 Error 500
Kode View:
VW-ERR-004

Nama File:
`resources/views/errors/500.blade.php`

Tujuan:
Menampilkan error internal sistem

## 25. Daftar Modal yang Disarankan
Modal berikut disarankan untuk konsistensi UI:

1. Modal konfirmasi hapus
2. Modal konfirmasi publish atau unpublish
3. Modal reset password user
4. Modal blokir atau buka blokir anggota
5. Modal ubah status item
6. Modal retry job queue

Catatan:
1. Modal dipakai untuk aksi singkat
2. Proses kompleks tetap memakai halaman khusus

## 26. Partial Form yang Disarankan
Partial form berikut disarankan untuk menghindari duplikasi:

1. `modules/master-data/authors/_form.blade.php`
2. `modules/master-data/publishers/_form.blade.php`
3. `modules/catalog/records/_form.blade.php`
4. `modules/collections/items/_form.blade.php`
5. `modules/members/_form.blade.php`
6. `modules/digital-repository/assets/_form.blade.php`
7. `modules/access/users/_form.blade.php`
8. `modules/access/roles/_form.blade.php`
9. `modules/settings/institution-profile/_form.blade.php`
10. `modules/settings/operational-rules/_form.blade.php`

## 27. Aturan Wajib Halaman Daftar
Setiap halaman daftar admin wajib memiliki:

1. Judul halaman
2. Breadcrumb
3. Tombol tambah jika ada permission create
4. Search
5. Filter relevan
6. Tabel data
7. Empty state
8. Pagination
9. Informasi jumlah data
10. Aksi per baris sesuai permission

Halaman daftar yang wajib mengikuti aturan ini:
1. Semua master data index
2. Katalog index
3. Item index
4. Anggota index
5. Pinjaman aktif index
6. Histori sirkulasi index
7. Aset digital index
8. Semua laporan index
9. Audit log index
10. User index
11. Role index
12. Permission index

## 28. Aturan Wajib Halaman Form
Setiap halaman form wajib memiliki:

1. Judul form
2. Breadcrumb
3. Field wajib ditandai jelas
4. Validasi inline atau block
5. Tombol simpan
6. Tombol batal
7. Keterangan bila ada field sensitif
8. Proteksi double submit bila perlu

## 29. Aturan Wajib Halaman Detail
Setiap halaman detail wajib memiliki:

1. Ringkasan objek
2. Data inti dalam format jelas
3. Status badge
4. Relasi penting
5. Tombol aksi lanjutan sesuai permission
6. Tombol kembali

## 30. Aturan Relasi View ke Route
Aturan resmi:
1. Satu route GET halaman harus punya satu file view utama
2. Route POST, PUT, PATCH, DELETE tidak wajib punya view sendiri
3. Setelah sukses aksi simpan, update, atau proses, route harus redirect ke view yang relevan
4. Route action proses seperti pinjam, kembali, publish, OCR, dan reindex wajib memiliki target redirect yang jelas

## 31. Mapping Route ke View Utama

| Nama Route | Kode View | Nama File |
|---|---|---|
| auth.login | VW-AUTH-001 | auth/login.blade.php |
| admin.dashboard.index | VW-DASH-001 | modules/dashboard/index.blade.php |
| admin.master_data.authors.index | VW-MAS-001 | modules/master-data/authors/index.blade.php |
| admin.master_data.authors.create | VW-MAS-002 | modules/master-data/authors/create.blade.php |
| admin.master_data.authors.edit | VW-MAS-003 | modules/master-data/authors/edit.blade.php |
| admin.catalog.records.index | VW-CAT-001 | modules/catalog/records/index.blade.php |
| admin.catalog.records.create | VW-CAT-002 | modules/catalog/records/create.blade.php |
| admin.catalog.records.show | VW-CAT-003 | modules/catalog/records/show.blade.php |
| admin.catalog.records.edit | VW-CAT-004 | modules/catalog/records/edit.blade.php |
| admin.collections.items.index | VW-COL-001 | modules/collections/items/index.blade.php |
| admin.collections.items.create | VW-COL-002 | modules/collections/items/create.blade.php |
| admin.collections.items.show | VW-COL-003 | modules/collections/items/show.blade.php |
| admin.collections.items.edit | VW-COL-004 | modules/collections/items/edit.blade.php |
| admin.collections.items.history | VW-COL-005 | modules/collections/items/history.blade.php |
| admin.members.index | VW-MEM-001 | modules/members/index.blade.php |
| admin.members.create | VW-MEM-002 | modules/members/create.blade.php |
| admin.members.show | VW-MEM-003 | modules/members/show.blade.php |
| admin.members.edit | VW-MEM-004 | modules/members/edit.blade.php |
| admin.members.history | VW-MEM-005 | modules/members/history.blade.php |
| admin.circulation.loans.create | VW-CIR-001 | modules/circulation/loans/create.blade.php |
| admin.circulation.returns.create | VW-CIR-002 | modules/circulation/returns/create.blade.php |
| admin.circulation.renewals.index | VW-CIR-003 | modules/circulation/renewals/index.blade.php |
| admin.circulation.active_loans.index | VW-CIR-004 | modules/circulation/active-loans/index.blade.php |
| admin.circulation.loans.show | VW-CIR-005 | modules/circulation/loans/show.blade.php |
| admin.circulation.history.index | VW-CIR-006 | modules/circulation/history/index.blade.php |
| admin.circulation.fines.index | VW-CIR-007 | modules/circulation/fines/index.blade.php |
| admin.digital.assets.index | VW-DIG-001 | modules/digital-repository/assets/index.blade.php |
| admin.digital.assets.create | VW-DIG-002 | modules/digital-repository/assets/create.blade.php |
| admin.digital.assets.show | VW-DIG-003 | modules/digital-repository/assets/show.blade.php |
| admin.digital.assets.edit | VW-DIG-004 | modules/digital-repository/assets/edit.blade.php |
| admin.reports.dashboard | VW-REP-001 | modules/reports/dashboard.blade.php |
| admin.reports.collections.index | VW-REP-002 | modules/reports/collections/index.blade.php |
| admin.reports.members.index | VW-REP-003 | modules/reports/members/index.blade.php |
| admin.reports.circulation.index | VW-REP-004 | modules/reports/circulation/index.blade.php |
| admin.reports.fines.index | VW-REP-005 | modules/reports/fines/index.blade.php |
| admin.reports.popular_collections.index | VW-REP-006 | modules/reports/popular-collections/index.blade.php |
| admin.reports.digital_access.index | VW-REP-007 | modules/reports/digital-access/index.blade.php |
| admin.audit.logs.index | VW-AUD-001 | modules/audit/logs/index.blade.php |
| admin.audit.logs.show | VW-AUD-002 | modules/audit/logs/show.blade.php |
| admin.audit.queue_monitor.index | VW-AUD-003 | modules/audit/queue-monitor/index.blade.php |
| admin.settings.institution_profile.edit | VW-SET-001 | modules/settings/institution-profile/edit.blade.php |
| admin.settings.operational_rules.edit | VW-SET-002 | modules/settings/operational-rules/edit.blade.php |
| admin.access.users.index | VW-ACC-001 | modules/access/users/index.blade.php |
| admin.access.users.create | VW-ACC-002 | modules/access/users/create.blade.php |
| admin.access.users.show | VW-ACC-003 | modules/access/users/show.blade.php |
| admin.access.users.edit | VW-ACC-004 | modules/access/users/edit.blade.php |
| admin.access.roles.index | VW-ACC-005 | modules/access/roles/index.blade.php |
| admin.access.roles.create | VW-ACC-006 | modules/access/roles/create.blade.php |
| admin.access.roles.edit | VW-ACC-007 | modules/access/roles/edit.blade.php |
| admin.access.permissions.index | VW-ACC-008 | modules/access/permissions/index.blade.php |
| admin.profile.show | VW-PROF-001 | modules/profile/show.blade.php |
| admin.profile.password.edit | VW-PROF-003 | modules/profile/change-password.blade.php |
| opac.home | VW-OPA-001 | modules/opac/home.blade.php |
| opac.search.index | VW-OPA-002 | modules/opac/search/index.blade.php |
| opac.records.show | VW-OPA-003 | modules/opac/records/show.blade.php |
| opac.assets.preview | VW-OPA-004 | modules/opac/assets/preview.blade.php |
| opac.about | VW-OPA-005 | modules/opac/about.blade.php |
| opac.help | VW-OPA-006 | modules/opac/help.blade.php |

## 32. View yang Tidak Boleh Ada pada Fase 1
View berikut tidak boleh dibuat sebagai halaman resmi fase 1:

1. Halaman pembayaran online
2. Halaman mobile app khusus
3. Halaman acquisition penuh
4. Halaman SSO aktif
5. Halaman RFID
6. Halaman reservasi lanjutan
7. Halaman portal anggota lengkap
8. Halaman API documentation publik
9. Halaman multi kampus

## 33. Aturan Traceability View
Aturan wajib:
1. Setiap view utama harus memiliki kode view
2. Setiap view utama harus punya route
3. Setiap view utama harus punya use case
4. Setiap view utama harus punya role atau permission yang jelas
5. Setiap view utama harus nanti terpetakan ke controller method
6. Tidak boleh ada file view utama yang tidak masuk dokumen ini, kecuali partial dan komponen kecil

## 34. Dampak ke Dokumen Berikutnya
Dokumen ini menjadi acuan wajib untuk:
1. 11_CONTROLLER_MAP.md
2. 12_SERVICE_LAYER.md
3. 13_MODEL_MAP.md
4. 16_VALIDATION_RULES.md
5. 18_UI_UX_STANDARD.md
6. 25_REPORTING_SPEC.md
7. 31_TEST_PLAN.md
8. 32_TEST_SCENARIO.md
9. 38_TREE.md
10. 39_TRACEABILITY_MATRIX.md
11. 40_PAGE_BUILD_PRIORITY.md
12. 42_FRONTEND_CHECKLIST.md

Aturan turunan:
1. Setiap controller GET page harus mengembalikan view yang tercatat di sini
2. Setiap route page harus cocok dengan view map ini
3. Semua halaman yang diuji harus merujuk daftar view ini
4. Semua halaman yang dibangun AI Agent wajib mengikuti view map ini

## 35. Kesimpulan
Dokumen View Map ini menetapkan seluruh halaman resmi PERPUSQU secara lengkap dan konsisten dengan Executive Summary, Stack Teknologi, Arsitektur Modular, PRD, SRS, Use Case, Role Permission Matrix, Menu Map, dan Route Map yang telah disepakati sebelumnya. Dokumen ini menjadi fondasi utama pengembangan antarmuka agar tidak ada missing page, broken navigation, atau halaman tanpa dasar kebutuhan yang jelas. Semua implementasi frontend PERPUSQU wajib merujuk dokumen ini.

END OF 10_VIEW_MAP.md