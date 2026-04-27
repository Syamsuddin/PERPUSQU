# 07_ROLE_PERMISSION_MATRIX.md

## 1. Nama Dokumen
Role Permission Matrix Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint matriks role dan permission

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan authorization, menu, route, controller, policy, service, dan audit

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan struktur role dan permission resmi untuk PERPUSQU agar seluruh fitur, menu, route, halaman, aksi data, dan kontrol akses berjalan konsisten. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, tester, dan administrator sistem dalam membangun authorization berbasis role dan permission.

## 3. Dokumen Acuan Wajib
Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md
5. 05_SRS.md
6. 06_USE_CASE.md

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep aplikasi tetap perpustakaan hibrid kampus.
3. Pembagian modul tetap mengikuti dokumen 03.
4. Kebutuhan produk dan sistem tetap mengikuti dokumen 04 dan 05.
5. Use case dan hak akses harus selaras dengan dokumen 06.
6. Tidak boleh ada permission yang memberi akses ke fitur yang belum didefinisikan pada blueprint sebelumnya.

## 4. Prinsip Umum Authorization
Authorization PERPUSQU menggunakan pendekatan role based access control dengan permission granular.

Prinsip utama:
1. Role menentukan kumpulan akses utama pengguna.
2. Permission menentukan aksi rinci yang diizinkan.
3. Satu user dapat memiliki satu atau lebih role bila diaktifkan secara desain.
4. Permission harus diperiksa pada level menu, route, controller, policy, dan aksi data penting.
5. Route admin wajib dilindungi autentikasi.
6. File digital privat wajib dilindungi permission atau rule akses tambahan.
7. Audit log wajib mencatat perubahan data penting dan perubahan hak akses.

## 5. Daftar Role Resmi Fase 1
Role resmi fase 1 ditetapkan sebagai berikut:

1. Super Admin
2. Admin Perpustakaan
3. Pustakawan
4. Petugas Sirkulasi
5. Operator Repositori Digital
6. Pimpinan Perpustakaan

Role layanan publik:
7. Pengguna OPAC Publik

Catatan:
1. Mahasiswa, Dosen, dan Tenaga Kependidikan pada fase 1 diperlakukan sebagai pengguna layanan OPAC publik, kecuali bila kampus mengaktifkan portal anggota pada fase berikutnya.
2. Tamu OPAC termasuk dalam kategori Pengguna OPAC Publik.
3. Role tambahan seperti Alumni, Reviewer Repositori, atau Admin Fakultas hanya boleh ditambahkan melalui revisi dokumen formal.

## 6. Definisi Role

### 6.1 Super Admin
Peran:
1. Pengelola tertinggi sistem.
2. Memiliki akses penuh ke konfigurasi, user, role, permission, dan seluruh modul inti.
3. Berwenang melakukan tindakan administrasi sistem tingkat tinggi.

### 6.2 Admin Perpustakaan
Peran:
1. Pengelola operasional utama perpustakaan.
2. Mengelola data master, katalog, koleksi, anggota, sirkulasi, repositori digital, laporan dasar, dan pengawasan operasional.

### 6.3 Pustakawan
Peran:
1. Mengelola bibliographic record.
2. Mengelola item fisik.
3. Melihat data anggota seperlunya.
4. Tidak memegang kontrol penuh atas user dan konfigurasi sistem.
5. Tidak menjadi aktor utama transaksi sirkulasi harian kecuali diizinkan kampus.

### 6.4 Petugas Sirkulasi
Peran:
1. Fokus pada peminjaman, pengembalian, perpanjangan, dan pemantauan pinjaman aktif.
2. Melihat katalog dan data item untuk mendukung transaksi.
3. Tidak mengelola metadata bibliografi utama.
4. Tidak mengelola konfigurasi sistem.

### 6.5 Operator Repositori Digital
Peran:
1. Fokus pada unggah file digital, metadata file, preview, OCR, indexing, dan aturan akses file digital.
2. Tidak mengelola transaksi sirkulasi.
3. Tidak mengelola user dan konfigurasi umum.

### 6.6 Pimpinan Perpustakaan
Peran:
1. Fokus pada monitoring.
2. Mengakses dashboard, laporan, dan data baca saja yang diperlukan untuk pengambilan keputusan.
3. Tidak melakukan perubahan data operasional utama.

### 6.7 Pengguna OPAC Publik
Peran:
1. Mengakses OPAC publik.
2. Mencari koleksi.
3. Melihat detail koleksi yang dipublikasikan.
4. Mengakses preview atau file digital sesuai aturan akses.
5. Tidak memiliki akses ke area admin.

## 7. Daftar Modul yang Diatur Hak Aksesnya
Modul yang diatur dalam dokumen ini:

1. Core
2. Identity and Access
3. Master Data
4. Catalog
5. Collection
6. Member
7. Circulation
8. Digital Repository
9. OPAC
10. Reporting
11. Audit and Monitoring

## 8. Kategori Permission
Permission dibagi dalam kategori berikut:

1. View
2. Create
3. Update
4. Delete
5. Manage
6. Approve
7. Process
8. Export
9. Access Special
10. Monitor

Definisi:
1. View berarti melihat daftar atau detail.
2. Create berarti menambah data.
3. Update berarti mengubah data.
4. Delete berarti menghapus data secara hard delete atau soft delete sesuai implementasi.
5. Manage berarti kontrol penuh pada modul tertentu.
6. Approve dipakai untuk fase lanjutan bila ada alur persetujuan.
7. Process dipakai untuk transaksi operasional seperti pinjam dan kembali.
8. Export berarti mengekspor laporan atau data.
9. Access Special dipakai untuk akses file digital khusus atau area teknis tertentu.
10. Monitor dipakai untuk monitoring audit dan job.

## 9. Format Penamaan Permission
Format nama permission resmi:

modul.aksi

Contoh:
1. users.view
2. users.create
3. users.update
4. users.delete
5. roles.manage
6. catalog.view
7. catalog.create
8. circulation.process_loan
9. digital_assets.manage_access
10. reports.export
11. audit_logs.view

Aturan:
1. Gunakan huruf kecil.
2. Gunakan titik sebagai pemisah.
3. Gunakan underscore hanya pada aksi majemuk.
4. Nama permission harus langsung mewakili aksi nyata pada sistem.

## 10. Daftar Permission Resmi

### 10.1 Core
1. core.view_dashboard
2. core.view_settings
3. core.update_settings
4. core.view_institution_profile
5. core.update_institution_profile
6. core.view_operational_rules
7. core.update_operational_rules

### 10.2 Identity and Access
1. users.view
2. users.create
3. users.update
4. users.delete
5. users.activate
6. users.reset_password
7. roles.view
8. roles.create
9. roles.update
10. roles.delete
11. roles.manage
12. permissions.view
13. permissions.manage
14. user_roles.assign
15. own_profile.view
16. own_profile.update
17. own_password.change

### 10.3 Master Data
1. authors.view
2. authors.create
3. authors.update
4. authors.delete
5. publishers.view
6. publishers.create
7. publishers.update
8. publishers.delete
9. languages.view
10. languages.create
11. languages.update
12. languages.delete
13. classifications.view
14. classifications.create
15. classifications.update
16. classifications.delete
17. subjects.view
18. subjects.create
19. subjects.update
20. subjects.delete
21. collection_types.view
22. collection_types.create
23. collection_types.update
24. collection_types.delete
25. rack_locations.view
26. rack_locations.create
27. rack_locations.update
28. rack_locations.delete
29. faculties.view
30. faculties.create
31. faculties.update
32. faculties.delete
33. study_programs.view
34. study_programs.create
35. study_programs.update
36. study_programs.delete
37. item_conditions.view
38. item_conditions.create
39. item_conditions.update
40. item_conditions.delete

### 10.4 Catalog
1. catalog.view
2. catalog.create
3. catalog.update
4. catalog.delete
5. catalog.publish
6. catalog.unpublish
7. catalog.manage_cover
8. catalog.link_authors
9. catalog.link_subjects
10. catalog.view_detail

### 10.5 Collection
1. collections.view
2. collections.create
3. collections.update
4. collections.delete
5. collections.change_status
6. collections.view_history
7. collections.view_detail

### 10.6 Member
1. members.view
2. members.create
3. members.update
4. members.delete
5. members.activate
6. members.deactivate
7. members.block
8. members.unblock
9. members.view_history
10. members.import
11. members.view_detail

### 10.7 Circulation
1. circulation.view
2. circulation.process_loan
3. circulation.process_return
4. circulation.process_renewal
5. circulation.view_active_loans
6. circulation.view_history
7. circulation.view_fines
8. circulation.override_rules

### 10.8 Digital Repository
1. digital_assets.view
2. digital_assets.create
3. digital_assets.update
4. digital_assets.delete
5. digital_assets.view_detail
6. digital_assets.preview
7. digital_assets.manage_access
8. digital_assets.publish
9. digital_assets.unpublish
10. digital_assets.run_ocr
11. digital_assets.reindex
12. digital_assets.download_private

### 10.9 OPAC
1. opac.search
2. opac.view_detail
3. opac.view_availability
4. opac.preview_public_asset

### 10.10 Reporting
1. reports.view_dashboard
2. reports.view_collections
3. reports.view_members
4. reports.view_circulation
5. reports.view_fines
6. reports.view_popular_collections
7. reports.view_digital_access
8. reports.export

### 10.11 Audit and Monitoring
1. audit_logs.view
2. audit_logs.view_detail
3. queue_monitor.view
4. queue_monitor.manage_retry

## 11. Matriks Hak Akses Tingkat Modul

Keterangan nilai:
1. Full = view, create, update, delete, process, dan manage yang relevan
2. Manage = kendali operasional utama tanpa seluruh administrasi sistem
3. View = hanya melihat
4. Limited = akses terbatas pada beberapa aksi
5. No = tidak ada akses

| Modul | Super Admin | Admin Perpustakaan | Pustakawan | Petugas Sirkulasi | Operator Repositori Digital | Pimpinan Perpustakaan | Pengguna OPAC Publik |
|---|---|---|---|---|---|---|---|
| Core | Full | Manage | View | View | View | View | No |
| Identity and Access | Full | Limited | No | No | No | No | No |
| Master Data | Full | Full | Limited | No | Limited | View | No |
| Catalog | Full | Full | Full | View | View | View | No |
| Collection | Full | Full | Full | View | No | View | No |
| Member | Full | Full | View | View | No | View | No |
| Circulation | Full | Full | No | Full | No | View | No |
| Digital Repository | Full | Full | Limited | No | Full | View | Limited |
| OPAC | View | View | View | View | View | View | Full |
| Reporting | Full | Full | Limited | Limited | Limited | Full | No |
| Audit and Monitoring | Full | Limited | No | No | No | No | No |

## 12. Matriks Permission per Role

### 12.1 Super Admin
Super Admin memiliki seluruh permission resmi berikut:
1. Semua permission pada dokumen ini
2. Hak penuh mengelola role dan permission
3. Hak penuh melihat audit log dan queue monitor
4. Hak penuh mengubah konfigurasi sistem
5. Hak override operasional jika fitur tersebut diaktifkan

### 12.2 Admin Perpustakaan
Admin Perpustakaan memiliki permission berikut:

Core:
1. core.view_dashboard
2. core.view_settings
3. core.update_settings
4. core.view_institution_profile
5. core.update_institution_profile
6. core.view_operational_rules
7. core.update_operational_rules

Identity:
8. users.view
9. users.create
10. users.update
11. users.activate
12. users.reset_password
13. roles.view
14. permissions.view
15. user_roles.assign
16. own_profile.view
17. own_profile.update
18. own_password.change

Master Data:
19. authors.view
20. authors.create
21. authors.update
22. publishers.view
23. publishers.create
24. publishers.update
25. languages.view
26. languages.create
27. languages.update
28. classifications.view
29. classifications.create
30. classifications.update
31. subjects.view
32. subjects.create
33. subjects.update
34. collection_types.view
35. collection_types.create
36. collection_types.update
37. rack_locations.view
38. rack_locations.create
39. rack_locations.update
40. faculties.view
41. faculties.create
42. faculties.update
43. study_programs.view
44. study_programs.create
45. study_programs.update
46. item_conditions.view
47. item_conditions.create
48. item_conditions.update

Catalog:
49. catalog.view
50. catalog.create
51. catalog.update
52. catalog.publish
53. catalog.unpublish
54. catalog.manage_cover
55. catalog.link_authors
56. catalog.link_subjects
57. catalog.view_detail

Collection:
58. collections.view
59. collections.create
60. collections.update
61. collections.change_status
62. collections.view_history
63. collections.view_detail

Member:
64. members.view
65. members.create
66. members.update
67. members.activate
68. members.deactivate
69. members.block
70. members.unblock
71. members.view_history
72. members.view_detail

Circulation:
73. circulation.view
74. circulation.process_loan
75. circulation.process_return
76. circulation.process_renewal
77. circulation.view_active_loans
78. circulation.view_history
79. circulation.view_fines

Digital Repository:
80. digital_assets.view
81. digital_assets.create
82. digital_assets.update
83. digital_assets.view_detail
84. digital_assets.preview
85. digital_assets.manage_access
86. digital_assets.publish
87. digital_assets.unpublish
88. digital_assets.run_ocr
89. digital_assets.reindex

OPAC:
90. opac.search
91. opac.view_detail
92. opac.view_availability
93. opac.preview_public_asset

Reporting:
94. reports.view_dashboard
95. reports.view_collections
96. reports.view_members
97. reports.view_circulation
98. reports.view_fines
99. reports.view_popular_collections
100. reports.view_digital_access
101. reports.export

Audit and Monitoring:
102. audit_logs.view
103. audit_logs.view_detail
104. queue_monitor.view

Catatan:
1. Admin Perpustakaan tidak dapat mengelola permission sistem secara penuh.
2. Admin Perpustakaan tidak dapat menghapus role inti tanpa kewenangan khusus.
3. Admin Perpustakaan tidak memiliki queue_monitor.manage_retry secara default.

### 12.3 Pustakawan
Pustakawan memiliki permission berikut:

Own:
1. own_profile.view
2. own_profile.update
3. own_password.change

Core:
4. core.view_dashboard
5. core.view_institution_profile
6. core.view_operational_rules

Master Data terbatas:
7. authors.view
8. authors.create
9. authors.update
10. publishers.view
11. publishers.create
12. publishers.update
13. languages.view
14. classifications.view
15. subjects.view
16. subjects.create
17. subjects.update
18. collection_types.view
19. rack_locations.view
20. item_conditions.view

Catalog:
21. catalog.view
22. catalog.create
23. catalog.update
24. catalog.manage_cover
25. catalog.link_authors
26. catalog.link_subjects
27. catalog.view_detail

Collection:
28. collections.view
29. collections.create
30. collections.update
31. collections.change_status
32. collections.view_history
33. collections.view_detail

Member baca saja:
34. members.view
35. members.view_detail
36. members.view_history

Digital Repository terbatas:
37. digital_assets.view
38. digital_assets.create
39. digital_assets.update
40. digital_assets.view_detail
41. digital_assets.preview
42. digital_assets.run_ocr
43. digital_assets.reindex

OPAC:
44. opac.search
45. opac.view_detail
46. opac.view_availability
47. opac.preview_public_asset

Reporting terbatas:
48. reports.view_dashboard
49. reports.view_collections
50. reports.view_digital_access

Catatan:
1. Pustakawan tidak dapat mengelola user, role, permission, dan konfigurasi inti.
2. Pustakawan tidak memproses pinjam dan kembali secara default.
3. Pustakawan tidak dapat mengelola akses file privat secara penuh kecuali diberi role tambahan.

### 12.4 Petugas Sirkulasi
Petugas Sirkulasi memiliki permission berikut:

Own:
1. own_profile.view
2. own_profile.update
3. own_password.change

Core:
4. core.view_dashboard
5. core.view_operational_rules

Catalog baca:
6. catalog.view
7. catalog.view_detail

Collection baca:
8. collections.view
9. collections.view_detail

Member baca:
10. members.view
11. members.view_detail
12. members.view_history

Circulation penuh operasional:
13. circulation.view
14. circulation.process_loan
15. circulation.process_return
16. circulation.process_renewal
17. circulation.view_active_loans
18. circulation.view_history
19. circulation.view_fines

OPAC:
20. opac.search
21. opac.view_detail
22. opac.view_availability
23. opac.preview_public_asset

Reporting terbatas:
24. reports.view_dashboard
25. reports.view_circulation
26. reports.view_fines

Catatan:
1. Petugas Sirkulasi tidak dapat mengubah bibliographic record.
2. Petugas Sirkulasi tidak dapat menambah item fisik.
3. Petugas Sirkulasi tidak dapat mengelola aset digital.
4. Petugas Sirkulasi tidak dapat memblokir anggota tanpa kewenangan khusus.

### 12.5 Operator Repositori Digital
Operator Repositori Digital memiliki permission berikut:

Own:
1. own_profile.view
2. own_profile.update
3. own_password.change

Core:
4. core.view_dashboard
5. core.view_institution_profile

Master Data terbatas:
6. authors.view
7. publishers.view
8. languages.view
9. subjects.view
10. collection_types.view

Catalog baca:
11. catalog.view
12. catalog.view_detail

Digital Repository penuh:
13. digital_assets.view
14. digital_assets.create
15. digital_assets.update
16. digital_assets.delete
17. digital_assets.view_detail
18. digital_assets.preview
19. digital_assets.manage_access
20. digital_assets.publish
21. digital_assets.unpublish
22. digital_assets.run_ocr
23. digital_assets.reindex
24. digital_assets.download_private

OPAC:
25. opac.search
26. opac.view_detail
27. opac.view_availability
28. opac.preview_public_asset

Reporting terbatas:
29. reports.view_dashboard
30. reports.view_digital_access

Catatan:
1. Operator Repositori Digital tidak memproses sirkulasi.
2. Operator Repositori Digital tidak mengelola anggota.
3. Operator Repositori Digital tidak mengubah bibliographic record inti kecuali ada role tambahan.

### 12.6 Pimpinan Perpustakaan
Pimpinan Perpustakaan memiliki permission berikut:

Own:
1. own_profile.view
2. own_profile.update
3. own_password.change

Core baca:
4. core.view_dashboard
5. core.view_institution_profile
6. core.view_operational_rules

Catalog baca:
7. catalog.view
8. catalog.view_detail

Collection baca:
9. collections.view
10. collections.view_detail

Member baca:
11. members.view
12. members.view_detail

Circulation baca:
13. circulation.view
14. circulation.view_active_loans
15. circulation.view_history
16. circulation.view_fines

Digital Repository baca:
17. digital_assets.view
18. digital_assets.view_detail
19. digital_assets.preview

OPAC:
20. opac.search
21. opac.view_detail
22. opac.view_availability
23. opac.preview_public_asset

Reporting penuh baca:
24. reports.view_dashboard
25. reports.view_collections
26. reports.view_members
27. reports.view_circulation
28. reports.view_fines
29. reports.view_popular_collections
30. reports.view_digital_access
31. reports.export

Catatan:
1. Pimpinan Perpustakaan tidak mengubah data operasional.
2. Pimpinan Perpustakaan tidak mengelola user dan konfigurasi inti.
3. Pimpinan Perpustakaan tidak melihat audit log secara default.

### 12.7 Pengguna OPAC Publik
Pengguna OPAC Publik memiliki permission berikut:

1. opac.search
2. opac.view_detail
3. opac.view_availability
4. opac.preview_public_asset

Catatan:
1. Pengguna OPAC Publik tidak memiliki akses ke admin panel.
2. Akses file digital tetap tunduk pada rule publikasi dan embargo.
3. Akses ke file privat tidak diberikan.

## 13. Matriks Permission Ringkas per Role

Keterangan:
Y = diizinkan
T = tidak diizinkan
L = terbatas
R = baca saja

| Permission Group | Super Admin | Admin Perpustakaan | Pustakawan | Petugas Sirkulasi | Operator Repositori Digital | Pimpinan Perpustakaan | OPAC Publik |
|---|---|---|---|---|---|---|---|
| Core View | Y | Y | Y | Y | Y | Y | T |
| Core Update | Y | Y | T | T | T | T | T |
| User Management | Y | Y | T | T | T | T | T |
| Role Management | Y | R | T | T | T | T | T |
| Permission Management | Y | R | T | T | T | T | T |
| Master Data View | Y | Y | Y | T | L | R | T |
| Master Data Create/Update | Y | Y | L | T | T | T | T |
| Catalog View | Y | Y | Y | R | R | R | T |
| Catalog Create/Update | Y | Y | Y | T | T | T | T |
| Collection View | Y | Y | Y | R | T | R | T |
| Collection Create/Update | Y | Y | Y | T | T | T | T |
| Member View | Y | Y | R | R | T | R | T |
| Member Create/Update | Y | Y | T | T | T | T | T |
| Circulation Process | Y | Y | T | Y | T | T | T |
| Digital Assets View | Y | Y | L | T | Y | R | L |
| Digital Assets Manage | Y | Y | L | T | Y | T | T |
| OPAC Search | Y | Y | Y | Y | Y | Y | Y |
| Reporting View | Y | Y | L | L | L | Y | T |
| Reporting Export | Y | Y | T | T | T | Y | T |
| Audit View | Y | L | T | T | T | T | T |
| Queue Monitor View | Y | L | T | T | T | T | T |

## 14. Matriks Use Case ke Role

| Use Case | Super Admin | Admin Perpustakaan | Pustakawan | Petugas Sirkulasi | Operator Repositori Digital | Pimpinan Perpustakaan | OPAC Publik |
|---|---|---|---|---|---|---|---|
| UC-IDA-001 Login | Y | Y | Y | Y | Y | Y | T |
| UC-IDA-003 Mengelola Pengguna | Y | Y | T | T | T | T | T |
| UC-IDA-004 Mengelola Role | Y | R | T | T | T | T | T |
| UC-IDA-005 Mengelola Permission | Y | T | T | T | T | T | T |
| UC-CORE-002 Mengelola Konfigurasi | Y | Y | T | T | T | T | T |
| UC-MAS-001 s.d. UC-MAS-010 | Y | Y | L | T | L | R | T |
| UC-CAT-001 Menambah Bibliographic Record | Y | Y | Y | T | T | T | T |
| UC-CAT-002 Mengubah Bibliographic Record | Y | Y | Y | T | T | T | T |
| UC-COL-001 Menambah Item Fisik | Y | Y | Y | T | T | T | T |
| UC-MEM-001 Menambah Anggota | Y | Y | T | T | T | T | T |
| UC-MEM-006 Memblokir Anggota | Y | Y | T | T | T | T | T |
| UC-CIR-001 Memproses Peminjaman | Y | Y | T | Y | T | T | T |
| UC-CIR-002 Memproses Pengembalian | Y | Y | T | Y | T | T | T |
| UC-DIG-001 Mengunggah Aset Digital | Y | Y | L | T | Y | T | T |
| UC-DIG-005 Mengelola Akses Aset Digital | Y | Y | T | T | Y | T | T |
| UC-OPA-001 Mencari pada OPAC | R | R | R | R | R | R | Y |
| UC-OPA-003 Melihat Detail OPAC | R | R | R | R | R | R | Y |
| UC-REP-001 Melihat Dashboard Statistik | Y | Y | L | L | L | Y | T |
| UC-AUD-001 Melihat Audit Log | Y | L | T | T | T | T | T |

## 15. Aturan Hak Akses per Area Sistem

### 15.1 Area Login
1. Semua role internal dapat mengakses halaman login.
2. Pengguna OPAC Publik tidak menggunakan login admin.
3. Login anggota kampus bukan bagian wajib fase 1.

### 15.2 Area Dashboard Admin
1. Semua role internal mendapat dashboard sesuai kebutuhan.
2. Isi widget dashboard harus dibatasi berdasar role.

### 15.3 Area Master Data
1. Hanya role tertentu yang dapat create, update, delete.
2. Pimpinan hanya baca bila diperlukan.
3. Petugas Sirkulasi tidak masuk ke area master data.

### 15.4 Area Katalog
1. Pustakawan, Admin Perpustakaan, dan Super Admin dapat mengelola katalog.
2. Petugas Sirkulasi dan Pimpinan hanya melihat.
3. Operator Repositori Digital hanya melihat untuk kebutuhan tautan aset digital.

### 15.5 Area Koleksi
1. Pustakawan dan Admin Perpustakaan mengelola item fisik.
2. Petugas Sirkulasi hanya melihat item.
3. Pimpinan hanya baca.

### 15.6 Area Anggota
1. Admin Perpustakaan dan Super Admin mengelola data anggota.
2. Petugas Sirkulasi hanya melihat data anggota untuk transaksi.
3. Pustakawan hanya baca terbatas bila diperlukan.

### 15.7 Area Sirkulasi
1. Petugas Sirkulasi adalah aktor utama.
2. Admin Perpustakaan dapat melakukan backup operasional.
3. Pimpinan hanya melihat laporan atau status.
4. Pustakawan dan Operator Repositori Digital tidak mengakses proses transaksi.

### 15.8 Area Repositori Digital
1. Operator Repositori Digital adalah aktor utama.
2. Admin Perpustakaan dapat mengelola.
3. Pustakawan dapat membantu unggah dasar bila diizinkan.
4. File privat harus tetap dibatasi walau role internal dapat melihat metadata.

### 15.9 Area OPAC
1. Dapat diakses publik tanpa login admin.
2. Detail akses file digital harus mengikuti rule publikasi dan embargo.
3. OPAC hanya menampilkan data yang telah dipublikasikan.

### 15.10 Area Reporting
1. Pimpinan Perpustakaan dan Admin Perpustakaan memiliki akses utama.
2. Pustakawan, Petugas Sirkulasi, dan Operator Repositori Digital mendapat laporan terbatas sesuai tugas.
3. Ekspor laporan dibatasi pada role tertentu.

### 15.11 Area Audit and Monitoring
1. Audit log hanya diakses Super Admin dan Admin Perpustakaan terbatas.
2. Queue monitoring hanya untuk Super Admin dan admin teknis yang diberi hak.
3. Role lain tidak mengakses area ini secara default.

## 16. Permission Sensitif
Permission berikut dianggap sensitif dan wajib diperiksa ketat:

1. permissions.manage
2. roles.manage
3. users.reset_password
4. core.update_settings
5. core.update_operational_rules
6. members.block
7. members.unblock
8. circulation.override_rules
9. digital_assets.manage_access
10. digital_assets.download_private
11. audit_logs.view
12. queue_monitor.manage_retry

Aturan:
1. Semua aksi sensitif wajib tercatat di audit log.
2. Beberapa aksi sensitif dapat memerlukan konfirmasi tambahan pada UI.
3. Permission sensitif tidak boleh diberikan ke role publik.

## 17. Aturan Penghapusan Data
Hak hapus harus sangat dibatasi.

Prinsip:
1. Super Admin boleh delete pada entitas tertentu sesuai implementasi.
2. Admin Perpustakaan delete terbatas, lebih dianjurkan nonaktif atau soft delete.
3. Pustakawan, Petugas Sirkulasi, Operator Repositori Digital, dan Pimpinan tidak diberi delete luas secara default.
4. Data transaksi sirkulasi tidak boleh dihapus sembarangan.
5. Data master yang sudah dipakai transaksi sebaiknya nonaktif, bukan delete.
6. Aset digital yang telah dipublikasi sebaiknya nonaktif atau arsip, bukan langsung hard delete.

## 18. Aturan Permission untuk OPAC dan File Digital
1. Semua pengguna publik dapat melakukan opac.search.
2. Semua pengguna publik dapat opac.view_detail untuk record yang dipublikasi.
3. opac.preview_public_asset hanya berlaku untuk aset digital yang memang publik dan diizinkan.
4. digital_assets.download_private tidak boleh dimiliki oleh Pengguna OPAC Publik.
5. Akses file privat harus melewati controller atau signed access yang memeriksa rule akses.

## 19. Aturan Permission untuk Audit dan Monitoring
1. audit_logs.view hanya untuk Super Admin dan Admin Perpustakaan yang diberi hak.
2. audit_logs.view_detail mengikuti audit_logs.view.
3. queue_monitor.view hanya untuk Super Admin dan admin teknis.
4. queue_monitor.manage_retry hanya untuk Super Admin secara default.

## 20. Default Role Assignment
Role default pengguna internal baru ditetapkan sebagai berikut:

1. Pengguna sistem baru harus dibuat oleh admin.
2. Role tidak boleh kosong untuk pengguna internal.
3. Role default paling aman untuk akun baru adalah sesuai fungsi kerja nyata, bukan otomatis admin.
4. Tidak ada role admin yang diberikan otomatis oleh sistem tanpa tindakan admin berwenang.

## 21. Aturan Multi Role
Pada fase 1, multi role diperbolehkan secara teknis tetapi harus dibatasi secara kebijakan.

Prinsip:
1. Satu user idealnya memiliki satu role utama.
2. Multi role hanya untuk kondisi khusus, misalnya Admin Perpustakaan merangkap Petugas Sirkulasi.
3. Jika multi role aktif, sistem menggunakan gabungan permission.
4. Semua gabungan permission tetap dicatat pada audit bila terjadi perubahan role.

## 22. Dampak ke Menu
Dokumen ini mengharuskan:
1. Menu hanya tampil bila user punya permission view terkait.
2. Tombol tambah hanya tampil bila user punya permission create.
3. Tombol ubah hanya tampil bila user punya permission update.
4. Tombol hapus hanya tampil bila user punya permission delete.
5. Tombol proses pinjam, kembali, perpanjang hanya tampil bila user punya permission process შესაბამისి.
6. Menu audit log dan queue monitor tidak boleh tampil pada role tanpa hak.

## 23. Dampak ke Route
Aturan route wajib:
1. Setiap route admin harus dilindungi middleware auth.
2. Setiap route aksi penting harus dilindungi permission yang spesifik.
3. Route OPAC publik dipisah dari route admin.
4. Route file digital privat harus memiliki pemeriksaan permission dan rule akses.

## 24. Dampak ke Controller dan Policy
1. Controller tidak boleh hanya bergantung pada tampilan menu.
2. Semua aksi penting tetap harus diperiksa ulang di controller atau policy.
3. Policy per model wajib disusun untuk entitas utama, minimal User, BibliographicRecord, PhysicalItem, Member, Loan, DigitalAsset, ActivityLog.
4. Role matrix ini menjadi sumber utama penyusunan policy.

## 25. Dampak ke Audit Log
Perubahan berikut wajib dicatat:
1. Pembuatan user
2. Perubahan user
3. Aktivasi dan nonaktifasi user
4. Reset password oleh admin
5. Perubahan role
6. Perubahan permission
7. Pemberian role ke user
8. Akses audit log
9. Perubahan rule akses aset digital
10. Perubahan konfigurasi sistem

## 26. Matriks Prioritas Implementasi Authorization

### Prioritas P1
1. Auth login dan logout
2. Permission menu admin
3. Permission route admin
4. Role user management dasar
5. Policy katalog
6. Policy item fisik
7. Policy anggota
8. Policy sirkulasi
9. Policy aset digital
10. Pembatasan OPAC publik vs admin

### Prioritas P2
1. Audit log akses sensitif
2. Queue monitor permission
3. Permission ekspor laporan
4. Multi role bila diaktifkan

### Prioritas P3
1. Permission approval
2. Permission integrasi
3. Permission notifikasi lanjutan
4. Permission portal anggota

## 27. Mapping ke Dokumen Turunan
Dokumen ini menjadi acuan wajib bagi:
1. 08_MENU_MAP.md
2. 09_ROUTE_MAP.md
3. 10_VIEW_MAP.md
4. 11_CONTROLLER_MAP.md
5. 12_SERVICE_LAYER.md
6. 13_MODEL_MAP.md
7. 16_VALIDATION_RULES.md
8. 17_WORKFLOW_STATE_MACHINE.md
9. 18_UI_UX_STANDARD.md
10. 28_SECURITY_POLICY.md
11. 29_AUDIT_LOG_SPEC.md
12. 31_TEST_PLAN.md
13. 32_TEST_SCENARIO.md

Aturan:
1. Menu harus mengikuti permission matrix.
2. Route harus mengikuti permission matrix.
3. Policy harus mengikuti permission matrix.
4. Test authorization harus mengikuti permission matrix.
5. Tidak boleh ada tombol aksi yang tampil tanpa dasar permission yang sesuai.

## 28. Kesimpulan
Dokumen Role Permission Matrix ini menetapkan kontrol akses resmi PERPUSQU secara rinci, konsisten, dan terukur. Dokumen ini memastikan setiap aktor hanya memperoleh akses sesuai tugasnya, sekaligus menjaga integritas data, keamanan sistem, dan konsistensi implementasi pada menu, route, controller, service, policy, dan audit log. Seluruh proses full stack coding PERPUSQU wajib merujuk dokumen ini sebagai acuan utama authorization.

END OF 07_ROLE_PERMISSION_MATRIX.md