# 06_USE_CASE.md

## 1. Nama Dokumen
Use Case Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint use case

### 2.3 Status Dokumen
Resmi, acuan wajib analisis fitur, desain menu, route, controller, service, model, schema, dan view

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan daftar use case resmi PERPUSQU berdasarkan konsensus pada dokumen sebelumnya. Dokumen ini menjadi jembatan antara kebutuhan produk dalam 04_PRD.md dan kebutuhan perangkat lunak dalam 05_SRS.md menuju dokumen teknis lanjutan seperti ROLE_PERMISSION_MATRIX, MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, dan SCHEMA.

## 3. Dokumen Acuan Wajib
Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md
5. 05_SRS.md

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep sistem tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Modul tetap mengikuti pembagian domain pada dokumen 03.
5. Use case hanya boleh memuat ruang lingkup yang selaras dengan PRD dan SRS.
6. Fitur lanjutan boleh dicatat, tetapi harus ditandai sebagai fase lanjutan.

## 4. Ruang Lingkup Use Case
Use case dalam dokumen ini mencakup:

1. Core
2. Identity and Access
3. Master Data
4. Catalog
5. Collection
6. Member
7. Circulation
8. Digital Repository
9. OPAC
10. Reporting
11. Audit and Monitoring

Use case yang bersifat fase lanjutan:
1. Integration
2. Acquisition penuh
3. SSO
4. Reservasi lanjutan
5. Notifikasi lanjutan
6. RFID

## 5. Daftar Aktor

### 5.1 Aktor Internal Utama
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan

### 5.2 Aktor Pengguna Layanan
1. Mahasiswa
2. Dosen
3. Tenaga Kependidikan
4. Tamu OPAC

### 5.3 Aktor Sistem Eksternal
1. Redis
2. Meilisearch
3. Object Storage
4. OCR Engine
5. SMTP Server
6. Sumber Data Anggota Eksternal pada fase lanjutan

## 6. Klasifikasi Aktor

### 6.1 Aktor Primer
Aktor yang secara langsung memicu use case:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan
7. Mahasiswa
8. Dosen
9. Tenaga Kependidikan
10. Tamu OPAC

### 6.2 Aktor Sekunder
Aktor pendukung proses:
1. Redis
2. Meilisearch
3. Object Storage
4. OCR Engine
5. SMTP Server

## 7. Tujuan Aktor

### 7.1 Super Admin
1. Mengelola sistem secara keseluruhan
2. Mengelola user, role, permission
3. Mengelola konfigurasi umum
4. Mengawasi audit aktivitas

### 7.2 Admin Perpustakaan
1. Mengelola operasional perpustakaan
2. Mengelola data master
3. Mengelola anggota
4. Mengawasi katalog, koleksi, dan laporan

### 7.3 Pustakawan
1. Mengelola bibliographic record
2. Mengelola item fisik
3. Mengelola kualitas metadata
4. Menyiapkan data koleksi untuk OPAC

### 7.4 Petugas Sirkulasi
1. Memproses peminjaman
2. Memproses pengembalian
3. Memproses perpanjangan
4. Memeriksa denda dan keterlambatan

### 7.5 Operator Repositori Digital
1. Mengunggah file digital
2. Mengelola metadata file digital
3. Mengelola akses file digital
4. Memantau OCR dan indexing dasar

### 7.6 Pimpinan Perpustakaan
1. Melihat dashboard dan laporan
2. Menilai performa layanan perpustakaan

### 7.7 Mahasiswa
1. Mencari koleksi
2. Melihat ketersediaan koleksi
3. Mengakses koleksi digital sesuai hak akses

### 7.8 Dosen
1. Mencari referensi fisik dan digital
2. Mengakses file digital sesuai hak akses
3. Melihat detail ketersediaan koleksi

### 7.9 Tenaga Kependidikan
1. Mencari koleksi
2. Mengakses fitur anggota sesuai hak akses

### 7.10 Tamu OPAC
1. Menelusuri katalog publik
2. Melihat detail koleksi yang dipublikasikan

## 8. Relasi Aktor ke Modul

| Aktor | Core | Identity | Master Data | Catalog | Collection | Member | Circulation | Digital Repository | OPAC | Reporting | Audit |
|---|---|---|---|---|---|---|---|---|---|---|---|
| Super Admin | Ya | Ya | Ya | Ya | Ya | Ya | Lihat | Lihat | Lihat | Ya | Ya |
| Admin Perpustakaan | Ya | Terbatas | Ya | Ya | Ya | Ya | Ya | Ya | Lihat | Ya | Terbatas |
| Pustakawan | Lihat | Profil | Lihat | Ya | Ya | Lihat | Tidak | Terbatas | Lihat | Terbatas | Tidak |
| Petugas Sirkulasi | Lihat | Profil | Tidak | Lihat | Lihat | Lihat | Ya | Tidak | Lihat | Terbatas | Tidak |
| Operator Repositori Digital | Lihat | Profil | Lihat | Lihat | Tidak | Tidak | Tidak | Ya | Lihat | Terbatas | Tidak |
| Pimpinan Perpustakaan | Lihat | Profil | Tidak | Lihat | Lihat | Lihat | Lihat | Lihat | Lihat | Ya | Tidak |
| Mahasiswa | Tidak | Fase lanjutan | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Dosen | Tidak | Fase lanjutan | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Tenaga Kependidikan | Tidak | Fase lanjutan | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Tamu OPAC | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |

## 9. Diagram Relasi Use Case Tingkat Tinggi Secara Naratif
Kelompok use case utama PERPUSQU:

1. Pengelolaan Sistem
   Meliputi login, user, role, permission, dan konfigurasi umum.

2. Pengelolaan Referensi
   Meliputi master data seperti pengarang, penerbit, bahasa, subjek, klasifikasi, lokasi rak, fakultas, program studi, dan kondisi item.

3. Pengelolaan Katalog dan Koleksi
   Meliputi bibliographic record dan item fisik.

4. Pengelolaan Anggota dan Sirkulasi
   Meliputi anggota, pinjam, kembali, perpanjang, keterlambatan, dan denda.

5. Pengelolaan Repositori Digital
   Meliputi unggah file, metadata file, preview, OCR, indexing, dan kontrol akses.

6. Pencarian Publik melalui OPAC
   Meliputi pencarian, filter, daftar hasil, detail koleksi, ketersediaan item, dan akses digital.

7. Pelaporan dan Monitoring
   Meliputi dashboard, laporan, audit log, dan pemantauan job dasar.

## 10. Daftar Use Case Resmi
Kode use case disusun dengan pola:
UC-[MODUL]-[NOMOR]

### 10.1 Modul Core
1. UC-CORE-001 Mengelola profil institusi
2. UC-CORE-002 Mengelola konfigurasi umum sistem
3. UC-CORE-003 Mengelola parameter operasional perpustakaan
4. UC-CORE-004 Melihat dashboard admin

### 10.2 Modul Identity and Access
1. UC-IDA-001 Login ke sistem
2. UC-IDA-002 Logout dari sistem
3. UC-IDA-003 Mengelola pengguna
4. UC-IDA-004 Mengelola role
5. UC-IDA-005 Mengelola permission
6. UC-IDA-006 Mengaitkan role ke pengguna
7. UC-IDA-007 Mengubah password
8. UC-IDA-008 Melihat profil sendiri

### 10.3 Modul Master Data
1. UC-MAS-001 Mengelola pengarang
2. UC-MAS-002 Mengelola penerbit
3. UC-MAS-003 Mengelola bahasa
4. UC-MAS-004 Mengelola klasifikasi
5. UC-MAS-005 Mengelola subjek
6. UC-MAS-006 Mengelola jenis koleksi
7. UC-MAS-007 Mengelola lokasi rak
8. UC-MAS-008 Mengelola fakultas
9. UC-MAS-009 Mengelola program studi
10. UC-MAS-010 Mengelola kondisi item

### 10.4 Modul Catalog
1. UC-CAT-001 Menambah bibliographic record
2. UC-CAT-002 Mengubah bibliographic record
3. UC-CAT-003 Melihat detail bibliographic record
4. UC-CAT-004 Mencari bibliographic record pada admin
5. UC-CAT-005 Menautkan pengarang dan subjek ke bibliographic record
6. UC-CAT-006 Mengelola cover bibliographic record
7. UC-CAT-007 Mengaktifkan atau menonaktifkan bibliographic record

### 10.5 Modul Collection
1. UC-COL-001 Menambah item fisik
2. UC-COL-002 Mengubah item fisik
3. UC-COL-003 Melihat detail item fisik
4. UC-COL-004 Mencari item fisik
5. UC-COL-005 Mengubah status item fisik
6. UC-COL-006 Melihat histori item fisik

### 10.6 Modul Member
1. UC-MEM-001 Menambah anggota
2. UC-MEM-002 Mengubah anggota
3. UC-MEM-003 Melihat detail anggota
4. UC-MEM-004 Mencari anggota
5. UC-MEM-005 Mengaktifkan atau menonaktifkan anggota
6. UC-MEM-006 Memblokir atau membuka blokir anggota
7. UC-MEM-007 Melihat histori transaksi anggota

### 10.7 Modul Circulation
1. UC-CIR-001 Memproses peminjaman item
2. UC-CIR-002 Memproses pengembalian item
3. UC-CIR-003 Memproses perpanjangan pinjaman
4. UC-CIR-004 Melihat pinjaman aktif
5. UC-CIR-005 Melihat histori transaksi sirkulasi
6. UC-CIR-006 Menghitung keterlambatan dan denda

### 10.8 Modul Digital Repository
1. UC-DIG-001 Mengunggah aset digital
2. UC-DIG-002 Mengubah metadata aset digital
3. UC-DIG-003 Melihat detail aset digital
4. UC-DIG-004 Mempreview file digital
5. UC-DIG-005 Mengelola akses aset digital
6. UC-DIG-006 Mengaktifkan atau menonaktifkan publikasi aset digital
7. UC-DIG-007 Menjalankan OCR aset digital
8. UC-DIG-008 Mengindeks isi dokumen digital

### 10.9 Modul OPAC
1. UC-OPA-001 Mencari koleksi pada OPAC
2. UC-OPA-002 Memfilter hasil pencarian OPAC
3. UC-OPA-003 Melihat detail koleksi pada OPAC
4. UC-OPA-004 Melihat ketersediaan item fisik pada OPAC
5. UC-OPA-005 Mengakses preview atau file digital sesuai hak akses

### 10.10 Modul Reporting
1. UC-REP-001 Melihat dashboard statistik
2. UC-REP-002 Melihat laporan jumlah koleksi
3. UC-REP-003 Melihat laporan anggota
4. UC-REP-004 Melihat laporan transaksi sirkulasi
5. UC-REP-005 Melihat laporan keterlambatan dan denda
6. UC-REP-006 Melihat laporan koleksi populer
7. UC-REP-007 Melihat laporan akses digital

### 10.11 Modul Audit and Monitoring
1. UC-AUD-001 Melihat audit log
2. UC-AUD-002 Menelusuri aktivitas pengguna
3. UC-AUD-003 Melihat monitoring job queue dasar

## 11. Detail Use Case Inti

## UC-IDA-001 Login ke Sistem

### Tujuan
Memungkinkan pengguna internal masuk ke sistem sesuai hak akses.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan, Petugas Sirkulasi, Operator Repositori Digital, Pimpinan Perpustakaan

### Prasyarat
1. Pengguna telah memiliki akun aktif.
2. Pengguna memiliki kredensial yang sah.

### Pemicu
Pengguna membuka halaman login dan mengirimkan kredensial.

### Alur Utama
1. Pengguna membuka halaman login.
2. Sistem menampilkan form login.
3. Pengguna mengisi email atau username dan password.
4. Sistem memvalidasi input.
5. Sistem memeriksa kredensial.
6. Sistem membuat session login.
7. Sistem mencatat aktivitas login.
8. Sistem mengarahkan pengguna ke dashboard sesuai peran.

### Alur Alternatif
1. Jika input kosong atau salah format, sistem menampilkan validasi.
2. Jika kredensial salah, sistem menampilkan pesan gagal login.
3. Jika akun nonaktif, sistem menolak login.

### Pasca Kondisi
1. Session aktif terbentuk.
2. Pengguna masuk ke area sistem yang sesuai.

### Kebutuhan Terkait
1. FR-IDA-001
2. FR-IDA-010
3. FR-AUD-001

---

## UC-IDA-003 Mengelola Pengguna

### Tujuan
Memungkinkan admin mengelola akun pengguna internal sistem.

### Aktor Utama
Super Admin, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor telah login.
2. Aktor memiliki permission pengelolaan user.

### Pemicu
Aktor membuka menu manajemen pengguna.

### Alur Utama
1. Aktor membuka daftar pengguna.
2. Sistem menampilkan daftar pengguna dengan search, filter, dan pagination.
3. Aktor memilih tambah pengguna baru atau mengubah pengguna yang ada.
4. Aktor mengisi data pengguna.
5. Sistem memvalidasi data.
6. Sistem menyimpan data pengguna.
7. Sistem mencatat aktivitas ke audit log.
8. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika data tidak valid, sistem menampilkan error validasi.
2. Jika email atau username duplikat, sistem menolak penyimpanan.
3. Jika aktor tidak berwenang, sistem menolak akses.

### Pasca Kondisi
1. Data pengguna tersimpan atau diperbarui.
2. Hak akses pengguna dapat digunakan pada proses login berikutnya.

### Kebutuhan Terkait
1. FR-IDA-003
2. FR-IDA-007
3. FR-AUD-002

---

## UC-CORE-002 Mengelola Konfigurasi Umum Sistem

### Tujuan
Memungkinkan admin mengatur parameter umum perpustakaan dan sistem.

### Aktor Utama
Super Admin, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Aktor memiliki hak akses konfigurasi.

### Pemicu
Aktor membuka menu konfigurasi sistem.

### Alur Utama
1. Aktor membuka halaman konfigurasi.
2. Sistem menampilkan data profil institusi dan parameter operasional.
3. Aktor memperbarui data yang diperlukan.
4. Sistem memvalidasi data.
5. Sistem menyimpan perubahan.
6. Sistem mencatat aktivitas perubahan.
7. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika data tidak valid, sistem menampilkan pesan validasi.
2. Jika aktor tidak berhak, sistem menolak perubahan.

### Pasca Kondisi
1. Konfigurasi baru berlaku untuk proses bisnis berikutnya.

### Kebutuhan Terkait
1. FR-CORE-002
2. FR-CORE-003
3. FR-CORE-006

---

## UC-MAS-001 Mengelola Pengarang

### Tujuan
Memungkinkan pengelola menambah, mengubah, menonaktifkan, dan mencari data pengarang.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Aktor memiliki hak akses ke master data.

### Pemicu
Aktor membuka menu pengarang.

### Alur Utama
1. Aktor membuka daftar pengarang.
2. Sistem menampilkan daftar dengan search dan pagination.
3. Aktor menambah atau mengubah data pengarang.
4. Sistem memvalidasi input.
5. Sistem menyimpan perubahan.
6. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika nama pengarang kosong, sistem menolak simpan.
2. Jika pengarang sedang dipakai banyak data dan dihapus, sistem menolak atau mengubah ke status nonaktif sesuai aturan.

### Pasca Kondisi
1. Data pengarang siap dipakai pada bibliographic record.

### Kebutuhan Terkait
1. FR-MAS-001
2. FR-MAS-012
3. FR-MAS-013

---

## UC-CAT-001 Menambah Bibliographic Record

### Tujuan
Memungkinkan pustakawan mencatat metadata judul atau karya ke sistem katalog.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Master data utama tersedia minimal sebagian.
3. Aktor memiliki hak akses katalog.

### Pemicu
Aktor memilih aksi tambah bibliographic record.

### Alur Utama
1. Aktor membuka form tambah bibliographic record.
2. Sistem menampilkan form metadata katalog.
3. Aktor mengisi data seperti judul, pengarang, penerbit, tahun terbit, bahasa, subjek, klasifikasi, jenis koleksi, sinopsis, dan metadata lain.
4. Aktor menyimpan data.
5. Sistem memvalidasi input.
6. Sistem menyimpan bibliographic record.
7. Sistem mencatat aktivitas ke audit log.
8. Sistem menampilkan detail record atau kembali ke daftar.

### Alur Alternatif
1. Jika field wajib kosong, sistem menampilkan error validasi.
2. Jika data pendukung belum tersedia, aktor diarahkan melengkapi master data sesuai hak akses.
3. Jika penyimpanan gagal, sistem menampilkan pesan gagal.

### Pasca Kondisi
1. Bibliographic record baru tersedia dalam sistem admin.
2. Record siap dikaitkan dengan item fisik atau aset digital.

### Kebutuhan Terkait
1. FR-CAT-001
2. FR-CAT-006
3. FR-CAT-013

---

## UC-CAT-002 Mengubah Bibliographic Record

### Tujuan
Memungkinkan pustakawan memperbaiki atau memperbarui metadata bibliographic record.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Bibliographic record sudah ada.

### Pemicu
Aktor membuka detail record dan memilih ubah.

### Alur Utama
1. Aktor membuka daftar atau detail bibliographic record.
2. Aktor memilih aksi ubah.
3. Sistem menampilkan form edit.
4. Aktor memperbarui metadata.
5. Sistem memvalidasi perubahan.
6. Sistem menyimpan pembaruan.
7. Sistem mencatat audit log.
8. Sistem memperbarui index pencarian bila diperlukan.

### Alur Alternatif
1. Jika record tidak ditemukan, sistem menampilkan error.
2. Jika perubahan tidak valid, sistem menampilkan validasi.
3. Jika index gagal diperbarui, data utama tetap sah dan job retry dijalankan sesuai desain.

### Pasca Kondisi
1. Metadata record terbaru tersimpan.
2. Informasi baru tercermin pada admin dan OPAC sesuai status publikasi.

### Kebutuhan Terkait
1. FR-CAT-002
2. FR-CAT-012
3. SRH-004

---

## UC-CAT-003 Melihat Detail Bibliographic Record

### Tujuan
Memungkinkan pengelola melihat detail metadata satu bibliographic record beserta item fisik dan aset digital yang terkait.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan, Petugas Sirkulasi, Pimpinan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Record tersedia.

### Pemicu
Aktor memilih satu bibliographic record dari daftar.

### Alur Utama
1. Aktor membuka daftar bibliographic record.
2. Aktor memilih satu record.
3. Sistem menampilkan metadata utama.
4. Sistem menampilkan relasi item fisik.
5. Sistem menampilkan relasi aset digital.
6. Sistem menampilkan status publikasi dan informasi pendukung.

### Pasca Kondisi
1. Aktor memperoleh informasi lengkap mengenai satu judul atau karya.

### Kebutuhan Terkait
1. FR-CAT-003
2. FR-CAT-015

---

## UC-COL-001 Menambah Item Fisik

### Tujuan
Memungkinkan pengelola mendaftarkan eksemplar fisik untuk sebuah bibliographic record.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Bibliographic record induk sudah tersedia.
3. Aktor memiliki hak akses collection.

### Pemicu
Aktor memilih aksi tambah item fisik.

### Alur Utama
1. Aktor membuka detail bibliographic record atau menu collection.
2. Aktor memilih tambah item fisik.
3. Sistem menampilkan form item.
4. Aktor mengisi kode inventaris atau barcode, lokasi rak, kondisi item, dan status awal.
5. Sistem memvalidasi data.
6. Sistem menyimpan item fisik.
7. Sistem mencatat aktivitas ke audit log.
8. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika barcode duplikat, sistem menolak.
2. Jika bibliographic record tidak sah, sistem menolak.
3. Jika lokasi rak tidak valid, sistem menampilkan validasi.

### Pasca Kondisi
1. Item fisik baru terhubung ke bibliographic record.
2. Item siap dipakai dalam proses sirkulasi.

### Kebutuhan Terkait
1. FR-COL-001
2. FR-COL-002
3. FR-COL-010

---

## UC-COL-005 Mengubah Status Item Fisik

### Tujuan
Memungkinkan pengelola memperbarui status item karena perubahan kondisi operasional di luar transaksi pinjam normal.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Item fisik tersedia dalam sistem.

### Pemicu
Aktor memilih aksi ubah status item.

### Alur Utama
1. Aktor membuka detail item.
2. Aktor memilih status baru seperti rusak, hilang, perbaikan, atau nonaktif.
3. Sistem memvalidasi apakah perubahan diperbolehkan.
4. Sistem menyimpan status baru.
5. Sistem mencatat histori perubahan dan audit log.
6. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika item sedang memiliki pinjaman aktif, sistem menolak status tertentu sesuai aturan.
2. Jika aktor tidak berwenang, sistem menolak perubahan.

### Pasca Kondisi
1. Status item terbarui.
2. Ketersediaan item ikut menyesuaikan.

### Kebutuhan Terkait
1. FR-COL-007
2. FR-COL-008
3. FR-COL-012

---

## UC-MEM-001 Menambah Anggota

### Tujuan
Memungkinkan admin mendaftarkan anggota perpustakaan.

### Aktor Utama
Super Admin, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Aktor memiliki hak akses member.

### Pemicu
Aktor memilih tambah anggota.

### Alur Utama
1. Aktor membuka menu anggota.
2. Aktor memilih tambah anggota.
3. Sistem menampilkan form data anggota.
4. Aktor mengisi identitas anggota.
5. Sistem memvalidasi data.
6. Sistem menyimpan anggota.
7. Sistem memberikan nomor anggota unik bila diperlukan.
8. Sistem mencatat audit log.
9. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika nomor anggota duplikat, sistem menolak.
2. Jika data wajib kosong, sistem menampilkan validasi.

### Pasca Kondisi
1. Anggota baru siap digunakan dalam transaksi sirkulasi.

### Kebutuhan Terkait
1. FR-MEM-001
2. FR-MEM-003
3. FR-MEM-005

---

## UC-MEM-006 Memblokir atau Membuka Blokir Anggota

### Tujuan
Memungkinkan admin mengubah status blokir anggota sesuai aturan perpustakaan.

### Aktor Utama
Super Admin, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Anggota tersedia dalam sistem.

### Pemicu
Aktor memilih aksi blokir atau buka blokir.

### Alur Utama
1. Aktor membuka detail anggota.
2. Aktor memilih blokir atau buka blokir.
3. Sistem memvalidasi hak akses.
4. Sistem menyimpan status blokir.
5. Sistem mencatat audit log.
6. Sistem menampilkan notifikasi sukses.

### Pasca Kondisi
1. Status anggota berubah.
2. Status baru akan mempengaruhi transaksi pinjam berikutnya.

### Kebutuhan Terkait
1. FR-MEM-008
2. FR-MEM-010
3. BR-004

---

## UC-CIR-001 Memproses Peminjaman Item

### Tujuan
Memungkinkan petugas memproses peminjaman item fisik ke anggota.

### Aktor Utama
Petugas Sirkulasi, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Anggota aktif tersedia.
3. Item tersedia untuk dipinjam.

### Pemicu
Petugas membuka halaman peminjaman dan memasukkan anggota serta barcode item.

### Alur Utama
1. Petugas membuka halaman peminjaman.
2. Petugas memilih atau mencari anggota.
3. Sistem menampilkan status anggota.
4. Petugas memindai atau memasukkan barcode item.
5. Sistem memverifikasi item.
6. Sistem memverifikasi batas pinjam anggota.
7. Sistem menghitung tanggal jatuh tempo.
8. Sistem menyimpan transaksi pinjam.
9. Sistem mengubah status item menjadi dipinjam.
10. Sistem mencatat audit log.
11. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika anggota diblokir atau nonaktif, sistem menolak transaksi.
2. Jika item tidak tersedia, sistem menolak transaksi.
3. Jika batas pinjam terlampaui, sistem menolak transaksi.
4. Jika barcode tidak ditemukan, sistem menampilkan error.

### Pasca Kondisi
1. Pinjaman aktif terbentuk.
2. Item berubah status menjadi dipinjam.

### Kebutuhan Terkait
1. FR-CIR-001
2. FR-CIR-004
3. FR-CIR-005
4. FR-CIR-006
5. FR-CIR-009

---

## UC-CIR-002 Memproses Pengembalian Item

### Tujuan
Memungkinkan petugas memproses pengembalian item yang sedang dipinjam.

### Aktor Utama
Petugas Sirkulasi, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Terdapat pinjaman aktif atas item.

### Pemicu
Petugas memindai barcode item yang dikembalikan.

### Alur Utama
1. Petugas membuka halaman pengembalian.
2. Petugas memindai barcode item.
3. Sistem mencari pinjaman aktif terkait.
4. Sistem menghitung selisih tanggal.
5. Sistem menghitung denda jika terlambat.
6. Petugas mengonfirmasi pengembalian.
7. Sistem menutup transaksi pinjam.
8. Sistem memperbarui status item menjadi tersedia atau status lain sesuai kondisi.
9. Sistem mencatat audit log.
10. Sistem menampilkan hasil pengembalian.

### Alur Alternatif
1. Jika pinjaman aktif tidak ditemukan, sistem menolak proses.
2. Jika item rusak saat kembali, petugas mencatat kondisi kembali dan sistem menyesuaikan status item.
3. Jika terjadi keterlambatan, sistem menampilkan denda.

### Pasca Kondisi
1. Transaksi pinjaman tertutup.
2. Status item diperbarui.
3. Histori anggota bertambah.

### Kebutuhan Terkait
1. FR-CIR-002
2. FR-CIR-007
3. FR-CIR-008
4. FR-CIR-010
5. FR-CIR-014

---

## UC-CIR-003 Memproses Perpanjangan Pinjaman

### Tujuan
Memungkinkan petugas memperpanjang masa pinjam bila aturan mengizinkan.

### Aktor Utama
Petugas Sirkulasi, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Pinjaman masih aktif.
2. Anggota tidak diblokir.
3. Perpanjangan masih diperbolehkan oleh aturan.

### Pemicu
Petugas memilih transaksi pinjam aktif dan aksi perpanjang.

### Alur Utama
1. Petugas membuka detail pinjaman aktif.
2. Petugas memilih perpanjang.
3. Sistem memvalidasi syarat perpanjangan.
4. Sistem menghitung tanggal jatuh tempo baru.
5. Sistem menyimpan perubahan.
6. Sistem mencatat audit log.
7. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika pinjaman sudah terlambat dan tidak boleh diperpanjang, sistem menolak.
2. Jika jumlah perpanjangan melebihi aturan, sistem menolak.

### Pasca Kondisi
1. Tanggal jatuh tempo baru tersimpan.

### Kebutuhan Terkait
1. FR-CIR-003
2. BR-CIR-006

---

## UC-DIG-001 Mengunggah Aset Digital

### Tujuan
Memungkinkan operator mengunggah file digital yang terhubung ke bibliographic record.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pustakawan, Operator Repositori Digital sesuai hak akses

### Prasyarat
1. Aktor login.
2. Bibliographic record induk tersedia.
3. Aktor memiliki hak akses ke digital repository.

### Pemicu
Aktor memilih aksi unggah file digital.

### Alur Utama
1. Aktor membuka halaman aset digital atau detail bibliographic record.
2. Aktor memilih unggah file.
3. Sistem menampilkan form metadata dan file upload.
4. Aktor memilih file dan mengisi metadata.
5. Sistem memvalidasi tipe dan ukuran file.
6. Sistem menyimpan file ke object storage.
7. Sistem menyimpan metadata aset digital ke database.
8. Sistem mencatat audit log.
9. Sistem mengirim job OCR atau indexing bila relevan.
10. Sistem menampilkan notifikasi sukses.

### Alur Alternatif
1. Jika file tidak valid, sistem menolak unggah.
2. Jika bibliographic record tidak sah, sistem menolak unggah.
3. Jika object storage gagal menyimpan, sistem membatalkan metadata final.

### Pasca Kondisi
1. Aset digital tersimpan.
2. Aset digital siap dipreview atau dipublikasikan sesuai status.

### Kebutuhan Terkait
1. FR-DIG-001
2. FR-DIG-002
3. FR-DIG-006
4. FR-DIG-011

---

## UC-DIG-005 Mengelola Akses Aset Digital

### Tujuan
Memungkinkan operator mengatur siapa yang dapat mengakses aset digital.

### Aktor Utama
Super Admin, Admin Perpustakaan, Operator Repositori Digital sesuai hak akses

### Prasyarat
1. Aset digital tersedia.
2. Aktor login dan memiliki izin.

### Pemicu
Aktor membuka detail aset digital dan memilih aturan akses.

### Alur Utama
1. Aktor membuka detail aset digital.
2. Aktor mengatur status publikasi dan hak akses.
3. Sistem memvalidasi konfigurasi.
4. Sistem menyimpan aturan akses.
5. Sistem mencatat audit log.
6. Sistem menyesuaikan tampilan pada OPAC bila relevan.

### Alur Alternatif
1. Jika aturan tidak valid, sistem menolak simpan.

### Pasca Kondisi
1. Hak akses aset digital berlaku pada proses preview atau unduh berikutnya.

### Kebutuhan Terkait
1. FR-DIG-008
2. FR-DIG-009
3. FR-DIG-016
4. BR-DIG-001

---

## UC-DIG-007 Menjalankan OCR Aset Digital

### Tujuan
Menghasilkan teks dari dokumen scan agar dapat diindeks dan dicari.

### Aktor Utama
Sistem, dipicu oleh Operator Repositori Digital melalui unggah aset digital

### Aktor Pendukung
OCR Engine, Queue Worker

### Prasyarat
1. File mendukung OCR.
2. Aset digital berhasil tersimpan.

### Pemicu
Unggah file digital yang memenuhi syarat OCR atau perintah reprocess OCR.

### Alur Utama
1. Sistem mengirim job OCR ke queue.
2. Queue worker mengambil job.
3. Sistem membaca file dari storage.
4. Sistem memproses OCR melalui engine.
5. Sistem menyimpan hasil OCR.
6. Sistem menandai status OCR berhasil.
7. Sistem mengirim job indexing bila relevan.

### Alur Alternatif
1. Jika OCR gagal, sistem menyimpan status gagal dan log error.
2. Sistem dapat melakukan retry sesuai kebijakan.

### Pasca Kondisi
1. Hasil OCR tersedia untuk pencarian bila proses berhasil.

### Kebutuhan Terkait
1. FR-DIG-011
2. FR-DIG-012
3. FR-DIG-013
4. BR-DIG-005

---

## UC-OPA-001 Mencari Koleksi pada OPAC

### Tujuan
Memungkinkan pengguna menemukan koleksi fisik dan digital dari satu pintu pencarian.

### Aktor Utama
Mahasiswa, Dosen, Tenaga Kependidikan, Tamu OPAC

### Prasyarat
1. OPAC publik aktif.
2. Data katalog telah terindeks.

### Pemicu
Pengguna memasukkan kata kunci pencarian di OPAC.

### Alur Utama
1. Pengguna membuka halaman OPAC.
2. Pengguna memasukkan kata kunci.
3. Sistem mengirim query ke search service.
4. Search service membaca indeks Meilisearch.
5. Sistem mengambil data final dari MySQL.
6. Sistem menampilkan hasil pencarian.
7. Pengguna dapat membuka detail koleksi.

### Alur Alternatif
1. Jika tidak ada hasil, sistem menampilkan pesan data tidak ditemukan.
2. Jika search engine tidak tersedia, sistem menampilkan fallback error yang terkendali sesuai kebijakan teknis.

### Pasca Kondisi
1. Pengguna melihat daftar koleksi yang relevan.

### Kebutuhan Terkait
1. FR-OPA-001
2. FR-OPA-002
3. FR-OPA-013
4. SRH-001

---

## UC-OPA-003 Melihat Detail Koleksi pada OPAC

### Tujuan
Memungkinkan pengguna melihat detail satu koleksi, termasuk item fisik dan aset digital yang tersedia.

### Aktor Utama
Mahasiswa, Dosen, Tenaga Kependidikan, Tamu OPAC

### Prasyarat
1. Bibliographic record dipublikasikan untuk OPAC.

### Pemicu
Pengguna memilih salah satu hasil pencarian.

### Alur Utama
1. Pengguna memilih satu hasil pencarian.
2. Sistem menampilkan detail bibliographic record.
3. Sistem menampilkan data pengarang, penerbit, subjek, sinopsis, dan metadata terkait.
4. Sistem menampilkan ketersediaan item fisik.
5. Sistem menampilkan informasi lokasi rak bila ada item tersedia.
6. Sistem menampilkan ketersediaan aset digital.
7. Sistem menampilkan tombol preview atau akses bila pengguna berhak.

### Pasca Kondisi
1. Pengguna memperoleh gambaran lengkap tentang koleksi yang dicari.

### Kebutuhan Terkait
1. FR-OPA-005
2. FR-OPA-006
3. FR-OPA-007
4. FR-OPA-008

---

## UC-OPA-005 Mengakses Preview atau File Digital Sesuai Hak Akses

### Tujuan
Memungkinkan pengguna mempreview atau membuka file digital sesuai aturan akses.

### Aktor Utama
Mahasiswa, Dosen, Tenaga Kependidikan, Tamu OPAC sesuai aturan akses
### Aktor Pendukung
Object Storage, PDF.js

### Prasyarat
1. Aset digital tersedia.
2. Akses file sesuai aturan.

### Pemicu
Pengguna menekan tombol preview atau akses file.

### Alur Utama
1. Pengguna membuka detail koleksi.
2. Pengguna memilih preview atau akses file.
3. Sistem memeriksa aturan akses.
4. Jika diizinkan, sistem menampilkan preview PDF atau memberikan akses file sesuai kebijakan.
5. Sistem mencatat aktivitas akses bila fitur pencatatan diaktifkan.

### Alur Alternatif
1. Jika pengguna tidak berhak, sistem menolak akses.
2. Jika file tidak tersedia, sistem menampilkan pesan gagal.
3. Jika aset sedang embargo, sistem menolak akses bagi pihak yang tidak berhak.

### Pasca Kondisi
1. Pengguna berhasil melihat file atau ditolak sesuai aturan.

### Kebutuhan Terkait
1. FR-DIG-016
2. FR-OPA-009
3. BR-DIG-001
4. BR-DIG-003

---

## UC-REP-001 Melihat Dashboard Statistik

### Tujuan
Memungkinkan pimpinan atau admin melihat ringkasan statistik operasional perpustakaan.

### Aktor Utama
Super Admin, Admin Perpustakaan, Pimpinan Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Data statistik tersedia.

### Pemicu
Aktor membuka dashboard laporan.

### Alur Utama
1. Aktor membuka dashboard statistik.
2. Sistem menampilkan ringkasan jumlah koleksi, anggota, transaksi, keterlambatan, dan akses digital sesuai hak akses.
3. Aktor membaca informasi statistik.
4. Aktor dapat berpindah ke laporan rinci.

### Pasca Kondisi
1. Aktor memperoleh ringkasan kondisi operasional perpustakaan.

### Kebutuhan Terkait
1. FR-REP-001
2. REP-002

---

## UC-AUD-001 Melihat Audit Log

### Tujuan
Memungkinkan admin teknis atau admin berwenang melihat aktivitas penting yang terekam di sistem.

### Aktor Utama
Super Admin, Admin Perpustakaan sesuai hak akses

### Prasyarat
1. Aktor login.
2. Aktor memiliki izin audit log.

### Pemicu
Aktor membuka menu audit log.

### Alur Utama
1. Aktor membuka halaman audit log.
2. Sistem menampilkan daftar aktivitas dengan filter tanggal, aktor, modul, dan aksi bila tersedia.
3. Aktor menelusuri aktivitas tertentu.
4. Sistem menampilkan detail log.

### Alur Alternatif
1. Jika aktor tidak berhak, sistem menolak akses.

### Pasca Kondisi
1. Aktor memperoleh informasi penelusuran aktivitas sistem.

### Kebutuhan Terkait
1. FR-AUD-008
2. AUD-001
3. AUD-002

## 12. Use Case Pendukung Fase 1

### UC-CAT-004 Mencari Bibliographic Record pada Admin
Aktor:
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi terbatas lihat

Tujuan:
Memudahkan pencarian bibliographic record untuk kebutuhan katalogisasi, sirkulasi, atau verifikasi data.

### UC-COL-004 Mencari Item Fisik
Aktor:
1. Admin Perpustakaan
2. Pustakawan
3. Petugas Sirkulasi

Tujuan:
Memudahkan pencarian item dengan barcode, kode inventaris, judul, atau lokasi rak.

### UC-MEM-004 Mencari Anggota
Aktor:
1. Admin Perpustakaan
2. Petugas Sirkulasi

Tujuan:
Memudahkan identifikasi anggota saat transaksi.

### UC-CIR-004 Melihat Pinjaman Aktif
Aktor:
1. Petugas Sirkulasi
2. Admin Perpustakaan
3. Pimpinan terbatas

Tujuan:
Melihat daftar pinjaman yang masih berjalan.

### UC-DIG-004 Mempreview File Digital
Aktor:
1. Operator Repositori Digital
2. Admin Perpustakaan
3. Pustakawan
4. Pengguna OPAC sesuai hak akses

Tujuan:
Memastikan file digital dapat ditampilkan dengan benar.

### UC-AUD-003 Melihat Monitoring Job Queue Dasar
Aktor:
1. Super Admin
2. Admin teknis yang diberi hak

Tujuan:
Memantau keberhasilan atau kegagalan job background seperti OCR, indexing, dan email.

## 13. Use Case Fase Lanjutan
Use case berikut tidak wajib pada fase 1, tetapi dicatat agar kesinambungan desain terjaga.

### 13.1 Integration
1. UC-INT-001 Import anggota dari sumber eksternal
2. UC-INT-002 Sinkronisasi akun pengguna
3. UC-INT-003 Sinkronisasi data akademik dasar

### 13.2 Acquisition
1. UC-ACQ-001 Mencatat usulan pengadaan
2. UC-ACQ-002 Mencatat penerimaan koleksi baru
3. UC-ACQ-003 Merekam sumber pengadaan

### 13.3 Notification
1. UC-NOT-001 Mengirim email pengingat jatuh tempo
2. UC-NOT-002 Mengirim email keterlambatan
3. UC-NOT-003 Mengirim notifikasi administrasi

### 13.4 Authentication Lanjutan
1. UC-SSO-001 Login melalui SSO kampus

## 14. Matriks Prioritas Use Case

### 14.1 Prioritas P1
1. UC-IDA-001 Login ke sistem
2. UC-IDA-002 Logout dari sistem
3. UC-IDA-003 Mengelola pengguna
4. UC-IDA-004 Mengelola role
5. UC-IDA-005 Mengelola permission
6. UC-CORE-002 Mengelola konfigurasi umum sistem
7. UC-CAT-001 Menambah bibliographic record
8. UC-CAT-002 Mengubah bibliographic record
9. UC-CAT-003 Melihat detail bibliographic record
10. UC-COL-001 Menambah item fisik
11. UC-COL-002 Mengubah item fisik
12. UC-MEM-001 Menambah anggota
13. UC-MEM-002 Mengubah anggota
14. UC-CIR-001 Memproses peminjaman item
15. UC-CIR-002 Memproses pengembalian item
16. UC-DIG-001 Mengunggah aset digital
17. UC-DIG-004 Mempreview file digital
18. UC-OPA-001 Mencari koleksi pada OPAC
19. UC-OPA-003 Melihat detail koleksi pada OPAC
20. UC-REP-001 Melihat dashboard statistik
21. UC-AUD-001 Melihat audit log

### 14.2 Prioritas P2
1. UC-CIR-003 Memproses perpanjangan pinjaman
2. UC-COL-006 Melihat histori item fisik
3. UC-MEM-007 Melihat histori transaksi anggota
4. UC-DIG-007 Menjalankan OCR aset digital
5. UC-DIG-008 Mengindeks isi dokumen digital
6. UC-REP-004 Melihat laporan transaksi sirkulasi
7. UC-REP-005 Melihat laporan keterlambatan dan denda
8. UC-REP-006 Melihat laporan koleksi populer
9. UC-REP-007 Melihat laporan akses digital

### 14.3 Prioritas P3
1. Integration dasar
2. Notification email
3. Import anggota
4. Acquisition dasar

### 14.4 Prioritas P4
1. SSO
2. WhatsApp notifikasi
3. RFID
4. Fitur lanjutan lain

## 15. Relasi Include dan Extend Secara Naratif

### 15.1 Relasi Include
Use case berikut mencakup proses lain secara wajib:

1. UC-CIR-001 Memproses peminjaman item include verifikasi anggota.
2. UC-CIR-001 Memproses peminjaman item include verifikasi item.
3. UC-CIR-001 Memproses peminjaman item include hitung jatuh tempo.
4. UC-CIR-002 Memproses pengembalian item include hitung keterlambatan dan denda.
5. UC-DIG-001 Mengunggah aset digital include validasi file.
6. UC-OPA-001 Mencari koleksi pada OPAC include pencarian indeks.
7. UC-OPA-003 Melihat detail koleksi pada OPAC include ambil data bibliographic record dan ketersediaan item.

### 15.2 Relasi Extend
Use case berikut bersifat perluasan kondisi tertentu:

1. UC-CIR-002 Memproses pengembalian item extend pencatatan kondisi item kembali.
2. UC-DIG-001 Mengunggah aset digital extend OCR aset digital jika file memenuhi syarat.
3. UC-DIG-001 Mengunggah aset digital extend indexing isi dokumen jika OCR atau teks tersedia.
4. UC-OPA-005 Mengakses preview atau file digital extend validasi embargo dan hak akses.
5. UC-CIR-003 Memproses perpanjangan pinjaman extend validasi aturan perpanjangan.

## 16. Pemetaan Use Case ke Modul dan Dokumen Turunan

| Kode Use Case | Modul | Dokumen Turunan Utama |
|---|---|---|
| UC-IDA-001 s.d. UC-IDA-008 | Identity and Access | ROLE_PERMISSION_MATRIX, MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER |
| UC-CORE-001 s.d. UC-CORE-004 | Core | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER |
| UC-MAS-001 s.d. UC-MAS-010 | Master Data | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, MODEL_MAP, SCHEMA |
| UC-CAT-001 s.d. UC-CAT-007 | Catalog | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, SCHEMA |
| UC-COL-001 s.d. UC-COL-006 | Collection | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, SCHEMA |
| UC-MEM-001 s.d. UC-MEM-007 | Member | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, SCHEMA |
| UC-CIR-001 s.d. UC-CIR-006 | Circulation | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, SCHEMA, WORKFLOW_STATE_MACHINE |
| UC-DIG-001 s.d. UC-DIG-008 | Digital Repository | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, MODEL_MAP, SCHEMA, STORAGE_FILE_POLICY, OCR_AND_DIGITAL_PROCESSING |
| UC-OPA-001 s.d. UC-OPA-005 | OPAC | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, SERVICE_LAYER, SEARCH_INDEXING_SPEC |
| UC-REP-001 s.d. UC-REP-007 | Reporting | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, REPORTING_SPEC |
| UC-AUD-001 s.d. UC-AUD-003 | Audit and Monitoring | MENU_MAP, ROUTE_MAP, VIEW_MAP, CONTROLLER_MAP, AUDIT_LOG_SPEC |

## 17. Dampak ke Desain UI
Dokumen ini mengharuskan keberadaan halaman minimum berikut pada fase 1:

1. Halaman login
2. Dashboard admin
3. Manajemen user
4. Manajemen role
5. Manajemen permission
6. Halaman profil pengguna
7. Master data per entitas
8. Daftar bibliographic record
9. Form tambah dan edit bibliographic record
10. Detail bibliographic record
11. Daftar item fisik
12. Form item fisik
13. Detail item fisik
14. Daftar anggota
15. Form anggota
16. Detail anggota
17. Halaman peminjaman
18. Halaman pengembalian
19. Halaman pinjaman aktif
20. Halaman histori transaksi
21. Daftar aset digital
22. Form unggah aset digital
23. Detail aset digital
24. Halaman OPAC
25. Halaman hasil pencarian OPAC
26. Halaman detail koleksi OPAC
27. Dashboard laporan
28. Halaman audit log
29. Halaman monitoring queue dasar bila ditampilkan dalam sistem

## 18. Dampak ke Desain Route
Dokumen ini mengharuskan struktur route yang konsisten untuk:
1. Route admin
2. Route OPAC publik
3. Route preview file digital
4. Route aksi transaksi sirkulasi
5. Route pengelolaan aset digital
6. Route laporan dan audit
7. Route API internal bila ada

## 19. Dampak ke Desain Service Layer
Dokumen ini mengharuskan service minimal berikut:

1. AuthService atau setara
2. UserManagementService
3. RolePermissionService
4. CatalogService
5. CatalogSearchService
6. PhysicalItemService
7. MemberService
8. LoanTransactionService
9. ReturnProcessingService
10. FineCalculationService
11. DigitalAssetUploadService
12. DigitalAssetAccessService
13. OcrProcessingService
14. SearchIndexService
15. ReportingService
16. AuditLogService

## 20. Dampak ke Desain Model
Dokumen ini mengharuskan model minimal berikut:

1. User
2. Role
3. Permission
4. Author
5. Publisher
6. Language
7. Classification
8. Subject
9. CollectionType
10. RackLocation
11. Faculty
12. StudyProgram
13. ItemCondition
14. BibliographicRecord
15. PhysicalItem
16. Member
17. Loan
18. Fine
19. DigitalAsset
20. OcrText
21. ActivityLog
22. SystemSetting

## 21. Ketentuan Konsistensi
Semua dokumen setelah ini wajib menjaga konsistensi berikut:

1. Satu use case harus punya menu atau titik akses yang jelas.
2. Satu use case harus punya route yang jelas.
3. Satu use case harus punya controller method yang jelas.
4. Satu use case utama harus punya service layer yang jelas.
5. Satu use case yang menyimpan data harus punya tabel dan model yang jelas.
6. Satu use case yang menampilkan halaman harus punya view yang jelas.
7. Tidak boleh ada route tanpa use case.
8. Tidak boleh ada menu tanpa use case.
9. Tidak boleh ada halaman utama tanpa use case.
10. Tidak boleh ada fitur besar yang tidak tercatat dalam PRD, SRS, dan Use Case.

## 22. Kesimpulan
Dokumen Use Case ini menetapkan perilaku sistem PERPUSQU dari sudut pandang aktor dan tujuan bisnis, serta menjadi fondasi wajib bagi desain teknis lanjutan. Dokumen ini telah disusun konsisten dengan Executive Summary, Stack Teknologi, Arsitektur Modular, PRD, dan SRS yang sudah disepakati sebelumnya. Seluruh dokumen turunan dan proses full stack coding AI Agent wajib mematuhi dokumen ini agar implementasi PERPUSQU tetap utuh, runtut, dan bebas dari missing flow.

END OF 06_USE_CASE.md