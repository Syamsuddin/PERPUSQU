# 09_ROUTE_MAP.md

## 1. Nama Dokumen
Route Map Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint peta route aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan route, middleware, authorization, controller map, view map, service layer, testing, dan traceability

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan struktur route resmi PERPUSQU untuk seluruh area sistem, meliputi area admin internal, area publik OPAC, preview file digital, serta route utilitas akun pengguna. Dokumen ini menjadi acuan wajib agar implementasi route konsisten dengan menu, use case, role permission, modul, dan struktur arsitektur monolith modular yang telah disepakati pada blueprint sebelumnya.

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

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep aplikasi tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Route harus merepresentasikan use case yang sudah ditetapkan.
5. Route harus mengikuti menu yang sudah ditetapkan.
6. Route harus dilindungi middleware dan permission yang sesuai.
7. Tidak boleh ada route utama tanpa use case, menu, dan controller tujuan yang jelas.
8. Tidak boleh ada route admin publik tanpa autentikasi.
9. Tidak boleh ada route file privat yang terbuka langsung ke publik.

## 4. Prinsip Umum Perancangan Route
Prinsip resmi route PERPUSQU adalah:

1. Route dipisahkan antara admin internal dan OPAC publik.
2. Route harus konsisten dengan modul bisnis.
3. Route harus menggunakan penamaan yang stabil dan mudah dilacak.
4. Route harus memanfaatkan middleware auth dan permission.
5. Route detail, edit, proses, preview, dan export harus eksplisit.
6. Route tidak boleh mengandung logika bisnis.
7. Route harus mudah dipetakan ke controller dan view.
8. Route untuk file digital privat harus melewati kontrol akses.
9. Route aksi sensitif wajib dibatasi method HTTP yang tepat.

## 5. Struktur Besar Route
Struktur route resmi dibagi menjadi:

1. Route publik
2. Route autentikasi internal
3. Route admin internal
4. Route utilitas akun internal
5. Route preview dan akses aset digital
6. Route monitoring teknis internal
7. Route API internal terbatas bila diperlukan

## 6. Prefix Route Resmi

### 6.1 Prefix Publik
1. `/`
2. `/opac`
3. `/collections`
4. `/about`
5. `/help`

### 6.2 Prefix Autentikasi Internal
1. `/login`
2. `/logout`

### 6.3 Prefix Admin
1. `/admin`

### 6.4 Prefix Modul dalam Admin
1. `/admin/dashboard`
2. `/admin/master-data`
3. `/admin/catalog`
4. `/admin/collections`
5. `/admin/members`
6. `/admin/circulation`
7. `/admin/digital-repository`
8. `/admin/reports`
9. `/admin/audit`
10. `/admin/settings`
11. `/admin/access`
12. `/admin/profile`

## 7. Konvensi Penamaan Route
Format nama route resmi:

`area.modul.resource.action`

Contoh:
1. `admin.catalog.records.index`
2. `admin.catalog.records.create`
3. `admin.catalog.records.store`
4. `admin.collections.items.edit`
5. `admin.circulation.loans.store`
6. `admin.digital.assets.preview`
7. `opac.search.index`
8. `opac.records.show`

Aturan:
1. Gunakan huruf kecil.
2. Gunakan titik sebagai pemisah.
3. Gunakan kata kerja standar seperti index, create, store, show, edit, update, destroy, export, preview.
4. Gunakan nama resource dalam bentuk plural bila route list resource.
5. Gunakan nama yang konsisten dengan modul dan controller.

## 8. Middleware Resmi
Middleware resmi yang dipakai pada route PERPUSQU minimal meliputi:

1. `web`
2. `guest`
3. `auth`
4. `verified` bila nanti diperlukan
5. `permission:<nama_permission>`
6. `throttle` pada route tertentu bila diperlukan
7. `signed` pada akses file tertentu bila diperlukan
8. Middleware khusus rule akses aset digital bila diperlukan

## 9. Daftar Route Publik Resmi

### 9.1 Beranda OPAC
- Method: GET
- URI: `/`
- Nama Route: `opac.home`
- Middleware: `web`
- Permission: publik
- Menu: MNU-OPA-001
- Use Case: UC-OPA-001
- Tujuan: Halaman beranda OPAC dengan search bar utama

### 9.2 Halaman Pencarian OPAC
- Method: GET
- URI: `/opac`
- Nama Route: `opac.search.index`
- Middleware: `web`
- Permission: `opac.search`
- Menu: MNU-OPA-002
- Use Case: UC-OPA-001, UC-OPA-002
- Tujuan: Menampilkan hasil pencarian OPAC

### 9.3 Halaman Detail Koleksi Publik
- Method: GET
- URI: `/opac/records/{record}`
- Nama Route: `opac.records.show`
- Middleware: `web`
- Permission: `opac.view_detail`
- Menu: MNU-OPA-003
- Use Case: UC-OPA-003, UC-OPA-004
- Tujuan: Menampilkan detail bibliographic record publik

### 9.4 Preview Aset Digital Publik
- Method: GET
- URI: `/opac/assets/{asset}/preview`
- Nama Route: `opac.assets.preview`
- Middleware: `web`
- Permission: `opac.preview_public_asset`
- Menu: MNU-OPA-004
- Use Case: UC-OPA-005
- Tujuan: Preview file digital publik sesuai rule publikasi

### 9.5 Tentang Perpustakaan
- Method: GET
- URI: `/about`
- Nama Route: `opac.about`
- Middleware: `web`
- Permission: publik
- Menu: MNU-OPA-005
- Tujuan: Menampilkan profil singkat perpustakaan

### 9.6 Bantuan Pencarian
- Method: GET
- URI: `/help`
- Nama Route: `opac.help`
- Middleware: `web`
- Permission: publik
- Menu: MNU-OPA-006
- Tujuan: Menampilkan panduan penggunaan OPAC

## 10. Daftar Route Autentikasi Internal

### 10.1 Halaman Login
- Method: GET
- URI: `/login`
- Nama Route: `auth.login`
- Middleware: `web`, `guest`
- Permission: publik internal
- Use Case: UC-IDA-001
- Tujuan: Menampilkan form login internal

### 10.2 Proses Login
- Method: POST
- URI: `/login`
- Nama Route: `auth.login.attempt`
- Middleware: `web`, `guest`
- Permission: publik internal
- Use Case: UC-IDA-001
- Tujuan: Memproses login internal

### 10.3 Proses Logout
- Method: POST
- URI: `/logout`
- Nama Route: `auth.logout`
- Middleware: `web`, `auth`
- Permission: login aktif
- Use Case: UC-IDA-002
- Tujuan: Mengakhiri sesi pengguna

## 11. Daftar Route Area Admin Resmi
Seluruh route admin berada di dalam grup berikut:

- Prefix: `/admin`
- Name Prefix: `admin.`
- Middleware dasar: `web`, `auth`

## 12. Route Dashboard Admin

### 12.1 Dashboard Utama
- Method: GET
- URI: `/admin/dashboard`
- Nama Route: `admin.dashboard.index`
- Middleware: `auth`, `permission:core.view_dashboard`
- Menu: MNU-DASH-001
- Use Case: UC-CORE-004, UC-REP-001
- Tujuan: Menampilkan dashboard sesuai role

## 13. Route Modul Master Data

### 13.1 Pengarang

#### 13.1.1 Daftar Pengarang
- Method: GET
- URI: `/admin/master-data/authors`
- Nama Route: `admin.master_data.authors.index`
- Middleware: `auth`, `permission:authors.view`
- Menu: MNU-MAS-001
- Use Case: UC-MAS-001

#### 13.1.2 Form Tambah Pengarang
- Method: GET
- URI: `/admin/master-data/authors/create`
- Nama Route: `admin.master_data.authors.create`
- Middleware: `auth`, `permission:authors.create`

#### 13.1.3 Simpan Pengarang
- Method: POST
- URI: `/admin/master-data/authors`
- Nama Route: `admin.master_data.authors.store`
- Middleware: `auth`, `permission:authors.create`

#### 13.1.4 Form Edit Pengarang
- Method: GET
- URI: `/admin/master-data/authors/{author}/edit`
- Nama Route: `admin.master_data.authors.edit`
- Middleware: `auth`, `permission:authors.update`

#### 13.1.5 Update Pengarang
- Method: PUT
- URI: `/admin/master-data/authors/{author}`
- Nama Route: `admin.master_data.authors.update`
- Middleware: `auth`, `permission:authors.update`

#### 13.1.6 Hapus Pengarang
- Method: DELETE
- URI: `/admin/master-data/authors/{author}`
- Nama Route: `admin.master_data.authors.destroy`
- Middleware: `auth`, `permission:authors.delete`

### 13.2 Penerbit

#### 13.2.1 Daftar Penerbit
- Method: GET
- URI: `/admin/master-data/publishers`
- Nama Route: `admin.master_data.publishers.index`
- Middleware: `auth`, `permission:publishers.view`
- Menu: MNU-MAS-002
- Use Case: UC-MAS-002

#### 13.2.2 Form Tambah Penerbit
- Method: GET
- URI: `/admin/master-data/publishers/create`
- Nama Route: `admin.master_data.publishers.create`
- Middleware: `auth`, `permission:publishers.create`

#### 13.2.3 Simpan Penerbit
- Method: POST
- URI: `/admin/master-data/publishers`
- Nama Route: `admin.master_data.publishers.store`
- Middleware: `auth`, `permission:publishers.create`

#### 13.2.4 Form Edit Penerbit
- Method: GET
- URI: `/admin/master-data/publishers/{publisher}/edit`
- Nama Route: `admin.master_data.publishers.edit`
- Middleware: `auth`, `permission:publishers.update`

#### 13.2.5 Update Penerbit
- Method: PUT
- URI: `/admin/master-data/publishers/{publisher}`
- Nama Route: `admin.master_data.publishers.update`
- Middleware: `auth`, `permission:publishers.update`

#### 13.2.6 Hapus Penerbit
- Method: DELETE
- URI: `/admin/master-data/publishers/{publisher}`
- Nama Route: `admin.master_data.publishers.destroy`
- Middleware: `auth`, `permission:publishers.delete`

### 13.3 Bahasa

#### 13.3.1 Daftar Bahasa
- Method: GET
- URI: `/admin/master-data/languages`
- Nama Route: `admin.master_data.languages.index`
- Middleware: `auth`, `permission:languages.view`
- Menu: MNU-MAS-003
- Use Case: UC-MAS-003

#### 13.3.2 Form Tambah Bahasa
- Method: GET
- URI: `/admin/master-data/languages/create`
- Nama Route: `admin.master_data.languages.create`
- Middleware: `auth`, `permission:languages.create`

#### 13.3.3 Simpan Bahasa
- Method: POST
- URI: `/admin/master-data/languages`
- Nama Route: `admin.master_data.languages.store`
- Middleware: `auth`, `permission:languages.create`

#### 13.3.4 Form Edit Bahasa
- Method: GET
- URI: `/admin/master-data/languages/{language}/edit`
- Nama Route: `admin.master_data.languages.edit`
- Middleware: `auth`, `permission:languages.update`

#### 13.3.5 Update Bahasa
- Method: PUT
- URI: `/admin/master-data/languages/{language}`
- Nama Route: `admin.master_data.languages.update`
- Middleware: `auth`, `permission:languages.update`

#### 13.3.6 Hapus Bahasa
- Method: DELETE
- URI: `/admin/master-data/languages/{language}`
- Nama Route: `admin.master_data.languages.destroy`
- Middleware: `auth`, `permission:languages.delete`

### 13.4 Klasifikasi

#### 13.4.1 Daftar Klasifikasi
- Method: GET
- URI: `/admin/master-data/classifications`
- Nama Route: `admin.master_data.classifications.index`
- Middleware: `auth`, `permission:classifications.view`
- Menu: MNU-MAS-004
- Use Case: UC-MAS-004

#### 13.4.2 Form Tambah Klasifikasi
- Method: GET
- URI: `/admin/master-data/classifications/create`
- Nama Route: `admin.master_data.classifications.create`
- Middleware: `auth`, `permission:classifications.create`

#### 13.4.3 Simpan Klasifikasi
- Method: POST
- URI: `/admin/master-data/classifications`
- Nama Route: `admin.master_data.classifications.store`
- Middleware: `auth`, `permission:classifications.create`

#### 13.4.4 Form Edit Klasifikasi
- Method: GET
- URI: `/admin/master-data/classifications/{classification}/edit`
- Nama Route: `admin.master_data.classifications.edit`
- Middleware: `auth`, `permission:classifications.update`

#### 13.4.5 Update Klasifikasi
- Method: PUT
- URI: `/admin/master-data/classifications/{classification}`
- Nama Route: `admin.master_data.classifications.update`
- Middleware: `auth`, `permission:classifications.update`

#### 13.4.6 Hapus Klasifikasi
- Method: DELETE
- URI: `/admin/master-data/classifications/{classification}`
- Nama Route: `admin.master_data.classifications.destroy`
- Middleware: `auth`, `permission:classifications.delete`

### 13.5 Subjek

#### 13.5.1 Daftar Subjek
- Method: GET
- URI: `/admin/master-data/subjects`
- Nama Route: `admin.master_data.subjects.index`
- Middleware: `auth`, `permission:subjects.view`
- Menu: MNU-MAS-005
- Use Case: UC-MAS-005

#### 13.5.2 Form Tambah Subjek
- Method: GET
- URI: `/admin/master-data/subjects/create`
- Nama Route: `admin.master_data.subjects.create`
- Middleware: `auth`, `permission:subjects.create`

#### 13.5.3 Simpan Subjek
- Method: POST
- URI: `/admin/master-data/subjects`
- Nama Route: `admin.master_data.subjects.store`
- Middleware: `auth`, `permission:subjects.create`

#### 13.5.4 Form Edit Subjek
- Method: GET
- URI: `/admin/master-data/subjects/{subject}/edit`
- Nama Route: `admin.master_data.subjects.edit`
- Middleware: `auth`, `permission:subjects.update`

#### 13.5.5 Update Subjek
- Method: PUT
- URI: `/admin/master-data/subjects/{subject}`
- Nama Route: `admin.master_data.subjects.update`
- Middleware: `auth`, `permission:subjects.update`

#### 13.5.6 Hapus Subjek
- Method: DELETE
- URI: `/admin/master-data/subjects/{subject}`
- Nama Route: `admin.master_data.subjects.destroy`
- Middleware: `auth`, `permission:subjects.delete`

### 13.6 Jenis Koleksi

#### 13.6.1 Daftar Jenis Koleksi
- Method: GET
- URI: `/admin/master-data/collection-types`
- Nama Route: `admin.master_data.collection_types.index`
- Middleware: `auth`, `permission:collection_types.view`
- Menu: MNU-MAS-006
- Use Case: UC-MAS-006

#### 13.6.2 Form Tambah Jenis Koleksi
- Method: GET
- URI: `/admin/master-data/collection-types/create`
- Nama Route: `admin.master_data.collection_types.create`
- Middleware: `auth`, `permission:collection_types.create`

#### 13.6.3 Simpan Jenis Koleksi
- Method: POST
- URI: `/admin/master-data/collection-types`
- Nama Route: `admin.master_data.collection_types.store`
- Middleware: `auth`, `permission:collection_types.create`

#### 13.6.4 Form Edit Jenis Koleksi
- Method: GET
- URI: `/admin/master-data/collection-types/{collectionType}/edit`
- Nama Route: `admin.master_data.collection_types.edit`
- Middleware: `auth`, `permission:collection_types.update`

#### 13.6.5 Update Jenis Koleksi
- Method: PUT
- URI: `/admin/master-data/collection-types/{collectionType}`
- Nama Route: `admin.master_data.collection_types.update`
- Middleware: `auth`, `permission:collection_types.update`

#### 13.6.6 Hapus Jenis Koleksi
- Method: DELETE
- URI: `/admin/master-data/collection-types/{collectionType}`
- Nama Route: `admin.master_data.collection_types.destroy`
- Middleware: `auth`, `permission:collection_types.delete`

### 13.7 Lokasi Rak

#### 13.7.1 Daftar Lokasi Rak
- Method: GET
- URI: `/admin/master-data/rack-locations`
- Nama Route: `admin.master_data.rack_locations.index`
- Middleware: `auth`, `permission:rack_locations.view`
- Menu: MNU-MAS-007
- Use Case: UC-MAS-007

#### 13.7.2 Form Tambah Lokasi Rak
- Method: GET
- URI: `/admin/master-data/rack-locations/create`
- Nama Route: `admin.master_data.rack_locations.create`
- Middleware: `auth`, `permission:rack_locations.create`

#### 13.7.3 Simpan Lokasi Rak
- Method: POST
- URI: `/admin/master-data/rack-locations`
- Nama Route: `admin.master_data.rack_locations.store`
- Middleware: `auth`, `permission:rack_locations.create`

#### 13.7.4 Form Edit Lokasi Rak
- Method: GET
- URI: `/admin/master-data/rack-locations/{rackLocation}/edit`
- Nama Route: `admin.master_data.rack_locations.edit`
- Middleware: `auth`, `permission:rack_locations.update`

#### 13.7.5 Update Lokasi Rak
- Method: PUT
- URI: `/admin/master-data/rack-locations/{rackLocation}`
- Nama Route: `admin.master_data.rack_locations.update`
- Middleware: `auth`, `permission:rack_locations.update`

#### 13.7.6 Hapus Lokasi Rak
- Method: DELETE
- URI: `/admin/master-data/rack-locations/{rackLocation}`
- Nama Route: `admin.master_data.rack_locations.destroy`
- Middleware: `auth`, `permission:rack_locations.delete`

### 13.8 Fakultas

#### 13.8.1 Daftar Fakultas
- Method: GET
- URI: `/admin/master-data/faculties`
- Nama Route: `admin.master_data.faculties.index`
- Middleware: `auth`, `permission:faculties.view`
- Menu: MNU-MAS-008
- Use Case: UC-MAS-008

#### 13.8.2 Form Tambah Fakultas
- Method: GET
- URI: `/admin/master-data/faculties/create`
- Nama Route: `admin.master_data.faculties.create`
- Middleware: `auth`, `permission:faculties.create`

#### 13.8.3 Simpan Fakultas
- Method: POST
- URI: `/admin/master-data/faculties`
- Nama Route: `admin.master_data.faculties.store`
- Middleware: `auth`, `permission:faculties.create`

#### 13.8.4 Form Edit Fakultas
- Method: GET
- URI: `/admin/master-data/faculties/{faculty}/edit`
- Nama Route: `admin.master_data.faculties.edit`
- Middleware: `auth`, `permission:faculties.update`

#### 13.8.5 Update Fakultas
- Method: PUT
- URI: `/admin/master-data/faculties/{faculty}`
- Nama Route: `admin.master_data.faculties.update`
- Middleware: `auth`, `permission:faculties.update`

#### 13.8.6 Hapus Fakultas
- Method: DELETE
- URI: `/admin/master-data/faculties/{faculty}`
- Nama Route: `admin.master_data.faculties.destroy`
- Middleware: `auth`, `permission:faculties.delete`

### 13.9 Program Studi

#### 13.9.1 Daftar Program Studi
- Method: GET
- URI: `/admin/master-data/study-programs`
- Nama Route: `admin.master_data.study_programs.index`
- Middleware: `auth`, `permission:study_programs.view`
- Menu: MNU-MAS-009
- Use Case: UC-MAS-009

#### 13.9.2 Form Tambah Program Studi
- Method: GET
- URI: `/admin/master-data/study-programs/create`
- Nama Route: `admin.master_data.study_programs.create`
- Middleware: `auth`, `permission:study_programs.create`

#### 13.9.3 Simpan Program Studi
- Method: POST
- URI: `/admin/master-data/study-programs`
- Nama Route: `admin.master_data.study_programs.store`
- Middleware: `auth`, `permission:study_programs.create`

#### 13.9.4 Form Edit Program Studi
- Method: GET
- URI: `/admin/master-data/study-programs/{studyProgram}/edit`
- Nama Route: `admin.master_data.study_programs.edit`
- Middleware: `auth`, `permission:study_programs.update`

#### 13.9.5 Update Program Studi
- Method: PUT
- URI: `/admin/master-data/study-programs/{studyProgram}`
- Nama Route: `admin.master_data.study_programs.update`
- Middleware: `auth`, `permission:study_programs.update`

#### 13.9.6 Hapus Program Studi
- Method: DELETE
- URI: `/admin/master-data/study-programs/{studyProgram}`
- Nama Route: `admin.master_data.study_programs.destroy`
- Middleware: `auth`, `permission:study_programs.delete`

### 13.10 Kondisi Item

#### 13.10.1 Daftar Kondisi Item
- Method: GET
- URI: `/admin/master-data/item-conditions`
- Nama Route: `admin.master_data.item_conditions.index`
- Middleware: `auth`, `permission:item_conditions.view`
- Menu: MNU-MAS-010
- Use Case: UC-MAS-010

#### 13.10.2 Form Tambah Kondisi Item
- Method: GET
- URI: `/admin/master-data/item-conditions/create`
- Nama Route: `admin.master_data.item_conditions.create`
- Middleware: `auth`, `permission:item_conditions.create`

#### 13.10.3 Simpan Kondisi Item
- Method: POST
- URI: `/admin/master-data/item-conditions`
- Nama Route: `admin.master_data.item_conditions.store`
- Middleware: `auth`, `permission:item_conditions.create`

#### 13.10.4 Form Edit Kondisi Item
- Method: GET
- URI: `/admin/master-data/item-conditions/{itemCondition}/edit`
- Nama Route: `admin.master_data.item_conditions.edit`
- Middleware: `auth`, `permission:item_conditions.update`

#### 13.10.5 Update Kondisi Item
- Method: PUT
- URI: `/admin/master-data/item-conditions/{itemCondition}`
- Nama Route: `admin.master_data.item_conditions.update`
- Middleware: `auth`, `permission:item_conditions.update`

#### 13.10.6 Hapus Kondisi Item
- Method: DELETE
- URI: `/admin/master-data/item-conditions/{itemCondition}`
- Nama Route: `admin.master_data.item_conditions.destroy`
- Middleware: `auth`, `permission:item_conditions.delete`

## 14. Route Modul Katalog

### 14.1 Daftar Katalog
- Method: GET
- URI: `/admin/catalog/records`
- Nama Route: `admin.catalog.records.index`
- Middleware: `auth`, `permission:catalog.view`
- Menu: MNU-CAT-001
- Use Case: UC-CAT-003, UC-CAT-004

### 14.2 Form Tambah Katalog
- Method: GET
- URI: `/admin/catalog/records/create`
- Nama Route: `admin.catalog.records.create`
- Middleware: `auth`, `permission:catalog.create`
- Menu: MNU-CAT-002
- Use Case: UC-CAT-001

### 14.3 Simpan Katalog
- Method: POST
- URI: `/admin/catalog/records`
- Nama Route: `admin.catalog.records.store`
- Middleware: `auth`, `permission:catalog.create`
- Use Case: UC-CAT-001

### 14.4 Detail Katalog
- Method: GET
- URI: `/admin/catalog/records/{record}`
- Nama Route: `admin.catalog.records.show`
- Middleware: `auth`, `permission:catalog.view_detail`
- Menu: MNU-CAT-003
- Use Case: UC-CAT-003

### 14.5 Form Edit Katalog
- Method: GET
- URI: `/admin/catalog/records/{record}/edit`
- Nama Route: `admin.catalog.records.edit`
- Middleware: `auth`, `permission:catalog.update`
- Menu: MNU-CAT-004
- Use Case: UC-CAT-002

### 14.6 Update Katalog
- Method: PUT
- URI: `/admin/catalog/records/{record}`
- Nama Route: `admin.catalog.records.update`
- Middleware: `auth`, `permission:catalog.update`
- Use Case: UC-CAT-002

### 14.7 Hapus Katalog
- Method: DELETE
- URI: `/admin/catalog/records/{record}`
- Nama Route: `admin.catalog.records.destroy`
- Middleware: `auth`, `permission:catalog.delete`
- Use Case: UC-CAT-002

### 14.8 Publish Katalog
- Method: PATCH
- URI: `/admin/catalog/records/{record}/publish`
- Nama Route: `admin.catalog.records.publish`
- Middleware: `auth`, `permission:catalog.publish`
- Use Case: UC-CAT-002

### 14.9 Unpublish Katalog
- Method: PATCH
- URI: `/admin/catalog/records/{record}/unpublish`
- Nama Route: `admin.catalog.records.unpublish`
- Middleware: `auth`, `permission:catalog.unpublish`
- Use Case: UC-CAT-007

## 15. Route Modul Koleksi Fisik

### 15.1 Daftar Item
- Method: GET
- URI: `/admin/collections/items`
- Nama Route: `admin.collections.items.index`
- Middleware: `auth`, `permission:collections.view`
- Menu: MNU-COL-001
- Use Case: UC-COL-003, UC-COL-004

### 15.2 Form Tambah Item
- Method: GET
- URI: `/admin/collections/items/create`
- Nama Route: `admin.collections.items.create`
- Middleware: `auth`, `permission:collections.create`
- Menu: MNU-COL-002
- Use Case: UC-COL-001

### 15.3 Simpan Item
- Method: POST
- URI: `/admin/collections/items`
- Nama Route: `admin.collections.items.store`
- Middleware: `auth`, `permission:collections.create`
- Use Case: UC-COL-001

### 15.4 Detail Item
- Method: GET
- URI: `/admin/collections/items/{item}`
- Nama Route: `admin.collections.items.show`
- Middleware: `auth`, `permission:collections.view_detail`
- Menu: MNU-COL-003
- Use Case: UC-COL-003

### 15.5 Form Edit Item
- Method: GET
- URI: `/admin/collections/items/{item}/edit`
- Nama Route: `admin.collections.items.edit`
- Middleware: `auth`, `permission:collections.update`
- Menu: MNU-COL-004
- Use Case: UC-COL-002

### 15.6 Update Item
- Method: PUT
- URI: `/admin/collections/items/{item}`
- Nama Route: `admin.collections.items.update`
- Middleware: `auth`, `permission:collections.update`
- Use Case: UC-COL-002

### 15.7 Hapus Item
- Method: DELETE
- URI: `/admin/collections/items/{item}`
- Nama Route: `admin.collections.items.destroy`
- Middleware: `auth`, `permission:collections.delete`
- Use Case: UC-COL-002

### 15.8 Ubah Status Item
- Method: PATCH
- URI: `/admin/collections/items/{item}/status`
- Nama Route: `admin.collections.items.change_status`
- Middleware: `auth`, `permission:collections.change_status`
- Use Case: UC-COL-005

### 15.9 Histori Item
- Method: GET
- URI: `/admin/collections/items/{item}/history`
- Nama Route: `admin.collections.items.history`
- Middleware: `auth`, `permission:collections.view_history`
- Menu: MNU-COL-005
- Use Case: UC-COL-006

## 16. Route Modul Anggota

### 16.1 Daftar Anggota
- Method: GET
- URI: `/admin/members`
- Nama Route: `admin.members.index`
- Middleware: `auth`, `permission:members.view`
- Menu: MNU-MEM-001
- Use Case: UC-MEM-003, UC-MEM-004

### 16.2 Form Tambah Anggota
- Method: GET
- URI: `/admin/members/create`
- Nama Route: `admin.members.create`
- Middleware: `auth`, `permission:members.create`
- Menu: MNU-MEM-002
- Use Case: UC-MEM-001

### 16.3 Simpan Anggota
- Method: POST
- URI: `/admin/members`
- Nama Route: `admin.members.store`
- Middleware: `auth`, `permission:members.create`
- Use Case: UC-MEM-001

### 16.4 Detail Anggota
- Method: GET
- URI: `/admin/members/{member}`
- Nama Route: `admin.members.show`
- Middleware: `auth`, `permission:members.view_detail`
- Menu: MNU-MEM-003
- Use Case: UC-MEM-003

### 16.5 Form Edit Anggota
- Method: GET
- URI: `/admin/members/{member}/edit`
- Nama Route: `admin.members.edit`
- Middleware: `auth`, `permission:members.update`
- Menu: MNU-MEM-004
- Use Case: UC-MEM-002

### 16.6 Update Anggota
- Method: PUT
- URI: `/admin/members/{member}`
- Nama Route: `admin.members.update`
- Middleware: `auth`, `permission:members.update`
- Use Case: UC-MEM-002

### 16.7 Hapus Anggota
- Method: DELETE
- URI: `/admin/members/{member}`
- Nama Route: `admin.members.destroy`
- Middleware: `auth`, `permission:members.delete`
- Use Case: UC-MEM-002

### 16.8 Aktivasi Anggota
- Method: PATCH
- URI: `/admin/members/{member}/activate`
- Nama Route: `admin.members.activate`
- Middleware: `auth`, `permission:members.activate`
- Use Case: UC-MEM-005

### 16.9 Nonaktifkan Anggota
- Method: PATCH
- URI: `/admin/members/{member}/deactivate`
- Nama Route: `admin.members.deactivate`
- Middleware: `auth`, `permission:members.deactivate`
- Use Case: UC-MEM-005

### 16.10 Blokir Anggota
- Method: PATCH
- URI: `/admin/members/{member}/block`
- Nama Route: `admin.members.block`
- Middleware: `auth`, `permission:members.block`
- Use Case: UC-MEM-006

### 16.11 Buka Blokir Anggota
- Method: PATCH
- URI: `/admin/members/{member}/unblock`
- Nama Route: `admin.members.unblock`
- Middleware: `auth`, `permission:members.unblock`
- Use Case: UC-MEM-006

### 16.12 Histori Anggota
- Method: GET
- URI: `/admin/members/{member}/history`
- Nama Route: `admin.members.history`
- Middleware: `auth`, `permission:members.view_history`
- Menu: MNU-MEM-005
- Use Case: UC-MEM-007

### 16.13 Import Anggota
- Method: GET
- URI: `/admin/members/import`
- Nama Route: `admin.members.import.form`
- Middleware: `auth`, `permission:members.import`
- Use Case: fase lanjutan

### 16.14 Proses Import Anggota
- Method: POST
- URI: `/admin/members/import`
- Nama Route: `admin.members.import.store`
- Middleware: `auth`, `permission:members.import`
- Use Case: fase lanjutan

## 17. Route Modul Sirkulasi

### 17.1 Halaman Peminjaman
- Method: GET
- URI: `/admin/circulation/loans/create`
- Nama Route: `admin.circulation.loans.create`
- Middleware: `auth`, `permission:circulation.process_loan`
- Menu: MNU-CIR-001
- Use Case: UC-CIR-001

### 17.2 Proses Peminjaman
- Method: POST
- URI: `/admin/circulation/loans`
- Nama Route: `admin.circulation.loans.store`
- Middleware: `auth`, `permission:circulation.process_loan`
- Use Case: UC-CIR-001

### 17.3 Halaman Pengembalian
- Method: GET
- URI: `/admin/circulation/returns/create`
- Nama Route: `admin.circulation.returns.create`
- Middleware: `auth`, `permission:circulation.process_return`
- Menu: MNU-CIR-002
- Use Case: UC-CIR-002

### 17.4 Proses Pengembalian
- Method: POST
- URI: `/admin/circulation/returns`
- Nama Route: `admin.circulation.returns.store`
- Middleware: `auth`, `permission:circulation.process_return`
- Use Case: UC-CIR-002

### 17.5 Halaman Perpanjangan
- Method: GET
- URI: `/admin/circulation/renewals`
- Nama Route: `admin.circulation.renewals.index`
- Middleware: `auth`, `permission:circulation.process_renewal`
- Menu: MNU-CIR-003
- Use Case: UC-CIR-003

### 17.6 Proses Perpanjangan
- Method: PATCH
- URI: `/admin/circulation/loans/{loan}/renew`
- Nama Route: `admin.circulation.loans.renew`
- Middleware: `auth`, `permission:circulation.process_renewal`
- Use Case: UC-CIR-003

### 17.7 Daftar Pinjaman Aktif
- Method: GET
- URI: `/admin/circulation/active-loans`
- Nama Route: `admin.circulation.active_loans.index`
- Middleware: `auth`, `permission:circulation.view_active_loans`
- Menu: MNU-CIR-004
- Use Case: UC-CIR-004

### 17.8 Detail Pinjaman
- Method: GET
- URI: `/admin/circulation/loans/{loan}`
- Nama Route: `admin.circulation.loans.show`
- Middleware: `auth`, `permission:circulation.view_active_loans`
- Use Case: UC-CIR-004

### 17.9 Histori Sirkulasi
- Method: GET
- URI: `/admin/circulation/history`
- Nama Route: `admin.circulation.history.index`
- Middleware: `auth`, `permission:circulation.view_history`
- Menu: MNU-CIR-005
- Use Case: UC-CIR-005

### 17.10 Denda dan Keterlambatan
- Method: GET
- URI: `/admin/circulation/fines`
- Nama Route: `admin.circulation.fines.index`
- Middleware: `auth`, `permission:circulation.view_fines`
- Menu: MNU-CIR-006
- Use Case: UC-CIR-006

## 18. Route Modul Repositori Digital

### 18.1 Daftar Aset Digital
- Method: GET
- URI: `/admin/digital-repository/assets`
- Nama Route: `admin.digital.assets.index`
- Middleware: `auth`, `permission:digital_assets.view`
- Menu: MNU-DIG-001
- Use Case: UC-DIG-003

### 18.2 Form Unggah Aset Digital
- Method: GET
- URI: `/admin/digital-repository/assets/create`
- Nama Route: `admin.digital.assets.create`
- Middleware: `auth`, `permission:digital_assets.create`
- Menu: MNU-DIG-002
- Use Case: UC-DIG-001

### 18.3 Simpan Aset Digital
- Method: POST
- URI: `/admin/digital-repository/assets`
- Nama Route: `admin.digital.assets.store`
- Middleware: `auth`, `permission:digital_assets.create`
- Use Case: UC-DIG-001

### 18.4 Detail Aset Digital
- Method: GET
- URI: `/admin/digital-repository/assets/{asset}`
- Nama Route: `admin.digital.assets.show`
- Middleware: `auth`, `permission:digital_assets.view_detail`
- Menu: MNU-DIG-003
- Use Case: UC-DIG-003, UC-DIG-004

### 18.5 Form Edit Aset Digital
- Method: GET
- URI: `/admin/digital-repository/assets/{asset}/edit`
- Nama Route: `admin.digital.assets.edit`
- Middleware: `auth`, `permission:digital_assets.update`
- Menu: MNU-DIG-004
- Use Case: UC-DIG-002

### 18.6 Update Aset Digital
- Method: PUT
- URI: `/admin/digital-repository/assets/{asset}`
- Nama Route: `admin.digital.assets.update`
- Middleware: `auth`, `permission:digital_assets.update`
- Use Case: UC-DIG-002

### 18.7 Hapus Aset Digital
- Method: DELETE
- URI: `/admin/digital-repository/assets/{asset}`
- Nama Route: `admin.digital.assets.destroy`
- Middleware: `auth`, `permission:digital_assets.delete`
- Use Case: UC-DIG-002

### 18.8 Preview Aset Digital Internal
- Method: GET
- URI: `/admin/digital-repository/assets/{asset}/preview`
- Nama Route: `admin.digital.assets.preview`
- Middleware: `auth`, `permission:digital_assets.preview`
- Use Case: UC-DIG-004

### 18.9 Publish Aset Digital
- Method: PATCH
- URI: `/admin/digital-repository/assets/{asset}/publish`
- Nama Route: `admin.digital.assets.publish`
- Middleware: `auth`, `permission:digital_assets.publish`
- Use Case: UC-DIG-006

### 18.10 Unpublish Aset Digital
- Method: PATCH
- URI: `/admin/digital-repository/assets/{asset}/unpublish`
- Nama Route: `admin.digital.assets.unpublish`
- Middleware: `auth`, `permission:digital_assets.unpublish`
- Use Case: UC-DIG-006

### 18.11 Update Akses Aset Digital
- Method: PATCH
- URI: `/admin/digital-repository/assets/{asset}/access`
- Nama Route: `admin.digital.assets.access.update`
- Middleware: `auth`, `permission:digital_assets.manage_access`
- Use Case: UC-DIG-005

### 18.12 Jalankan OCR
- Method: POST
- URI: `/admin/digital-repository/assets/{asset}/ocr`
- Nama Route: `admin.digital.assets.ocr.run`
- Middleware: `auth`, `permission:digital_assets.run_ocr`
- Menu: MNU-DIG-005
- Use Case: UC-DIG-007

### 18.13 Jalankan Reindex
- Method: POST
- URI: `/admin/digital-repository/assets/{asset}/reindex`
- Nama Route: `admin.digital.assets.reindex`
- Middleware: `auth`, `permission:digital_assets.reindex`
- Menu: MNU-DIG-005
- Use Case: UC-DIG-008

### 18.14 Unduh Aset Privat Internal
- Method: GET
- URI: `/admin/digital-repository/assets/{asset}/download`
- Nama Route: `admin.digital.assets.download`
- Middleware: `auth`, `permission:digital_assets.download_private`
- Use Case: akses khusus internal

## 19. Route Modul Laporan

### 19.1 Dashboard Statistik
- Method: GET
- URI: `/admin/reports/dashboard`
- Nama Route: `admin.reports.dashboard`
- Middleware: `auth`, `permission:reports.view_dashboard`
- Menu: MNU-REP-001
- Use Case: UC-REP-001

### 19.2 Laporan Koleksi
- Method: GET
- URI: `/admin/reports/collections`
- Nama Route: `admin.reports.collections.index`
- Middleware: `auth`, `permission:reports.view_collections`
- Menu: MNU-REP-002
- Use Case: UC-REP-002

### 19.3 Laporan Anggota
- Method: GET
- URI: `/admin/reports/members`
- Nama Route: `admin.reports.members.index`
- Middleware: `auth`, `permission:reports.view_members`
- Menu: MNU-REP-003
- Use Case: UC-REP-003

### 19.4 Laporan Sirkulasi
- Method: GET
- URI: `/admin/reports/circulation`
- Nama Route: `admin.reports.circulation.index`
- Middleware: `auth`, `permission:reports.view_circulation`
- Menu: MNU-REP-004
- Use Case: UC-REP-004

### 19.5 Laporan Denda
- Method: GET
- URI: `/admin/reports/fines`
- Nama Route: `admin.reports.fines.index`
- Middleware: `auth`, `permission:reports.view_fines`
- Menu: MNU-REP-005
- Use Case: UC-REP-005

### 19.6 Laporan Koleksi Populer
- Method: GET
- URI: `/admin/reports/popular-collections`
- Nama Route: `admin.reports.popular_collections.index`
- Middleware: `auth`, `permission:reports.view_popular_collections`
- Menu: MNU-REP-006
- Use Case: UC-REP-006

### 19.7 Laporan Akses Digital
- Method: GET
- URI: `/admin/reports/digital-access`
- Nama Route: `admin.reports.digital_access.index`
- Middleware: `auth`, `permission:reports.view_digital_access`
- Menu: MNU-REP-007
- Use Case: UC-REP-007

### 19.8 Export Laporan Koleksi
- Method: GET
- URI: `/admin/reports/collections/export`
- Nama Route: `admin.reports.collections.export`
- Middleware: `auth`, `permission:reports.export`

### 19.9 Export Laporan Anggota
- Method: GET
- URI: `/admin/reports/members/export`
- Nama Route: `admin.reports.members.export`
- Middleware: `auth`, `permission:reports.export`

### 19.10 Export Laporan Sirkulasi
- Method: GET
- URI: `/admin/reports/circulation/export`
- Nama Route: `admin.reports.circulation.export`
- Middleware: `auth`, `permission:reports.export`

### 19.11 Export Laporan Denda
- Method: GET
- URI: `/admin/reports/fines/export`
- Nama Route: `admin.reports.fines.export`
- Middleware: `auth`, `permission:reports.export`

### 19.12 Export Laporan Koleksi Populer
- Method: GET
- URI: `/admin/reports/popular-collections/export`
- Nama Route: `admin.reports.popular_collections.export`
- Middleware: `auth`, `permission:reports.export`

### 19.13 Export Laporan Akses Digital
- Method: GET
- URI: `/admin/reports/digital-access/export`
- Nama Route: `admin.reports.digital_access.export`
- Middleware: `auth`, `permission:reports.export`

## 20. Route Modul Audit dan Monitoring

### 20.1 Audit Log
- Method: GET
- URI: `/admin/audit/logs`
- Nama Route: `admin.audit.logs.index`
- Middleware: `auth`, `permission:audit_logs.view`
- Menu: MNU-AUD-001
- Use Case: UC-AUD-001, UC-AUD-002

### 20.2 Detail Audit Log
- Method: GET
- URI: `/admin/audit/logs/{log}`
- Nama Route: `admin.audit.logs.show`
- Middleware: `auth`, `permission:audit_logs.view_detail`
- Menu: MNU-AUD-001
- Use Case: UC-AUD-002

### 20.3 Monitoring Queue
- Method: GET
- URI: `/admin/audit/queue-monitor`
- Nama Route: `admin.audit.queue_monitor.index`
- Middleware: `auth`, `permission:queue_monitor.view`
- Menu: MNU-AUD-002
- Use Case: UC-AUD-003

### 20.4 Retry Job Tertentu
- Method: POST
- URI: `/admin/audit/queue-monitor/{job}/retry`
- Nama Route: `admin.audit.queue_monitor.retry`
- Middleware: `auth`, `permission:queue_monitor.manage_retry`
- Use Case: tindakan teknis admin

## 21. Route Modul Pengaturan Sistem

### 21.1 Profil Institusi
- Method: GET
- URI: `/admin/settings/institution-profile`
- Nama Route: `admin.settings.institution_profile.edit`
- Middleware: `auth`, `permission:core.view_institution_profile`
- Menu: MNU-SET-001
- Use Case: UC-CORE-001

### 21.2 Update Profil Institusi
- Method: PUT
- URI: `/admin/settings/institution-profile`
- Nama Route: `admin.settings.institution_profile.update`
- Middleware: `auth`, `permission:core.update_institution_profile`
- Use Case: UC-CORE-001

### 21.3 Aturan Operasional
- Method: GET
- URI: `/admin/settings/operational-rules`
- Nama Route: `admin.settings.operational_rules.edit`
- Middleware: `auth`, `permission:core.view_operational_rules`
- Menu: MNU-SET-002
- Use Case: UC-CORE-003

### 21.4 Update Aturan Operasional
- Method: PUT
- URI: `/admin/settings/operational-rules`
- Nama Route: `admin.settings.operational_rules.update`
- Middleware: `auth`, `permission:core.update_operational_rules`
- Use Case: UC-CORE-003

## 22. Route Modul Manajemen Akses

### 22.1 Daftar Pengguna
- Method: GET
- URI: `/admin/access/users`
- Nama Route: `admin.access.users.index`
- Middleware: `auth`, `permission:users.view`
- Menu: MNU-ACC-001
- Use Case: UC-IDA-003

### 22.2 Form Tambah Pengguna
- Method: GET
- URI: `/admin/access/users/create`
- Nama Route: `admin.access.users.create`
- Middleware: `auth`, `permission:users.create`
- Use Case: UC-IDA-003

### 22.3 Simpan Pengguna
- Method: POST
- URI: `/admin/access/users`
- Nama Route: `admin.access.users.store`
- Middleware: `auth`, `permission:users.create`
- Use Case: UC-IDA-003

### 22.4 Detail Pengguna
- Method: GET
- URI: `/admin/access/users/{user}`
- Nama Route: `admin.access.users.show`
- Middleware: `auth`, `permission:users.view`
- Use Case: UC-IDA-003

### 22.5 Form Edit Pengguna
- Method: GET
- URI: `/admin/access/users/{user}/edit`
- Nama Route: `admin.access.users.edit`
- Middleware: `auth`, `permission:users.update`
- Use Case: UC-IDA-003

### 22.6 Update Pengguna
- Method: PUT
- URI: `/admin/access/users/{user}`
- Nama Route: `admin.access.users.update`
- Middleware: `auth`, `permission:users.update`
- Use Case: UC-IDA-003

### 22.7 Hapus Pengguna
- Method: DELETE
- URI: `/admin/access/users/{user}`
- Nama Route: `admin.access.users.destroy`
- Middleware: `auth`, `permission:users.delete`
- Use Case: UC-IDA-003

### 22.8 Aktivasi Pengguna
- Method: PATCH
- URI: `/admin/access/users/{user}/activate`
- Nama Route: `admin.access.users.activate`
- Middleware: `auth`, `permission:users.activate`
- Use Case: UC-IDA-003

### 22.9 Reset Password Pengguna
- Method: PATCH
- URI: `/admin/access/users/{user}/reset-password`
- Nama Route: `admin.access.users.reset_password`
- Middleware: `auth`, `permission:users.reset_password`
- Use Case: UC-IDA-003

### 22.10 Daftar Role
- Method: GET
- URI: `/admin/access/roles`
- Nama Route: `admin.access.roles.index`
- Middleware: `auth`, `permission:roles.view`
- Menu: MNU-ACC-002
- Use Case: UC-IDA-004

### 22.11 Form Tambah Role
- Method: GET
- URI: `/admin/access/roles/create`
- Nama Route: `admin.access.roles.create`
- Middleware: `auth`, `permission:roles.create`
- Use Case: UC-IDA-004

### 22.12 Simpan Role
- Method: POST
- URI: `/admin/access/roles`
- Nama Route: `admin.access.roles.store`
- Middleware: `auth`, `permission:roles.create`
- Use Case: UC-IDA-004

### 22.13 Form Edit Role
- Method: GET
- URI: `/admin/access/roles/{role}/edit`
- Nama Route: `admin.access.roles.edit`
- Middleware: `auth`, `permission:roles.update`
- Use Case: UC-IDA-004

### 22.14 Update Role
- Method: PUT
- URI: `/admin/access/roles/{role}`
- Nama Route: `admin.access.roles.update`
- Middleware: `auth`, `permission:roles.update`
- Use Case: UC-IDA-004

### 22.15 Hapus Role
- Method: DELETE
- URI: `/admin/access/roles/{role}`
- Nama Route: `admin.access.roles.destroy`
- Middleware: `auth`, `permission:roles.delete`
- Use Case: UC-IDA-004

### 22.16 Daftar Permission
- Method: GET
- URI: `/admin/access/permissions`
- Nama Route: `admin.access.permissions.index`
- Middleware: `auth`, `permission:permissions.view`
- Menu: MNU-ACC-003
- Use Case: UC-IDA-005

### 22.17 Update Permission Role
- Method: PATCH
- URI: `/admin/access/roles/{role}/permissions`
- Nama Route: `admin.access.roles.permissions.update`
- Middleware: `auth`, `permission:permissions.manage`
- Use Case: UC-IDA-005

### 22.18 Assign Role ke Pengguna
- Method: PATCH
- URI: `/admin/access/users/{user}/roles`
- Nama Route: `admin.access.users.roles.update`
- Middleware: `auth`, `permission:user_roles.assign`
- Use Case: UC-IDA-006

## 23. Route Utilitas Akun Pengguna Internal

### 23.1 Profil Saya
- Method: GET
- URI: `/admin/profile`
- Nama Route: `admin.profile.show`
- Middleware: `auth`, `permission:own_profile.view`
- Use Case: UC-IDA-008

### 23.2 Update Profil Saya
- Method: PUT
- URI: `/admin/profile`
- Nama Route: `admin.profile.update`
- Middleware: `auth`, `permission:own_profile.update`
- Use Case: UC-IDA-008

### 23.3 Form Ubah Password Saya
- Method: GET
- URI: `/admin/profile/change-password`
- Nama Route: `admin.profile.password.edit`
- Middleware: `auth`, `permission:own_password.change`
- Use Case: UC-IDA-007

### 23.4 Proses Ubah Password Saya
- Method: PUT
- URI: `/admin/profile/change-password`
- Nama Route: `admin.profile.password.update`
- Middleware: `auth`, `permission:own_password.change`
- Use Case: UC-IDA-007

## 24. Route Akses Aset Digital Publik dan Privat

### 24.1 Preview Publik Aset
- Method: GET
- URI: `/assets/public/{asset}/preview`
- Nama Route: `assets.public.preview`
- Middleware: `web`
- Permission: rule publik
- Tujuan: fallback preview publik bila diperlukan secara arsitektural

### 24.2 Akses Privat Aset Internal
- Method: GET
- URI: `/assets/private/{asset}`
- Nama Route: `assets.private.show`
- Middleware: `auth`
- Permission: diperiksa di controller atau policy
- Tujuan: menampilkan atau stream file privat setelah lolos rule akses

### 24.3 Download Privat Aset Internal
- Method: GET
- URI: `/assets/private/{asset}/download`
- Nama Route: `assets.private.download`
- Middleware: `auth`
- Permission: diperiksa di controller atau policy
- Tujuan: download file privat secara aman

Catatan:
1. Route ini tidak boleh membuka URL storage mentah langsung ke pengguna.
2. Pemeriksaan permission, status publikasi, dan embargo wajib dilakukan.

## 25. Route API Internal Terbatas
Pada fase 1, API internal tidak menjadi fokus utama, namun jalurnya disediakan sebagai ruang arsitektural terbatas.

Prefix:
- `/api/internal`

Aturan:
1. Hanya diaktifkan bila benar-benar diperlukan.
2. Harus dilindungi autentikasi token internal atau mekanisme resmi.
3. Tidak menjadi jalur utama UI admin.

Contoh calon route:
1. `GET /api/internal/catalog/search`
2. `GET /api/internal/members/lookup`
3. `POST /api/internal/imports/members`

Status:
1. Tidak wajib diimplementasikan penuh pada fase 1.
2. Bila belum dipakai, jangan dibuat dummy tanpa kebutuhan.

## 26. Route yang Tidak Diizinkan pada Fase 1
Route berikut tidak boleh muncul pada implementasi fase 1:

1. Route pembayaran online
2. Route multi tenant kampus
3. Route mobile app khusus
4. Route SSO aktif bila belum dirancang
5. Route RFID aktif
6. Route reservasi lanjutan
7. Route acquisition kompleks
8. Route API publik eksternal penuh

## 27. Aturan Method HTTP
Aturan penggunaan method HTTP resmi:

1. GET untuk membaca halaman atau detail
2. POST untuk membuat data atau memicu proses baru
3. PUT untuk update penuh
4. PATCH untuk update parsial atau aksi status
5. DELETE untuk hapus
6. Tidak boleh memakai GET untuk aksi yang mengubah data
7. Tidak boleh memakai POST untuk sekadar menampilkan form

## 28. Aturan Route Parameter
Aturan parameter route:

1. Gunakan parameter yang jelas dan singular
2. Gunakan route model binding bila sesuai
3. Gunakan slug publik hanya bila dibutuhkan pada OPAC
4. Gunakan id internal atau uuid sesuai desain model
5. Jangan menaruh terlalu banyak parameter dalam satu URI tanpa alasan

Contoh:
1. `{record}`
2. `{item}`
3. `{member}`
4. `{loan}`
5. `{asset}`
6. `{user}`
7. `{role}`

## 29. Aturan Middleware Permission
Aturan permission route:

1. Route list harus punya permission view
2. Route form tambah dan store harus punya permission create
3. Route detail harus punya permission view_detail atau view
4. Route edit dan update harus punya permission update
5. Route destroy harus punya permission delete
6. Route proses pinjam, kembali, perpanjang harus punya permission process համապատասխան
7. Route publish, unpublish, access update, OCR, reindex harus punya permission spesifik
8. Route export laporan harus punya permission reports.export

## 30. Aturan Route ke Menu
Aturan hubungan route dan menu:

1. Setiap menu utama harus punya minimal satu route index
2. Setiap tombol tambah harus punya route create dan store
3. Setiap halaman detail harus punya route show
4. Setiap halaman edit harus punya route edit dan update
5. Halaman turunan seperti history, export, preview, dan publish tidak harus muncul sebagai menu utama tetapi wajib punya rute resmi
6. Tidak boleh ada menu yang menunjuk ke route yang tidak terdokumentasi di sini

## 31. Mapping Ringkas Menu ke Route Index

| Kode Menu | Nama Menu | Route Index Utama |
|---|---|---|
| MNU-DASH-001 | Dashboard | admin.dashboard.index |
| MNU-MAS-001 | Pengarang | admin.master_data.authors.index |
| MNU-MAS-002 | Penerbit | admin.master_data.publishers.index |
| MNU-MAS-003 | Bahasa | admin.master_data.languages.index |
| MNU-MAS-004 | Klasifikasi | admin.master_data.classifications.index |
| MNU-MAS-005 | Subjek | admin.master_data.subjects.index |
| MNU-MAS-006 | Jenis Koleksi | admin.master_data.collection_types.index |
| MNU-MAS-007 | Lokasi Rak | admin.master_data.rack_locations.index |
| MNU-MAS-008 | Fakultas | admin.master_data.faculties.index |
| MNU-MAS-009 | Program Studi | admin.master_data.study_programs.index |
| MNU-MAS-010 | Kondisi Item | admin.master_data.item_conditions.index |
| MNU-CAT-001 | Daftar Katalog | admin.catalog.records.index |
| MNU-COL-001 | Daftar Item | admin.collections.items.index |
| MNU-MEM-001 | Daftar Anggota | admin.members.index |
| MNU-CIR-001 | Peminjaman | admin.circulation.loans.create |
| MNU-CIR-002 | Pengembalian | admin.circulation.returns.create |
| MNU-CIR-003 | Perpanjangan | admin.circulation.renewals.index |
| MNU-CIR-004 | Pinjaman Aktif | admin.circulation.active_loans.index |
| MNU-CIR-005 | Histori Sirkulasi | admin.circulation.history.index |
| MNU-CIR-006 | Denda dan Keterlambatan | admin.circulation.fines.index |
| MNU-DIG-001 | Daftar Aset Digital | admin.digital.assets.index |
| MNU-REP-001 | Dashboard Statistik | admin.reports.dashboard |
| MNU-REP-002 | Laporan Koleksi | admin.reports.collections.index |
| MNU-REP-003 | Laporan Anggota | admin.reports.members.index |
| MNU-REP-004 | Laporan Sirkulasi | admin.reports.circulation.index |
| MNU-REP-005 | Laporan Denda | admin.reports.fines.index |
| MNU-REP-006 | Laporan Koleksi Populer | admin.reports.popular_collections.index |
| MNU-REP-007 | Laporan Akses Digital | admin.reports.digital_access.index |
| MNU-AUD-001 | Audit Log | admin.audit.logs.index |
| MNU-AUD-002 | Monitoring Queue | admin.audit.queue_monitor.index |
| MNU-SET-001 | Profil Institusi | admin.settings.institution_profile.edit |
| MNU-SET-002 | Aturan Operasional | admin.settings.operational_rules.edit |
| MNU-ACC-001 | Pengguna | admin.access.users.index |
| MNU-ACC-002 | Role | admin.access.roles.index |
| MNU-ACC-003 | Permission | admin.access.permissions.index |
| MNU-OPA-001 | Beranda OPAC | opac.home |
| MNU-OPA-002 | Pencarian Katalog | opac.search.index |
| MNU-OPA-003 | Detail Koleksi | opac.records.show |

## 32. Mapping Route ke Use Case Utama

| Nama Route | Use Case |
|---|---|
| auth.login | UC-IDA-001 |
| auth.login.attempt | UC-IDA-001 |
| auth.logout | UC-IDA-002 |
| admin.dashboard.index | UC-CORE-004, UC-REP-001 |
| admin.catalog.records.store | UC-CAT-001 |
| admin.catalog.records.update | UC-CAT-002 |
| admin.catalog.records.show | UC-CAT-003 |
| admin.collections.items.store | UC-COL-001 |
| admin.collections.items.update | UC-COL-002 |
| admin.collections.items.show | UC-COL-003 |
| admin.members.store | UC-MEM-001 |
| admin.members.update | UC-MEM-002 |
| admin.members.show | UC-MEM-003 |
| admin.members.block | UC-MEM-006 |
| admin.circulation.loans.store | UC-CIR-001 |
| admin.circulation.returns.store | UC-CIR-002 |
| admin.circulation.loans.renew | UC-CIR-003 |
| admin.digital.assets.store | UC-DIG-001 |
| admin.digital.assets.update | UC-DIG-002 |
| admin.digital.assets.show | UC-DIG-003 |
| admin.digital.assets.preview | UC-DIG-004 |
| admin.digital.assets.access.update | UC-DIG-005 |
| admin.digital.assets.publish | UC-DIG-006 |
| admin.digital.assets.ocr.run | UC-DIG-007 |
| admin.digital.assets.reindex | UC-DIG-008 |
| opac.search.index | UC-OPA-001, UC-OPA-002 |
| opac.records.show | UC-OPA-003, UC-OPA-004 |
| opac.assets.preview | UC-OPA-005 |
| admin.reports.dashboard | UC-REP-001 |
| admin.audit.logs.index | UC-AUD-001 |
| admin.audit.logs.show | UC-AUD-002 |
| admin.audit.queue_monitor.index | UC-AUD-003 |

## 33. Aturan Pengelompokan File Route
Agar konsisten dengan arsitektur modular, file route disarankan dipisah per modul, misalnya:

1. `app/Modules/Core/routes/web.php`
2. `app/Modules/Identity/routes/web.php`
3. `app/Modules/MasterData/routes/web.php`
4. `app/Modules/Catalog/routes/web.php`
5. `app/Modules/Collection/routes/web.php`
6. `app/Modules/Member/routes/web.php`
7. `app/Modules/Circulation/routes/web.php`
8. `app/Modules/DigitalRepository/routes/web.php`
9. `app/Modules/OPAC/routes/web.php`
10. `app/Modules/Reporting/routes/web.php`
11. `app/Modules/Audit/routes/web.php`

Aturan:
1. Route publik OPAC dipisah dari route admin.
2. Route module file harus tetap didaftarkan secara terpusat oleh aplikasi inti.
3. Penamaan route name prefix per modul wajib konsisten dengan dokumen ini.

## 34. Aturan Redirect Baku
Redirect baku sistem:

1. Setelah login sukses, user diarahkan ke `admin.dashboard.index`
2. Setelah logout, user diarahkan ke `auth.login`
3. Setelah create sukses, user diarahkan ke halaman detail atau daftar sesuai keputusan UI per modul
4. Setelah update sukses, user tetap di detail atau kembali ke daftar sesuai konteks
5. Setelah proses pinjam dan kembali, user diarahkan kembali ke halaman transaksi dengan notifikasi hasil
6. Akses route tanpa permission diarahkan ke halaman 403
7. Akses resource tidak ditemukan diarahkan ke 404

## 35. Aturan Error Route
1. 403 untuk route ada tetapi permission tidak cukup
2. 404 untuk route atau resource tidak ditemukan
3. 419 untuk session invalid atau CSRF error
4. 500 hanya untuk error tak tertangani
5. Halaman error harus konsisten dengan branding sistem

## 36. Dampak ke Dokumen Berikutnya
Dokumen ini menjadi acuan wajib untuk:
1. 10_VIEW_MAP.md
2. 11_CONTROLLER_MAP.md
3. 12_SERVICE_LAYER.md
4. 13_MODEL_MAP.md
5. 16_VALIDATION_RULES.md
6. 17_WORKFLOW_STATE_MACHINE.md
7. 18_UI_UX_STANDARD.md
8. 29_AUDIT_LOG_SPEC.md
9. 31_TEST_PLAN.md
10. 32_TEST_SCENARIO.md
11. 39_TRACEABILITY_MATRIX.md

Aturan turunan:
1. Setiap route utama harus punya view page atau action handler yang jelas
2. Setiap route harus punya controller method yang jelas
3. Setiap route action harus punya validasi dan service yang sesuai bila perlu
4. Setiap route yang menyimpan data harus punya model dan schema pendukung
5. Test route harus mengikuti route map ini

## 37. Kesimpulan
Dokumen Route Map ini menetapkan struktur endpoint resmi PERPUSQU secara lengkap dan konsisten dengan Executive Summary, Stack Teknologi, Arsitektur Modular, PRD, SRS, Use Case, Role Permission Matrix, dan Menu Map yang telah disepakati sebelumnya. Dokumen ini menjadi acuan wajib untuk implementasi seluruh navigasi dan endpoint aplikasi, agar tidak terjadi route liar, broken flow, duplikasi endpoint, atau ketidaksesuaian antara menu, permission, controller, service, view, dan data.

END OF 09_ROUTE_MAP.md