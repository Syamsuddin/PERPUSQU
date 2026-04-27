# 02_STACK_TEKNOLOGI.md

## 1. Nama Dokumen
Stack Teknologi Sistem Informasi Perpustakaan Hibrid Kampus

## 2. Tujuan Dokumen
Dokumen ini menetapkan stack teknologi resmi yang menjadi dasar pembangunan aplikasi PERPUSQU - Sistem Informasi Perpustakaan Hibrid Kampus. Dokumen ini berfungsi sebagai acuan tunggal bagi tim analis, programmer, administrator server, tester, dan pihak manajemen agar seluruh keputusan teknis konsisten sejak fase desain, implementasi, pengujian, sampai operasional produksi.

## 3. Prinsip Pemilihan Stack
Pemilihan stack teknologi pada proyek ini menggunakan prinsip berikut:

1. Cocok untuk tim pengembang kampus yang tidak terlalu besar.
2. Cepat dikembangkan dan mudah dipelihara.
3. Stabil untuk operasional harian perpustakaan.
4. Mudah di-deploy pada VPS atau server kampus.
5. Mendukung arsitektur monolith modular.
6. Mendukung pengelolaan koleksi fisik dan digital dalam satu sistem.
7. Mendukung pencarian katalog cepat dan akurat.
8. Mendukung unggah, preview, dan pengelolaan file digital.
9. Memiliki dokumentasi resmi yang baik dan komunitas yang kuat.
10. Biaya lisensi rendah atau berbasis open source.

## 4. Arsitektur Umum yang Dipilih
Arsitektur aplikasi yang dipilih adalah monolith modular.

Definisi operasional:
1. Satu codebase utama.
2. Satu aplikasi backend utama berbasis Laravel.
3. Satu database utama MySQL.
4. Modul bisnis dipisah secara logis per domain.
5. Komponen pendukung seperti cache, pencarian, queue, dan object storage dipisah sebagai service pendukung.
6. Tidak menggunakan microservices pada fase awal.

Alasan pemilihan:
1. Lebih mudah dikembangkan oleh tim kecil.
2. Lebih mudah diuji end to end.
3. Lebih mudah dideploy dan dipelihara.
4. Lebih hemat biaya infrastruktur.
5. Tetap cukup rapi bila modul dipisah dengan disiplin yang baik.

## 5. Stack Teknologi Resmi

### 5.1 Sistem Operasi Server
- Ubuntu Server 24.04 LTS

Peran:
1. Sistem operasi utama untuk server aplikasi dan server pendukung.
2. Basis lingkungan produksi, staging, dan pengembangan internal bila diperlukan.

Alasan:
1. Stabil untuk server.
2. Siklus dukungan panjang.
3. Dokumentasi luas.
4. Kompatibel dengan Nginx, PHP, MySQL, Redis, Meilisearch, dan Docker.

### 5.2 Web Server
- Nginx

Peran:
1. Menangani HTTP dan HTTPS.
2. Reverse proxy ke PHP-FPM.
3. Menangani static assets.
4. Menangani TLS termination.
5. Mendukung rate limiting dan basic hardening di level web server.

Alasan:
1. Ringan.
2. Stabil.
3. Umum dipakai pada deployment Laravel.
4. Baik untuk performa aplikasi web kampus.

### 5.3 Bahasa Pemrograman Backend
- PHP 8.4

Peran:
1. Bahasa utama backend aplikasi.
2. Menjalankan seluruh modul bisnis, API, halaman admin, OPAC, dan integrasi.

Alasan:
1. Selaras dengan Laravel 13.
2. Mudah dikuasai tim PHP kampus.
3. Ekosistem package besar.
4. Cocok untuk pengembangan cepat.

### 5.4 Framework Backend
- Laravel 13

Peran:
1. Framework utama aplikasi web.
2. Menyediakan routing, middleware, ORM, migration, validation, queue, scheduler, storage abstraction, policy, job, event, dan testing foundation.

Alasan:
1. Produktivitas tinggi.
2. Dokumentasi kuat.
3. Struktur aplikasi rapi.
4. Sangat cocok untuk monolith modular.
5. Cocok untuk tim kecil sampai menengah.

### 5.5 Frontend Rendering
- Blade Template Engine
- Livewire 4
- Bootstrap 5.3
- Vite

Peran:
1. Blade untuk rendering halaman server side.
2. Livewire untuk komponen interaktif tanpa SPA penuh.
3. Bootstrap untuk UI admin dashboard, form, tabel, card, modal, dan komponen antarmuka.
4. Vite untuk build asset CSS dan JavaScript.

Alasan:
1. Lebih cepat dikerjakan oleh tim kecil dibanding frontend SPA penuh.
2. Tetap interaktif untuk filter, pencarian, modal, dan form dinamis.
3. Bootstrap mempercepat standar UI admin kampus.
4. Vite membuat manajemen asset lebih sederhana.

### 5.6 Database Utama
- MySQL 8.4
- Engine utama: InnoDB

Peran:
1. Menyimpan data transaksi utama.
2. Menyimpan data bibliografis, eksemplar, anggota, sirkulasi, denda, pengguna, audit, dan konfigurasi.
3. Menyimpan metadata tambahan berbasis JSON untuk kebutuhan tertentu.

Alasan:
1. Familiar bagi banyak tim kampus.
2. Stabil untuk aplikasi transaksi.
3. Mendukung relasi, index, transaction, JSON, dan backup yang matang.
4. Mudah diintegrasikan dengan Laravel.

### 5.7 Cache, Queue, dan Session Store
- Redis

Peran:
1. Cache aplikasi.
2. Session store terpusat.
3. Queue backend.
4. Rate limit backend.
5. Mendukung proses asynchronous.

Alasan:
1. Sangat cepat.
2. Ringan.
3. Cocok untuk queue dan cache Laravel.
4. Mengurangi beban query berulang ke MySQL.

### 5.8 Queue Monitoring
- Laravel Horizon

Peran:
1. Monitoring antrean queue.
2. Monitoring job gagal.
3. Monitoring throughput worker.
4. Kontrol worker Redis queue.

Alasan:
1. Integrasi resmi dengan Laravel.
2. Memudahkan pemantauan job latar belakang.

### 5.9 Search Engine Katalog
- Meilisearch

Peran:
1. Mesin pencarian cepat untuk OPAC dan katalog internal.
2. Menangani typo tolerance.
3. Menangani prefix search.
4. Menangani faceted filtering.
5. Menangani relevansi pencarian katalog.

Alasan:
1. Instalasi lebih ringan dibanding search stack besar.
2. Cepat dan cocok untuk katalog perpustakaan.
3. Mudah diintegrasikan dengan Laravel.
4. Baik untuk tim kecil.

### 5.10 Media dan File Storage
- Laravel Filesystem
- MinIO atau object storage kompatibel S3 untuk produksi
- Local managed storage untuk lingkungan pengembangan

Peran:
1. Penyimpanan file cover buku.
2. Penyimpanan file PDF, e-book, skripsi, tesis, jurnal internal, scan dokumen, dan lampiran.
3. Penyimpanan hasil OCR dan file turunan bila dibutuhkan.

Alasan:
1. File digital tidak membebani database.
2. Skalabilitas lebih baik.
3. Backup dan migrasi file lebih mudah.
4. Kompatibel dengan Laravel filesystem abstraction.

### 5.11 Preview Dokumen
- PDF.js

Peran:
1. Preview PDF langsung di browser.
2. Mendukung tampilan file digital tanpa harus selalu diunduh.

Alasan:
1. Cocok untuk repositori digital kampus.
2. Meningkatkan pengalaman pengguna saat membaca dokumen.

### 5.12 OCR
- Tesseract OCR

Peran:
1. Ekstraksi teks dari scan gambar atau PDF hasil pemindaian.
2. Membantu pengindeksan isi dokumen ke mesin pencarian.

Alasan:
1. Open source.
2. Banyak dipakai.
3. Cocok untuk koleksi digital hasil scan.

### 5.13 Process Control
- Supervisor

Peran:
1. Menjaga worker queue tetap aktif.
2. Menjaga proses background tetap berjalan di server Linux.

Alasan:
1. Umum dipakai pada server Laravel.
2. Stabil untuk worker jangka panjang.

### 5.14 Monitoring dan Debugging Internal
- Laravel Telescope untuk development dan staging
- Laravel Pulse untuk pemantauan aplikasi
- Log file Laravel
- Log Nginx
- Slow query log MySQL

Peran:
1. Monitoring request, query, queue, exception, dan job.
2. Membantu debugging saat pengembangan dan operasional.

### 5.15 Version Control dan Workflow Kode
- Git
- GitHub atau GitLab

Peran:
1. Manajemen source code.
2. Kolaborasi tim.
3. Branching pengembangan.
4. Review kode.
5. Release tagging.

## 6. Stack Pendukung yang Direkomendasikan

### 6.1 Package Laravel Resmi atau Umum
1. laravel/sanctum
2. laravel/horizon
3. laravel/telescope
4. laravel/pulse

### 6.2 Package Komunitas yang Direkomendasikan
1. spatie/laravel-permission
2. spatie/laravel-medialibrary
3. spatie/laravel-activitylog
4. barryvdh/laravel-dompdf bila diperlukan cetak laporan PDF
5. maatwebsite/excel bila diperlukan import dan export Excel

## 7. Stack UI Resmi
Standar UI resmi yang dipakai pada aplikasi ini adalah:

1. Layout dashboard admin berbasis Bootstrap 5.3.
2. Sidebar kiri.
3. Header atas.
4. Content area responsif.
5. Footer sederhana.
6. Datatable dengan fitur search, filter, pagination, dan informasi jumlah data.
7. Form CRUD konsisten.
8. Modal Bootstrap untuk aksi ringan.
9. Halaman detail untuk proses bisnis yang kompleks.
10. OPAC publik responsif untuk desktop dan mobile.

## 8. Matriks Stack Utama

| Layer | Teknologi | Status | Fungsi Utama |
|---|---|---|---|
| Operating System | Ubuntu Server 24.04 LTS | Wajib | Basis server produksi |
| Web Server | Nginx | Wajib | HTTP, HTTPS, reverse proxy |
| Backend Language | PHP 8.4 | Wajib | Menjalankan aplikasi |
| Backend Framework | Laravel 13 | Wajib | Framework utama |
| Frontend Template | Blade | Wajib | Render halaman |
| Reactive Component | Livewire 4 | Wajib | Interaksi dinamis |
| UI Framework | Bootstrap 5.3 | Wajib | Standar antarmuka |
| Asset Builder | Vite | Wajib | Build asset frontend |
| Database | MySQL 8.4 | Wajib | Data utama sistem |
| Storage Engine | InnoDB | Wajib | Engine transaksi utama |
| Cache | Redis | Wajib | Cache aplikasi |
| Queue | Redis Queue | Wajib | Job latar belakang |
| Queue Dashboard | Horizon | Wajib | Monitoring queue |
| Search Engine | Meilisearch | Wajib | Pencarian katalog |
| File Storage | MinIO / S3 Compatible | Direkomendasikan | File digital |
| PDF Viewer | PDF.js | Wajib | Preview PDF |
| OCR | Tesseract OCR | Direkomendasikan | Ekstraksi teks |
| Monitoring | Telescope / Pulse | Direkomendasikan | Debugging dan observasi |
| Process Manager | Supervisor | Wajib | Menjaga worker |

## 9. Alasan Tidak Memilih Stack Lain pada Fase Awal

### 9.1 Tidak Memilih Microservices
Alasan:
1. Tim kecil.
2. Kompleksitas deployment meningkat.
3. Kebutuhan awal kampus belum menuntut pemecahan service.
4. Monitoring dan debugging menjadi lebih sulit.

### 9.2 Tidak Memilih Frontend SPA Penuh
Contoh yang tidak dipilih sebagai default:
1. Vue SPA penuh
2. React SPA penuh
3. Next.js frontend terpisah

Alasan:
1. Menambah kompleksitas project.
2. Memerlukan disiplin API yang lebih berat sejak awal.
3. Tim PHP kecil lebih lambat bila harus memelihara dua codebase besar.
4. Kebutuhan aplikasi kampus masih cocok dengan server side rendering plus komponen reaktif.

### 9.3 Tidak Memilih PostgreSQL Sebagai Default
Alasan:
1. MySQL lebih familier bagi banyak tim kampus.
2. Banyak admin server kampus lebih terbiasa dengan MySQL.
3. Kebutuhan fase awal masih sangat layak ditangani MySQL 8.4.

Catatan:
PostgreSQL tetap dapat dipertimbangkan pada proyek lain atau fase lanjutan bila kebutuhan analitik, text search, atau struktur data lebih kompleks.

## 10. Pola Deployment yang Disarankan

### 10.1 Development
1. Laravel Sail atau Docker Compose lokal
2. MySQL lokal
3. Redis lokal
4. Meilisearch lokal
5. Storage lokal

### 10.2 Staging
1. Server terpisah dari produksi
2. Konfigurasi mendekati produksi
3. Data dummy atau salinan data tersamarkan
4. Digunakan untuk UAT dan verifikasi release

### 10.3 Production
1. Ubuntu Server
2. Nginx
3. PHP-FPM
4. Laravel App
5. MySQL
6. Redis
7. Meilisearch
8. Supervisor
9. MinIO atau storage object setara
10. Backup harian database dan file

## 11. Topologi Server Minimal Produksi
Topologi minimal yang disarankan untuk tahap awal:

1. 1 server aplikasi utama
2. 1 database MySQL
3. 1 Redis instance
4. 1 Meilisearch instance
5. 1 object storage service
6. 1 domain utama aplikasi

Bila sumber daya terbatas, semua komponen dapat ditempatkan pada satu server yang sama pada fase awal dengan catatan:
1. Resource CPU dan RAM cukup
2. Monitoring aktif
3. Backup disiplin
4. Pertumbuhan data dipantau

## 12. Spesifikasi Server Minimal Awal
Spesifikasi minimal awal untuk implementasi kampus skala kecil sampai menengah:

### 12.1 Server All in One Tahap Awal
1. CPU 4 vCPU
2. RAM 8 GB
3. SSD 160 GB
4. Sistem operasi Ubuntu Server 24.04 LTS
5. Backup eksternal harian

### 12.2 Server Rekomendasi Lebih Aman
1. CPU 8 vCPU
2. RAM 16 GB
3. SSD 320 GB atau lebih
4. Storage objek terpisah untuk file digital
5. Snapshot rutin dan backup offsite

## 13. Standar Konfigurasi Aplikasi

### 13.1 Environment Dasar
1. APP_ENV dipisah jelas antara local, staging, production
2. APP_DEBUG hanya aktif pada local dan staging
3. Queue driver menggunakan Redis
4. Cache driver menggunakan Redis
5. Session driver menggunakan Redis
6. Filesystem disk dipisah antara local dan object storage
7. Search index dipisah per environment
8. Mail driver dikonfigurasi sesuai server kampus atau SMTP resmi

### 13.2 Konfigurasi Produksi
1. HTTPS wajib aktif
2. HSTS direkomendasikan
3. Queue worker dijalankan oleh Supervisor
4. Scheduler aktif melalui cron
5. Backup database otomatis
6. Backup file digital otomatis
7. Log rotation aktif

## 14. Standar Keamanan Dasar Stack

1. Semua akses aplikasi produksi wajib melalui HTTPS.
2. Password disimpan dengan hashing bawaan Laravel.
3. CSRF protection aktif.
4. Validation server side wajib pada semua form.
5. Authorization berbasis role dan permission wajib diterapkan.
6. Upload file wajib divalidasi ekstensi, mime type, dan ukuran file.
7. Direktori storage privat tidak boleh diakses publik langsung.
8. File digital terbatas harus diakses melalui mekanisme signed URL atau controller terproteksi.
9. Database tidak dibuka ke internet publik tanpa pembatasan.
10. Redis dan Meilisearch tidak dibuka ke publik tanpa autentikasi dan pembatasan jaringan.
11. Audit log wajib untuk aktivitas penting.

## 15. Standar Performa Dasar

1. Query utama harus menggunakan index yang tepat.
2. Pencarian katalog publik dialihkan ke Meilisearch.
3. Data referensi yang sering dipakai di-cache.
4. Thumbnail dan preview diproses di background job bila berat.
5. OCR dilakukan asynchronous melalui queue.
6. Import data besar dilakukan melalui queue.
7. Pagination wajib pada daftar data besar.
8. Eager loading wajib dipakai untuk mencegah N+1 query.

## 16. Ketentuan Pengembangan Tim
Seluruh tim wajib mematuhi ketentuan berikut:

1. Tidak menambah stack baru tanpa persetujuan arsitek sistem atau analis utama.
2. Tidak membuat modul dengan framework berbeda di dalam codebase yang sama.
3. Tidak membuat frontend kedua yang memecah fokus pengembangan fase awal.
4. Semua integrasi baru harus melalui layer service dan konfigurasi environment.
5. Semua package pihak ketiga harus dicatat dalam dokumen dependency project.

## 17. Rencana Evolusi Stack
Stack ini dirancang untuk bertahan pada fase awal sampai menengah. Evolusi yang diperbolehkan di masa depan meliputi:

1. Menambah SSO kampus.
2. Menambah API eksternal.
3. Memisahkan object storage ke server khusus.
4. Memisahkan Meilisearch ke server khusus.
5. Menambah worker khusus OCR dan indexing.
6. Menambah CDN untuk aset statis bila trafik meningkat.
7. Menambah replikasi database bila kebutuhan bertambah.

## 18. Kesimpulan Teknis
Stack teknologi resmi untuk Sistem Informasi Perpustakaan Hibrid Kampus adalah kombinasi Laravel, PHP, MySQL, Bootstrap, Livewire, Redis, Meilisearch, dan object storage yang dirancang khusus untuk kebutuhan kampus dengan tim pengembang kecil sampai menengah. Stack ini dipilih karena seimbang antara kecepatan pengembangan, kemudahan operasional, kestabilan produksi, dan kesiapan untuk berkembang secara bertahap.

Dokumen ini menjadi acuan resmi untuk seluruh blueprint teknis berikutnya, termasuk arsitektur modular, PRD, SRS, schema database, route map, view map, workflow, dan deployment.

END OF 02_STACK_TEKNOLOGI.md