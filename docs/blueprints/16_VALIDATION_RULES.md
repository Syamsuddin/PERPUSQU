# 16_VALIDATION_RULES.md

## 1. Nama Dokumen

Validation Rules Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint aturan validasi input dan request

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan form request, validasi input, validasi query filter, validasi aksi proses, dan validasi bisnis dasar

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan aturan validasi resmi untuk seluruh input utama PERPUSQU, baik pada form create, update, search, filter, proses transaksi, maupun aksi sensitif. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, tester, dan reviewer agar seluruh request tervalidasi secara konsisten, aman, dan selaras dengan controller map, service layer, model map, serta schema database yang telah disusun sebelumnya.

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

Aturan wajib:

1. Semua request write harus memiliki validasi.
2. Semua request sensitif harus memiliki validasi eksplisit.
3. Semua query filter utama harus memiliki validasi input.
4. Validasi harus konsisten dengan schema database.
5. Validasi harus konsisten dengan service layer dan aturan bisnis.
6. Validasi teknis tidak boleh bertentangan dengan permission dan workflow.
7. Validasi harus dibagi menjadi validasi format, validasi relasi, dan validasi bisnis.

## 4. Prinsip Umum Validasi

Prinsip resmi validasi PERPUSQU adalah:

1. Validasi wajib dilakukan sebelum controller memanggil service.
2. Validasi utama menggunakan Form Request Laravel.
3. Validasi harus memeriksa field wajib, format, panjang data, enum, relasi foreign key, dan aturan bisnis dasar.
4. Validasi bisnis lanjutan tetap diperiksa lagi di service bila diperlukan.
5. Validasi tidak boleh hanya mengandalkan frontend.
6. Validasi harus menghasilkan pesan yang jelas dan manusiawi.
7. Validasi untuk list dan filter harus mencegah query tidak valid.
8. Validasi harus mendukung keamanan dasar, integritas data, dan konsistensi operasional.

## 5. Jenis Validasi

Validasi dalam PERPUSQU dibagi menjadi:

1. Validasi form create
2. Validasi form update
3. Validasi aksi status
4. Validasi aksi proses transaksi
5. Validasi upload file
6. Validasi filter dan search
7. Validasi authorization context
8. Validasi business rule lanjutan di service

## 6. Struktur Form Request yang Disarankan

Struktur Form Request yang disarankan:

```text
app/
  Modules/
    Identity/
      Http/
        Requests/
    Core/
      Http/
        Requests/
    MasterData/
      Http/
        Requests/
    Catalog/
      Http/
        Requests/
    Collection/
      Http/
        Requests/
    Member/
      Http/
        Requests/
    Circulation/
      Http/
        Requests/
    DigitalRepository/
      Http/
        Requests/
    Reporting/
      Http/
        Requests/
    Audit/
      Http/
        Requests/
    OPAC/
      Http/
        Requests/
    Profile/
      Http/
        Requests/
````

## 7. Aturan Umum Penamaan Form Request

Aturan penamaan:

1. Gunakan PascalCase.
2. Gunakan suffix `Request`.
3. Gunakan nama yang menggambarkan aksi.

Contoh:

1. `StoreUserRequest`
2. `UpdateBibliographicRecordRequest`
3. `StoreLoanRequest`
4. `UpdateDigitalAssetAccessRequest`
5. `OpacSearchRequest`

## 8. Aturan Umum Validasi per Kategori

### 8.1 Validasi String

1. Gunakan `string`
2. Tetapkan `max`
3. Gunakan `min` bila relevan
4. Gunakan `trim` di layer sanitasi bila disiapkan
5. Hindari string kosong pada field wajib

### 8.2 Validasi Angka

1. Gunakan `integer` atau `numeric`
2. Gunakan `min` dan `max`
3. Untuk nominal gunakan `numeric`
4. Untuk counter gunakan `integer`

### 8.3 Validasi Tanggal

1. Gunakan `date` atau `date_format`
2. Gunakan `after_or_equal` atau `before_or_equal` bila perlu
3. Validasi relasi tanggal penting tetap diperiksa lagi di service

### 8.4 Validasi Boolean

1. Gunakan `boolean`

### 8.5 Validasi Enum

1. Gunakan `in`
2. Nilai enum harus sama dengan schema dan workflow

### 8.6 Validasi Foreign Key

1. Gunakan `exists:tabel,id`
2. Tambahkan syarat aktif bila diperlukan di service atau custom rule

### 8.7 Validasi Unik

1. Gunakan `unique`
2. Saat update gunakan pengecualian id record saat ini

### 8.8 Validasi File

1. Gunakan `file`
2. Gunakan `mimes` atau `mimetypes`
3. Gunakan `max`
4. Untuk aset digital fase 1 fokus pada PDF

## 9. Daftar Form Request Resmi

### 9.1 Identity and Profile

1. LoginRequest
2. UserIndexRequest
3. StoreUserRequest
4. UpdateUserRequest
5. ResetUserPasswordRequest
6. RoleIndexRequest
7. StoreRoleRequest
8. UpdateRoleRequest
9. PermissionIndexRequest
10. UpdateUserRolesRequest
11. UpdateRolePermissionsRequest
12. UpdateOwnProfileRequest
13. ChangeOwnPasswordRequest

### 9.2 Core

1. UpdateInstitutionProfileRequest
2. UpdateOperationalRuleRequest

### 9.3 Master Data

1. AuthorIndexRequest
2. StoreAuthorRequest
3. UpdateAuthorRequest
4. PublisherIndexRequest
5. StorePublisherRequest
6. UpdatePublisherRequest
7. LanguageIndexRequest
8. StoreLanguageRequest
9. UpdateLanguageRequest
10. ClassificationIndexRequest
11. StoreClassificationRequest
12. UpdateClassificationRequest
13. SubjectIndexRequest
14. StoreSubjectRequest
15. UpdateSubjectRequest
16. CollectionTypeIndexRequest
17. StoreCollectionTypeRequest
18. UpdateCollectionTypeRequest
19. RackLocationIndexRequest
20. StoreRackLocationRequest
21. UpdateRackLocationRequest
22. FacultyIndexRequest
23. StoreFacultyRequest
24. UpdateFacultyRequest
25. StudyProgramIndexRequest
26. StoreStudyProgramRequest
27. UpdateStudyProgramRequest
28. ItemConditionIndexRequest
29. StoreItemConditionRequest
30. UpdateItemConditionRequest

### 9.4 Catalog

1. BibliographicRecordIndexRequest
2. StoreBibliographicRecordRequest
3. UpdateBibliographicRecordRequest

### 9.5 Collection

1. PhysicalItemIndexRequest
2. StorePhysicalItemRequest
3. UpdatePhysicalItemRequest
4. ChangePhysicalItemStatusRequest
5. PhysicalItemHistoryRequest

### 9.6 Member

1. MemberIndexRequest
2. StoreMemberRequest
3. UpdateMemberRequest
4. BlockMemberRequest
5. MemberHistoryRequest
6. MemberImportRequest

### 9.7 Circulation

1. StoreLoanRequest
2. StoreReturnRequest
3. RenewLoanRequest
4. RenewalIndexRequest
5. ActiveLoanIndexRequest
6. CirculationHistoryRequest
7. FineIndexRequest

### 9.8 Digital Repository

1. DigitalAssetIndexRequest
2. StoreDigitalAssetRequest
3. UpdateDigitalAssetRequest
4. UpdateDigitalAssetAccessRequest

### 9.9 Reporting

1. ReportDashboardRequest
2. CollectionReportRequest
3. CollectionReportExportRequest
4. MemberReportRequest
5. MemberReportExportRequest
6. CirculationReportRequest
7. CirculationReportExportRequest
8. FineReportRequest
9. FineReportExportRequest
10. PopularCollectionReportRequest
11. PopularCollectionReportExportRequest
12. DigitalAccessReportRequest
13. DigitalAccessReportExportRequest

### 9.10 Audit

1. AuditLogIndexRequest
2. QueueMonitorRequest
3. QueueRetryRequest

### 9.11 OPAC

1. OpacSearchRequest

## 10. Aturan Sanitasi Input

Sanitasi dasar yang disarankan:

1. Trim untuk string umum.
2. Lowercase untuk email dan username.
3. Uppercase opsional untuk code tertentu seperti classification code atau rack code.
4. Hilangkan spasi ganda untuk nama.
5. Normalisasi slug bibliographic record di service, bukan hanya di request.
6. Normalisasi keyword search di query request.

Catatan:

1. Sanitasi tidak menggantikan validasi.
2. Sanitasi berat sebaiknya dilakukan di service atau mutator.

## 11. Validasi Modul Identity and Profile

### 11.1 LoginRequest

Tujuan:
Memvalidasi form login internal.

Field dan aturan:

1. `login` => required|string|min:3|max:150
2. `password` => required|string|min:6|max:255

Catatan:

1. Field `login` dapat berisi email atau username.
2. Validasi akun aktif dilakukan di AuthenticationService.

### 11.2 UserIndexRequest

Tujuan:
Memvalidasi query daftar user.

Field:

1. `keyword` => nullable|string|max:150
2. `role_id` => nullable|integer|exists:roles,id
3. `is_active` => nullable|boolean
4. `page` => nullable|integer|min:1
5. `per_page` => nullable|integer|in:10,25,50,100

### 11.3 StoreUserRequest

Field:

1. `name` => required|string|min:3|max:150
2. `username` => required|string|min:3|max:100|alpha_dash|unique:users,username
3. `email` => required|email:rfc,dns|max:150|unique:users,email
4. `password` => required|string|min:8|max:255
5. `is_active` => nullable|boolean
6. `role_ids` => required|array|min:1
7. `role_ids.*` => integer|exists:roles,id

Aturan bisnis tambahan:

1. Role wajib minimal satu.
2. Password default dapat diatur admin, tetapi tetap minimal 8 karakter.

### 11.4 UpdateUserRequest

Field:

1. `name` => required|string|min:3|max:150
2. `username` => required|string|min:3|max:100|alpha_dash|unique:users,username,{user_id}
3. `email` => required|email:rfc,dns|max:150|unique:users,email,{user_id}
4. `is_active` => nullable|boolean

Catatan:

1. Perubahan role tidak dilakukan di request ini, tetapi di UpdateUserRolesRequest.

### 11.5 ResetUserPasswordRequest

Field:

1. `new_password` => required|string|min:8|max:255|confirmed

Field pasangan:

1. `new_password_confirmation` => required|string|min:8|max:255

### 11.6 RoleIndexRequest

Field:

1. `keyword` => nullable|string|max:100
2. `page` => nullable|integer|min:1
3. `per_page` => nullable|integer|in:10,25,50,100

### 11.7 StoreRoleRequest

Field:

1. `name` => required|string|min:3|max:100|unique:roles,name
2. `guard_name` => nullable|string|in:web

### 11.8 UpdateRoleRequest

Field:

1. `name` => required|string|min:3|max:100|unique:roles,name,{role_id}
2. `guard_name` => nullable|string|in:web

### 11.9 PermissionIndexRequest

Field:

1. `keyword` => nullable|string|max:150
2. `module` => nullable|string|max:100

### 11.10 UpdateUserRolesRequest

Field:

1. `role_ids` => required|array|min:1
2. `role_ids.*` => integer|exists:roles,id

Aturan bisnis tambahan:

1. User internal harus tetap memiliki minimal satu role aktif.

### 11.11 UpdateRolePermissionsRequest

Field:

1. `permission_ids` => required|array|min:1
2. `permission_ids.*` => integer|exists:permissions,id

Catatan:

1. Role tanpa permission sama sekali tidak dianjurkan.
2. Permission sensitif tetap dibatasi oleh authorization admin.

### 11.12 UpdateOwnProfileRequest

Field:

1. `name` => required|string|min:3|max:150
2. `email` => required|email:rfc,dns|max:150|unique:users,email,{current_user_id}

### 11.13 ChangeOwnPasswordRequest

Field:

1. `current_password` => required|string|min:8|max:255
2. `new_password` => required|string|min:8|max:255|different:current_password|confirmed
3. `new_password_confirmation` => required|string|min:8|max:255

Aturan bisnis tambahan:

1. Password lama diverifikasi lagi di service.

## 12. Validasi Modul Core

### 12.1 UpdateInstitutionProfileRequest

Field:

1. `institution_name` => required|string|min:3|max:255
2. `library_name` => required|string|min:3|max:255
3. `address` => nullable|string|max:2000
4. `phone` => nullable|string|max:50
5. `email` => nullable|email:rfc,dns|max:150
6. `website` => nullable|url|max:255
7. `about_text` => nullable|string|max:10000
8. `logo` => nullable|file|mimes:jpg,jpeg,png,webp|max:2048

### 12.2 UpdateOperationalRuleRequest

Field:

1. `loan_default_days` => required|integer|min:1|max:365
2. `loan_max_active_loans` => required|integer|min:1|max:50
3. `loan_max_renewal_count` => required|integer|min:0|max:10
4. `allow_renewal` => required|boolean
5. `require_active_member` => required|boolean
6. `require_unblocked_member` => required|boolean
7. `fine_daily_amount` => required|numeric|min:0|max:99999999.99
8. `asset_max_upload_size_mb` => required|integer|min:1|max:500
9. `ocr_enabled` => required|boolean
10. `public_preview_enabled` => required|boolean

## 13. Validasi Modul Master Data

### 13.1 Pola Umum Index Request Master Data

Field:

1. `keyword` => nullable|string|max:150
2. `is_active` => nullable|boolean
3. `page` => nullable|integer|min:1
4. `per_page` => nullable|integer|in:10,25,50,100

### 13.2 StoreAuthorRequest

Field:

1. `name` => required|string|min:3|max:200|unique:authors,name
2. `normalized_name` => nullable|string|max:200
3. `notes` => nullable|string|max:1000
4. `is_active` => nullable|boolean

### 13.3 UpdateAuthorRequest

Field:

1. `name` => required|string|min:3|max:200|unique:authors,name,{author_id}
2. `normalized_name` => nullable|string|max:200
3. `notes` => nullable|string|max:1000
4. `is_active` => nullable|boolean

### 13.4 StorePublisherRequest

Field:

1. `name` => required|string|min:3|max:200|unique:publishers,name
2. `city` => nullable|string|max:150
3. `notes` => nullable|string|max:1000
4. `is_active` => nullable|boolean

### 13.5 UpdatePublisherRequest

Field:

1. `name` => required|string|min:3|max:200|unique:publishers,name,{publisher_id}
2. `city` => nullable|string|max:150
3. `notes` => nullable|string|max:1000
4. `is_active` => nullable|boolean

### 13.6 StoreLanguageRequest

Field:

1. `code` => required|string|min:2|max:20|unique:languages,code
2. `name` => required|string|min:2|max:100|unique:languages,name
3. `is_active` => nullable|boolean

### 13.7 UpdateLanguageRequest

Field:

1. `code` => required|string|min:2|max:20|unique:languages,code,{language_id}
2. `name` => required|string|min:2|max:100|unique:languages,name,{language_id}
3. `is_active` => nullable|boolean

### 13.8 StoreClassificationRequest

Field:

1. `parent_id` => nullable|integer|exists:classifications,id
2. `code` => required|string|min:1|max:50|unique:classifications,code
3. `name` => required|string|min:2|max:200
4. `is_active` => nullable|boolean

### 13.9 UpdateClassificationRequest

Field:

1. `parent_id` => nullable|integer|exists:classifications,id|not_in:{classification_id}
2. `code` => required|string|min:1|max:50|unique:classifications,code,{classification_id}
3. `name` => required|string|min:2|max:200
4. `is_active` => nullable|boolean

Aturan bisnis tambahan:

1. Parent tidak boleh sama dengan dirinya sendiri.

### 13.10 StoreSubjectRequest

Field:

1. `name` => required|string|min:2|max:200|unique:subjects,name
2. `notes` => nullable|string|max:1000
3. `is_active` => nullable|boolean

### 13.11 UpdateSubjectRequest

Field:

1. `name` => required|string|min:2|max:200|unique:subjects,name,{subject_id}
2. `notes` => nullable|string|max:1000
3. `is_active` => nullable|boolean

### 13.12 StoreCollectionTypeRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:collection_types,code
2. `name` => required|string|min:2|max:150|unique:collection_types,name
3. `is_active` => nullable|boolean

### 13.13 UpdateCollectionTypeRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:collection_types,code,{collection_type_id}
2. `name` => required|string|min:2|max:150|unique:collection_types,name,{collection_type_id}
3. `is_active` => nullable|boolean

### 13.14 StoreRackLocationRequest

Field:

1. `code` => required|string|min:2|max:50|unique:rack_locations,code
2. `name` => required|string|min:2|max:150
3. `floor` => nullable|string|max:50
4. `room` => nullable|string|max:100
5. `description` => nullable|string|max:1000
6. `is_active` => nullable|boolean

### 13.15 UpdateRackLocationRequest

Field:

1. `code` => required|string|min:2|max:50|unique:rack_locations,code,{rack_location_id}
2. `name` => required|string|min:2|max:150
3. `floor` => nullable|string|max:50
4. `room` => nullable|string|max:100
5. `description` => nullable|string|max:1000
6. `is_active` => nullable|boolean

### 13.16 StoreFacultyRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:faculties,code
2. `name` => required|string|min:2|max:150|unique:faculties,name
3. `is_active` => nullable|boolean

### 13.17 UpdateFacultyRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:faculties,code,{faculty_id}
2. `name` => required|string|min:2|max:150|unique:faculties,name,{faculty_id}
3. `is_active` => nullable|boolean

### 13.18 StoreStudyProgramRequest

Field:

1. `faculty_id` => required|integer|exists:faculties,id
2. `code` => required|string|min:2|max:50|alpha_dash|unique:study_programs,code
3. `name` => required|string|min:2|max:150
4. `is_active` => nullable|boolean

### 13.19 UpdateStudyProgramRequest

Field:

1. `faculty_id` => required|integer|exists:faculties,id
2. `code` => required|string|min:2|max:50|alpha_dash|unique:study_programs,code,{study_program_id}
3. `name` => required|string|min:2|max:150
4. `is_active` => nullable|boolean

### 13.20 StoreItemConditionRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:item_conditions,code
2. `name` => required|string|min:2|max:150|unique:item_conditions,name
3. `severity_level` => required|integer|min:1|max:10
4. `is_active` => nullable|boolean

### 13.21 UpdateItemConditionRequest

Field:

1. `code` => required|string|min:2|max:50|alpha_dash|unique:item_conditions,code,{item_condition_id}
2. `name` => required|string|min:2|max:150|unique:item_conditions,name,{item_condition_id}
3. `severity_level` => required|integer|min:1|max:10
4. `is_active` => nullable|boolean

## 14. Validasi Modul Catalog

### 14.1 BibliographicRecordIndexRequest

Field:

1. `keyword` => nullable|string|max:255
2. `collection_type_id` => nullable|integer|exists:collection_types,id
3. `language_id` => nullable|integer|exists:languages,id
4. `publication_year` => nullable|integer|min:1000|max:9999
5. `publication_status` => nullable|string|in:draft,published,unpublished,archived
6. `is_public` => nullable|boolean
7. `page` => nullable|integer|min:1
8. `per_page` => nullable|integer|in:10,25,50,100

### 14.2 StoreBibliographicRecordRequest

Field:

1. `title` => required|string|min:2|max:255
2. `publisher_id` => nullable|integer|exists:publishers,id
3. `language_id` => nullable|integer|exists:languages,id
4. `classification_id` => nullable|integer|exists:classifications,id
5. `collection_type_id` => required|integer|exists:collection_types,id
6. `publication_year` => nullable|integer|min:1000|max:9999
7. `isbn` => nullable|string|max:50
8. `edition` => nullable|string|max:100
9. `keywords` => nullable|string|max:2000
10. `abstract` => nullable|string|max:20000
11. `cover` => nullable|file|mimes:jpg,jpeg,png,webp|max:4096
12. `publication_status` => nullable|string|in:draft,published,unpublished,archived
13. `is_public` => nullable|boolean
14. `author_ids` => required|array|min:1
15. `author_ids.*` => integer|exists:authors,id
16. `subject_ids` => nullable|array
17. `subject_ids.*` => integer|exists:subjects,id
18. `metadata_json` => nullable|array

Aturan bisnis tambahan:

1. Minimal satu pengarang wajib ada.
2. `is_public = true` tidak otomatis berarti boleh diterbitkan jika status belum published, logika final diperiksa lagi di service.
3. Validasi duplikasi bibliographic record bersifat business rule lanjutan di service, bukan unique schema murni.

### 14.3 UpdateBibliographicRecordRequest

Field:

1. `title` => required|string|min:2|max:255
2. `publisher_id` => nullable|integer|exists:publishers,id
3. `language_id` => nullable|integer|exists:languages,id
4. `classification_id` => nullable|integer|exists:classifications,id
5. `collection_type_id` => required|integer|exists:collection_types,id
6. `publication_year` => nullable|integer|min:1000|max:9999
7. `isbn` => nullable|string|max:50
8. `edition` => nullable|string|max:100
9. `keywords` => nullable|string|max:2000
10. `abstract` => nullable|string|max:20000
11. `cover` => nullable|file|mimes:jpg,jpeg,png,webp|max:4096
12. `publication_status` => nullable|string|in:draft,published,unpublished,archived
13. `is_public` => nullable|boolean
14. `author_ids` => required|array|min:1
15. `author_ids.*` => integer|exists:authors,id
16. `subject_ids` => nullable|array
17. `subject_ids.*` => integer|exists:subjects,id
18. `metadata_json` => nullable|array

## 15. Validasi Modul Collection

### 15.1 PhysicalItemIndexRequest

Field:

1. `keyword` => nullable|string|max:150
2. `bibliographic_record_id` => nullable|integer|exists:bibliographic_records,id
3. `rack_location_id` => nullable|integer|exists:rack_locations,id
4. `item_condition_id` => nullable|integer|exists:item_conditions,id
5. `item_status` => nullable|string|in:available,loaned,damaged,lost,repair,inactive
6. `page` => nullable|integer|min:1
7. `per_page` => nullable|integer|in:10,25,50,100

### 15.2 StorePhysicalItemRequest

Field:

1. `bibliographic_record_id` => required|integer|exists:bibliographic_records,id
2. `rack_location_id` => nullable|integer|exists:rack_locations,id
3. `item_condition_id` => nullable|integer|exists:item_conditions,id
4. `barcode` => required|string|min:3|max:100|unique:physical_items,barcode
5. `inventory_code` => nullable|string|min:3|max:100|unique:physical_items,inventory_code
6. `acquisition_date` => nullable|date
7. `item_status` => required|string|in:available,loaned,damaged,lost,repair,inactive
8. `notes` => nullable|string|max:1000

Aturan bisnis tambahan:

1. Item baru sangat dianjurkan berstatus available, damaged, repair, atau inactive.
2. Item baru tidak boleh langsung loaned tanpa proses sirkulasi, aturan ini diperiksa lagi di service.

### 15.3 UpdatePhysicalItemRequest

Field:

1. `rack_location_id` => nullable|integer|exists:rack_locations,id
2. `item_condition_id` => nullable|integer|exists:item_conditions,id
3. `barcode` => required|string|min:3|max:100|unique:physical_items,barcode,{item_id}
4. `inventory_code` => nullable|string|min:3|max:100|unique:physical_items,inventory_code,{item_id}
5. `acquisition_date` => nullable|date
6. `item_status` => required|string|in:available,loaned,damaged,lost,repair,inactive
7. `notes` => nullable|string|max:1000

### 15.4 ChangePhysicalItemStatusRequest

Field:

1. `new_status` => required|string|in:available,loaned,damaged,lost,repair,inactive
2. `reason` => nullable|string|max:1000

Aturan bisnis tambahan:

1. Transisi status sah diperiksa lagi di service.
2. Item dengan pinjaman aktif tidak boleh sembarang diubah ke status tertentu.

### 15.5 PhysicalItemHistoryRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `page` => nullable|integer|min:1
4. `per_page` => nullable|integer|in:10,25,50,100

## 16. Validasi Modul Member

### 16.1 MemberIndexRequest

Field:

1. `keyword` => nullable|string|max:150
2. `member_type` => nullable|string|in:student,lecturer,staff,alumni,guest
3. `faculty_id` => nullable|integer|exists:faculties,id
4. `study_program_id` => nullable|integer|exists:study_programs,id
5. `is_active` => nullable|boolean
6. `is_blocked` => nullable|boolean
7. `page` => nullable|integer|min:1
8. `per_page` => nullable|integer|in:10,25,50,100

### 16.2 StoreMemberRequest

Field:

1. `member_number` => required|string|min:3|max:100|unique:members,member_number
2. `member_type` => required|string|in:student,lecturer,staff,alumni,guest
3. `identity_number` => nullable|string|max:100|unique:members,identity_number
4. `name` => required|string|min:3|max:200
5. `email` => nullable|email:rfc,dns|max:150
6. `phone` => nullable|string|max:50
7. `faculty_id` => nullable|integer|exists:faculties,id
8. `study_program_id` => nullable|integer|exists:study_programs,id
9. `is_active` => nullable|boolean
10. `notes` => nullable|string|max:2000

Aturan bisnis tambahan:

1. Jika `study_program_id` diisi, konsistensi fakultas ke program studi diperiksa lagi di service.
2. `guest` boleh tanpa faculty dan study program.

### 16.3 UpdateMemberRequest

Field:

1. `member_number` => required|string|min:3|max:100|unique:members,member_number,{member_id}
2. `member_type` => required|string|in:student,lecturer,staff,alumni,guest
3. `identity_number` => nullable|string|max:100|unique:members,identity_number,{member_id}
4. `name` => required|string|min:3|max:200
5. `email` => nullable|email:rfc,dns|max:150
6. `phone` => nullable|string|max:50
7. `faculty_id` => nullable|integer|exists:faculties,id
8. `study_program_id` => nullable|integer|exists:study_programs,id
9. `is_active` => nullable|boolean
10. `notes` => nullable|string|max:2000

### 16.4 BlockMemberRequest

Field:

1. `blocked_reason` => required|string|min:3|max:1000

### 16.5 MemberHistoryRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `page` => nullable|integer|min:1
4. `per_page` => nullable|integer|in:10,25,50,100

### 16.6 MemberImportRequest

Status:
Fase lanjutan terbatas

Field:

1. `file` => required|file|mimes:xlsx,csv|max:10240

## 17. Validasi Modul Circulation

### 17.1 StoreLoanRequest

Field:

1. `member_id` => required|integer|exists:members,id
2. `barcode` => required|string|min:3|max:100
3. `loan_date` => nullable|date
4. `notes` => nullable|string|max:1000

Aturan bisnis tambahan:

1. Item dicari dari barcode di service.
2. Validasi anggota aktif dan tidak diblokir diperiksa lagi di service.
3. Validasi item tersedia diperiksa lagi di service.
4. Validasi batas pinjam diperiksa lagi di service.

### 17.2 StoreReturnRequest

Field:

1. `barcode` => required|string|min:3|max:100
2. `returned_at` => nullable|date
3. `returned_condition_id` => nullable|integer|exists:item_conditions,id
4. `notes` => nullable|string|max:1000

Aturan bisnis tambahan:

1. Pinjaman aktif dari item diperiksa di service.
2. Perhitungan keterlambatan dan denda diperiksa di service.

### 17.3 RenewLoanRequest

Field:

1. `notes` => nullable|string|max:1000

Aturan bisnis tambahan:

1. Kelayakan perpanjangan diperiksa di service.
2. Jumlah perpanjangan maksimum diperiksa di service.
3. Status pinjaman aktif diperiksa di service.

### 17.4 RenewalIndexRequest

Field:

1. `keyword` => nullable|string|max:150
2. `member_id` => nullable|integer|exists:members,id
3. `page` => nullable|integer|min:1
4. `per_page` => nullable|integer|in:10,25,50,100

### 17.5 ActiveLoanIndexRequest

Field:

1. `keyword` => nullable|string|max:150
2. `member_id` => nullable|integer|exists:members,id
3. `member_type` => nullable|string|in:student,lecturer,staff,alumni,guest
4. `is_overdue` => nullable|boolean
5. `from_date` => nullable|date
6. `to_date` => nullable|date|after_or_equal:from_date
7. `page` => nullable|integer|min:1
8. `per_page` => nullable|integer|in:10,25,50,100

### 17.6 CirculationHistoryRequest

Field:

1. `keyword` => nullable|string|max:150
2. `member_id` => nullable|integer|exists:members,id
3. `user_id` => nullable|integer|exists:users,id
4. `from_date` => nullable|date
5. `to_date` => nullable|date|after_or_equal:from_date
6. `page` => nullable|integer|min:1
7. `per_page` => nullable|integer|in:10,25,50,100

### 17.7 FineIndexRequest

Field:

1. `member_id` => nullable|integer|exists:members,id
2. `status` => nullable|string|in:outstanding,settled,waived,cancelled
3. `fine_type` => nullable|string|in:overdue,damage,loss,manual_adjustment
4. `from_date` => nullable|date
5. `to_date` => nullable|date|after_or_equal:from_date
6. `page` => nullable|integer|min:1
7. `per_page` => nullable|integer|in:10,25,50,100

## 18. Validasi Modul Digital Repository

### 18.1 DigitalAssetIndexRequest

Field:

1. `keyword` => nullable|string|max:255
2. `bibliographic_record_id` => nullable|integer|exists:bibliographic_records,id
3. `asset_type` => nullable|string|in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other
4. `publication_status` => nullable|string|in:draft,published,unpublished,archived
5. `is_public` => nullable|boolean
6. `ocr_status` => nullable|string|in:not_requested,queued,processing,success,failed
7. `index_status` => nullable|string|in:pending,queued,processing,indexed,failed
8. `page` => nullable|integer|min:1
9. `per_page` => nullable|integer|in:10,25,50,100

### 18.2 StoreDigitalAssetRequest

Field:

1. `bibliographic_record_id` => required|integer|exists:bibliographic_records,id
2. `asset_type` => required|string|in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other
3. `title` => nullable|string|max:255
4. `description` => nullable|string|max:5000
5. `file` => required|file|mimes:pdf|max:51200
6. `publication_status` => nullable|string|in:draft,published,unpublished,archived
7. `is_public` => nullable|boolean
8. `is_embargoed` => nullable|boolean
9. `embargo_until` => nullable|date|after:today
10. `access_rules` => nullable|array

Aturan bisnis tambahan:

1. Jika `is_embargoed = true`, maka `embargo_until` wajib diisi dan diperiksa lagi di service.
2. Publikasi publik final diperiksa lagi di service.
3. Tipe file fase 1 fokus PDF.

### 18.3 UpdateDigitalAssetRequest

Field:

1. `asset_type` => required|string|in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other
2. `title` => nullable|string|max:255
3. `description` => nullable|string|max:5000
4. `publication_status` => required|string|in:draft,published,unpublished,archived
5. `is_public` => nullable|boolean
6. `is_embargoed` => nullable|boolean
7. `embargo_until` => nullable|date
8. `replacement_file` => nullable|file|mimes:pdf|max:51200

Aturan bisnis tambahan:

1. Jika `is_embargoed = true`, maka `embargo_until` wajib dan harus lebih besar dari hari ini.
2. Jika file pengganti diunggah, integritas metadata file diperiksa lagi di service.

### 18.4 UpdateDigitalAssetAccessRequest

Field:

1. `access_rules` => nullable|array
2. `access_rules.*.access_scope` => required|string|in:public,internal,role_based,member_type_based
3. `access_rules.*.role_name` => nullable|string|max:100
4. `access_rules.*.member_type` => nullable|string|in:student,lecturer,staff,alumni,guest
5. `access_rules.*.allow_preview` => required|boolean
6. `access_rules.*.allow_download` => required|boolean

Aturan bisnis tambahan:

1. Validasi konsistensi scope dengan role_name atau member_type diperiksa lagi di service.
2. Aset privat tidak boleh otomatis menjadi publik hanya karena rule salah.

## 19. Validasi Modul Reporting

### 19.1 Prinsip Umum Request Laporan

Semua request laporan memiliki pola:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `page` => nullable|integer|min:1
4. `per_page` => nullable|integer|in:10,25,50,100

Tambahan sesuai jenis laporan.

### 19.2 ReportDashboardRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date

### 19.3 CollectionReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `collection_type_id` => nullable|integer|exists:collection_types,id
4. `language_id` => nullable|integer|exists:languages,id
5. `publication_status` => nullable|string|in:draft,published,unpublished,archived
6. `page` => nullable|integer|min:1
7. `per_page` => nullable|integer|in:10,25,50,100

### 19.4 CollectionReportExportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `collection_type_id` => nullable|integer|exists:collection_types,id
4. `language_id` => nullable|integer|exists:languages,id
5. `publication_status` => nullable|string|in:draft,published,unpublished,archived
6. `export_format` => nullable|string|in:xlsx,csv,pdf

Catatan:

1. Bila format belum semua didukung, service menentukan fallback.

### 19.5 MemberReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `member_type` => nullable|string|in:student,lecturer,staff,alumni,guest
4. `faculty_id` => nullable|integer|exists:faculties,id
5. `study_program_id` => nullable|integer|exists:study_programs,id
6. `is_active` => nullable|boolean
7. `is_blocked` => nullable|boolean
8. `page` => nullable|integer|min:1
9. `per_page` => nullable|integer|in:10,25,50,100

### 19.6 MemberReportExportRequest

Field:

1. Semua field dari MemberReportRequest
2. `export_format` => nullable|string|in:xlsx,csv,pdf

### 19.7 CirculationReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `member_type` => nullable|string|in:student,lecturer,staff,alumni,guest
4. `is_overdue` => nullable|boolean
5. `page` => nullable|integer|min:1
6. `per_page` => nullable|integer|in:10,25,50,100

### 19.8 CirculationReportExportRequest

Field:

1. Semua field dari CirculationReportRequest
2. `export_format` => nullable|string|in:xlsx,csv,pdf

### 19.9 FineReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `fine_type` => nullable|string|in:overdue,damage,loss,manual_adjustment
4. `status` => nullable|string|in:outstanding,settled,waived,cancelled
5. `page` => nullable|integer|min:1
6. `per_page` => nullable|integer|in:10,25,50,100

### 19.10 FineReportExportRequest

Field:

1. Semua field dari FineReportRequest
2. `export_format` => nullable|string|in:xlsx,csv,pdf

### 19.11 PopularCollectionReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `collection_type_id` => nullable|integer|exists:collection_types,id
4. `page` => nullable|integer|min:1
5. `per_page` => nullable|integer|in:10,25,50,100

### 19.12 PopularCollectionReportExportRequest

Field:

1. Semua field dari PopularCollectionReportRequest
2. `export_format` => nullable|string|in:xlsx,csv,pdf

### 19.13 DigitalAccessReportRequest

Field:

1. `from_date` => nullable|date
2. `to_date` => nullable|date|after_or_equal:from_date
3. `asset_type` => nullable|string|in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other
4. `publication_status` => nullable|string|in:draft,published,unpublished,archived
5. `page` => nullable|integer|min:1
6. `per_page` => nullable|integer|in:10,25,50,100

### 19.14 DigitalAccessReportExportRequest

Field:

1. Semua field dari DigitalAccessReportRequest
2. `export_format` => nullable|string|in:xlsx,csv,pdf

## 20. Validasi Modul Audit

### 20.1 AuditLogIndexRequest

Field:

1. `user_id` => nullable|integer|exists:users,id
2. `action` => nullable|string|max:100
3. `module_name` => nullable|string|max:100
4. `from_date` => nullable|date
5. `to_date` => nullable|date|after_or_equal:from_date
6. `page` => nullable|integer|min:1
7. `per_page` => nullable|integer|in:10,25,50,100

### 20.2 QueueMonitorRequest

Field:

1. `status` => nullable|string|max:50
2. `queue` => nullable|string|max:100
3. `from_date` => nullable|date
4. `to_date` => nullable|date|after_or_equal:from_date
5. `page` => nullable|integer|min:1
6. `per_page` => nullable|integer|in:10,25,50,100

### 20.3 QueueRetryRequest

Field:

1. `reason` => nullable|string|max:500

Aturan bisnis tambahan:

1. Job id dari route tetap diverifikasi lagi di service.
2. Retry hanya boleh untuk job yang sah dan gagal.

## 21. Validasi Modul OPAC

### 21.1 OpacSearchRequest

Field:

1. `q` => nullable|string|max:255
2. `collection_type_id` => nullable|integer|exists:collection_types,id
3. `language_id` => nullable|integer|exists:languages,id
4. `publication_year` => nullable|integer|min:1000|max:9999
5. `page` => nullable|integer|min:1
6. `per_page` => nullable|integer|in:10,20,50

Aturan bisnis tambahan:

1. OPAC hanya mengembalikan record publik.
2. Empty query boleh jika halaman OPAC ingin menampilkan data umum, keputusan final di service.

## 22. Validasi Enum Resmi

Enum yang harus konsisten di seluruh request dan service:

### 22.1 Publication Status Bibliographic Record

1. draft
2. published
3. unpublished
4. archived

### 22.2 Physical Item Status

1. available
2. loaned
3. damaged
4. lost
5. repair
6. inactive

### 22.3 Member Type

1. student
2. lecturer
3. staff
4. alumni
5. guest

### 22.4 Loan Status

1. active
2. returned
3. cancelled

### 22.5 Fine Type

1. overdue
2. damage
3. loss
4. manual_adjustment

### 22.6 Fine Status

1. outstanding
2. settled
3. waived
4. cancelled

### 22.7 Digital Asset Type

1. ebook
2. thesis
3. dissertation
4. journal_article
5. module
6. scanned_book
7. supplementary
8. other

### 22.8 Digital Asset Publication Status

1. draft
2. published
3. unpublished
4. archived

### 22.9 OCR Status

1. not_requested
2. queued
3. processing
4. success
5. failed

### 22.10 Index Status

1. pending
2. queued
3. processing
4. indexed
5. failed

### 22.11 Access Scope

1. public
2. internal
3. role_based
4. member_type_based

## 23. Validasi Upload File

Aturan upload file resmi fase 1:

### 23.1 Logo Institusi

1. mimes => jpg,jpeg,png,webp
2. max => 2048 KB

### 23.2 Cover Bibliographic Record

1. mimes => jpg,jpeg,png,webp
2. max => 4096 KB

### 23.3 Digital Asset

1. mimes => pdf
2. max => 51200 KB, 50 MB

Catatan:

1. Nilai maksimal aset digital harus konsisten dengan system setting.
2. Jika system setting berubah, request class dapat mengambil angka dari konfigurasi runtime melalui custom rule atau service helper.

## 24. Validasi Relasi Antar Field

Aturan relasi antar field yang penting:

1. `embargo_until` wajib bila `is_embargoed = true`
2. `new_password_confirmation` wajib bila `new_password` diisi
3. `author_ids` wajib minimal satu pada bibliographic record
4. `subject_ids` boleh kosong
5. `faculty_id` dan `study_program_id` harus konsisten secara domain
6. `returned_condition_id` harus ada di item_conditions bila diisi
7. `role_name` hanya relevan bila `access_scope = role_based`
8. `member_type` hanya relevan bila `access_scope = member_type_based`

## 25. Validasi Business Rule di Service

Aturan berikut tidak cukup aman bila hanya divalidasi di Form Request, sehingga wajib diperiksa lagi di service:

1. User internal harus tetap punya minimal satu role.
2. Role sensitif tidak boleh dimodifikasi sembarang oleh role yang lebih rendah.
3. Item tidak boleh dipinjam bila status bukan available.
4. Member tidak boleh meminjam bila nonaktif atau diblokir.
5. Batas maksimum pinjaman aktif harus dihormati.
6. Perpanjangan hanya boleh untuk pinjaman aktif dan yang masih memenuhi aturan.
7. Return harus menemukan pinjaman aktif yang sah.
8. Status item tidak boleh diubah ke status yang bertentangan dengan pinjaman aktif.
9. Aset digital privat tidak boleh diakses publik.
10. Publish record dan publish asset harus mematuhi syarat publikasi.
11. Embargo harus memblok akses sesuai rule.
12. OCR dan reindex hanya boleh dijalankan untuk aset yang relevan.
13. Hapus master data yang masih dipakai transaksi harus ditolak atau dialihkan ke nonaktif.
14. Parent classification tidak boleh membentuk siklus hierarki.

## 26. Pesan Validasi yang Direkomendasikan

Pesan validasi harus:

1. Jelas
2. Ringkas
3. Tidak terlalu teknis
4. Konsisten antar modul

Contoh gaya pesan:

1. Nama wajib diisi.
2. Kode harus unik.
3. Email tidak valid.
4. File harus berformat PDF.
5. Barcode sudah digunakan item lain.
6. Minimal satu pengarang harus dipilih.
7. Tanggal akhir harus sama atau setelah tanggal awal.
8. Status tidak valid.
9. Ukuran file melebihi batas maksimum.

## 27. Aturan Gagal Validasi

Saat validasi gagal:

1. User dikembalikan ke form sebelumnya.
2. Input lama tetap ditampilkan kecuali field file dan password.
3. Pesan error harus tampil dekat field atau di area global.
4. Tidak boleh melanjutkan ke service write process.
5. Error query filter tidak boleh merusak halaman, dapat fallback ke nilai aman bila dipilih demikian.

## 28. Aturan Validasi pada Filter dan Search

1. Query filter tidak boleh membiarkan value liar tak terbatas.
2. `per_page` harus dibatasi ke pilihan resmi.
3. `page` minimal 1.
4. Keyword dibatasi panjangnya.
5. Enum filter harus sesuai daftar status resmi.
6. Foreign key filter harus memakai exists bila berbasis id.

## 29. Aturan Validasi Aksi Sensitif

Aksi sensitif yang wajib punya request atau validasi eksplisit:

1. reset password user
2. update role permission
3. assign role ke user
4. block member
5. change physical item status
6. loan
7. return
8. renew
9. update digital asset access
10. run queue retry

## 30. Matriks Request ke Controller

| Request                          | Controller                    | Method                |
| -------------------------------- | ----------------------------- | --------------------- |
| LoginRequest                     | LoginController               | login                 |
| StoreUserRequest                 | UserController                | store                 |
| UpdateUserRequest                | UserController                | update                |
| ResetUserPasswordRequest         | UserController                | resetPassword         |
| StoreRoleRequest                 | RoleController                | store                 |
| UpdateRoleRequest                | RoleController                | update                |
| UpdateUserRolesRequest           | UserRoleController            | updateUserRoles       |
| UpdateRolePermissionsRequest     | UserRoleController            | updateRolePermissions |
| UpdateOwnProfileRequest          | ProfileController             | update                |
| ChangeOwnPasswordRequest         | PasswordController            | update                |
| UpdateInstitutionProfileRequest  | InstitutionProfileController  | update                |
| UpdateOperationalRuleRequest     | OperationalRuleController     | update                |
| StoreAuthorRequest               | AuthorController              | store                 |
| UpdateAuthorRequest              | AuthorController              | update                |
| StorePublisherRequest            | PublisherController           | store                 |
| UpdatePublisherRequest           | PublisherController           | update                |
| StoreLanguageRequest             | LanguageController            | store                 |
| UpdateLanguageRequest            | LanguageController            | update                |
| StoreClassificationRequest       | ClassificationController      | store                 |
| UpdateClassificationRequest      | ClassificationController      | update                |
| StoreSubjectRequest              | SubjectController             | store                 |
| UpdateSubjectRequest             | SubjectController             | update                |
| StoreCollectionTypeRequest       | CollectionTypeController      | store                 |
| UpdateCollectionTypeRequest      | CollectionTypeController      | update                |
| StoreRackLocationRequest         | RackLocationController        | store                 |
| UpdateRackLocationRequest        | RackLocationController        | update                |
| StoreFacultyRequest              | FacultyController             | store                 |
| UpdateFacultyRequest             | FacultyController             | update                |
| StoreStudyProgramRequest         | StudyProgramController        | store                 |
| UpdateStudyProgramRequest        | StudyProgramController        | update                |
| StoreItemConditionRequest        | ItemConditionController       | store                 |
| UpdateItemConditionRequest       | ItemConditionController       | update                |
| StoreBibliographicRecordRequest  | BibliographicRecordController | store                 |
| UpdateBibliographicRecordRequest | BibliographicRecordController | update                |
| StorePhysicalItemRequest         | PhysicalItemController        | store                 |
| UpdatePhysicalItemRequest        | PhysicalItemController        | update                |
| ChangePhysicalItemStatusRequest  | PhysicalItemController        | changeStatus          |
| StoreMemberRequest               | MemberController              | store                 |
| UpdateMemberRequest              | MemberController              | update                |
| BlockMemberRequest               | MemberController              | block                 |
| StoreLoanRequest                 | LoanController                | store                 |
| StoreReturnRequest               | ReturnController              | store                 |
| RenewLoanRequest                 | LoanController                | renew                 |
| StoreDigitalAssetRequest         | DigitalAssetController        | store                 |
| UpdateDigitalAssetRequest        | DigitalAssetController        | update                |
| UpdateDigitalAssetAccessRequest  | DigitalAssetController        | updateAccess          |
| OpacSearchRequest                | SearchController              | index                 |

## 31. Matriks Request ke Service

| Request                          | Service Utama                   |
| -------------------------------- | ------------------------------- |
| LoginRequest                     | AuthenticationService           |
| StoreUserRequest                 | UserManagementService           |
| UpdateUserRequest                | UserManagementService           |
| ResetUserPasswordRequest         | UserManagementService           |
| UpdateUserRolesRequest           | UserRoleAssignmentService       |
| UpdateRolePermissionsRequest     | RolePermissionAssignmentService |
| UpdateInstitutionProfileRequest  | InstitutionProfileService       |
| UpdateOperationalRuleRequest     | OperationalRuleService          |
| StoreBibliographicRecordRequest  | BibliographicRecordService      |
| UpdateBibliographicRecordRequest | BibliographicRecordService      |
| StorePhysicalItemRequest         | PhysicalItemService             |
| UpdatePhysicalItemRequest        | PhysicalItemService             |
| ChangePhysicalItemStatusRequest  | PhysicalItemStatusService       |
| StoreMemberRequest               | MemberService                   |
| UpdateMemberRequest              | MemberService                   |
| BlockMemberRequest               | MemberBlockingService           |
| StoreLoanRequest                 | LoanTransactionService          |
| StoreReturnRequest               | ReturnProcessingService         |
| RenewLoanRequest                 | RenewalService                  |
| StoreDigitalAssetRequest         | DigitalAssetUploadService       |
| UpdateDigitalAssetRequest        | DigitalAssetService             |
| UpdateDigitalAssetAccessRequest  | DigitalAssetAccessService       |
| OpacSearchRequest                | CatalogSearchService            |

## 32. Validasi yang Wajib Diuji

Pengujian validasi minimal wajib mencakup:

1. Login gagal karena field kosong
2. Buat user dengan email duplikat
3. Buat role tanpa nama
4. Bibliographic record tanpa pengarang
5. Physical item dengan barcode duplikat
6. Member dengan member_number duplikat
7. Loan dengan member diblokir
8. Loan dengan item tidak available
9. Return tanpa pinjaman aktif
10. Renew pada pinjaman tidak aktif
11. Upload asset non PDF
12. Upload asset melebihi ukuran maksimum
13. Update access rule tidak konsisten
14. Filter report dengan to_date lebih kecil dari from_date
15. OPAC search dengan parameter tidak valid

## 33. Dokumen Turunan yang Wajib Mengacu

Dokumen ini menjadi acuan wajib bagi:

1. 17_WORKFLOW_STATE_MACHINE.md
2. 18_UI_UX_STANDARD.md
3. 21_SEARCH_INDEXING_SPEC.md
4. 22_STORAGE_FILE_POLICY.md
5. 23_OCR_AND_DIGITAL_PROCESSING.md
6. 25_REPORTING_SPEC.md
7. 28_SECURITY_POLICY.md
8. 29_AUDIT_LOG_SPEC.md
9. 31_TEST_PLAN.md
10. 32_TEST_SCENARIO.md
11. 39_TRACEABILITY_MATRIX.md
12. 41_BACKEND_CHECKLIST.md
13. 42_FRONTEND_CHECKLIST.md

Aturan:

1. Workflow harus konsisten dengan enum validasi.
2. UI harus mampu menampilkan error sesuai dokumen ini.
3. Test plan harus memuat pengujian validasi utama.
4. Checklist backend dan frontend harus memeriksa implementasi request class sesuai dokumen ini.

## 34. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua controller write utama punya request class.
2. Semua field inti schema tervalidasi.
3. Semua enum schema selaras dengan request.
4. Semua upload file punya batas format dan ukuran.
5. Semua foreign key input utama memakai exists.
6. Semua proses kritis sirkulasi punya validasi minimal.
7. Semua filter utama list dan report tervalidasi.
8. Aturan bisnis yang tidak cukup ditangani request sudah dicatat untuk service.

## 35. Kesimpulan

Dokumen Validation Rules ini menetapkan standar validasi resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 15. Dokumen ini memastikan bahwa seluruh input, filter, aksi sensitif, transaksi sirkulasi, pengelolaan katalog, dan repositori digital memiliki lapisan validasi yang jelas sebelum masuk ke service layer. Dengan dokumen ini, implementasi backend dan frontend PERPUSQU memiliki acuan yang kuat untuk menjaga integritas data, keamanan input, dan konsistensi proses.

END OF 16_VALIDATION_RULES.md
