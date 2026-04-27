# 35_BACKUP_AND_RECOVERY.md

## 1. Nama Dokumen

Backup and Recovery Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint kebijakan backup, restore, recovery, dan kesinambungan data operasional

### 2.3 Status Dokumen

Resmi, acuan wajib perencanaan, pelaksanaan, verifikasi, dan pengendalian backup serta recovery PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan kebijakan resmi backup dan recovery PERPUSQU agar database, file digital, konfigurasi penting, dan komponen operasional inti dapat dipulihkan secara terukur saat terjadi kegagalan, kerusakan data, kesalahan operasional, atau insiden infrastruktur. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, administrator sistem, reviewer, dan pihak operasional agar tidak ada data kritis yang dibiarkan tanpa cadangan, tidak ada proses restore yang liar, dan tidak ada asumsi pemulihan yang bertentangan dengan blueprint sistem, storage policy, security policy, deployment guide, dan integration spec.

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
34. 34_ENV_CONFIGURATION.md

Aturan wajib:

1. MySQL tetap menjadi source of truth utama.
2. Backup wajib memprioritaskan database dan file digital utama.
3. Search index tidak diperlakukan sebagai data primer yang harus selalu dibackup.
4. File temp, cache, dan artefak sementara tidak wajib dibackup.
5. Backup dan restore harus mematuhi security policy dan secret management.
6. Recovery harus bisa diverifikasi, bukan hanya diasumsikan berhasil.
7. Backup produksi harus dipisahkan dari backup staging dan local.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip umum backup dan recovery
2. klasifikasi data dan aset yang dibackup
3. tujuan RPO dan RTO
4. jenis backup
5. frekuensi backup
6. retensi backup
7. lokasi backup
8. keamanan backup
9. verifikasi backup
10. strategi restore
11. recovery per komponen
12. recovery pasca deploy gagal
13. uji restore berkala
14. peran dan tanggung jawab
15. batasan backup fase 1

## 5. Prinsip Umum Backup dan Recovery

Prinsip resmi backup dan recovery PERPUSQU adalah:

1. prioritas pada data primer
2. konsistensi
3. keterulangan
4. verifikasi
5. keamanan
6. pemisahan environment
7. restore first mindset
8. sederhana dan realistis untuk tim kecil

## 6. Sasaran Backup dan Recovery

Sasaran utama dokumen ini adalah:

1. memastikan database operasional dapat dipulihkan
2. memastikan file digital penting dapat dipulihkan
3. memastikan konfigurasi produksi dapat dipulihkan dengan aman
4. memastikan kehilangan data dapat ditekan
5. memastikan pemulihan dapat dilakukan oleh admin berwenang dengan langkah yang jelas
6. memastikan backup bukan sekadar file tersimpan, tetapi benar benar dapat digunakan saat restore

## 7. Definisi Umum

### 7.1 Backup

Salinan data, file, atau konfigurasi yang dibuat untuk kebutuhan pemulihan.

### 7.2 Restore

Tindakan mengembalikan data atau file dari backup ke suatu target.

### 7.3 Recovery

Proses lengkap memulihkan layanan hingga kembali usable, termasuk restore data, sinkronisasi layanan, dan verifikasi operasional.

### 7.4 RPO

Recovery Point Objective, yaitu toleransi maksimum kehilangan data.

### 7.5 RTO

Recovery Time Objective, yaitu target waktu pemulihan layanan.

## 8. Prioritas Komponen untuk Backup

Komponen backup dibagi menjadi prioritas berikut:

### 8.1 Prioritas A

Wajib dibackup secara ketat.

1. database MySQL production
2. object storage private assets
3. object storage public assets yang penting
4. file konfigurasi environment production
5. audit log database
6. report export history bila dipakai

### 8.2 Prioritas B

Sangat dianjurkan dibackup.

1. release artifacts penting
2. konfigurasi Nginx
3. konfigurasi Supervisor atau systemd
4. konfigurasi scheduler
5. konfigurasi Meilisearch penting
6. skrip deploy operasional

### 8.3 Prioritas C

Tidak wajib dibackup rutin.

1. search index Meilisearch
2. cache Redis
3. session Redis
4. temp OCR files
5. temp import files
6. temp export files
7. build cache
8. compiled views

## 9. Source of Truth

Source of truth resmi:

1. database MySQL untuk data domain
2. object storage untuk file digital utama
3. `.env` dan konfigurasi server untuk parameter operasional
4. source code repository untuk kode aplikasi

Bukan source of truth:

1. Meilisearch index
2. Redis cache
3. temp files
4. queue payload sementara

## 10. Data dan Aset yang Wajib Dibackup

### 10.1 Database

Tabel yang termasuk kritis:

1. users
2. roles dan permissions serta relasinya
3. institution_profiles
4. system_settings
5. seluruh master data
6. bibliographic_records
7. relasi author dan subject
8. physical_items
9. physical_item_status_histories
10. members
11. loans
12. loan_renewals
13. return_transactions
14. fines
15. digital_assets
16. digital_asset_access_rules
17. ocr_texts
18. activity_logs
19. report_export_histories, bila dipakai

### 10.2 File Digital

1. logo institusi
2. cover record
3. seluruh PDF aset digital utama
4. file export penting yang masih dalam masa retensi, bila kebijakan memerlukan

### 10.3 Konfigurasi

1. `.env` production
2. konfigurasi Nginx
3. konfigurasi Supervisor atau systemd
4. cron scheduler config
5. kebijakan backup script dan retention script

## 11. Data yang Tidak Wajib Dibackup

Data berikut tidak wajib dibackup rutin:

1. Meilisearch documents
2. Redis cache
3. Redis session
4. Redis queue transient data
5. temp upload
6. temp OCR
7. temp export
8. release lama yang sudah tidak aktif, kecuali untuk rollback jangka pendek
9. compiled assets yang bisa dibangun ulang dari source code

## 12. Tujuan RPO dan RTO

### 12.1 Target RPO Fase 1

Target RPO yang direkomendasikan:

1. database production, maksimal 24 jam
2. file digital production, maksimal 24 jam
3. konfigurasi production, maksimal 24 jam atau setiap perubahan penting

Catatan:

1. bila kapasitas infrastruktur memungkinkan, RPO dapat diperketat
2. target ini realistis untuk tim kecil dengan backup harian

### 12.2 Target RTO Fase 1

Target RTO yang direkomendasikan:

1. gangguan minor aplikasi, 2 sampai 4 jam
2. pemulihan penuh dari backup database dan file, 4 sampai 8 jam
3. pemulihan layanan staging, fleksibel dan tidak seketat production

Catatan:

1. RTO dipengaruhi ukuran data, kapasitas server, dan kesiapan admin
2. fase 1 tidak menargetkan high availability enterprise

## 13. Jenis Backup Resmi

Jenis backup resmi yang direkomendasikan:

1. full backup database harian
2. full backup object storage harian atau sinkronisasi harian
3. backup konfigurasi setiap perubahan penting
4. backup pre deployment sebelum release production
5. backup manual insidental sebelum perubahan berisiko tinggi

## 14. Backup Database

### 14.1 Metode Backup Database

Metode yang direkomendasikan:

1. logical dump MySQL
2. kompresi hasil dump
3. penyimpanan ke lokasi backup aman

### 14.2 Frekuensi Backup Database

Rekomendasi:

1. full logical dump harian
2. backup tambahan sebelum migration besar atau deploy production

### 14.3 Format File Backup Database

Format yang direkomendasikan:

1. `.sql.gz`
2. nama file konsisten dan mengandung timestamp

Contoh:
`perpusqu-db-prod-20260420-230000.sql.gz`

## 15. Backup Object Storage

### 15.1 Cakupan

Object storage yang dibackup:

1. private digital assets
2. public assets penting
3. tidak termasuk temp area

### 15.2 Metode

Metode yang direkomendasikan:

1. sinkronisasi bucket ke backup target
2. snapshot storage bila infrastruktur mendukung
3. salinan inkremental atau sinkronisasi harian

### 15.3 Frekuensi

Rekomendasi:

1. harian
2. tambahan sebelum operasi bulk atau maintenance berisiko

## 16. Backup Konfigurasi dan Secrets

### 16.1 Cakupan

1. `.env` production
2. Nginx config
3. Supervisor config
4. cron config
5. script deploy
6. backup script

### 16.2 Aturan

1. backup konfigurasi harus terenkripsi atau dilindungi ketat
2. secret tidak boleh tersimpan di lokasi backup terbuka
3. perubahan konfigurasi penting harus dibackup segera setelah perubahan stabil

## 17. Backup Source Code dan Release Artifacts

Karena source of truth kode ada di repository:

1. source code utama dipulihkan dari repository
2. release artifacts aktif boleh disimpan sementara untuk rollback cepat
3. backup release artifacts bukan prioritas setara database dan file digital

Aturan:

1. repository harus sehat dan terjaga aksesnya
2. rollback jangka pendek tetap dianjurkan mempertahankan beberapa release terakhir di server

## 18. Backup Search Index

Meilisearch index tidak wajib dibackup rutin.

Alasan:

1. dapat dibangun ulang dari MySQL
2. bukan source of truth
3. lebih efisien memulihkan dengan reindex

Aturan:

1. setelah restore database, search settings dan reindex wajib dijalankan ulang
2. backup Meilisearch hanya opsional untuk percepatan tertentu

## 19. Backup Redis

Redis cache, session, dan queue transient data tidak wajib dibackup rutin.

Alasan:

1. data ini dapat dibangun ulang atau dianggap sementara
2. bukan source of truth domain
3. recovery sistem tidak boleh bergantung pada Redis restore

## 20. Jadwal Backup Minimum yang Direkomendasikan

### 20.1 Harian

1. full database backup
2. object storage sync backup
3. verifikasi ringkas hasil backup

### 20.2 Mingguan

1. simpan salinan backup mingguan terpisah
2. review keberhasilan backup
3. cek kapasitas storage backup

### 20.3 Bulanan

1. simpan satu backup bulanan
2. uji restore sampel
3. review retensi dan kapasitas

### 20.4 Sebelum Perubahan Besar

1. backup manual database
2. backup konfigurasi
3. backup object storage jika perubahan menyentuh storage atau file processing

## 21. Retensi Backup

Retensi minimum yang direkomendasikan:

1. backup harian, simpan 7 sampai 14 hari
2. backup mingguan, simpan 4 sampai 8 minggu
3. backup bulanan, simpan 3 sampai 6 bulan
4. backup pre deployment, simpan sesuai siklus release atau sampai release dianggap stabil

Catatan:

1. retensi final menyesuaikan kapasitas storage dan kebijakan institusi
2. audit kebutuhan legal atau operasional dapat memperpanjang retensi tertentu

## 22. Lokasi Penyimpanan Backup

Lokasi backup harus dipisah dari data aktif sejauh mungkin.

Pilihan yang direkomendasikan:

1. storage backup lokal server sekunder, sementara
2. object storage backup terpisah
3. server backup terpisah
4. media snapshot VPS, bila didukung, sebagai lapisan tambahan

Aturan:

1. jangan hanya menyimpan backup di lokasi yang sama dengan data aktif tanpa salinan kedua
2. production backup tidak boleh bercampur dengan staging
3. akses ke lokasi backup harus dibatasi

## 23. Prinsip 3-2-1 Secara Praktis

Pendekatan praktis yang direkomendasikan:

1. minimal 3 salinan data penting
2. pada 2 media atau lokasi logis berbeda
3. 1 salinan terpisah dari server utama

Catatan:

1. implementasi penuh 3-2-1 dapat disederhanakan sesuai kemampuan tim
2. paling tidak harus ada salinan kedua di luar folder aplikasi aktif

## 24. Penamaan File Backup

Format nama file backup harus konsisten.

Format yang direkomendasikan:
`perpusqu-{komponen}-{env}-{yyyyMMdd-HHmmss}.{ext}`

Contoh:

1. `perpusqu-db-prod-20260420-230000.sql.gz`
2. `perpusqu-storage-private-prod-20260420-230000.tar.gz`
3. `perpusqu-config-prod-20260420-230000.enc`

## 25. Keamanan Backup

Backup mengandung data sensitif. Karena itu wajib:

1. akses dibatasi
2. lokasi backup tidak publik
3. file backup terlindungi permission
4. secret backup terenkripsi atau dilindungi kuat
5. transport backup antar host aman
6. admin backup terbatas

## 26. Enkripsi Backup

Enkripsi sangat dianjurkan untuk:

1. backup database production
2. backup konfigurasi yang mengandung secret
3. backup file digital bila dipindah ke lokasi eksternal

Aturan:

1. kunci enkripsi dikelola aman
2. kunci tidak disimpan bersama file backup tanpa proteksi
3. prosedur restore harus tahu bagaimana membuka backup terenkripsi

## 27. Validasi Backup

Backup dianggap valid hanya bila:

1. file backup berhasil dibuat
2. ukuran file wajar
3. checksum atau integritas dasar sesuai
4. backup dapat dibaca
5. restore sample berhasil atau minimal lulus verifikasi dasar

Aturan:

1. backup sukses bukan hanya log "command executed"
2. backup harus punya bukti hasil yang dapat diverifikasi

## 28. Verifikasi Backup Harian

Verifikasi harian minimum:

1. file backup tersedia
2. timestamp benar
3. ukuran file tidak nol
4. log backup tidak menunjukkan kegagalan
5. backup object storage sinkron selesai

## 29. Uji Restore Berkala

Uji restore wajib dilakukan berkala.

Rekomendasi:

1. bulanan untuk database
2. berkala untuk object storage sample
3. setelah perubahan backup procedure besar

Tujuan:

1. memastikan backup benar benar bisa dipakai
2. memastikan prosedur restore terdokumentasi akurat
3. memastikan admin memahami recovery flow

## 30. Restore Database

### 30.1 Kapan Dilakukan

Restore database dipertimbangkan jika:

1. database korup
2. data hilang signifikan
3. migration merusak data
4. kesalahan operasional besar tidak dapat diperbaiki secara manual aman

### 30.2 Langkah Umum Restore Database

1. hentikan write access bila perlu
2. identifikasi backup yang akan dipakai
3. backup keadaan saat ini sebelum restore, bila memungkinkan
4. siapkan database target
5. restore dump
6. verifikasi koneksi aplikasi
7. verifikasi jumlah data utama
8. jalankan langkah sinkronisasi lanjutan yang perlu

### 30.3 Verifikasi Setelah Restore Database

1. tabel utama tersedia
2. login admin berhasil
3. data inti ada
4. audit log ada
5. route inti berjalan
6. search direbuild bila perlu

## 31. Restore Object Storage

### 31.1 Kapan Dilakukan

1. file digital hilang
2. bucket rusak
3. asset tidak dapat dibaca massal
4. storage terhapus atau salah sinkronisasi

### 31.2 Langkah Umum

1. identifikasi prefix atau bucket terdampak
2. pilih backup yang tepat
3. pulihkan file ke target storage
4. verifikasi sampel file penting
5. cek metadata database masih sinkron

### 31.3 Verifikasi Setelah Restore Storage

1. preview privat berhasil
2. preview publik sah berhasil
3. file tidak bocor
4. OCR source file penting kembali tersedia

## 32. Restore Konfigurasi

Jika konfigurasi rusak:

1. pulihkan `.env` yang benar
2. pulihkan konfigurasi Nginx atau Supervisor
3. clear dan rebuild config cache
4. restart service terkait
5. verifikasi health check

Aturan:

1. restore konfigurasi harus hati hati karena dapat memengaruhi koneksi ke DB, Redis, storage, dan search
2. backup konfigurasi harus selalu versi terbaru yang diketahui valid

## 33. Recovery Search

Karena search index bukan data primer, recovery search dilakukan dengan rebuild.

Langkah umum:

1. pastikan database pulih
2. pastikan Meilisearch aktif
3. sync search settings
4. jalankan full reindex publik
5. verifikasi hasil query OPAC

Aturan:

1. jangan memulihkan search sebelum database stabil
2. jika database berubah besar, search harus selalu direbuild

## 34. Recovery Redis dan Queue

Jika Redis atau queue gagal:

1. pulihkan service Redis
2. verifikasi queue connection
3. jalankan ulang worker
4. cek job penting yang perlu dipicu ulang
5. jangan menganggap job lama selalu pulih otomatis

Aturan:

1. data domain tetap mengacu MySQL
2. OCR, export async, dan reindex yang tertunda dapat dipicu ulang terkontrol

## 35. Recovery OCR Pipeline

Jika OCR service atau worker gagal:

1. pastikan binary OCR tersedia
2. pastikan temp path aman dan writable
3. restart worker
4. identifikasi asset failed
5. jalankan retry OCR pada asset yang relevan

Aturan:

1. asset utama tidak hilang hanya karena OCR gagal
2. status OCR dan OcrText harus diverifikasi setelah recovery

## 36. Recovery Setelah Deploy Gagal

Jika deploy baru gagal:

1. gunakan rollback code sesuai deployment guide
2. jika database sudah termigrasi dan bermasalah, evaluasi restore database
3. jika storage berubah dan bermasalah, restore object storage seperlunya
4. dokumentasikan insiden

Aturan:

1. rollback code lebih dulu dipertimbangkan
2. restore database hanya jika benar benar dibutuhkan
3. jangan restore tanpa mengetahui konsekuensi data terbaru

## 37. Recovery Scope Berdasarkan Tingkat Insiden

### 37.1 Insiden Minor

Contoh:

1. queue worker mati
2. search service mati
3. export gagal

Tindakan:

1. restart service
2. cek config
3. uji fungsi terkait
4. tidak perlu restore penuh

### 37.2 Insiden Menengah

Contoh:

1. file private asset sebagian hilang
2. reindex massal gagal
3. konfigurasi rusak

Tindakan:

1. restore komponen terkait
2. verifikasi lintas modul

### 37.3 Insiden Besar

Contoh:

1. database korup
2. storage utama rusak
3. release production menyebabkan data tidak konsisten besar

Tindakan:

1. freeze write access
2. restore dari backup
3. rebuild search
4. smoke test penuh
5. dokumentasi insiden lengkap

## 38. Peran dan Tanggung Jawab

### 38.1 Administrator Sistem

Tanggung jawab:

1. menyiapkan jadwal backup
2. mengawasi hasil backup
3. menjalankan restore teknis
4. menjaga secret backup
5. memverifikasi health pasca recovery

### 38.2 Developer atau Reviewer Teknis

Tanggung jawab:

1. membantu analisis insiden
2. membantu validasi integritas aplikasi pasca restore
3. membantu recovery search, OCR, dan queue

### 38.3 Admin Aplikasi

Tanggung jawab:

1. memverifikasi data operasional setelah recovery
2. memeriksa fungsi bisnis utama
3. membantu UAT pasca restore jika diperlukan

## 39. Artefak Backup dan Recovery yang Harus Terdokumentasi

Artefak minimum:

1. jadwal backup
2. log backup
3. lokasi backup
4. daftar restore test
5. waktu restore
6. operator restore
7. backup source yang dipakai
8. hasil verifikasi pasca restore
9. keputusan insiden dan tindak lanjut

## 40. Bukti Verifikasi Recovery

Bukti recovery yang direkomendasikan:

1. login admin berhasil
2. record sample tersedia
3. member sample tersedia
4. loan sample tersedia
5. asset sample bisa dipreview sesuai hak akses
6. OPAC search berjalan setelah reindex
7. audit log masih tersedia
8. report dashboard tampil

## 41. Komponen yang Harus Diverifikasi Setelah Recovery

Komponen minimum pasca recovery:

1. database connect
2. login admin
3. master data inti
4. bibliographic records
5. members
6. circulation
7. digital assets
8. OCR status basic consistency
9. OPAC search
10. reporting
11. audit log
12. export dasar
13. queue worker
14. scheduler

## 42. Hubungan dengan Security Policy

Backup dan recovery wajib mematuhi security policy.

Aturan:

1. backup tidak boleh bocor
2. restore hanya oleh pihak berwenang
3. secret dalam backup dilindungi
4. media backup tidak boleh terbuka publik
5. log recovery tidak boleh membocorkan secret

## 43. Hubungan dengan Storage Policy

Storage recovery harus mematuhi storage policy.

Aturan:

1. restore asset ke private dan public storage sesuai klasifikasi
2. temp area tidak wajib direstore
3. object key harus konsisten
4. akses asset pasca restore harus tetap lewat aplikasi

## 44. Hubungan dengan Search Indexing Spec

Setelah restore database:

1. search settings harus diverifikasi
2. full reindex wajib dijalankan bila perlu
3. hasil OPAC harus diverifikasi

Aturan:

1. jangan menganggap Meilisearch state lama pasti cocok dengan database hasil restore
2. lebih aman rebuild index dari source of truth

## 45. Hubungan dengan OCR dan Digital Processing

Setelah restore storage dan database:

1. asset PDF sample harus diverifikasi
2. OcrText harus sesuai database restore
3. asset failed dapat diproses ulang bila perlu
4. temp OCR lama tidak wajib dipulihkan

## 46. Hubungan dengan Import Export

Tidak wajib membackup:

1. temp import files
2. temp export files

Namun perlu menjaga:

1. report_export_histories bila dipakai
2. export files yang masih dalam masa retensi jika dianggap penting operasional

## 47. Kebijakan Backup Sebelum Deploy

Sebelum deploy production, wajib atau sangat dianjurkan:

1. backup database
2. backup konfigurasi
3. verifikasi backup terbaru ada
4. pastikan rollback code tersedia

Jika deploy menyentuh storage processing besar:

1. pertimbangkan backup tambahan storage

## 48. Jadwal Otomasi Backup

Backup sebaiknya diotomasi dengan:

1. cron
2. script backup terdokumentasi
3. logging hasil script
4. alert operasional, bila kelak diperlukan

Aturan:

1. script harus idempotent sejauh mungkin
2. script tidak boleh hardcode secret dalam plaintext jika bisa dihindari
3. hasil script harus mudah dibaca admin

## 49. Struktur Direktori Backup yang Direkomendasikan

Contoh struktur logis:

```text id="vt0b5m"
/backup/perpusqu/prod/database/
/backup/perpusqu/prod/storage/private/
/backup/perpusqu/prod/storage/public/
/backup/perpusqu/prod/config/
/backup/perpusqu/prod/logs/
/backup/perpusqu/staging/database/
/backup/perpusqu/staging/storage/
````

Aturan:

1. pisahkan production dan staging
2. pisahkan database, storage, dan config
3. permission direktori backup harus ketat

## 50. Penamaan Job Backup yang Direkomendasikan

Nama job atau script yang direkomendasikan:

1. `backup_perpusqu_database.sh`
2. `backup_perpusqu_storage.sh`
3. `backup_perpusqu_config.sh`
4. `verify_perpusqu_backup.sh`
5. `cleanup_perpusqu_backup.sh`

Catatan:

1. nama akhir dapat menyesuaikan
2. fungsi harus tetap konsisten

## 51. Testing Requirement Backup dan Recovery

Pengujian minimum wajib mencakup:

1. backup database harian berhasil dibuat
2. file backup database dapat dibaca
3. backup storage menghasilkan salinan file sample
4. backup config tersimpan aman
5. restore database ke environment uji berhasil
6. restore storage sample berhasil
7. login admin berhasil setelah restore uji
8. OPAC search pulih setelah reindex
9. asset private sample dapat dipreview sesuai hak akses setelah restore
10. audit log masih tersedia setelah restore uji
11. backup production tidak bercampur dengan staging
12. secret tidak bocor di log backup

## 52. Skenario Uji Recovery Minimum

### 52.1 Skenario A

Restore database ke staging test

Hasil yang Diharapkan:

1. database dapat dipulihkan
2. aplikasi bisa login
3. data utama konsisten

### 52.2 Skenario B

Restore storage private sample

Hasil yang Diharapkan:

1. file asset sample tersedia
2. preview privat bekerja
3. file publik tetap aman

### 52.3 Skenario C

Recovery penuh setelah simulasi database loss

Hasil yang Diharapkan:

1. database pulih
2. storage pulih
3. search direbuild
4. smoke test inti lulus

## 53. Kriteria Keberhasilan Recovery

Recovery dianggap berhasil bila:

1. layanan inti dapat diakses kembali
2. data domain utama konsisten
3. file digital utama tersedia
4. search publik bekerja setelah rebuild
5. role dan permission tetap benar
6. audit log inti tersedia
7. smoke test inti lulus

## 54. Kegagalan Recovery yang Bersifat Blocker

Recovery dianggap belum berhasil jika:

1. admin tidak bisa login
2. database utama tidak lengkap
3. file digital utama hilang signifikan
4. asset privat tidak dapat dibaca atau bocor
5. OPAC publik rusak total
6. search tidak dapat direbuild
7. audit log utama hilang total tanpa penjelasan
8. reporting inti gagal total

## 55. Anti Pattern yang Dilarang

Backup dan recovery tidak boleh:

1. hanya membackup database tanpa file digital
2. hanya membackup file tanpa database
3. menyimpan backup di lokasi yang sama tanpa salinan kedua
4. menganggap search index sebagai pengganti backup data primer
5. menyimpan secret backup sembarangan
6. melakukan restore langsung ke production tanpa verifikasi yang cukup
7. tidak pernah menguji restore
8. mencampur backup staging dan production
9. menghapus backup lama tanpa melihat retensi dan kebutuhan recovery
10. menjalankan restore destruktif tanpa dokumentasi

## 56. Prioritas Implementasi

### Prioritas P1

1. backup database harian
2. backup object storage harian
3. backup konfigurasi penting
4. pre deployment backup
5. verifikasi backup harian
6. restore database test

### Prioritas P2

1. restore storage test
2. recovery search rebuild checklist
3. recovery OCR readiness checklist
4. cleanup backup retention automation

### Prioritas P3

1. backup encryption refinement
2. multi location backup strengthening
3. advanced restore rehearsal
4. incident drill berkala

## 57. Hubungan dengan Dokumen Berikutnya

Dokumen ini sangat terkait dengan:

1. 36_PERFORMANCE_GUIDE.md
2. 37_CODING_STANDARD.md
3. 41_BACKEND_CHECKLIST.md
4. 45_SMOKE_TEST_CHECKLIST.md
5. 46_UAT_CHECKLIST.md

Hubungan:

1. Performance Guide perlu mempertimbangkan dampak backup job
2. Coding Standard harus melarang hardcoded backup secret
3. Backend Checklist harus memverifikasi script dan flow backup recovery
4. Smoke Test harus menjadi verifikasi pasca restore
5. UAT Checklist dapat dipakai untuk validasi operasional setelah recovery besar

## 58. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 36_PERFORMANCE_GUIDE.md
2. 37_CODING_STANDARD.md
3. 38_TREE.md
4. 39_TRACEABILITY_MATRIX.md
5. 41_BACKEND_CHECKLIST.md
6. 42_FRONTEND_CHECKLIST.md
7. 45_SMOKE_TEST_CHECKLIST.md
8. 46_UAT_CHECKLIST.md

Aturan:

1. Performance Guide harus mempertimbangkan jam backup, I O, dan OCR load
2. TREE harus memetakan lokasi script atau folder backup operasional yang relevan
3. Traceability Matrix harus memetakan backup recovery ke komponen inti
4. Checklists harus memverifikasi keberadaan prosedur dan uji restore

## 59. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. data primer yang dibackup sudah tepat
2. data non primer yang tidak wajib dibackup sudah jelas
3. frekuensi dan retensi backup terdokumentasi
4. strategi restore dan recovery jelas
5. hubungan dengan deployment, storage, search, OCR, dan security jelas
6. verifikasi backup dan uji restore diwajibkan
7. ruang lingkup fase 1 tetap realistis

## 60. Kesimpulan

Dokumen Backup and Recovery ini menetapkan kebijakan resmi backup dan recovery PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 34. Dokumen ini memastikan bahwa database, file digital, konfigurasi penting, dan layanan inti dapat dipulihkan secara tertib, aman, dan terukur saat terjadi gangguan atau insiden, dengan MySQL sebagai source of truth, object storage sebagai penyimpan file primer, dan search index sebagai komponen yang dapat dibangun ulang. Semua pelaksanaan backup dan recovery PERPUSQU wajib merujuk dokumen ini.

END OF 35_BACKUP_AND_RECOVERY.md
