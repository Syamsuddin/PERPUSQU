# 08_MENU_MAP.md

## 1. Nama Dokumen
Menu Map Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint peta menu aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib perancangan navigasi, sidebar, header action, breadcrumb, route map, view map, controller map, dan implementasi UI

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan struktur menu resmi PERPUSQU untuk seluruh area sistem, meliputi area admin internal, area operasional, area pelaporan, dan area publik OPAC. Dokumen ini menjadi acuan wajib agar seluruh halaman, route, permission, dan komponen navigasi tersusun rapi, konsisten, dan selaras dengan blueprint sebelumnya.

## 3. Dokumen Acuan Wajib
Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md
5. 05_SRS.md
6. 06_USE_CASE.md
7. 07_ROLE_PERMISSION_MATRIX.md

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Modul tetap mengikuti dokumen 03.
5. Menu harus mengikuti use case pada dokumen 06.
6. Hak tampil menu harus mengikuti role permission pada dokumen 07.
7. Tidak boleh ada menu tanpa dasar modul, use case, dan permission yang jelas.

## 4. Prinsip Umum Menu
Prinsip perancangan menu PERPUSQU adalah:

1. Menu harus mengikuti domain bisnis aplikasi.
2. Menu harus sederhana dan mudah dipahami operator perpustakaan.
3. Menu harus konsisten dengan role pengguna.
4. Menu harus mendukung alur kerja harian yang cepat.
5. Menu harus memisahkan area admin dan area publik OPAC.
6. Menu harus disusun dari fungsi paling sering dipakai ke fungsi pendukung.
7. Menu tidak boleh menampilkan fitur yang tidak diizinkan role.
8. Satu menu utama harus mengarah ke satu kelompok modul yang jelas.

## 5. Area Navigasi Sistem
PERPUSQU dibagi ke dalam 3 area navigasi utama:

1. Area Admin Internal
2. Area Publik OPAC
3. Area Header Utilitas

## 6. Struktur Navigasi Tingkat Tinggi

### 6.1 Area Admin Internal
Area ini dipakai oleh:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan

Komponen utama:
1. Sidebar kiri
2. Header atas
3. Breadcrumb
4. Content page
5. Footer

### 6.2 Area Publik OPAC
Area ini dipakai oleh:
1. Mahasiswa
2. Dosen
3. Tenaga Kependidikan
4. Tamu OPAC
5. Pengguna umum yang diizinkan melihat katalog publik

Komponen utama:
1. Header publik
2. Search bar utama
3. Navigasi ringan
4. Daftar hasil pencarian
5. Detail koleksi
6. Footer publik

### 6.3 Area Header Utilitas
Area ini berada di bagian atas halaman admin dan berisi:
1. Nama sistem
2. Nama institusi
3. Profil pengguna
4. Ganti password
5. Logout
6. Breadcrumb
7. Quick actions terbatas sesuai role

## 7. Struktur Sidebar Admin Resmi
Urutan menu sidebar admin resmi ditetapkan sebagai berikut:

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

Catatan:
1. Urutan menu mengikuti intensitas penggunaan operasional.
2. Menu yang tidak relevan untuk suatu role wajib disembunyikan.
3. Menu OPAC tidak menjadi bagian sidebar admin, tetapi bisa diberi shortcut di header atau dashboard.

## 8. Peta Menu Admin Lengkap

### 8.1 Menu Dashboard
Kode Menu:
MNU-DASH-001

Nama Menu:
Dashboard

Tujuan:
Menampilkan ringkasan awal sesuai role pengguna.

Submenu:
1. Dashboard Utama

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan

Permission:
1. core.view_dashboard
2. reports.view_dashboard untuk widget statistik tertentu

Use Case Terkait:
1. UC-CORE-004 Melihat dashboard admin
2. UC-REP-001 Melihat dashboard statistik

Catatan UI:
1. Widget harus menyesuaikan role.
2. Quick action boleh muncul di dashboard.

---

### 8.2 Menu Master Data
Kode Menu:
MNU-MAS-000

Nama Menu:
Master Data

Tujuan:
Mengelola data referensi yang dipakai lintas modul.

Aktor Utama:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan terbatas
4. Operator Repositori Digital terbatas
5. Pimpinan baca terbatas bila diaktifkan

Submenu Resmi:
1. Pengarang
2. Penerbit
3. Bahasa
4. Klasifikasi
5. Subjek
6. Jenis Koleksi
7. Lokasi Rak
8. Fakultas
9. Program Studi
10. Kondisi Item

#### 8.2.1 Submenu Pengarang
Kode Menu:
MNU-MAS-001

Permission:
1. authors.view
2. authors.create
3. authors.update
4. authors.delete

Use Case:
1. UC-MAS-001 Mengelola pengarang

#### 8.2.2 Submenu Penerbit
Kode Menu:
MNU-MAS-002

Permission:
1. publishers.view
2. publishers.create
3. publishers.update
4. publishers.delete

Use Case:
1. UC-MAS-002 Mengelola penerbit

#### 8.2.3 Submenu Bahasa
Kode Menu:
MNU-MAS-003

Permission:
1. languages.view
2. languages.create
3. languages.update
4. languages.delete

Use Case:
1. UC-MAS-003 Mengelola bahasa

#### 8.2.4 Submenu Klasifikasi
Kode Menu:
MNU-MAS-004

Permission:
1. classifications.view
2. classifications.create
3. classifications.update
4. classifications.delete

Use Case:
1. UC-MAS-004 Mengelola klasifikasi

#### 8.2.5 Submenu Subjek
Kode Menu:
MNU-MAS-005

Permission:
1. subjects.view
2. subjects.create
3. subjects.update
4. subjects.delete

Use Case:
1. UC-MAS-005 Mengelola subjek

#### 8.2.6 Submenu Jenis Koleksi
Kode Menu:
MNU-MAS-006

Permission:
1. collection_types.view
2. collection_types.create
3. collection_types.update
4. collection_types.delete

Use Case:
1. UC-MAS-006 Mengelola jenis koleksi

#### 8.2.7 Submenu Lokasi Rak
Kode Menu:
MNU-MAS-007

Permission:
1. rack_locations.view
2. rack_locations.create
3. rack_locations.update
4. rack_locations.delete

Use Case:
1. UC-MAS-007 Mengelola lokasi rak

#### 8.2.8 Submenu Fakultas
Kode Menu:
MNU-MAS-008

Permission:
1. faculties.view
2. faculties.create
3. faculties.update
4. faculties.delete

Use Case:
1. UC-MAS-008 Mengelola fakultas

#### 8.2.9 Submenu Program Studi
Kode Menu:
MNU-MAS-009

Permission:
1. study_programs.view
2. study_programs.create
3. study_programs.update
4. study_programs.delete

Use Case:
1. UC-MAS-009 Mengelola program studi

#### 8.2.10 Submenu Kondisi Item
Kode Menu:
MNU-MAS-010

Permission:
1. item_conditions.view
2. item_conditions.create
3. item_conditions.update
4. item_conditions.delete

Use Case:
1. UC-MAS-010 Mengelola kondisi item

---

### 8.3 Menu Katalog
Kode Menu:
MNU-CAT-000

Nama Menu:
Katalog

Tujuan:
Mengelola bibliographic record sebagai induk koleksi fisik dan digital.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi baca
5. Operator Repositori Digital baca
6. Pimpinan baca

Submenu Resmi:
1. Daftar Katalog
2. Tambah Katalog
3. Detail Katalog
4. Edit Katalog

Catatan:
1. Detail dan Edit bukan menu sidebar permanen.
2. Detail dan Edit adalah menu turunan halaman kerja.

#### 8.3.1 Submenu Daftar Katalog
Kode Menu:
MNU-CAT-001

Permission:
1. catalog.view

Use Case:
1. UC-CAT-003 Melihat detail bibliographic record
2. UC-CAT-004 Mencari bibliographic record pada admin

#### 8.3.2 Submenu Tambah Katalog
Kode Menu:
MNU-CAT-002

Permission:
1. catalog.create

Use Case:
1. UC-CAT-001 Menambah bibliographic record

#### 8.3.3 Halaman Detail Katalog
Kode Menu:
MNU-CAT-003

Permission:
1. catalog.view_detail

Use Case:
1. UC-CAT-003 Melihat detail bibliographic record

#### 8.3.4 Halaman Edit Katalog
Kode Menu:
MNU-CAT-004

Permission:
1. catalog.update

Use Case:
1. UC-CAT-002 Mengubah bibliographic record

---

### 8.4 Menu Koleksi Fisik
Kode Menu:
MNU-COL-000

Nama Menu:
Koleksi Fisik

Tujuan:
Mengelola item fisik per eksemplar yang terhubung ke bibliographic record.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi baca
5. Pimpinan baca

Submenu Resmi:
1. Daftar Item
2. Tambah Item
3. Detail Item
4. Edit Item
5. Histori Item

#### 8.4.1 Submenu Daftar Item
Kode Menu:
MNU-COL-001

Permission:
1. collections.view

Use Case:
1. UC-COL-003 Melihat detail item fisik
2. UC-COL-004 Mencari item fisik

#### 8.4.2 Submenu Tambah Item
Kode Menu:
MNU-COL-002

Permission:
1. collections.create

Use Case:
1. UC-COL-001 Menambah item fisik

#### 8.4.3 Halaman Detail Item
Kode Menu:
MNU-COL-003

Permission:
1. collections.view_detail

Use Case:
1. UC-COL-003 Melihat detail item fisik

#### 8.4.4 Halaman Edit Item
Kode Menu:
MNU-COL-004

Permission:
1. collections.update

Use Case:
1. UC-COL-002 Mengubah item fisik
2. UC-COL-005 Mengubah status item fisik

#### 8.4.5 Halaman Histori Item
Kode Menu:
MNU-COL-005

Permission:
1. collections.view_history

Use Case:
1. UC-COL-006 Melihat histori item fisik

---

### 8.5 Menu Anggota
Kode Menu:
MNU-MEM-000

Nama Menu:
Anggota

Tujuan:
Mengelola data anggota perpustakaan.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan baca
4. Petugas Sirkulasi baca
5. Pimpinan baca

Submenu Resmi:
1. Daftar Anggota
2. Tambah Anggota
3. Detail Anggota
4. Edit Anggota
5. Histori Anggota

#### 8.5.1 Submenu Daftar Anggota
Kode Menu:
MNU-MEM-001

Permission:
1. members.view

Use Case:
1. UC-MEM-003 Melihat detail anggota
2. UC-MEM-004 Mencari anggota

#### 8.5.2 Submenu Tambah Anggota
Kode Menu:
MNU-MEM-002

Permission:
1. members.create

Use Case:
1. UC-MEM-001 Menambah anggota

#### 8.5.3 Halaman Detail Anggota
Kode Menu:
MNU-MEM-003

Permission:
1. members.view_detail

Use Case:
1. UC-MEM-003 Melihat detail anggota

#### 8.5.4 Halaman Edit Anggota
Kode Menu:
MNU-MEM-004

Permission:
1. members.update

Use Case:
1. UC-MEM-002 Mengubah anggota
2. UC-MEM-005 Mengaktifkan atau menonaktifkan anggota
3. UC-MEM-006 Memblokir atau membuka blokir anggota

#### 8.5.5 Halaman Histori Anggota
Kode Menu:
MNU-MEM-005

Permission:
1. members.view_history

Use Case:
1. UC-MEM-007 Melihat histori transaksi anggota

---

### 8.6 Menu Sirkulasi
Kode Menu:
MNU-CIR-000

Nama Menu:
Sirkulasi

Tujuan:
Menjalankan transaksi peminjaman, pengembalian, dan perpanjangan.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Petugas Sirkulasi
4. Pimpinan baca

Submenu Resmi:
1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Pinjaman Aktif
5. Histori Sirkulasi
6. Denda dan Keterlambatan

#### 8.6.1 Submenu Peminjaman
Kode Menu:
MNU-CIR-001

Permission:
1. circulation.process_loan

Use Case:
1. UC-CIR-001 Memproses peminjaman item

#### 8.6.2 Submenu Pengembalian
Kode Menu:
MNU-CIR-002

Permission:
1. circulation.process_return

Use Case:
1. UC-CIR-002 Memproses pengembalian item

#### 8.6.3 Submenu Perpanjangan
Kode Menu:
MNU-CIR-003

Permission:
1. circulation.process_renewal

Use Case:
1. UC-CIR-003 Memproses perpanjangan pinjaman

#### 8.6.4 Submenu Pinjaman Aktif
Kode Menu:
MNU-CIR-004

Permission:
1. circulation.view_active_loans

Use Case:
1. UC-CIR-004 Melihat pinjaman aktif

#### 8.6.5 Submenu Histori Sirkulasi
Kode Menu:
MNU-CIR-005

Permission:
1. circulation.view_history

Use Case:
1. UC-CIR-005 Melihat histori transaksi sirkulasi

#### 8.6.6 Submenu Denda dan Keterlambatan
Kode Menu:
MNU-CIR-006

Permission:
1. circulation.view_fines

Use Case:
1. UC-CIR-006 Menghitung keterlambatan dan denda
2. UC-REP-005 Melihat laporan keterlambatan dan denda

---

### 8.7 Menu Repositori Digital
Kode Menu:
MNU-DIG-000

Nama Menu:
Repositori Digital

Tujuan:
Mengelola file digital, metadata, preview, OCR, indexing, dan akses file.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Operator Repositori Digital
4. Pustakawan terbatas
5. Pimpinan baca

Submenu Resmi:
1. Daftar Aset Digital
2. Unggah Aset Digital
3. Detail Aset Digital
4. Edit Aset Digital
5. OCR dan Indexing

#### 8.7.1 Submenu Daftar Aset Digital
Kode Menu:
MNU-DIG-001

Permission:
1. digital_assets.view

Use Case:
1. UC-DIG-003 Melihat detail aset digital

#### 8.7.2 Submenu Unggah Aset Digital
Kode Menu:
MNU-DIG-002

Permission:
1. digital_assets.create

Use Case:
1. UC-DIG-001 Mengunggah aset digital

#### 8.7.3 Halaman Detail Aset Digital
Kode Menu:
MNU-DIG-003

Permission:
1. digital_assets.view_detail

Use Case:
1. UC-DIG-003 Melihat detail aset digital
2. UC-DIG-004 Mempreview file digital

#### 8.7.4 Halaman Edit Aset Digital
Kode Menu:
MNU-DIG-004

Permission:
1. digital_assets.update
2. digital_assets.manage_access
3. digital_assets.publish
4. digital_assets.unpublish

Use Case:
1. UC-DIG-002 Mengubah metadata aset digital
2. UC-DIG-005 Mengelola akses aset digital
3. UC-DIG-006 Mengaktifkan atau menonaktifkan publikasi aset digital

#### 8.7.5 Submenu OCR dan Indexing
Kode Menu:
MNU-DIG-005

Permission:
1. digital_assets.run_ocr
2. digital_assets.reindex

Use Case:
1. UC-DIG-007 Menjalankan OCR aset digital
2. UC-DIG-008 Mengindeks isi dokumen digital

---

### 8.8 Menu Laporan
Kode Menu:
MNU-REP-000

Nama Menu:
Laporan

Tujuan:
Menyediakan statistik dan laporan operasional.

Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pimpinan Perpustakaan
4. Pustakawan terbatas
5. Petugas Sirkulasi terbatas
6. Operator Repositori Digital terbatas

Submenu Resmi:
1. Dashboard Statistik
2. Laporan Koleksi
3. Laporan Anggota
4. Laporan Sirkulasi
5. Laporan Denda
6. Laporan Koleksi Populer
7. Laporan Akses Digital

#### 8.8.1 Submenu Dashboard Statistik
Kode Menu:
MNU-REP-001

Permission:
1. reports.view_dashboard

Use Case:
1. UC-REP-001 Melihat dashboard statistik

#### 8.8.2 Submenu Laporan Koleksi
Kode Menu:
MNU-REP-002

Permission:
1. reports.view_collections

Use Case:
1. UC-REP-002 Melihat laporan jumlah koleksi

#### 8.8.3 Submenu Laporan Anggota
Kode Menu:
MNU-REP-003

Permission:
1. reports.view_members

Use Case:
1. UC-REP-003 Melihat laporan anggota

#### 8.8.4 Submenu Laporan Sirkulasi
Kode Menu:
MNU-REP-004

Permission:
1. reports.view_circulation

Use Case:
1. UC-REP-004 Melihat laporan transaksi sirkulasi

#### 8.8.5 Submenu Laporan Denda
Kode Menu:
MNU-REP-005

Permission:
1. reports.view_fines

Use Case:
1. UC-REP-005 Melihat laporan keterlambatan dan denda

#### 8.8.6 Submenu Laporan Koleksi Populer
Kode Menu:
MNU-REP-006

Permission:
1. reports.view_popular_collections

Use Case:
1. UC-REP-006 Melihat laporan koleksi populer

#### 8.8.7 Submenu Laporan Akses Digital
Kode Menu:
MNU-REP-007

Permission:
1. reports.view_digital_access

Use Case:
1. UC-REP-007 Melihat laporan akses digital

---

### 8.9 Menu Audit dan Monitoring
Kode Menu:
MNU-AUD-000

Nama Menu:
Audit dan Monitoring

Tujuan:
Menyediakan penelusuran aktivitas dan monitoring proses teknis dasar.

Aktor:
1. Super Admin
2. Admin Perpustakaan terbatas

Submenu Resmi:
1. Audit Log
2. Monitoring Queue

#### 8.9.1 Submenu Audit Log
Kode Menu:
MNU-AUD-001

Permission:
1. audit_logs.view
2. audit_logs.view_detail

Use Case:
1. UC-AUD-001 Melihat audit log
2. UC-AUD-002 Menelusuri aktivitas pengguna

#### 8.9.2 Submenu Monitoring Queue
Kode Menu:
MNU-AUD-002

Permission:
1. queue_monitor.view
2. queue_monitor.manage_retry

Use Case:
1. UC-AUD-003 Melihat monitoring job queue dasar

---

### 8.10 Menu Pengaturan Sistem
Kode Menu:
MNU-SET-000

Nama Menu:
Pengaturan Sistem

Tujuan:
Mengelola profil institusi dan aturan operasional sistem.

Aktor:
1. Super Admin
2. Admin Perpustakaan

Submenu Resmi:
1. Profil Institusi
2. Aturan Operasional

#### 8.10.1 Submenu Profil Institusi
Kode Menu:
MNU-SET-001

Permission:
1. core.view_institution_profile
2. core.update_institution_profile

Use Case:
1. UC-CORE-001 Mengelola profil institusi

#### 8.10.2 Submenu Aturan Operasional
Kode Menu:
MNU-SET-002

Permission:
1. core.view_operational_rules
2. core.update_operational_rules

Use Case:
1. UC-CORE-003 Mengelola parameter operasional perpustakaan

---

### 8.11 Menu Manajemen Akses
Kode Menu:
MNU-ACC-000

Nama Menu:
Manajemen Akses

Tujuan:
Mengelola user, role, dan permission sistem.

Aktor:
1. Super Admin
2. Admin Perpustakaan terbatas

Submenu Resmi:
1. Pengguna
2. Role
3. Permission

#### 8.11.1 Submenu Pengguna
Kode Menu:
MNU-ACC-001

Permission:
1. users.view
2. users.create
3. users.update
4. users.delete
5. users.activate
6. users.reset_password

Use Case:
1. UC-IDA-003 Mengelola pengguna

#### 8.11.2 Submenu Role
Kode Menu:
MNU-ACC-002

Permission:
1. roles.view
2. roles.create
3. roles.update
4. roles.delete
5. roles.manage

Use Case:
1. UC-IDA-004 Mengelola role

#### 8.11.3 Submenu Permission
Kode Menu:
MNU-ACC-003

Permission:
1. permissions.view
2. permissions.manage

Use Case:
1. UC-IDA-005 Mengelola permission

## 9. Peta Header Admin
Header admin resmi memuat elemen berikut:

1. Toggle Sidebar
2. Judul Halaman
3. Breadcrumb
4. Shortcut OPAC
5. Nama Pengguna
6. Dropdown Profil
7. Ubah Password
8. Logout

Catatan:
1. Shortcut OPAC tampil untuk semua role internal.
2. Breadcrumb wajib tampil pada halaman internal selain dashboard.
3. Judul halaman harus mengikuti menu aktif.

## 10. Peta Dropdown Profil Pengguna
Dropdown profil pengguna admin berisi:

1. Profil Saya
2. Ubah Password
3. Logout

Permission:
1. own_profile.view
2. own_profile.update
3. own_password.change

Use Case:
1. UC-IDA-007 Mengubah password
2. UC-IDA-008 Melihat profil sendiri

## 11. Peta Quick Action Dashboard
Quick action hanya tampil pada dashboard dan menyesuaikan role.

### 11.1 Quick Action Super Admin
1. Tambah Pengguna
2. Pengaturan Sistem
3. Audit Log
4. Lihat OPAC

### 11.2 Quick Action Admin Perpustakaan
1. Tambah Katalog
2. Tambah Item
3. Tambah Anggota
4. Unggah Aset Digital
5. Proses Peminjaman
6. Lihat OPAC

### 11.3 Quick Action Pustakawan
1. Tambah Katalog
2. Tambah Item
3. Unggah Aset Digital
4. Lihat Katalog
5. Lihat OPAC

### 11.4 Quick Action Petugas Sirkulasi
1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Pinjaman Aktif
5. Lihat OPAC

### 11.5 Quick Action Operator Repositori Digital
1. Unggah Aset Digital
2. Daftar Aset Digital
3. OCR dan Indexing
4. Lihat OPAC

### 11.6 Quick Action Pimpinan Perpustakaan
1. Dashboard Statistik
2. Laporan Koleksi
3. Laporan Sirkulasi
4. Laporan Akses Digital
5. Lihat OPAC

## 12. Peta Menu OPAC Publik

### 12.1 Struktur Header OPAC
Header OPAC publik resmi terdiri atas:

1. Logo atau Nama PERPUSQU
2. Beranda
3. Katalog
4. Pencarian
5. Bantuan atau Tentang
6. Kontak Perpustakaan bila diaktifkan

Catatan:
1. Area publik harus ringan.
2. Fokus utama tetap pencarian.
3. Menu publik tidak boleh terlalu banyak.

### 12.2 Menu Publik Resmi
1. Beranda OPAC
2. Pencarian Katalog
3. Detail Koleksi
4. Preview Aset Publik
5. Tentang Perpustakaan
6. Bantuan Pencarian

#### 12.2.1 Beranda OPAC
Kode Menu:
MNU-OPA-001

Permission:
1. opac.search

Use Case:
1. UC-OPA-001 Mencari koleksi pada OPAC

#### 12.2.2 Pencarian Katalog
Kode Menu:
MNU-OPA-002

Permission:
1. opac.search

Use Case:
1. UC-OPA-001 Mencari koleksi pada OPAC
2. UC-OPA-002 Memfilter hasil pencarian OPAC

#### 12.2.3 Detail Koleksi
Kode Menu:
MNU-OPA-003

Permission:
1. opac.view_detail
2. opac.view_availability

Use Case:
1. UC-OPA-003 Melihat detail koleksi pada OPAC
2. UC-OPA-004 Melihat ketersediaan item fisik pada OPAC

#### 12.2.4 Preview Aset Publik
Kode Menu:
MNU-OPA-004

Permission:
1. opac.preview_public_asset

Use Case:
1. UC-OPA-005 Mengakses preview atau file digital sesuai hak akses

#### 12.2.5 Tentang Perpustakaan
Kode Menu:
MNU-OPA-005

Permission:
1. opac.search

Tujuan:
Menampilkan profil singkat perpustakaan untuk area publik.

#### 12.2.6 Bantuan Pencarian
Kode Menu:
MNU-OPA-006

Permission:
1. opac.search

Tujuan:
Menampilkan panduan singkat penggunaan OPAC.

## 13. Breadcrumb Resmi
Aturan breadcrumb:

1. Dashboard tidak perlu breadcrumb panjang.
2. Semua halaman admin selain dashboard wajib memiliki breadcrumb.
3. Breadcrumb harus mengikuti hierarki menu nyata.
4. Halaman detail dan edit wajib menyertakan parent list page.

Contoh breadcrumb:
1. Dashboard / Katalog / Daftar Katalog
2. Dashboard / Katalog / Daftar Katalog / Detail Katalog
3. Dashboard / Koleksi Fisik / Daftar Item / Edit Item
4. Dashboard / Sirkulasi / Peminjaman
5. Dashboard / Laporan / Laporan Sirkulasi

## 14. Aturan Tampil Sembunyi Menu
Aturan tampil menu resmi:

1. Menu utama tampil bila ada minimal satu submenu yang diizinkan.
2. Submenu tampil hanya bila role memiliki permission view atau permission aksi terkait.
3. Tombol Tambah tampil hanya bila ada permission create.
4. Tombol Ubah tampil hanya bila ada permission update.
5. Tombol Hapus tampil hanya bila ada permission delete.
6. Tombol Proses tampil hanya bila ada permission process terkait.
7. Menu Audit dan Monitoring wajib tersembunyi bagi role tanpa hak.
8. Menu Manajemen Akses wajib tersembunyi bagi role non admin akses.

## 15. Peta Menu per Role

### 15.1 Super Admin
Menu yang tampil:
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

### 15.2 Admin Perpustakaan
Menu yang tampil:
1. Dashboard
2. Master Data
3. Katalog
4. Koleksi Fisik
5. Anggota
6. Sirkulasi
7. Repositori Digital
8. Laporan
9. Audit dan Monitoring terbatas
10. Pengaturan Sistem
11. Manajemen Akses terbatas

### 15.3 Pustakawan
Menu yang tampil:
1. Dashboard
2. Master Data terbatas
3. Katalog
4. Koleksi Fisik
5. Anggota baca
6. Repositori Digital terbatas
7. Laporan terbatas

Menu yang tidak tampil:
1. Sirkulasi
2. Audit dan Monitoring
3. Pengaturan Sistem
4. Manajemen Akses

### 15.4 Petugas Sirkulasi
Menu yang tampil:
1. Dashboard
2. Katalog baca
3. Koleksi Fisik baca
4. Anggota baca
5. Sirkulasi
6. Laporan terbatas

Menu yang tidak tampil:
1. Master Data
2. Repositori Digital
3. Audit dan Monitoring
4. Pengaturan Sistem
5. Manajemen Akses

### 15.5 Operator Repositori Digital
Menu yang tampil:
1. Dashboard
2. Master Data terbatas
3. Katalog baca
4. Repositori Digital
5. Laporan akses digital terbatas

Menu yang tidak tampil:
1. Koleksi Fisik
2. Anggota
3. Sirkulasi
4. Audit dan Monitoring
5. Pengaturan Sistem
6. Manajemen Akses

### 15.6 Pimpinan Perpustakaan
Menu yang tampil:
1. Dashboard
2. Katalog baca
3. Koleksi Fisik baca
4. Anggota baca
5. Sirkulasi baca
6. Repositori Digital baca
7. Laporan

Menu yang tidak tampil:
1. Master Data
2. Audit dan Monitoring
3. Pengaturan Sistem
4. Manajemen Akses

### 15.7 Pengguna OPAC Publik
Menu yang tampil:
1. Beranda OPAC
2. Katalog
3. Pencarian
4. Tentang Perpustakaan
5. Bantuan Pencarian

## 16. Mapping Menu ke Use Case

| Kode Menu | Nama Menu | Use Case Utama |
|---|---|---|
| MNU-DASH-001 | Dashboard | UC-CORE-004, UC-REP-001 |
| MNU-MAS-001 | Pengarang | UC-MAS-001 |
| MNU-MAS-002 | Penerbit | UC-MAS-002 |
| MNU-MAS-003 | Bahasa | UC-MAS-003 |
| MNU-MAS-004 | Klasifikasi | UC-MAS-004 |
| MNU-MAS-005 | Subjek | UC-MAS-005 |
| MNU-MAS-006 | Jenis Koleksi | UC-MAS-006 |
| MNU-MAS-007 | Lokasi Rak | UC-MAS-007 |
| MNU-MAS-008 | Fakultas | UC-MAS-008 |
| MNU-MAS-009 | Program Studi | UC-MAS-009 |
| MNU-MAS-010 | Kondisi Item | UC-MAS-010 |
| MNU-CAT-001 | Daftar Katalog | UC-CAT-003, UC-CAT-004 |
| MNU-CAT-002 | Tambah Katalog | UC-CAT-001 |
| MNU-CAT-003 | Detail Katalog | UC-CAT-003 |
| MNU-CAT-004 | Edit Katalog | UC-CAT-002 |
| MNU-COL-001 | Daftar Item | UC-COL-003, UC-COL-004 |
| MNU-COL-002 | Tambah Item | UC-COL-001 |
| MNU-COL-003 | Detail Item | UC-COL-003 |
| MNU-COL-004 | Edit Item | UC-COL-002, UC-COL-005 |
| MNU-COL-005 | Histori Item | UC-COL-006 |
| MNU-MEM-001 | Daftar Anggota | UC-MEM-003, UC-MEM-004 |
| MNU-MEM-002 | Tambah Anggota | UC-MEM-001 |
| MNU-MEM-003 | Detail Anggota | UC-MEM-003 |
| MNU-MEM-004 | Edit Anggota | UC-MEM-002, UC-MEM-005, UC-MEM-006 |
| MNU-MEM-005 | Histori Anggota | UC-MEM-007 |
| MNU-CIR-001 | Peminjaman | UC-CIR-001 |
| MNU-CIR-002 | Pengembalian | UC-CIR-002 |
| MNU-CIR-003 | Perpanjangan | UC-CIR-003 |
| MNU-CIR-004 | Pinjaman Aktif | UC-CIR-004 |
| MNU-CIR-005 | Histori Sirkulasi | UC-CIR-005 |
| MNU-CIR-006 | Denda dan Keterlambatan | UC-CIR-006 |
| MNU-DIG-001 | Daftar Aset Digital | UC-DIG-003 |
| MNU-DIG-002 | Unggah Aset Digital | UC-DIG-001 |
| MNU-DIG-003 | Detail Aset Digital | UC-DIG-003, UC-DIG-004 |
| MNU-DIG-004 | Edit Aset Digital | UC-DIG-002, UC-DIG-005, UC-DIG-006 |
| MNU-DIG-005 | OCR dan Indexing | UC-DIG-007, UC-DIG-008 |
| MNU-REP-001 | Dashboard Statistik | UC-REP-001 |
| MNU-REP-002 | Laporan Koleksi | UC-REP-002 |
| MNU-REP-003 | Laporan Anggota | UC-REP-003 |
| MNU-REP-004 | Laporan Sirkulasi | UC-REP-004 |
| MNU-REP-005 | Laporan Denda | UC-REP-005 |
| MNU-REP-006 | Laporan Koleksi Populer | UC-REP-006 |
| MNU-REP-007 | Laporan Akses Digital | UC-REP-007 |
| MNU-AUD-001 | Audit Log | UC-AUD-001, UC-AUD-002 |
| MNU-AUD-002 | Monitoring Queue | UC-AUD-003 |
| MNU-SET-001 | Profil Institusi | UC-CORE-001 |
| MNU-SET-002 | Aturan Operasional | UC-CORE-003 |
| MNU-ACC-001 | Pengguna | UC-IDA-003 |
| MNU-ACC-002 | Role | UC-IDA-004 |
| MNU-ACC-003 | Permission | UC-IDA-005 |
| MNU-OPA-001 | Beranda OPAC | UC-OPA-001 |
| MNU-OPA-002 | Pencarian Katalog | UC-OPA-001, UC-OPA-002 |
| MNU-OPA-003 | Detail Koleksi | UC-OPA-003, UC-OPA-004 |
| MNU-OPA-004 | Preview Aset Publik | UC-OPA-005 |

## 17. Mapping Menu ke Permission

| Kode Menu | Permission Minimum |
|---|---|
| MNU-DASH-001 | core.view_dashboard |
| MNU-MAS-001 | authors.view |
| MNU-MAS-002 | publishers.view |
| MNU-MAS-003 | languages.view |
| MNU-MAS-004 | classifications.view |
| MNU-MAS-005 | subjects.view |
| MNU-MAS-006 | collection_types.view |
| MNU-MAS-007 | rack_locations.view |
| MNU-MAS-008 | faculties.view |
| MNU-MAS-009 | study_programs.view |
| MNU-MAS-010 | item_conditions.view |
| MNU-CAT-001 | catalog.view |
| MNU-CAT-002 | catalog.create |
| MNU-CAT-003 | catalog.view_detail |
| MNU-CAT-004 | catalog.update |
| MNU-COL-001 | collections.view |
| MNU-COL-002 | collections.create |
| MNU-COL-003 | collections.view_detail |
| MNU-COL-004 | collections.update |
| MNU-COL-005 | collections.view_history |
| MNU-MEM-001 | members.view |
| MNU-MEM-002 | members.create |
| MNU-MEM-003 | members.view_detail |
| MNU-MEM-004 | members.update |
| MNU-MEM-005 | members.view_history |
| MNU-CIR-001 | circulation.process_loan |
| MNU-CIR-002 | circulation.process_return |
| MNU-CIR-003 | circulation.process_renewal |
| MNU-CIR-004 | circulation.view_active_loans |
| MNU-CIR-005 | circulation.view_history |
| MNU-CIR-006 | circulation.view_fines |
| MNU-DIG-001 | digital_assets.view |
| MNU-DIG-002 | digital_assets.create |
| MNU-DIG-003 | digital_assets.view_detail |
| MNU-DIG-004 | digital_assets.update |
| MNU-DIG-005 | digital_assets.run_ocr |
| MNU-REP-001 | reports.view_dashboard |
| MNU-REP-002 | reports.view_collections |
| MNU-REP-003 | reports.view_members |
| MNU-REP-004 | reports.view_circulation |
| MNU-REP-005 | reports.view_fines |
| MNU-REP-006 | reports.view_popular_collections |
| MNU-REP-007 | reports.view_digital_access |
| MNU-AUD-001 | audit_logs.view |
| MNU-AUD-002 | queue_monitor.view |
| MNU-SET-001 | core.view_institution_profile |
| MNU-SET-002 | core.view_operational_rules |
| MNU-ACC-001 | users.view |
| MNU-ACC-002 | roles.view |
| MNU-ACC-003 | permissions.view |
| MNU-OPA-001 | opac.search |
| MNU-OPA-002 | opac.search |
| MNU-OPA-003 | opac.view_detail |
| MNU-OPA-004 | opac.preview_public_asset |

## 18. Aturan Penamaan Menu
Standar penamaan menu:

1. Gunakan bahasa Indonesia.
2. Gunakan nama singkat dan jelas.
3. Hindari istilah teknis yang tidak perlu pada sidebar.
4. Gunakan istilah yang familiar bagi operator kampus.
5. Nama menu harus konsisten dengan nama modul dan halaman.

Contoh benar:
1. Katalog
2. Koleksi Fisik
3. Anggota
4. Sirkulasi
5. Repositori Digital
6. Laporan

Contoh yang tidak dipakai:
1. Catalog Management Center
2. Library Operation Hub
3. Record Control Console

## 19. Aturan UX untuk Menu
1. Sidebar harus dapat collapse.
2. Submenu harus dapat expand dan collapse.
3. Menu aktif harus diberi penanda visual yang jelas.
4. Menu parent aktif saat salah satu submenu aktif.
5. Search global admin tidak menjadi pengganti sidebar.
6. Menu tidak boleh berubah urutan secara acak antar role, kecuali disembunyikan sesuai hak.
7. Halaman detail dan edit tidak perlu tampil permanen di sidebar.
8. OPAC publik harus menempatkan search bar sebagai elemen paling menonjol.

## 20. Aturan Menu yang Tidak Ditampilkan
Menu berikut tidak tampil pada fase 1 karena belum masuk ruang lingkup go live:

1. Pengadaan
2. Integrasi SIAKAD
3. SSO
4. WhatsApp Notifikasi
5. RFID
6. Reservasi Lanjutan
7. Portal Anggota Lengkap
8. API Eksternal

Catatan:
1. Fitur tersebut boleh dicatat pada roadmap.
2. Fitur tersebut tidak boleh muncul pada sidebar fase 1.

## 21. Dampak ke Dokumen Berikutnya
Dokumen ini menjadi acuan wajib untuk:
1. 09_ROUTE_MAP.md
2. 10_VIEW_MAP.md
3. 11_CONTROLLER_MAP.md
4. 12_SERVICE_LAYER.md
5. 18_UI_UX_STANDARD.md
6. 39_TRACEABILITY_MATRIX.md
7. 40_PAGE_BUILD_PRIORITY.md

Aturan turunan:
1. Setiap menu harus punya route yang jelas.
2. Setiap menu harus punya halaman view yang jelas.
3. Setiap menu yang melakukan aksi harus punya controller method yang jelas.
4. Setiap menu utama harus punya service pendukung yang jelas bila memuat logika bisnis.
5. Tidak boleh ada route atau view utama yang tidak memiliki menu atau titik akses yang sah.

## 22. Kesimpulan
Dokumen Menu Map ini menetapkan navigasi resmi PERPUSQU secara lengkap dan konsisten dengan seluruh blueprint sebelumnya. Struktur menu disusun berdasarkan modul bisnis, use case, dan permission yang telah disepakati. Dokumen ini menjadi dasar utama untuk menurunkan route map, view map, controller map, dan implementasi UI, agar aplikasi PERPUSQU memiliki navigasi yang rapi, mudah dipahami, dan sesuai dengan kebutuhan operasional perpustakaan kampus.

END OF 08_MENU_MAP.md