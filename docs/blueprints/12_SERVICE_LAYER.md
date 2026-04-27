# 12_SERVICE_LAYER.md

## 1. Nama Dokumen
Service Layer Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint service layer aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan service class, domain service, application service, transaction boundary, integrasi modul, queue dispatch, dan orkestrasi logika bisnis

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan struktur service layer resmi PERPUSQU agar seluruh logika bisnis inti, orkestrasi proses, integrasi modul, dan transaction boundary ditempatkan secara benar. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar controller tetap tipis, model tetap fokus pada data, dan proses bisnis utama berjalan konsisten sesuai blueprint sebelumnya.

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

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep sistem tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Service harus mengikuti controller map resmi.
5. Service harus mengikuti use case, SRS, dan workflow yang telah ditetapkan.
6. Service tidak boleh bertentangan dengan role permission matrix.
7. Service menjadi tempat logika bisnis inti, bukan controller.
8. Service tidak boleh mengembalikan HTML atau view.
9. Semua proses lintas model utama harus dipusatkan di service.
10. Semua process berat seperti OCR, indexing, export, dan import harus dikelola dari service.

## 4. Definisi Service Layer
Service layer dalam PERPUSQU adalah lapisan aplikasi yang bertanggung jawab untuk:

1. Menjalankan logika bisnis inti.
2. Menjaga konsistensi proses lintas model.
3. Menangani transaction boundary.
4. Mengorkestrasi integrasi lintas modul.
5. Mengorkestrasi queue, job, audit log, dan search indexing.
6. Menjadi jembatan antara controller dan model atau repository.
7. Menjaga agar controller tetap tipis dan model tetap fokus pada data.

## 5. Prinsip Umum Service Layer
Prinsip resmi service layer PERPUSQU adalah:

1. Satu service harus memiliki tanggung jawab yang jelas.
2. Nama service harus menggambarkan domain atau proses bisnisnya.
3. Service harus reusable.
4. Service harus dapat diuji secara mandiri.
5. Service tidak boleh menampilkan view.
6. Service tidak boleh tergantung pada request object mentah bila tidak perlu.
7. Service boleh menerima DTO, array tervalidasi, atau model yang sah.
8. Service harus menjaga integritas data.
9. Service harus menjadi tempat transaksi database lintas entity.
10. Service harus menangani efek samping seperti audit log, queue, dan indexing secara terkendali.

## 6. Posisi Service Layer dalam Arsitektur
Sesuai 03_ARSITEKTUR_MODULAR.md, service layer berada di antara controller dan model.

Alur standar:
1. Route menerima request
2. Controller menerima request tervalidasi
3. Controller memanggil service
4. Service menjalankan logika bisnis
5. Service memanggil model, query, job, event, storage, search, atau komponen pendukung
6. Service mengembalikan hasil terstruktur ke controller
7. Controller mengembalikan redirect, response, atau view

## 7. Kategori Service
Service dalam PERPUSQU dibagi menjadi beberapa kategori:

1. Application Service
2. Domain Service
3. Query Service
4. Integration Service
5. Reporting Service
6. File and Asset Service
7. Search and Processing Service
8. Audit and Monitoring Service

### 7.1 Application Service
Fokus:
1. Mengorkestrasi use case
2. Mengelola transaction boundary
3. Menghubungkan beberapa domain

Contoh:
1. LoanTransactionService
2. ReturnProcessingService
3. DigitalAssetUploadService

### 7.2 Domain Service
Fokus:
1. Menangani aturan domain spesifik
2. Menjaga konsistensi entity dan rule bisnis

Contoh:
1. FineCalculationService
2. MemberBlockingService
3. CatalogPublicationService

### 7.3 Query Service
Fokus:
1. Menyediakan query baca yang kompleks
2. Menyusun data untuk halaman list, detail, history, dan dashboard

Contoh:
1. CatalogSearchService
2. AuditLogQueryService
3. ActiveLoanService

### 7.4 Integration Service
Fokus:
1. Menjembatani integrasi ke komponen luar
2. Menyatukan storage, OCR, queue, dan sumber eksternal

Contoh:
1. AssetStreamingService
2. MemberImportService
3. QueueMonitorService

### 7.5 Reporting Service
Fokus:
1. Menyusun statistik dan laporan
2. Menyediakan data ekspor

Contoh:
1. CollectionReportService
2. ReportingDashboardService
3. ReportExportService

## 8. Struktur Folder Service yang Disarankan
Struktur folder service resmi yang disarankan:

```text
app/
  Modules/
    Core/
      Services/
    Identity/
      Services/
    MasterData/
      Services/
    Catalog/
      Services/
    Collection/
      Services/
    Member/
      Services/
    Circulation/
      Services/
    DigitalRepository/
      Services/
    Reporting/
      Services/
    Audit/
      Services/
    OPAC/
      Services/
    Profile/
      Services/
````

Subfolder diperbolehkan bila diperlukan, misalnya:

```text
Services/
  Queries/
  Actions/
  Reports/
  Exports/
  Access/
  Processing/
```

## 9. Aturan Penamaan Service

Aturan penamaan service:

1. Gunakan PascalCase.
2. Gunakan suffix `Service`.
3. Nama harus berbasis domain atau proses.
4. Hindari nama umum seperti `HelperService`.
5. Hindari satu service yang menangani terlalu banyak domain.

Contoh benar:

1. `BibliographicRecordService`
2. `LoanTransactionService`
3. `FineCalculationService`
4. `DigitalAssetUploadService`
5. `ReportExportService`

Contoh yang tidak dipakai:

1. `GeneralService`
2. `MasterService`
3. `LibraryService`
4. `CommonBusinessService`

## 10. Aturan Input dan Output Service

Aturan input service:

1. Service menerima data tervalidasi dari controller.
2. Service dapat menerima model, scalar, array tervalidasi, atau DTO.
3. Service tidak wajib mengetahui detail HTTP request.

Aturan output service:

1. Service dapat mengembalikan model.
2. Service dapat mengembalikan collection.
3. Service dapat mengembalikan array hasil proses.
4. Service dapat mengembalikan object hasil proses.
5. Service tidak mengembalikan view.
6. Service tidak mengembalikan redirect response.

## 11. Aturan Transaction Boundary

Transaction boundary wajib ditempatkan pada service untuk proses berikut:

1. Pembuatan bibliographic record beserta relasi pengarang dan subjek.
2. Pembuatan item fisik.
3. Peminjaman item fisik.
4. Pengembalian item fisik.
5. Perpanjangan pinjaman.
6. Pembuatan anggota bila melibatkan identitas unik dan relasi lain.
7. Unggah aset digital beserta metadata penting.
8. Perubahan aturan akses aset digital.
9. Penugasan role ke user.
10. Pembaruan permission ke role.
11. Import anggota massal.

Aturan:

1. Controller tidak boleh mengatur transaction boundary proses ini.
2. Service harus memutuskan commit atau rollback.
3. Kegagalan job OCR atau indexing tidak boleh membatalkan data utama bila data utama sudah sah.

## 12. Aturan Audit Log dalam Service

Audit log harus dipicu dari service pada proses berikut:

1. Login dan logout
2. Pembuatan, perubahan, aktivasi, nonaktifasi user
3. Perubahan role dan permission
4. Pembuatan, perubahan, publish, unpublish bibliographic record
5. Pembuatan, perubahan, penghapusan, perubahan status item
6. Pembuatan, perubahan, blokir, buka blokir anggota
7. Peminjaman, pengembalian, perpanjangan
8. Unggah, perubahan, penghapusan, perubahan akses aset digital
9. Perubahan profil institusi
10. Perubahan aturan operasional
11. Retry job monitoring sensitif

## 13. Aturan Queue dalam Service

Queue harus dipicu dari service, bukan dari controller langsung, untuk proses berikut:

1. OCR dokumen
2. Reindex search
3. Export laporan berat
4. Import anggota massal
5. Thumbnail atau derivative file bila nanti dipakai
6. Notifikasi email bila nanti diaktifkan

Aturan:

1. Service harus memutuskan job mana yang dijalankan async.
2. Controller hanya memanggil method service.
3. Service harus menangani status awal proses bila job async dipakai.

## 14. Aturan Search Indexing dalam Service

Search indexing harus dikendalikan oleh service berikut:

1. SearchIndexService
2. CatalogSearchService untuk query baca
3. OcrProcessingService untuk kontribusi teks hasil OCR

Aturan:

1. Bibliographic record adalah sumber utama index publik.
2. Aset digital dapat menyumbang teks pencarian bila diizinkan.
3. Controller tidak boleh membuat payload index langsung.
4. Service harus menjaga konsistensi antara MySQL dan Meilisearch.

## 15. Aturan Storage dan File Handling dalam Service

Pengelolaan file harus dilakukan dari service berikut:

1. DigitalAssetUploadService
2. DigitalAssetAccessService
3. AssetStreamingService

Aturan:

1. File tidak boleh disimpan ke database.
2. Metadata file disimpan ke MySQL.
3. Penyimpanan file harus tervalidasi.
4. Preview dan unduh file privat harus melalui pemeriksaan akses.
5. Service harus menangani kegagalan storage secara aman.

## 16. Daftar Service Resmi PERPUSQU

### 16.1 Modul Identity dan Profile

1. AuthenticationService
2. UserManagementService
3. RoleManagementService
4. PermissionMatrixService
5. UserRoleAssignmentService
6. RolePermissionAssignmentService
7. OwnProfileService
8. OwnPasswordService

### 16.2 Modul Core

1. DashboardWidgetService
2. InstitutionProfileService
3. OperationalRuleService

### 16.3 Modul Master Data

1. AuthorService
2. PublisherService
3. LanguageService
4. ClassificationService
5. SubjectService
6. CollectionTypeService
7. RackLocationService
8. FacultyService
9. StudyProgramService
10. ItemConditionService

### 16.4 Modul Catalog

1. BibliographicRecordService
2. CatalogPublicationService
3. SearchIndexService

### 16.5 Modul Collection

1. PhysicalItemService
2. PhysicalItemStatusService
3. PhysicalItemHistoryService

### 16.6 Modul Member

1. MemberService
2. MemberStatusService
3. MemberBlockingService
4. MemberHistoryService
5. MemberImportService

### 16.7 Modul Circulation

1. LoanTransactionService
2. ReturnProcessingService
3. RenewalService
4. RenewalListService
5. ActiveLoanService
6. CirculationHistoryService
7. FineCalculationService
8. FineReportingService

### 16.8 Modul Digital Repository

1. DigitalAssetService
2. DigitalAssetUploadService
3. DigitalAssetAccessService
4. OcrProcessingService
5. AssetStreamingService

### 16.9 Modul Reporting

1. ReportingDashboardService
2. CollectionReportService
3. MemberReportService
4. CirculationReportService
5. FineReportService
6. PopularCollectionReportService
7. DigitalAccessReportService
8. ReportExportService

### 16.10 Modul Audit

1. AuditLogService
2. AuditLogQueryService
3. QueueMonitorService

### 16.11 Modul OPAC

1. OpacHomeService
2. CatalogSearchService
3. OpacRecordDetailService
4. StaticPageContentService

## 17. Service Map Per Modul

## 17.1 Modul Identity dan Profile

### 17.1.1 AuthenticationService

Nama File:
`app/Modules/Identity/Services/AuthenticationService.php`

Tujuan:

1. Memproses autentikasi user internal
2. Menentukan redirect pasca login
3. Menjalankan logout terkontrol
4. Memicu audit log login dan logout

Dipakai oleh:

1. LoginController

Method inti:

1. `attemptLogin(array $credentials): AuthResult`
2. `logoutCurrentUser(): void`
3. `resolveRedirectForUser(User $user): string`

Tanggung jawab:

1. Validasi kredensial sah
2. Menolak akun nonaktif
3. Menyusun hasil login
4. Mencatat aktivitas autentikasi

Tidak boleh:

1. Mengembalikan view
2. Menangani flash message controller

### 17.1.2 UserManagementService

Nama File:
`app/Modules/Identity/Services/UserManagementService.php`

Tujuan:

1. Mengelola data user internal
2. Membuat user baru
3. Memperbarui user
4. Mengaktifkan user
5. Menghapus user sesuai kebijakan
6. Mereset password user

Dipakai oleh:

1. UserController

Method inti:

1. `getPaginatedUsers(array $filters): LengthAwarePaginator`
2. `createUser(array $data): User`
3. `updateUser(User $user, array $data): User`
4. `deleteUser(User $user): void`
5. `activateUser(User $user): User`
6. `resetPassword(User $user, array $data): void`

Tanggung jawab:

1. Menjamin email atau username unik
2. Menentukan status aktif awal
3. Menjaga audit trail user

### 17.1.3 RoleManagementService

Nama File:
`app/Modules/Identity/Services/RoleManagementService.php`

Tujuan:

1. Mengelola role sistem
2. Membuat role
3. Memperbarui role
4. Menghapus role sesuai kebijakan

Dipakai oleh:

1. RoleController

Method inti:

1. `getPaginatedRoles(array $filters): LengthAwarePaginator`
2. `createRole(array $data): Role`
3. `updateRole(Role $role, array $data): Role`
4. `deleteRole(Role $role): void`

### 17.1.4 PermissionMatrixService

Nama File:
`app/Modules/Identity/Services/PermissionMatrixService.php`

Tujuan:

1. Menyediakan daftar permission
2. Menyediakan matriks role ke permission
3. Menyediakan data tampilan halaman permission

Dipakai oleh:

1. PermissionController

Method inti:

1. `getPermissionMatrix(array $filters = []): array`
2. `getPermissionsGroupedByModule(): array`

### 17.1.5 UserRoleAssignmentService

Nama File:
`app/Modules/Identity/Services/UserRoleAssignmentService.php`

Tujuan:

1. Menetapkan role ke user
2. Memastikan assignment role sesuai kebijakan

Dipakai oleh:

1. UserRoleController

Method inti:

1. `assignRolesToUser(User $user, array $roleIds): User`

Aturan:

1. Harus memakai transaction
2. Harus mencatat audit log

### 17.1.6 RolePermissionAssignmentService

Nama File:
`app/Modules/Identity/Services/RolePermissionAssignmentService.php`

Tujuan:

1. Menetapkan permission ke role
2. Menjaga integritas permission sensitif

Dipakai oleh:

1. UserRoleController

Method inti:

1. `assignPermissionsToRole(Role $role, array $permissionIds): Role`

Aturan:

1. Harus memakai transaction
2. Harus mencatat audit log

### 17.1.7 OwnProfileService

Nama File:
`app/Modules/Profile/Services/OwnProfileService.php`

Tujuan:

1. Menyediakan data profil user login
2. Memperbarui profil user login

Dipakai oleh:

1. ProfileController

Method inti:

1. `getCurrentProfile(User $user): array`
2. `updateCurrentProfile(User $user, array $data): User`

### 17.1.8 OwnPasswordService

Nama File:
`app/Modules/Profile/Services/OwnPasswordService.php`

Tujuan:

1. Memproses perubahan password user login

Dipakai oleh:

1. PasswordController

Method inti:

1. `changePassword(User $user, array $data): void`

Aturan:

1. Harus memverifikasi password lama
2. Harus mencatat audit log perubahan password

## 17.2 Modul Core

### 17.2.1 DashboardWidgetService

Nama File:
`app/Modules/Core/Services/DashboardWidgetService.php`

Tujuan:

1. Menyusun data dashboard sesuai role
2. Menggabungkan statistik ringkas dari beberapa modul

Dipakai oleh:

1. DashboardController

Method inti:

1. `buildDashboardForUser(User $user): array`

Tanggung jawab:

1. Menentukan widget per role
2. Menyusun quick action
3. Menyusun statistik ringan

### 17.2.2 InstitutionProfileService

Nama File:
`app/Modules/Core/Services/InstitutionProfileService.php`

Tujuan:

1. Mengambil profil institusi
2. Memperbarui profil institusi

Dipakai oleh:

1. InstitutionProfileController

Method inti:

1. `getInstitutionProfile(): array`
2. `updateInstitutionProfile(array $data): array`

Aturan:

1. Harus mencatat audit log

### 17.2.3 OperationalRuleService

Nama File:
`app/Modules/Core/Services/OperationalRuleService.php`

Tujuan:

1. Menyediakan aturan operasional perpustakaan
2. Memperbarui aturan operasional

Dipakai oleh:

1. OperationalRuleController
2. LoanTransactionService
3. RenewalService
4. FineCalculationService

Method inti:

1. `getOperationalRules(): array`
2. `updateOperationalRules(array $data): array`
3. `getLoanPolicyForMember(Member $member): array`
4. `getFinePolicy(): array`

## 17.3 Modul Master Data

### 17.3.1 Pola Umum Service Master Data

Setiap service master data bertanggung jawab untuk:

1. Menyediakan paginated list
2. Menyimpan entitas baru
3. Memperbarui entitas
4. Menghapus atau menonaktifkan entitas sesuai kebijakan
5. Menyediakan opsi select untuk form modul lain

Method standar:

1. `getPaginatedList(array $filters): LengthAwarePaginator`
2. `create(array $data): Model`
3. `update(Model $model, array $data): Model`
4. `delete(Model $model): void`
5. `getSelectableOptions(array $filters = []): Collection`

### 17.3.2 AuthorService

Nama File:
`app/Modules/MasterData/Services/AuthorService.php`

Dipakai oleh:

1. AuthorController
2. BibliographicRecordService

### 17.3.3 PublisherService

Nama File:
`app/Modules/MasterData/Services/PublisherService.php`

Dipakai oleh:

1. PublisherController
2. BibliographicRecordService

### 17.3.4 LanguageService

Nama File:
`app/Modules/MasterData/Services/LanguageService.php`

Dipakai oleh:

1. LanguageController
2. BibliographicRecordService

### 17.3.5 ClassificationService

Nama File:
`app/Modules/MasterData/Services/ClassificationService.php`

Dipakai oleh:

1. ClassificationController
2. BibliographicRecordService

### 17.3.6 SubjectService

Nama File:
`app/Modules/MasterData/Services/SubjectService.php`

Dipakai oleh:

1. SubjectController
2. BibliographicRecordService

### 17.3.7 CollectionTypeService

Nama File:
`app/Modules/MasterData/Services/CollectionTypeService.php`

Dipakai oleh:

1. CollectionTypeController
2. BibliographicRecordService

### 17.3.8 RackLocationService

Nama File:
`app/Modules/MasterData/Services/RackLocationService.php`

Dipakai oleh:

1. RackLocationController
2. PhysicalItemService

### 17.3.9 FacultyService

Nama File:
`app/Modules/MasterData/Services/FacultyService.php`

Dipakai oleh:

1. FacultyController
2. MemberService

### 17.3.10 StudyProgramService

Nama File:
`app/Modules/MasterData/Services/StudyProgramService.php`

Dipakai oleh:

1. StudyProgramController
2. MemberService

### 17.3.11 ItemConditionService

Nama File:
`app/Modules/MasterData/Services/ItemConditionService.php`

Dipakai oleh:

1. ItemConditionController
2. PhysicalItemService
3. ReturnProcessingService

Catatan:

1. Semua service master data wajib mencatat audit log pada create, update, dan delete.
2. Semua service master data wajib memeriksa apakah data masih dipakai transaksi sebelum delete.

## 17.4 Modul Catalog

### 17.4.1 BibliographicRecordService

Nama File:
`app/Modules/Catalog/Services/BibliographicRecordService.php`

Tujuan:

1. Mengelola bibliographic record
2. Menyimpan metadata bibliografi
3. Menangani relasi pengarang, subjek, dan atribut bibliografi lain

Dipakai oleh:

1. BibliographicRecordController
2. OpacRecordDetailService
3. CatalogSearchService

Method inti:

1. `getPaginatedRecords(array $filters): LengthAwarePaginator`
2. `getRecordDetail(BibliographicRecord $record): array`
3. `createRecord(array $data): BibliographicRecord`
4. `updateRecord(BibliographicRecord $record, array $data): BibliographicRecord`
5. `deleteRecord(BibliographicRecord $record): void`

Aturan:

1. Create dan update harus memakai transaction
2. Relasi pengarang dan subjek diproses di service ini
3. Audit log wajib dicatat

### 17.4.2 CatalogPublicationService

Nama File:
`app/Modules/Catalog/Services/CatalogPublicationService.php`

Tujuan:

1. Mengatur publish dan unpublish bibliographic record
2. Menentukan kelayakan tampil ke OPAC

Dipakai oleh:

1. BibliographicRecordController

Method inti:

1. `publish(BibliographicRecord $record): BibliographicRecord`
2. `unpublish(BibliographicRecord $record): BibliographicRecord`
3. `isPubliclyVisible(BibliographicRecord $record): bool`

Aturan:

1. Publish dapat memicu reindex
2. Audit log wajib dicatat

### 17.4.3 SearchIndexService

Nama File:
`app/Modules/Catalog/Services/SearchIndexService.php`

Tujuan:

1. Membangun payload index pencarian
2. Sinkronisasi data katalog ke Meilisearch
3. Menghapus index bila data tidak lagi publik
4. Menjadwalkan reindex bila perlu

Dipakai oleh:

1. BibliographicRecordService
2. CatalogPublicationService
3. DigitalAssetService
4. OcrProcessingService

Method inti:

1. `reindexRecord(BibliographicRecord $record): void`
2. `removeRecordFromIndex(BibliographicRecord $record): void`
3. `dispatchReindexRecord(BibliographicRecord $record): void`
4. `buildIndexDocument(BibliographicRecord $record): array`

Aturan:

1. Tidak boleh dipanggil langsung dari controller untuk menyusun payload
2. Dapat memutuskan sync atau async

## 17.5 Modul Collection

### 17.5.1 PhysicalItemService

Nama File:
`app/Modules/Collection/Services/PhysicalItemService.php`

Tujuan:

1. Mengelola item fisik
2. Menjaga integritas relasi item ke bibliographic record
3. Menyediakan data item untuk modul sirkulasi

Dipakai oleh:

1. PhysicalItemController
2. LoanTransactionService
3. ReturnProcessingService

Method inti:

1. `getPaginatedItems(array $filters): LengthAwarePaginator`
2. `getItemDetail(PhysicalItem $item): array`
3. `createItem(array $data): PhysicalItem`
4. `updateItem(PhysicalItem $item, array $data): PhysicalItem`
5. `deleteItem(PhysicalItem $item): void`
6. `findAvailableItemByBarcode(string $barcode): ?PhysicalItem`

Aturan:

1. Barcode atau kode inventaris harus unik
2. Create dan update wajib mencatat audit log

### 17.5.2 PhysicalItemStatusService

Nama File:
`app/Modules/Collection/Services/PhysicalItemStatusService.php`

Tujuan:

1. Mengubah status item
2. Memastikan transisi status item valid

Dipakai oleh:

1. PhysicalItemController
2. LoanTransactionService
3. ReturnProcessingService

Method inti:

1. `changeStatus(PhysicalItem $item, string $targetStatus, array $context = []): PhysicalItem`
2. `markAsLoaned(PhysicalItem $item): PhysicalItem`
3. `markAsAvailable(PhysicalItem $item): PhysicalItem`
4. `markAsDamaged(PhysicalItem $item): PhysicalItem`
5. `markAsLost(PhysicalItem $item): PhysicalItem`

Aturan:

1. Harus memeriksa pinjaman aktif
2. Harus menjaga status yang sah

### 17.5.3 PhysicalItemHistoryService

Nama File:
`app/Modules/Collection/Services/PhysicalItemHistoryService.php`

Tujuan:

1. Menyediakan histori item
2. Menggabungkan perubahan status, transaksi, dan audit terkait item

Dipakai oleh:

1. PhysicalItemController

Method inti:

1. `getItemHistory(PhysicalItem $item, array $filters = []): array`

## 17.6 Modul Member

### 17.6.1 MemberService

Nama File:
`app/Modules/Member/Services/MemberService.php`

Tujuan:

1. Mengelola anggota
2. Menyimpan dan memperbarui data anggota
3. Menyediakan lookup anggota untuk sirkulasi

Dipakai oleh:

1. MemberController
2. LoanTransactionService
3. ActiveLoanService

Method inti:

1. `getPaginatedMembers(array $filters): LengthAwarePaginator`
2. `getMemberDetail(Member $member): array`
3. `createMember(array $data): Member`
4. `updateMember(Member $member, array $data): Member`
5. `deleteMember(Member $member): void`
6. `findMemberForCirculation(string $keyword): ?Member`

### 17.6.2 MemberStatusService

Nama File:
`app/Modules/Member/Services/MemberStatusService.php`

Tujuan:

1. Mengaktifkan dan menonaktifkan anggota
2. Menentukan kelayakan anggota untuk transaksi

Dipakai oleh:

1. MemberController
2. LoanTransactionService

Method inti:

1. `activate(Member $member): Member`
2. `deactivate(Member $member): Member`
3. `isEligibleForLoan(Member $member): bool`

### 17.6.3 MemberBlockingService

Nama File:
`app/Modules/Member/Services/MemberBlockingService.php`

Tujuan:

1. Memblokir anggota
2. Membuka blokir anggota
3. Menyimpan alasan blokir bila diperlukan

Dipakai oleh:

1. MemberController
2. LoanTransactionService

Method inti:

1. `block(Member $member, array $data = []): Member`
2. `unblock(Member $member): Member`
3. `isBlocked(Member $member): bool`

### 17.6.4 MemberHistoryService

Nama File:
`app/Modules/Member/Services/MemberHistoryService.php`

Tujuan:

1. Menyediakan histori anggota
2. Menampilkan histori pinjam, denda, dan perubahan status

Dipakai oleh:

1. MemberController

Method inti:

1. `getMemberHistory(Member $member, array $filters = []): array`

### 17.6.5 MemberImportService

Nama File:
`app/Modules/Member/Services/MemberImportService.php`

Status:
Fase lanjutan terbatas

Tujuan:

1. Mengimpor anggota massal
2. Memvalidasi file impor
3. Menjalankan import async bila perlu

Dipakai oleh:

1. MemberImportController

Method inti:

1. `importFromFile(array $data): ImportResult`
2. `dispatchImport(array $data): string`

## 17.7 Modul Circulation

### 17.7.1 LoanTransactionService

Nama File:
`app/Modules/Circulation/Services/LoanTransactionService.php`

Tujuan:

1. Memproses peminjaman item
2. Memvalidasi anggota
3. Memvalidasi item
4. Menentukan tanggal jatuh tempo
5. Membentuk transaksi pinjam
6. Mengubah status item

Dipakai oleh:

1. LoanController

Method inti:

1. `createLoan(array $data): Loan`
2. `validateLoanPrerequisites(Member $member, PhysicalItem $item): void`
3. `calculateDueDate(Member $member, PhysicalItem $item): Carbon`

Aturan:

1. Wajib transaction
2. Wajib memanggil OperationalRuleService
3. Wajib memanggil MemberStatusService
4. Wajib memanggil MemberBlockingService
5. Wajib memanggil PhysicalItemStatusService
6. Wajib mencatat audit log

### 17.7.2 ReturnProcessingService

Nama File:
`app/Modules/Circulation/Services/ReturnProcessingService.php`

Tujuan:

1. Memproses pengembalian item
2. Menutup pinjaman aktif
3. Menghitung keterlambatan dan denda
4. Mengembalikan status item

Dipakai oleh:

1. ReturnController

Method inti:

1. `processReturn(array $data): ReturnResult`
2. `resolveActiveLoanByItem(PhysicalItem $item): Loan`
3. `applyReturnedItemCondition(PhysicalItem $item, array $data = []): PhysicalItem`

Aturan:

1. Wajib transaction
2. Wajib memanggil FineCalculationService
3. Wajib memanggil PhysicalItemStatusService
4. Wajib mencatat audit log

### 17.7.3 RenewalService

Nama File:
`app/Modules/Circulation/Services/RenewalService.php`

Tujuan:

1. Memproses perpanjangan pinjaman
2. Memvalidasi syarat perpanjangan
3. Menetapkan jatuh tempo baru

Dipakai oleh:

1. LoanController

Method inti:

1. `renewLoan(Loan $loan, array $data = []): Loan`
2. `canRenew(Loan $loan): bool`
3. `calculateNewDueDate(Loan $loan): Carbon`

Aturan:

1. Harus mematuhi OperationalRuleService
2. Harus mencatat audit log

### 17.7.4 RenewalListService

Nama File:
`app/Modules/Circulation/Services/RenewalListService.php`

Tujuan:

1. Menyediakan daftar pinjaman yang dapat diperpanjang

Dipakai oleh:

1. RenewalController

Method inti:

1. `getRenewableLoans(array $filters): LengthAwarePaginator`

### 17.7.5 ActiveLoanService

Nama File:
`app/Modules/Circulation/Services/ActiveLoanService.php`

Tujuan:

1. Menyediakan daftar pinjaman aktif
2. Menyediakan filter pinjaman aktif

Dipakai oleh:

1. ActiveLoanController

Method inti:

1. `getActiveLoans(array $filters): LengthAwarePaginator`

### 17.7.6 CirculationHistoryService

Nama File:
`app/Modules/Circulation/Services/CirculationHistoryService.php`

Tujuan:

1. Menyediakan histori transaksi sirkulasi

Dipakai oleh:

1. CirculationHistoryController

Method inti:

1. `getCirculationHistory(array $filters): LengthAwarePaginator`

### 17.7.7 FineCalculationService

Nama File:
`app/Modules/Circulation/Services/FineCalculationService.php`

Tujuan:

1. Menghitung keterlambatan
2. Menghitung nominal denda
3. Menyusun hasil denda untuk pengembalian

Dipakai oleh:

1. ReturnProcessingService
2. FineReportingService
3. FineReportService

Method inti:

1. `calculateFine(Loan $loan, Carbon $returnedAt): FineCalculationResult`
2. `calculateLateDays(Loan $loan, Carbon $returnedAt): int`

### 17.7.8 FineReportingService

Nama File:
`app/Modules/Circulation/Services/FineReportingService.php`

Tujuan:

1. Menyediakan data operasional denda dan keterlambatan

Dipakai oleh:

1. FineController

Method inti:

1. `getFineIndexData(array $filters): LengthAwarePaginator`
2. `getFineSummary(array $filters): array`

## 17.8 Modul Digital Repository

### 17.8.1 DigitalAssetService

Nama File:
`app/Modules/DigitalRepository/Services/DigitalAssetService.php`

Tujuan:

1. Mengelola metadata aset digital
2. Menyediakan daftar dan detail aset
3. Menyelaraskan publikasi aset ke OPAC dan search index

Dipakai oleh:

1. DigitalAssetController
2. OpacRecordDetailService

Method inti:

1. `getPaginatedAssets(array $filters): LengthAwarePaginator`
2. `getAssetDetail(DigitalAsset $asset): array`
3. `updateAsset(DigitalAsset $asset, array $data): DigitalAsset`
4. `deleteAsset(DigitalAsset $asset): void`
5. `publishAsset(DigitalAsset $asset): DigitalAsset`
6. `unpublishAsset(DigitalAsset $asset): DigitalAsset`

### 17.8.2 DigitalAssetUploadService

Nama File:
`app/Modules/DigitalRepository/Services/DigitalAssetUploadService.php`

Tujuan:

1. Mengunggah file digital
2. Menyimpan metadata file
3. Menjalankan proses pasca unggah

Dipakai oleh:

1. DigitalAssetController

Method inti:

1. `uploadAsset(array $data): DigitalAsset`
2. `storeFile(array $data): StoredFileResult`
3. `dispatchPostUploadJobs(DigitalAsset $asset): void`

Aturan:

1. Wajib transaction untuk metadata
2. Penyimpanan file dan metadata harus aman dari kegagalan parsial
3. Audit log wajib dicatat

### 17.8.3 DigitalAssetAccessService

Nama File:
`app/Modules/DigitalRepository/Services/DigitalAssetAccessService.php`

Tujuan:

1. Menentukan apakah aset boleh diakses
2. Menentukan apakah aset boleh dipreview
3. Menentukan apakah aset boleh diunduh
4. Menetapkan aturan akses aset

Dipakai oleh:

1. DigitalAssetController
2. AssetAccessController
3. AssetPreviewController

Method inti:

1. `updateAccessRules(DigitalAsset $asset, array $data): DigitalAsset`
2. `canPreview(?User $user, DigitalAsset $asset): bool`
3. `canDownload(?User $user, DigitalAsset $asset): bool`
4. `isPublicAsset(DigitalAsset $asset): bool`

### 17.8.4 OcrProcessingService

Nama File:
`app/Modules/DigitalRepository/Services/OcrProcessingService.php`

Tujuan:

1. Menentukan kelayakan OCR
2. Menjalankan atau mendispatch OCR
3. Menyimpan hasil OCR
4. Memicu reindex bila hasil OCR berubah

Dipakai oleh:

1. DigitalAssetController

Method inti:

1. `dispatchOcr(DigitalAsset $asset): void`
2. `runOcrNow(DigitalAsset $asset): OcrResult`
3. `storeOcrResult(DigitalAsset $asset, OcrResult $result): void`

Aturan:

1. Controller hanya memanggil service ini
2. Service dapat memilih async
3. Gagal OCR tidak boleh membatalkan aset digital utama

### 17.8.5 AssetStreamingService

Nama File:
`app/Modules/DigitalRepository/Services/AssetStreamingService.php`

Tujuan:

1. Menyajikan stream file
2. Menyajikan download file
3. Menyembunyikan path storage internal

Dipakai oleh:

1. AssetAccessController

Method inti:

1. `streamAsset(DigitalAsset $asset): StreamedResponse`
2. `downloadAsset(DigitalAsset $asset): BinaryFileResponse`

## 17.9 Modul Reporting

### 17.9.1 ReportingDashboardService

Nama File:
`app/Modules/Reporting/Services/ReportingDashboardService.php`

Tujuan:

1. Menyusun dashboard statistik utama

Dipakai oleh:

1. DashboardReportController

Method inti:

1. `getDashboardData(array $filters = [], ?User $user = null): array`

### 17.9.2 CollectionReportService

Nama File:
`app/Modules/Reporting/Services/CollectionReportService.php`

Tujuan:

1. Menyediakan laporan koleksi

Method inti:

1. `getCollectionReport(array $filters): LengthAwarePaginator`
2. `getCollectionSummary(array $filters): array`

### 17.9.3 MemberReportService

Nama File:
`app/Modules/Reporting/Services/MemberReportService.php`

Method inti:

1. `getMemberReport(array $filters): LengthAwarePaginator`
2. `getMemberSummary(array $filters): array`

### 17.9.4 CirculationReportService

Nama File:
`app/Modules/Reporting/Services/CirculationReportService.php`

Method inti:

1. `getCirculationReport(array $filters): LengthAwarePaginator`
2. `getCirculationSummary(array $filters): array`

### 17.9.5 FineReportService

Nama File:
`app/Modules/Reporting/Services/FineReportService.php`

Method inti:

1. `getFineReport(array $filters): LengthAwarePaginator`
2. `getFineSummary(array $filters): array`

### 17.9.6 PopularCollectionReportService

Nama File:
`app/Modules/Reporting/Services/PopularCollectionReportService.php`

Method inti:

1. `getPopularCollectionReport(array $filters): LengthAwarePaginator`
2. `getPopularCollectionSummary(array $filters): array`

### 17.9.7 DigitalAccessReportService

Nama File:
`app/Modules/Reporting/Services/DigitalAccessReportService.php`

Method inti:

1. `getDigitalAccessReport(array $filters): LengthAwarePaginator`
2. `getDigitalAccessSummary(array $filters): array`

### 17.9.8 ReportExportService

Nama File:
`app/Modules/Reporting/Services/ReportExportService.php`

Tujuan:

1. Menangani ekspor laporan
2. Menentukan format ekspor
3. Menjaga konsistensi file output

Dipakai oleh:

1. Semua report controller yang memiliki action export

Method inti:

1. `exportCollectionReport(array $filters): ExportResult`
2. `exportMemberReport(array $filters): ExportResult`
3. `exportCirculationReport(array $filters): ExportResult`
4. `exportFineReport(array $filters): ExportResult`
5. `exportPopularCollectionReport(array $filters): ExportResult`
6. `exportDigitalAccessReport(array $filters): ExportResult`

## 17.10 Modul Audit

### 17.10.1 AuditLogService

Nama File:
`app/Modules/Audit/Services/AuditLogService.php`

Tujuan:

1. Mencatat aktivitas penting sistem

Dipakai oleh:

1. Hampir semua service write utama

Method inti:

1. `log(string $action, mixed $subject, array $context = []): void`
2. `logAuthentication(string $action, User $user, array $context = []): void`
3. `logSensitiveAction(string $action, mixed $subject, array $context = []): void`

Aturan:

1. Harus generik
2. Tidak boleh menghasilkan coupling berat
3. Dipanggil dari service, bukan dari view

### 17.10.2 AuditLogQueryService

Nama File:
`app/Modules/Audit/Services/AuditLogQueryService.php`

Tujuan:

1. Menyediakan daftar audit log
2. Menyediakan detail audit log

Dipakai oleh:

1. AuditLogController

Method inti:

1. `getPaginatedLogs(array $filters): LengthAwarePaginator`
2. `getLogDetail(ActivityLog $log): array`

### 17.10.3 QueueMonitorService

Nama File:
`app/Modules/Audit/Services/QueueMonitorService.php`

Tujuan:

1. Menyediakan data monitoring queue
2. Menjalankan retry job bila diizinkan

Dipakai oleh:

1. QueueMonitorController

Method inti:

1. `getQueueMonitorData(array $filters = []): array`
2. `retryJob(string $jobId): void`

## 17.11 Modul OPAC

### 17.11.1 OpacHomeService

Nama File:
`app/Modules/OPAC/Services/OpacHomeService.php`

Tujuan:

1. Menyediakan data beranda OPAC
2. Menyusun statistik publik ringan
3. Menyusun shortcut pencarian

Dipakai oleh:

1. HomeController

Method inti:

1. `getHomeData(): array`

### 17.11.2 CatalogSearchService

Nama File:
`app/Modules/OPAC/Services/CatalogSearchService.php`

Tujuan:

1. Menjalankan pencarian OPAC
2. Membaca index Meilisearch
3. Menggabungkan hasil dengan data final dari MySQL

Dipakai oleh:

1. SearchController
2. Reporting bila perlu query populer tertentu
3. BibliographicRecordService secara tidak langsung untuk reindex logic

Method inti:

1. `search(array $filters): SearchResult`
2. `buildFilters(array $filters): array`
3. `hydrateRecordsFromIndex(array $ids): Collection`

Aturan:

1. Meilisearch adalah pintu relevansi
2. MySQL tetap sumber kebenaran final

### 17.11.3 OpacRecordDetailService

Nama File:
`app/Modules/OPAC/Services/OpacRecordDetailService.php`

Tujuan:

1. Menyusun detail koleksi publik
2. Menampilkan item fisik yang layak ditampilkan
3. Menampilkan aset digital yang layak ditampilkan

Dipakai oleh:

1. RecordController

Method inti:

1. `getPublicRecordDetail(BibliographicRecord $record): array`

Aturan:

1. Hanya data publik yang boleh keluar
2. Aset digital privat tidak boleh bocor ke OPAC publik

### 17.11.4 StaticPageContentService

Nama File:
`app/Modules/OPAC/Services/StaticPageContentService.php`

Tujuan:

1. Menyediakan konten halaman tentang perpustakaan
2. Menyediakan konten bantuan pencarian

Dipakai oleh:

1. StaticPageController

Method inti:

1. `getAboutPageData(): array`
2. `getHelpPageData(): array`

## 18. Service Khusus yang Wajib Menjadi Pusat Logika Bisnis

### 18.1 Pusat Logika Sirkulasi

Wajib dipusatkan pada:

1. LoanTransactionService
2. ReturnProcessingService
3. RenewalService
4. FineCalculationService

### 18.2 Pusat Logika Katalog

Wajib dipusatkan pada:

1. BibliographicRecordService
2. CatalogPublicationService
3. SearchIndexService

### 18.3 Pusat Logika Aset Digital

Wajib dipusatkan pada:

1. DigitalAssetUploadService
2. DigitalAssetService
3. DigitalAssetAccessService
4. OcrProcessingService

### 18.4 Pusat Logika Authorization Assignment

Wajib dipusatkan pada:

1. UserRoleAssignmentService
2. RolePermissionAssignmentService

## 19. Service yang Tidak Boleh Menjadi God Service

Service berikut tidak boleh berkembang menjadi pusat segala hal:

1. DashboardWidgetService
2. BibliographicRecordService
3. MemberService
4. DigitalAssetService
5. ReportingDashboardService

Aturan:

1. Bila method mulai terlalu banyak dan domain terlalu lebar, pecah ke service turunan.
2. Controller tidak boleh memakai satu service super besar untuk semua modul.

## 20. Aturan Dependency Antar Service

Aturan dependency resmi:

1. Service boleh memanggil service lain dalam modul yang sama.
2. Service boleh memanggil service lintas modul bila memang ada dependensi domain sah.
3. Dependency lintas modul harus minimal dan jelas.
4. Hindari circular dependency.

Contoh dependency sah:

1. LoanTransactionService -> MemberStatusService
2. LoanTransactionService -> MemberBlockingService
3. LoanTransactionService -> OperationalRuleService
4. ReturnProcessingService -> FineCalculationService
5. DigitalAssetUploadService -> SearchIndexService
6. OcrProcessingService -> SearchIndexService
7. OpacRecordDetailService -> DigitalAssetAccessService

Contoh yang tidak sah:

1. AuthorService memanggil LoanTransactionService
2. FineReportService memanggil LoginController
3. View memanggil service langsung untuk write process

## 21. Aturan Query di Service

Aturan query data:

1. Query baca kompleks sebaiknya berada di Query Service atau service index.
2. Query tulis inti berada di service domain terkait.
3. Query tidak boleh tersebar acak di controller.
4. Query harus efisien dan siap diindeks.
5. Gunakan eager loading yang tepat.

## 22. Aturan Service dan Event

Service boleh memicu event bila dibutuhkan, tetapi event bukan pengganti service inti.

Contoh event yang boleh dipakai:

1. LoanCreated
2. LoanReturned
3. DigitalAssetUploaded
4. RecordPublished
5. UserRolesChanged

Aturan:

1. Controller memanggil service
2. Service memicu event bila ada efek samping tambahan
3. Event tidak boleh menggantikan proses utama transaksi inti

## 23. Aturan Service dan Job

Job boleh dipicu dari service untuk:

1. OCR
2. Reindex
3. Export berat
4. Import massal
5. Email notifikasi fase berikutnya

Aturan:

1. Service memutuskan dispatch
2. Job tidak dipanggil langsung dari view
3. Controller tidak mengelola detail queue

## 24. Matriks Controller ke Service Utama

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

## 25. Matriks Service ke Proses Kritis

| Proses Kritis                | Service Penanggung Jawab        |
| ---------------------------- | ------------------------------- |
| Login                        | AuthenticationService           |
| Create user                  | UserManagementService           |
| Assign role ke user          | UserRoleAssignmentService       |
| Assign permission ke role    | RolePermissionAssignmentService |
| Create bibliographic record  | BibliographicRecordService      |
| Publish bibliographic record | CatalogPublicationService       |
| Create item fisik            | PhysicalItemService             |
| Change status item           | PhysicalItemStatusService       |
| Create member                | MemberService                   |
| Block member                 | MemberBlockingService           |
| Loan item                    | LoanTransactionService          |
| Return item                  | ReturnProcessingService         |
| Renew loan                   | RenewalService                  |
| Calculate fine               | FineCalculationService          |
| Upload aset digital          | DigitalAssetUploadService       |
| Manage akses aset digital    | DigitalAssetAccessService       |
| Run OCR                      | OcrProcessingService            |
| Reindex                      | SearchIndexService              |
| Dashboard report             | ReportingDashboardService       |
| Export report                | ReportExportService             |
| View audit log               | AuditLogQueryService            |
| Retry queue job              | QueueMonitorService             |
| OPAC search                  | CatalogSearchService            |
| OPAC record detail           | OpacRecordDetailService         |

## 26. Service yang Wajib Memakai AuditLogService

Service berikut wajib memanggil AuditLogService:

1. AuthenticationService
2. UserManagementService
3. RoleManagementService
4. UserRoleAssignmentService
5. RolePermissionAssignmentService
6. InstitutionProfileService
7. OperationalRuleService
8. Semua service master data
9. BibliographicRecordService
10. CatalogPublicationService
11. PhysicalItemService
12. PhysicalItemStatusService
13. MemberService
14. MemberStatusService
15. MemberBlockingService
16. LoanTransactionService
17. ReturnProcessingService
18. RenewalService
19. DigitalAssetUploadService
20. DigitalAssetService
21. DigitalAssetAccessService
22. QueueMonitorService

## 27. Service yang Wajib Memakai Transaction

Service berikut wajib memakai transaction database:

1. UserManagementService pada create, update sensitif, reset password admin
2. UserRoleAssignmentService
3. RolePermissionAssignmentService
4. BibliographicRecordService
5. PhysicalItemService
6. MemberService
7. LoanTransactionService
8. ReturnProcessingService
9. RenewalService
10. DigitalAssetUploadService
11. DigitalAssetService pada update sensitif
12. MemberImportService

## 28. Service yang Wajib Mendukung Testing Tingkat Tinggi

Service berikut wajib menjadi fokus unit test dan integration test:

1. AuthenticationService
2. UserManagementService
3. BibliographicRecordService
4. PhysicalItemStatusService
5. LoanTransactionService
6. ReturnProcessingService
7. RenewalService
8. FineCalculationService
9. DigitalAssetUploadService
10. DigitalAssetAccessService
11. OcrProcessingService
12. SearchIndexService
13. CatalogSearchService

## 29. Service yang Tidak Wajib Aktif pada Go Live Fase 1

Service berikut boleh disiapkan sebagai struktur, tetapi tidak wajib aktif penuh pada go live fase 1:

1. MemberImportService
2. ReportExportService untuk semua format lanjutan
3. StaticPageContentService bila konten statis cukup sederhana
4. Service integrasi SIAKAD
5. Service SSO
6. Service notifikasi WhatsApp
7. Service RFID
8. Service acquisition penuh

## 30. Aturan Implementasi Dependency Injection

Semua service harus menggunakan dependency injection resmi Laravel.

Aturan:

1. Inject service ke controller melalui constructor atau method injection yang wajar.
2. Inject dependency service ke service lain melalui constructor.
3. Jangan membuat service baru manual dengan `new` di banyak tempat bila dapat di-inject.
4. Dependensi eksternal seperti storage, queue, search client, dan OCR wrapper sebaiknya di-abstraksikan.

## 31. Aturan Konsistensi dengan Dokumen Berikutnya

Dokumen ini harus menjadi acuan bagi:

1. 13_MODEL_MAP.md
2. 14_SCHEMA.sql
3. 16_VALIDATION_RULES.md
4. 17_WORKFLOW_STATE_MACHINE.md
5. 21_SEARCH_INDEXING_SPEC.md
6. 22_STORAGE_FILE_POLICY.md
7. 23_OCR_AND_DIGITAL_PROCESSING.md
8. 25_REPORTING_SPEC.md
9. 28_SECURITY_POLICY.md
10. 29_AUDIT_LOG_SPEC.md
11. 31_TEST_PLAN.md
12. 32_TEST_SCENARIO.md
13. 38_TREE.md
14. 39_TRACEABILITY_MATRIX.md
15. 41_BACKEND_CHECKLIST.md

Aturan:

1. Setiap controller utama pada dokumen 11 harus punya service di dokumen ini.
2. Setiap service yang mengubah data harus ditopang model dan schema yang jelas pada dokumen berikutnya.
3. Setiap service dengan aturan status harus selaras dengan workflow state machine.
4. Setiap service yang menyentuh search, OCR, storage, export, dan audit harus diturunkan ke spesifikasi teknis lanjutan.

## 32. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua controller utama pada 11_CONTROLLER_MAP.md telah memiliki service utama.
2. Semua proses bisnis inti telah dipindahkan dari controller ke service.
3. Semua transaction boundary proses penting telah ditentukan.
4. Semua proses audit penting telah memiliki titik service yang jelas.
5. Semua proses OCR, search indexing, storage, dan report export telah memiliki service penanggung jawab.
6. Tidak ada service yang menjadi god service lintas domain tanpa batas.

## 33. Kesimpulan

Dokumen Service Layer ini menetapkan lapisan logika bisnis resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 11. Dokumen ini memastikan setiap proses penting seperti katalogisasi, pengelolaan item, sirkulasi, repositori digital, pencarian OPAC, pelaporan, dan audit memiliki service penanggung jawab yang jelas. Dengan dokumen ini, AI Agent dan developer memiliki acuan kuat untuk menempatkan logika bisnis di tempat yang benar, menjaga controller tetap tipis, menjaga integritas data, dan menghindari implementasi yang kacau. Semua coding backend PERPUSQU wajib merujuk dokumen ini.

END OF 12_SERVICE_LAYER.md