# 28_SECURITY_POLICY.md

## 1. Nama Dokumen

Security Policy Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint kebijakan keamanan aplikasi, data, akses, file, proses, dan operasional

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan kontrol keamanan, hardening aplikasi, kontrol akses, perlindungan data, keamanan file, keamanan API, keamanan integrasi, dan keamanan operasional

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan kebijakan keamanan resmi PERPUSQU agar seluruh komponen sistem, mulai dari autentikasi, otorisasi, validasi input, penyimpanan file, pencarian, OCR, ekspor laporan, API internal, dan layanan pendukung, berjalan aman, konsisten, dan selaras dengan seluruh blueprint yang telah ditetapkan sebelumnya. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, tester, dan administrator sistem agar tidak ada implementasi yang mengabaikan kontrol dasar keamanan, tidak ada data privat yang bocor, tidak ada akses file tanpa kontrol, dan tidak ada alur proses yang melanggar prinsip least privilege serta defense in depth.

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

Aturan wajib:

1. Semua kebijakan keamanan harus realistis terhadap arsitektur monolith modular yang telah disepakati.
2. Semua kontrol keamanan harus selaras dengan schema, route, service, permission, dan workflow yang sudah ada.
3. Tidak boleh ada kebijakan keamanan yang menuntut tabel atau fitur yang belum disepakati seolah sudah tersedia.
4. Semua kontrol keamanan wajib diterapkan di backend, bukan hanya di frontend.
5. Seluruh data privat dan operasi sensitif harus memiliki kontrol akses yang jelas.
6. Audit dan log keamanan harus terpisah dari notifikasi biasa.
7. Keamanan harus diterapkan bertingkat pada request, service, storage, queue, search, dan deployment.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip umum keamanan
2. klasifikasi aset dan data
3. model ancaman fase 1
4. kebijakan autentikasi
5. kebijakan otorisasi
6. kebijakan session dan cookie
7. kebijakan validasi dan sanitasi input
8. kebijakan proteksi terhadap serangan web umum
9. kebijakan keamanan API
10. kebijakan keamanan file dan storage
11. kebijakan keamanan OCR, queue, dan search
12. kebijakan keamanan import dan export
13. kebijakan keamanan reporting
14. kebijakan keamanan konfigurasi dan secrets
15. kebijakan logging dan audit keamanan
16. kebijakan hardening server dan aplikasi
17. kebijakan backup dan recovery secara keamanan
18. kebijakan testing keamanan
19. batasan keamanan fase 1

## 5. Prinsip Umum Keamanan

Prinsip resmi keamanan PERPUSQU adalah:

1. Least privilege
2. Defense in depth
3. Secure by default
4. Deny by default
5. Separation of concerns
6. Data minimization
7. Fail safe
8. Traceability
9. Practical hardening
10. Incremental maturity

## 6. Sasaran Keamanan Fase 1

Sasaran keamanan fase 1 adalah:

1. memastikan hanya pengguna sah yang bisa masuk ke area admin
2. memastikan role dan permission bekerja konsisten
3. memastikan file digital privat tidak bisa diakses liar
4. memastikan OPAC publik hanya menampilkan data publik
5. memastikan input berbahaya ditolak atau dinetralisir
6. memastikan OCR, queue, dan export tidak membuka celah eksekusi atau kebocoran data
7. memastikan data penting dapat diaudit
8. memastikan konfigurasi sensitif tidak bocor di kode sumber atau UI

## 7. Acuan Kerangka Keamanan

PERPUSQU pada fase 1 mengikuti prinsip praktik baik umum dari:

1. OWASP Top 10 sebagai referensi risiko aplikasi web
2. prinsip least privilege untuk role dan permission
3. prinsip secure configuration untuk layanan infrastruktur
4. prinsip confidentiality, integrity, dan availability
5. prinsip logging dan accountability untuk aktivitas sensitif

Catatan:

1. dokumen ini tidak mengklaim sertifikasi formal
2. dokumen ini menjadi dasar implementasi keamanan praktis dan profesional

## 8. Klasifikasi Data Resmi

Data pada PERPUSQU diklasifikasikan menjadi 4 tingkat:

### 8.1 Public

Data yang boleh ditampilkan ke publik.

Contoh:

1. judul koleksi publik
2. pengarang
3. subjek
4. cover
5. info ketersediaan publik yang aman
6. profil perpustakaan
7. preview aset digital publik yang sah

### 8.2 Internal

Data untuk pengguna internal yang berwenang.

Contoh:

1. data anggota
2. status OCR internal
3. status indexing
4. ringkasan laporan admin
5. histori status item
6. file export internal

### 8.3 Restricted

Data sensitif yang hanya boleh diakses oleh role tertentu.

Contoh:

1. manajemen user
2. role dan permission
3. audit log
4. rule akses asset digital
5. hasil import error detail
6. queue failure detail

### 8.4 Sensitive Technical

Data teknis yang tidak boleh tampil ke pengguna biasa.

Contoh:

1. password hash
2. remember_token
3. env secrets
4. object storage key
5. checksum file
6. path storage privat
7. stack trace
8. internal job payload tertentu

## 9. Klasifikasi Aset Sistem

Aset utama yang wajib dilindungi:

1. akun user internal
2. role dan permission
3. data bibliographic record
4. data anggota
5. data pinjaman dan denda
6. file digital asset
7. metadata OCR
8. konfigurasi sistem
9. export file
10. audit log
11. credential layanan
12. index pencarian dan metadata turunannya

## 10. Model Ancaman Fase 1

Ancaman utama yang harus diantisipasi:

1. login tidak sah ke area admin
2. eskalasi hak akses
3. akses langsung ke file digital privat
4. SQL injection
5. XSS
6. CSRF
7. IDOR atau akses resource yang bukan miliknya
8. upload file tidak valid atau berbahaya
9. kebocoran detail teknis melalui error message
10. manipulasi parameter laporan atau export
11. penyalahgunaan API internal
12. data leakage dari search index publik
13. job queue disalahgunakan atau di-trigger berlebihan
14. misconfiguration pada storage, Redis, atau search engine

## 11. Security Domain Utama

Domain keamanan dibagi menjadi:

1. authentication security
2. authorization security
3. input security
4. file and storage security
5. data exposure security
6. process security
7. infrastructure security
8. audit and traceability
9. operational security

## 12. Kebijakan Authentication

### 12.1 Area yang Membutuhkan Authentication

Authentication wajib untuk:

1. seluruh area admin
2. API internal
3. preview privat asset
4. download privat asset
5. import
6. export
7. setting
8. access control management

Authentication tidak wajib untuk:

1. OPAC publik
2. beranda publik
3. detail record publik
4. preview aset publik yang memang sah

### 12.2 Mekanisme Login

Fase 1 memakai:

1. login berbasis session web Laravel
2. user login dengan username atau email
3. password tersimpan dalam bentuk hash aman

### 12.3 Password Policy

Aturan minimum password:

1. minimal 8 karakter
2. disimpan menggunakan hash yang aman
3. tidak pernah disimpan plaintext
4. tidak pernah dikirim ulang ke UI setelah disimpan

Rekomendasi tambahan:

1. kombinasi huruf besar, huruf kecil, angka, dan simbol
2. fase 1 minimal menjaga panjang dan hashing
3. kompleksitas lebih tinggi dapat diperkuat di implementasi tanpa melanggar blueprint

### 12.4 Reset Password

Reset password hanya boleh dilakukan oleh:

1. user sendiri pada perubahan password pribadi
2. admin berwenang untuk user tertentu

Aturan:

1. aksi reset password harus diaudit
2. password baru tidak boleh ditampilkan lagi setelah sukses
3. reset password tidak boleh dilakukan oleh role yang tidak berwenang

### 12.5 Status Akun dan Login

User dengan `is_active = 0` tidak boleh login.

Aturan:

1. validasi login tidak cukup hanya memeriksa password
2. AuthenticationService wajib memeriksa status akun
3. notifikasi login gagal tidak boleh membocorkan apakah username atau password yang salah secara detail berlebihan

## 13. Kebijakan Session dan Cookie

### 13.1 Session Security

Session admin wajib:

1. memakai cookie aman
2. tidak menaruh data sensitif berlebihan di client
3. mengikuti timeout yang wajar
4. dirotasi saat login sukses

### 13.2 Cookie Policy

Cookie session harus:

1. HttpOnly
2. Secure pada HTTPS
3. SameSite sesuai kebutuhan aplikasi
4. tidak menyimpan data domain sensitif mentah

### 13.3 Session Invalidation

Session harus diinvalidasi pada:

1. logout
2. perubahan password sendiri, bila dipilih
3. deaktivasi user, untuk sesi baru dan sebaiknya sesi berjalan berikutnya
4. penggantian peran yang kritis, bila diperlukan kebijakan lebih ketat

## 14. Kebijakan Authorization

### 14.1 Prinsip Otorisasi

1. route wajib dilindungi middleware auth dan permission yang sesuai
2. service wajib memeriksa policy atau permission untuk aksi sensitif
3. view hanya menampilkan tombol yang diizinkan, tetapi backend tetap wajib memeriksa ulang
4. tidak boleh mengandalkan sembunyikan tombol sebagai kontrol utama

### 14.2 Sumber Otorisasi Resmi

Sumber resmi:

1. role
2. permission
3. policy resource tertentu
4. workflow state
5. access rules asset digital

### 14.3 Deny by Default

Jika permission tidak jelas:

1. akses harus ditolak
2. route tidak boleh dibuka
3. tombol aksi tidak boleh muncul
4. API internal harus mengembalikan forbidden

## 15. Kebijakan Policy Resource

Policy wajib diterapkan minimal pada:

1. User
2. BibliographicRecord
3. PhysicalItem
4. Member
5. Loan
6. DigitalAsset
7. ActivityLog

Aturan:

1. resource policy dipakai untuk aksi yang tergantung objek tertentu
2. permission umum tetap dipakai untuk akses modul
3. policy dan permission tidak boleh bertentangan

## 16. Kebijakan Role dan Permission Management

Manajemen role dan permission adalah area sensitif.

Aturan:

1. hanya role berwenang yang boleh mengubah role dan permission
2. perubahan role permission harus diaudit
3. UI harus jelas membedakan lihat dan ubah
4. role sensitif tidak boleh dikelola oleh role yang lebih rendah tanpa izin

## 17. Kebijakan Validasi dan Sanitasi Input

Semua input harus mengikuti 16_VALIDATION_RULES.md.

Aturan umum:

1. semua write request memakai Form Request
2. semua enum harus whitelist
3. semua foreign key harus diverifikasi
4. semua filter laporan dan search harus tervalidasi
5. sanitasi dasar dilakukan pada string, email, dan kode
6. business rule tetap diperiksa di service

## 18. Kebijakan Proteksi Serangan Web Umum

### 18.1 SQL Injection

Mitigasi:

1. gunakan Eloquent atau query builder
2. jangan membangun raw query dari input user tanpa binding aman
3. filter dan sort field harus whitelist

### 18.2 Cross Site Scripting

Mitigasi:

1. escape output Blade secara default
2. jangan render HTML mentah dari input user tanpa sanitasi yang sah
3. notifikasi, label, dan error harus di-escape
4. abstract atau notes yang ditampilkan harus diperlakukan aman

### 18.3 Cross Site Request Forgery

Mitigasi:

1. semua form admin memakai CSRF token
2. route write internal web wajib CSRF protected
3. API internal berbasis session harus tetap memperhatikan mekanisme perlindungan yang sesuai

### 18.4 Insecure Direct Object Reference

Mitigasi:

1. semua akses resource by id wajib melewati policy atau permission
2. asset preview privat wajib memeriksa access service
3. audit log detail tidak boleh dibuka hanya dengan menebak id
4. export file tidak boleh diambil tanpa pemeriksaan hak akses

### 18.5 Broken Access Control

Mitigasi:

1. middleware auth dan permission
2. policy resource
3. deny by default
4. UI action restriction
5. service recheck

## 19. Kebijakan Error Handling

Pesan error ke pengguna harus:

1. ringkas
2. aman
3. tidak teknis berlebihan
4. tidak membocorkan konfigurasi atau query internal

Yang tidak boleh tampil ke pengguna biasa:

1. stack trace
2. SQL statement
3. class path penuh
4. bucket name
5. storage path privat
6. internal env values

## 20. Kebijakan Logging dan Audit Keamanan

### 20.1 Audit Wajib

Aksi berikut wajib dapat diaudit:

1. login penting bila diperlukan kebijakan implementasi
2. reset password user
3. perubahan role dan permission
4. update settings
5. block dan unblock member
6. publish dan unpublish record
7. change item status
8. create loan dan return
9. upload asset
10. update access rule
11. run OCR
12. reindex trigger penting
13. import anggota
14. export laporan
15. retry queue

### 20.2 Log Teknis

Log teknis harus mencatat:

1. storage error
2. OCR failure
3. indexing failure
4. import parsing failure
5. export generation failure
6. integration failure
7. permission denial yang relevan untuk diagnosis

### 20.3 Pemisahan Audit dan Log Teknis

1. audit log untuk jejak aksi pengguna
2. application log untuk error teknis
3. dashboard alert bukan pengganti audit

## 21. Kebijakan Data Exposure

Prinsip:

1. tampilkan hanya data minimum yang diperlukan
2. data publik dan internal harus dipisahkan
3. API publik hanya mengembalikan field yang aman
4. laporan tidak boleh memuat field sensitif yang tidak diperlukan
5. notifikasi tidak boleh membocorkan data teknis

## 22. Kebijakan Keamanan OPAC

OPAC publik wajib:

1. hanya menampilkan record publik
2. hanya menampilkan asset preview yang sah
3. tidak mengembalikan path privat
4. tidak menampilkan status internal OCR atau queue detail
5. tidak menampilkan rule akses internal

Mitigasi:

1. hydration final dari MySQL
2. filter visibilitas final
3. DigitalAssetAccessService untuk preview publik
4. search index publik hanya memuat data aman

## 23. Kebijakan Keamanan Search

Mengacu ke 21_SEARCH_INDEXING_SPEC.md.

Aturan:

1. index publik hanya memuat record publik
2. OCR text privat tidak boleh masuk index publik
3. query search harus tervalidasi
4. field filter harus whitelist
5. result akhir tetap diverifikasi ke MySQL
6. public suggestion harus rate limited bila diaktifkan

## 24. Kebijakan Keamanan API

### 24.1 API Internal

API internal wajib:

1. session authenticated atau mekanisme auth resmi
2. permission checked
3. JSON response aman
4. tidak mengembalikan field sensitif yang tidak perlu

### 24.2 API Publik

API publik terbatas wajib:

1. hanya read only
2. rate limited
3. data publik saja
4. tidak membocorkan metadata privat
5. tidak menjadi jalur untuk mengambil file privat

### 24.3 Error Response API

Response API tidak boleh:

1. membocorkan stack trace
2. membocorkan struktur tabel
3. membocorkan internal path
4. membocorkan credential

## 25. Kebijakan File Upload

Semua upload file wajib tunduk pada:

1. 16_VALIDATION_RULES.md
2. 22_STORAGE_FILE_POLICY.md
3. 23_OCR_AND_DIGITAL_PROCESSING.md

Aturan:

1. format file dibatasi
2. ukuran file dibatasi
3. mime type diverifikasi
4. extension diverifikasi
5. file disimpan lewat service
6. metadata baru ditulis setelah file berhasil tersimpan
7. file temp dibersihkan

## 26. Kebijakan Storage Security

Storage adalah area sensitif.

Aturan:

1. public_assets dan private_assets wajib dipisahkan
2. file digital utama disimpan di private_assets
3. akses file privat hanya melalui aplikasi
4. object key mentah tidak boleh dijadikan izin akses
5. secret storage di env
6. bucket privat tidak boleh public list
7. checksum tidak boleh tampil ke publik

## 27. Kebijakan Asset Streaming

Preview dan download asset digital wajib melalui:

1. DigitalAssetAccessService
2. AssetStreamingService

Aturan:

1. service memeriksa publication_status
2. service memeriksa is_public
3. service memeriksa embargo
4. service memeriksa rule akses
5. controller tidak mengambil keputusan akses mentah

## 28. Kebijakan OCR Security

OCR processing wajib aman.

Aturan:

1. hanya PDF yang diproses
2. file sumber diambil dari private storage
3. temp files tidak publik
4. OCR dijalankan via queue
5. hasil OCR tidak otomatis publik
6. error OCR tidak membocorkan data teknis ke user umum
7. retry OCR hanya oleh role berwenang

## 29. Kebijakan Queue Security

Queue wajib dijaga karena memproses tugas sensitif.

Aturan:

1. job berat dijalankan async
2. payload job tidak boleh menyimpan credential
3. retry harus terkontrol
4. queue monitor hanya untuk role berwenang
5. queue failure dicatat
6. queue connection tidak terekspos ke UI

## 30. Kebijakan Import Security

Import anggota adalah titik risiko tinggi.

Aturan:

1. hanya role dengan permission `members.import`
2. file dibatasi xlsx dan csv
3. ukuran dibatasi
4. template harus tervalidasi
5. validasi per baris wajib
6. partial success harus terkendali
7. file import bersifat temp
8. hasil import dicatat
9. error detail hanya untuk admin yang berwenang

## 31. Kebijakan Export Security

Export laporan adalah area sensitif.

Aturan:

1. hanya role dengan `reports.export`
2. export mengikuti filter aktif yang tervalidasi
3. file export tidak memuat data sensitif yang tidak perlu
4. file export tidak boleh menjadi public artifact liar
5. export besar boleh async
6. export dicatat audit bila relevan
7. file export temp harus dibersihkan sesuai kebijakan

## 32. Kebijakan Reporting Security

Reporting harus menjaga data scope.

Aturan:

1. role hanya melihat laporan yang diizinkan
2. ekspor hanya data sesuai scope
3. digital access report fase 1 tidak boleh mengklaim user activity log yang belum ada
4. query tidak boleh membocorkan data private file
5. pimpinan boleh melihat agregat, bukan semua detail teknis bila tidak perlu

## 33. Kebijakan Konfigurasi dan Secrets

Secrets meliputi:

1. APP_KEY
2. DB password
3. Redis password
4. Meilisearch key
5. S3 or MinIO key dan secret
6. mail credentials
7. session secrets terkait aplikasi

Aturan:

1. semua secret hanya di environment
2. tidak pernah commit ke repository
3. tidak tampil di UI
4. tidak tampil di export atau debug page
5. rotasi secret harus dimungkinkan secara operasional

## 34. Kebijakan Debug dan Environment

Pada production:

1. APP_DEBUG harus off
2. error detail tidak tampil publik
3. test credential tidak dipakai
4. dummy storage atau dummy mailer tidak dipakai bila sudah production

Pada development:

1. debug boleh aktif
2. tetapi data sensitif nyata tetap harus dibatasi
3. environment dev tidak boleh diperlakukan sebagai alasan mengabaikan praktik aman

## 35. Kebijakan Hardening Aplikasi Laravel

Hardening minimum aplikasi:

1. gunakan env production yang benar
2. debug off di production
3. route write dilindungi auth dan CSRF
4. session cookies aman
5. rate limit endpoint publik terbatas yang relevan
6. gunakan package resmi yang dirawat
7. patch dependency berkala
8. disable unused features bila tidak diperlukan

## 36. Kebijakan Hardening Nginx dan Server

Hardening minimum server:

1. HTTPS wajib pada production
2. direct directory listing nonaktif
3. akses ke file sensitif seperti env, git, storage internal, dan log harus diblok
4. hanya port yang diperlukan dibuka
5. user proses server tidak boleh terlalu permisif
6. permission file sistem harus ketat
7. log akses dan error server harus tersedia

## 37. Kebijakan HTTPS dan Transport Security

Pada production:

1. semua area admin wajib HTTPS
2. OPAC publik juga sebaiknya HTTPS penuh
3. redirect HTTP ke HTTPS
4. cookie secure diaktifkan

## 38. Kebijakan Header Security

Header keamanan yang direkomendasikan:

1. X-Frame-Options atau kebijakan frame yang setara
2. X-Content-Type-Options
3. Referrer-Policy
4. Content-Security-Policy, bila diimplementasikan bertahap
5. Strict-Transport-Security, pada HTTPS production yang stabil

Catatan:

1. CSP dapat diimplementasikan bertahap agar tidak mengganggu Blade, Livewire, dan PDF.js
2. header final disesuaikan dengan kompatibilitas aplikasi

## 39. Kebijakan Rate Limiting

Rate limiting direkomendasikan untuk:

1. login attempts
2. public suggestion API
3. public metadata API
4. OCR dispatch API internal bila diperlukan
5. export async trigger bila ada risiko abuse

Aturan:

1. rate limit publik lebih ketat
2. rate limit internal tetap mempertimbangkan usability admin
3. rate limit tidak boleh memblok normal operations secara berlebihan

## 40. Kebijakan Password Storage

Password wajib:

1. di-hash dengan algoritma aman yang didukung framework
2. tidak pernah di-log
3. tidak pernah diekspor
4. tidak pernah dikirim via notifikasi biasa setelah penyimpanan

## 41. Kebijakan Data Retention dari Sudut Keamanan

Retensi harus memperhatikan:

1. file temp import
2. file temp export
3. file temp OCR
4. obsolete file replacement
5. log teknis
6. audit log

Aturan:

1. data temp harus dibersihkan
2. data yang masih diperlukan audit tidak boleh dihapus liar
3. cleanup job harus aman dan tercatat bila sensitif

## 42. Kebijakan Backup Security

Backup harus:

1. melindungi database
2. melindungi object storage penting
3. dilindungi aksesnya
4. tidak terbuka publik
5. tidak menggunakan nama file yang membocorkan data sensitif berlebihan

Aturan:

1. backup bukan alasan melonggarkan security
2. restore harus mengikuti otorisasi operasional
3. backup credential dan aksesnya harus dibatasi

## 43. Kebijakan Dependency Security

Dependency dan package pihak ketiga harus:

1. relevan
2. aktif dirawat
3. tidak berlebihan
4. dipantau update keamanannya
5. digunakan sesuai fungsi resmi

Prioritas package sensitif:

1. auth and permission
2. activity log
3. media or storage helpers
4. queue dashboard
5. PDF and OCR support

## 44. Kebijakan Data Minimization

Prinsip:

1. UI hanya menampilkan data yang diperlukan
2. API hanya mengembalikan field yang diperlukan
3. laporan hanya mengekspor kolom yang relevan
4. index publik hanya memuat field aman
5. audit tidak perlu menyimpan seluruh payload besar bila tidak perlu

## 45. Kebijakan Secure Coding

Aturan secure coding minimum:

1. jangan hardcode secret
2. jangan bypass validation rules
3. jangan bypass service layer
4. jangan menulis raw SQL dari input user tanpa binding aman
5. jangan percaya input frontend
6. jangan mengandalkan hidden field untuk otorisasi
7. jangan render HTML mentah tanpa sanitasi
8. jangan log data sensitif sembarangan
9. jangan expose path private
10. jangan buat shortcut akses file

## 46. Kebijakan Incident Handling Dasar

Jika terjadi insiden keamanan atau indikasi kuat:

1. batasi akses ke fitur terdampak
2. catat waktu dan gejala
3. identifikasi jalur yang terdampak
4. amankan log dan bukti teknis
5. lakukan perbaikan terukur
6. audit pasca insiden dilakukan oleh admin berwenang

Fase 1:

1. belum membutuhkan modul incident management khusus
2. tetapi prinsip respons dasar wajib siap

## 47. Kebijakan Security Testing

Pengujian keamanan minimum wajib mencakup:

1. user inactive tidak bisa login
2. user tanpa permission tidak bisa akses route sensitif
3. tombol tersembunyi bukan satu satunya perlindungan
4. asset privat tidak bisa diakses lewat tebakan URL
5. record non publik tidak tampil di OPAC
6. OCR text privat tidak bocor ke search publik
7. file upload non valid ditolak
8. CSRF protection aktif pada form admin
9. error tidak membocorkan stack trace
10. export tanpa permission ditolak
11. import tanpa permission ditolak
12. API internal tanpa auth ditolak
13. API publik hanya mengembalikan data aman
14. filter dan sort injection tidak berhasil
15. queue retry tanpa hak akses ditolak

## 48. Skenario Keamanan Kritis

### 48.1 Skenario A, User Menebak URL Asset Privat

Hasil yang diharapkan:

1. akses ditolak
2. tidak ada file yang ter-stream
3. tidak ada path privat yang bocor
4. log internal dapat mencatat kejadian bila perlu

### 48.2 Skenario B, User Tanpa Permission Mengakses Export

Hasil yang diharapkan:

1. akses ditolak
2. file tidak dibuat
3. pesan aman ditampilkan
4. audit atau log denial dapat tercatat

### 48.3 Skenario C, Asset Publik Berubah Menjadi Unpublished

Hasil yang diharapkan:

1. preview publik tidak lagi tersedia
2. search publik tidak lagi menampilkan kontribusi asset
3. reindex dipicu
4. access service menolak permintaan berikutnya

### 48.4 Skenario D, Import File Berbahaya atau Tidak Valid

Hasil yang diharapkan:

1. file ditolak
2. data tidak ditulis
3. user menerima pesan validasi
4. log teknis dapat mencatat kegagalan

## 49. Batasan Keamanan Fase 1

Fase 1 belum mewajibkan:

1. MFA
2. SSO
3. SIEM integration
4. antivirus terintegrasi penuh
5. DLP enterprise
6. database encryption at column level khusus
7. notification center security analytics
8. IDS atau IPS khusus aplikasi
9. device fingerprinting
10. session anomaly detection lanjutan

Catatan:

1. ketiadaan fitur di atas tidak berarti keamanan diabaikan
2. fase 1 fokus pada kontrol inti yang paling berdampak

## 50. Kontrol Keamanan Wajib Fase 1

Kontrol berikut wajib ada:

1. auth session untuk admin
2. password hashing
3. permission dan policy
4. CSRF protection
5. validation rules
6. safe file upload
7. private asset access control
8. public data filtering
9. safe error handling
10. audit log untuk aksi sensitif
11. secure environment secrets
12. HTTPS production
13. queue and OCR control
14. export and import control

## 51. Mapping Kontrol Keamanan ke Area

| Area | Kontrol Utama |
|---|---|
| Login | auth, password hash, inactive check, rate limit opsional |
| Access control | role, permission, policy, deny by default |
| Form admin | validation, CSRF, sanitasi |
| File upload | mime check, size check, private storage, service layer |
| Asset preview | DigitalAssetAccessService, streaming aman |
| OPAC | publik only filter, hydration MySQL, no private path |
| Search | safe indexing, no private OCR leak |
| OCR | queue only, temp file cleanup, no public leak |
| Import | file validation, row validation, permission |
| Export | permission, filtered output, temp storage, audit |
| Reporting | role scope, safe fields |
| API internal | auth, permission, safe JSON |
| API publik | public only, rate limit, safe response |
| Settings | restricted access, audit |
| Audit | activity log, safe access |

## 52. Prioritas Implementasi Keamanan

### Prioritas P1

1. auth dan permission hardening
2. policy resource utama
3. CSRF dan validation
4. private asset access control
5. safe file upload
6. secure error handling
7. audit log sensitif
8. HTTPS production
9. secret management

### Prioritas P2

1. rate limiting publik
2. header security
3. queue hardening
4. import and export security refinement
5. log review dan dashboard alert operasional

### Prioritas P3

1. MFA future scope
2. SSO future scope
3. advanced monitoring
4. deeper dependency scanning
5. advanced anomaly detection

## 53. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 29_AUDIT_LOG_SPEC.md
2. 30_ERROR_CODE.md
3. 31_TEST_PLAN.md
4. 32_TEST_SCENARIO.md
5. 33_DEPLOYMENT_GUIDE.md
6. 34_ENV_CONFIGURATION.md
7. 35_BACKUP_AND_RECOVERY.md
8. 36_PERFORMANCE_GUIDE.md
9. 37_CODING_STANDARD.md
10. 38_TREE.md
11. 39_TRACEABILITY_MATRIX.md
12. 41_BACKEND_CHECKLIST.md
13. 42_FRONTEND_CHECKLIST.md
14. 45_SMOKE_TEST_CHECKLIST.md
15. 46_UAT_CHECKLIST.md

Aturan:

1. audit log spec harus menguatkan jejak aksi sensitif yang disebut di dokumen ini
2. error code document harus menjaga error aman dan tidak bocor
3. test plan wajib memuat security scenarios utama
4. deployment guide harus memuat HTTPS, env, server hardening, dan access control storage
5. coding standard harus memuat secure coding rules yang sejalan
6. checklists backend dan frontend harus memverifikasi kontrol keamanan inti

## 54. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. seluruh area sensitif sudah punya kontrol keamanan minimum
2. kontrol auth, permission, policy, dan state saling selaras
3. file privat, OCR, search, API, import, dan export sudah memiliki aturan aman
4. tidak ada kontrol keamanan yang bertentangan dengan schema atau service layer
5. role dan permission tetap menjadi dasar otorisasi
6. data publik dan privat dibedakan jelas
7. kebijakan aman namun tetap realistis untuk fase 1

## 55. Kesimpulan

Dokumen Security Policy ini menetapkan kebijakan keamanan resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 27. Dokumen ini memastikan bahwa autentikasi, otorisasi, validasi input, perlindungan file privat, keamanan OPAC, keamanan search, keamanan OCR, keamanan import export, keamanan API, serta keamanan konfigurasi dan infrastruktur diterapkan secara bertingkat dan praktis sesuai ruang lingkup fase 1. Semua implementasi keamanan PERPUSQU wajib merujuk dokumen ini.

END OF 28_SECURITY_POLICY.md
