````markdown id="dep33guide"
# 33_DEPLOYMENT_GUIDE.md

## 1. Nama Dokumen
Deployment Guide Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint panduan deployment aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib persiapan server, instalasi layanan pendukung, konfigurasi environment, proses rilis, verifikasi pasca deploy, rollback, dan operasional deployment

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan panduan deployment resmi PERPUSQU agar proses pemasangan dan rilis aplikasi berjalan konsisten, aman, stabil, dan sesuai dengan arsitektur monolith modular yang telah disepakati. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, administrator sistem, dan reviewer agar tidak ada langkah deploy yang liar, tidak ada layanan pendukung yang terlewat, dan tidak ada inkonsistensi antara aplikasi, database, storage, search, OCR, queue, dan aturan keamanan.

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
31. 31_TEST_PLAN.md
32. 32_TEST_SCENARIO.md

Aturan wajib:
1. Deployment harus mengikuti stack teknologi resmi.
2. Deployment harus mengikuti kebijakan keamanan resmi.
3. Deployment harus menjaga pemisahan data publik dan privat.
4. Deployment harus memastikan seluruh layanan pendukung aktif sebelum aplikasi dipakai.
5. Deployment harus mempertimbangkan rollback.
6. Deployment harus diikuti verifikasi pasca deploy.
7. Deployment tidak boleh mengaktifkan fitur di luar scope fase 1 seolah sudah siap.

## 4. Ruang Lingkup Dokumen
Dokumen ini mencakup:

1. prinsip deployment
2. target environment
3. topologi deployment fase 1
4. kebutuhan sistem operasi dan paket
5. struktur direktori server
6. instalasi layanan pendukung
7. konfigurasi aplikasi Laravel
8. konfigurasi Nginx
9. konfigurasi PHP
10. konfigurasi queue worker
11. konfigurasi search
12. konfigurasi storage
13. konfigurasi OCR
14. proses rilis aplikasi
15. migrasi database
16. seed dan bootstrap awal
17. verifikasi pasca deploy
18. rollback
19. monitoring dasar
20. batasan deployment fase 1

## 5. Prinsip Umum Deployment
Prinsip resmi deployment PERPUSQU adalah:

1. sederhana
2. stabil
3. aman
4. dapat diulang
5. mudah dirollback
6. sesuai kapasitas tim kecil
7. konsisten antar staging dan production
8. tidak bergantung pada langkah manual yang tidak terdokumentasi

## 6. Model Deployment Resmi Fase 1
Model deployment resmi fase 1 adalah:

1. aplikasi web monolith modular Laravel
2. satu codebase
3. satu database utama MySQL
4. layanan pendukung terpisah secara proses atau container, tetapi tetap dalam arsitektur sederhana
5. cocok untuk tim pengembang tidak terlalu besar
6. cocok untuk VPS tunggal atau server kecil menengah

## 7. Target Environment Resmi
Environment resmi yang direkomendasikan:

1. local
2. staging
3. production

### 7.1 Local
Tujuan:
1. pengembangan
2. unit test
3. feature test awal

### 7.2 Staging
Tujuan:
1. integrasi penuh
2. UAT
3. smoke test
4. verifikasi deployment

### 7.3 Production
Tujuan:
1. layanan operasional nyata
2. akses user internal dan publik
3. monitoring stabilitas

Aturan:
1. staging harus semirip mungkin dengan production
2. production wajib lebih ketat secara keamanan dibanding local
3. local boleh memakai layanan ringan, tetapi struktur integrasi tetap sama

## 8. Topologi Deployment Resmi Fase 1
Topologi yang direkomendasikan untuk fase 1:

1. 1 server aplikasi utama
2. Nginx
3. PHP-FPM
4. MySQL
5. Redis
6. Meilisearch
7. queue worker
8. scheduler
9. OCR engine
10. object storage S3 compatible atau MinIO

Pendekatan realistis untuk tim kecil:
1. single VPS dengan beberapa service lokal
2. atau VPS aplikasi ditambah object storage terpisah bila tersedia

## 9. Topologi Minimum Production
Topologi minimum yang direkomendasikan:

### 9.1 Satu Host atau VPS Utama
Menjalankan:
1. Nginx
2. PHP-FPM
3. aplikasi Laravel
4. Redis
5. Meilisearch
6. queue worker
7. scheduler
8. Tesseract OCR
9. MySQL, bila tidak memakai layanan DB terpisah
10. MinIO, bila tidak memakai object storage terpisah

### 9.2 Penyimpanan File
Pilihan:
1. MinIO lokal
2. S3 compatible eksternal

Rekomendasi:
1. fase 1 tetap sah memakai MinIO lokal untuk kesederhanaan
2. bila infrastruktur memungkinkan, object storage terpisah lebih baik

## 10. Sistem Operasi dan Versi Dasar
Sesuai 02_STACK_TEKNOLOGI.md, target utama:

1. Ubuntu Server 24.04 LTS
2. Nginx
3. PHP 8.4
4. Laravel 13
5. MySQL 8.4
6. Redis
7. Meilisearch
8. MinIO atau S3 compatible storage
9. Tesseract OCR

## 11. Spesifikasi Server Minimum yang Direkomendasikan
Spesifikasi minimum realistis untuk fase 1:

### 11.1 Staging
1. 4 vCPU
2. 8 GB RAM
3. SSD 100 GB atau lebih
4. swap aktif sesuai kebijakan admin

### 11.2 Production Awal
1. 4 sampai 8 vCPU
2. 8 sampai 16 GB RAM
3. SSD 150 GB atau lebih
4. ruang penyimpanan file digital sesuai proyeksi aset

Catatan:
1. kebutuhan aktual bergantung pada volume PDF, OCR, dan export
2. jika OCR aktif intensif, RAM dan CPU harus lebih lega

## 12. Kebutuhan Paket Sistem
Paket sistem minimum yang direkomendasikan:

1. git
2. curl
3. unzip
4. zip
5. nginx
6. mysql-server
7. redis-server
8. supervisor
9. tesseract-ocr
10. poppler-utils atau tool PDF yang relevan untuk ekstraksi atau rasterisasi
11. imagemagick atau tool raster yang aman bila dipakai
12. composer
13. nodejs LTS
14. npm
15. php8.4-fpm
16. php8.4-cli
17. php8.4-mysql
18. php8.4-bcmath
19. php8.4-curl
20. php8.4-dom
21. php8.4-fileinfo
22. php8.4-gd
23. php8.4-intl
24. php8.4-mbstring
25. php8.4-xml
26. php8.4-zip
27. php8.4-redis, bila diperlukan
28. certbot, untuk HTTPS bila memakai Let's Encrypt

## 13. Direktori Deployment yang Direkomendasikan
Struktur direktori server yang direkomendasikan:

```text
/var/www/perpusqu
/var/www/perpusqu/releases
/var/www/perpusqu/shared
/var/www/perpusqu/shared/storage
/var/www/perpusqu/shared/bootstrap_cache
/var/www/perpusqu/current
````

Penjelasan:

1. `releases` menyimpan setiap rilis
2. `shared` menyimpan data yang dipakai lintas rilis
3. `current` adalah symlink ke rilis aktif

## 14. Struktur Shared Directory

Direktori shared yang direkomendasikan:

```text
/var/www/perpusqu/shared/.env
/var/www/perpusqu/shared/storage
/var/www/perpusqu/shared/bootstrap_cache
/var/www/perpusqu/shared/public_uploads_optional
```

Aturan:

1. `.env` tidak ikut di repository
2. storage shared wajib konsisten lintas release
3. file cache build tertentu boleh dibangun ulang saat release
4. private file asset bukan berada di folder public aplikasi

## 15. Model Release yang Direkomendasikan

Model release yang direkomendasikan:

1. release directory
2. symlink current
3. rollback dengan ganti symlink

Contoh struktur:

```text
/var/www/perpusqu/releases/20260420-220000
/var/www/perpusqu/current -> /var/www/perpusqu/releases/20260420-220000
```

Keuntungan:

1. rollback lebih cepat
2. file release lama tetap ada sementara
3. deployment lebih terstruktur

## 16. Pengguna Sistem Operasi

Disarankan menggunakan user aplikasi khusus, misalnya:

1. `deploy`
2. `www-data` sebagai web user
3. `mysql`
4. `redis`

Aturan:

1. jangan menjalankan semua proses sebagai root
2. hak akses file harus minimum yang diperlukan
3. deploy user dan web user harus diatur hati hati

## 17. Branch dan Source Release

Sumber release yang direkomendasikan:

1. branch `main` untuk production
2. branch `staging` untuk staging
3. tag release untuk production sangat dianjurkan

Aturan:

1. production release sebaiknya berasal dari commit atau tag yang jelas
2. jangan deploy dari working tree yang kotor
3. setiap release harus bisa ditelusuri ke commit tertentu

## 18. Urutan Umum Deployment

Urutan deployment resmi:

1. siapkan server dan dependency
2. siapkan database
3. siapkan Redis
4. siapkan Meilisearch
5. siapkan storage
6. siapkan OCR engine
7. clone atau tarik source code release
8. pasang dependency composer
9. pasang dependency npm dan build asset
10. link shared file dan folder
11. set environment
12. set permission
13. jalankan migrate
14. jalankan seed awal bila instalasi baru
15. clear dan rebuild cache
16. aktifkan queue worker dan scheduler
17. pindahkan symlink current
18. jalankan verifikasi pasca deploy
19. jalankan smoke test

## 19. Tahap Persiapan Server Awal

Persiapan server awal minimum:

1. update package OS
2. install dependency sistem
3. buat user deploy
4. buat direktori aplikasi
5. atur SSH key
6. atur firewall
7. aktifkan HTTPS
8. nonaktifkan akses publik ke file sensitif

## 20. Persiapan Database

MySQL harus disiapkan dengan:

1. database `perpusqu`
2. user database khusus
3. password kuat
4. hak akses hanya ke database yang diperlukan

Aturan:

1. jangan gunakan root database untuk aplikasi
2. connection string diambil dari `.env`
3. collation dan charset harus konsisten dengan schema

## 21. Persiapan Redis

Redis dipakai untuk:

1. queue
2. cache
3. session, bila dipilih

Aturan:

1. Redis harus aktif sebelum queue dijalankan
2. Redis idealnya dibatasi aksesnya ke localhost atau private network
3. password Redis dianjurkan jika dibuka lintas host

## 22. Persiapan Meilisearch

Meilisearch harus disiapkan untuk:

1. index `opac_records`
2. konfigurasi searchable attributes
3. filterable attributes
4. sortable attributes

Aturan:

1. Meilisearch hanya menerima data yang dibangun dari MySQL
2. key akses Meilisearch harus aman
3. service harus aktif sebelum reindex awal

## 23. Persiapan Storage

Storage harus mendukung:

1. public_assets
2. private_assets
3. temp storage

Aturan:

1. bucket atau namespace publik dan privat harus dipisah
2. private assets tidak boleh public list
3. kredensial storage di `.env`
4. struktur object key mengikuti 22_STORAGE_FILE_POLICY.md

## 24. Persiapan OCR Engine

OCR engine minimal:

1. Tesseract OCR
2. utilitas PDF dan image yang diperlukan proses OCR

Aturan:

1. binary OCR harus dikenali sistem
2. path OCR dapat ditaruh di config atau env
3. worker harus punya hak akses ke temp file OCR
4. file temp harus berada di area yang aman

## 25. Persiapan Mailer Opsional

Jika email opsional diaktifkan:

1. siapkan SMTP atau mail relay
2. set from address resmi
3. uji pengiriman dasar

Catatan:

1. email tidak wajib aktif saat awal go live
2. bila belum aktif, gunakan log mailer atau disable flow email dengan aman

## 26. Persiapan Nginx

Nginx harus dikonfigurasi untuk:

1. domain utama aplikasi admin
2. domain atau subpath OPAC publik, bila satu domain
3. root ke folder `public` release aktif
4. HTTPS
5. block akses file sensitif

## 27. Contoh Server Block Konseptual Nginx

Elemen penting server block:

1. server_name
2. root ke `current/public`
3. index `index.php`
4. try_files ke Laravel front controller
5. location untuk PHP-FPM
6. block file sensitif seperti `.env`, `.git`, `storage`, `composer.json`, `package.json`, `artisan`

Aturan:

1. detail final dapat menyesuaikan server
2. prinsip keamanan wajib dipertahankan

## 28. Persiapan PHP-FPM

PHP-FPM harus disiapkan dengan:

1. memory_limit cukup
2. max_execution_time wajar
3. upload_max_filesize sesuai kebijakan
4. post_max_size sesuai kebijakan
5. OPcache aktif
6. extension yang diperlukan aktif

Nilai dasar yang direkomendasikan:

1. upload_max_filesize minimal 50M atau lebih sesuai asset policy
2. post_max_size sedikit di atas upload_max_filesize
3. max_execution_time cukup untuk request normal, bukan untuk OCR berat
4. OCR berat tetap lewat queue

## 29. Persiapan Node dan Asset Build

Frontend build dibutuhkan untuk:

1. Vite
2. asset CSS dan JS admin
3. asset OPAC publik

Langkah umum:

1. install dependency npm
2. jalankan build production
3. pastikan hasil build tersedia pada release aktif

Aturan:

1. build sebaiknya dilakukan sebelum symlink current diaktifkan
2. file build tidak boleh bergantung pada `.env` publik yang salah

## 30. Composer Install

Composer install harus dilakukan dengan prinsip production safe.

Langkah:

1. `composer install` tanpa dev dependency pada production
2. optimasi autoload
3. verifikasi package sesuai lock file

Aturan:

1. gunakan `composer.lock`
2. jangan update dependency sembarangan saat deploy production
3. dependency harus sudah tervalidasi di staging

## 31. Linking Shared Files

Setelah source release siap, link file shared yang diperlukan.

Yang wajib di-link:

1. `.env`
2. `storage`
3. cache shared bila dipakai sesuai desain

Aturan:

1. link harus dicek sebelum app dijalankan
2. release tidak boleh memakai `.env` dari repository
3. storage shared harus tetap utuh saat ganti release

## 32. Permission File dan Folder

Permission minimum yang direkomendasikan:

1. owner release sesuai deploy user
2. web server user punya hak baca ke source dan tulis hanya ke area yang diperlukan
3. `storage` dan `bootstrap/cache` writable
4. folder lain tidak boleh writable berlebihan

Aturan:

1. jangan set permission 777
2. gunakan least privilege
3. cek hak akses sebelum aplikasi diaktifkan

## 33. Perintah Laravel Pasca Install

Setelah dependency siap, langkah Laravel minimum:

1. `php artisan key:generate` hanya saat instalasi baru jika APP_KEY belum ada
2. `php artisan migrate --force`
3. `php artisan db:seed --force` hanya untuk seed awal yang memang perlu
4. `php artisan config:cache`
5. `php artisan route:cache`
6. `php artisan view:cache`
7. `php artisan event:cache` bila dipakai
8. `php artisan optimize` bila sesuai praktik versi framework
9. `php artisan storage:link` hanya bila memang ada kebutuhan file publik lokal, bukan untuk private asset utama object storage

Catatan:

1. seed awal tidak boleh dijalankan ulang sembarangan di production jika menyebabkan duplikasi
2. seed default role permission awal harus dikendalikan hati hati

## 34. Aturan Migrasi Database

Migrasi database harus:

1. dijalankan setelah backup
2. diuji di staging dulu
3. tidak merusak data existing
4. memakai `--force` pada production non interactive
5. sinkron dengan schema blueprint resmi

Aturan:

1. migration destruktif harus sangat hati hati
2. perubahan besar perlu rencana rollback
3. setiap release harus tahu migration mana yang dijalankan

## 35. Aturan Seed Data

Seed data production dibagi menjadi:

### 35.1 Initial Seed

Boleh dijalankan saat instalasi awal:

1. roles
2. permissions
3. super admin awal
4. master data default
5. system settings default

### 35.2 Incremental Seed

Harus hati hati:

1. hanya untuk data referensi tambahan yang aman
2. jangan mengganggu data operasional existing

Aturan:

1. seed bukan alat koreksi data manual harian
2. perubahan data reference sebaiknya terkontrol

## 36. Persiapan Queue Worker

Queue worker wajib aktif untuk:

1. OCR
2. reindex
3. export async, bila dipakai
4. email opsional async, bila dipakai
5. housekeeping tertentu, bila diaktifkan

Rekomendasi:

1. pakai Supervisor atau systemd
2. worker harus auto restart
3. log worker harus tersedia

## 37. Supervisor untuk Queue Worker

Struktur proses yang direkomendasikan:

1. `perpusqu-worker-default`
2. `perpusqu-worker-heavy`, opsional untuk OCR berat
3. `perpusqu-horizon`, bila Horizon dipakai sebagai pengelola utama

Aturan:

1. jumlah worker disesuaikan resource server
2. OCR tidak boleh memonopoli semua worker jika queue lain penting
3. monitoring worker harus aktif

## 38. Persiapan Scheduler

Laravel scheduler wajib aktif untuk:

1. housekeeping temp files
2. cache refresh tertentu
3. report cleanup opsional
4. maintenance tasks lainnya

Rekomendasi:

1. cron menjalankan `php artisan schedule:run` setiap menit
2. scheduler log harus tersedia

## 39. Persiapan Horizon

Jika Laravel Horizon digunakan:

1. Redis harus aktif
2. Horizon config harus sesuai queue yang dipakai
3. route dashboard Horizon harus dilindungi akses
4. environment production harus membatasi akses dashboard

Aturan:

1. dashboard Horizon hanya untuk admin berwenang
2. jangan buka dashboard monitoring ke publik

## 40. Persiapan Telescope dan Pulse

Jika dipakai:

1. aktifkan terbatas untuk environment yang sesuai
2. akses harus dibatasi role yang benar
3. production harus hati hati agar tidak membebani sistem atau membocorkan data

Rekomendasi:

1. Telescope aman dipakai di local atau staging
2. production hanya jika benar benar diperlukan dan sudah dibatasi
3. Pulse dapat dipakai untuk monitoring ringan jika resource cukup

## 41. Inisialisasi Search

Setelah aplikasi aktif dan Meilisearch siap, langkah awal:

1. sinkronkan search settings
2. jalankan full reindex publik
3. verifikasi jumlah record index
4. uji query publik dasar

Command konseptual:

1. `php artisan perpusqu:search:sync-settings`
2. `php artisan perpusqu:search:reindex-public-records`

Aturan:

1. jalankan setelah migration dan seed relevan selesai
2. search publik tidak dianggap siap sebelum reindex awal selesai

## 42. Inisialisasi Storage Buckets

Storage harus memiliki bucket atau namespace yang sesuai:

1. public assets
2. private assets
3. temp area, bila diperlukan

Aturan:

1. object path structure harus sesuai kebijakan storage
2. private bucket tidak boleh public
3. uji upload dan read path dasar sebelum go live

## 43. Inisialisasi OCR Readiness

Sebelum go live, uji OCR readiness:

1. cek Tesseract terpasang
2. cek tool PDF to image yang dipakai
3. cek worker bisa membaca asset dari private storage
4. cek temp directory writable
5. cek OCR sample berjalan

## 44. Health Check Minimum Sebelum Aktivasi

Sebelum release aktif, cek:

1. app boots normal
2. database connect
3. Redis connect
4. Meilisearch connect
5. storage connect
6. OCR dependency terdeteksi
7. queue worker berjalan
8. scheduler aktif
9. domain dan HTTPS aktif
10. admin login berhasil

## 45. Verifikasi Pasca Deploy Wajib

Setelah deploy, lakukan verifikasi minimum:

1. halaman login terbuka
2. login admin berhasil
3. dashboard tampil
4. OPAC beranda tampil
5. OPAC search bekerja
6. create master data dasar bekerja
7. create record bekerja
8. upload asset bekerja
9. OCR dispatch bekerja
10. queue worker memproses job
11. report dashboard tampil
12. export laporan dasar bekerja
13. audit log terbentuk
14. asset privat tidak bisa diakses publik

## 46. Smoke Test Pasca Deploy

Smoke test minimum pasca deploy wajib mencakup:

1. login
2. dashboard
3. create bibliographic record
4. create member
5. create item
6. loan
7. return
8. upload digital asset
9. OPAC search
10. report open
11. audit log open

Catatan:

1. detail checklist akan ditulis pada 45_SMOKE_TEST_CHECKLIST.md
2. deployment dianggap belum selesai sebelum smoke test lulus

## 47. Langkah Deploy Release Baru

Urutan deploy release baru yang direkomendasikan:

1. backup database dan data kritis
2. ambil source release baru
3. install dependency
4. build asset frontend
5. link shared resources
6. jalankan migration
7. clear dan build cache
8. restart atau reload PHP-FPM bila perlu
9. restart queue worker atau horizon
10. pindahkan symlink current
11. jalankan health check
12. jalankan smoke test
13. monitor log dan worker

## 48. Restart Service yang Direkomendasikan

Setelah release baru, layanan yang mungkin perlu reload atau restart:

1. php-fpm
2. queue worker atau horizon
3. scheduler tidak perlu restart bila hanya cron normal
4. nginx hanya jika config berubah
5. Meilisearch tidak perlu restart bila tidak ada perubahan layanan
6. MinIO tidak perlu restart bila tidak ada perubahan layanan

## 49. Clear Cache dan Warmup

Langkah cache yang direkomendasikan:

1. clear config cache lama jika perlu
2. cache ulang config
3. cache route
4. cache view
5. warmup halaman penting secara ringan, opsional

Aturan:

1. cache harus sejalan dengan env aktif
2. jangan pakai cache lama yang tidak sinkron dengan release baru

## 50. Rollback Strategy

Rollback harus disiapkan sebelum deploy.

Strategi rollback resmi:

1. kembalikan symlink `current` ke release sebelumnya
2. rollback service restart bila perlu
3. rollback database hanya jika aman dan memang diperlukan
4. rollback file data harus sangat hati hati

Aturan:

1. rollback code lebih mudah daripada rollback data
2. migration destruktif harus punya rencana tersendiri
3. sebelum rollback data, evaluasi konsekuensi operasional

## 51. Kapan Rollback Harus Dipertimbangkan

Rollback dipertimbangkan jika:

1. aplikasi tidak bisa login
2. route utama rusak
3. migration menyebabkan kerusakan signifikan
4. OPAC publik gagal total
5. file privat bocor atau akses tidak terkendali
6. queue failure mengganggu fungsi inti secara berat
7. smoke test P1 gagal berat

## 52. Langkah Rollback Konseptual

Langkah rollback yang direkomendasikan:

1. nonaktifkan akses write sementara jika perlu
2. catat release yang gagal
3. kembalikan symlink current ke release stabil terakhir
4. reload php-fpm
5. restart worker bila perlu
6. verifikasi aplikasi
7. evaluasi apakah migration perlu tindakan lanjut
8. dokumentasikan insiden

## 53. Monitoring Pasca Deploy

Monitoring minimum pasca deploy:

1. Nginx error log
2. PHP application log
3. queue worker status
4. Horizon dashboard, bila aktif
5. Redis health
6. Meilisearch health
7. storage access test
8. MySQL error dan load
9. OCR process sample
10. disk usage

## 54. Logging yang Wajib Dicek Pasca Deploy

Periksa:

1. application log
2. queue failure log
3. OCR failure log
4. Nginx error log
5. PHP-FPM log
6. Supervisor log
7. export failure log, bila ada
8. import failure log, bila ada

## 55. Housekeeping Pasca Deploy

Housekeeping yang direkomendasikan:

1. hapus release lama secara bertahap
2. pertahankan beberapa release terakhir
3. pastikan temp files tidak menumpuk
4. periksa disk usage storage dan log
5. periksa worker zombie atau gagal

Rekomendasi:

1. simpan minimal 3 sampai 5 release terakhir
2. hapus release sangat lama setelah verifikasi stabil

## 56. Deployment Staging

Staging harus mengikuti production semirip mungkin.

Aturan:

1. domain staging terpisah
2. database staging terpisah
3. storage staging terpisah
4. search index staging terpisah
5. mail staging tidak boleh mengirim ke alamat nyata tanpa kontrol
6. data nyata sensitif jangan dipakai sembarangan

## 57. Deployment Production

Production wajib:

1. HTTPS aktif
2. debug off
3. env aman
4. worker aktif
5. search aktif
6. storage aman
7. OCR dependency aktif
8. backup tersedia
9. smoke test dilakukan
10. admin akses monitoring terbatas

## 58. Deployment dengan Downtime Minimal

Untuk tim kecil, pendekatan downtime minimal yang direkomendasikan:

1. build release di direktori baru
2. lakukan migration saat waktu aman
3. pindahkan symlink secepat mungkin
4. reload service yang perlu
5. jalankan smoke test

Catatan:

1. ini bukan zero downtime penuh
2. tetapi cukup realistis dan aman untuk fase 1

## 59. Kebijakan Keamanan Saat Deployment

Deployment wajib mengikuti 28_SECURITY_POLICY.md.

Aturan:

1. jangan commit `.env`
2. jangan tampilkan secrets di log deploy
3. jangan deploy dengan APP_DEBUG aktif di production
4. jangan buka dashboard Horizon atau Telescope ke publik
5. batasi akses SSH
6. gunakan user deploy terpisah
7. lindungi backup dan export files

## 60. Kebijakan Database Saat Deployment

Aturan:

1. backup sebelum migrate
2. migration harus diuji di staging
3. schema harus sinkron dengan blueprint
4. query perubahan data manual harus dihindari kecuali terdokumentasi
5. seed awal hanya saat perlu

## 61. Kebijakan Storage Saat Deployment

Aturan:

1. cek bucket atau endpoint aktif
2. cek permission write dan read
3. cek private dan public separation
4. cek sample upload dan sample preview
5. jangan menghapus data private lama tanpa rencana

## 62. Kebijakan Search Saat Deployment

Aturan:

1. sync settings setelah deploy bila diperlukan
2. jalankan reindex awal jika schema index berubah
3. verifikasi hasil search publik
4. jika search engine gagal, data inti MySQL tetap aman tetapi aplikasi belum dianggap sepenuhnya siap

## 63. Kebijakan OCR Saat Deployment

Aturan:

1. verifikasi binary OCR
2. verifikasi worker OCR
3. verifikasi temp path
4. jalankan sample OCR
5. pantau failure awal

## 64. Kebijakan Import Export Saat Deployment

Aturan:

1. uji import template dasar
2. uji export laporan dasar
3. pastikan temp import dan export path tersedia
4. pastikan file hasil tidak bocor ke publik
5. audit export dan import sample harus muncul

## 65. Kebijakan Audit dan Error Saat Deployment

Aturan:

1. audit log harus aktif
2. error code mapping harus aman
3. error page tidak boleh membocorkan stack trace
4. action sensitif sample harus tercatat di audit

## 66. Kriteria Siap Go Live dari Sudut Deployment

Sistem layak go live bila:

1. seluruh layanan pendukung aktif
2. env production benar
3. migration sukses
4. search siap
5. storage siap
6. OCR siap
7. queue siap
8. smoke test lulus
9. defect blocker deployment = 0
10. akses publik dan internal tervalidasi

## 67. Defect Deployment yang Bersifat Blocker

Deployment dianggap gagal jika terjadi:

1. admin tidak bisa login
2. route inti rusak
3. database tidak terkoneksi
4. storage tidak bisa read atau write
5. search publik gagal total
6. asset privat bocor
7. OCR worker gagal start
8. queue tidak berjalan
9. export inti gagal total
10. audit log tidak terbentuk pada aksi sensitif sample

## 68. Artefak Deployment yang Harus Terdokumentasi

Artefak minimum:

1. release id atau tag
2. commit hash
3. waktu deploy
4. operator deploy
5. hasil migrate
6. hasil smoke test
7. catatan issue
8. keputusan rollback atau lanjut
9. daftar service yang direstart

## 69. Alat Operasional yang Direkomendasikan

Alat bantu yang direkomendasikan:

1. Git
2. Composer
3. Node dan npm
4. Supervisor
5. systemctl
6. cron
7. certbot
8. MySQL client
9. Redis CLI
10. Meilisearch health endpoint
11. MinIO client atau tool storage lain
12. log viewer yang aman

## 70. Batasan Deployment Fase 1

Fase 1 belum mewajibkan:

1. Kubernetes
2. Docker Swarm
3. CI/CD penuh otomatis
4. blue green deployment penuh
5. canary deployment
6. multi region
7. load balancer kompleks
8. auto scaling
9. centralized observability stack enterprise

Catatan:

1. deployment fase 1 sengaja sederhana dan realistis
2. kesederhanaan tidak boleh mengorbankan keamanan dasar dan kestabilan

## 71. Skenario Uji Deploy Minimum

### 71.1 Skenario A

Fresh install staging dari nol

Verifikasi:

1. install berhasil
2. migrate berhasil
3. seed berhasil
4. login berhasil
5. OPAC tampil
6. queue aktif
7. search aktif

### 71.2 Skenario B

Deploy release update ke staging

Verifikasi:

1. release baru aktif
2. data lama tetap aman
3. migrate berhasil
4. worker tetap jalan
5. smoke test lulus

### 71.3 Skenario C

Rollback ke release sebelumnya

Verifikasi:

1. current kembali ke release lama
2. aplikasi kembali normal
3. data kritis aman
4. insiden terdokumentasi

## 72. Testing Requirement Deployment

Pengujian deployment minimum wajib mencakup:

1. server dependency lengkap
2. env valid
3. database connect
4. redis connect
5. meilisearch connect
6. storage connect
7. OCR dependency terdeteksi
8. queue worker aktif
9. scheduler aktif
10. login admin berhasil
11. OPAC publik bekerja
12. upload asset bekerja
13. OCR dispatch bekerja
14. export report bekerja
15. audit log sample terbentuk
16. private asset tidak bocor

## 73. Hubungan dengan Dokumen Lanjutan

Dokumen ini sangat bergantung dan akan dilengkapi oleh:

1. 34_ENV_CONFIGURATION.md
2. 35_BACKUP_AND_RECOVERY.md
3. 36_PERFORMANCE_GUIDE.md
4. 45_SMOKE_TEST_CHECKLIST.md
5. 46_UAT_CHECKLIST.md

Hubungan:

1. Deployment Guide menjelaskan cara rilis
2. Env Configuration menjelaskan semua variabel environment
3. Backup and Recovery menjelaskan cadangan dan pemulihan
4. Smoke Test Checklist memverifikasi hasil deploy
5. UAT Checklist memverifikasi kelayakan operasional

## 74. Prioritas Implementasi Dokumen Turunan

Setelah dokumen ini, yang paling penting ditulis adalah:

1. 34_ENV_CONFIGURATION.md
2. 35_BACKUP_AND_RECOVERY.md
3. 45_SMOKE_TEST_CHECKLIST.md

## 75. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 34_ENV_CONFIGURATION.md
2. 35_BACKUP_AND_RECOVERY.md
3. 36_PERFORMANCE_GUIDE.md
4. 37_CODING_STANDARD.md
5. 38_TREE.md
6. 39_TRACEABILITY_MATRIX.md
7. 41_BACKEND_CHECKLIST.md
8. 42_FRONTEND_CHECKLIST.md
9. 45_SMOKE_TEST_CHECKLIST.md
10. 46_UAT_CHECKLIST.md

Aturan:

1. Env Configuration harus menurunkan semua dependency deploy dari dokumen ini
2. Backup and Recovery harus melengkapi prosedur pra dan pasca deploy
3. Performance Guide harus mempertimbangkan topologi dan service yang dipasang
4. Checklists harus memakai deployment flow ini sebagai referensi

## 76. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. stack deployment sesuai dokumen teknologi
2. layanan pendukung yang diwajibkan sudah lengkap
3. model release dan rollback jelas
4. security deployment sudah diperhitungkan
5. search, storage, OCR, queue, dan reporting sudah masuk alur deploy
6. verifikasi pasca deploy dan smoke test sudah didefinisikan
7. batasan fase 1 tetap realistis

## 77. Kesimpulan

Dokumen Deployment Guide ini menetapkan panduan deployment resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 32. Dokumen ini memastikan bahwa instalasi server, konfigurasi layanan pendukung, rilis aplikasi, migrasi database, aktivasi queue, search, storage, OCR, verifikasi pasca deploy, dan rollback dilakukan secara tertib, aman, dan sesuai kebutuhan operasional fase 1. Semua deployment PERPUSQU wajib merujuk dokumen ini.

END OF 33_DEPLOYMENT_GUIDE.md
