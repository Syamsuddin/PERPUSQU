# 05_SRS.md

## 1. Nama Dokumen
Software Requirements Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Sistem
Aplikasi web Sistem Informasi Perpustakaan Hibrid Kampus

### 2.3 Status Dokumen
Resmi, acuan teknis wajib pengembangan sistem

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan kebutuhan perangkat lunak secara rinci untuk PERPUSQU agar seluruh proses analisis, desain, coding, pengujian, deployment, dan operasional berjalan konsisten. Dokumen ini ditulis sebagai turunan langsung dari blueprint sebelumnya dan menjadi acuan teknis wajib bagi AI Agent, developer, tester, administrator sistem, dan pemangku kepentingan kampus.

## 3. Dokumen Acuan Wajib
Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep sistem tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Stack tetap Laravel, PHP, MySQL, Blade, Livewire, Bootstrap, Vite, Redis, Meilisearch, object storage, PDF.js, dan Tesseract OCR.
5. Modul inti tetap mengikuti arsitektur modular yang telah ditetapkan.
6. Fase 1 tetap fokus pada modul inti yang telah disepakati dalam PRD.

## 4. Tujuan Sistem
PERPUSQU dibangun untuk:
1. Mengintegrasikan koleksi fisik dan koleksi digital dalam satu sistem.
2. Menyediakan katalog daring terpadu.
3. Mendukung operasional perpustakaan kampus secara digital.
4. Menyediakan repositori digital internal kampus.
5. Menyediakan OPAC publik untuk pencarian koleksi.
6. Menyediakan laporan operasional dan statistik dasar.
7. Menyediakan fondasi integrasi dengan sistem kampus di tahap berikutnya.

## 5. Ruang Lingkup Sistem
Ruang lingkup fase 1 meliputi:

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

Ruang lingkup lanjutan yang belum wajib aktif pada fase 1:
1. Acquisition penuh
2. Integrasi SIAKAD real time
3. SSO kampus
4. Notifikasi WhatsApp
5. RFID
6. API eksternal publik
7. Mobile app native

## 6. Definisi Singkat Sistem
PERPUSQU adalah sistem perpustakaan hibrid berbasis web yang mengelola bibliographic record, eksemplar fisik, file digital, anggota perpustakaan, transaksi sirkulasi, OPAC, dan pelaporan dalam satu platform terpadu.

## 7. Aktor Sistem

### 7.1 Aktor Utama Internal
1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan

### 7.2 Aktor Pengguna Layanan
1. Mahasiswa
2. Dosen
3. Tenaga Kependidikan
4. Alumni bila diaktifkan
5. Tamu OPAC bila diaktifkan

### 7.3 Aktor Sistem Eksternal
1. SMTP server
2. Search engine Meilisearch
3. Redis
4. Object storage
5. OCR engine
6. Sumber import anggota pada fase lanjutan

## 8. Asumsi Dasar
1. Kampus memiliki kebijakan operasional perpustakaan dasar.
2. Kampus memiliki koleksi fisik yang akan dimigrasikan ke sistem.
3. Kampus memiliki atau akan memiliki koleksi digital.
4. Pengguna menggunakan browser modern.
5. Infrastruktur server dasar tersedia.
6. Tim pengelola perpustakaan siap menggunakan sistem berbasis web.

## 9. Batasan Sistem
1. Tidak menggunakan microservices.
2. Tidak membangun aplikasi mobile native pada fase 1.
3. Tidak membangun fitur pembayaran online pada fase 1.
4. Tidak membangun integrasi nasional yang kompleks pada fase 1.
5. Tidak membangun multi tenant multi kampus pada fase 1.

## 10. Gambaran Umum Arsitektur Sistem
Arsitektur sistem mengikuti monolith modular dengan komponen berikut:
1. Laravel sebagai aplikasi utama
2. MySQL sebagai database utama
3. Redis untuk cache, session, queue
4. Meilisearch untuk pencarian
5. Object storage untuk file digital
6. Queue worker untuk proses asinkron
7. Tesseract OCR untuk ekstraksi teks scan
8. PDF.js untuk preview PDF
9. Nginx dan PHP-FPM untuk web serving

## 11. Klasifikasi Kebutuhan
Kebutuhan pada dokumen ini dibagi menjadi:
1. Kebutuhan fungsional
2. Kebutuhan nonfungsional
3. Kebutuhan data
4. Kebutuhan antarmuka
5. Kebutuhan keamanan
6. Kebutuhan operasional
7. Kebutuhan integrasi
8. Kebutuhan pelaporan
9. Aturan bisnis

## 12. Kebutuhan Fungsional Umum

### FR-GEN-001
Sistem harus menyediakan halaman login untuk pengguna internal.

### FR-GEN-002
Sistem harus membedakan akses pengguna berdasarkan role dan permission.

### FR-GEN-003
Sistem harus menyediakan dashboard sesuai jenis pengguna.

### FR-GEN-004
Sistem harus mendukung CRUD data sesuai hak akses pada tiap modul.

### FR-GEN-005
Sistem harus mencatat aktivitas penting ke audit log.

### FR-GEN-006
Sistem harus menggunakan pagination pada daftar data besar.

### FR-GEN-007
Sistem harus mendukung pencarian data pada halaman daftar yang relevan.

### FR-GEN-008
Sistem harus mendukung filter pada halaman daftar yang relevan.

### FR-GEN-009
Sistem harus menampilkan notifikasi sukses dan gagal pada setiap aksi penting.

### FR-GEN-010
Sistem harus memisahkan area admin dan area OPAC publik.

## 13. Kebutuhan Fungsional Modul Core

### FR-CORE-001
Sistem harus menyediakan data profil institusi.

### FR-CORE-002
Sistem harus menyediakan konfigurasi nama perpustakaan, alamat, kontak, dan identitas kampus.

### FR-CORE-003
Sistem harus menyediakan parameter operasional seperti lama pinjam default, denda per hari, dan batas pinjam dasar.

### FR-CORE-004
Sistem harus menyediakan pengaturan umum sistem yang dapat diubah oleh Super Admin atau Admin Perpustakaan sesuai hak akses.

### FR-CORE-005
Sistem harus menyediakan dashboard ringkas untuk admin.

### FR-CORE-006
Sistem harus menyimpan perubahan konfigurasi penting ke audit log.

### FR-CORE-007
Sistem harus menyediakan data referensi global yang dipakai lintas modul bila dibutuhkan.

## 14. Kebutuhan Fungsional Modul Identity and Access

### FR-IDA-001
Sistem harus mendukung login dengan email atau username yang ditetapkan sistem.

### FR-IDA-002
Sistem harus mendukung logout.

### FR-IDA-003
Sistem harus mendukung manajemen pengguna internal.

### FR-IDA-004
Sistem harus mendukung pembuatan role.

### FR-IDA-005
Sistem harus mendukung pemberian permission ke role.

### FR-IDA-006
Sistem harus mendukung pengaitan user ke role.

### FR-IDA-007
Sistem harus mendukung aktivasi dan nonaktifasi user.

### FR-IDA-008
Sistem harus mendukung reset password oleh admin sesuai kebijakan.

### FR-IDA-009
Sistem harus mendukung perubahan password oleh pengguna yang berhak.

### FR-IDA-010
Sistem harus menolak akses ke route yang tidak sesuai hak akses.

### FR-IDA-011
Sistem harus mencatat login, logout, dan perubahan hak akses ke audit log.

### FR-IDA-012
Sistem harus mendukung halaman profil pengguna internal.

## 15. Kebutuhan Fungsional Modul Master Data

### FR-MAS-001
Sistem harus mendukung CRUD data pengarang.

### FR-MAS-002
Sistem harus mendukung CRUD data penerbit.

### FR-MAS-003
Sistem harus mendukung CRUD data bahasa.

### FR-MAS-004
Sistem harus mendukung CRUD data klasifikasi.

### FR-MAS-005
Sistem harus mendukung CRUD data subjek.

### FR-MAS-006
Sistem harus mendukung CRUD data jenis koleksi.

### FR-MAS-007
Sistem harus mendukung CRUD data lokasi rak.

### FR-MAS-008
Sistem harus mendukung CRUD data fakultas.

### FR-MAS-009
Sistem harus mendukung CRUD data program studi.

### FR-MAS-010
Sistem harus mendukung CRUD data kondisi item.

### FR-MAS-011
Sistem harus mendukung status aktif dan nonaktif untuk master data tertentu.

### FR-MAS-012
Sistem harus menyediakan pencarian dan filter pada master data.

### FR-MAS-013
Sistem harus mencegah penghapusan master data yang sedang dipakai oleh data operasional, atau menggantinya dengan soft delete atau status nonaktif sesuai desain teknis.

## 16. Kebutuhan Fungsional Modul Catalog

### FR-CAT-001
Sistem harus mendukung pembuatan bibliographic record.

### FR-CAT-002
Sistem harus mendukung perubahan bibliographic record.

### FR-CAT-003
Sistem harus mendukung penayangan detail bibliographic record.

### FR-CAT-004
Sistem harus mendukung pencarian bibliographic record pada area admin.

### FR-CAT-005
Sistem harus mendukung filter bibliographic record berdasarkan jenis koleksi, bahasa, subjek, penerbit, tahun terbit, dan status publikasi bila relevan.

### FR-CAT-006
Sistem harus menyimpan metadata minimal berupa judul, pengarang, penerbit, tahun terbit, bahasa, subjek, klasifikasi, dan jenis koleksi.

### FR-CAT-007
Sistem harus mendukung metadata tambahan berupa ISBN, edisi, kata kunci, sinopsis, dan cover.

### FR-CAT-008
Sistem harus memungkinkan satu bibliographic record memiliki lebih dari satu pengarang.

### FR-CAT-009
Sistem harus memungkinkan satu bibliographic record memiliki lebih dari satu subjek.

### FR-CAT-010
Sistem harus memungkinkan satu bibliographic record memiliki lebih dari satu item fisik.

### FR-CAT-011
Sistem harus memungkinkan satu bibliographic record memiliki lebih dari satu aset digital bila diperlukan.

### FR-CAT-012
Sistem harus mendukung status record aktif dan nonaktif atau terpublikasi dan tidak terpublikasi sesuai desain data.

### FR-CAT-013
Sistem harus mencatat perubahan bibliographic record ke audit log.

### FR-CAT-014
Sistem harus menandai bibliographic record yang belum memiliki item fisik dan belum memiliki aset digital bila diperlukan untuk kontrol data.

### FR-CAT-015
Sistem harus menyediakan tampilan relasi antara bibliographic record, item fisik, dan aset digital.

## 17. Kebutuhan Fungsional Modul Collection

### FR-COL-001
Sistem harus mendukung pendaftaran item fisik per eksemplar.

### FR-COL-002
Sistem harus memberikan identitas unik pada setiap item fisik.

### FR-COL-003
Sistem harus menyimpan data barcode atau kode inventaris item.

### FR-COL-004
Sistem harus menyimpan lokasi rak item.

### FR-COL-005
Sistem harus menyimpan kondisi item.

### FR-COL-006
Sistem harus menyimpan status item.

### FR-COL-007
Sistem harus mendukung perubahan status item, minimal tersedia, dipinjam, rusak, hilang, perbaikan, dan nonaktif sesuai aturan bisnis.

### FR-COL-008
Sistem harus menampilkan histori perubahan item bila diaktifkan pada desain data.

### FR-COL-009
Sistem harus mendukung pencarian item berdasarkan barcode, judul, atau lokasi rak.

### FR-COL-010
Sistem harus mengaitkan setiap item fisik ke satu bibliographic record yang sah.

### FR-COL-011
Sistem harus mencegah transaksi pinjam atas item yang tidak tersedia.

### FR-COL-012
Sistem harus mencatat perubahan item penting ke audit log.

## 18. Kebutuhan Fungsional Modul Member

### FR-MEM-001
Sistem harus mendukung CRUD anggota perpustakaan.

### FR-MEM-002
Sistem harus membedakan kategori anggota, minimal mahasiswa, dosen, dan tenaga kependidikan.

### FR-MEM-003
Sistem harus menyimpan nomor anggota yang unik.

### FR-MEM-004
Sistem harus menyimpan status aktif atau nonaktif anggota.

### FR-MEM-005
Sistem harus menyimpan data dasar anggota, minimal nama, identitas internal, kategori, kontak dasar, dan afiliasi fakultas atau program studi bila relevan.

### FR-MEM-006
Sistem harus mendukung pencarian anggota.

### FR-MEM-007
Sistem harus menampilkan histori transaksi anggota.

### FR-MEM-008
Sistem harus mendukung blokir anggota bila ada pelanggaran aturan.

### FR-MEM-009
Sistem harus mendukung import anggota pada fase yang diaktifkan sesuai spesifikasi lanjutan.

### FR-MEM-010
Sistem harus mencatat perubahan status anggota ke audit log.

## 19. Kebutuhan Fungsional Modul Circulation

### FR-CIR-001
Sistem harus mendukung transaksi peminjaman item fisik.

### FR-CIR-002
Sistem harus mendukung transaksi pengembalian item fisik.

### FR-CIR-003
Sistem harus mendukung perpanjangan masa pinjam bila aturan mengizinkan.

### FR-CIR-004
Sistem harus memvalidasi status anggota sebelum transaksi pinjam.

### FR-CIR-005
Sistem harus memvalidasi status item sebelum transaksi pinjam.

### FR-CIR-006
Sistem harus menghitung tanggal jatuh tempo berdasarkan aturan sistem.

### FR-CIR-007
Sistem harus menghitung keterlambatan berdasarkan tanggal jatuh tempo dan tanggal kembali.

### FR-CIR-008
Sistem harus menghitung denda dasar berdasarkan keterlambatan sesuai konfigurasi.

### FR-CIR-009
Sistem harus memperbarui status item menjadi dipinjam saat transaksi pinjam berhasil.

### FR-CIR-010
Sistem harus memperbarui status item menjadi tersedia atau status lain yang sesuai saat pengembalian berhasil.

### FR-CIR-011
Sistem harus menyimpan histori pinjam dan kembali.

### FR-CIR-012
Sistem harus mendukung pencarian transaksi pinjam aktif.

### FR-CIR-013
Sistem harus menampilkan pinjaman aktif per anggota.

### FR-CIR-014
Sistem harus mendukung pencatatan kondisi item saat kembali bila diperlukan.

### FR-CIR-015
Sistem harus mencegah transaksi pinjam bila anggota diblokir.

### FR-CIR-016
Sistem harus mencegah transaksi pinjam bila item sudah dipinjam atau tidak tersedia.

### FR-CIR-017
Sistem harus mendukung transaksi peminjaman dengan scan barcode item.

### FR-CIR-018
Sistem harus mendukung tampilan ringkas antrean transaksi pinjam dan kembali pada halaman petugas.

### FR-CIR-019
Sistem harus mencatat transaksi sirkulasi ke audit log.

### FR-CIR-020
Sistem harus menyimpan informasi petugas yang memproses transaksi.

## 20. Kebutuhan Fungsional Modul Digital Repository

### FR-DIG-001
Sistem harus mendukung unggah file digital.

### FR-DIG-002
Sistem harus memvalidasi tipe file yang diizinkan.

### FR-DIG-003
Sistem harus memvalidasi ukuran file sesuai kebijakan sistem.

### FR-DIG-004
Sistem harus menyimpan metadata file digital.

### FR-DIG-005
Sistem harus mengaitkan file digital ke bibliographic record yang sah.

### FR-DIG-006
Sistem harus menyimpan file digital di storage yang ditetapkan, bukan di database.

### FR-DIG-007
Sistem harus mendukung preview PDF di browser.

### FR-DIG-008
Sistem harus mendukung pengaturan hak akses file digital.

### FR-DIG-009
Sistem harus mendukung status publikasi aset digital.

### FR-DIG-010
Sistem harus mendukung status embargo dasar bila fitur diaktifkan.

### FR-DIG-011
Sistem harus mengirim job OCR untuk file scan yang memenuhi syarat.

### FR-DIG-012
Sistem harus menyimpan hasil OCR yang berhasil diproses.

### FR-DIG-013
Sistem harus mendukung indexing isi dokumen ke mesin pencarian bila fitur diaktifkan.

### FR-DIG-014
Sistem harus mendukung penghapusan logis atau penonaktifan aset digital sesuai aturan kebijakan data.

### FR-DIG-015
Sistem harus mencatat unggah, perubahan, dan penghapusan aset digital ke audit log.

### FR-DIG-016
Sistem harus mencegah akses file privat oleh pengguna yang tidak berhak.

### FR-DIG-017
Sistem harus mendukung penyimpanan versi file atau metadata versi bila diputuskan dalam desain rinci.

## 21. Kebutuhan Fungsional Modul OPAC

### FR-OPA-001
Sistem harus menyediakan halaman OPAC publik.

### FR-OPA-002
Sistem harus menyediakan pencarian umum pada OPAC.

### FR-OPA-003
Sistem harus mendukung filter hasil pencarian pada OPAC.

### FR-OPA-004
Sistem harus menampilkan hasil pencarian berupa daftar koleksi yang relevan.

### FR-OPA-005
Sistem harus menampilkan detail bibliographic record pada halaman detail koleksi.

### FR-OPA-006
Sistem harus menampilkan ketersediaan item fisik pada halaman detail.

### FR-OPA-007
Sistem harus menampilkan informasi lokasi rak pada item fisik yang tersedia.

### FR-OPA-008
Sistem harus menampilkan informasi ketersediaan koleksi digital.

### FR-OPA-009
Sistem harus menampilkan tombol preview atau akses file digital sesuai hak akses.

### FR-OPA-010
Sistem harus mendukung pagination hasil pencarian.

### FR-OPA-011
Sistem harus responsif pada perangkat desktop dan mobile.

### FR-OPA-012
Sistem harus menampilkan pesan yang jelas bila hasil pencarian tidak ditemukan.

### FR-OPA-013
Sistem harus menggunakan search index sebagai dasar hasil pencarian dan MySQL sebagai sumber data final.

### FR-OPA-014
Sistem harus mendukung pencarian berdasarkan judul, pengarang, subjek, kata kunci, ISBN, dan teks dokumen bila tersedia.

## 22. Kebutuhan Fungsional Modul Reporting

### FR-REP-001
Sistem harus menyediakan dashboard statistik dasar.

### FR-REP-002
Sistem harus menyediakan laporan jumlah koleksi.

### FR-REP-003
Sistem harus menyediakan laporan jumlah anggota.

### FR-REP-004
Sistem harus menyediakan laporan transaksi peminjaman.

### FR-REP-005
Sistem harus menyediakan laporan keterlambatan.

### FR-REP-006
Sistem harus menyediakan laporan denda dasar.

### FR-REP-007
Sistem harus menyediakan laporan koleksi populer.

### FR-REP-008
Sistem harus menyediakan laporan akses koleksi digital.

### FR-REP-009
Sistem harus mendukung filter periode pada laporan.

### FR-REP-010
Sistem harus mendukung ekspor laporan pada tahap fitur yang diaktifkan.

### FR-REP-011
Sistem harus membatasi akses laporan berdasarkan role.

## 23. Kebutuhan Fungsional Modul Audit and Monitoring

### FR-AUD-001
Sistem harus mencatat login dan logout.

### FR-AUD-002
Sistem harus mencatat perubahan user, role, dan permission.

### FR-AUD-003
Sistem harus mencatat tambah, ubah, dan hapus bibliographic record.

### FR-AUD-004
Sistem harus mencatat tambah, ubah, dan hapus item fisik.

### FR-AUD-005
Sistem harus mencatat transaksi pinjam dan kembali.

### FR-AUD-006
Sistem harus mencatat unggah, ubah, dan hapus aset digital.

### FR-AUD-007
Sistem harus mencatat perubahan konfigurasi sistem.

### FR-AUD-008
Sistem harus menyediakan tampilan log aktivitas untuk admin yang berwenang.

### FR-AUD-009
Sistem harus menyediakan data monitoring job queue dasar untuk admin teknis sesuai alat yang dipakai.

## 24. Aturan Bisnis Umum

### BR-001
Setiap item fisik wajib terhubung ke satu bibliographic record.

### BR-002
Setiap aset digital wajib terhubung ke satu bibliographic record.

### BR-003
Status item fisik harus konsisten dengan transaksi sirkulasi.

### BR-004
Anggota nonaktif atau diblokir tidak boleh melakukan peminjaman baru.

### BR-005
Item yang berstatus dipinjam, hilang, rusak berat, atau nonaktif tidak boleh dipinjam.

### BR-006
Hak akses file digital harus ditentukan oleh status publikasi, embargo, dan role pengguna bila relevan.

### BR-007
Perubahan data penting wajib masuk audit log.

### BR-008
Proses OCR dan indexing dokumen harus berjalan sebagai proses asinkron bila file memenuhi syarat.

### BR-009
Search index tidak boleh menjadi sumber data transaksi final.

### BR-010
Data master yang dipakai transaksi tidak boleh dihapus secara sembarangan.

## 25. Aturan Bisnis Sirkulasi

### BR-CIR-001
Jumlah maksimum pinjaman aktif per anggota mengikuti konfigurasi sistem.

### BR-CIR-002
Lama pinjam default mengikuti konfigurasi sistem dan bisa dibedakan menurut kategori anggota bila diaktifkan pada desain berikutnya.

### BR-CIR-003
Denda keterlambatan dihitung berdasarkan aturan sistem.

### BR-CIR-004
Transaksi pengembalian wajib menutup pinjaman aktif terkait.

### BR-CIR-005
Satu item fisik hanya boleh memiliki satu transaksi pinjam aktif pada satu waktu.

### BR-CIR-006
Perpanjangan hanya boleh dilakukan pada pinjaman yang masih aktif dan memenuhi syarat.

## 26. Aturan Bisnis Repositori Digital

### BR-DIG-001
File digital privat hanya boleh diakses pengguna yang berhak.

### BR-DIG-002
File yang belum terpublikasi tidak boleh muncul di OPAC publik.

### BR-DIG-003
File yang berada pada masa embargo tidak boleh diunduh atau dipreview oleh pihak yang tidak berhak.

### BR-DIG-004
OCR hanya dijalankan pada file yang mendukung dan lolos validasi proses.

### BR-DIG-005
Kegagalan OCR tidak membatalkan keabsahan metadata aset digital.

## 27. Kebutuhan Antarmuka Pengguna

### UI-001
Sistem harus menggunakan layout dashboard admin dengan sidebar, header, content area, dan footer sesuai standar UI yang akan ditetapkan pada dokumen turunan.

### UI-002
Sistem harus menggunakan komponen UI yang konsisten pada semua modul.

### UI-003
Form harus menampilkan label, validasi, dan pesan error yang jelas.

### UI-004
Daftar data harus menyediakan search, filter, pagination, dan informasi jumlah data bila relevan.

### UI-005
Halaman transaksi sirkulasi harus dioptimalkan untuk input cepat.

### UI-006
Halaman OPAC harus menonjolkan kotak pencarian dan hasil yang mudah dibaca.

### UI-007
Sistem harus mendukung tampilan responsif minimal untuk admin dasar dan OPAC.

## 28. Kebutuhan Antarmuka Sistem

### INT-001
Sistem harus terhubung ke MySQL sebagai database utama.

### INT-002
Sistem harus terhubung ke Redis untuk cache, session, dan queue.

### INT-003
Sistem harus terhubung ke Meilisearch untuk pencarian.

### INT-004
Sistem harus terhubung ke object storage atau storage yang ditetapkan untuk file digital.

### INT-005
Sistem harus dapat memanggil OCR engine untuk file yang relevan.

### INT-006
Sistem harus dapat menggunakan SMTP atau mail driver yang dikonfigurasi untuk pengiriman notifikasi email bila fitur diaktifkan.

## 29. Kebutuhan Antarmuka Data

### DAT-001
Data bibliographic record harus tersimpan dalam struktur relasional.

### DAT-002
Data item fisik harus tersimpan dalam struktur relasional.

### DAT-003
Data anggota harus tersimpan dalam struktur relasional.

### DAT-004
Data transaksi sirkulasi harus tersimpan dalam struktur relasional.

### DAT-005
Metadata aset digital harus tersimpan dalam struktur relasional.

### DAT-006
Teks hasil OCR dapat disimpan dalam tabel khusus atau struktur yang sesuai desain data.

### DAT-007
Data referensi harus dipisah dari data transaksi.

### DAT-008
Audit log harus tersimpan dalam struktur yang mendukung penelusuran waktu, aktor, aksi, dan objek.

## 30. Kebutuhan Nonfungsional Umum

### NFR-001
Sistem harus dirancang untuk tim pengembang kecil sampai menengah.

### NFR-002
Sistem harus menggunakan arsitektur monolith modular.

### NFR-003
Sistem harus mudah dipelihara dan dikembangkan bertahap.

### NFR-004
Sistem harus mendukung deployment pada server kampus atau VPS.

### NFR-005
Sistem harus didokumentasikan dengan baik melalui blueprint.

## 31. Kebutuhan Nonfungsional Performa

### NFR-PERF-001
Pencarian OPAC harus responsif pada kondisi data wajar operasional kampus.

### NFR-PERF-002
Daftar data besar harus menggunakan pagination.

### NFR-PERF-003
Query yang sering dipakai harus dapat dioptimalkan melalui indexing database dan cache.

### NFR-PERF-004
Proses berat seperti OCR, indexing, dan import besar harus dijalankan melalui queue.

### NFR-PERF-005
Sistem harus menghindari N+1 query pada halaman daftar dan detail yang kompleks.

## 32. Kebutuhan Nonfungsional Keamanan

### NFR-SEC-001
Semua halaman admin harus dilindungi autentikasi.

### NFR-SEC-002
Setiap aksi penting harus melalui authorization policy atau mekanisme yang setara.

### NFR-SEC-003
Password harus disimpan dalam bentuk hash yang aman sesuai framework.

### NFR-SEC-004
Upload file harus divalidasi tipe dan ukurannya.

### NFR-SEC-005
File digital privat tidak boleh diakses melalui URL publik langsung tanpa kontrol akses.

### NFR-SEC-006
Sistem harus mencatat aktivitas penting ke audit log.

### NFR-SEC-007
APP_DEBUG tidak boleh aktif di production.

### NFR-SEC-008
Redis, Meilisearch, dan database tidak boleh dibuka bebas ke publik.

## 33. Kebutuhan Nonfungsional Keandalan

### NFR-REL-001
Transaksi sirkulasi harus bersifat konsisten.

### NFR-REL-002
Kegagalan proses OCR atau indexing tidak boleh merusak data utama.

### NFR-REL-003
Sistem harus mendukung retry job untuk proses asinkron yang gagal.

### NFR-REL-004
Sistem harus menyediakan log untuk investigasi masalah.

### NFR-REL-005
Sistem harus mendukung backup database dan file digital.

## 34. Kebutuhan Nonfungsional Ketersediaan

### NFR-AVA-001
Sistem harus layak digunakan selama jam operasional perpustakaan.

### NFR-AVA-002
Queue worker harus dikelola agar proses latar belakang tetap berjalan.

### NFR-AVA-003
Scheduler harus aktif untuk job terjadwal.

## 35. Kebutuhan Nonfungsional Skalabilitas

### NFR-SCA-001
Sistem harus mendukung pertumbuhan jumlah bibliographic record.

### NFR-SCA-002
Sistem harus mendukung pertumbuhan jumlah item fisik.

### NFR-SCA-003
Sistem harus mendukung pertumbuhan file digital dengan penyimpanan terpisah dari database.

### NFR-SCA-004
Komponen pencarian dan storage harus dapat dipisah ke server lain pada tahap berikutnya tanpa mengubah logika bisnis inti secara besar.

## 36. Kebutuhan Nonfungsional Usability

### NFR-USE-001
Halaman admin harus mudah dipelajari operator perpustakaan.

### NFR-USE-002
Form harus memiliki pesan validasi yang jelas.

### NFR-USE-003
OPAC harus mudah digunakan oleh mahasiswa dan dosen tanpa pelatihan khusus.

### NFR-USE-004
Transaksi sirkulasi harus membutuhkan langkah minimal yang wajar.

## 37. Kebutuhan Nonfungsional Maintainability

### NFR-MAI-001
Kode harus dipisahkan berdasarkan modul.

### NFR-MAI-002
Logika bisnis inti tidak boleh menumpuk di controller atau view.

### NFR-MAI-003
Nama route, controller, service, model, dan view harus konsisten.

### NFR-MAI-004
Blueprint harus menjadi acuan tetap selama pengembangan.

### NFR-MAI-005
Perubahan besar fitur harus melalui revisi dokumen terkait.

## 38. Kebutuhan Nonfungsional Portability

### NFR-POR-001
Sistem harus dapat berjalan pada lingkungan Linux server yang didukung.

### NFR-POR-002
Lingkungan pengembangan lokal harus dapat disiapkan secara konsisten melalui mekanisme yang disepakati, misalnya Laravel Sail atau Docker Compose lokal.

## 39. Kebutuhan Nonfungsional Logging dan Monitoring

### NFR-LOG-001
Sistem harus menyimpan log aplikasi.

### NFR-LOG-002
Sistem harus menyimpan log aktivitas penting.

### NFR-LOG-003
Sistem harus menyediakan alat monitoring job queue yang sesuai stack.

### NFR-LOG-004
Sistem harus menyediakan dasar observasi performa aplikasi untuk admin teknis.

## 40. Kebutuhan Deployment dan Operasional

### OPS-001
Sistem harus mendukung konfigurasi environment untuk local, staging, dan production.

### OPS-002
Sistem harus menggunakan HTTPS pada production.

### OPS-003
Sistem harus mendukung cron scheduler.

### OPS-004
Sistem harus mendukung queue worker berbasis Supervisor atau mekanisme setara yang disetujui.

### OPS-005
Sistem harus mendukung backup terjadwal.

### OPS-006
Sistem harus mendukung build asset frontend sebelum rilis production.

## 41. Kebutuhan Pelaporan

### REP-001
Laporan harus dapat difilter berdasarkan rentang tanggal bila relevan.

### REP-002
Dashboard harus menampilkan ringkasan koleksi, anggota, dan transaksi.

### REP-003
Laporan keterlambatan harus menampilkan anggota, item, tanggal jatuh tempo, dan jumlah hari terlambat.

### REP-004
Laporan koleksi populer harus menampilkan bibliographic record dengan frekuensi pinjam tertinggi dalam periode tertentu.

### REP-005
Laporan akses digital harus menampilkan ringkasan penggunaan aset digital bila pencatatan akses diaktifkan.

## 42. Kebutuhan Migrasi Data

### MIG-001
Sistem harus mendukung import data awal secara bertahap.

### MIG-002
Migrasi data koleksi harus dapat dilakukan tanpa merusak data transaksi yang telah berjalan.

### MIG-003
Data master harus dibersihkan dan distandarkan sebelum atau saat migrasi.

### MIG-004
Sistem harus memungkinkan pengisian data secara manual bila sumber data lama belum rapi.

## 43. Kebutuhan Search dan Indexing

### SRH-001
Sistem harus mengindeks bibliographic record ke Meilisearch.

### SRH-002
Sistem harus dapat mengindeks field utama seperti judul, pengarang, subjek, kata kunci, ISBN, dan sinopsis bila tersedia.

### SRH-003
Sistem harus dapat mengindeks isi dokumen dari OCR atau teks file digital bila diaktifkan.

### SRH-004
Perubahan bibliographic record harus memicu pembaruan index.

### SRH-005
Perubahan status publikasi aset digital yang mempengaruhi hasil OPAC harus memicu pembaruan index bila relevan.

## 44. Kebutuhan Penyimpanan File

### FIL-001
Sistem harus menyimpan file digital pada storage yang ditetapkan.

### FIL-002
Sistem harus menyimpan cover koleksi secara terpisah dari data utama.

### FIL-003
Sistem harus mendukung struktur penyimpanan file yang rapi dan dapat dilacak.

### FIL-004
Sistem harus mencegah file yang tidak valid masuk ke storage final.

### FIL-005
Sistem harus mendukung penghapusan atau penonaktifan file secara terkendali.

## 45. Kebutuhan Audit dan Kepatuhan Operasional

### AUD-001
Setiap perubahan kritis harus dapat ditelusuri ke pengguna dan waktu kejadian.

### AUD-002
Sistem harus memungkinkan admin yang berwenang melihat riwayat audit.

### AUD-003
Sistem harus mencatat aktivitas penting tanpa mengganggu performa operasional secara berlebihan.

## 46. Kebutuhan Pengujian
Sistem harus dapat diuji pada level berikut:
1. Unit test untuk service penting
2. Feature test untuk route dan alur utama
3. Authorization test untuk role dan permission
4. Integration test untuk alur lintas modul
5. UAT untuk operator perpustakaan dan perwakilan pengguna

Kebutuhan minimum pengujian fase 1:
1. Login dan hak akses
2. CRUD bibliographic record
3. CRUD item fisik
4. CRUD anggota
5. Peminjaman
6. Pengembalian
7. Upload aset digital
8. Preview PDF
9. Pencarian OPAC
10. Audit log dasar

## 47. Kriteria Penerimaan Sistem Tingkat Tinggi

### AC-001
Admin dapat login dan mengakses dashboard sesuai role.

### AC-002
Pustakawan dapat membuat dan mengubah bibliographic record.

### AC-003
Pustakawan dapat mendaftarkan item fisik.

### AC-004
Petugas sirkulasi dapat memproses peminjaman dan pengembalian.

### AC-005
Status item berubah sesuai transaksi.

### AC-006
Admin dapat mengelola anggota.

### AC-007
Operator dapat mengunggah file digital dan menautkannya ke bibliographic record.

### AC-008
Pengguna dapat mencari koleksi melalui OPAC.

### AC-009
Pengguna dapat melihat detail koleksi, termasuk status item fisik dan ketersediaan digital.

### AC-010
Sistem dapat menghasilkan laporan dasar.

### AC-011
Sistem mencatat aktivitas penting ke audit log.

## 48. Pemetaan Kebutuhan ke Modul

1. Core menangani konfigurasi global dan dashboard ringkas.
2. Identity and Access menangani autentikasi dan otorisasi.
3. Master Data menangani data referensi.
4. Catalog menangani bibliographic record.
5. Collection menangani item fisik.
6. Member menangani anggota.
7. Circulation menangani pinjam dan kembali.
8. Digital Repository menangani aset digital.
9. OPAC menangani pencarian publik.
10. Reporting menangani statistik dan laporan.
11. Audit and Monitoring menangani log aktivitas.

## 49. Pemetaan Kebutuhan ke Dokumen Turunan
Dokumen ini menjadi dasar wajib bagi:
1. 06_USE_CASE.md
2. 07_ROLE_PERMISSION_MATRIX.md
3. 08_MENU_MAP.md
4. 09_ROUTE_MAP.md
5. 10_VIEW_MAP.md
6. 11_CONTROLLER_MAP.md
7. 12_SERVICE_LAYER.md
8. 13_MODEL_MAP.md
9. 14_SCHEMA.sql
10. 16_VALIDATION_RULES.md
11. 17_WORKFLOW_STATE_MACHINE.md
12. 18_UI_UX_STANDARD.md
13. 21_SEARCH_INDEXING_SPEC.md
14. 22_STORAGE_FILE_POLICY.md
15. 23_OCR_AND_DIGITAL_PROCESSING.md
16. 25_REPORTING_SPEC.md
17. 28_SECURITY_POLICY.md
18. 29_AUDIT_LOG_SPEC.md
19. 31_TEST_PLAN.md
20. 32_TEST_SCENARIO.md

Aturan:
1. Dokumen turunan tidak boleh bertentangan dengan kebutuhan pada SRS ini.
2. Bila ada fitur baru besar, PRD dan SRS harus direvisi resmi.
3. AI Agent wajib menggunakan SRS ini sebagai referensi requirement utama saat coding.

## 50. Kesimpulan
SRS ini menetapkan kebutuhan perangkat lunak PERPUSQU secara rinci dan konsisten dengan Executive Summary, Stack Teknologi, Arsitektur Modular, dan PRD yang telah disepakati sebelumnya. Dokumen ini menjadi kontrak teknis utama antara kebutuhan produk dan implementasi sistem. Seluruh pengembangan full stack coding PERPUSQU wajib merujuk pada dokumen ini agar tidak terjadi missing feature, broken flow, inkonsistensi modul, atau penyimpangan dari konsensus rancang bangun yang telah ditetapkan.

END OF 05_SRS.md