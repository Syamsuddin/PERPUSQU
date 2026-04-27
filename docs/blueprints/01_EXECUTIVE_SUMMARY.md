# 01_EXECUTIVE_SUMMARY.md

## Nama Proyek
PERPUSQU - Sistem Informasi Perpustakaan Hibrid Kampus

## 1. Ringkasan Eksekutif
PERPUSQU adalah Sistem Informasi Perpustakaan Hibrid Kampus berupa aplikasi perpustakaan berbasis web yang dirancang untuk mengintegrasikan pengelolaan koleksi buku cetak dan koleksi digital dalam satu sistem terpadu. Sistem ini dibangun dengan pendekatan monolith modular agar pengembangan, pemeliharaan, dan pengoperasian tetap efisien untuk tim pengembang kampus yang tidak terlalu besar.

Aplikasi ini bertujuan menyelesaikan persoalan umum perpustakaan kampus, yaitu pencarian koleksi yang lambat, data koleksi fisik yang belum tertata secara terpusat, pengelolaan file digital yang terpisah dari katalog, proses sirkulasi yang masih manual atau semi manual, serta keterbatasan pelaporan untuk pimpinan dan pengelola perpustakaan.

Melalui sistem ini, seluruh koleksi cetak tetap dikelola sebagai koleksi fisik di rak, tetapi data bibliografisnya masuk ke basis data perpustakaan dan tampil dalam katalog daring. Koleksi digital seperti e-book, skripsi, tesis, jurnal internal, modul ajar, hasil scan, dan dokumen akademik lain juga dikelola dalam sistem yang sama atau dalam repositori yang terhubung. Dengan demikian, pengguna memperoleh satu pintu layanan untuk mencari, menemukan, mengetahui status ketersediaan, meminjam, atau mengakses koleksi sesuai hak akses masing-masing.

## 2. Latar Belakang
Perpustakaan kampus saat ini tidak lagi cukup dikelola hanya sebagai ruang penyimpanan buku cetak. Kebutuhan sivitas akademika telah bergeser ke layanan pencarian cepat, akses daring, ketersediaan metadata yang rapi, integrasi koleksi fisik dan digital, serta pelaporan yang mendukung pengambilan keputusan.

Banyak perpustakaan kampus masih menghadapi kondisi berikut:
1. Data buku cetak belum sepenuhnya masuk ke database katalog.
2. Koleksi digital tersebar di folder atau media simpan yang tidak terstandar.
3. Pencarian koleksi belum terpadu antara bahan cetak dan digital.
4. Proses peminjaman, pengembalian, reservasi, dan denda belum efisien.
5. Pelaporan statistik penggunaan koleksi masih lemah.
6. Integrasi dengan sistem akademik kampus belum tersedia atau belum stabil.

Kondisi tersebut menuntut hadirnya aplikasi perpustakaan hibrid yang stabil, mudah dikelola, dan cukup fleksibel untuk berkembang secara bertahap.

## 3. Tujuan Pengembangan
Tujuan pengembangan sistem ini adalah:
1. Membangun satu platform perpustakaan kampus yang mengintegrasikan koleksi fisik dan digital.
2. Mempermudah pencarian koleksi melalui katalog daring terpadu.
3. Mendigitalisasi proses operasional utama perpustakaan, mulai dari katalogisasi sampai sirkulasi.
4. Menyediakan repositori digital yang terkelola, aman, dan mudah diakses sesuai hak akses.
5. Menyediakan dashboard dan laporan manajerial untuk pimpinan kampus dan pengelola perpustakaan.
6. Menyiapkan fondasi integrasi dengan sistem akademik kampus pada tahap lanjutan.

## 4. Sasaran Pengguna
Sistem ini ditujukan untuk:
1. Super Admin Sistem
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Dosen
6. Mahasiswa
7. Tenaga Kependidikan
8. Tamu atau pengunjung OPAC publik

## 5. Konsep Solusi
Aplikasi ini menggunakan konsep perpustakaan hibrid. Semua koleksi cetak dicatat dalam database bibliografis dan dapat dicari melalui katalog daring. Semua koleksi digital dicatat dalam metadata yang setara dan ditautkan ke file digital bila hak akses mengizinkan. Dengan model ini, pengguna cukup melakukan satu kali pencarian untuk mengetahui:
1. Apakah koleksi tersedia dalam bentuk cetak
2. Apakah koleksi tersedia dalam bentuk digital
3. Di mana lokasi fisik koleksi berada
4. Berapa jumlah eksemplar yang tersedia
5. Siapa yang berhak mengakses file digital
6. Apa status pinjam atau akses koleksi saat ini

## 6. Ruang Lingkup Fase 1
Fase pertama difokuskan pada fungsi inti agar aplikasi cepat berjalan dan cepat dipakai. Ruang lingkup fase 1 meliputi:

### 6.1 Modul Core
1. Manajemen pengguna
2. Role dan permission
3. Konfigurasi sistem
4. Audit log dasar
5. Notifikasi dasar

### 6.2 Modul Katalog
1. Data bibliografis koleksi
2. Pengarang
3. Penerbit
4. Subjek
5. Klasifikasi
6. ISBN
7. Bahasa
8. Tahun terbit
9. Edisi
10. Kata kunci

### 6.3 Modul Koleksi Fisik
1. Data eksemplar
2. Barcode atau kode inventaris
3. Lokasi rak
4. Status tersedia
5. Status dipinjam
6. Kondisi buku
7. Histori item

### 6.4 Modul Sirkulasi
1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Reservasi
5. Keterlambatan
6. Denda

### 6.5 Modul Anggota
1. Data mahasiswa
2. Data dosen
3. Data tenaga kependidikan
4. Status aktif anggota
5. Nomor anggota
6. Riwayat transaksi anggota

### 6.6 Modul Repositori Digital
1. Upload file digital
2. Metadata file
3. Preview dokumen
4. Hak akses unduh
5. OCR untuk file hasil scan
6. Pengindeksan isi dokumen

### 6.7 Modul OPAC
1. Pencarian umum
2. Filter pencarian
3. Detail koleksi
4. Status ketersediaan
5. Informasi lokasi rak
6. Informasi akses file digital

### 6.8 Modul Laporan
1. Statistik jumlah koleksi
2. Statistik peminjaman
3. Statistik keterlambatan
4. Statistik koleksi populer
5. Statistik akses koleksi digital
6. Laporan anggota aktif

## 7. Ruang Lingkup Fase Lanjutan
Setelah fase 1 stabil, sistem dapat dikembangkan ke fase berikutnya:
1. Integrasi SIAKAD atau PDDikti internal kampus
2. Integrasi SSO kampus
3. Usulan pengadaan buku
4. Workflow persetujuan koleksi digital
5. Integrasi RFID
6. Integrasi notifikasi WhatsApp atau email kampus
7. Mobile friendly self service portal
8. Dashboard pimpinan yang lebih analitis
9. Sinkronisasi metadata antar unit perpustakaan
10. API eksternal untuk integrasi pihak ketiga

## 8. Prinsip Arsitektur
Arsitektur yang dipilih adalah monolith modular. Artinya, sistem dibangun dalam satu codebase utama, satu aplikasi backend utama, dan satu database utama, tetapi domain bisnis dipisahkan ke dalam modul-modul yang jelas.

Prinsip arsitektur yang digunakan:
1. Satu aplikasi inti untuk mempercepat pengembangan
2. Modular di level domain agar kode tetap rapi
3. Database relasional terpusat untuk transaksi utama
4. Search engine terpisah untuk pencarian katalog yang cepat
5. Object storage untuk file digital
6. Queue untuk proses latar belakang
7. Audit trail untuk aktivitas penting
8. Role based access control untuk keamanan akses

## 9. Stack Teknologi yang Disepakati
Stack teknologi inti sistem adalah:
1. Backend: Laravel
2. Bahasa pemrograman: PHP
3. Database utama: MySQL
4. Frontend: Blade, Livewire, Bootstrap, Vite
5. Cache dan queue: Redis
6. Search engine: Meilisearch
7. Penyimpanan file digital: Object storage kompatibel S3 atau storage lokal terkelola
8. PDF preview: PDF.js
9. OCR: Tesseract
10. Web server: Nginx
11. OS server: Ubuntu Server LTS

## 10. Manfaat Utama Sistem
Sistem ini diharapkan memberi manfaat berikut:
1. Meningkatkan kemudahan pencarian koleksi bagi mahasiswa dan dosen
2. Mempercepat layanan sirkulasi perpustakaan
3. Meningkatkan akurasi data koleksi
4. Menyatukan data buku cetak dan koleksi digital
5. Menyediakan dasar pengambilan keputusan berbasis data
6. Mengurangi ketergantungan pada proses manual
7. Meningkatkan kualitas layanan perpustakaan kampus
8. Menjadi fondasi transformasi digital perpustakaan

## 11. Target Keluaran Sistem
Keluaran utama yang diharapkan dari proyek ini adalah:
1. Aplikasi web perpustakaan hibrid siap pakai
2. Katalog daring terpadu
3. Modul sirkulasi aktif
4. Repositori digital internal kampus
5. Dashboard operasional perpustakaan
6. Laporan manajerial berkala
7. Struktur data dan modul yang siap dikembangkan lebih lanjut

## 12. Indikator Keberhasilan Awal
Indikator keberhasilan tahap awal proyek adalah:
1. Seluruh koleksi cetak prioritas telah masuk ke database
2. Pengguna dapat mencari koleksi fisik dan digital dari satu portal
3. Transaksi peminjaman dan pengembalian tercatat penuh dalam sistem
4. File digital dapat diunggah, dipreview, dan diakses sesuai hak akses
5. Pustakawan dapat menghasilkan laporan dasar tanpa olah manual
6. Waktu pencarian koleksi lebih cepat dibanding proses lama
7. Sistem dapat dipakai stabil oleh perpustakaan kampus dalam operasional harian

## 13. Strategi Implementasi
Strategi implementasi disarankan sebagai berikut:
1. Mulai dari kebutuhan inti, bukan fitur yang terlalu luas
2. Bangun fase 1 sampai stabil dan digunakan aktif
3. Lakukan migrasi data koleksi secara bertahap
4. Lakukan pelatihan operator perpustakaan
5. Uji proses sirkulasi pada unit terbatas lebih dulu
6. Evaluasi penggunaan nyata sebelum menambah fitur lanjutan

## 14. Risiko Utama
Risiko yang perlu diantisipasi:
1. Data koleksi lama belum lengkap
2. Standar input metadata belum seragam
3. SDM operator belum terbiasa dengan sistem digital
4. Kualitas file scan digital belum baik
5. Proses integrasi dengan sistem akademik memerlukan penyesuaian tambahan
6. Pertumbuhan file digital dapat membebani storage bila tidak diatur sejak awal

## 15. Arah Dokumen Blueprint Berikutnya
Dokumen ini menjadi dasar bagi penyusunan dokumen lanjutan, yaitu:
1. 02_STACK_TEKNOLOGI.md
2. 03_ARSITEKTUR_MODULAR.md
3. 04_PRD.md
4. 05_SRS.md
5. 06_ROLE_PERMISSION_MATRIX.md
6. 07_MENU_MAP.md
7. 08_ROUTE_MAP.md
8. 09_SCHEMA.sql
9. 10_VIEW_MAP.md
10. 11_WORKFLOW_STATE_MACHINE.md

## Penutup
Blueprint ini menempatkan perpustakaan kampus sebagai layanan informasi modern yang tetap menjaga koleksi fisik, namun memperluas akses melalui sistem digital terpadu. Dengan pendekatan monolith modular, kampus memperoleh aplikasi yang realistis untuk dibangun oleh tim kecil, cukup kuat untuk operasional harian, dan tetap terbuka untuk pengembangan jangka menengah.

END OF 01_EXECUTIVE_SUMMARY.md