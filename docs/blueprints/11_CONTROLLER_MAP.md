# 11_CONTROLLER_MAP.md

## 1. Nama Dokumen
Controller Map Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint peta controller aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan controller, method, orchestration layer, authorization, service binding, dan response view

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan seluruh controller resmi PERPUSQU, struktur class, method publik, tanggung jawab, relasi ke route, relasi ke view, dan relasi ke service layer. Dokumen ini menjadi acuan wajib agar tidak ada route tanpa controller, tidak ada controller tanpa use case, tidak ada method tanpa target view atau redirect, dan tidak ada controller yang memuat logika bisnis yang seharusnya berada pada service layer.

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

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep sistem tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Controller harus mengikuti route map resmi.
5. Controller harus mengembalikan view yang sesuai dengan view map.
6. Controller harus mematuhi role permission matrix.
7. Controller tidak boleh menaruh logika bisnis berat.
8. Controller tidak boleh menulis query rumit yang seharusnya berada di service atau query object.
9. Semua method aksi wajib konsisten dengan use case yang telah ditetapkan.

## 4. Prinsip Umum Controller
Prinsip resmi controller PERPUSQU adalah:

1. Controller berfungsi sebagai application orchestration layer.
2. Controller menerima request, memanggil validation, memanggil service, lalu mengembalikan response.
3. Controller tidak menjadi tempat logika bisnis utama.
4. Controller tidak boleh langsung menangani detail OCR, indexing, queue orchestration, atau aturan bisnis kompleks.
5. Controller harus tipis, terstruktur, dan mudah diuji.
6. Controller harus konsisten dalam naming, redirect, dan notifikasi hasil.
7. Controller GET harus jelas mengembalikan view mana.
8. Controller POST, PUT, PATCH, dan DELETE harus jelas mengembalikan redirect atau response hasil.
9. Setiap controller wajib memeriksa authorization melalui middleware, policy, atau helper authorization yang sah.

## 5. Struktur Folder Controller Resmi
Struktur folder controller yang disarankan mengikuti arsitektur modular:

```text
app/
  Modules/
    Core/
      Http/
        Controllers/
    Identity/
      Http/
        Controllers/
    MasterData/
      Http/
        Controllers/
    Catalog/
      Http/
        Controllers/
    Collection/
      Http/
        Controllers/
    Member/
      Http/
        Controllers/
    Circulation/
      Http/
        Controllers/
    DigitalRepository/
      Http/
        Controllers/
    Reporting/
      Http/
        Controllers/
    Audit/
      Http/
        Controllers/
    OPAC/
      Http/
        Controllers/
    Profile/
      Http/
        Controllers/
````

## 6. Konvensi Penamaan Controller

Aturan penamaan controller:

1. Gunakan PascalCase.
2. Nama controller harus mewakili domain resource atau proses.
3. Gunakan suffix `Controller`.
4. Hindari nama yang terlalu generik.
5. Gunakan pemisahan controller proses bila alur kerja sangat berbeda.

Contoh benar:

1. `DashboardController`
2. `BibliographicRecordController`
3. `PhysicalItemController`
4. `LoanController`
5. `ReturnController`
6. `DigitalAssetController`
7. `AuditLogController`

Contoh yang tidak dipakai:

1. `GeneralController`
2. `LibraryController`
3. `SystemActionController`
4. `ProcessController`

## 7. Aturan Method Controller

Method controller menggunakan konvensi berikut:

1. `index` untuk daftar data atau halaman utama resource
2. `create` untuk menampilkan form tambah
3. `store` untuk menyimpan data baru
4. `show` untuk detail data
5. `edit` untuk menampilkan form edit
6. `update` untuk memperbarui data
7. `destroy` untuk menghapus data
8. `publish` untuk aksi publish
9. `unpublish` untuk aksi unpublish
10. `activate` untuk aktivasi
11. `deactivate` untuk nonaktifasi
12. `block` untuk blokir
13. `unblock` untuk buka blokir
14. `history` untuk histori
15. `preview` untuk preview aset
16. `download` untuk unduh aset
17. `runOcr` untuk OCR
18. `reindex` untuk reindex
19. `renew` untuk perpanjangan
20. `retry` untuk retry job

## 8. Aturan Tanggung Jawab Controller

Controller wajib melakukan hal berikut:

1. Memeriksa request masuk melalui Form Request atau validasi terstruktur.
2. Memanggil service layer yang sesuai.
3. Memanggil policy bila dibutuhkan.
4. Menyiapkan data untuk view.
5. Mengembalikan redirect dengan flash message pada aksi write.
6. Menghindari coupling lintas modul yang tidak perlu.
7. Menangani error operasional secara terkendali.

Controller dilarang melakukan hal berikut:

1. Menulis logika hitung denda lengkap di controller.
2. Menulis seluruh logika OCR dan indexing di controller.
3. Menulis query relasi berat berulang di banyak controller.
4. Menyimpan file langsung tanpa service file handling.
5. Menentukan struktur search index langsung di controller.
6. Menjalankan transaksi database kompleks langsung di controller kecuali melalui service transaction boundary yang jelas.

## 9. Daftar Controller Resmi PERPUSQU

### 9.1 Modul Auth dan Identity

1. `App\Modules\Identity\Http\Controllers\Auth\LoginController`
2. `App\Modules\Identity\Http\Controllers\Access\UserController`
3. `App\Modules\Identity\Http\Controllers\Access\RoleController`
4. `App\Modules\Identity\Http\Controllers\Access\PermissionController`
5. `App\Modules\Identity\Http\Controllers\Access\UserRoleController`
6. `App\Modules\Profile\Http\Controllers\ProfileController`
7. `App\Modules\Profile\Http\Controllers\PasswordController`

### 9.2 Modul Core

1. `App\Modules\Core\Http\Controllers\DashboardController`
2. `App\Modules\Core\Http\Controllers\Settings\InstitutionProfileController`
3. `App\Modules\Core\Http\Controllers\Settings\OperationalRuleController`

### 9.3 Modul Master Data

1. `App\Modules\MasterData\Http\Controllers\AuthorController`
2. `App\Modules\MasterData\Http\Controllers\PublisherController`
3. `App\Modules\MasterData\Http\Controllers\LanguageController`
4. `App\Modules\MasterData\Http\Controllers\ClassificationController`
5. `App\Modules\MasterData\Http\Controllers\SubjectController`
6. `App\Modules\MasterData\Http\Controllers\CollectionTypeController`
7. `App\Modules\MasterData\Http\Controllers\RackLocationController`
8. `App\Modules\MasterData\Http\Controllers\FacultyController`
9. `App\Modules\MasterData\Http\Controllers\StudyProgramController`
10. `App\Modules\MasterData\Http\Controllers\ItemConditionController`

### 9.4 Modul Catalog

1. `App\Modules\Catalog\Http\Controllers\BibliographicRecordController`

### 9.5 Modul Collection

1. `App\Modules\Collection\Http\Controllers\PhysicalItemController`

### 9.6 Modul Member

1. `App\Modules\Member\Http\Controllers\MemberController`
2. `App\Modules\Member\Http\Controllers\MemberImportController`

### 9.7 Modul Circulation

1. `App\Modules\Circulation\Http\Controllers\LoanController`
2. `App\Modules\Circulation\Http\Controllers\ReturnController`
3. `App\Modules\Circulation\Http\Controllers\RenewalController`
4. `App\Modules\Circulation\Http\Controllers\ActiveLoanController`
5. `App\Modules\Circulation\Http\Controllers\CirculationHistoryController`
6. `App\Modules\Circulation\Http\Controllers\FineController`

### 9.8 Modul Digital Repository

1. `App\Modules\DigitalRepository\Http\Controllers\DigitalAssetController`
2. `App\Modules\DigitalRepository\Http\Controllers\AssetAccessController`

### 9.9 Modul Reporting

1. `App\Modules\Reporting\Http\Controllers\DashboardReportController`
2. `App\Modules\Reporting\Http\Controllers\CollectionReportController`
3. `App\Modules\Reporting\Http\Controllers\MemberReportController`
4. `App\Modules\Reporting\Http\Controllers\CirculationReportController`
5. `App\Modules\Reporting\Http\Controllers\FineReportController`
6. `App\Modules\Reporting\Http\Controllers\PopularCollectionReportController`
7. `App\Modules\Reporting\Http\Controllers\DigitalAccessReportController`

### 9.10 Modul Audit

1. `App\Modules\Audit\Http\Controllers\AuditLogController`
2. `App\Modules\Audit\Http\Controllers\QueueMonitorController`

### 9.11 Modul OPAC

1. `App\Modules\OPAC\Http\Controllers\HomeController`
2. `App\Modules\OPAC\Http\Controllers\SearchController`
3. `App\Modules\OPAC\Http\Controllers\RecordController`
4. `App\Modules\OPAC\Http\Controllers\AssetPreviewController`
5. `App\Modules\OPAC\Http\Controllers\StaticPageController`

## 10. Controller Map Per Modul

## 10.1 Modul Auth dan Identity

### 10.1.1 LoginController

Nama Class:
`LoginController`

Nama File:
`app/Modules/Identity/Http/Controllers/Auth/LoginController.php`

Tanggung jawab:

1. Menampilkan form login
2. Memproses autentikasi
3. Memproses logout
4. Menangani redirect pasca login dan pasca logout
5. Mencatat event login dan logout melalui service atau listener

Method publik:

1. `showLoginForm()`
2. `login(LoginRequest $request)`
3. `logout(Request $request)`

Mapping route:

1. `auth.login` -> `showLoginForm()`
2. `auth.login.attempt` -> `login()`
3. `auth.logout` -> `logout()`

View response:

1. `auth/login.blade.php`

Service yang dipanggil:

1. `AuthenticationService`
2. `AuditLogService`

Redirect standar:

1. Login sukses ke `admin.dashboard.index`
2. Logout ke `auth.login`

### 10.1.2 UserController

Nama Class:
`UserController`

Nama File:
`app/Modules/Identity/Http/Controllers/Access/UserController.php`

Tanggung jawab:

1. Menampilkan daftar user
2. Menampilkan form tambah user
3. Menyimpan user baru
4. Menampilkan detail user
5. Menampilkan form edit user
6. Memperbarui user
7. Menghapus user bila diizinkan
8. Mengaktifkan user
9. Reset password user

Method publik:

1. `index(UserIndexRequest $request)`
2. `create()`
3. `store(StoreUserRequest $request)`
4. `show(User $user)`
5. `edit(User $user)`
6. `update(UpdateUserRequest $request, User $user)`
7. `destroy(User $user)`
8. `activate(User $user)`
9. `resetPassword(ResetUserPasswordRequest $request, User $user)`

Mapping route:

1. `admin.access.users.index` -> `index()`
2. `admin.access.users.create` -> `create()`
3. `admin.access.users.store` -> `store()`
4. `admin.access.users.show` -> `show()`
5. `admin.access.users.edit` -> `edit()`
6. `admin.access.users.update` -> `update()`
7. `admin.access.users.destroy` -> `destroy()`
8. `admin.access.users.activate` -> `activate()`
9. `admin.access.users.reset_password` -> `resetPassword()`

View response:

1. `modules/access/users/index.blade.php`
2. `modules/access/users/create.blade.php`
3. `modules/access/users/show.blade.php`
4. `modules/access/users/edit.blade.php`

Service yang dipanggil:

1. `UserManagementService`
2. `AuditLogService`

### 10.1.3 RoleController

Nama Class:
`RoleController`

Nama File:
`app/Modules/Identity/Http/Controllers/Access/RoleController.php`

Tanggung jawab:

1. Menampilkan daftar role
2. Menampilkan form tambah role
3. Menyimpan role
4. Menampilkan form edit role
5. Memperbarui role
6. Menghapus role bila diizinkan

Method publik:

1. `index(RoleIndexRequest $request)`
2. `create()`
3. `store(StoreRoleRequest $request)`
4. `edit(Role $role)`
5. `update(UpdateRoleRequest $request, Role $role)`
6. `destroy(Role $role)`

Mapping route:

1. `admin.access.roles.index` -> `index()`
2. `admin.access.roles.create` -> `create()`
3. `admin.access.roles.store` -> `store()`
4. `admin.access.roles.edit` -> `edit()`
5. `admin.access.roles.update` -> `update()`
6. `admin.access.roles.destroy` -> `destroy()`

View response:

1. `modules/access/roles/index.blade.php`
2. `modules/access/roles/create.blade.php`
3. `modules/access/roles/edit.blade.php`

Service yang dipanggil:

1. `RoleManagementService`
2. `AuditLogService`

### 10.1.4 PermissionController

Nama Class:
`PermissionController`

Nama File:
`app/Modules/Identity/Http/Controllers/Access/PermissionController.php`

Tanggung jawab:

1. Menampilkan daftar permission
2. Menampilkan matriks role ke permission
3. Menyediakan data untuk update permission role

Method publik:

1. `index(PermissionIndexRequest $request)`

Mapping route:

1. `admin.access.permissions.index` -> `index()`

View response:

1. `modules/access/permissions/index.blade.php`

Service yang dipanggil:

1. `PermissionMatrixService`

### 10.1.5 UserRoleController

Nama Class:
`UserRoleController`

Nama File:
`app/Modules/Identity/Http/Controllers/Access/UserRoleController.php`

Tanggung jawab:

1. Memperbarui role pada user
2. Memperbarui permission pada role

Method publik:

1. `updateUserRoles(UpdateUserRolesRequest $request, User $user)`
2. `updateRolePermissions(UpdateRolePermissionsRequest $request, Role $role)`

Mapping route:

1. `admin.access.users.roles.update` -> `updateUserRoles()`
2. `admin.access.roles.permissions.update` -> `updateRolePermissions()`

Service yang dipanggil:

1. `UserRoleAssignmentService`
2. `RolePermissionAssignmentService`
3. `AuditLogService`

Response:

1. Redirect ke detail user atau halaman role sesuai konteks

### 10.1.6 ProfileController

Nama Class:
`ProfileController`

Nama File:
`app/Modules/Profile/Http/Controllers/ProfileController.php`

Tanggung jawab:

1. Menampilkan profil pengguna login
2. Memperbarui profil pengguna login

Method publik:

1. `show(Request $request)`
2. `update(UpdateOwnProfileRequest $request)`

Mapping route:

1. `admin.profile.show` -> `show()`
2. `admin.profile.update` -> `update()`

View response:

1. `modules/profile/show.blade.php`
2. `modules/profile/edit.blade.php` bila implementasi memisah halaman edit

Service yang dipanggil:

1. `OwnProfileService`

### 10.1.7 PasswordController

Nama Class:
`PasswordController`

Nama File:
`app/Modules/Profile/Http/Controllers/PasswordController.php`

Tanggung jawab:

1. Menampilkan form ubah password sendiri
2. Memproses perubahan password sendiri

Method publik:

1. `edit()`
2. `update(ChangeOwnPasswordRequest $request)`

Mapping route:

1. `admin.profile.password.edit` -> `edit()`
2. `admin.profile.password.update` -> `update()`

View response:

1. `modules/profile/change-password.blade.php`

Service yang dipanggil:

1. `OwnPasswordService`
2. `AuditLogService`

## 10.2 Modul Core

### 10.2.1 DashboardController

Nama Class:
`DashboardController`

Nama File:
`app/Modules/Core/Http/Controllers/DashboardController.php`

Tanggung jawab:

1. Menampilkan dashboard utama
2. Mengambil widget yang relevan sesuai role
3. Menggabungkan ringkasan operasional dari beberapa modul secara baca saja

Method publik:

1. `index(Request $request)`

Mapping route:

1. `admin.dashboard.index` -> `index()`

View response:

1. `modules/dashboard/index.blade.php`

Service yang dipanggil:

1. `DashboardWidgetService`

### 10.2.2 InstitutionProfileController

Nama Class:
`InstitutionProfileController`

Nama File:
`app/Modules/Core/Http/Controllers/Settings/InstitutionProfileController.php`

Tanggung jawab:

1. Menampilkan profil institusi
2. Memperbarui profil institusi

Method publik:

1. `edit()`
2. `update(UpdateInstitutionProfileRequest $request)`

Mapping route:

1. `admin.settings.institution_profile.edit` -> `edit()`
2. `admin.settings.institution_profile.update` -> `update()`

View response:

1. `modules/settings/institution-profile/edit.blade.php`

Service yang dipanggil:

1. `InstitutionProfileService`
2. `AuditLogService`

### 10.2.3 OperationalRuleController

Nama Class:
`OperationalRuleController`

Nama File:
`app/Modules/Core/Http/Controllers/Settings/OperationalRuleController.php`

Tanggung jawab:

1. Menampilkan aturan operasional
2. Memperbarui aturan operasional

Method publik:

1. `edit()`
2. `update(UpdateOperationalRuleRequest $request)`

Mapping route:

1. `admin.settings.operational_rules.edit` -> `edit()`
2. `admin.settings.operational_rules.update` -> `update()`

View response:

1. `modules/settings/operational-rules/edit.blade.php`

Service yang dipanggil:

1. `OperationalRuleService`
2. `AuditLogService`

## 10.3 Modul Master Data

### 10.3.1 Pola Standar Controller Master Data

Semua controller master data mengikuti pola method berikut:

1. `index()`
2. `create()`
3. `store()`
4. `edit()`
5. `update()`
6. `destroy()`

Setiap controller master data:

1. Mengembalikan halaman index, create, edit
2. Memanggil service master data masing-masing
3. Menggunakan Form Request khusus
4. Tidak menulis logic master data lintas modul

### 10.3.2 AuthorController

Nama File:
`app/Modules/MasterData/Http/Controllers/AuthorController.php`

Method publik:

1. `index(AuthorIndexRequest $request)`
2. `create()`
3. `store(StoreAuthorRequest $request)`
4. `edit(Author $author)`
5. `update(UpdateAuthorRequest $request, Author $author)`
6. `destroy(Author $author)`

Route terkait:

1. `admin.master_data.authors.index`
2. `admin.master_data.authors.create`
3. `admin.master_data.authors.store`
4. `admin.master_data.authors.edit`
5. `admin.master_data.authors.update`
6. `admin.master_data.authors.destroy`

View:

1. `modules/master-data/authors/index.blade.php`
2. `modules/master-data/authors/create.blade.php`
3. `modules/master-data/authors/edit.blade.php`

Service:

1. `AuthorService`

### 10.3.3 PublisherController

Nama File:
`app/Modules/MasterData/Http/Controllers/PublisherController.php`

Method publik:

1. `index(PublisherIndexRequest $request)`
2. `create()`
3. `store(StorePublisherRequest $request)`
4. `edit(Publisher $publisher)`
5. `update(UpdatePublisherRequest $request, Publisher $publisher)`
6. `destroy(Publisher $publisher)`

Service:

1. `PublisherService`

### 10.3.4 LanguageController

Nama File:
`app/Modules/MasterData/Http/Controllers/LanguageController.php`

Method publik:

1. `index(LanguageIndexRequest $request)`
2. `create()`
3. `store(StoreLanguageRequest $request)`
4. `edit(Language $language)`
5. `update(UpdateLanguageRequest $request, Language $language)`
6. `destroy(Language $language)`

Service:

1. `LanguageService`

### 10.3.5 ClassificationController

Nama File:
`app/Modules/MasterData/Http/Controllers/ClassificationController.php`

Method publik:

1. `index(ClassificationIndexRequest $request)`
2. `create()`
3. `store(StoreClassificationRequest $request)`
4. `edit(Classification $classification)`
5. `update(UpdateClassificationRequest $request, Classification $classification)`
6. `destroy(Classification $classification)`

Service:

1. `ClassificationService`

### 10.3.6 SubjectController

Nama File:
`app/Modules/MasterData/Http/Controllers/SubjectController.php`

Method publik:

1. `index(SubjectIndexRequest $request)`
2. `create()`
3. `store(StoreSubjectRequest $request)`
4. `edit(Subject $subject)`
5. `update(UpdateSubjectRequest $request, Subject $subject)`
6. `destroy(Subject $subject)`

Service:

1. `SubjectService`

### 10.3.7 CollectionTypeController

Nama File:
`app/Modules/MasterData/Http/Controllers/CollectionTypeController.php`

Method publik:

1. `index(CollectionTypeIndexRequest $request)`
2. `create()`
3. `store(StoreCollectionTypeRequest $request)`
4. `edit(CollectionType $collectionType)`
5. `update(UpdateCollectionTypeRequest $request, CollectionType $collectionType)`
6. `destroy(CollectionType $collectionType)`

Service:

1. `CollectionTypeService`

### 10.3.8 RackLocationController

Nama File:
`app/Modules/MasterData/Http/Controllers/RackLocationController.php`

Method publik:

1. `index(RackLocationIndexRequest $request)`
2. `create()`
3. `store(StoreRackLocationRequest $request)`
4. `edit(RackLocation $rackLocation)`
5. `update(UpdateRackLocationRequest $request, RackLocation $rackLocation)`
6. `destroy(RackLocation $rackLocation)`

Service:

1. `RackLocationService`

### 10.3.9 FacultyController

Nama File:
`app/Modules/MasterData/Http/Controllers/FacultyController.php`

Method publik:

1. `index(FacultyIndexRequest $request)`
2. `create()`
3. `store(StoreFacultyRequest $request)`
4. `edit(Faculty $faculty)`
5. `update(UpdateFacultyRequest $request, Faculty $faculty)`
6. `destroy(Faculty $faculty)`

Service:

1. `FacultyService`

### 10.3.10 StudyProgramController

Nama File:
`app/Modules/MasterData/Http/Controllers/StudyProgramController.php`

Method publik:

1. `index(StudyProgramIndexRequest $request)`
2. `create()`
3. `store(StoreStudyProgramRequest $request)`
4. `edit(StudyProgram $studyProgram)`
5. `update(UpdateStudyProgramRequest $request, StudyProgram $studyProgram)`
6. `destroy(StudyProgram $studyProgram)`

Service:

1. `StudyProgramService`

### 10.3.11 ItemConditionController

Nama File:
`app/Modules/MasterData/Http/Controllers/ItemConditionController.php`

Method publik:

1. `index(ItemConditionIndexRequest $request)`
2. `create()`
3. `store(StoreItemConditionRequest $request)`
4. `edit(ItemCondition $itemCondition)`
5. `update(UpdateItemConditionRequest $request, ItemCondition $itemCondition)`
6. `destroy(ItemCondition $itemCondition)`

Service:

1. `ItemConditionService`

## 10.4 Modul Catalog

### 10.4.1 BibliographicRecordController

Nama Class:
`BibliographicRecordController`

Nama File:
`app/Modules/Catalog/Http/Controllers/BibliographicRecordController.php`

Tanggung jawab:

1. Menampilkan daftar bibliographic record
2. Menampilkan form tambah record
3. Menyimpan bibliographic record baru
4. Menampilkan detail bibliographic record
5. Menampilkan form edit bibliographic record
6. Memperbarui bibliographic record
7. Menghapus bibliographic record sesuai kebijakan
8. Publish record
9. Unpublish record

Method publik:

1. `index(BibliographicRecordIndexRequest $request)`
2. `create()`
3. `store(StoreBibliographicRecordRequest $request)`
4. `show(BibliographicRecord $record)`
5. `edit(BibliographicRecord $record)`
6. `update(UpdateBibliographicRecordRequest $request, BibliographicRecord $record)`
7. `destroy(BibliographicRecord $record)`
8. `publish(BibliographicRecord $record)`
9. `unpublish(BibliographicRecord $record)`

Mapping route:

1. `admin.catalog.records.index` -> `index()`
2. `admin.catalog.records.create` -> `create()`
3. `admin.catalog.records.store` -> `store()`
4. `admin.catalog.records.show` -> `show()`
5. `admin.catalog.records.edit` -> `edit()`
6. `admin.catalog.records.update` -> `update()`
7. `admin.catalog.records.destroy` -> `destroy()`
8. `admin.catalog.records.publish` -> `publish()`
9. `admin.catalog.records.unpublish` -> `unpublish()`

View response:

1. `modules/catalog/records/index.blade.php`
2. `modules/catalog/records/create.blade.php`
3. `modules/catalog/records/show.blade.php`
4. `modules/catalog/records/edit.blade.php`

Service yang dipanggil:

1. `BibliographicRecordService`
2. `CatalogPublicationService`
3. `SearchIndexService`
4. `AuditLogService`

Catatan:

1. Method `store()` dan `update()` tidak boleh langsung mengelola relasi kompleks tanpa service.
2. Sinkronisasi search index dilakukan melalui service atau job, bukan manual di controller.

## 10.5 Modul Collection

### 10.5.1 PhysicalItemController

Nama Class:
`PhysicalItemController`

Nama File:
`app/Modules/Collection/Http/Controllers/PhysicalItemController.php`

Tanggung jawab:

1. Menampilkan daftar item fisik
2. Menampilkan form tambah item
3. Menyimpan item
4. Menampilkan detail item
5. Menampilkan form edit item
6. Memperbarui item
7. Menghapus item sesuai kebijakan
8. Mengubah status item
9. Menampilkan histori item

Method publik:

1. `index(PhysicalItemIndexRequest $request)`
2. `create()`
3. `store(StorePhysicalItemRequest $request)`
4. `show(PhysicalItem $item)`
5. `edit(PhysicalItem $item)`
6. `update(UpdatePhysicalItemRequest $request, PhysicalItem $item)`
7. `destroy(PhysicalItem $item)`
8. `changeStatus(ChangePhysicalItemStatusRequest $request, PhysicalItem $item)`
9. `history(PhysicalItem $item, PhysicalItemHistoryRequest $request)`

Mapping route:

1. `admin.collections.items.index` -> `index()`
2. `admin.collections.items.create` -> `create()`
3. `admin.collections.items.store` -> `store()`
4. `admin.collections.items.show` -> `show()`
5. `admin.collections.items.edit` -> `edit()`
6. `admin.collections.items.update` -> `update()`
7. `admin.collections.items.destroy` -> `destroy()`
8. `admin.collections.items.change_status` -> `changeStatus()`
9. `admin.collections.items.history` -> `history()`

View response:

1. `modules/collections/items/index.blade.php`
2. `modules/collections/items/create.blade.php`
3. `modules/collections/items/show.blade.php`
4. `modules/collections/items/edit.blade.php`
5. `modules/collections/items/history.blade.php`

Service yang dipanggil:

1. `PhysicalItemService`
2. `PhysicalItemStatusService`
3. `PhysicalItemHistoryService`
4. `AuditLogService`

## 10.6 Modul Member

### 10.6.1 MemberController

Nama Class:
`MemberController`

Nama File:
`app/Modules/Member/Http/Controllers/MemberController.php`

Tanggung jawab:

1. Menampilkan daftar anggota
2. Menampilkan form tambah anggota
3. Menyimpan anggota
4. Menampilkan detail anggota
5. Menampilkan form edit anggota
6. Memperbarui anggota
7. Menghapus anggota sesuai kebijakan
8. Mengaktifkan anggota
9. Menonaktifkan anggota
10. Memblokir anggota
11. Membuka blokir anggota
12. Menampilkan histori anggota

Method publik:

1. `index(MemberIndexRequest $request)`
2. `create()`
3. `store(StoreMemberRequest $request)`
4. `show(Member $member)`
5. `edit(Member $member)`
6. `update(UpdateMemberRequest $request, Member $member)`
7. `destroy(Member $member)`
8. `activate(Member $member)`
9. `deactivate(Member $member)`
10. `block(BlockMemberRequest $request, Member $member)`
11. `unblock(Member $member)`
12. `history(Member $member, MemberHistoryRequest $request)`

Mapping route:

1. `admin.members.index` -> `index()`
2. `admin.members.create` -> `create()`
3. `admin.members.store` -> `store()`
4. `admin.members.show` -> `show()`
5. `admin.members.edit` -> `edit()`
6. `admin.members.update` -> `update()`
7. `admin.members.destroy` -> `destroy()`
8. `admin.members.activate` -> `activate()`
9. `admin.members.deactivate` -> `deactivate()`
10. `admin.members.block` -> `block()`
11. `admin.members.unblock` -> `unblock()`
12. `admin.members.history` -> `history()`

View response:

1. `modules/members/index.blade.php`
2. `modules/members/create.blade.php`
3. `modules/members/show.blade.php`
4. `modules/members/edit.blade.php`
5. `modules/members/history.blade.php`

Service yang dipanggil:

1. `MemberService`
2. `MemberStatusService`
3. `MemberBlockingService`
4. `MemberHistoryService`
5. `AuditLogService`

### 10.6.2 MemberImportController

Nama Class:
`MemberImportController`

Nama File:
`app/Modules/Member/Http/Controllers/MemberImportController.php`

Status:
Tidak wajib aktif pada go live fase 1, tetapi tetap dicatat sebagai rancangan terkontrol.

Method publik:

1. `create()`
2. `store(MemberImportRequest $request)`

Mapping route:

1. `admin.members.import.form` -> `create()`
2. `admin.members.import.store` -> `store()`

View response:

1. `modules/members/import.blade.php` bila fitur diaktifkan

Service:

1. `MemberImportService`

## 10.7 Modul Circulation

### 10.7.1 LoanController

Nama Class:
`LoanController`

Nama File:
`app/Modules/Circulation/Http/Controllers/LoanController.php`

Tanggung jawab:

1. Menampilkan halaman peminjaman
2. Memproses peminjaman
3. Menampilkan detail pinjaman
4. Memproses perpanjangan pinjaman

Method publik:

1. `create()`
2. `store(StoreLoanRequest $request)`
3. `show(Loan $loan)`
4. `renew(RenewLoanRequest $request, Loan $loan)`

Mapping route:

1. `admin.circulation.loans.create` -> `create()`
2. `admin.circulation.loans.store` -> `store()`
3. `admin.circulation.loans.show` -> `show()`
4. `admin.circulation.loans.renew` -> `renew()`

View response:

1. `modules/circulation/loans/create.blade.php`
2. `modules/circulation/loans/show.blade.php`

Service yang dipanggil:

1. `LoanTransactionService`
2. `RenewalService`
3. `AuditLogService`

### 10.7.2 ReturnController

Nama Class:
`ReturnController`

Nama File:
`app/Modules/Circulation/Http/Controllers/ReturnController.php`

Tanggung jawab:

1. Menampilkan halaman pengembalian
2. Memproses pengembalian item

Method publik:

1. `create()`
2. `store(StoreReturnRequest $request)`

Mapping route:

1. `admin.circulation.returns.create` -> `create()`
2. `admin.circulation.returns.store` -> `store()`

View response:

1. `modules/circulation/returns/create.blade.php`

Service yang dipanggil:

1. `ReturnProcessingService`
2. `FineCalculationService`
3. `AuditLogService`

### 10.7.3 RenewalController

Nama Class:
`RenewalController`

Nama File:
`app/Modules/Circulation/Http/Controllers/RenewalController.php`

Tanggung jawab:

1. Menampilkan daftar pinjaman yang dapat diperpanjang

Method publik:

1. `index(RenewalIndexRequest $request)`

Mapping route:

1. `admin.circulation.renewals.index` -> `index()`

View response:

1. `modules/circulation/renewals/index.blade.php`

Service yang dipanggil:

1. `RenewalListService`

### 10.7.4 ActiveLoanController

Nama Class:
`ActiveLoanController`

Nama File:
`app/Modules/Circulation/Http/Controllers/ActiveLoanController.php`

Tanggung jawab:

1. Menampilkan daftar pinjaman aktif
2. Menyediakan filter pinjaman aktif

Method publik:

1. `index(ActiveLoanIndexRequest $request)`

Mapping route:

1. `admin.circulation.active_loans.index` -> `index()`

View response:

1. `modules/circulation/active-loans/index.blade.php`

Service yang dipanggil:

1. `ActiveLoanService`

### 10.7.5 CirculationHistoryController

Nama Class:
`CirculationHistoryController`

Nama File:
`app/Modules/Circulation/Http/Controllers/CirculationHistoryController.php`

Tanggung jawab:

1. Menampilkan histori sirkulasi

Method publik:

1. `index(CirculationHistoryRequest $request)`

Mapping route:

1. `admin.circulation.history.index` -> `index()`

View response:

1. `modules/circulation/history/index.blade.php`

Service yang dipanggil:

1. `CirculationHistoryService`

### 10.7.6 FineController

Nama Class:
`FineController`

Nama File:
`app/Modules/Circulation/Http/Controllers/FineController.php`

Tanggung jawab:

1. Menampilkan data denda dan keterlambatan operasional

Method publik:

1. `index(FineIndexRequest $request)`

Mapping route:

1. `admin.circulation.fines.index` -> `index()`

View response:

1. `modules/circulation/fines/index.blade.php`

Service yang dipanggil:

1. `FineReportingService`

## 10.8 Modul Digital Repository

### 10.8.1 DigitalAssetController

Nama Class:
`DigitalAssetController`

Nama File:
`app/Modules/DigitalRepository/Http/Controllers/DigitalAssetController.php`

Tanggung jawab:

1. Menampilkan daftar aset digital
2. Menampilkan form unggah aset digital
3. Menyimpan aset digital baru
4. Menampilkan detail aset digital
5. Menampilkan form edit aset digital
6. Memperbarui aset digital
7. Menghapus aset digital sesuai kebijakan
8. Preview aset digital internal
9. Publish aset digital
10. Unpublish aset digital
11. Memperbarui aturan akses aset digital
12. Menjalankan OCR
13. Menjalankan reindex

Method publik:

1. `index(DigitalAssetIndexRequest $request)`
2. `create()`
3. `store(StoreDigitalAssetRequest $request)`
4. `show(DigitalAsset $asset)`
5. `edit(DigitalAsset $asset)`
6. `update(UpdateDigitalAssetRequest $request, DigitalAsset $asset)`
7. `destroy(DigitalAsset $asset)`
8. `preview(DigitalAsset $asset)`
9. `publish(DigitalAsset $asset)`
10. `unpublish(DigitalAsset $asset)`
11. `updateAccess(UpdateDigitalAssetAccessRequest $request, DigitalAsset $asset)`
12. `runOcr(DigitalAsset $asset)`
13. `reindex(DigitalAsset $asset)`

Mapping route:

1. `admin.digital.assets.index` -> `index()`
2. `admin.digital.assets.create` -> `create()`
3. `admin.digital.assets.store` -> `store()`
4. `admin.digital.assets.show` -> `show()`
5. `admin.digital.assets.edit` -> `edit()`
6. `admin.digital.assets.update` -> `update()`
7. `admin.digital.assets.destroy` -> `destroy()`
8. `admin.digital.assets.preview` -> `preview()`
9. `admin.digital.assets.publish` -> `publish()`
10. `admin.digital.assets.unpublish` -> `unpublish()`
11. `admin.digital.assets.access.update` -> `updateAccess()`
12. `admin.digital.assets.ocr.run` -> `runOcr()`
13. `admin.digital.assets.reindex` -> `reindex()`

View response:

1. `modules/digital-repository/assets/index.blade.php`
2. `modules/digital-repository/assets/create.blade.php`
3. `modules/digital-repository/assets/show.blade.php`
4. `modules/digital-repository/assets/edit.blade.php`

Service yang dipanggil:

1. `DigitalAssetService`
2. `DigitalAssetUploadService`
3. `DigitalAssetAccessService`
4. `OcrProcessingService`
5. `SearchIndexService`
6. `AuditLogService`

### 10.8.2 AssetAccessController

Nama Class:
`AssetAccessController`

Nama File:
`app/Modules/DigitalRepository/Http/Controllers/AssetAccessController.php`

Tanggung jawab:

1. Menyajikan stream file privat internal
2. Menyediakan unduh privat setelah lolos pemeriksaan akses
3. Menyajikan preview publik fallback bila dibutuhkan

Method publik:

1. `publicPreview(DigitalAsset $asset)`
2. `privateShow(DigitalAsset $asset)`
3. `privateDownload(DigitalAsset $asset)`

Mapping route:

1. `assets.public.preview` -> `publicPreview()`
2. `assets.private.show` -> `privateShow()`
3. `assets.private.download` -> `privateDownload()`

Service yang dipanggil:

1. `DigitalAssetAccessService`
2. `AssetStreamingService`
3. `AuditLogService` bila pencatatan akses diaktifkan

Response:

1. Stream response
2. File response
3. Preview response terproteksi

## 10.9 Modul Reporting

### 10.9.1 DashboardReportController

Nama Class:
`DashboardReportController`

Nama File:
`app/Modules/Reporting/Http/Controllers/DashboardReportController.php`

Method publik:

1. `index(ReportDashboardRequest $request)`

Mapping route:

1. `admin.reports.dashboard` -> `index()`

View:

1. `modules/reports/dashboard.blade.php`

Service:

1. `ReportingDashboardService`

### 10.9.2 CollectionReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/CollectionReportController.php`

Method publik:

1. `index(CollectionReportRequest $request)`
2. `export(CollectionReportExportRequest $request)`

Route:

1. `admin.reports.collections.index`
2. `admin.reports.collections.export`

View:

1. `modules/reports/collections/index.blade.php`

Service:

1. `CollectionReportService`
2. `ReportExportService`

### 10.9.3 MemberReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/MemberReportController.php`

Method publik:

1. `index(MemberReportRequest $request)`
2. `export(MemberReportExportRequest $request)`

Route:

1. `admin.reports.members.index`
2. `admin.reports.members.export`

View:

1. `modules/reports/members/index.blade.php`

Service:

1. `MemberReportService`
2. `ReportExportService`

### 10.9.4 CirculationReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/CirculationReportController.php`

Method publik:

1. `index(CirculationReportRequest $request)`
2. `export(CirculationReportExportRequest $request)`

Route:

1. `admin.reports.circulation.index`
2. `admin.reports.circulation.export`

View:

1. `modules/reports/circulation/index.blade.php`

Service:

1. `CirculationReportService`
2. `ReportExportService`

### 10.9.5 FineReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/FineReportController.php`

Method publik:

1. `index(FineReportRequest $request)`
2. `export(FineReportExportRequest $request)`

Route:

1. `admin.reports.fines.index`
2. `admin.reports.fines.export`

View:

1. `modules/reports/fines/index.blade.php`

Service:

1. `FineReportService`
2. `ReportExportService`

### 10.9.6 PopularCollectionReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/PopularCollectionReportController.php`

Method publik:

1. `index(PopularCollectionReportRequest $request)`
2. `export(PopularCollectionReportExportRequest $request)`

Route:

1. `admin.reports.popular_collections.index`
2. `admin.reports.popular_collections.export`

View:

1. `modules/reports/popular-collections/index.blade.php`

Service:

1. `PopularCollectionReportService`
2. `ReportExportService`

### 10.9.7 DigitalAccessReportController

Nama File:
`app/Modules/Reporting/Http/Controllers/DigitalAccessReportController.php`

Method publik:

1. `index(DigitalAccessReportRequest $request)`
2. `export(DigitalAccessReportExportRequest $request)`

Route:

1. `admin.reports.digital_access.index`
2. `admin.reports.digital_access.export`

View:

1. `modules/reports/digital-access/index.blade.php`

Service:

1. `DigitalAccessReportService`
2. `ReportExportService`

## 10.10 Modul Audit

### 10.10.1 AuditLogController

Nama Class:
`AuditLogController`

Nama File:
`app/Modules/Audit/Http/Controllers/AuditLogController.php`

Tanggung jawab:

1. Menampilkan daftar audit log
2. Menampilkan detail audit log

Method publik:

1. `index(AuditLogIndexRequest $request)`
2. `show(ActivityLog $log)`

Mapping route:

1. `admin.audit.logs.index` -> `index()`
2. `admin.audit.logs.show` -> `show()`

View:

1. `modules/audit/logs/index.blade.php`
2. `modules/audit/logs/show.blade.php`

Service:

1. `AuditLogQueryService`

### 10.10.2 QueueMonitorController

Nama Class:
`QueueMonitorController`

Nama File:
`app/Modules/Audit/Http/Controllers/QueueMonitorController.php`

Tanggung jawab:

1. Menampilkan monitoring queue dasar
2. Menjalankan retry job bila diizinkan

Method publik:

1. `index(QueueMonitorRequest $request)`
2. `retry(QueueRetryRequest $request, string $job)`

Mapping route:

1. `admin.audit.queue_monitor.index` -> `index()`
2. `admin.audit.queue_monitor.retry` -> `retry()`

View:

1. `modules/audit/queue-monitor/index.blade.php`

Service:

1. `QueueMonitorService`

## 10.11 Modul OPAC

### 10.11.1 HomeController

Nama Class:
`HomeController`

Nama File:
`app/Modules/OPAC/Http/Controllers/HomeController.php`

Tanggung jawab:

1. Menampilkan beranda OPAC
2. Menyediakan search box utama dan data publik ringan

Method publik:

1. `index(Request $request)`

Mapping route:

1. `opac.home` -> `index()`

View:

1. `modules/opac/home.blade.php`

Service:

1. `OpacHomeService`

### 10.11.2 SearchController

Nama Class:
`SearchController`

Nama File:
`app/Modules/OPAC/Http/Controllers/SearchController.php`

Tanggung jawab:

1. Memproses pencarian OPAC
2. Memproses filter pencarian
3. Menampilkan hasil pencarian

Method publik:

1. `index(OpacSearchRequest $request)`

Mapping route:

1. `opac.search.index` -> `index()`

View:

1. `modules/opac/search/index.blade.php`

Service:

1. `CatalogSearchService`

### 10.11.3 RecordController

Nama Class:
`RecordController`

Nama File:
`app/Modules/OPAC/Http/Controllers/RecordController.php`

Tanggung jawab:

1. Menampilkan detail koleksi publik
2. Menampilkan ketersediaan item fisik
3. Menampilkan informasi aset digital publik

Method publik:

1. `show(BibliographicRecord $record)`

Mapping route:

1. `opac.records.show` -> `show()`

View:

1. `modules/opac/records/show.blade.php`

Service:

1. `OpacRecordDetailService`

### 10.11.4 AssetPreviewController

Nama Class:
`AssetPreviewController`

Nama File:
`app/Modules/OPAC/Http/Controllers/AssetPreviewController.php`

Tanggung jawab:

1. Menyajikan preview aset publik dari OPAC

Method publik:

1. `show(DigitalAsset $asset)`

Mapping route:

1. `opac.assets.preview` -> `show()`

View:

1. `modules/opac/assets/preview.blade.php`

Service:

1. `DigitalAssetAccessService`

### 10.11.5 StaticPageController

Nama Class:
`StaticPageController`

Nama File:
`app/Modules/OPAC/Http/Controllers/StaticPageController.php`

Tanggung jawab:

1. Menampilkan halaman tentang perpustakaan
2. Menampilkan halaman bantuan pencarian

Method publik:

1. `about()`
2. `help()`

Mapping route:

1. `opac.about` -> `about()`
2. `opac.help` -> `help()`

View:

1. `modules/opac/about.blade.php`
2. `modules/opac/help.blade.php`

## 11. Matriks Route ke Controller Method

| Nama Route                                | Controller                        | Method                |
| ----------------------------------------- | --------------------------------- | --------------------- |
| auth.login                                | LoginController                   | showLoginForm         |
| auth.login.attempt                        | LoginController                   | login                 |
| auth.logout                               | LoginController                   | logout                |
| admin.dashboard.index                     | DashboardController               | index                 |
| admin.settings.institution_profile.edit   | InstitutionProfileController      | edit                  |
| admin.settings.institution_profile.update | InstitutionProfileController      | update                |
| admin.settings.operational_rules.edit     | OperationalRuleController         | edit                  |
| admin.settings.operational_rules.update   | OperationalRuleController         | update                |
| admin.access.users.index                  | UserController                    | index                 |
| admin.access.users.create                 | UserController                    | create                |
| admin.access.users.store                  | UserController                    | store                 |
| admin.access.users.show                   | UserController                    | show                  |
| admin.access.users.edit                   | UserController                    | edit                  |
| admin.access.users.update                 | UserController                    | update                |
| admin.access.users.destroy                | UserController                    | destroy               |
| admin.access.users.activate               | UserController                    | activate              |
| admin.access.users.reset_password         | UserController                    | resetPassword         |
| admin.access.roles.index                  | RoleController                    | index                 |
| admin.access.roles.create                 | RoleController                    | create                |
| admin.access.roles.store                  | RoleController                    | store                 |
| admin.access.roles.edit                   | RoleController                    | edit                  |
| admin.access.roles.update                 | RoleController                    | update                |
| admin.access.roles.destroy                | RoleController                    | destroy               |
| admin.access.permissions.index            | PermissionController              | index                 |
| admin.access.roles.permissions.update     | UserRoleController                | updateRolePermissions |
| admin.access.users.roles.update           | UserRoleController                | updateUserRoles       |
| admin.profile.show                        | ProfileController                 | show                  |
| admin.profile.update                      | ProfileController                 | update                |
| admin.profile.password.edit               | PasswordController                | edit                  |
| admin.profile.password.update             | PasswordController                | update                |
| admin.catalog.records.index               | BibliographicRecordController     | index                 |
| admin.catalog.records.create              | BibliographicRecordController     | create                |
| admin.catalog.records.store               | BibliographicRecordController     | store                 |
| admin.catalog.records.show                | BibliographicRecordController     | show                  |
| admin.catalog.records.edit                | BibliographicRecordController     | edit                  |
| admin.catalog.records.update              | BibliographicRecordController     | update                |
| admin.catalog.records.destroy             | BibliographicRecordController     | destroy               |
| admin.catalog.records.publish             | BibliographicRecordController     | publish               |
| admin.catalog.records.unpublish           | BibliographicRecordController     | unpublish             |
| admin.collections.items.index             | PhysicalItemController            | index                 |
| admin.collections.items.create            | PhysicalItemController            | create                |
| admin.collections.items.store             | PhysicalItemController            | store                 |
| admin.collections.items.show              | PhysicalItemController            | show                  |
| admin.collections.items.edit              | PhysicalItemController            | edit                  |
| admin.collections.items.update            | PhysicalItemController            | update                |
| admin.collections.items.destroy           | PhysicalItemController            | destroy               |
| admin.collections.items.change_status     | PhysicalItemController            | changeStatus          |
| admin.collections.items.history           | PhysicalItemController            | history               |
| admin.members.index                       | MemberController                  | index                 |
| admin.members.create                      | MemberController                  | create                |
| admin.members.store                       | MemberController                  | store                 |
| admin.members.show                        | MemberController                  | show                  |
| admin.members.edit                        | MemberController                  | edit                  |
| admin.members.update                      | MemberController                  | update                |
| admin.members.destroy                     | MemberController                  | destroy               |
| admin.members.activate                    | MemberController                  | activate              |
| admin.members.deactivate                  | MemberController                  | deactivate            |
| admin.members.block                       | MemberController                  | block                 |
| admin.members.unblock                     | MemberController                  | unblock               |
| admin.members.history                     | MemberController                  | history               |
| admin.members.import.form                 | MemberImportController            | create                |
| admin.members.import.store                | MemberImportController            | store                 |
| admin.circulation.loans.create            | LoanController                    | create                |
| admin.circulation.loans.store             | LoanController                    | store                 |
| admin.circulation.loans.show              | LoanController                    | show                  |
| admin.circulation.loans.renew             | LoanController                    | renew                 |
| admin.circulation.returns.create          | ReturnController                  | create                |
| admin.circulation.returns.store           | ReturnController                  | store                 |
| admin.circulation.renewals.index          | RenewalController                 | index                 |
| admin.circulation.active_loans.index      | ActiveLoanController              | index                 |
| admin.circulation.history.index           | CirculationHistoryController      | index                 |
| admin.circulation.fines.index             | FineController                    | index                 |
| admin.digital.assets.index                | DigitalAssetController            | index                 |
| admin.digital.assets.create               | DigitalAssetController            | create                |
| admin.digital.assets.store                | DigitalAssetController            | store                 |
| admin.digital.assets.show                 | DigitalAssetController            | show                  |
| admin.digital.assets.edit                 | DigitalAssetController            | edit                  |
| admin.digital.assets.update               | DigitalAssetController            | update                |
| admin.digital.assets.destroy              | DigitalAssetController            | destroy               |
| admin.digital.assets.preview              | DigitalAssetController            | preview               |
| admin.digital.assets.publish              | DigitalAssetController            | publish               |
| admin.digital.assets.unpublish            | DigitalAssetController            | unpublish             |
| admin.digital.assets.access.update        | DigitalAssetController            | updateAccess          |
| admin.digital.assets.ocr.run              | DigitalAssetController            | runOcr                |
| admin.digital.assets.reindex              | DigitalAssetController            | reindex               |
| admin.digital.assets.download             | AssetAccessController             | privateDownload       |
| admin.reports.dashboard                   | DashboardReportController         | index                 |
| admin.reports.collections.index           | CollectionReportController        | index                 |
| admin.reports.collections.export          | CollectionReportController        | export                |
| admin.reports.members.index               | MemberReportController            | index                 |
| admin.reports.members.export              | MemberReportController            | export                |
| admin.reports.circulation.index           | CirculationReportController       | index                 |
| admin.reports.circulation.export          | CirculationReportController       | export                |
| admin.reports.fines.index                 | FineReportController              | index                 |
| admin.reports.fines.export                | FineReportController              | export                |
| admin.reports.popular_collections.index   | PopularCollectionReportController | index                 |
| admin.reports.popular_collections.export  | PopularCollectionReportController | export                |
| admin.reports.digital_access.index        | DigitalAccessReportController     | index                 |
| admin.reports.digital_access.export       | DigitalAccessReportController     | export                |
| admin.audit.logs.index                    | AuditLogController                | index                 |
| admin.audit.logs.show                     | AuditLogController                | show                  |
| admin.audit.queue_monitor.index           | QueueMonitorController            | index                 |
| admin.audit.queue_monitor.retry           | QueueMonitorController            | retry                 |
| opac.home                                 | HomeController                    | index                 |
| opac.search.index                         | SearchController                  | index                 |
| opac.records.show                         | RecordController                  | show                  |
| opac.assets.preview                       | AssetPreviewController            | show                  |
| opac.about                                | StaticPageController              | about                 |
| opac.help                                 | StaticPageController              | help                  |
| assets.public.preview                     | AssetAccessController             | publicPreview         |
| assets.private.show                       | AssetAccessController             | privateShow           |
| assets.private.download                   | AssetAccessController             | privateDownload       |

## 12. Matriks Controller ke View

| Controller                        | Method        | View Utama                                          |
| --------------------------------- | ------------- | --------------------------------------------------- |
| LoginController                   | showLoginForm | auth/login.blade.php                                |
| DashboardController               | index         | modules/dashboard/index.blade.php                   |
| UserController                    | index         | modules/access/users/index.blade.php                |
| UserController                    | create        | modules/access/users/create.blade.php               |
| UserController                    | show          | modules/access/users/show.blade.php                 |
| UserController                    | edit          | modules/access/users/edit.blade.php                 |
| RoleController                    | index         | modules/access/roles/index.blade.php                |
| RoleController                    | create        | modules/access/roles/create.blade.php               |
| RoleController                    | edit          | modules/access/roles/edit.blade.php                 |
| PermissionController              | index         | modules/access/permissions/index.blade.php          |
| ProfileController                 | show          | modules/profile/show.blade.php                      |
| PasswordController                | edit          | modules/profile/change-password.blade.php           |
| InstitutionProfileController      | edit          | modules/settings/institution-profile/edit.blade.php |
| OperationalRuleController         | edit          | modules/settings/operational-rules/edit.blade.php   |
| AuthorController                  | index         | modules/master-data/authors/index.blade.php         |
| AuthorController                  | create        | modules/master-data/authors/create.blade.php        |
| AuthorController                  | edit          | modules/master-data/authors/edit.blade.php          |
| BibliographicRecordController     | index         | modules/catalog/records/index.blade.php             |
| BibliographicRecordController     | create        | modules/catalog/records/create.blade.php            |
| BibliographicRecordController     | show          | modules/catalog/records/show.blade.php              |
| BibliographicRecordController     | edit          | modules/catalog/records/edit.blade.php              |
| PhysicalItemController            | index         | modules/collections/items/index.blade.php           |
| PhysicalItemController            | create        | modules/collections/items/create.blade.php          |
| PhysicalItemController            | show          | modules/collections/items/show.blade.php            |
| PhysicalItemController            | edit          | modules/collections/items/edit.blade.php            |
| PhysicalItemController            | history       | modules/collections/items/history.blade.php         |
| MemberController                  | index         | modules/members/index.blade.php                     |
| MemberController                  | create        | modules/members/create.blade.php                    |
| MemberController                  | show          | modules/members/show.blade.php                      |
| MemberController                  | edit          | modules/members/edit.blade.php                      |
| MemberController                  | history       | modules/members/history.blade.php                   |
| LoanController                    | create        | modules/circulation/loans/create.blade.php          |
| LoanController                    | show          | modules/circulation/loans/show.blade.php            |
| ReturnController                  | create        | modules/circulation/returns/create.blade.php        |
| RenewalController                 | index         | modules/circulation/renewals/index.blade.php        |
| ActiveLoanController              | index         | modules/circulation/active-loans/index.blade.php    |
| CirculationHistoryController      | index         | modules/circulation/history/index.blade.php         |
| FineController                    | index         | modules/circulation/fines/index.blade.php           |
| DigitalAssetController            | index         | modules/digital-repository/assets/index.blade.php   |
| DigitalAssetController            | create        | modules/digital-repository/assets/create.blade.php  |
| DigitalAssetController            | show          | modules/digital-repository/assets/show.blade.php    |
| DigitalAssetController            | edit          | modules/digital-repository/assets/edit.blade.php    |
| DashboardReportController         | index         | modules/reports/dashboard.blade.php                 |
| CollectionReportController        | index         | modules/reports/collections/index.blade.php         |
| MemberReportController            | index         | modules/reports/members/index.blade.php             |
| CirculationReportController       | index         | modules/reports/circulation/index.blade.php         |
| FineReportController              | index         | modules/reports/fines/index.blade.php               |
| PopularCollectionReportController | index         | modules/reports/popular-collections/index.blade.php |
| DigitalAccessReportController     | index         | modules/reports/digital-access/index.blade.php      |
| AuditLogController                | index         | modules/audit/logs/index.blade.php                  |
| AuditLogController                | show          | modules/audit/logs/show.blade.php                   |
| QueueMonitorController            | index         | modules/audit/queue-monitor/index.blade.php         |
| HomeController                    | index         | modules/opac/home.blade.php                         |
| SearchController                  | index         | modules/opac/search/index.blade.php                 |
| RecordController                  | show          | modules/opac/records/show.blade.php                 |
| AssetPreviewController            | show          | modules/opac/assets/preview.blade.php               |
| StaticPageController              | about         | modules/opac/about.blade.php                        |
| StaticPageController              | help          | modules/opac/help.blade.php                         |

## 13. Matriks Controller ke Service Rekomendasi

| Controller                        | Service Utama                                                                                                       |
| --------------------------------- | ------------------------------------------------------------------------------------------------------------------- |
| LoginController                   | AuthenticationService                                                                                               |
| UserController                    | UserManagementService                                                                                               |
| RoleController                    | RoleManagementService                                                                                               |
| PermissionController              | PermissionMatrixService                                                                                             |
| UserRoleController                | UserRoleAssignmentService, RolePermissionAssignmentService                                                          |
| ProfileController                 | OwnProfileService                                                                                                   |
| PasswordController                | OwnPasswordService                                                                                                  |
| DashboardController               | DashboardWidgetService                                                                                              |
| InstitutionProfileController      | InstitutionProfileService                                                                                           |
| OperationalRuleController         | OperationalRuleService                                                                                              |
| AuthorController                  | AuthorService                                                                                                       |
| PublisherController               | PublisherService                                                                                                    |
| LanguageController                | LanguageService                                                                                                     |
| ClassificationController          | ClassificationService                                                                                               |
| SubjectController                 | SubjectService                                                                                                      |
| CollectionTypeController          | CollectionTypeService                                                                                               |
| RackLocationController            | RackLocationService                                                                                                 |
| FacultyController                 | FacultyService                                                                                                      |
| StudyProgramController            | StudyProgramService                                                                                                 |
| ItemConditionController           | ItemConditionService                                                                                                |
| BibliographicRecordController     | BibliographicRecordService, CatalogPublicationService, SearchIndexService                                           |
| PhysicalItemController            | PhysicalItemService, PhysicalItemStatusService, PhysicalItemHistoryService                                          |
| MemberController                  | MemberService, MemberStatusService, MemberBlockingService, MemberHistoryService                                     |
| MemberImportController            | MemberImportService                                                                                                 |
| LoanController                    | LoanTransactionService, RenewalService                                                                              |
| ReturnController                  | ReturnProcessingService, FineCalculationService                                                                     |
| RenewalController                 | RenewalListService                                                                                                  |
| ActiveLoanController              | ActiveLoanService                                                                                                   |
| CirculationHistoryController      | CirculationHistoryService                                                                                           |
| FineController                    | FineReportingService                                                                                                |
| DigitalAssetController            | DigitalAssetService, DigitalAssetUploadService, DigitalAssetAccessService, OcrProcessingService, SearchIndexService |
| AssetAccessController             | DigitalAssetAccessService, AssetStreamingService                                                                    |
| DashboardReportController         | ReportingDashboardService                                                                                           |
| CollectionReportController        | CollectionReportService, ReportExportService                                                                        |
| MemberReportController            | MemberReportService, ReportExportService                                                                            |
| CirculationReportController       | CirculationReportService, ReportExportService                                                                       |
| FineReportController              | FineReportService, ReportExportService                                                                              |
| PopularCollectionReportController | PopularCollectionReportService, ReportExportService                                                                 |
| DigitalAccessReportController     | DigitalAccessReportService, ReportExportService                                                                     |
| AuditLogController                | AuditLogQueryService                                                                                                |
| QueueMonitorController            | QueueMonitorService                                                                                                 |
| HomeController                    | OpacHomeService                                                                                                     |
| SearchController                  | CatalogSearchService                                                                                                |
| RecordController                  | OpacRecordDetailService                                                                                             |
| AssetPreviewController            | DigitalAssetAccessService                                                                                           |
| StaticPageController              | StaticPageContentService                                                                                            |

## 14. Aturan Response Controller

### 14.1 Response GET

1. Method GET halaman harus mengembalikan view.
2. Data view harus sudah siap pakai.
3. Data select option dapat disiapkan melalui service atau presenter ringan.

### 14.2 Response POST, PUT, PATCH, DELETE

1. Harus redirect ke route yang relevan.
2. Harus membawa flash message sukses atau gagal.
3. Harus menjaga input lama bila validasi gagal.
4. Tidak boleh mengembalikan HTML mentah acak pada form standar admin.

### 14.3 Response Preview dan File

1. Preview file dapat berupa view dengan embedded PDF.js atau stream response.
2. File privat harus selalu melalui pemeriksaan akses.
3. Response file tidak boleh membocorkan path storage internal.

## 15. Aturan Authorization pada Controller

Setiap controller harus mematuhi aturan berikut:

1. Route middleware permission adalah lapis pertama.
2. Policy model adalah lapis kedua untuk resource sensitif.
3. Controller wajib memanggil authorize atau policy bila aksi membutuhkan verifikasi objek tertentu.
4. Akses file digital privat wajib melewati pemeriksaan rule akses tambahan.
5. Akses laporan ekspor wajib memeriksa permission export terpisah.

Model yang wajib memiliki policy minimal:

1. User
2. BibliographicRecord
3. PhysicalItem
4. Member
5. Loan
6. DigitalAsset
7. ActivityLog

## 16. Aturan Validation pada Controller

Aturan validation resmi:

1. Controller tidak boleh memvalidasi field kompleks langsung di method dengan array panjang bila sudah ada Form Request.
2. Semua aksi create dan update wajib menggunakan Form Request.
3. Semua aksi status seperti publish, block, renew, runOcr, reindex wajib menggunakan Request khusus bila ada input.
4. Search dan filter index dapat menggunakan Request query khusus.

Form Request minimal yang dibutuhkan:

1. LoginRequest
2. StoreUserRequest
3. UpdateUserRequest
4. ResetUserPasswordRequest
5. StoreRoleRequest
6. UpdateRoleRequest
7. UpdateUserRolesRequest
8. UpdateRolePermissionsRequest
9. UpdateOwnProfileRequest
10. ChangeOwnPasswordRequest
11. UpdateInstitutionProfileRequest
12. UpdateOperationalRuleRequest
13. Request per master data entity
14. StoreBibliographicRecordRequest
15. UpdateBibliographicRecordRequest
16. StorePhysicalItemRequest
17. UpdatePhysicalItemRequest
18. ChangePhysicalItemStatusRequest
19. StoreMemberRequest
20. UpdateMemberRequest
21. BlockMemberRequest
22. StoreLoanRequest
23. StoreReturnRequest
24. RenewLoanRequest
25. StoreDigitalAssetRequest
26. UpdateDigitalAssetRequest
27. UpdateDigitalAssetAccessRequest
28. Request filter laporan
29. Request filter audit log
30. Request filter queue monitor
31. OpacSearchRequest

## 17. Aturan Transaction Boundary

Controller tidak boleh menetapkan transaction boundary sendiri kecuali kondisi sangat sederhana. Transaction boundary wajib ditangani di service untuk proses berikut:

1. Simpan bibliographic record beserta relasinya
2. Simpan item fisik
3. Proses peminjaman
4. Proses pengembalian
5. Proses perpanjangan
6. Simpan aset digital beserta metadata
7. Perubahan rule akses aset digital
8. Import anggota
9. Penugasan role dan permission yang mempengaruhi beberapa tabel

## 18. Aturan Error Handling pada Controller

Controller harus menangani error secara terkendali:

1. ValidationException dikembalikan ke form dengan pesan yang jelas
2. AuthorizationException diarahkan ke 403
3. ModelNotFoundException diarahkan ke 404
4. DomainException dapat dikembalikan sebagai flash error yang manusiawi
5. Exception teknis berat harus dilog dan mengembalikan respons aman

Controller tidak boleh:

1. Menampilkan stack trace di production
2. Menelan error diam-diam tanpa logging
3. Mengembalikan pesan teknis mentah ke pengguna umum

## 19. Aturan Integrasi Controller dengan Search, OCR, dan Queue

1. Controller hanya memicu service.
2. Service memutuskan apakah job dijalankan sinkron atau asinkron.
3. Controller tidak boleh memanggil engine OCR langsung.
4. Controller tidak boleh menulis dokumen search index langsung.
5. Controller boleh memicu `runOcr()` dan `reindex()` hanya sebagai entry point proses.

## 20. Aturan Controller untuk OPAC

1. Controller OPAC hanya menampilkan data yang sudah dipublikasikan.
2. Controller OPAC tidak boleh mengakses data admin privat secara mentah.
3. Controller OPAC harus memanfaatkan search service untuk hasil pencarian.
4. Controller OPAC detail harus menampilkan ketersediaan item dan aset digital secara aman.
5. Controller preview publik harus mematuhi status publikasi dan embargo.

## 21. Controller yang Tidak Boleh Ada pada Fase 1

Controller berikut tidak boleh dibuat sebagai controller resmi fase 1:

1. PaymentController
2. SsoController aktif
3. RfidController
4. ReservationController lanjutan
5. AcquisitionWorkflowController kompleks
6. MultiCampusController
7. PublicApiController penuh
8. MobileAppController khusus

Catatan:

1. Bila butuh placeholder arsitektural, cukup dicatat di roadmap, bukan diimplementasikan.

## 22. Matriks Prioritas Implementasi Controller

### Prioritas P1

1. LoginController
2. DashboardController
3. InstitutionProfileController
4. OperationalRuleController
5. UserController
6. RoleController
7. PermissionController
8. UserRoleController
9. ProfileController
10. PasswordController
11. Semua controller master data
12. BibliographicRecordController
13. PhysicalItemController
14. MemberController
15. LoanController
16. ReturnController
17. ActiveLoanController
18. FineController
19. DigitalAssetController
20. SearchController
21. RecordController
22. HomeController
23. DashboardReportController

### Prioritas P2

1. RenewalController
2. CirculationHistoryController
3. QueueMonitorController
4. AssetAccessController
5. AssetPreviewController
6. Semua report controller ekspor
7. AuditLogController

### Prioritas P3

1. MemberImportController
2. StaticPageController
3. Controller fase lanjutan lainnya bila diaktifkan resmi

## 23. Mapping Controller ke Dokumen Turunan

Dokumen ini menjadi acuan wajib bagi:

1. 12_SERVICE_LAYER.md
2. 13_MODEL_MAP.md
3. 14_SCHEMA.sql
4. 16_VALIDATION_RULES.md
5. 17_WORKFLOW_STATE_MACHINE.md
6. 18_UI_UX_STANDARD.md
7. 21_SEARCH_INDEXING_SPEC.md
8. 22_STORAGE_FILE_POLICY.md
9. 23_OCR_AND_DIGITAL_PROCESSING.md
10. 25_REPORTING_SPEC.md
11. 28_SECURITY_POLICY.md
12. 29_AUDIT_LOG_SPEC.md
13. 31_TEST_PLAN.md
14. 32_TEST_SCENARIO.md
15. 38_TREE.md
16. 39_TRACEABILITY_MATRIX.md

Aturan turunannya:

1. Setiap controller method write harus punya service yang jelas.
2. Setiap controller method show atau index harus punya view yang sudah disepakati.
3. Setiap controller method harus memiliki request validation yang jelas.
4. Setiap controller method yang bekerja pada entity harus ditopang model dan schema yang jelas.
5. Test feature harus mengacu ke controller map ini.

## 24. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua route pada 09_ROUTE_MAP.md memiliki controller method.
2. Semua halaman pada 10_VIEW_MAP.md memiliki controller GET yang mengembalikan view.
3. Semua use case inti pada 06_USE_CASE.md memiliki controller entry point.
4. Semua permission sensitif pada 07_ROLE_PERMISSION_MATRIX.md dilindungi oleh controller dan route.
5. Semua modul inti pada 03_ARSITEKTUR_MODULAR.md memiliki controller yang memadai.
6. Tidak ada controller liar di luar domain yang sudah disepakati.

## 25. Kesimpulan

Dokumen Controller Map ini menetapkan seluruh controller resmi PERPUSQU secara lengkap, runtut, dan konsisten dengan blueprint 01 sampai 10. Dokumen ini menutup rantai dari menu, route, dan view menuju lapisan aplikasi yang nyata, sehingga AI Agent dan developer memiliki acuan yang jelas dalam membangun controller tanpa missing method, tanpa broken flow, dan tanpa penumpukan logika bisnis pada controller. Semua implementasi controller PERPUSQU wajib merujuk dokumen ini.

END OF 11_CONTROLLER_MAP.md