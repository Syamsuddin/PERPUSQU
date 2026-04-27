# 32_TEST_SCENARIO.md

## 1. Nama Dokumen

Test Scenario Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint skenario pengujian rinci

### 2.3 Status Dokumen

Resmi, acuan wajib pelaksanaan test case, validasi hasil, pencatatan defect, dan verifikasi kesiapan fitur PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan skenario pengujian rinci untuk seluruh fitur inti PERPUSQU agar tim pengembang, AI Agent, reviewer, tester, dan user penguji memiliki acuan operasional yang jelas saat melakukan verifikasi sistem. Dokumen ini menjadi turunan langsung dari 31_TEST_PLAN.md dan wajib selaras dengan seluruh blueprint PERPUSQU yang telah ditulis sebelumnya.

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

Aturan wajib:

1. Semua skenario uji harus mengacu pada aturan dan ruang lingkup fase 1.
2. Semua hasil yang diharapkan harus konsisten dengan workflow, validation, security, dan error code resmi.
3. Tidak boleh ada skenario yang menguji fitur di luar blueprint seolah sudah tersedia.
4. Skenario uji harus bisa diturunkan menjadi test case manual maupun otomatis.
5. Skenario ini menjadi dasar bagi smoke test, UAT, dan regression test.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. struktur penulisan skenario uji
2. kode skenario uji
3. skenario per modul
4. skenario happy path
5. skenario negative path
6. skenario security
7. skenario integrasi
8. skenario reporting
9. skenario import export
10. skenario OCR dan search
11. skenario audit dan error handling
12. skenario UAT inti

## 5. Struktur Skenario Uji

Setiap skenario uji minimal memiliki unsur:

1. kode skenario
2. nama skenario
3. modul
4. prioritas
5. precondition
6. langkah uji
7. hasil yang diharapkan
8. referensi blueprint
9. jenis uji
10. severity bila gagal

## 6. Klasifikasi Prioritas Skenario

Prioritas resmi:

1. P1, wajib lulus sebelum UAT
2. P2, wajib lulus sebelum final go live
3. P3, penting namun dapat menyusul setelah inti stabil

## 7. Klasifikasi Jenis Uji pada Skenario

Jenis uji yang dipakai dalam dokumen ini:

1. Unit
2. Feature
3. Integration
4. UI Functional
5. Security
6. Reporting
7. Import Export
8. OCR Search
9. UAT

## 8. Format Kode Skenario

Format kode yang direkomendasikan:
`TS-{MODUL}-{NOMOR}`

Contoh:

1. TS-AUTH-001
2. TS-CAT-005
3. TS-CIRC-010
4. TS-DIG-012
5. TS-OPAC-007

## 9. Modul Skenario Resmi

Modul pengujian rinci:

1. AUTH
2. ACCESS
3. CORE
4. MASTER
5. CATALOG
6. COLLECTION
7. MEMBER
8. CIRCULATION
9. DIGITAL
10. OPAC
11. REPORT
12. IMPORT
13. EXPORT
14. AUDIT
15. API
16. SECURITY
17. INTEGRATION
18. ERROR

## 10. Skenario AUTH

### TS-AUTH-001

Nama:
Login berhasil dengan akun aktif

Modul:
AUTH

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User aktif tersedia
2. Password benar
3. Role aktif tersedia

Langkah:

1. Buka halaman login
2. Isi username atau email yang valid
3. Isi password yang benar
4. Klik Login

Hasil yang Diharapkan:

1. User masuk ke dashboard
2. Session aktif
3. Menu tampil sesuai role
4. Tidak ada pesan error
5. Redirect sesuai route dashboard

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 09_ROUTE_MAP.md
3. 18_UI_UX_STANDARD.md
4. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-AUTH-002

Nama:
Login gagal dengan password salah

Modul:
AUTH

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User aktif tersedia

Langkah:

1. Buka halaman login
2. Isi username atau email valid
3. Isi password salah
4. Klik Login

Hasil yang Diharapkan:

1. Login ditolak
2. Pesan login gagal tampil
3. Session tidak terbentuk
4. Error code yang dipetakan konsisten dengan AUTH_401_INVALID_CREDENTIALS
5. Tidak ada detail teknis bocor

Referensi:

1. 24_NOTIFICATION_RULES.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-AUTH-003

Nama:
Login ditolak untuk akun inactive

Modul:
AUTH

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User tersedia
2. is_active = 0
3. Password benar

Langkah:

1. Login dengan kredensial user inactive

Hasil yang Diharapkan:

1. Login ditolak
2. Pesan akun tidak aktif tampil
3. Error code konsisten dengan AUTH_403_ACCOUNT_INACTIVE
4. Session tidak terbentuk

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-AUTH-004

Nama:
Logout berhasil

Modul:
AUTH

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User sudah login

Langkah:

1. Klik logout dari menu user

Hasil yang Diharapkan:

1. Session dibersihkan
2. Redirect ke login
3. Route admin berikutnya butuh login ulang

Referensi:

1. 09_ROUTE_MAP.md
2. 18_UI_UX_STANDARD.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
High

### TS-AUTH-005

Nama:
Reset password oleh admin berwenang

Modul:
AUTH

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Admin berwenang login
2. User target tersedia

Langkah:

1. Buka halaman user management
2. Pilih reset password
3. Konfirmasi aksi

Hasil yang Diharapkan:

1. Password user berubah sesuai kebijakan
2. Flash success tampil
3. Audit log tercatat
4. Tidak ada password lama atau hash tampil di UI

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 24_NOTIFICATION_RULES.md
3. 28_SECURITY_POLICY.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

## 11. Skenario ACCESS

### TS-ACCESS-001

Nama:
Role tanpa permission tidak melihat menu terlarang

Modul:
ACCESS

Prioritas:
P1

Jenis Uji:
UI Functional

Precondition:

1. User login dengan role terbatas

Langkah:

1. Login sebagai role terbatas
2. Lihat sidebar dan menu

Hasil yang Diharapkan:

1. Menu yang tidak diizinkan tidak tampil
2. Menu yang diizinkan tampil normal
3. Sidebar konsisten dengan permission matrix

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 08_MENU_MAP.md
3. 18_UI_UX_STANDARD.md

Severity bila gagal:
High

### TS-ACCESS-002

Nama:
Route sensitif ditolak untuk role tanpa permission

Modul:
ACCESS

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. User login tanpa permission terkait

Langkah:

1. Akses manual URL route sensitif

Hasil yang Diharapkan:

1. Akses ditolak
2. HTTP status dan pesan aman sesuai konteks
3. Tidak ada data sensitif tampil
4. Error code konsisten dengan SEC_403_PERMISSION_DENIED atau SEC_403_POLICY_DENIED

Referensi:

1. 09_ROUTE_MAP.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-ACCESS-003

Nama:
Policy resource menolak akses ke objek tertentu

Modul:
ACCESS

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. User login
2. Ada resource yang tidak boleh diakses role tersebut

Langkah:

1. Akses detail resource via id
2. Coba aksi edit atau view detail

Hasil yang Diharapkan:

1. Policy menolak akses
2. Pesan aman tampil
3. Tidak ada bocor old_values atau metadata sensitif

Referensi:

1. 28_SECURITY_POLICY.md
2. 29_AUDIT_LOG_SPEC.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

## 12. Skenario CORE

### TS-CORE-001

Nama:
Update institution profile berhasil

Modul:
CORE

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Admin berwenang login

Langkah:

1. Buka halaman institution profile
2. Ubah data dasar
3. Simpan

Hasil yang Diharapkan:

1. Data tersimpan
2. Flash success tampil
3. Audit log tercatat
4. Data tampil konsisten pada area publik bila relevan

Referensi:

1. 12_SERVICE_LAYER.md
2. 18_UI_UX_STANDARD.md
3. 24_NOTIFICATION_RULES.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-CORE-002

Nama:
Update operational settings memengaruhi circulation rule baru

Modul:
CORE

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Admin berwenang login
2. Setting loan_max_active_loans diketahui

Langkah:

1. Ubah loan_max_active_loans
2. Simpan
3. Coba transaksi pinjam dengan member yang mendekati batas baru

Hasil yang Diharapkan:

1. Setting baru dipakai pada transaksi berikutnya
2. Rule lama tidak dipakai lagi untuk transaksi baru
3. Audit log setting tercatat

Referensi:

1. 12_SERVICE_LAYER.md
2. 17_WORKFLOW_STATE_MACHINE.md
3. 25_REPORTING_SPEC.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

## 13. Skenario MASTER DATA

### TS-MASTER-001

Nama:
Tambah pengarang berhasil

Modul:
MASTER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User dengan permission authors.create login

Langkah:

1. Buka form tambah pengarang
2. Isi nama valid
3. Simpan

Hasil yang Diharapkan:

1. Data author tersimpan
2. Tampil di list
3. Flash success tampil
4. Audit bila diaktifkan untuk master data

Referensi:

1. 08_MENU_MAP.md
2. 16_VALIDATION_RULES.md
3. 24_NOTIFICATION_RULES.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Medium

### TS-MASTER-002

Nama:
Tambah pengarang gagal karena duplikat

Modul:
MASTER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Author dengan nama sama atau nilai unik terkait sudah ada

Langkah:

1. Input author yang bentrok
2. Simpan

Hasil yang Diharapkan:

1. Validasi atau penolakan domain terjadi
2. Pesan error tampil
3. Tidak ada data ganda tersimpan
4. Error code konsisten dengan VAL_422_DUPLICATE_VALUE atau BUS_409_RESOURCE_ALREADY_EXISTS sesuai implementasi

Referensi:

1. 16_VALIDATION_RULES.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-MASTER-003

Nama:
Master data yang masih dipakai tidak dapat dihapus

Modul:
MASTER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Master data sedang dipakai oleh record atau entity lain

Langkah:

1. Coba hapus master data tersebut

Hasil yang Diharapkan:

1. Proses ditolak
2. Pesan yang sesuai tampil
3. Error code konsisten dengan BUS_409_RESOURCE_IN_USE
4. Data referensi tetap utuh

Referensi:

1. 13_MODEL_MAP.md
2. 14_SCHEMA.sql
3. 30_ERROR_CODE.md

Severity bila gagal:
High

## 14. Skenario CATALOG

### TS-CAT-001

Nama:
Buat bibliographic record draft berhasil

Modul:
CATALOG

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Semua master data terkait tersedia

Langkah:

1. Buka form tambah katalog
2. Isi field wajib
3. Simpan

Hasil yang Diharapkan:

1. Record tersimpan
2. publication_status = draft
3. Tampil di list admin
4. Tidak tampil di OPAC publik
5. Audit create record tercatat

Referensi:

1. 14_SCHEMA.sql
2. 16_VALIDATION_RULES.md
3. 17_WORKFLOW_STATE_MACHINE.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CAT-002

Nama:
Publish record berhasil bila metadata minimum lengkap

Modul:
CATALOG

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Record draft lengkap
2. Semua field publish minimum terpenuhi

Langkah:

1. Klik publish

Hasil yang Diharapkan:

1. publication_status berubah menjadi published
2. Flash success tampil
3. Audit tercatat
4. Reindex dipicu
5. OPAC dapat menemukan record jika is_public = 1

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 24_NOTIFICATION_RULES.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CAT-003

Nama:
Publish record ditolak bila metadata minimum belum lengkap

Modul:
CATALOG

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Record draft ada
2. Metadata minimum belum lengkap

Langkah:

1. Klik publish

Hasil yang Diharapkan:

1. Publish ditolak
2. Status tetap draft
3. Pesan error sesuai domain tampil
4. Error code konsisten dengan BUS_409_RECORD_PUBLISH_NOT_ALLOWED

Referensi:

1. 16_VALIDATION_RULES.md
2. 17_WORKFLOW_STATE_MACHINE.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-CAT-004

Nama:
Unpublish record menghilangkan visibilitas publik

Modul:
CATALOG

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Record published dan public

Langkah:

1. Klik unpublish
2. Cek OPAC
3. Cek hasil search publik

Hasil yang Diharapkan:

1. publication_status menjadi unpublished
2. Record tidak tampil di OPAC
3. Dokumen index publik ditarik atau tidak dihydrate lagi
4. Audit tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 19_OPAC_UX_FLOW.md
3. 21_SEARCH_INDEXING_SPEC.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CAT-005

Nama:
Update relasi author memicu reindex record

Modul:
CATALOG

Prioritas:
P2

Jenis Uji:
Integration

Precondition:

1. Record published ada
2. Search aktif

Langkah:

1. Ubah author record
2. Simpan
3. Jalankan reindex otomatis atau tunggu worker
4. Cari record dengan nama author baru

Hasil yang Diharapkan:

1. Record ditemukan dengan author baru
2. Ringkasan author pada hasil tampil benar
3. Tidak ada author lama jika relasi dihapus

Referensi:

1. 12_SERVICE_LAYER.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 27_INTEGRATION_SPEC.md

Severity bila gagal:
High

## 15. Skenario COLLECTION

### TS-COLL-001

Nama:
Tambah physical item berhasil

Modul:
COLLECTION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Bibliographic record tersedia

Langkah:

1. Buka form tambah item
2. Pilih record induk
3. Isi barcode dan data wajib
4. Simpan

Hasil yang Diharapkan:

1. Item tersimpan
2. item_status default sesuai desain
3. Ringkasan total item record bertambah
4. Audit create item tercatat

Referensi:

1. 14_SCHEMA.sql
2. 16_VALIDATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-COLL-002

Nama:
Tambah physical item gagal karena barcode duplikat

Modul:
COLLECTION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Barcode sudah dipakai item lain

Langkah:

1. Isi barcode yang sudah ada
2. Simpan

Hasil yang Diharapkan:

1. Proses ditolak
2. Pesan validasi tampil
3. Tidak ada item baru tersimpan

Referensi:

1. 16_VALIDATION_RULES.md
2. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-COLL-003

Nama:
Ubah status item ke repair berhasil bila transisi sah

Modul:
COLLECTION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Item available

Langkah:

1. Pilih ubah status
2. Ganti ke repair
3. Simpan

Hasil yang Diharapkan:

1. item_status berubah ke repair
2. Histori status tercatat
3. Audit tercatat
4. Item tidak bisa dipinjam lagi

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-COLL-004

Nama:
Transisi status item ditolak bila tidak sah

Modul:
COLLECTION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Item berada pada status yang tidak mengizinkan transisi tertentu

Langkah:

1. Coba ubah ke status yang tidak sah

Hasil yang Diharapkan:

1. Proses ditolak
2. Status lama tetap
3. Error code konsisten dengan BUS_409_ITEM_STATUS_CHANGE_NOT_ALLOWED

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 30_ERROR_CODE.md

Severity bila gagal:
High

## 16. Skenario MEMBER

### TS-MEMBER-001

Nama:
Tambah anggota berhasil

Modul:
MEMBER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Data fakultas dan program studi tersedia bila diperlukan

Langkah:

1. Buka form tambah anggota
2. Isi field wajib
3. Simpan

Hasil yang Diharapkan:

1. Member tersimpan
2. Status aktif sesuai default
3. Nomor anggota unik
4. Audit create member tercatat

Referensi:

1. 14_SCHEMA.sql
2. 16_VALIDATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-MEMBER-002

Nama:
Block anggota berhasil

Modul:
MEMBER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Member aktif tersedia

Langkah:

1. Klik block member
2. Isi alasan jika diperlukan
3. Konfirmasi

Hasil yang Diharapkan:

1. is_blocked = 1
2. Status derived menjadi ACTIVE_BLOCKED atau INACTIVE_BLOCKED sesuai kondisi
3. Flash success tampil
4. Audit tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 24_NOTIFICATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-MEMBER-003

Nama:
Unblock anggota berhasil

Modul:
MEMBER

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Member diblokir

Langkah:

1. Klik unblock
2. Konfirmasi

Hasil yang Diharapkan:

1. is_blocked = 0
2. Status derived kembali sesuai kondisi aktif
3. Audit tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-MEMBER-004

Nama:
Member guest boleh tanpa faculty dan study program

Modul:
MEMBER

Prioritas:
P2

Jenis Uji:
Feature

Precondition:

1. Form tambah anggota siap

Langkah:

1. Pilih member_type guest
2. Kosongkan faculty dan study program
3. Simpan

Hasil yang Diharapkan:

1. Proses berhasil
2. Validasi tidak menolak jika memang rule mengizinkan
3. Data tersimpan benar

Referensi:

1. 16_VALIDATION_RULES.md
2. 26_IMPORT_EXPORT_SPEC.md

Severity bila gagal:
Medium

## 17. Skenario CIRCULATION

### TS-CIRC-001

Nama:
Loan berhasil untuk member aktif dan item available

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Member ACTIVE_READY
2. Item AVAILABLE
3. Batas pinjam belum tercapai

Langkah:

1. Buka halaman peminjaman
2. Pilih anggota
3. Scan atau input barcode item
4. Proses pinjam

Hasil yang Diharapkan:

1. Loan tercipta dengan status active
2. due_date terisi
3. item_status berubah ke loaned
4. Flash success tampil
5. Audit create loan tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 24_NOTIFICATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CIRC-002

Nama:
Loan ditolak untuk member blocked

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Member ACTIVE_BLOCKED
2. Item AVAILABLE

Langkah:

1. Coba proses pinjam

Hasil yang Diharapkan:

1. Loan tidak tercipta
2. item_status tetap available
3. Pesan penolakan tampil
4. Error code konsisten dengan BUS_409_MEMBER_BLOCKED

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 24_NOTIFICATION_RULES.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-CIRC-003

Nama:
Loan ditolak untuk item tidak available

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Member ACTIVE_READY
2. Item status bukan available

Langkah:

1. Coba proses pinjam

Hasil yang Diharapkan:

1. Loan tidak tercipta
2. Error code konsisten dengan BUS_409_ITEM_NOT_AVAILABLE
3. Pesan sesuai tampil

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-CIRC-004

Nama:
Return berhasil tanpa denda

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Loan active
2. Belum overdue
3. Kondisi kembali normal

Langkah:

1. Buka pengembalian
2. Input item
3. Proses kembali dengan kondisi normal

Hasil yang Diharapkan:

1. Loan status menjadi returned
2. returned_at terisi
3. ReturnTransaction tercipta
4. Item status menjadi available
5. Fine tidak tercipta
6. Audit return tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CIRC-005

Nama:
Return berhasil dengan denda keterlambatan

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Loan active
2. due_date sudah lewat
3. setting fine_daily_amount aktif

Langkah:

1. Proses return

Hasil yang Diharapkan:

1. Loan returned
2. Item kembali sesuai kondisi
3. Fine tercipta
4. nominal denda sesuai rumus
5. Flash atau alert denda tampil
6. Audit return dan create fine tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 24_NOTIFICATION_RULES.md
3. 25_REPORTING_SPEC.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-CIRC-006

Nama:
Renew berhasil pada loan aktif yang memenuhi syarat

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Loan active
2. allow_renewal true
3. batas renew belum tercapai

Langkah:

1. Klik renew
2. Konfirmasi

Hasil yang Diharapkan:

1. Loan tetap active
2. due_date berubah
3. LoanRenewal tercipta
4. Flash success tampil
5. Audit renew tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 24_NOTIFICATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-CIRC-007

Nama:
Renew ditolak bila melebihi batas

Modul:
CIRCULATION

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Loan active
2. jumlah renew sudah mencapai batas

Langkah:

1. Klik renew

Hasil yang Diharapkan:

1. Renew ditolak
2. due_date tidak berubah
3. Error code konsisten dengan BUS_409_RENEWAL_NOT_ALLOWED

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-CIRC-008

Nama:
Return dengan kondisi item repair

Modul:
CIRCULATION

Prioritas:
P2

Jenis Uji:
Integration

Precondition:

1. Loan active
2. Item kembali butuh perbaikan

Langkah:

1. Proses return
2. Pilih kondisi repair

Hasil yang Diharapkan:

1. Loan returned
2. Item status menjadi repair
3. ReturnTransaction tercipta
4. Audit tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

## 18. Skenario DIGITAL REPOSITORY

### TS-DIG-001

Nama:
Upload digital asset PDF berhasil

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Bibliographic record tersedia
2. User punya permission upload asset
3. File PDF valid tersedia

Langkah:

1. Buka form upload asset
2. Pilih record induk
3. Isi metadata wajib
4. Pilih file PDF
5. Simpan

Hasil yang Diharapkan:

1. File tersimpan di private storage
2. Metadata DigitalAsset tersimpan
3. ocr_status awal sesuai kebijakan
4. index_status awal sesuai kebijakan
5. Audit upload asset tercatat

Referensi:

1. 22_STORAGE_FILE_POLICY.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-DIG-002

Nama:
Upload asset gagal untuk file non PDF

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. User login
2. File non PDF tersedia

Langkah:

1. Coba unggah file non PDF sebagai asset digital

Hasil yang Diharapkan:

1. Proses ditolak
2. Pesan validasi tampil
3. Tidak ada asset tercipta
4. Error code konsisten dengan FILE_422_INVALID_TYPE atau FILE_422_INVALID_MIME

Referensi:

1. 16_VALIDATION_RULES.md
2. 22_STORAGE_FILE_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-DIG-003

Nama:
Publish digital asset berhasil

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Asset draft valid tersedia
2. Rule publish terpenuhi

Langkah:

1. Klik publish asset

Hasil yang Diharapkan:

1. publication_status menjadi published
2. Audit publish asset tercatat
3. Reindex dipicu bila relevan
4. Jika is_public dan rule sesuai, asset dapat muncul sebagai preview publik

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-DIG-004

Nama:
Preview privat berhasil untuk user berwenang

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Asset ada
2. User punya hak preview privat

Langkah:

1. Buka preview internal asset

Hasil yang Diharapkan:

1. File di-stream aman
2. PDF.js menampilkan file
3. Path privat tidak bocor
4. Tidak ada direct object link mentah di UI

Referensi:

1. 18_UI_UX_STANDARD.md
2. 22_STORAGE_FILE_POLICY.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-DIG-005

Nama:
Preview privat ditolak untuk user tanpa hak

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Asset ada
2. User login tanpa permission

Langkah:

1. Akses route preview privat

Hasil yang Diharapkan:

1. Akses ditolak
2. Tidak ada file ter-stream
3. Error code konsisten dengan SEC_403_PRIVATE_ASSET_ACCESS_DENIED

Referensi:

1. 28_SECURITY_POLICY.md
2. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-DIG-006

Nama:
Asset embargoed tidak tersedia untuk preview publik

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Asset published
2. is_public = 1
3. is_embargoed = 1
4. embargo_until masih di masa depan

Langkah:

1. Akses preview publik asset

Hasil yang Diharapkan:

1. Preview ditolak
2. Pesan aman tampil
3. Tidak ada file bocor
4. Error code konsisten dengan BUS_409_ASSET_EMBARGO_ACTIVE atau OPAC_404_ASSET_PREVIEW_NOT_PUBLIC sesuai implementasi aman

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 19_OPAC_UX_FLOW.md
3. 28_SECURITY_POLICY.md
4. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

## 19. Skenario OCR DAN INDEXING

### TS-OCR-001

Nama:
Dispatch OCR berhasil

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Feature

Precondition:

1. Asset layak OCR
2. User punya permission digital_assets.run_ocr

Langkah:

1. Klik Run OCR

Hasil yang Diharapkan:

1. ocr_status berubah ke queued
2. Flash success tampil
3. Audit run_ocr tercatat
4. Job antrean terbentuk

Referensi:

1. 23_OCR_AND_DIGITAL_PROCESSING.md
2. 24_NOTIFICATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-OCR-002

Nama:
OCR success menyimpan OcrText dan memicu reindex

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Asset queued
2. Queue worker aktif
3. File OCR valid

Langkah:

1. Jalankan worker
2. Tunggu proses selesai

Hasil yang Diharapkan:

1. ocr_status menjadi success
2. OcrText tersimpan
3. extracted_at terisi
4. index_status masuk siklus reindex yang relevan
5. Search dapat diperbarui sesuai rule visibilitas

Referensi:

1. 21_SEARCH_INDEXING_SPEC.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 27_INTEGRATION_SPEC.md

Severity bila gagal:
Blocker

### TS-OCR-003

Nama:
OCR failure mengubah status ke failed

Modul:
DIGITAL

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Asset queued
2. File atau kondisi OCR dibuat gagal

Langkah:

1. Jalankan worker

Hasil yang Diharapkan:

1. ocr_status menjadi failed
2. Error teknis dicatat di log internal
3. Asset utama tetap valid
4. Tidak ada kebocoran detail teknis ke UI publik

Referensi:

1. 23_OCR_AND_DIGITAL_PROCESSING.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-OCR-004

Nama:
Retry OCR dari failed berhasil kembali ke queued

Modul:
DIGITAL

Prioritas:
P2

Jenis Uji:
Feature

Precondition:

1. Asset ocr_status failed
2. User berwenang login

Langkah:

1. Klik Retry OCR

Hasil yang Diharapkan:

1. Status kembali queued
2. Audit retry_ocr tercatat
3. Pesan success tampil

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-IDX-001

Nama:
Reindex manual berhasil dijadwalkan

Modul:
DIGITAL

Prioritas:
P2

Jenis Uji:
Feature

Precondition:

1. Asset atau record relevan tersedia
2. User punya permission reindex

Langkah:

1. Klik Reindex

Hasil yang Diharapkan:

1. index_status berubah ke queued atau pending lalu queued
2. Pesan success tampil
3. Audit run_reindex tercatat

Referensi:

1. 21_SEARCH_INDEXING_SPEC.md
2. 24_NOTIFICATION_RULES.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

## 20. Skenario OPAC

### TS-OPAC-001

Nama:
Beranda OPAC tampil normal

Modul:
OPAC

Prioritas:
P1

Jenis Uji:
UI Functional

Precondition:

1. Situs publik aktif

Langkah:

1. Buka beranda OPAC

Hasil yang Diharapkan:

1. Search bar utama tampil
2. Header publik tampil
3. Link bantuan dan tentang berfungsi
4. Layout sesuai UI standard

Referensi:

1. 18_UI_UX_STANDARD.md
2. 19_OPAC_UX_FLOW.md

Severity bila gagal:
High

### TS-OPAC-002

Nama:
Search OPAC menemukan record publik

Modul:
OPAC

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Ada record published dan public
2. Search engine aktif

Langkah:

1. Cari judul atau author yang sesuai

Hasil yang Diharapkan:

1. Hasil pencarian tampil
2. Judul, author, jenis koleksi, dan availability tampil
3. Hanya record publik yang muncul

Referensi:

1. 19_OPAC_UX_FLOW.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-OPAC-003

Nama:
Search OPAC tidak menampilkan record draft

Modul:
OPAC

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Ada record draft atau unpublished yang cocok kata kunci

Langkah:

1. Cari kata kunci record non publik

Hasil yang Diharapkan:

1. Record non publik tidak muncul
2. Hasil hanya berisi record publik yang sah

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-OPAC-004

Nama:
Detail record publik menampilkan summary fisik dan digital

Modul:
OPAC

Prioritas:
P1

Jenis Uji:
UI Functional

Precondition:

1. Record publik tersedia
2. Memiliki item fisik dan atau asset digital

Langkah:

1. Buka detail record dari hasil pencarian

Hasil yang Diharapkan:

1. Metadata detail tampil
2. Summary item fisik tampil
3. Summary asset digital tampil
4. Tombol preview publik hanya tampil bila sah

Referensi:

1. 19_OPAC_UX_FLOW.md
2. 18_UI_UX_STANDARD.md

Severity bila gagal:
High

### TS-OPAC-005

Nama:
Preview publik berhasil untuk asset public accessible

Modul:
OPAC

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Asset published
2. is_public = 1
3. embargo tidak aktif
4. access rule mengizinkan

Langkah:

1. Klik preview publik

Hasil yang Diharapkan:

1. Viewer tampil
2. File di-stream aman
3. Tidak ada path privat bocor

Referensi:

1. 19_OPAC_UX_FLOW.md
2. 22_STORAGE_FILE_POLICY.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-OPAC-006

Nama:
Pencarian OPAC kosong menampilkan empty state yang benar

Modul:
OPAC

Prioritas:
P2

Jenis Uji:
UI Functional

Precondition:

1. Query tidak punya hasil

Langkah:

1. Masukkan query yang tidak cocok

Hasil yang Diharapkan:

1. Empty state tampil
2. Pesan ramah tampil
3. Tidak ada error teknis

Referensi:

1. 19_OPAC_UX_FLOW.md
2. 24_NOTIFICATION_RULES.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Medium

## 21. Skenario REPORTING

### TS-RPT-001

Nama:
Dashboard report menampilkan angka snapshot yang benar

Modul:
REPORT

Prioritas:
P1

Jenis Uji:
Reporting

Precondition:

1. Dataset uji tersedia

Langkah:

1. Buka dashboard report
2. Bandingkan angka dengan data seed atau query referensi

Hasil yang Diharapkan:

1. Semua metrik utama sesuai definisi rumus
2. Tidak ada angka negatif atau anomali
3. Scope sesuai role

Referensi:

1. 25_REPORTING_SPEC.md
2. 31_TEST_PLAN.md

Severity bila gagal:
Blocker

### TS-RPT-002

Nama:
Collection report filter bekerja benar

Modul:
REPORT

Prioritas:
P1

Jenis Uji:
Reporting

Precondition:

1. Tersedia record dengan variasi jenis, bahasa, dan status

Langkah:

1. Terapkan filter collection_type
2. Terapkan filter language
3. Terapkan filter publication_status

Hasil yang Diharapkan:

1. Tabel menampilkan data yang sesuai filter
2. Summary juga menyesuaikan filter bila didefinisikan demikian
3. Pagination mempertahankan filter

Referensi:

1. 16_VALIDATION_RULES.md
2. 18_UI_UX_STANDARD.md
3. 25_REPORTING_SPEC.md

Severity bila gagal:
High

### TS-RPT-003

Nama:
Circulation report menghitung overdue dengan benar

Modul:
REPORT

Prioritas:
P1

Jenis Uji:
Reporting

Precondition:

1. Ada loan active overdue dan non overdue

Langkah:

1. Buka circulation report
2. Aktifkan filter overdue bila ada

Hasil yang Diharapkan:

1. Total overdue sesuai rumus
2. Hanya loan active lewat due_date yang dihitung overdue
3. Returned loan tidak dihitung overdue aktif

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 25_REPORTING_SPEC.md

Severity bila gagal:
Blocker

### TS-RPT-004

Nama:
Fine report menghitung nominal outstanding dengan benar

Modul:
REPORT

Prioritas:
P1

Jenis Uji:
Reporting

Precondition:

1. Ada fine dengan status campuran

Langkah:

1. Buka fine report

Hasil yang Diharapkan:

1. Total nominal sesuai sum amount
2. Outstanding hanya menjumlah status outstanding
3. Tabel sesuai data aktual

Referensi:

1. 14_SCHEMA.sql
2. 25_REPORTING_SPEC.md

Severity bila gagal:
High

### TS-RPT-005

Nama:
Popular collection report mengurutkan record berdasarkan jumlah loan

Modul:
REPORT

Prioritas:
P2

Jenis Uji:
Reporting

Precondition:

1. Ada beberapa record dengan jumlah loan berbeda

Langkah:

1. Buka popular collection report
2. Bandingkan ranking dengan query referensi

Hasil yang Diharapkan:

1. Ranking desc sesuai jumlah loan
2. Judul dan jumlah pinjam tampil benar

Referensi:

1. 25_REPORTING_SPEC.md

Severity bila gagal:
Medium

### TS-RPT-006

Nama:
Digital repository report menampilkan status OCR dan index dengan benar

Modul:
REPORT

Prioritas:
P2

Jenis Uji:
Reporting

Precondition:

1. Ada asset dengan status OCR dan index beragam

Langkah:

1. Buka digital repository report

Hasil yang Diharapkan:

1. Summary asset sesuai data
2. OCR success, failed, indexed, failed index akurat
3. Tidak ada klaim usage analytics detail yang belum ada datanya

Referensi:

1. 23_OCR_AND_DIGITAL_PROCESSING.md
2. 25_REPORTING_SPEC.md

Severity bila gagal:
High

## 22. Skenario IMPORT

### TS-IMP-001

Nama:
Import anggota xlsx valid berhasil

Modul:
IMPORT

Prioritas:
P1

Jenis Uji:
Import Export

Precondition:

1. File xlsx valid tersedia
2. Header sesuai template
3. User punya permission members.import

Langkah:

1. Buka halaman import anggota
2. Upload file
3. Proses import

Hasil yang Diharapkan:

1. Data valid masuk ke database
2. Ringkasan hasil tampil
3. Audit import tercatat
4. File temp dikelola sesuai policy

Referensi:

1. 16_VALIDATION_RULES.md
2. 22_STORAGE_FILE_POLICY.md
3. 26_IMPORT_EXPORT_SPEC.md
4. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-IMP-002

Nama:
Import gagal untuk header template salah

Modul:
IMPORT

Prioritas:
P1

Jenis Uji:
Import Export

Precondition:

1. File import dengan header salah tersedia

Langkah:

1. Upload file
2. Proses import

Hasil yang Diharapkan:

1. Proses ditolak
2. Tidak ada data masuk
3. Error code konsisten dengan IMP_422_INVALID_HEADER

Referensi:

1. 26_IMPORT_EXPORT_SPEC.md
2. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-IMP-003

Nama:
Import partial success berjalan benar

Modul:
IMPORT

Prioritas:
P1

Jenis Uji:
Import Export

Precondition:

1. File mengandung campuran baris valid dan invalid

Langkah:

1. Upload file
2. Proses import

Hasil yang Diharapkan:

1. Baris valid tersimpan
2. Baris invalid gagal
3. Summary total, success, failed akurat
4. Tidak ada data korup
5. Audit import ringkas tercatat

Referensi:

1. 26_IMPORT_EXPORT_SPEC.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-IMP-004

Nama:
Import ditolak untuk file tanpa permission

Modul:
IMPORT

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. User login tanpa permission members.import

Langkah:

1. Akses halaman import atau submit request import

Hasil yang Diharapkan:

1. Akses ditolak
2. Tidak ada file diproses
3. Error aman tampil

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

## 23. Skenario EXPORT

### TS-EXP-001

Nama:
Export member report berhasil dengan filter aktif

Modul:
EXPORT

Prioritas:
P1

Jenis Uji:
Import Export

Precondition:

1. User punya permission reports.export
2. Filter laporan dapat diterapkan

Langkah:

1. Buka member report
2. Terapkan filter
3. Klik export

Hasil yang Diharapkan:

1. File export terbentuk
2. Nama file sesuai pola
3. Isi file mengikuti filter aktif
4. Audit export tercatat

Referensi:

1. 25_REPORTING_SPEC.md
2. 26_IMPORT_EXPORT_SPEC.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-EXP-002

Nama:
Export ditolak untuk user tanpa permission

Modul:
EXPORT

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. User login tanpa reports.export

Langkah:

1. Coba klik export

Hasil yang Diharapkan:

1. Akses ditolak
2. File tidak dibuat
3. Error code konsisten dengan EXP_403_NOT_ALLOWED

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-EXP-003

Nama:
Export digital repository report tidak memuat data sensitif

Modul:
EXPORT

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Data asset digital tersedia
2. Export dilakukan oleh user berwenang

Langkah:

1. Export digital repository report
2. Buka file hasil

Hasil yang Diharapkan:

1. Tidak ada file_path privat
2. Tidak ada checksum
3. Tidak ada access rule detail sensitif
4. Hanya kolom yang disetujui blueprint yang tampil

Referensi:

1. 22_STORAGE_FILE_POLICY.md
2. 25_REPORTING_SPEC.md
3. 26_IMPORT_EXPORT_SPEC.md
4. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

## 24. Skenario AUDIT

### TS-AUDIT-001

Nama:
Aksi publish record mencatat audit dengan benar

Modul:
AUDIT

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Record draft valid ada
2. User berwenang login

Langkah:

1. Publish record
2. Buka audit log

Hasil yang Diharapkan:

1. Event audit muncul
2. action sesuai
3. module_name sesuai
4. subject_type dan subject_id benar
5. old_values dan new_values ringkas sesuai

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
High

### TS-AUDIT-002

Nama:
Audit log tidak menampilkan data sensitif terlarang

Modul:
AUDIT

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Ada event sensitif seperti reset password atau upload asset

Langkah:

1. Buka detail audit

Hasil yang Diharapkan:

1. Password tidak tampil
2. Hash tidak tampil
3. Secret tidak tampil
4. Full private path tidak tampil
5. OCR text panjang penuh tidak tampil

Referensi:

1. 28_SECURITY_POLICY.md
2. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-AUDIT-003

Nama:
Akses audit log ditolak untuk role tanpa permission

Modul:
AUDIT

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. User login tanpa audit_logs.view

Langkah:

1. Akses halaman audit

Hasil yang Diharapkan:

1. Akses ditolak
2. Tidak ada daftar audit tampil

Referensi:

1. 07_ROLE_PERMISSION_MATRIX.md
2. 28_SECURITY_POLICY.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

## 25. Skenario API INTERNAL

### TS-API-001

Nama:
Lookup member berhasil untuk user berwenang

Modul:
API

Prioritas:
P2

Jenis Uji:
Feature

Precondition:

1. User login
2. Permission members.view atau circulation.view tersedia

Langkah:

1. Panggil endpoint lookup members dengan keyword valid

Hasil yang Diharapkan:

1. JSON response sukses
2. Struktur response sesuai contract
3. Data ringkas saja yang tampil
4. Tidak ada field sensitif tak perlu

Referensi:

1. 20_API_CONTRACT.md
2. 28_SECURITY_POLICY.md

Severity bila gagal:
High

### TS-API-002

Nama:
API internal ditolak tanpa auth

Modul:
API

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Tidak login

Langkah:

1. Panggil endpoint internal

Hasil yang Diharapkan:

1. Akses ditolak
2. Error code konsisten dengan AUTH_401_UNAUTHENTICATED

Referensi:

1. 20_API_CONTRACT.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

### TS-API-003

Nama:
API public suggestion hanya mengembalikan record publik

Modul:
API

Prioritas:
P2

Jenis Uji:
Security

Precondition:

1. Public suggestion diaktifkan
2. Ada record publik dan non publik dengan kata kunci serupa

Langkah:

1. Panggil endpoint suggestion publik

Hasil yang Diharapkan:

1. Hanya record publik muncul
2. Data minimal tampil
3. Tidak ada metadata internal bocor

Referensi:

1. 20_API_CONTRACT.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 28_SECURITY_POLICY.md

Severity bila gagal:
High

## 26. Skenario ERROR HANDLING

### TS-ERR-001

Nama:
Error code member blocked konsisten di UI dan log

Modul:
ERROR

Prioritas:
P2

Jenis Uji:
Integration

Precondition:

1. Member blocked tersedia

Langkah:

1. Coba loan
2. Amati UI
3. Cek log internal

Hasil yang Diharapkan:

1. UI menampilkan pesan manusiawi
2. Log internal memuat BUS_409_MEMBER_BLOCKED
3. Tidak ada detail sensitif bocor

Referensi:

1. 24_NOTIFICATION_RULES.md
2. 29_AUDIT_LOG_SPEC.md
3. 30_ERROR_CODE.md

Severity bila gagal:
High

### TS-ERR-002

Nama:
Error file tidak ditemukan ditangani aman

Modul:
ERROR

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Metadata asset ada
2. File fisik hilang atau tidak terbaca

Langkah:

1. Akses preview asset

Hasil yang Diharapkan:

1. Pesan aman tampil
2. Error code sesuai FILE_404_NOT_FOUND atau FILE_500_STORAGE_READ_FAILED sesuai konteks
3. Tidak ada stack trace atau path storage tampil
4. Log teknis mencatat context aman

Referensi:

1. 22_STORAGE_FILE_POLICY.md
2. 28_SECURITY_POLICY.md
3. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

## 27. Skenario SECURITY DASAR

### TS-SEC-001

Nama:
CSRF protection aktif pada form admin

Modul:
SECURITY

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Form admin tersedia

Langkah:

1. Submit form tanpa token valid atau dengan token tidak sah

Hasil yang Diharapkan:

1. Request ditolak
2. Data tidak diproses
3. Error page atau redirect aman muncul

Referensi:

1. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-SEC-002

Nama:
Asset privat tidak dapat diakses dengan menebak URL

Modul:
SECURITY

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Asset privat ada
2. User publik atau user tanpa hak

Langkah:

1. Tebak route atau direct path asset

Hasil yang Diharapkan:

1. Akses ditolak
2. Tidak ada file bocor
3. Tidak ada path sensitif tampil

Referensi:

1. 22_STORAGE_FILE_POLICY.md
2. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-SEC-003

Nama:
Error publik tidak membocorkan stack trace

Modul:
SECURITY

Prioritas:
P1

Jenis Uji:
Security

Precondition:

1. Sistem dipaksa ke kondisi error terkontrol

Langkah:

1. Trigger error publik yang aman untuk test

Hasil yang Diharapkan:

1. UI hanya menampilkan pesan aman
2. Tidak ada stack trace
3. Tidak ada path file aplikasi
4. Tidak ada query SQL tampil

Referensi:

1. 28_SECURITY_POLICY.md
2. 30_ERROR_CODE.md

Severity bila gagal:
Blocker

## 28. Skenario INTEGRATION

### TS-INT-001

Nama:
Create loan mengubah loan dan item dalam satu alur konsisten

Modul:
INTEGRATION

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Member eligible
2. Item available

Langkah:

1. Proses loan
2. Cek database loan
3. Cek database physical_items
4. Cek UI pinjaman aktif

Hasil yang Diharapkan:

1. Loan active tercipta
2. Item status loaned
3. UI dan DB konsisten
4. Audit tercatat

Referensi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 27_INTEGRATION_SPEC.md
3. 29_AUDIT_LOG_SPEC.md

Severity bila gagal:
Blocker

### TS-INT-002

Nama:
OCR success memengaruhi search publik hanya bila rule publik sah

Modul:
INTEGRATION

Prioritas:
P1

Jenis Uji:
Integration

Precondition:

1. Asset publik ada
2. OCR success
3. Record publik
4. Search aktif

Langkah:

1. Jalankan OCR sampai sukses
2. Reindex
3. Cari term yang hanya ada di OCR text
4. Ulangi pada asset privat sebagai pembanding

Hasil yang Diharapkan:

1. Asset publik memberi kontribusi pada search publik bila diizinkan
2. Asset privat tidak memberi kontribusi publik
3. Tidak ada kebocoran OCR privat

Referensi:

1. 21_SEARCH_INDEXING_SPEC.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 27_INTEGRATION_SPEC.md
4. 28_SECURITY_POLICY.md

Severity bila gagal:
Blocker

### TS-INT-003

Nama:
Export async atau sync menghasilkan file sesuai storage policy

Modul:
INTEGRATION

Prioritas:
P2

Jenis Uji:
Integration

Precondition:

1. Permission export tersedia
2. Laporan memiliki data

Langkah:

1. Jalankan export
2. Verifikasi file hasil
3. Verifikasi lokasi penyimpanan sementara
4. Verifikasi cleanup sesuai kebijakan bila berlaku

Hasil yang Diharapkan:

1. File terbentuk benar
2. File tidak menjadi public artifact liar
3. Lokasi penyimpanan sesuai policy
4. Notifikasi sesuai mode proses

Referensi:

1. 22_STORAGE_FILE_POLICY.md
2. 24_NOTIFICATION_RULES.md
3. 26_IMPORT_EXPORT_SPEC.md
4. 27_INTEGRATION_SPEC.md

Severity bila gagal:
High

## 29. Skenario UAT INTI

### TS-UAT-001

Nama:
Pustakawan membuat katalog sampai siap terbit

Modul:
UAT

Prioritas:
P1

Jenis Uji:
UAT

Precondition:

1. User pustakawan tersedia
2. Master data tersedia

Langkah:

1. Login sebagai pustakawan
2. Tambah bibliographic record
3. Isi author, subject, dan metadata inti
4. Simpan draft
5. Publish

Hasil yang Diharapkan:

1. Alur terasa logis
2. Tidak ada field penting yang membingungkan
3. Publish berhasil bila data lengkap
4. Record siap dipakai modul lain

Referensi:

1. 06_USE_CASE.md
2. 18_UI_UX_STANDARD.md
3. 31_TEST_PLAN.md

Severity bila gagal:
High

### TS-UAT-002

Nama:
Petugas sirkulasi memproses loan dan return lengkap

Modul:
UAT

Prioritas:
P1

Jenis Uji:
UAT

Precondition:

1. Member valid
2. Item valid

Langkah:

1. Login sebagai petugas sirkulasi
2. Proses pinjam
3. Cari pinjaman aktif
4. Proses kembali

Hasil yang Diharapkan:

1. Alur cepat dan mudah dipahami
2. Validasi status anggota dan item jelas
3. Hasil transaksi sesuai harapan operasional

Referensi:

1. 06_USE_CASE.md
2. 18_UI_UX_STANDARD.md
3. 31_TEST_PLAN.md

Severity bila gagal:
Blocker

### TS-UAT-003

Nama:
Operator repositori mengunggah asset lalu menjalankan OCR

Modul:
UAT

Prioritas:
P1

Jenis Uji:
UAT

Precondition:

1. Record induk tersedia
2. PDF valid tersedia

Langkah:

1. Login sebagai operator repositori
2. Upload asset
3. Cek detail asset
4. Jalankan OCR
5. Cek status

Hasil yang Diharapkan:

1. Alur upload mudah
2. Status proses jelas
3. User memahami apakah OCR berhasil atau gagal
4. Tidak perlu akses teknis rumit

Referensi:

1. 18_UI_UX_STANDARD.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 31_TEST_PLAN.md

Severity bila gagal:
High

### TS-UAT-004

Nama:
Pimpinan membuka dashboard dan export laporan

Modul:
UAT

Prioritas:
P2

Jenis Uji:
UAT

Precondition:

1. User pimpinan tersedia
2. Permission laporan tersedia

Langkah:

1. Login sebagai pimpinan
2. Buka dashboard report
3. Buka salah satu laporan
4. Export laporan

Hasil yang Diharapkan:

1. Summary mudah dibaca
2. Filter mudah digunakan
3. File export siap dipakai administratif

Referensi:

1. 18_UI_UX_STANDARD.md
2. 25_REPORTING_SPEC.md
3. 31_TEST_PLAN.md

Severity bila gagal:
Medium

### TS-UAT-005

Nama:
Pengguna publik mencari koleksi dan membuka preview publik

Modul:
UAT

Prioritas:
P1

Jenis Uji:
UAT

Precondition:

1. Record publik tersedia
2. Asset publik preview tersedia

Langkah:

1. Buka OPAC
2. Cari kata kunci
3. Buka detail
4. Buka preview

Hasil yang Diharapkan:

1. Hasil pencarian relevan
2. Detail mudah dipahami
3. Preview aman dan mudah dibuka

Referensi:

1. 19_OPAC_UX_FLOW.md
2. 31_TEST_PLAN.md

Severity bila gagal:
High

## 30. Skenario Regression Minimum

Regression minimum setelah perubahan besar wajib mengulang:

1. TS-AUTH-001
2. TS-ACCESS-002
3. TS-CAT-002
4. TS-COLL-003
5. TS-MEMBER-002
6. TS-CIRC-001
7. TS-CIRC-004
8. TS-DIG-001
9. TS-OCR-002
10. TS-OPAC-002
11. TS-OPAC-003
12. TS-RPT-001
13. TS-IMP-001
14. TS-EXP-001
15. TS-AUDIT-001
16. TS-SEC-002
17. TS-INT-001

## 31. Matriks Skenario P1 Inti

| Kode | Modul | Fokus |
|---|---|---|
| TS-AUTH-001 | AUTH | login berhasil |
| TS-AUTH-003 | AUTH | akun inactive ditolak |
| TS-ACCESS-002 | ACCESS | route sensitif ditolak |
| TS-CAT-001 | CATALOG | create draft record |
| TS-CAT-002 | CATALOG | publish record |
| TS-COLL-001 | COLLECTION | create item |
| TS-MEMBER-001 | MEMBER | create member |
| TS-MEMBER-002 | MEMBER | block member |
| TS-CIRC-001 | CIRCULATION | loan berhasil |
| TS-CIRC-002 | CIRCULATION | loan ditolak member blocked |
| TS-CIRC-004 | CIRCULATION | return berhasil |
| TS-DIG-001 | DIGITAL | upload asset |
| TS-DIG-004 | DIGITAL | preview privat aman |
| TS-OCR-002 | DIGITAL | OCR success dan reindex |
| TS-OPAC-002 | OPAC | search publik |
| TS-OPAC-003 | OPAC | non publik tidak tampil |
| TS-RPT-001 | REPORT | dashboard akurat |
| TS-IMP-001 | IMPORT | import valid |
| TS-EXP-001 | EXPORT | export valid |
| TS-AUDIT-001 | AUDIT | audit publish record |
| TS-SEC-002 | SECURITY | asset privat aman |
| TS-INT-001 | INTEGRATION | loan sinkron domain |

## 32. Aturan Dokumentasi Hasil Uji

Untuk setiap skenario uji, hasil harus dicatat minimal:

1. status pass atau fail
2. tanggal uji
3. penguji
4. bukti uji
5. defect id bila fail
6. catatan penting

## 33. Aturan Bukti Uji

Bukti uji yang direkomendasikan:

1. screenshot halaman
2. response API
3. query hasil database
4. file export hasil
5. audit record
6. log teknis aman
7. hasil search

Aturan:

1. bukti tidak boleh memuat secret
2. bukti tidak boleh memuat password
3. bukti publik tidak boleh memuat private path

## 34. Aturan Penanganan Gagal Uji

Jika satu skenario gagal:

1. buat defect record
2. tandai severity
3. hubungkan ke blueprint terkait
4. lakukan perbaikan
5. lakukan retest
6. lakukan regression pada area terdampak

## 35. Hubungan dengan Dokumen Lanjutan

Dokumen ini menjadi dasar langsung untuk:

1. 45_SMOKE_TEST_CHECKLIST.md
2. 46_UAT_CHECKLIST.md

Dokumen ini juga akan sangat dibantu oleh:

1. 38_TREE.md
2. 39_TRACEABILITY_MATRIX.md
3. 41_BACKEND_CHECKLIST.md
4. 42_FRONTEND_CHECKLIST.md

## 36. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 33_DEPLOYMENT_GUIDE.md
2. 34_ENV_CONFIGURATION.md
3. 35_BACKUP_AND_RECOVERY.md
4. 36_PERFORMANCE_GUIDE.md
5. 38_TREE.md
6. 39_TRACEABILITY_MATRIX.md
7. 41_BACKEND_CHECKLIST.md
8. 42_FRONTEND_CHECKLIST.md
9. 45_SMOKE_TEST_CHECKLIST.md
10. 46_UAT_CHECKLIST.md

Aturan:

1. Deployment Guide harus mengacu pada smoke scenario minimum
2. Performance Guide harus menurunkan skenario uji performa dari area kritis
3. Traceability Matrix harus menghubungkan use case dan route ke skenario uji
4. Checklists harus memakai skenario P1 dan P2 sebagai dasar verifikasi

## 37. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. seluruh modul inti memiliki skenario utama
2. seluruh area P1 sudah memiliki skenario rinci
3. seluruh area sensitif memiliki skenario security
4. seluruh area integrasi utama memiliki skenario integration
5. seluruh area reporting, import, export, OCR, dan OPAC telah tercakup
6. semua hasil yang diharapkan selaras dengan blueprint sebelumnya
7. tidak ada fitur di luar scope fase 1 yang diuji seolah aktif

## 38. Kesimpulan

Dokumen Test Scenario ini menetapkan skenario pengujian rinci PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 31. Dokumen ini memastikan bahwa seluruh fungsi inti, role, state transition, keamanan, file, OCR, search, reporting, import export, audit, dan integrasi dapat diverifikasi secara operasional dan sistematis. Semua pelaksanaan pengujian rinci PERPUSQU wajib merujuk dokumen ini.

END OF 32_TEST_SCENARIO.md
