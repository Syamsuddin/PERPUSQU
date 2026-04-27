# 36_PERFORMANCE_GUIDE.md

## 1. Nama Dokumen

Performance Guide Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint panduan performa aplikasi

### 2.3 Status Dokumen

Resmi, acuan wajib perencanaan, implementasi, pengukuran, optimasi, dan pengendalian performa sistem PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan panduan performa resmi PERPUSQU agar seluruh modul, query, halaman, layanan pendukung, file processing, search, OCR, reporting, import export, dan deployment berjalan cukup cepat, stabil, dan realistis untuk tim kecil serta arsitektur monolith modular yang telah disepakati. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, tester, dan administrator sistem agar tidak ada implementasi yang berat tanpa alasan, tidak ada query boros, tidak ada proses sinkron yang seharusnya async, dan tidak ada optimasi yang bertentangan dengan keamanan, audit, atau konsistensi data.

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
35. 35_BACKUP_AND_RECOVERY.md

Aturan wajib:

1. Performa tidak boleh dicapai dengan mengorbankan kebenaran data.
2. Performa tidak boleh dicapai dengan mengabaikan security policy.
3. Optimasi harus mengikuti arsitektur monolith modular.
4. MySQL tetap source of truth utama.
5. Proses berat wajib dipindahkan ke queue bila sesuai.
6. Search engine digunakan untuk discovery, bukan mengganti validasi final dari MySQL.
7. Panduan ini harus realistis terhadap kapasitas server fase 1.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. tujuan performa
2. target performa minimum
3. area bottleneck utama
4. strategi performa aplikasi web
5. strategi performa database
6. strategi performa search
7. strategi performa OCR
8. strategi performa storage
9. strategi performa reporting
10. strategi performa import export
11. strategi queue dan background job
12. strategi monitoring performa
13. strategi load awareness
14. strategi pengujian performa
15. batasan performa fase 1

## 5. Prinsip Umum Performa

Prinsip resmi performa PERPUSQU adalah:

1. cukup cepat
2. stabil
3. konsisten
4. terukur
5. hemat resource
6. tidak berlebihan
7. berbasis bottleneck nyata
8. mudah dipelihara

## 6. Sasaran Performa Fase 1

Sasaran utama performa fase 1 adalah:

1. halaman admin utama terasa responsif
2. OPAC publik terasa cepat untuk pencarian umum
3. transaksi sirkulasi berjalan cepat
4. upload dan preview file digital berjalan stabil
5. OCR tidak memblok request web normal
6. export besar tidak merusak respons halaman
7. queue job dapat menyerap proses berat
8. server kecil menengah tetap mampu melayani beban awal operasional kampus

## 7. Definisi Respons yang Diharapkan

Dalam konteks fase 1, panduan respons yang diharapkan:

1. halaman list admin ringan, ideal di bawah 2 detik pada kondisi normal
2. halaman form admin ringan, ideal di bawah 2 detik
3. transaksi pinjam dan kembali, ideal di bawah 3 detik
4. OPAC search normal, ideal di bawah 2 detik setelah search engine siap
5. preview asset digital, bergantung ukuran file tetapi mulai streaming harus cepat
6. OCR dan reindex tidak dinilai dari request web, karena harus async

Catatan:

1. angka ini adalah target operasional praktis, bukan SLA formal
2. hasil aktual dipengaruhi ukuran data, spesifikasi server, dan jumlah user aktif

## 8. Kategori Performa yang Dikelola

Performa dibagi menjadi:

1. page response performance
2. database performance
3. queue performance
4. search performance
5. file access performance
6. OCR processing performance
7. reporting performance
8. import export performance
9. infrastructure performance

## 9. Area Bottleneck Utama yang Diprediksi

Bottleneck utama fase 1 diperkirakan berada pada:

1. query list dan report yang join banyak tabel
2. OPAC search hydration setelah hasil search
3. upload dan preview PDF besar
4. OCR raster untuk PDF scan
5. full reindex
6. export laporan besar
7. import anggota file besar
8. queue worker yang tidak seimbang
9. object storage latency
10. disk dan RAM pada server kecil

## 10. Sasaran Performa per Modul

### 10.1 Identity and Access

Target:

1. login cepat
2. middleware auth dan permission efisien
3. menu render tidak berat

### 10.2 Master Data

Target:

1. CRUD ringan
2. lookup cepat
3. datatable stabil

### 10.3 Catalog

Target:

1. create dan update record responsif
2. relasi author dan subject efisien
3. publish memicu async reindex, bukan proses berat sinkron

### 10.4 Collection

Target:

1. create item cepat
2. status item update cepat
3. availability summary stabil

### 10.5 Member

Target:

1. form dan lookup anggota cepat
2. block dan unblock instan
3. import tetap aman untuk file menengah

### 10.6 Circulation

Target:

1. loan cepat
2. return cepat
3. renew cepat
4. evaluasi business rule tetap ringan

### 10.7 Digital Repository

Target:

1. upload stabil
2. metadata save cepat
3. preview aman dan cukup cepat
4. OCR async

### 10.8 OPAC

Target:

1. search cepat
2. detail record cepat
3. preview publik aman dan stabil

### 10.9 Reporting

Target:

1. dashboard snapshot cepat
2. laporan list tetap terkontrol dengan filter dan pagination
3. export besar bisa async bila perlu

## 11. Target Performa Minimum per Jenis Halaman

Target minimum yang direkomendasikan:

1. dashboard admin, kurang dari 2 detik pada kondisi normal
2. halaman list master data, kurang dari 2 detik
3. halaman list katalog, kurang dari 3 detik
4. halaman detail record, kurang dari 2 detik
5. halaman sirkulasi transaksi, kurang dari 3 detik
6. halaman detail asset digital, kurang dari 3 detik
7. OPAC search result, kurang dari 2 detik setelah hasil engine tersedia
8. reporting list, kurang dari 4 detik dengan filter normal

Catatan:

1. halaman sangat berat harus diarahkan ke async export atau background job
2. target ini berlaku setelah env staging atau production tertata benar

## 12. Prinsip Database Performance

Prinsip database performance resmi:

1. query seperlunya
2. eager loading seperlunya
3. hindari N plus 1
4. pakai index dengan benar
5. pakai pagination
6. hindari select star jika tidak perlu
7. agregasi di SQL, bukan loop PHP besar
8. transaksional untuk konsistensi, bukan untuk semua proses berat

## 13. Strategi Query Admin List

Untuk halaman list admin:

1. gunakan pagination wajib
2. gunakan filter yang tervalidasi
3. select field yang dibutuhkan saja
4. eager load relasi minimum
5. jangan memuat relasi besar yang tidak dipakai tabel

Contoh area:

1. daftar users
2. daftar authors
3. daftar records
4. daftar members
5. daftar loans
6. daftar digital assets

## 14. Strategi Query Detail Page

Untuk halaman detail:

1. ambil relasi yang benar benar dibutuhkan
2. hindari eager load semua relasi besar sekaligus
3. pisahkan panel data besar jika perlu
4. gunakan summary count untuk data turunan

Contoh:

1. detail record memuat author, subject, summary item, summary asset
2. detail asset tidak perlu memuat seluruh OCR raw text panjang ke view default

## 15. Strategi Index Database

Index database wajib dipakai konsisten dengan schema dan query dominan.

Index penting fase 1:

1. users.username
2. users.email
3. activity_logs.user_id, action, module_name, created_at
4. bibliographic_records.publication_status
5. bibliographic_records.is_public
6. physical_items.barcode
7. physical_items.inventory_code
8. physical_items.bibliographic_record_id
9. physical_items.item_status
10. members.member_number
11. members.email
12. members.member_type
13. members.is_active
14. members.is_blocked
15. loans.member_id
16. loans.physical_item_id
17. loans.loan_status
18. loans.due_date
19. fines.loan_id
20. fines.status
21. digital_assets.bibliographic_record_id
22. digital_assets.publication_status
23. digital_assets.ocr_status
24. digital_assets.index_status
25. digital_assets.asset_type
26. ocr_texts.digital_asset_id

Catatan:

1. index final tetap mengikuti 14_SCHEMA.sql
2. query nyata di staging harus dipantau untuk tuning lanjutan

## 16. Anti Pattern Query yang Dilarang

Tidak boleh:

1. N plus 1 query di list page
2. load semua data untuk dipotong manual
3. query tanpa pagination untuk list besar
4. agregasi besar dengan loop PHP
5. eager load relasi raksasa yang tidak dipakai
6. full table scan yang sebenarnya bisa difilter
7. raw SQL dari input user tanpa kontrol aman

## 17. Strategi Pagination

Pagination wajib untuk:

1. semua halaman list admin
2. audit log
3. collection report
4. member report
5. circulation report
6. fine report
7. digital repository report
8. OPAC result bila hasil banyak

Pilihan per page resmi:

1. 10
2. 25
3. 50
4. 100

Aturan:

1. default harus moderat
2. nilai per page ekstrem tidak boleh dibuka bebas

## 18. Strategi Caching

Caching boleh dipakai secara terukur.

Area yang cocok untuk cache ringan:

1. institution profile publik
2. master data lookup yang jarang berubah
3. dashboard widget tertentu yang aman dan ringkas
4. search settings metadata internal

Area yang tidak boleh terlalu mengandalkan cache:

1. transaksi sirkulasi aktif
2. access control keputusan final
3. asset preview permission final
4. data yang sangat sensitif terhadap update real time

Aturan:

1. cache harus punya invalidation jelas
2. jangan cache liar tanpa strategi refresh
3. cache tidak boleh menjadi sumber kebenaran domain

## 19. Strategi Redis Performance

Redis dipakai untuk:

1. queue
2. cache
3. session, bila diaktifkan

Aturan performa:

1. pisahkan DB index Redis untuk cache, queue, dan session
2. monitor memory Redis
3. gunakan prefix yang jelas
4. jangan jadikan Redis tempat menyimpan data domain primer

## 20. Strategi Queue Performance

Queue adalah komponen kunci performa fase 1.

Proses yang wajib async:

1. OCR
2. reindex
3. export async bila dipakai
4. email opsional async
5. housekeeping task tertentu

Aturan:

1. request web normal tidak boleh menunggu OCR atau export besar selesai
2. jumlah worker disesuaikan kapasitas CPU dan RAM
3. queue heavy job dapat dipisah dari queue default
4. job harus idempotent sejauh mungkin

## 21. Strategi Worker Separation

Pemisahan worker yang direkomendasikan:

1. worker default untuk job ringan
2. worker heavy untuk OCR dan job berat
3. worker export terpisah bila volume export meningkat

Manfaat:

1. OCR tidak menahan job ringan
2. notifikasi dan reindex kecil tetap jalan
3. sistem lebih stabil pada beban campuran

## 22. Strategi Search Performance

Search performance harus mengacu ke Meilisearch dan hydration MySQL.

Prinsip:

1. search engine untuk pencarian cepat
2. MySQL untuk validasi final
3. dokumen index ringan dan relevan
4. query search publik sederhana

Aturan:

1. OPAC search tidak boleh fallback ke query like besar pada MySQL sebagai jalur utama jika search engine aktif
2. hydration harus selektif
3. jangan ambil relasi berlebih saat hydration hasil search
4. filter search harus tervalidasi

## 23. Target Search Performance

Target praktis:

1. suggestion publik cepat
2. pencarian OPAC umum kurang dari 2 detik pada data awal hingga menengah
3. detail record hasil search tetap responsif
4. reindex background tidak merusak respons halaman admin

## 24. Strategi Search Reindex

Reindex harus efisien.

Aturan:

1. gunakan incremental reindex sebagai default
2. full reindex hanya saat perlu
3. chunk size harus moderat
4. logging progress disiapkan
5. full reindex dijalankan pada waktu aman

Chunk size awal yang direkomendasikan:

1. 100
2. 250
3. 500

Nilai final disesuaikan melalui uji staging.

## 25. Strategi OPAC Performance

OPAC harus tetap ringan dan aman.

Aturan:

1. halaman hasil tidak memuat detail yang tidak perlu
2. card hasil hanya field penting
3. detail record memuat summary, bukan semua relasi besar
4. preview asset publik tetap di-stream aman
5. image cover harus ringan dan terkendali ukurannya

## 26. Strategi File and Storage Performance

File digital berpotensi menjadi bottleneck.

Strategi:

1. file utama tetap di object storage
2. preview di-stream efisien
3. metadata file disimpan di database
4. jangan load file penuh ke memory aplikasi jika tidak perlu
5. cover image harus ringan

Aturan:

1. asset PDF besar tidak boleh diproses sinkron di request biasa
2. streaming file harus aman dan cukup efisien
3. konektivitas ke storage harus dipantau

## 27. Strategi Upload Performance

Untuk upload asset digital:

1. validasi file dilakukan cepat
2. tulis file dulu, lalu metadata
3. OCR jangan dijalankan sinkron
4. checksum dihitung secara efisien
5. feedback ke user harus cepat setelah metadata sukses dan job dijadwalkan

## 28. Strategi Preview Performance

Untuk preview internal dan publik:

1. gunakan viewer yang stabil
2. jangan expose file melalui mekanisme yang memaksa download penuh tanpa alasan
3. gunakan streaming aman
4. preview publik hanya untuk file yang sah
5. koneksi storage harus cukup cepat

Catatan:

1. preview file besar tetap tergantung bandwidth dan ukuran file
2. fokus fase 1 adalah stabilitas dan keamanan, lalu optimasi bertahap

## 29. Strategi OCR Performance

OCR adalah salah satu proses paling berat.

Prinsip:

1. wajib async
2. gunakan queue khusus bila perlu
3. temp file dibersihkan
4. hindari OCR ulang tanpa alasan
5. prioritaskan ekstraksi teks langsung jika tersedia sebelum raster OCR penuh

Aturan:

1. OCR text based PDF harus diutamakan bila memadai
2. scanned PDF diproses batch per halaman atau per job yang terkendali
3. OCR tidak boleh dijalankan massal tanpa perencanaan resource

## 30. Target OCR Performance

Karena PDF bervariasi, target OCR fase 1 bersifat praktis:

1. job OCR tidak memblok request web
2. file kecil hingga menengah selesai dalam antrean normal
3. file besar tetap bisa diproses bertahap
4. worker tidak crash karena temp file atau memory leak

## 31. Strategi OCR Tuning

Tuning yang dapat dipakai:

1. batasi page per batch bila perlu
2. atur DPI raster secara moderat
3. gunakan worker terpisah untuk OCR berat
4. atur timeout worker lebih tinggi untuk OCR
5. pantau failure dan retry rate

Aturan:

1. tuning harus diuji di staging
2. jangan menaikkan resource OCR tanpa melihat kapasitas server keseluruhan

## 32. Strategi Reporting Performance

Reporting rentan berat karena agregasi dan join.

Prinsip:

1. gunakan query agregasi SQL
2. filter wajib
3. pagination wajib
4. dashboard pakai snapshot ringan
5. export besar boleh async

Area paling sensitif:

1. circulation report
2. fine report
3. popular collection report
4. digital repository report

## 33. Target Reporting Performance

Target praktis:

1. dashboard report kurang dari 3 detik pada kondisi normal
2. report list kurang dari 4 detik dengan filter normal
3. export besar tidak dilakukan sinkron bila mulai berat
4. tidak ada list report besar tanpa pagination

## 34. Strategi Popular Collection Query

Popular collection harus dihitung efisien.

Aturan:

1. group by dilakukan di SQL
2. join hanya ke tabel yang dibutuhkan
3. gunakan periode filter
4. hindari menghitung seluruh histori tanpa filter jika data membesar

## 35. Strategi Digital Repository Report

Digital repository report harus:

1. memakai agregasi status OCR dan index secara SQL
2. memuat kolom inti saja
3. tidak memuat OCR text penuh
4. tidak memuat detail access rule besar dalam list

## 36. Strategi Import Performance

Import anggota berpotensi berat jika file besar.

Prinsip:

1. validasi file dulu
2. validasi per baris efisien
3. gunakan chunk bila implementasi membutuhkan
4. hindari memuat seluruh file sangat besar ke memory jika tidak perlu
5. partial success harus tetap terkontrol

Aturan:

1. file import maksimal mengikuti kebijakan
2. import fase 1 fokus pada ukuran wajar
3. import bukan jalur update massal tanpa kontrol

## 37. Strategi Export Performance

Export besar dapat membebani CPU, memory, dan storage.

Prinsip:

1. sinkron untuk hasil kecil atau sedang
2. async untuk hasil besar
3. file temp dibersihkan
4. data yang diekspor hanya yang diperlukan

Aturan:

1. jangan ekspor seluruh dataset tanpa filter jika data membesar
2. export harus memanfaatkan report service, bukan query liar baru
3. hindari membangun seluruh data raksasa di memory bila bisa streaming builder

## 38. Strategi Audit Log Performance

Audit log harus tetap efisien.

Aturan:

1. catat event penting saja
2. gunakan pagination di halaman audit
3. filter dan index kolom action, module_name, user_id, created_at
4. jangan simpan blob besar di old_values dan new_values
5. jangan jadikan audit log tempat semua debug detail

## 39. Strategi Notification Performance

Notification fase 1 bersifat ringan.

Aturan:

1. flash message tidak berdampak besar
2. dashboard alert dihitung agregat efisien
3. email opsional harus async
4. notifikasi tidak boleh memicu query berulang tidak perlu pada tiap halaman

## 40. Strategi Middleware dan Permission Performance

Middleware dan permission harus aman tetapi tidak boros.

Aturan:

1. gunakan middleware seperlunya
2. jangan lakukan query role permission berulang tidak perlu dalam satu request
3. manfaatkan caching internal yang aman bila framework atau package mendukung
4. tetap utamakan kebenaran akses

## 41. Strategi View Performance

View dan Blade harus ringan.

Aturan:

1. hindari logika berat di Blade
2. hindari loop nested berlebihan
3. jangan panggil query langsung dari view
4. gunakan komponen yang konsisten dan tidak boros
5. asset frontend dibangun production ready

## 42. Strategi Frontend Asset Performance

Frontend performance dipengaruhi asset build.

Aturan:

1. gunakan build production
2. minimalkan asset yang tidak terpakai
3. hindari library frontend berlebihan
4. image dan logo harus ringan
5. cover image publik sebaiknya tidak terlalu besar

## 43. Strategi Memory Usage

Aplikasi harus menjaga penggunaan memory.

Aturan:

1. hindari load collection besar ke memory
2. gunakan chunk atau cursor saat sesuai
3. export besar dan reindex besar harus dipecah
4. OCR temp dan image harus dibersihkan
5. worker berat dipisah bila perlu

## 44. Strategi CPU Usage

CPU sensitif pada:

1. OCR
2. export besar
3. full reindex
4. agregasi report berat

Mitigasi:

1. async jobs
2. jadwal di waktu aman
3. worker separation
4. tuning batch size
5. hindari full rebuild berulang tanpa alasan

## 45. Strategi Disk Usage

Disk usage sensitif pada:

1. PDF asset
2. temp OCR
3. export files
4. logs
5. backup
6. release lama

Mitigasi:

1. housekeeping terjadwal
2. retensi temp file
3. retensi release
4. retensi log
5. monitoring disk rutin

## 46. Strategi Network Usage

Network usage sensitif pada:

1. object storage transfer
2. OPAC preview
3. export download
4. search engine remote host, bila terpisah

Mitigasi:

1. server dan layanan pendukung sebaiknya dekat secara jaringan
2. preview file harus efisien
3. export besar bisa diunduh terkontrol
4. hindari transfer antar layanan yang tidak perlu

## 47. Monitoring Performa yang Direkomendasikan

Monitoring minimum yang direkomendasikan:

1. response time halaman utama
2. query slow log database
3. queue length
4. queue failure rate
5. OCR processing duration
6. reindex duration
7. export duration
8. storage latency dasar
9. Redis memory
10. disk usage
11. CPU dan RAM server

## 48. Alat Monitoring yang Direkomendasikan

Alat bantu yang cocok:

1. Laravel Pulse
2. Horizon
3. MySQL slow query log
4. Nginx access log
5. application log
6. Supervisor log
7. system monitoring OS

Catatan:

1. tools dipilih sesuai kapasitas server
2. jangan aktifkan monitoring terlalu berat di production kecil tanpa perhitungan

## 49. Threshold Operasional yang Perlu Diperhatikan

Threshold awal yang perlu dipantau:

1. queue menumpuk terus menerus
2. banyak OCR failed karena timeout atau resource
3. response halaman admin melebihi target normal berulang
4. OPAC search melambat signifikan
5. disk usage tinggi karena temp file
6. export besar memonopoli worker
7. reindex terlalu sering memakan resource

## 50. Strategi Pengujian Performa

Pengujian performa fase 1 bersifat praktis.

Yang wajib diuji:

1. login response
2. dashboard response
3. loan response
4. return response
5. OPAC search response
6. upload asset basic response
7. OCR queue behavior
8. report list response
9. export behavior
10. reindex behavior

## 51. Skenario Uji Performa Minimum

### 51.1 Skenario A

Login admin dengan dataset normal

Hasil yang Diharapkan:

1. respons cepat
2. session terbentuk normal

### 51.2 Skenario B

OPAC search dengan data publik menengah

Hasil yang Diharapkan:

1. hasil muncul cepat
2. hydration tidak berat

### 51.3 Skenario C

Loan transaction pada jam operasional normal

Hasil yang Diharapkan:

1. proses cepat
2. item status berubah benar
3. tidak ada delay berat

### 51.4 Skenario D

Upload asset PDF menengah

Hasil yang Diharapkan:

1. metadata tersimpan cepat
2. OCR dijadwalkan async
3. UI tidak menunggu OCR selesai

### 51.5 Skenario E

Dashboard report dan circulation report dibuka dengan data uji menengah

Hasil yang Diharapkan:

1. summary dan list tampil wajar
2. query tidak timeout

## 52. Anti Pattern Performa yang Dilarang

Tidak boleh:

1. menjalankan OCR sinkron di request web normal
2. full reindex pada setiap update kecil tanpa kontrol
3. export besar sinkron tanpa batas
4. report tanpa pagination
5. N plus 1 query di halaman utama
6. search publik fallback ke query like besar sebagai jalur utama
7. view memanggil query langsung
8. memuat seluruh relasi besar tanpa kebutuhan
9. membiarkan temp files menumpuk
10. mengaktifkan fitur monitoring berat sembarangan di server kecil

## 53. Batasan Performa Fase 1

Fase 1 belum menargetkan:

1. high concurrency enterprise
2. zero downtime scaling besar
3. multi server load balancing
4. distributed OCR cluster
5. huge scale analytics
6. search miliaran dokumen
7. HA database cluster
8. advanced autoscaling

Catatan:

1. fokus fase 1 adalah performa baik untuk beban kampus awal sampai menengah
2. arsitektur tetap bisa diperluas nanti jika dibutuhkan

## 54. Strategi Tuning Bertahap

Tuning harus bertahap:

1. ukur bottleneck dulu
2. optimasi query dulu
3. optimasi index database
4. optimasi queue separation
5. optimasi batch size OCR dan reindex
6. optimasi export mode
7. baru pertimbangkan upgrade server

Prinsip:

1. jangan langsung menambah resource tanpa melihat akar masalah
2. jangan over engineer terlalu dini

## 55. Hubungan dengan Dokumen Lanjutan

Dokumen ini sangat terkait dengan:

1. 37_CODING_STANDARD.md
2. 38_TREE.md
3. 39_TRACEABILITY_MATRIX.md
4. 41_BACKEND_CHECKLIST.md
5. 42_FRONTEND_CHECKLIST.md
6. 45_SMOKE_TEST_CHECKLIST.md
7. 46_UAT_CHECKLIST.md

Hubungan:

1. Coding standard harus melarang anti pattern performa
2. Tree membantu menata job, service, dan support class performa
3. Traceability matrix membantu melihat jalur fitur yang perlu dioptimasi
4. Checklists harus memverifikasi query, pagination, queue, dan async flow
5. Smoke test dan UAT harus ikut memeriksa performa dasar

## 56. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 37_CODING_STANDARD.md
2. 38_TREE.md
3. 39_TRACEABILITY_MATRIX.md
4. 41_BACKEND_CHECKLIST.md
5. 42_FRONTEND_CHECKLIST.md
6. 43_INTEGRATION_CHECKLIST.md
7. 44_SECURITY_HARDENING_CHECKLIST.md
8. 45_SMOKE_TEST_CHECKLIST.md
9. 46_UAT_CHECKLIST.md

Aturan:

1. coding standard harus memuat pedoman query dan async job yang selaras
2. backend checklist harus memeriksa N plus 1, pagination, queue, dan indexing
3. frontend checklist harus memeriksa page weight dasar dan behavior UI ringan
4. smoke test harus menilai respons inti minimum
5. UAT harus memastikan performa operasional masih nyaman dipakai

## 57. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. target performa realistis untuk fase 1
2. bottleneck utama terpetakan
3. strategi untuk DB, queue, search, OCR, storage, reporting, dan import export jelas
4. tidak ada optimasi yang melanggar source of truth atau security
5. anti pattern performa terdefinisi
6. monitoring minimum terdefinisi
7. hubungan dengan test plan, deployment, env, dan coding standard jelas

## 58. Kesimpulan

Dokumen Performance Guide ini menetapkan panduan performa resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 35. Dokumen ini memastikan bahwa query database, search, OCR, file digital, reporting, import export, queue, dan halaman utama dioptimalkan secara terukur dan realistis, tanpa mengorbankan keamanan, audit, atau konsistensi data. Semua implementasi dan tuning performa PERPUSQU wajib merujuk dokumen ini.

END OF 36_PERFORMANCE_GUIDE.md
