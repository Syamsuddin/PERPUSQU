# 26_IMPORT_EXPORT_SPEC.md

## 1. Nama Dokumen

Import Export Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi impor data, ekspor data, template file, pipeline proses, dan aturan operasional

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan fitur impor dan ekspor data, termasuk validasi file, pemetaan kolom, strategi pemrosesan, logging, notifikasi, keamanan, dan keterkaitan dengan reporting

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan spesifikasi resmi impor dan ekspor data pada PERPUSQU agar proses pertukaran data berjalan konsisten, aman, terukur, dan sesuai dengan seluruh blueprint yang telah disusun sebelumnya. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar fitur import dan export tidak melampaui scope fase 1, tidak menabrak schema, tidak mengabaikan service layer, dan tidak menghasilkan file atau data yang bertentangan dengan role, workflow, validation, storage policy, reporting, dan audit.

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

Aturan wajib:

1. Fitur import dan export hanya boleh mengelola data yang sudah didukung schema dan service.
2. Fase 1 tidak boleh memaksakan bulk import besar untuk domain yang belum punya service dan validasi resmi.
3. Semua file import dan export wajib diproses melalui service layer resmi.
4. File hasil export wajib mengikuti storage policy.
5. Proses import dan export wajib tunduk pada role dan permission matrix.
6. Proses import dan export wajib tunduk pada validation rules dan audit policy.
7. Proses import tidak boleh langsung menulis ke database dari controller.
8. Proses export tidak boleh membocorkan data sensitif.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip umum import dan export
2. jenis operasi yang didukung fase 1
3. format file resmi
4. template import
5. mapping kolom
6. pipeline import
7. pipeline export
8. validasi file
9. validasi konten
10. strategi duplicate handling
11. strategi transaction dan partial failure
12. notifikasi pengguna
13. audit dan logging
14. penyimpanan file import dan export
15. penghapusan file sementara
16. pengujian fitur

## 5. Prinsip Umum Import Export

Prinsip resmi import export PERPUSQU adalah:

1. Terbatas pada kebutuhan nyata.
2. Aman.
3. Terkontrol.
4. Konsisten.
5. Dapat ditelusuri.
6. Tidak membypass service layer.
7. Tidak membuka celah data korup.
8. Tidak menambah kompleksitas di luar fase 1.

## 6. Cakupan Operasi Resmi Fase 1

### 6.1 Import yang Didukung Fase 1

Import resmi fase 1 yang didukung secara eksplisit adalah:

1. Import anggota massal terbatas

Dasar acuan:

1. `MemberImportService` sudah didefinisikan pada 12_SERVICE_LAYER.md
2. `MemberImportController` sudah disebut pada controller dan service map
3. `MemberImportRequest` sudah didefinisikan pada 16_VALIDATION_RULES.md

### 6.2 Export yang Didukung Fase 1

Export resmi fase 1 yang didukung adalah:

1. Export laporan koleksi
2. Export laporan anggota
3. Export laporan sirkulasi
4. Export laporan denda
5. Export laporan koleksi populer
6. Export laporan digital repository

Dasar acuan:

1. `ReportExportService` sudah didefinisikan pada 12_SERVICE_LAYER.md
2. route export resmi sudah didefinisikan pada 25_REPORTING_SPEC.md
3. request export resmi sudah didefinisikan pada 16_VALIDATION_RULES.md

### 6.3 Operasi yang Belum Wajib di Fase 1

Operasi berikut belum wajib diimplementasikan di fase 1:

1. import bibliographic record massal
2. import physical item massal
3. import master data massal umum
4. import digital asset massal
5. export full database
6. export audit log mentah ke publik
7. export OCR text penuh
8. export file aset digital massal

Catatan:

1. operasi di atas hanya boleh masuk bila ada revisi blueprint dan service resmi tambahan
2. fase 1 harus jujur membatasi diri pada import anggota dan export laporan

## 7. Tujuan Operasi Import Fase 1

Import anggota massal fase 1 bertujuan:

1. mempercepat input data anggota awal
2. mempermudah onboarding anggota kampus
3. mengurangi input manual satu per satu
4. menjaga konsistensi format data anggota

Import fase 1 tidak bertujuan:

1. menggantikan semua proses CRUD anggota biasa
2. menjadi integrasi SIAKAD penuh
3. memuat update kompleks lintas domain
4. menimpa data anggota tanpa kontrol

## 8. Tujuan Operasi Export Fase 1

Export fase 1 bertujuan:

1. mendukung kebutuhan administrasi laporan
2. memberi file unduhan sesuai filter aktif
3. mendukung pelaporan internal perpustakaan
4. memudahkan distribusi ringkas data dalam bentuk file

Export fase 1 tidak bertujuan:

1. menjadi saluran replikasi database
2. membuka data privat tanpa kontrol
3. mengekspor semua tabel mentah
4. membuat dump sistem dari UI

## 9. Kanal dan Mode Proses

### 9.1 Import

Mode resmi import:

1. upload file dari UI admin
2. proses sinkron untuk file kecil atau validasi awal
3. proses async untuk file yang lebih besar atau bila diputuskan oleh service

### 9.2 Export

Mode resmi export:

1. sinkron untuk laporan ringan
2. async untuk laporan besar atau export berat

Aturan:

1. mode final diputuskan oleh service
2. UI harus tetap memberi umpan balik yang jelas sesuai 24_NOTIFICATION_RULES.md

## 10. Format File Resmi

### 10.1 Format Import Resmi

Sesuai validation rules, format import anggota yang didukung:

1. xlsx
2. csv

### 10.2 Format Export Resmi

Sesuai reporting spec, format export yang direkomendasikan:

1. xlsx
2. csv
3. pdf, opsional bertahap

Aturan:

1. fase 1 minimal wajib mendukung xlsx atau csv
2. pdf untuk export laporan boleh masuk bertahap dan tidak wajib pada implementasi paling awal
3. format final yang aktif harus jelas di UI

## 11. Modul dan Service yang Terlibat

Service resmi yang wajib terlibat:

1. MemberImportService
2. MemberService
3. FacultyService
4. StudyProgramService
5. ReportExportService
6. CollectionReportService
7. MemberReportService
8. CirculationReportService
9. FineReportService
10. PopularCollectionReportService
11. DigitalAccessReportService
12. AuditLogService

Service tambahan yang direkomendasikan:

1. ImportTemplateService
2. ImportValidationService
3. ExportFileBuilderService

Catatan:

1. nama tambahan ini bersifat rekomendasi
2. implementasi boleh melebur ke service resmi selama tidak jadi god service

## 12. Controller dan Route yang Terlibat

Controller resmi yang terkait:

1. MemberImportController
2. CollectionReportController
3. MemberReportController
4. CirculationReportController
5. FineReportController
6. PopularCollectionReportController
7. DigitalAccessReportController

Route resmi:

1. `admin.members.import`
2. `admin.reports.collections.export`
3. `admin.reports.members.export`
4. `admin.reports.circulation.export`
5. `admin.reports.fines.export`
6. `admin.reports.popular_collections.export`
7. `admin.reports.digital_access.export`

## 13. Role dan Permission

### 13.1 Permission Import

Permission minimum untuk import anggota:

1. `members.import`

### 13.2 Permission Export

Permission minimum untuk export laporan:

1. `reports.export`

### 13.3 Role yang Relevan

Role yang secara realistis boleh memakai fitur ini:

1. Super Admin
2. Admin Perpustakaan
3. Pimpinan Perpustakaan, untuk export laporan
4. Role lain hanya bila permission diaktifkan secara eksplisit

## 14. Prinsip Template Import

Template import wajib:

1. sederhana
2. jelas
3. punya header tetap
4. punya petunjuk pengisian
5. tidak memiliki kolom liar
6. tidak mengandung formula yang berbahaya
7. selaras dengan schema member

## 15. Spesifikasi Template Import Anggota

### 15.1 Nama Template

Nama template yang direkomendasikan:
`template-import-anggota-perpusqu.xlsx`

### 15.2 Sheet yang Direkomendasikan

1. `DATA_ANGGOTA`
2. `PETUNJUK`

### 15.3 Kolom Resmi Sheet DATA_ANGGOTA

Kolom resmi yang direkomendasikan:

1. member_number
2. member_type
3. identity_number
4. name
5. email
6. phone
7. faculty_code
8. study_program_code
9. is_active
10. notes

### 15.4 Keterangan Kolom

1. `member_number`, wajib
2. `member_type`, wajib
3. `identity_number`, opsional
4. `name`, wajib
5. `email`, opsional
6. `phone`, opsional
7. `faculty_code`, opsional
8. `study_program_code`, opsional
9. `is_active`, opsional
10. `notes`, opsional

### 15.5 Nilai Enum yang Diizinkan

`member_type`:

1. student
2. lecturer
3. staff
4. alumni
5. guest

`is_active`:

1. 1
2. 0
3. true
4. false
5. ya atau tidak, boleh dikonversi di layer normalisasi bila diinginkan

## 16. Mapping Template Import ke Schema Members

| Kolom Template | Field Database | Keterangan |
|---|---|---|
| member_number | members.member_number | wajib, unik |
| member_type | members.member_type | wajib, enum resmi |
| identity_number | members.identity_number | opsional, unik bila diisi |
| name | members.name | wajib |
| email | members.email | opsional |
| phone | members.phone | opsional |
| faculty_code | members.faculty_id | dipetakan dari faculties.code |
| study_program_code | members.study_program_id | dipetakan dari study_programs.code |
| is_active | members.is_active | opsional, default 1 |
| notes | members.notes | opsional |

Catatan:

1. `is_blocked` tidak diimport langsung pada fase 1
2. `blocked_reason` dan `blocked_at` tidak termasuk template
3. data sensitif atau status administratif khusus tidak boleh masuk template massal awal

## 17. Aturan Resolusi Faculty dan Study Program

Resolusi relasi import anggota:

1. `faculty_code` dipetakan ke `faculties.code`
2. `study_program_code` dipetakan ke `study_programs.code`

Aturan:

1. bila `faculty_code` diisi, sistem harus mencari faculty yang aktif atau valid
2. bila `study_program_code` diisi, sistem harus mencari study program yang valid
3. bila keduanya diisi, konsistensi study program terhadap faculty wajib diperiksa
4. bila `member_type = guest`, kedua field boleh kosong
5. jika kode tidak ditemukan, baris harus gagal validasi

## 18. Aturan File Import

Aturan file import anggota:

1. format hanya xlsx atau csv
2. ukuran maksimum sesuai 16_VALIDATION_RULES.md, yaitu 10 MB
3. file harus punya header yang benar
4. file tidak boleh kosong
5. file tidak boleh mengandung kolom wajib yang hilang

## 19. Tahapan Pipeline Import Anggota

Pipeline import anggota resmi fase 1:

1. user memilih file
2. request divalidasi oleh `MemberImportRequest`
3. file diterima oleh `MemberImportController`
4. controller memanggil `MemberImportService`
5. service membaca file
6. service memvalidasi header
7. service memetakan kolom
8. service memvalidasi tiap baris
9. service menyiapkan hasil valid dan invalid
10. service menulis data valid ke database sesuai kebijakan
11. service menghasilkan ringkasan hasil
12. sistem menampilkan notifikasi dan hasil import

## 20. Mode Import yang Diizinkan

Mode import yang direkomendasikan pada fase 1:

### 20.1 Insert Only

Makna:

1. hanya menambah anggota baru
2. jika `member_number` sudah ada, baris ditolak

Status:

1. ini adalah mode paling aman dan direkomendasikan sebagai default fase 1

### 20.2 Upsert Terbatas

Makna:

1. menambah anggota baru
2. memperbarui anggota yang sudah ada berdasarkan `member_number`

Status:

1. opsional terbatas
2. hanya boleh diaktifkan jika service dan UI sudah benar benar jelas
3. bila belum pasti, jangan diaktifkan di implementasi awal

Rekomendasi blueprint:

1. fase 1 default memakai insert only
2. upsert hanya disiapkan sebagai opsi lanjutan

## 21. Validasi Import Level File

Validasi level file meliputi:

1. format file
2. ukuran file
3. header minimum
4. jumlah kolom minimum
5. jumlah baris tidak nol

Jika gagal:

1. seluruh proses ditolak
2. tidak ada transaksi data dijalankan
3. user menerima pesan validasi file

## 22. Validasi Import Level Baris

Validasi per baris meliputi:

1. member_number wajib dan unik
2. member_type wajib dan enum sah
3. identity_number unik bila diisi
4. name wajib
5. email valid bila diisi
6. faculty_code valid bila diisi
7. study_program_code valid bila diisi
8. relasi faculty dan study program konsisten
9. is_active valid bila diisi
10. panjang nilai tidak melebihi schema

## 23. Aturan Duplicate Handling Import

Duplicate handling import anggota:

### 23.1 Duplicate dalam File yang Sama

Jika `member_number` sama muncul lebih dari satu kali dalam satu file:

1. baris kedua dan seterusnya ditandai error
2. service harus melaporkan nomor baris yang bentrok

### 23.2 Duplicate dengan Database

Jika `member_number` sudah ada di database:

1. pada mode insert only, baris ditolak
2. pada mode upsert, baris diproses update bila diaktifkan

### 23.3 Duplicate identity_number

Jika `identity_number` duplikat:

1. baris ditolak kecuali kebijakan khusus menyatakan lain
2. karena schema sudah mengharuskan unik bila diisi

## 24. Strategi Transaksi Import

Karena import massal rentan error, strategi transaksi harus jelas.

### 24.1 Opsi Strategi

1. full rollback bila ada satu baris gagal
2. partial success dengan laporan baris gagal

### 24.2 Strategi Resmi Fase 1

Strategi resmi yang direkomendasikan:

1. partial success terkontrol

Alasan:

1. lebih praktis bagi admin
2. lebih cocok untuk import massal anggota
3. memudahkan perbaikan data file

Aturan:

1. setiap baris valid boleh diproses
2. baris gagal dilaporkan detail
3. ringkasan hasil wajib jelas
4. integritas per baris tetap dijaga dengan validasi dan transaction yang sesuai

## 25. Struktur Hasil Import

Hasil import minimal harus memuat:

1. total_rows
2. success_rows
3. failed_rows
4. skipped_rows, opsional
5. error_details per baris

Contoh struktur konseptual:

```json
{
  "total_rows": 100,
  "success_rows": 92,
  "failed_rows": 8,
  "errors": [
    {
      "row_number": 5,
      "field": "member_number",
      "message": "Nomor anggota sudah digunakan."
    }
  ]
}
````

## 26. Notifikasi Import

Mengacu ke 24_NOTIFICATION_RULES.md.

### 26.1 Jika Import Berhasil Sebagian

Pesan:

1. Import anggota selesai. 92 data berhasil, 8 data gagal.

### 26.2 Jika Import Gagal Total

Pesan:

1. Import anggota gagal diproses.

### 26.3 Jika File Tidak Valid

Pesan:

1. File import tidak valid.
2. Template tidak sesuai.
3. Kolom wajib tidak lengkap.

## 27. Audit dan Logging Import

Import anggota harus memiliki jejak yang jelas.

### 27.1 Audit Log

Aksi yang layak dicatat:

1. user memulai import anggota
2. hasil import anggota
3. jumlah data sukses dan gagal

### 27.2 Log Teknis

Log teknis dapat mencatat:

1. error parsing file
2. error mapping kolom
3. error row processing
4. error database

Aturan:

1. audit log ringkas untuk aksi pengguna
2. log teknis untuk diagnosis internal
3. jangan tampilkan stack trace ke UI

## 28. Storage Policy untuk File Import

File import mengikuti 22_STORAGE_FILE_POLICY.md.

Aturan:

1. file import bersifat sementara
2. file import tidak menjadi data permanen utama
3. file import dapat disimpan di temp storage
4. file import harus dibersihkan setelah proses selesai atau setelah waktu retensi pendek

Lokasi yang direkomendasikan:

1. `temp/uploads/imports/`

## 29. Template Download

Sistem disarankan menyediakan tombol unduh template import anggota.

Manfaat:

1. mengurangi error format
2. menyamakan header
3. mempercepat onboarding admin

Route yang direkomendasikan:

1. `admin.members.import.template`

Catatan:

1. jika route ini belum ditambahkan pada route map, harus dimasukkan secara resmi saat pembaruan route detail
2. template download adalah fitur pendukung yang sangat disarankan

## 30. Struktur Halaman Import Anggota

Halaman import anggota wajib memuat:

1. page header
2. penjelasan singkat format file
3. tombol unduh template
4. form upload file
5. batasan format dan ukuran
6. hasil ringkasan import
7. daftar error baris, bila ada

Aturan UI:

1. mengikuti 18_UI_UX_STANDARD.md
2. tidak menampilkan data sensitif
3. hasil import mudah dipahami

## 31. Export Laporan Resmi

Export laporan resmi fase 1 meliputi:

1. collection report export
2. member report export
3. circulation report export
4. fine report export
5. popular collection report export
6. digital access report export

Semua export harus menggunakan filter aktif yang sama dengan halaman laporan.

## 32. Aturan Umum Export

Aturan export resmi:

1. hanya untuk role dengan permission `reports.export`
2. format sesuai validation rules
3. file export hanya memuat data yang memang ditampilkan atau diringkas oleh laporan terkait
4. field sensitif dikeluarkan bila tidak relevan
5. export tidak boleh mengambil data di luar scope laporan

## 33. Pipeline Export Laporan

Pipeline export resmi:

1. user membuka halaman laporan
2. user memilih filter
3. user klik export
4. request export divalidasi
5. controller memanggil `ReportExportService`
6. service mengambil filter aktif
7. service memanggil report service terkait
8. service membangun file export
9. file disimpan sementara
10. user menerima file atau notifikasi siap unduh
11. audit dan log dicatat bila perlu

## 34. Format Export per Laporan

### 34.1 Collection Report

Format data minimum:

1. judul
2. jenis koleksi
3. bahasa
4. tahun terbit
5. penerbit
6. status publikasi
7. visibilitas publik
8. jumlah item fisik
9. jumlah aset digital

### 34.2 Member Report

Format data minimum:

1. nomor anggota
2. nama
3. tipe anggota
4. fakultas
5. program studi
6. status aktif
7. status blocked
8. tanggal registrasi administratif

### 34.3 Circulation Report

Format data minimum:

1. nomor anggota
2. nama anggota
3. barcode item
4. judul record
5. tanggal pinjam
6. jatuh tempo
7. tanggal kembali
8. status pinjaman
9. overdue indicator
10. jumlah perpanjangan

### 34.4 Fine Report

Format data minimum:

1. anggota
2. referensi pinjaman
3. jenis denda
4. amount
5. late_days
6. status
7. catatan
8. created_at

### 34.5 Popular Collection Report

Format data minimum:

1. ranking
2. judul
3. jenis koleksi
4. tahun terbit
5. total item fisik
6. jumlah transaksi pinjam

### 34.6 Digital Access Report

Format data minimum:

1. judul asset
2. record induk
3. asset_type
4. publication_status
5. is_public
6. is_embargoed
7. embargo_until
8. ocr_status
9. index_status
10. uploaded_at

## 35. Aturan Naming File Export

Sesuai 25_REPORTING_SPEC.md, format penamaan file yang direkomendasikan:

`perpusqu-{report_name}-{yyyyMMdd-HHmmss}.{ext}`

Contoh:

1. `perpusqu-member-report-20260420-104500.xlsx`
2. `perpusqu-circulation-report-20260420-104500.csv`

## 36. Storage Policy untuk File Export

File export mengikuti 22_STORAGE_FILE_POLICY.md.

Aturan:

1. file export bersifat sementara, kecuali kebijakan khusus mengharuskan retensi
2. file export disimpan di temp area atau storage terkontrol
3. file export tidak boleh menjadi public asset mentah tanpa kontrol
4. jika export langsung di-download, file sementara tetap perlu dikelola

Lokasi yang direkomendasikan:

1. `temp/export/`
2. `private_assets/reports/`, bila mode async dan retensi singkat dipakai

## 37. Export Sinkron dan Async

### 37.1 Export Sinkron

Dipakai bila:

1. jumlah data kecil atau sedang
2. file dapat dibangun cepat
3. pengalaman pengguna masih baik

### 37.2 Export Async

Dipakai bila:

1. jumlah data besar
2. proses file berat
3. server perlu menghindari timeout
4. user perlu notifikasi saat file siap

Rekomendasi fase 1:

1. export sinkron untuk kebanyakan laporan
2. export async disiapkan untuk laporan besar bila diperlukan

## 38. Notifikasi Export

Mengacu ke 24_NOTIFICATION_RULES.md.

### 38.1 Export Sinkron Berhasil

Pesan:

1. Export laporan berhasil.

### 38.2 Export Async Dijadwalkan

Pesan:

1. Export laporan berhasil dijadwalkan.

### 38.3 Export Async Selesai

Pesan:

1. File export siap diunduh.
2. email opsional boleh dikirim bila kanal email aktif

### 38.4 Export Gagal

Pesan:

1. Export laporan gagal diproses.

## 39. Audit dan Logging Export

Export laporan layak dicatat karena menghasilkan artefak file.

### 39.1 Audit Log

Aksi yang dapat dicatat:

1. user melakukan export
2. jenis laporan yang diekspor
3. waktu export

### 39.2 ReportExportHistory

Jika fitur ini diaktifkan:

1. simpan exported_by
2. report_type
3. filter_payload
4. file_name
5. created_at

### 39.3 Log Teknis

Catat:

1. gagal generate file
2. gagal simpan file export
3. gagal kirim email opsional
4. gagal cleanup file sementara

## 40. Aturan Keamanan Export

Export tidak boleh memuat:

1. password
2. token
3. checksum asset
4. file_path privat
5. access rule internal detail yang tidak relevan
6. error_message OCR mentah
7. stack trace
8. data role atau permission kecuali memang laporan itu dirancang untuk admin access dan telah ada scope-nya

## 41. Aturan Template Export

File export harus:

1. punya header jelas
2. konsisten dengan nama kolom yang ramah pengguna
3. tidak memakai nama field teknis mentah bila tidak perlu
4. menjaga urutan kolom stabil
5. mendukung encoding yang aman, terutama untuk csv

## 42. Header Kolom yang Direkomendasikan

Gunakan label ramah pengguna, contoh:

1. "Nomor Anggota"
2. "Nama Anggota"
3. "Jenis Koleksi"
4. "Status Publikasi"
5. "Tanggal Pinjam"
6. "Jatuh Tempo"
7. "Status OCR"
8. "Status Index"

Hindari label teknis mentah seperti:

1. member_number
2. publication_status
3. ocr_status
   kecuali file memang untuk kebutuhan teknis internal khusus dan telah disetujui

## 43. Aturan Partial Failure pada Export

Jika export async melibatkan banyak langkah:

1. jika build file gagal, proses ditandai gagal
2. tidak boleh ada file setengah jadi diberikan ke user
3. user menerima notifikasi gagal
4. log teknis dicatat

## 44. Batasan Import Export Fase 1

Fase 1 memiliki batasan tegas:

1. hanya import anggota yang resmi
2. tidak ada import massal katalog
3. tidak ada import massal item fisik
4. tidak ada import massal digital asset
5. export fokus pada laporan, bukan data dump sistem
6. tidak ada eksport file digital asset massal
7. tidak ada import update akses role dari file
8. tidak ada import audit log

## 45. Integrasi dengan Validation Rules

Import dan export harus mengacu ke 16_VALIDATION_RULES.md.

Relevansi utama:

1. `MemberImportRequest`
2. `CollectionReportExportRequest`
3. `MemberReportExportRequest`
4. `CirculationReportExportRequest`
5. `FineReportExportRequest`
6. `PopularCollectionReportExportRequest`
7. `DigitalAccessReportExportRequest`

Aturan:

1. validasi request file dan parameter wajib dijalankan sebelum service
2. service tetap memeriksa business rule tambahan bila perlu

## 46. Integrasi dengan Workflow State Machine

Import dan export harus tunduk pada 17_WORKFLOW_STATE_MACHINE.md.

Implikasi:

1. data anggota hasil import tetap harus valid terhadap state member
2. laporan sirkulasi harus memakai definisi overdue resmi
3. laporan digital asset harus memakai status OCR dan index resmi
4. export tidak boleh mengubah state apa pun kecuali logging atau histori export opsional

## 47. Integrasi dengan Notification Rules

Import dan export harus tunduk pada 24_NOTIFICATION_RULES.md.

Implikasi:

1. import selesai memberi flash message atau result summary
2. export async dapat memakai notifikasi siap unduh
3. error import dan export tidak boleh tampil sebagai exception mentah di UI

## 48. Integrasi dengan Storage Policy

Import dan export harus tunduk pada 22_STORAGE_FILE_POLICY.md.

Implikasi:

1. file import bersifat temp
2. file export bersifat temp atau controlled private
3. cleanup wajib direncanakan
4. object key atau temp path tidak boleh bocor ke publik

## 49. Integrasi dengan Reporting Spec

Export laporan harus tunduk pada 25_REPORTING_SPEC.md.

Implikasi:

1. hanya laporan resmi yang bisa diekspor
2. definisi kolom dan metrik harus sama dengan halaman laporan
3. filter aktif harus ikut ke export
4. data digital access report tidak boleh mengklaim usage log yang belum ada

## 50. Integrasi dengan API Contract

Fase 1 tidak mewajibkan API khusus untuk import export publik.

Internal API dapat dipertimbangkan hanya bila:

1. UI internal memakai komponen async tertentu
2. proses export ready status perlu JSON response
3. tetap tunduk pada 20_API_CONTRACT.md

Namun default fase 1:

1. import dan export tetap berbasis web route admin
2. bukan public API

## 51. Struktur File dan Kelas yang Direkomendasikan

Struktur yang direkomendasikan:

```text
app/
  Modules/
    Member/
      Services/
        MemberImportService.php
      Http/
        Requests/
          MemberImportRequest.php
      Support/
        Import/
          MemberImportTemplateDefinition.php
          MemberImportRowValidator.php
    Reporting/
      Services/
        ReportExportService.php
      Support/
        Export/
          CollectionReportExporter.php
          MemberReportExporter.php
          CirculationReportExporter.php
          FineReportExporter.php
          PopularCollectionReportExporter.php
          DigitalAccessReportExporter.php
```

Catatan:

1. struktur support bersifat rekomendasi
2. implementasi boleh menyesuaikan selama tidak menyimpang dari arsitektur modular

## 52. Testing Requirement Import

Pengujian minimum import anggota wajib mencakup:

1. file xlsx valid berhasil diproses
2. file csv valid berhasil diproses
3. file selain xlsx atau csv ditolak
4. file terlalu besar ditolak
5. header tidak valid ditolak
6. member_number kosong ditolak
7. member_type tidak valid ditolak
8. email tidak valid ditolak
9. faculty_code tidak valid ditolak
10. study_program_code tidak valid ditolak
11. relasi faculty dan study program tidak cocok ditolak
12. duplicate member_number dalam file ditolak
13. duplicate member_number dengan database ditolak pada mode insert only
14. import sukses sebagian menghasilkan summary yang benar
15. audit log import tercatat
16. file temp dibersihkan sesuai kebijakan

## 53. Testing Requirement Export

Pengujian minimum export wajib mencakup:

1. export collection report berhasil
2. export member report berhasil
3. export circulation report berhasil
4. export fine report berhasil
5. export popular collection report berhasil
6. export digital access report berhasil
7. filter aktif ikut ke file export
8. user tanpa permission export ditolak
9. file name export mengikuti pola resmi
10. file export tidak memuat data sensitif
11. empty result export ditangani dengan benar
12. export async, bila diaktifkan, memberi notifikasi yang benar

## 54. Anti Pattern yang Dilarang

Implementasi import export tidak boleh:

1. membaca file besar langsung di controller
2. menulis ke database tanpa service
3. mengimpor domain yang belum punya service resmi
4. mengekspor tabel mentah tanpa tujuan jelas
5. mengekspor file aset privat massal sebagai laporan
6. membocorkan temp path atau storage path
7. menganggap partial success tanpa summary yang jelas
8. membuat file export tanpa audit atau logging yang memadai
9. menimpa data anggota lama tanpa aturan yang jelas
10. mencampur template import dengan layout bebas yang tidak stabil

## 55. Prioritas Implementasi

### Prioritas P1

1. template import anggota
2. member import insert only
3. collection report export
4. member report export
5. circulation report export
6. fine report export

### Prioritas P2

1. popular collection report export
2. digital access report export
3. report export history, bila diaktifkan
4. cleanup temp import and export files

### Prioritas P3

1. import anggota mode upsert terbatas
2. async export luas
3. email ready notification
4. template import yang lebih kaya
5. preview result import sebelum commit, bila kelak dibutuhkan

## 56. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 28_SECURITY_POLICY.md
2. 29_AUDIT_LOG_SPEC.md
3. 30_ERROR_CODE.md
4. 31_TEST_PLAN.md
5. 32_TEST_SCENARIO.md
6. 33_DEPLOYMENT_GUIDE.md
7. 34_ENV_CONFIGURATION.md
8. 35_BACKUP_AND_RECOVERY.md
9. 38_TREE.md
10. 39_TRACEABILITY_MATRIX.md
11. 41_BACKEND_CHECKLIST.md
12. 42_FRONTEND_CHECKLIST.md
13. 45_SMOKE_TEST_CHECKLIST.md
14. 46_UAT_CHECKLIST.md

Aturan:

1. security policy harus mengatur kontrol file import dan export
2. audit log spec harus memuat event import dan export
3. error code document harus memuat kegagalan parsing, mapping, duplicate, dan export failure
4. test plan harus mencakup import partial success dan export filter consistency
5. traceability matrix harus menghubungkan member import dan report export ke service, route, request, dan tabel terkait

## 57. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. hanya operasi import export yang realistis dan didukung blueprint yang aktif
2. import anggota punya template, mapping, dan validasi yang jelas
3. export laporan selaras dengan reporting spec
4. role dan permission import export sudah jelas
5. storage policy file import dan export sudah terhubung
6. notifikasi dan audit sudah diperhitungkan
7. tidak ada operasi massal liar di luar scope fase 1

## 58. Kesimpulan

Dokumen Import Export Specification ini menetapkan aturan resmi impor dan ekspor PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 25. Dokumen ini memastikan bahwa import anggota massal dan export laporan resmi berjalan dengan format yang jelas, validasi yang ketat, service yang benar, penyimpanan file yang aman, serta hasil yang mudah dipahami pengguna. Semua implementasi fitur import dan export pada PERPUSQU wajib merujuk dokumen ini.

END OF 26_IMPORT_EXPORT_SPEC.md
