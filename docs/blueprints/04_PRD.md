# 04_PRD.md

## 1. Nama Dokumen
Product Requirements Document Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Produk
PERPUSQU

### 2.2 Jenis Produk
Aplikasi web Sistem Informasi Perpustakaan Hibrid Kampus

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan produk

### 2.4 Tujuan Dokumen
Dokumen ini menjabarkan kebutuhan produk PERPUSQU dari sisi bisnis, layanan, pengguna, ruang lingkup fitur, prioritas pengembangan, sasaran implementasi, dan hasil akhir yang harus dicapai. Dokumen ini menjadi acuan utama bagi AI Agent, analis, developer, tester, dan pemangku kepentingan kampus agar seluruh proses full stack coding berjalan konsisten dan tidak menyimpang dari blueprint sebelumnya.

## 3. Dokumen Acuan Wajib
Dokumen ini wajib dibaca dan dipatuhi bersama dokumen berikut:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md

Aturan konsistensi wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep produk tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Stack tetap Laravel, PHP, MySQL, Blade, Livewire, Bootstrap, Vite, Redis, Meilisearch, object storage, PDF.js, dan Tesseract OCR.
5. Modul inti tetap mengikuti pembagian domain pada dokumen 03.
6. Ruang lingkup fase 1 tidak boleh keluar dari fondasi yang telah disepakati pada dokumen 01.

## 4. Ringkasan Produk
PERPUSQU adalah aplikasi web perpustakaan hibrid kampus yang mengintegrasikan pengelolaan koleksi fisik dan koleksi digital ke dalam satu sistem terpadu. Produk ini dirancang agar perpustakaan kampus mampu mengelola bibliographic record, eksemplar fisik, anggota, sirkulasi, file digital, pencarian OPAC, dan pelaporan operasional dalam satu platform.

PERPUSQU tidak diposisikan sebagai sekadar inventaris buku. PERPUSQU diposisikan sebagai platform layanan perpustakaan kampus modern yang:
1. Menyatukan koleksi cetak dan digital.
2. Memudahkan pencarian melalui OPAC publik.
3. Meningkatkan efisiensi kerja pustakawan dan petugas sirkulasi.
4. Menyediakan fondasi repositori digital internal kampus.
5. Menyediakan data operasional dan statistik bagi pimpinan kampus.

## 5. Latar Belakang Masalah
Banyak perpustakaan kampus masih menghadapi persoalan operasional berikut:

1. Data buku cetak belum seluruhnya masuk ke sistem katalog digital.
2. Koleksi digital tersebar di folder lokal, hard disk, cloud drive, atau media simpan lain tanpa tata kelola yang baik.
3. Pengguna kesulitan mengetahui apakah koleksi tersedia dalam bentuk fisik, digital, atau keduanya.
4. Proses peminjaman dan pengembalian masih lambat atau bergantung pada proses manual.
5. Riwayat pinjam, keterlambatan, dan denda tidak tercatat rapi.
6. Laporan statistik perpustakaan sulit dibuat secara cepat.
7. Integrasi data anggota dengan lingkungan kampus belum tertata.
8. Pustakawan membutuhkan satu sistem kerja yang lebih praktis dan konsisten.

PERPUSQU dirancang untuk menjawab masalah tersebut dengan pendekatan bertahap, realistis, dan sesuai kapasitas tim pengembang kampus yang tidak terlalu besar.

## 6. Visi Produk
Menjadi platform perpustakaan hibrid kampus yang terintegrasi, stabil, mudah digunakan, dan mampu mendukung transformasi layanan perpustakaan dari proses manual menjadi layanan digital terpadu.

## 7. Misi Produk
1. Membangun sistem perpustakaan kampus yang menggabungkan katalog koleksi fisik dan digital.
2. Menyediakan satu pintu pencarian koleksi bagi mahasiswa, dosen, dan pengguna kampus.
3. Menyederhanakan proses operasional pustakawan dan petugas sirkulasi.
4. Meningkatkan kualitas tata kelola metadata, transaksi, file digital, dan laporan.
5. Menyediakan fondasi teknis yang dapat dikembangkan bertahap tanpa merusak sistem inti.

## 8. Tujuan Produk
Tujuan utama PERPUSQU adalah:

1. Menghadirkan katalog daring terpadu untuk koleksi fisik dan digital.
2. Mendigitalisasi proses katalogisasi dan sirkulasi.
3. Menyediakan repositori digital kampus dengan hak akses yang terkelola.
4. Menyediakan OPAC publik yang cepat, akurat, dan mudah digunakan.
5. Menyediakan dashboard operasional dan laporan dasar perpustakaan.
6. Menyediakan pondasi integrasi dengan sistem kampus pada fase lanjutan.

## 9. Sasaran Implementasi
Produk ini ditujukan untuk lingkungan kampus dengan karakteristik berikut:

1. Memiliki perpustakaan fisik dengan koleksi cetak.
2. Memiliki kebutuhan mengelola koleksi digital seperti skripsi, tesis, jurnal internal, atau modul.
3. Memiliki tim pengelola perpustakaan yang ingin berpindah dari proses manual atau semi manual ke sistem digital.
4. Memiliki tim pengembang internal yang kecil sampai menengah.
5. Membutuhkan sistem yang stabil, mudah dipelihara, dan dapat berkembang bertahap.

## 10. Segmentasi Pengguna

### 10.1 Pengguna Internal
1. Super Admin Sistem
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Data Koleksi
6. Operator Repositori Digital

### 10.2 Pengguna Institusional
1. Pimpinan kampus
2. Kepala perpustakaan
3. Unit TI kampus

### 10.3 Pengguna Eksternal Sistem
1. Mahasiswa
2. Dosen
3. Tenaga kependidikan
4. Alumni bila diaktifkan
5. Tamu OPAC bila diaktifkan

## 11. Persona Pengguna Utama

### 11.1 Persona 1, Pustakawan
Karakter:
1. Fokus pada katalogisasi, pembaruan metadata, dan validitas koleksi.
2. Membutuhkan sistem yang rapi, cepat, dan tidak rumit.
3. Ingin pencarian dan input data konsisten.

Kebutuhan:
1. Entri bibliografi yang terstruktur.
2. Manajemen item fisik.
3. Upload dan kelola file digital.
4. Akses laporan koleksi.

### 11.2 Persona 2, Petugas Sirkulasi
Karakter:
1. Fokus pada transaksi pinjam kembali.
2. Berinteraksi langsung dengan anggota.
3. Membutuhkan halaman cepat dan sederhana.

Kebutuhan:
1. Scan barcode item.
2. Validasi anggota.
3. Proses pinjam dan kembali cepat.
4. Informasi denda dan keterlambatan jelas.

### 11.3 Persona 3, Mahasiswa
Karakter:
1. Ingin mencari koleksi dengan cepat.
2. Tidak ingin datang ke rak hanya untuk mengecek ketersediaan.
3. Ingin mengetahui apakah tersedia versi digital.

Kebutuhan:
1. OPAC mudah dipakai.
2. Filter pencarian.
3. Informasi ketersediaan koleksi.
4. Akses file digital sesuai izin.

### 11.4 Persona 4, Dosen
Karakter:
1. Mencari referensi cetak dan digital untuk pembelajaran dan penelitian.
2. Butuh akses cepat ke karya ilmiah dan referensi.

Kebutuhan:
1. Pencarian berbasis judul, pengarang, subjek, kata kunci.
2. Akses file digital.
3. Riwayat pinjam.
4. Informasi reservasi bila dikembangkan.

### 11.5 Persona 5, Pimpinan Perpustakaan
Karakter:
1. Membutuhkan data ringkas, bukan detail teknis harian.
2. Ingin melihat statistik layanan.

Kebutuhan:
1. Dashboard koleksi.
2. Statistik peminjaman.
3. Statistik keterlambatan.
4. Statistik akses digital.
5. Laporan periodik.

## 12. Pernyataan Masalah Produk
Produk ini dibangun untuk mengatasi pernyataan masalah berikut:

1. Perpustakaan kampus belum memiliki sistem terpadu untuk koleksi fisik dan digital.
2. Data bibliografi, data item, transaksi, dan file digital masih terpisah.
3. Pengguna belum memiliki pengalaman pencarian koleksi yang utuh.
4. Operasional perpustakaan belum efisien karena proses masih bergantung pada pencatatan manual atau aplikasi terpisah.
5. Pengambilan keputusan manajerial sulit dilakukan tanpa data statistik yang cepat dan konsisten.

## 13. Solusi Produk yang Ditawarkan
PERPUSQU menawarkan solusi produk berikut:

1. Satu aplikasi web untuk manajemen perpustakaan hibrid kampus.
2. Satu katalog induk untuk bibliographic record.
3. Satu pengelolaan item fisik untuk koleksi cetak.
4. Satu repositori digital untuk file koleksi elektronik.
5. Satu modul sirkulasi untuk peminjaman, pengembalian, dan denda.
6. Satu OPAC publik untuk pencarian.
7. Satu modul pelaporan untuk operasional dan pimpinan.
8. Satu fondasi integrasi untuk fase berikutnya.

## 14. Nilai Utama Produk
Nilai utama yang ditawarkan PERPUSQU adalah:

1. Integrasi
Semua data penting perpustakaan berada dalam satu sistem.

2. Efisiensi
Pustakawan dan petugas sirkulasi bekerja lebih cepat.

3. Kemudahan Akses
Pengguna bisa mencari koleksi fisik dan digital dari satu portal.

4. Keterlacakan
Transaksi, aktivitas, dan perubahan data penting tercatat.

5. Kesiapan Tumbuh
Sistem bisa berkembang tanpa mengganti fondasi utama.

## 15. Ruang Lingkup Produk Fase 1
Ruang lingkup fase 1 wajib mengikuti hasil konsensus sebelumnya dan difokuskan pada fitur inti yang langsung dipakai.

### 15.1 Modul Core
Cakupan:
1. Konfigurasi umum sistem
2. Profil institusi
3. Parameter operasional
4. Setting dasar perpustakaan
5. Dashboard ringkas admin

### 15.2 Modul Identity and Access
Cakupan:
1. Login
2. Logout
3. Manajemen user
4. Role
5. Permission
6. Profil pengguna
7. Reset password internal

### 15.3 Modul Master Data
Cakupan:
1. Pengarang
2. Penerbit
3. Bahasa
4. Klasifikasi
5. Subjek
6. Jenis koleksi
7. Lokasi rak
8. Fakultas
9. Program studi
10. Kondisi item

### 15.4 Modul Catalog
Cakupan:
1. Entri bibliographic record
2. Edit bibliographic record
3. Pencarian katalog internal
4. Data judul
5. Pengarang
6. Penerbit
7. ISBN
8. Tahun terbit
9. Edisi
10. Kata kunci
11. Sinopsis
12. Cover

### 15.5 Modul Collection
Cakupan:
1. Registrasi item fisik
2. Barcode atau kode inventaris
3. Lokasi rak
4. Status item
5. Kondisi item
6. Histori item

### 15.6 Modul Member
Cakupan:
1. Data mahasiswa
2. Data dosen
3. Data tenaga kependidikan
4. Nomor anggota
5. Status aktif
6. Riwayat anggota dasar

### 15.7 Modul Circulation
Cakupan:
1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Denda
5. Keterlambatan
6. Validasi anggota
7. Histori transaksi

### 15.8 Modul Digital Repository
Cakupan:
1. Upload file digital
2. Metadata file
3. Relasi ke bibliographic record
4. Preview PDF
5. Hak akses unduh
6. OCR untuk file scan
7. Indexing isi dokumen
8. Status publikasi
9. Embargo dasar bila diaktifkan

### 15.9 Modul OPAC
Cakupan:
1. Pencarian publik
2. Filter pencarian
3. Halaman detail koleksi
4. Status ketersediaan item
5. Informasi lokasi rak
6. Informasi koleksi digital
7. Preview file sesuai hak akses

### 15.10 Modul Reporting
Cakupan:
1. Statistik koleksi
2. Statistik anggota
3. Statistik peminjaman
4. Statistik keterlambatan
5. Statistik koleksi populer
6. Statistik akses digital
7. Laporan operasional dasar

### 15.11 Modul Audit and Monitoring
Cakupan:
1. Audit aktivitas penting
2. Log perubahan data utama
3. Log transaksi pinjam kembali
4. Log upload file digital
5. Monitoring queue dasar

## 16. Ruang Lingkup Produk Fase Lanjutan
Fase lanjutan tidak menjadi target rilis awal, tetapi harus diakomodasi secara desain.

Fase lanjutan meliputi:
1. Integrasi SIAKAD kampus
2. Integrasi SSO kampus
3. Modul Acquisition penuh
4. Reservasi tingkat lanjut
5. Notifikasi email dan WhatsApp lebih luas
6. Portal anggota lebih kaya
7. Dashboard pimpinan lebih analitis
8. Integrasi RFID
9. Sinkronisasi antarunit perpustakaan
10. API eksternal terbatas

## 17. Fitur yang Tidak Masuk Fase 1
Agar fokus dan realistis, fitur berikut tidak masuk fase 1:

1. Mobile app native
2. Microservices
3. Multi campus multi tenant
4. RFID penuh
5. Integrasi pembayaran daring
6. Marketplace buku
7. Chatbot perpustakaan
8. Analitik prediktif kompleks
9. Integrasi nasional tingkat lanjut
10. Workflow akuisisi yang panjang dan kompleks

## 18. Sasaran Hasil Produk Fase 1
Hasil yang wajib dicapai pada akhir fase 1:

1. Bibliographic record dapat dikelola penuh.
2. Item fisik dapat didaftarkan dan dilacak statusnya.
3. Anggota dapat dikelola.
4. Transaksi pinjam dan kembali berjalan normal.
5. File digital dapat diunggah, dipreview, dan diakses sesuai aturan.
6. OPAC publik dapat dipakai untuk mencari koleksi.
7. Laporan dasar tersedia.
8. Audit aktivitas penting tercatat.
9. Sistem cukup stabil untuk dipakai operasional dasar perpustakaan kampus.

## 19. Sasaran Bisnis dan Operasional
Sasaran operasional yang ingin dicapai:

1. Mengurangi waktu pencarian koleksi.
2. Mengurangi kesalahan pencatatan transaksi.
3. Meningkatkan jumlah koleksi yang terdigitalisasi dalam sistem.
4. Meningkatkan keterlacakan status item fisik.
5. Meningkatkan kualitas layanan kepada mahasiswa dan dosen.
6. Mempercepat penyusunan laporan operasional perpustakaan.

## 20. Indikator Keberhasilan Produk
Indikator keberhasilan awal meliputi:

1. Seluruh koleksi prioritas telah berhasil masuk ke sistem katalog.
2. OPAC dapat mencari koleksi fisik dan digital dalam satu antarmuka.
3. Transaksi peminjaman dan pengembalian tercatat akurat.
4. File digital berhasil dikelola dengan preview dan kontrol akses.
5. Pustakawan dapat memperbarui metadata tanpa proses manual di luar sistem.
6. Laporan operasional dapat dihasilkan dari sistem tanpa rekap manual utama.
7. Sistem dipakai aktif oleh perpustakaan pada operasional harian.

## 21. Kebutuhan Produk Berdasarkan Modul

### 21.1 Kebutuhan Produk Modul Core
Kebutuhan:
1. Sistem harus memiliki parameter operasional perpustakaan.
2. Sistem harus memiliki pengaturan institusi.
3. Sistem harus memiliki konfigurasi denda dasar.
4. Sistem harus memiliki pengaturan lama pinjam dasar.
5. Sistem harus memiliki dashboard ringkas awal.

### 21.2 Kebutuhan Produk Modul Identity and Access
Kebutuhan:
1. User harus dapat login.
2. Admin harus dapat mengelola user.
3. Sistem harus mendukung role dan permission.
4. Hak akses per modul harus dapat dibatasi.
5. Aktivitas autentikasi penting harus tercatat.

### 21.3 Kebutuhan Produk Modul Master Data
Kebutuhan:
1. Pengelola harus dapat membuat data referensi yang konsisten.
2. Data referensi harus bisa dipakai ulang oleh modul lain.
3. Data master harus dapat dicari dan difilter.
4. Data master harus dapat dinonaktifkan bila tidak dipakai lagi.

### 21.4 Kebutuhan Produk Modul Catalog
Kebutuhan:
1. Pustakawan harus dapat menambah bibliographic record.
2. Pustakawan harus dapat mengubah bibliographic record.
3. Pustakawan harus dapat mencari bibliographic record.
4. Satu record harus dapat terhubung ke banyak item fisik.
5. Satu record harus dapat terhubung ke banyak file digital bila dibutuhkan.

### 21.5 Kebutuhan Produk Modul Collection
Kebutuhan:
1. Item fisik harus dapat didaftarkan per eksemplar.
2. Setiap item harus memiliki identitas unik.
3. Setiap item harus punya status yang jelas.
4. Lokasi rak harus dapat dicatat.
5. Kondisi item harus dapat dicatat.
6. Histori item harus tersedia.

### 21.6 Kebutuhan Produk Modul Member
Kebutuhan:
1. Data anggota harus dapat dikelola.
2. Status aktif anggota harus dapat dibedakan.
3. Sistem harus menyimpan kategori anggota.
4. Riwayat transaksi anggota harus dapat ditampilkan.
5. Blokir anggota dasar harus dapat diterapkan bila diperlukan.

### 21.7 Kebutuhan Produk Modul Circulation
Kebutuhan:
1. Petugas harus dapat memproses peminjaman dengan cepat.
2. Petugas harus dapat memproses pengembalian.
3. Sistem harus menghitung keterlambatan.
4. Sistem harus menghitung denda dasar.
5. Sistem harus memperbarui status item secara otomatis.
6. Histori transaksi harus tersimpan.

### 21.8 Kebutuhan Produk Modul Digital Repository
Kebutuhan:
1. File digital harus dapat diunggah.
2. File digital harus memiliki metadata.
3. File digital harus dapat dihubungkan ke bibliographic record.
4. File PDF harus dapat dipreview di browser.
5. Hak akses file harus dapat diatur.
6. Teks hasil OCR harus dapat dihasilkan untuk file scan yang didukung.
7. Isi dokumen harus dapat diindeks ke pencarian bila relevan.

### 21.9 Kebutuhan Produk Modul OPAC
Kebutuhan:
1. Pengguna harus dapat mencari koleksi dengan cepat.
2. Pengguna harus dapat memfilter hasil pencarian.
3. Pengguna harus melihat detail koleksi.
4. Pengguna harus mengetahui status ketersediaan item.
5. Pengguna harus mengetahui apakah versi digital tersedia.
6. Halaman OPAC harus nyaman digunakan pada desktop dan mobile.

### 21.10 Kebutuhan Produk Modul Reporting
Kebutuhan:
1. Admin harus dapat melihat ringkasan statistik.
2. Pengelola harus dapat mengekspor laporan pada tahap yang ditentukan.
3. Laporan harus bisa difilter berdasarkan periode.
4. Laporan harus memberi gambaran koleksi, transaksi, anggota, dan akses digital.

### 21.11 Kebutuhan Produk Modul Audit and Monitoring
Kebutuhan:
1. Sistem harus mencatat aktivitas penting.
2. Sistem harus menyediakan catatan perubahan data kritis.
3. Sistem harus membantu penelusuran masalah operasional.
4. Monitoring proses queue dasar harus tersedia untuk admin teknis.

## 22. User Stories Inti Fase 1

### 22.1 User Story Pustakawan
1. Sebagai pustakawan, saya ingin menambah bibliographic record agar koleksi tercatat rapi.
2. Sebagai pustakawan, saya ingin menghubungkan satu judul dengan banyak item agar stok fisik terbaca dengan benar.
3. Sebagai pustakawan, saya ingin mengunggah file digital agar koleksi digital dapat diakses sesuai hak akses.
4. Sebagai pustakawan, saya ingin mencari metadata dengan cepat agar pekerjaan katalogisasi lebih efisien.

### 22.2 User Story Petugas Sirkulasi
1. Sebagai petugas sirkulasi, saya ingin memindai item dan memproses pinjam dengan cepat agar antrean layanan singkat.
2. Sebagai petugas sirkulasi, saya ingin sistem otomatis menghitung jatuh tempo dan denda agar tidak salah hitung.
3. Sebagai petugas sirkulasi, saya ingin melihat histori anggota agar dapat memeriksa status pinjam.

### 22.3 User Story Mahasiswa
1. Sebagai mahasiswa, saya ingin mencari koleksi di OPAC agar saya tahu apakah buku tersedia.
2. Sebagai mahasiswa, saya ingin melihat lokasi rak agar saya mudah menemukan buku fisik.
3. Sebagai mahasiswa, saya ingin mengetahui apakah ada versi digital agar saya tidak perlu datang ke rak bila tidak perlu.

### 22.4 User Story Dosen
1. Sebagai dosen, saya ingin mencari koleksi fisik dan digital dari satu portal agar pencarian referensi lebih cepat.
2. Sebagai dosen, saya ingin melihat detail akses file digital agar tahu apakah dokumen dapat saya baca atau unduh.

### 22.5 User Story Pimpinan
1. Sebagai pimpinan perpustakaan, saya ingin melihat statistik koleksi dan transaksi agar dapat menilai kinerja layanan.
2. Sebagai pimpinan, saya ingin laporan dasar tersedia tanpa olah manual besar.

## 23. Prioritas Produk
Prioritas produk dibagi menjadi empat tingkat.

### 23.1 Prioritas P1, Wajib Ada untuk Go Live Fase 1
1. Login dan manajemen user dasar
2. Role dan permission dasar
3. Master data inti
4. Bibliographic record
5. Item fisik
6. Anggota
7. Peminjaman
8. Pengembalian
9. Denda dasar
10. Upload file digital
11. Preview PDF
12. OPAC publik dasar
13. Search katalog
14. Laporan dasar
15. Audit log dasar

### 23.2 Prioritas P2, Sangat Disarankan pada Fase 1
1. OCR dokumen scan
2. Indexing isi dokumen
3. Dashboard statistik ringkas
4. Histori item
5. Histori anggota
6. Filter lanjutan OPAC
7. Status embargo dasar

### 23.3 Prioritas P3, Dikerjakan Setelah Fase 1 Stabil
1. Integrasi SIAKAD
2. Notifikasi email
3. Reservasi
4. Import anggota massal
5. Export laporan lanjutan
6. Acquisition dasar

### 23.4 Prioritas P4, Fase Lanjutan
1. SSO kampus
2. WhatsApp notifikasi
3. RFID
4. Analitik lanjutan
5. Portal anggota lebih lengkap
6. API eksternal

## 24. Kebutuhan UX Produk

### 24.1 Prinsip UX Admin
1. Sederhana
2. Konsisten
3. Cepat dipelajari
4. Minim klik untuk proses berulang
5. Tampilan tabel jelas
6. Filter mudah dipakai
7. Validasi error jelas

### 24.2 Prinsip UX OPAC
1. Cepat ditemukan
2. Pencarian dominan
3. Hasil mudah dibaca
4. Status ketersediaan terlihat jelas
5. Mobile responsive
6. Detail koleksi informatif

### 24.3 Prinsip UX Sirkulasi
1. Fokus pada kecepatan transaksi
2. Input barcode menjadi titik utama interaksi
3. Status anggota dan item tampil jelas
4. Peringatan denda dan blokir tampil langsung

## 25. Kebutuhan Data Produk
Kebutuhan data inti yang wajib tersedia dalam produk:

1. User
2. Role
3. Permission
4. Pengarang
5. Penerbit
6. Bahasa
7. Subjek
8. Klasifikasi
9. Lokasi rak
10. Bibliographic record
11. Physical item
12. Member
13. Loan
14. Fine
15. Digital asset
16. OCR text
17. Activity log
18. Setting sistem

Prinsip data:
1. Bibliographic record menjadi induk.
2. Physical item menjadi turunan untuk koleksi cetak.
3. Digital asset menjadi turunan untuk koleksi digital.
4. Search index mengikuti data utama, bukan menggantikannya.

## 26. Kebutuhan Integrasi Produk
Pada fase 1, integrasi wajib disiapkan secara desain tetapi belum seluruhnya diaktifkan.

Integrasi yang harus dipertimbangkan:
1. Import anggota dari sumber kampus
2. Sinkronisasi user dasar
3. SMTP email kampus
4. Search engine internal
5. Object storage
6. OCR engine

Integrasi yang belum wajib aktif:
1. SIAKAD real time
2. SSO kampus
3. WhatsApp gateway
4. RFID

## 27. Kebutuhan Keamanan Produk
Kebutuhan keamanan utama:

1. Semua halaman admin harus memerlukan login.
2. Hak akses harus dibatasi berdasar role dan permission.
3. File digital privat tidak boleh dibuka bebas.
4. Aktivitas penting harus terekam.
5. Validasi upload file harus ketat.
6. Password harus dikelola aman.
7. Perubahan data penting harus bisa ditelusuri.

## 28. Kebutuhan Kinerja Produk
Kebutuhan kinerja utama:

1. Pencarian OPAC harus responsif.
2. Daftar data besar harus menggunakan pagination.
3. Search katalog harus memakai indeks pencarian.
4. Proses OCR dan indexing harus dijalankan di background job.
5. Sistem harus tetap layak dipakai pada jam operasional perpustakaan.

## 29. Kebutuhan Operasional Produk
Kebutuhan operasional:

1. Sistem harus mudah di-deploy pada server kampus atau VPS.
2. Sistem harus mudah di-backup.
3. Sistem harus mudah dirawat tim kecil.
4. Log aplikasi harus tersedia.
5. Konfigurasi environment harus jelas.
6. Queue worker harus terkelola.

## 30. Asumsi Produk
Asumsi yang digunakan dalam PRD ini:

1. Kampus memiliki perpustakaan fisik.
2. Kampus memiliki koleksi digital atau rencana mengelolanya.
3. Tim pengembang internal tidak besar.
4. Tim pengelola perpustakaan bersedia migrasi ke proses digital.
5. Infrastruktur server dasar dapat disediakan.
6. Pengguna utama memakai browser modern.

## 31. Kendala Produk
Kendala yang harus diakui sejak awal:

1. Data lama mungkin tidak rapi.
2. Standar metadata lama mungkin tidak konsisten.
3. Kualitas file scan mungkin bervariasi.
4. SDM operator mungkin perlu pelatihan.
5. Integrasi kampus bisa berbeda antar institusi.
6. Storage file digital akan bertumbuh dari waktu ke waktu.

## 32. Ketergantungan Produk
Ketergantungan utama:

1. Dokumen blueprint 01, 02, dan 03
2. Keputusan struktur role kampus
3. Data koleksi awal
4. Data anggota awal
5. Infrastruktur server
6. Kebijakan perpustakaan kampus
7. Standar katalog internal yang akan dipakai

## 33. Risiko Produk
Risiko utama:

1. Scope creep karena ingin memasukkan terlalu banyak fitur sejak awal.
2. Ketidakkonsistenan metadata saat migrasi data lama.
3. Ketergantungan pada operator tertentu.
4. Kegagalan adopsi bila UX terlalu rumit.
5. Beban storage meningkat bila pengelolaan file digital tidak disiplin.
6. Hasil OCR tidak akurat untuk scan berkualitas rendah.

Mitigasi awal:
1. Fokus pada fase 1.
2. Standarkan master data dan validasi.
3. Gunakan workflow input yang sederhana.
4. Lakukan pelatihan operator.
5. Siapkan aturan unggah file.
6. Pisahkan proses berat ke queue.

## 34. Strategi Rilis Produk

### 34.1 Rilis Tahap 1
Cakupan:
1. Core
2. Identity and Access
3. Master Data
4. Catalog
5. Collection
6. Member
7. Circulation
8. Digital Repository dasar
9. OPAC dasar
10. Reporting dasar
11. Audit dasar

### 34.2 Rilis Tahap 2
Cakupan:
1. Peningkatan OCR
2. Peningkatan OPAC
3. Import massal
4. Integrasi dasar kampus
5. Notifikasi email
6. Dashboard pimpinan

### 34.3 Rilis Tahap 3
Cakupan:
1. Integrasi SSO
2. Integrasi SIAKAD lebih dalam
3. Acquisition penuh
4. Reservasi lanjutan
5. Fitur tambahan sesuai evaluasi penggunaan nyata

## 35. KPI Produk Awal
KPI awal yang relevan:

1. Persentase koleksi prioritas yang berhasil dimigrasikan ke sistem.
2. Jumlah transaksi pinjam kembali yang tercatat penuh di sistem.
3. Jumlah file digital yang berhasil dikelola dalam repositori.
4. Waktu rata-rata pencarian koleksi oleh pengguna.
5. Waktu rata-rata proses transaksi pinjam oleh petugas.
6. Jumlah laporan dasar yang dapat dihasilkan langsung dari sistem.
7. Jumlah pengguna aktif OPAC.

## 36. Definisi Sukses Produk
PERPUSQU dinyatakan sukses pada fase 1 bila:

1. Sistem dipakai aktif dalam operasional perpustakaan kampus.
2. Katalog koleksi inti sudah masuk ke sistem.
3. Proses peminjaman dan pengembalian berjalan melalui sistem.
4. OPAC dipakai oleh pengguna untuk mencari koleksi.
5. Repositori digital dasar berfungsi.
6. Pustakawan dan petugas sirkulasi tidak lagi bergantung pada pencatatan manual utama.
7. Laporan operasional dasar tersedia dari sistem.

## 37. Acceptance Criteria Tingkat Produk
Kriteria penerimaan tingkat produk untuk fase 1:

1. Admin dapat login dan mengelola user sesuai role.
2. Pustakawan dapat membuat bibliographic record.
3. Pustakawan dapat mendaftarkan item fisik.
4. Petugas dapat memproses pinjam dan kembali.
5. Sistem dapat menampilkan status item.
6. Sistem dapat mengelola anggota.
7. Sistem dapat mengunggah dan mempreview file digital.
8. OPAC dapat mencari koleksi.
9. Laporan dasar dapat dilihat atau dihasilkan.
10. Audit aktivitas penting tercatat.

## 38. Batasan Produk
Batasan resmi produk fase 1:

1. Bukan sistem ERP kampus.
2. Bukan sistem akuisisi penuh.
3. Bukan sistem repositori penelitian nasional.
4. Bukan aplikasi mobile native.
5. Tidak mengejar semua integrasi eksternal sejak awal.
6. Tidak menggunakan microservices.

## 39. Arah Dokumen Turunan Setelah PRD
Dokumen ini menjadi dasar bagi penyusunan dokumen berikut:

1. 05_SRS.md
2. 06_USE_CASE.md
3. 07_ROLE_PERMISSION_MATRIX.md
4. 08_MENU_MAP.md
5. 09_ROUTE_MAP.md
6. 10_VIEW_MAP.md
7. 11_CONTROLLER_MAP.md
8. 12_SERVICE_LAYER.md
9. 13_MODEL_MAP.md
10. 14_SCHEMA.sql

Semua dokumen turunan wajib:
1. Mengacu ke nama modul yang telah ditetapkan.
2. Mengacu ke ruang lingkup fase 1 dan fase lanjutan pada dokumen ini.
3. Tidak boleh menambah fitur besar baru tanpa perubahan formal pada PRD.
4. Menjaga keterkaitan antara kebutuhan produk, desain teknis, dan implementasi coding.

## 40. Kesimpulan
PERPUSQU dirancang sebagai aplikasi web perpustakaan hibrid kampus yang fokus pada integrasi koleksi fisik dan digital, efisiensi operasional, kemudahan pencarian, dan kesiapan pengembangan bertahap. PRD ini menetapkan arah produk secara jelas agar seluruh proses pengembangan berikutnya, termasuk kerja AI Agent untuk full stack coding, berjalan dalam koridor yang sama dengan blueprint sebelumnya.

PRD ini bersifat mengikat untuk seluruh dokumen turunan sampai ada revisi formal tingkat produk.

END OF 04_PRD.md