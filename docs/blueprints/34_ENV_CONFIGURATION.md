# 34_ENV_CONFIGURATION.md

## 1. Nama Dokumen

Environment Configuration Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint konfigurasi environment aplikasi

### 2.3 Status Dokumen

Resmi, acuan wajib penetapan variabel environment, konfigurasi aplikasi, konfigurasi layanan pendukung, dan pengelolaan secret untuk seluruh environment PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan konfigurasi environment resmi PERPUSQU agar seluruh komponen aplikasi, database, queue, search, storage, OCR, email, logging, dan security dapat berjalan konsisten pada local, staging, dan production. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, administrator sistem, dan reviewer agar tidak ada variabel penting yang terlewat, tidak ada secret yang ditulis di source code, dan tidak ada ketidaksesuaian konfigurasi antara deployment, security policy, storage policy, search indexing, OCR, reporting, dan layanan pendukung lain.

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
33. 33_DEPLOYMENT_GUIDE.md

Aturan wajib:

1. Semua konfigurasi sensitif wajib berasal dari environment file atau secret manager, bukan hardcoded.
2. Konfigurasi harus dibedakan jelas antara local, staging, dan production.
3. APP_DEBUG tidak boleh aktif di production.
4. Secret tidak boleh masuk repository.
5. Semua layanan pendukung yang diwajibkan blueprint harus punya variabel environment yang jelas.
6. Konfigurasi environment harus mendukung prinsip least privilege dan secure by default.
7. Semua perubahan penting pada environment production harus terdokumentasi.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip umum konfigurasi environment
2. klasifikasi variabel environment
3. struktur file konfigurasi
4. variabel inti aplikasi
5. variabel database
6. variabel cache, session, dan queue
7. variabel Redis
8. variabel Meilisearch
9. variabel storage
10. variabel OCR
11. variabel mail
12. variabel logging dan monitoring
13. variabel reporting, export, dan import
14. variabel feature toggle yang diizinkan
15. perbedaan local, staging, dan production
16. validasi konfigurasi
17. security rule untuk secret
18. contoh template `.env`

## 5. Prinsip Umum Konfigurasi Environment

Prinsip resmi konfigurasi environment PERPUSQU adalah:

1. terpusat
2. terdokumentasi
3. aman
4. konsisten
5. mudah diaudit
6. tidak ambigu
7. mudah dipindahkan antar environment
8. tidak bergantung pada nilai tersembunyi di source code

## 6. Sasaran Konfigurasi Environment

Sasaran utama dokumen ini adalah:

1. memastikan aplikasi dapat boot dengan konfigurasi yang benar
2. memastikan semua layanan pendukung dapat terkoneksi
3. memastikan semua fitur fase 1 memiliki pengaturan minimum yang jelas
4. memastikan security policy bisa diterapkan melalui environment
5. memastikan deployment dan rollback dapat dilakukan terukur

## 7. Klasifikasi Variabel Environment

Variabel environment dibagi menjadi:

1. mandatory global
2. mandatory conditional
3. optional controlled
4. local only helper
5. secret
6. non secret config

### 7.1 Mandatory Global

Wajib ada di semua environment.

Contoh:

1. APP_NAME
2. APP_ENV
3. APP_KEY
4. APP_URL
5. DB_CONNECTION
6. DB_HOST
7. DB_PORT
8. DB_DATABASE
9. DB_USERNAME
10. DB_PASSWORD

### 7.2 Mandatory Conditional

Wajib ada bila fitur atau layanan terkait diaktifkan.

Contoh:

1. MAIL_HOST bila mail aktif
2. MEILISEARCH_HOST bila search aktif
3. AWS_ACCESS_KEY_ID bila storage S3 compatible aktif
4. OCR_BINARY_PATH bila OCR path tidak otomatis terdeteksi

### 7.3 Optional Controlled

Boleh ada untuk tuning atau mode tertentu.

Contoh:

1. APP_TIMEZONE
2. LOG_LEVEL
3. OCR_MAX_PAGES_PER_BATCH
4. REPORT_EXPORT_ASYNC

### 7.4 Local Only Helper

Boleh hanya ada di local.

Contoh:

1. DEV_MAIL_TO
2. DEBUGBAR_ENABLED, bila suatu saat dipakai
3. VITE_DEV_SERVER_URL, bila diperlukan

### 7.5 Secret

Wajib dirahasiakan.

Contoh:

1. APP_KEY
2. DB_PASSWORD
3. REDIS_PASSWORD
4. MEILISEARCH_KEY
5. AWS_SECRET_ACCESS_KEY
6. MAIL_PASSWORD

### 7.6 Non Secret Config

Boleh diketahui admin dan developer berwenang.

Contoh:

1. APP_NAME
2. APP_URL
3. APP_LOCALE
4. DB_PORT
5. REDIS_PORT
6. OCR_ENABLED

## 8. File Konfigurasi yang Direkomendasikan

Struktur file yang direkomendasikan:

1. `.env.example`
2. `.env.local`, opsional sesuai kebijakan
3. `.env.staging`
4. `.env.production`, tidak disimpan di repository
5. config Laravel sesuai domain

Aturan:

1. `.env.example` hanya memuat placeholder aman
2. `.env.production` hanya ada di server production
3. `.env.staging` hanya ada di staging
4. source code tidak boleh mengandung secret default nyata

## 9. Aturan `.env.example`

`.env.example` wajib:

1. lengkap
2. tidak mengandung secret nyata
3. mencerminkan seluruh variabel penting fase 1
4. punya placeholder yang jelas
5. membantu bootstrap local dan staging

## 10. Aturan Secret Management

Secret wajib:

1. tidak di-commit ke repository
2. tidak dibagikan lewat chat biasa
3. tidak disimpan di kode sumber
4. tidak disimpan di dokumentasi publik
5. dibatasi aksesnya ke administrator dan pihak berwenang

Rekomendasi:

1. production secret dikelola oleh admin sistem
2. staging secret dipisahkan dari production
3. local secret memakai nilai berbeda dari production

## 11. Variabel Inti Aplikasi

Variabel inti aplikasi minimum:

1. APP_NAME
2. APP_ENV
3. APP_KEY
4. APP_DEBUG
5. APP_URL
6. APP_TIMEZONE
7. APP_LOCALE
8. APP_FALLBACK_LOCALE
9. APP_MAINTENANCE_DRIVER, bila dipakai
10. APP_FORCE_HTTPS, variabel kustom yang direkomendasikan

### 11.1 APP_NAME

Nilai rekomendasi:
`PERPUSQU`

Fungsi:
nama aplikasi untuk UI, mail, dan log.

### 11.2 APP_ENV

Nilai yang diizinkan:

1. local
2. staging
3. production

### 11.3 APP_KEY

Wajib:

1. unik
2. rahasia
3. tidak berubah sembarangan setelah sistem aktif

### 11.4 APP_DEBUG

Aturan:

1. local boleh true
2. staging disarankan false atau sangat terbatas
3. production wajib false

### 11.5 APP_URL

Contoh:

1. `http://perpusqu.local`
2. `https://staging-perpusqu.domain.tld`
3. `https://perpusqu.domain.tld`

### 11.6 APP_TIMEZONE

Nilai rekomendasi:
`Asia/Makassar` atau timezone operasional yang diputuskan resmi

Catatan:

1. karena konteks pengguna berada di wilayah WITA, timezone sistem disarankan konsisten dengan operasional lokal
2. bila institusi menetapkan timezone lain, semua logika tanggal harus menyesuaikan secara resmi

### 11.7 APP_LOCALE

Nilai rekomendasi:
`id`

### 11.8 APP_FALLBACK_LOCALE

Nilai rekomendasi:
`en`

### 11.9 APP_FORCE_HTTPS

Nilai yang direkomendasikan:

1. true di production
2. true di staging bila HTTPS sudah aktif
3. false di local bila belum memakai TLS

## 12. Variabel Logging Dasar

Variabel minimum:

1. LOG_CHANNEL
2. LOG_LEVEL
3. LOG_STACK
4. LOG_DAILY_DAYS, bila memakai channel daily

Nilai rekomendasi:

1. local: stack atau single
2. staging: daily
3. production: daily

Aturan:

1. LOG_LEVEL local boleh debug
2. LOG_LEVEL staging info atau warning
3. LOG_LEVEL production info atau warning
4. data sensitif tidak boleh sengaja dimasukkan ke log

## 13. Variabel Database

Variabel minimum:

1. DB_CONNECTION
2. DB_HOST
3. DB_PORT
4. DB_DATABASE
5. DB_USERNAME
6. DB_PASSWORD
7. DB_CHARSET
8. DB_COLLATION

Nilai rekomendasi:

1. DB_CONNECTION = mysql
2. DB_CHARSET = utf8mb4
3. DB_COLLATION = utf8mb4_unicode_ci

Aturan:

1. gunakan user database khusus aplikasi
2. jangan gunakan root database
3. password database wajib kuat
4. local, staging, dan production wajib memakai database berbeda

## 14. Variabel Redis

Variabel minimum:

1. REDIS_CLIENT
2. REDIS_HOST
3. REDIS_PASSWORD
4. REDIS_PORT
5. REDIS_CACHE_DB
6. REDIS_QUEUE_DB
7. REDIS_SESSION_DB, bila session di Redis

Nilai rekomendasi:

1. REDIS_CLIENT = phpredis atau predis sesuai stack
2. REDIS_HOST = 127.0.0.1 atau host private
3. REDIS_PORT = 6379

Aturan:

1. pemisahan database index Redis sangat dianjurkan
2. password Redis dianjurkan di staging dan production
3. Redis tidak boleh dibuka publik tanpa proteksi

## 15. Variabel Cache

Variabel minimum:

1. CACHE_STORE atau CACHE_DRIVER sesuai versi framework
2. CACHE_PREFIX

Nilai rekomendasi:

1. staging dan production menggunakan Redis
2. prefix unik per environment, misalnya `perpusqu_prod_`

Contoh:

1. `CACHE_PREFIX=perpusqu_local_`
2. `CACHE_PREFIX=perpusqu_staging_`
3. `CACHE_PREFIX=perpusqu_prod_`

## 16. Variabel Session

Variabel minimum:

1. SESSION_DRIVER
2. SESSION_LIFETIME
3. SESSION_ENCRYPT, bila dipakai
4. SESSION_DOMAIN
5. SESSION_SECURE_COOKIE
6. SESSION_SAME_SITE

Nilai rekomendasi:

1. local boleh file atau redis
2. staging dan production disarankan redis
3. SESSION_SECURE_COOKIE = true pada HTTPS
4. SESSION_SAME_SITE = lax, kecuali ada kebutuhan lain

## 17. Variabel Queue

Variabel minimum:

1. QUEUE_CONNECTION
2. QUEUE_FAILED_DRIVER, bila digunakan
3. QUEUE_DEFAULT_TIMEOUT, variabel kustom yang direkomendasikan
4. QUEUE_HEAVY_TIMEOUT, variabel kustom yang direkomendasikan untuk OCR

Nilai rekomendasi:

1. production dan staging memakai redis
2. local boleh sync untuk development ringan, tetapi redis tetap dianjurkan untuk uji OCR dan indexing

Aturan:

1. sync tidak cocok untuk pengujian OCR berat
2. timeout OCR harus lebih longgar dari timeout request web biasa

## 18. Variabel Broadcast

Karena fase 1 tidak mewajibkan broadcast realtime, variabel ini opsional.

Jika tidak dipakai:

1. gunakan default aman
2. tidak perlu aktivasi layanan tambahan

## 19. Variabel Meilisearch

Variabel minimum:

1. MEILISEARCH_HOST
2. MEILISEARCH_KEY
3. SEARCH_DRIVER, variabel kustom yang direkomendasikan
4. SEARCH_INDEX_PUBLIC_RECORDS, variabel kustom yang direkomendasikan

Nilai rekomendasi:

1. SEARCH_DRIVER = meilisearch
2. SEARCH_INDEX_PUBLIC_RECORDS = opac_records

Aturan:

1. MEILISEARCH_KEY dianggap secret
2. host search lokal dan production harus dipisah
3. index production tidak boleh dipakai oleh staging

## 20. Variabel Storage

Karena fase 1 memakai object storage S3 compatible atau MinIO, variabel minimum:

1. FILESYSTEM_DISK atau FILESYSTEM_DRIVER sesuai versi framework
2. AWS_ACCESS_KEY_ID
3. AWS_SECRET_ACCESS_KEY
4. AWS_DEFAULT_REGION
5. AWS_BUCKET
6. AWS_ENDPOINT
7. AWS_USE_PATH_STYLE_ENDPOINT
8. STORAGE_PUBLIC_BUCKET, variabel kustom opsional
9. STORAGE_PRIVATE_BUCKET, variabel kustom opsional
10. STORAGE_TEMP_PREFIX, variabel kustom opsional

Aturan:

1. public dan private asset harus dipisah secara logis
2. endpoint local MinIO berbeda dari production
3. secret storage tidak boleh bocor ke log atau UI

## 21. Variabel Disk Publik dan Privat yang Direkomendasikan

Untuk mendukung dua disk utama, direkomendasikan variabel kustom:

1. STORAGE_PUBLIC_DISK=public_assets
2. STORAGE_PRIVATE_DISK=private_assets
3. STORAGE_TEMP_DISK=local_temp

Jika implementasi memakai satu bucket dengan prefix berbeda:

1. tetap dokumentasikan prefix publik dan privat
2. access control tetap wajib dipisah di level aplikasi dan storage policy

## 22. Variabel OCR

Variabel minimum OCR yang direkomendasikan:

1. OCR_ENABLED
2. OCR_BINARY_PATH
3. OCR_TEMP_PATH
4. OCR_DEFAULT_MODE
5. OCR_MAX_PAGES_PER_BATCH
6. OCR_RETRY_ATTEMPTS
7. OCR_MINIMUM_TEXT_THRESHOLD
8. OCR_PDF_TO_IMAGE_BINARY, bila perlu
9. OCR_IMAGE_DPI, bila diatur

Nilai rekomendasi:

1. OCR_ENABLED = true pada staging dan production bila fitur diaktifkan
2. OCR_DEFAULT_MODE = queue
3. OCR_TEMP_PATH mengarah ke direktori temp aman

Aturan:

1. binary path harus divalidasi saat deploy
2. temp path harus writable
3. nilai tuning OCR tidak boleh melampaui kapasitas server tanpa uji

## 23. Variabel Import Export

Variabel import export yang direkomendasikan:

1. MEMBER_IMPORT_MAX_FILE_MB
2. REPORT_EXPORT_DEFAULT_FORMAT
3. REPORT_EXPORT_ASYNC
4. REPORT_EXPORT_TEMP_PATH
5. REPORT_EXPORT_RETENTION_HOURS

Nilai rekomendasi:

1. MEMBER_IMPORT_MAX_FILE_MB = 10
2. REPORT_EXPORT_DEFAULT_FORMAT = xlsx
3. REPORT_EXPORT_ASYNC = false untuk awal bila volume kecil
4. REPORT_EXPORT_RETENTION_HOURS diisi sesuai kebijakan housekeeping

## 24. Variabel Upload dan File Policy

Variabel file penting yang direkomendasikan:

1. LIBRARY_LOGO_MAX_MB
2. LIBRARY_COVER_MAX_MB
3. LIBRARY_ASSET_MAX_MB
4. LIBRARY_ALLOWED_IMAGE_EXTENSIONS
5. LIBRARY_ALLOWED_ASSET_EXTENSIONS
6. LIBRARY_ALLOWED_ASSET_MIME_TYPES

Nilai rekomendasi:

1. LIBRARY_LOGO_MAX_MB = 2
2. LIBRARY_COVER_MAX_MB = 4
3. LIBRARY_ASSET_MAX_MB = 50
4. asset extension fokus ke pdf
5. mime type asset fokus ke application/pdf

## 25. Variabel Circulation Policy

Variabel circulation yang direkomendasikan:

1. LOAN_DEFAULT_DAYS
2. LOAN_MAX_ACTIVE_LOANS
3. LOAN_MAX_RENEWAL_COUNT
4. LOAN_ALLOW_RENEWAL
5. LOAN_REQUIRE_ACTIVE_MEMBER
6. LOAN_REQUIRE_UNBLOCKED_MEMBER
7. FINE_DAILY_AMOUNT

Catatan:

1. secara domain resmi, nilai operasional utama berada di system settings
2. environment variable hanya dipakai untuk bootstrap awal, fallback aman, atau seed default
3. setelah sistem aktif, source of truth operasional tetap system settings di database

## 26. Variabel Mail

Variabel minimum bila mail aktif:

1. MAIL_MAILER
2. MAIL_HOST
3. MAIL_PORT
4. MAIL_USERNAME
5. MAIL_PASSWORD
6. MAIL_ENCRYPTION
7. MAIL_FROM_ADDRESS
8. MAIL_FROM_NAME

Nilai rekomendasi:

1. local dapat memakai log atau smtp sandbox
2. staging memakai sandbox atau test mail relay
3. production memakai SMTP resmi institusi atau relay terpercaya

Aturan:

1. MAIL_PASSWORD rahasia
2. alamat from harus konsisten
3. mail tidak wajib aktif pada awal go live jika belum siap

## 27. Variabel Horizon dan Monitoring

Variabel yang direkomendasikan:

1. HORIZON_ENABLED
2. HORIZON_AUTH_MODE, variabel kustom opsional
3. PULSE_ENABLED
4. TELESCOPE_ENABLED

Aturan:

1. Horizon boleh aktif pada staging dan production
2. Telescope production harus sangat dibatasi atau nonaktif
3. Pulse boleh aktif jika resource server memadai
4. monitoring dashboard tidak boleh publik

## 28. Variabel Frontend Build

Variabel build yang direkomendasikan:

1. VITE_APP_NAME
2. VITE_APP_URL
3. VITE_OPAC_PUBLIC_NAME, bila diperlukan
4. VITE_ENABLE_PUBLIC_SUGGESTION, bila diperlukan

Aturan:

1. jangan menaruh secret di variabel VITE
2. semua variabel VITE dianggap akan sampai ke frontend

## 29. Variabel Security Hardening

Variabel keamanan kustom yang direkomendasikan:

1. SECURITY_FORCE_HTTPS
2. SECURITY_ENABLE_HSTS
3. SECURITY_ENABLE_RATE_LIMIT_LOGIN
4. SECURITY_ENABLE_RATE_LIMIT_PUBLIC_API
5. SECURITY_LOGIN_RATE_LIMIT_PER_MINUTE
6. SECURITY_PUBLIC_API_RATE_LIMIT_PER_MINUTE
7. SECURITY_HIDE_DEBUG_DETAILS

Nilai rekomendasi:

1. true di production untuk seluruh kontrol yang relevan
2. local dapat lebih longgar untuk debugging, kecuali kontrol yang mengubah logika domain

## 30. Variabel Audit dan Logging Kustom

Variabel yang direkomendasikan:

1. AUDIT_ENABLED
2. AUDIT_LOG_IP_ADDRESS
3. AUDIT_LOG_USER_AGENT
4. AUDIT_LOG_VALUE_SANITIZATION
5. APP_LOG_SENSITIVE_FIELDS_MASKING

Aturan:

1. audit sebaiknya aktif pada semua environment uji dan production
2. local juga sebaiknya aktif agar developer melihat perilaku audit
3. masking field sensitif sangat dianjurkan

## 31. Variabel OPAC Publik

Variabel OPAC kustom yang direkomendasikan:

1. OPAC_ENABLE_PUBLIC_SUGGESTION
2. OPAC_DEFAULT_PER_PAGE
3. OPAC_MAX_PER_PAGE
4. OPAC_SHOW_ABOUT_PAGE
5. OPAC_SHOW_HELP_PAGE

Nilai rekomendasi:

1. OPAC_DEFAULT_PER_PAGE = 10 atau 20
2. OPAC_MAX_PER_PAGE tetap terkendali

## 32. Variabel Search Relevance dan Index

Variabel kustom yang direkomendasikan:

1. SEARCH_REINDEX_CHUNK_SIZE
2. SEARCH_HYDRATE_CHUNK_SIZE
3. SEARCH_PUBLIC_ENABLED
4. SEARCH_SYNC_SETTINGS_ON_DEPLOY

Nilai rekomendasi:

1. SEARCH_REINDEX_CHUNK_SIZE = 100 atau 250
2. SEARCH_PUBLIC_ENABLED = true pada environment yang menjalankan OPAC penuh

## 33. Variabel Housekeeping

Variabel housekeeping yang direkomendasikan:

1. TEMP_FILE_RETENTION_HOURS
2. OCR_TEMP_RETENTION_HOURS
3. EXPORT_TEMP_RETENTION_HOURS
4. OBSOLETE_FILE_RETENTION_DAYS
5. KEEP_RELEASE_COUNT

Nilai rekomendasi:

1. OCR temp singkat
2. export temp singkat
3. obsolete file retensi terbatas
4. release lama dipertahankan secukupnya

## 34. Variabel Feature Toggle yang Diizinkan

Feature toggle yang diizinkan harus terbatas dan jelas.

Contoh yang diizinkan:

1. OCR_ENABLED
2. HORIZON_ENABLED
3. PULSE_ENABLED
4. TELESCOPE_ENABLED
5. OPAC_ENABLE_PUBLIC_SUGGESTION
6. REPORT_EXPORT_ASYNC
7. MAIL_ENABLED, variabel kustom opsional

Aturan:

1. feature toggle tidak boleh menjadi alasan mengabaikan service layer
2. feature toggle tidak boleh menimbulkan state domain tidak konsisten
3. fitur yang belum masuk blueprint tidak boleh diam diam diaktifkan hanya karena ada env variable

## 35. Variabel yang Tidak Boleh Ada Secara Liar

Tidak boleh ada variabel liar yang:

1. mengubah rule bisnis inti tanpa dokumentasi
2. menonaktifkan security secara diam diam
3. membuka akses asset privat
4. mengubah permission matrix dari env
5. mengubah workflow state machine dari env
6. menambahkan domain integrasi di luar blueprint

## 36. Perbedaan Environment Local

Karakter local:

1. APP_DEBUG boleh true
2. mail boleh log
3. storage boleh endpoint lokal
4. queue boleh redis atau sync
5. search boleh aktif untuk integrasi test
6. data uji tidak nyata

Tujuan:

1. pengembangan
2. debugging
3. test awal

## 37. Perbedaan Environment Staging

Karakter staging:

1. semirip mungkin dengan production
2. APP_DEBUG disarankan false
3. HTTPS dianjurkan
4. storage terpisah dari production
5. database terpisah dari production
6. Meilisearch terpisah
7. mail sandbox atau aman

Tujuan:

1. integrasi
2. UAT
3. smoke test deploy
4. rehearsal release

## 38. Perbedaan Environment Production

Karakter production:

1. APP_DEBUG wajib false
2. HTTPS wajib aktif
3. semua secret produksi khusus
4. storage dan DB hanya produksi
5. mail resmi bila aktif
6. audit aktif
7. logging aman
8. monitoring aktif
9. rate limit aktif
10. queue worker aktif

## 39. Matriks Variabel per Environment

| Variabel | Local | Staging | Production |
|---|---|---|---|
| APP_DEBUG | true boleh | false dianjurkan | false wajib |
| APP_ENV | local | staging | production |
| HTTPS | opsional | dianjurkan | wajib |
| MAIL | log atau sandbox | sandbox | resmi atau disabled aman |
| Redis password | opsional | dianjurkan | dianjurkan kuat |
| Meilisearch key | wajib jika aktif | wajib | wajib |
| OCR | opsional untuk local ringan | aktif dianjurkan | aktif bila fitur dipakai |
| Queue | sync atau redis | redis | redis |
| Search public | opsional | aktif uji | aktif |
| Telescope | boleh | terbatas | nonaktif atau sangat terbatas |

## 40. Validasi Konfigurasi Saat Boot

Aplikasi harus memiliki pemeriksaan konfigurasi minimum.

Yang harus diverifikasi saat deploy atau boot health check:

1. APP_KEY ada
2. database connect
3. Redis connect bila queue aktif
4. Meilisearch connect bila search aktif
5. storage connect
6. OCR binary tersedia bila OCR_ENABLED = true
7. mail config valid bila MAIL_ENABLED = true

Aturan:

1. error konfigurasi harus aman
2. jangan tampilkan secret di pesan error
3. log internal harus cukup membantu admin

## 41. Aturan Fallback Configuration

Fallback configuration diperbolehkan terbatas.

Boleh:

1. nilai default aman untuk per_page
2. nilai default aman untuk feature toggle non kritis
3. nilai default aman untuk retention

Tidak boleh:

1. fallback ke secret kosong
2. fallback yang membuka keamanan
3. fallback yang mengubah bisnis inti tanpa dokumentasi

## 42. Aturan Naming Variabel

Penamaan variabel harus:

1. uppercase
2. snake case
3. konsisten
4. mudah dipahami
5. domain based bila kustom

Contoh baik:

1. OCR_ENABLED
2. REPORT_EXPORT_ASYNC
3. SEARCH_REINDEX_CHUNK_SIZE

Contoh buruk:

1. FLAG1
2. TEMPX
3. SETTING_A

## 43. Aturan Dokumentasi Nilai Default

Setiap variabel penting harus terdokumentasi:

1. arti
2. mandatory atau optional
3. contoh nilai
4. environment target
5. risiko jika salah

## 44. Aturan Perubahan Konfigurasi Production

Perubahan environment production harus:

1. terdokumentasi
2. dibatasi ke admin sistem berwenang
3. diuji dampaknya
4. disertai backup atau rencana rollback bila memengaruhi layanan penting
5. tidak dilakukan sembarangan pada jam sibuk bila berisiko tinggi

## 45. Aturan Reload Konfigurasi

Setelah perubahan `.env` atau config penting:

1. clear config cache lama bila perlu
2. bangun ulang config cache
3. restart worker bila memerlukan nilai baru
4. reload PHP-FPM bila perlu
5. verifikasi health check

## 46. Aturan Config Cache

Production dan staging sebaiknya menggunakan config cache.

Aturan:

1. setiap perubahan env harus diikuti refresh config cache
2. local boleh tanpa cache demi kemudahan debugging
3. jangan pakai cache config yang sudah tidak sinkron dengan `.env`

## 47. Aturan Perlindungan `.env`

File `.env` wajib:

1. tidak berada di folder public
2. tidak bisa dibaca via web server
3. permission file dibatasi
4. tidak ikut backup terbuka tanpa proteksi
5. tidak ikut export log

## 48. Contoh Template `.env.example` Ringkas

Contoh ringkas berikut hanya ilustrasi dan tidak mengandung secret nyata:

```dotenv
APP_NAME=PERPUSQU
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://perpusqu.local
APP_TIMEZONE=Asia/Makassar
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FORCE_HTTPS=false

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpusqu
DB_USERNAME=perpusqu_user
DB_PASSWORD=

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=
REDIS_PORT=6379
REDIS_CACHE_DB=0
REDIS_QUEUE_DB=1
REDIS_SESSION_DB=2

CACHE_STORE=redis
CACHE_PREFIX=perpusqu_local_

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

QUEUE_CONNECTION=redis

MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=
SEARCH_DRIVER=meilisearch
SEARCH_INDEX_PUBLIC_RECORDS=opac_records

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=perpusqu
AWS_ENDPOINT=http://127.0.0.1:9000
AWS_USE_PATH_STYLE_ENDPOINT=true
STORAGE_PUBLIC_DISK=public_assets
STORAGE_PRIVATE_DISK=private_assets
STORAGE_TEMP_DISK=local_temp

OCR_ENABLED=true
OCR_BINARY_PATH=/usr/bin/tesseract
OCR_TEMP_PATH=/var/www/perpusqu/shared/storage/app/temp/ocr
OCR_DEFAULT_MODE=queue
OCR_MAX_PAGES_PER_BATCH=25
OCR_RETRY_ATTEMPTS=3

LIBRARY_LOGO_MAX_MB=2
LIBRARY_COVER_MAX_MB=4
LIBRARY_ASSET_MAX_MB=50
LIBRARY_ALLOWED_IMAGE_EXTENSIONS=jpg,jpeg,png,webp
LIBRARY_ALLOWED_ASSET_EXTENSIONS=pdf
LIBRARY_ALLOWED_ASSET_MIME_TYPES=application/pdf

MEMBER_IMPORT_MAX_FILE_MB=10
REPORT_EXPORT_DEFAULT_FORMAT=xlsx
REPORT_EXPORT_ASYNC=false
REPORT_EXPORT_RETENTION_HOURS=24

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@perpusqu.local
MAIL_FROM_NAME="${APP_NAME}"

HORIZON_ENABLED=true
PULSE_ENABLED=false
TELESCOPE_ENABLED=false

OPAC_ENABLE_PUBLIC_SUGGESTION=false
OPAC_DEFAULT_PER_PAGE=10
OPAC_MAX_PER_PAGE=50

SECURITY_FORCE_HTTPS=false
SECURITY_ENABLE_HSTS=false
SECURITY_ENABLE_RATE_LIMIT_LOGIN=true
SECURITY_ENABLE_RATE_LIMIT_PUBLIC_API=true
SECURITY_LOGIN_RATE_LIMIT_PER_MINUTE=10
SECURITY_PUBLIC_API_RATE_LIMIT_PER_MINUTE=60

AUDIT_ENABLED=true
AUDIT_LOG_IP_ADDRESS=true
AUDIT_LOG_USER_AGENT=true
AUDIT_LOG_VALUE_SANITIZATION=true

TEMP_FILE_RETENTION_HOURS=24
OCR_TEMP_RETENTION_HOURS=12
EXPORT_TEMP_RETENTION_HOURS=24
OBSOLETE_FILE_RETENTION_DAYS=7
KEEP_RELEASE_COUNT=5
````

## 49. Contoh Perbedaan Nilai Production

Contoh penyesuaian production yang direkomendasikan:

1. APP_ENV=production
2. APP_DEBUG=false
3. APP_FORCE_HTTPS=true
4. LOG_LEVEL=info atau warning
5. SESSION_SECURE_COOKIE=true
6. MAIL_MAILER=smtp atau relay resmi bila aktif
7. SECURITY_ENABLE_HSTS=true
8. TELESCOPE_ENABLED=false atau sangat dibatasi
9. PULSE_ENABLED=true bila resource cukup
10. OPAC_ENABLE_PUBLIC_SUGGESTION=true bila sudah diuji aman

## 50. Aturan Test Environment

Environment uji harus:

1. tidak memakai secret production
2. tidak memakai database production
3. tidak memakai storage production
4. tidak memakai index production
5. tidak mengirim email ke user nyata tanpa kontrol

## 51. Aturan Rotasi Secret

Jika secret harus dirotasi:

1. dokumentasikan kapan
2. tentukan layanan terdampak
3. update env
4. refresh config cache
5. restart service terkait
6. verifikasi aplikasi tetap sehat

Contoh secret yang mungkin dirotasi:

1. DB_PASSWORD
2. REDIS_PASSWORD
3. AWS_SECRET_ACCESS_KEY
4. MAIL_PASSWORD
5. MEILISEARCH_KEY

## 52. Aturan Troubleshooting Konfigurasi

Jika aplikasi gagal boot atau fitur gagal:

1. cek `.env`
2. cek config cache
3. cek service pendukung
4. cek log aman
5. cek permission file
6. cek koneksi antar layanan
7. jangan langsung menebak dan mengubah variabel tanpa catatan

## 53. Testing Requirement Konfigurasi

Pengujian minimum konfigurasi wajib mencakup:

1. APP boots normal
2. database connect berhasil
3. Redis connect berhasil
4. queue berjalan
5. storage public dan private bisa diakses sesuai policy
6. search connect berhasil
7. OCR dependency terdeteksi saat aktif
8. mail fallback aman saat disabled atau log mode
9. APP_DEBUG production benar benar false
10. session secure cookie benar pada HTTPS production

## 54. Anti Pattern yang Dilarang

Konfigurasi environment tidak boleh:

1. menaruh secret di source code
2. menaruh secret di `.env.example`
3. memakai database production di staging
4. memakai bucket production di local
5. menyalakan APP_DEBUG di production
6. mengirim email nyata dari staging tanpa kontrol
7. memakai satu Redis namespace tanpa pemisahan bila berdampak konflik
8. membiarkan variabel kritis kosong tanpa deteksi
9. menambah feature toggle liar yang mengubah domain inti
10. menulis secret di log deploy

## 55. Prioritas Implementasi

Prioritas environment configuration:

### Prioritas P1

1. APP
2. DB
3. REDIS
4. QUEUE
5. STORAGE
6. SEARCH
7. OCR
8. SESSION
9. SECURITY minimum

### Prioritas P2

1. MAIL
2. HORIZON
3. PULSE
4. HOUSEKEEPING
5. REPORT EXPORT tuning

### Prioritas P3

1. tambahan tuning OCR
2. tambahan tuning search
3. advanced monitoring config

## 56. Hubungan dengan Dokumen Berikutnya

Dokumen ini sangat terkait dengan:

1. 35_BACKUP_AND_RECOVERY.md
2. 36_PERFORMANCE_GUIDE.md
3. 37_CODING_STANDARD.md
4. 41_BACKEND_CHECKLIST.md
5. 42_FRONTEND_CHECKLIST.md
6. 45_SMOKE_TEST_CHECKLIST.md
7. 46_UAT_CHECKLIST.md

Hubungan:

1. Backup and Recovery memerlukan detail storage, DB, dan retention config
2. Performance Guide memerlukan queue, OCR, search, dan cache config
3. Coding Standard harus melarang hardcoded secret
4. Smoke Test memverifikasi environment berjalan benar

## 57. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 35_BACKUP_AND_RECOVERY.md
2. 36_PERFORMANCE_GUIDE.md
3. 37_CODING_STANDARD.md
4. 38_TREE.md
5. 39_TRACEABILITY_MATRIX.md
6. 41_BACKEND_CHECKLIST.md
7. 42_FRONTEND_CHECKLIST.md
8. 45_SMOKE_TEST_CHECKLIST.md
9. 46_UAT_CHECKLIST.md

Aturan:

1. semua checklist harus memeriksa konfigurasi environment minimum
2. coding standard harus mengacu pada aturan secret management
3. traceability matrix harus menandai dependency env pada fitur penting
4. performance guide harus memakai nilai env tuning sebagai referensi

## 58. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. semua layanan pendukung punya variabel env yang jelas
2. variabel mandatory dan optional dibedakan
3. local, staging, dan production dibedakan jelas
4. secret management dijelaskan
5. config selaras dengan deployment, security, storage, search, OCR, dan reporting
6. tidak ada variabel liar di luar scope fase 1
7. `.env.example` dapat dipakai sebagai dasar bootstrap aman

## 59. Kesimpulan

Dokumen Environment Configuration ini menetapkan standar resmi konfigurasi environment PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 33. Dokumen ini memastikan bahwa seluruh variabel aplikasi, database, Redis, queue, search, storage, OCR, mail, logging, security, import export, dan housekeeping terdokumentasi dengan jelas, aman, dan siap dipakai pada local, staging, dan production. Semua konfigurasi environment PERPUSQU wajib merujuk dokumen ini.

END OF 34_ENV_CONFIGURATION.md
