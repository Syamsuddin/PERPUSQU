# 39_TRACEABILITY_MATRIX.md

## 1. Nama Dokumen

Traceability Matrix Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint matriks keterlacakan implementasi sistem

### 2.3 Status Dokumen

Resmi, acuan wajib pemetaan keterkaitan antara use case, menu, route, controller, request, service, model, tabel, view, permission, audit, error code, dan test scenario

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan matriks keterlacakan resmi PERPUSQU agar seluruh definisi pada blueprint, mulai dari use case, menu, route, controller, service, model, schema database, view, audit, security, reporting, import export, OCR, search, dan test scenario, saling terhubung secara jelas. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, tester, dan administrator sistem agar tidak ada menu tanpa route, tidak ada route tanpa controller, tidak ada controller tanpa service, tidak ada service tanpa model atau tabel, tidak ada aksi sensitif tanpa permission dan audit, serta tidak ada fitur inti tanpa skenario uji.

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
36. 36_PERFORMANCE_GUIDE.md
37. 37_CODING_STANDARD.md
38. 38_TREE.md

Aturan wajib:

1. Semua pemetaan dalam matriks ini wajib mengikuti nama resmi yang telah disepakati.
2. Tidak boleh ada pemetaan yang mengasumsikan file, route, atau service di luar TREE resmi.
3. Tidak boleh ada fitur inti yang berhenti pada menu atau UI saja tanpa jejak implementasi backend.
4. Aksi sensitif wajib terhubung ke permission, audit, dan test scenario.
5. Fitur fase 1 yang belum termasuk scope wajib ditandai sebagai out of scope, bukan dibiarkan ambigu.
6. Matriks ini menjadi alat utama verifikasi missing link sebelum coding, sebelum testing, dan sebelum go live.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. prinsip traceability
2. level traceability
3. kode traceability item
4. matriks per modul
5. pemetaan use case ke implementasi
6. pemetaan menu ke route dan view
7. pemetaan route ke controller dan request
8. pemetaan controller ke service
9. pemetaan service ke model dan tabel
10. pemetaan aksi ke permission dan policy
11. pemetaan aksi ke audit log
12. pemetaan aksi ke error code utama
13. pemetaan aksi ke test scenario
14. daftar gap yang tidak boleh terjadi

## 5. Prinsip Umum Traceability

Prinsip resmi traceability PERPUSQU adalah:

1. lengkap
2. jelas
3. konsisten
4. dapat diuji
5. dapat diaudit
6. dapat dilacak dua arah
7. tidak ambigu
8. fokus pada fitur nyata

## 6. Level Traceability Resmi

Traceability resmi harus mampu menjawab jalur berikut:

1. use case ke menu
2. menu ke route
3. route ke controller
4. controller ke request validation
5. controller ke service
6. service ke model
7. model ke tabel
8. service ke audit
9. service ke error code
10. fitur ke view
11. fitur ke permission
12. fitur ke test scenario

## 7. Kode Item Traceability

Format kode item yang direkomendasikan:
`TM-{MODUL}-{NOMOR}`

Contoh:

1. TM-AUTH-001
2. TM-CAT-005
3. TM-CIRC-004
4. TM-DIG-008
5. TM-RPT-003

## 8. Kategori Modul Traceability

Kategori modul resmi:

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
16. INTEGRATION

## 9. Kolom Traceability Minimum

Setiap item traceability minimal memuat:

1. kode item
2. fitur atau aksi
3. use case
4. menu
5. route name
6. controller method
7. request
8. service utama
9. model utama
10. tabel utama
11. view
12. permission atau policy
13. audit event
14. error code utama
15. test scenario utama

## 10. Aturan Status Keterlacakan

Status keterlacakan yang dipakai:

1. linked
2. partial
3. planned
4. out_of_scope

Arti:

1. linked, seluruh jejak utama sudah ada di blueprint
2. partial, sebagian jejak ada tetapi butuh detail lanjutan atau implementasi pendukung
3. planned, sudah direncanakan tetapi menunggu dokumen lanjutan minor
4. out_of_scope, tidak termasuk fase 1

Untuk fitur inti pada fase 1, target status wajib adalah:

1. linked

## 11. Traceability Matrix Tingkat Tinggi per Modul

| Modul | Use Case | Menu | Route | Controller | Service | Model | View | Permission | Audit | Test | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|
| AUTH | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| ACCESS | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| CORE | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| MASTER | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| CATALOG | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| COLLECTION | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| MEMBER | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| CIRCULATION | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| DIGITAL | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| OPAC | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| REPORT | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| IMPORT | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| EXPORT | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| AUDIT | ya | ya | ya | ya | ya | ya | ya | ya | ya | ya | linked |
| API | ya | tidak wajib menu | ya | ya | ya | ya | tidak wajib | ya | opsional | ya | linked |
| INTEGRATION | ya | tidak langsung | ya atau job | ya | ya | ya | tidak wajib | ya | ya | ya | linked |

## 12. Matriks Traceability AUTH

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-AUTH-001 | Login | login admin | Login | admin.auth.login | AuthController@login | LoginRequest | AuthenticationService | users | auth/login.blade.php | guest only | opsional login event | AUTH_401_INVALID_CREDENTIALS, AUTH_403_ACCOUNT_INACTIVE | TS-AUTH-001, TS-AUTH-002, TS-AUTH-003 | linked |
| TM-AUTH-002 | Logout | logout admin | User Menu | admin.auth.logout | AuthController@logout | tidak wajib | AuthenticationService | users | tidak wajib view khusus | auth | opsional logout event | AUTH_440_SESSION_EXPIRED | TS-AUTH-004 | linked |
| TM-AUTH-003 | Reset password user | reset password pengguna | Users | admin.identity.users.reset_password | UserController@resetPassword | ResetUserPasswordRequest | UserService | users | modules/identity/users/index.blade.php | users.reset_password, UserPolicy | reset_user_password | AUTH_403_PASSWORD_RESET_NOT_ALLOWED, SEC_403_PERMISSION_DENIED | TS-AUTH-005 | linked |

## 13. Matriks Traceability ACCESS

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-ACCESS-001 | Lihat menu sesuai role | akses menu sesuai role | Sidebar | semua route admin | middleware dan layout | tidak wajib | PermissionMatrixResolver | roles, permissions, model_has_roles, role_has_permissions | layouts/admin.blade.php | permission matrix | tidak wajib | SEC_403_PERMISSION_DENIED | TS-ACCESS-001 | linked |
| TM-ACCESS-002 | Tolak route tanpa permission | akses route sensitif | seluruh menu sensitif | seluruh route admin sensitif | middleware permission | tidak wajib | middleware plus policy | users, roles, permissions | errors/403.blade.php | permission dan policy | opsional denial log | SEC_403_PERMISSION_DENIED | TS-ACCESS-002 | linked |
| TM-ACCESS-003 | Tolak resource oleh policy | akses resource tertentu | context menu terkait | route resource terkait | controller resource terkait | request terkait | policy plus service | resource terkait | errors/403.blade.php | resource policy | opsional denial log | SEC_403_POLICY_DENIED | TS-ACCESS-003 | linked |

## 14. Matriks Traceability CORE

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-CORE-001 | Dashboard admin | lihat dashboard | Dashboard | admin.dashboard | DashboardController@index | tidak wajib | DashboardService | institution_profiles, system_settings dan agregat modul | modules/core/dashboard/index.blade.php | dashboard.view | tidak wajib | RPT_500_AGGREGATION_FAILED | TS-CORE-001 implisit melalui UI dan smoke | linked |
| TM-CORE-002 | Ubah profil institusi | kelola profil perpustakaan | Profil Institusi | admin.core.institution_profile.update | InstitutionProfileController@update | UpdateInstitutionProfileRequest | InstitutionProfileService | institution_profiles | modules/core/institution_profile/edit.blade.php | institution_profile.update, InstitutionProfilePolicy | update_institution_profile | VAL_422_INVALID_INPUT | TS-CORE-001 | linked |
| TM-CORE-003 | Ubah pengaturan operasional | kelola setting | Pengaturan Sistem | admin.core.system_settings.update | SystemSettingController@update | UpdateSystemSettingRequest | SystemSettingService | system_settings | modules/core/system_settings/edit.blade.php | system_settings.update, SystemSettingPolicy | update_system_setting | BUS_409_RULE_VIOLATION, VAL_422_INVALID_INPUT | TS-CORE-002 | linked |

## 15. Matriks Traceability MASTER DATA

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-MASTER-001 | CRUD author | kelola pengarang | Master Data > Author | admin.master_data.authors.index, store, update | AuthorController@index, store, update | StoreAuthorRequest, UpdateAuthorRequest | AuthorService | authors | modules/master_data/authors/index.blade.php | authors.view, authors.create, authors.update, AuthorPolicy | create_author, update_author | VAL_422_DUPLICATE_VALUE, BUS_409_RESOURCE_IN_USE | TS-MASTER-001, TS-MASTER-002, TS-MASTER-003 | linked |
| TM-MASTER-002 | CRUD publisher | kelola penerbit | Master Data > Publisher | admin.master_data.publishers.index, store, update | PublisherController@index, store, update | StorePublisherRequest, UpdatePublisherRequest | PublisherService | publishers | modules/master_data/publishers/index.blade.php | publishers.* | create_publisher, update_publisher | VAL_422_DUPLICATE_VALUE, BUS_409_RESOURCE_IN_USE | TS-MASTER-003 analog | linked |
| TM-MASTER-003 | CRUD language | kelola bahasa | Master Data > Language | admin.master_data.languages.index, store, update | LanguageController@index, store, update | StoreLanguageRequest, UpdateLanguageRequest | LanguageService | languages | modules/master_data/languages/index.blade.php | languages.* | create_language, update_language | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-004 | CRUD classification | kelola klasifikasi | Master Data > Classification | admin.master_data.classifications.index, store, update | ClassificationController@index, store, update | StoreClassificationRequest, UpdateClassificationRequest | ClassificationService | classifications | modules/master_data/classifications/index.blade.php | classifications.* | create_classification, update_classification | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-005 | CRUD subject | kelola subjek | Master Data > Subject | admin.master_data.subjects.index, store, update | SubjectController@index, store, update | StoreSubjectRequest, UpdateSubjectRequest | SubjectService | subjects | modules/master_data/subjects/index.blade.php | subjects.* | create_subject, update_subject | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-006 | CRUD collection type | kelola jenis koleksi | Master Data > Collection Type | admin.master_data.collection_types.index, store, update | CollectionTypeController@index, store, update | StoreCollectionTypeRequest, UpdateCollectionTypeRequest | CollectionTypeService | collection_types | modules/master_data/collection_types/index.blade.php | collection_types.* | create_collection_type, update_collection_type | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-007 | CRUD rack location | kelola lokasi rak | Master Data > Rack Location | admin.master_data.rack_locations.index, store, update | RackLocationController@index, store, update | StoreRackLocationRequest, UpdateRackLocationRequest | RackLocationService | rack_locations | modules/master_data/rack_locations/index.blade.php | rack_locations.* | create_rack_location, update_rack_location | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-008 | CRUD faculty | kelola fakultas | Master Data > Faculty | admin.master_data.faculties.index, store, update | FacultyController@index, store, update | StoreFacultyRequest, UpdateFacultyRequest | FacultyService | faculties | modules/master_data/faculties/index.blade.php | faculties.* | create_faculty, update_faculty | VAL_422_DUPLICATE_VALUE | TS-MASTER setara | linked |
| TM-MASTER-009 | CRUD study program | kelola prodi | Master Data > Study Program | admin.master_data.study_programs.index, store, update | StudyProgramController@index, store, update | StoreStudyProgramRequest, UpdateStudyProgramRequest | StudyProgramService | study_programs | modules/master_data/study_programs/index.blade.php | study_programs.* | create_study_program, update_study_program | VAL_422_DUPLICATE_VALUE, VAL_422_INVALID_FOREIGN_KEY | TS-MASTER setara | linked |
| TM-MASTER-010 | CRUD item condition | kelola kondisi item | Collection atau Master terkait | admin.collection.item_conditions.index | ItemConditionController@index dan aksi terkait | request terkait | service kondisi item | item_conditions | belum perlu view terpisah bila embedded atau planned | item_conditions.* | create_item_condition, update_item_condition | VAL_422_DUPLICATE_VALUE | planned minor |
| TM-MASTER-011 | Lookup master data | lookup dinamis | dipakai banyak form | api.internal.lookup.* | lookup controller internal | request lookup | LookupOptionBuilder dan service domain | tabel master terkait | tidak wajib view | permission lookup sesuai modul | tidak wajib | API_422_INVALID_PAYLOAD | TS-API-001 analog | linked |

## 16. Matriks Traceability CATALOG

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-CAT-001 | List katalog | lihat daftar katalog | Katalog > Daftar Katalog | admin.catalog.records.index | BibliographicRecordController@index | BibliographicRecordFilterRequest | BibliographicRecordService, BibliographicRecordIndexQuery | bibliographic_records, bibliographic_record_authors, bibliographic_record_subjects | modules/catalog/records/index.blade.php | catalog.records.view, BibliographicRecordPolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-CAT-001 sebagian | linked |
| TM-CAT-002 | Tambah katalog | buat record | Katalog > Tambah Katalog | admin.catalog.records.store | BibliographicRecordController@store | StoreBibliographicRecordRequest | BibliographicRecordService | bibliographic_records, relasi author dan subject | modules/catalog/records/create.blade.php, _form.blade.php | catalog.records.create, BibliographicRecordPolicy | create_record | VAL_422_INVALID_INPUT, VAL_422_INVALID_FOREIGN_KEY | TS-CAT-001 | linked |
| TM-CAT-003 | Ubah katalog | update record | Katalog > Daftar Katalog | admin.catalog.records.update | BibliographicRecordController@update | UpdateBibliographicRecordRequest | BibliographicRecordService | bibliographic_records, relasi author dan subject | modules/catalog/records/edit.blade.php, _form.blade.php | catalog.records.update, BibliographicRecordPolicy | update_record | VAL_422_INVALID_INPUT | TS-CAT-005 implisit update relasi | linked |
| TM-CAT-004 | Publish katalog | publish record | Katalog > Daftar Katalog | admin.catalog.records.publish | BibliographicRecordController@publish | PublishBibliographicRecordRequest | BibliographicRecordPublishService | bibliographic_records | modules/catalog/records/index.blade.php | catalog.records.publish, BibliographicRecordPolicy | publish_record | BUS_409_RECORD_PUBLISH_NOT_ALLOWED, BUS_409_RECORD_ALREADY_PUBLISHED | TS-CAT-002, TS-CAT-003 | linked |
| TM-CAT-005 | Unpublish katalog | unpublish record | Katalog > Daftar Katalog | admin.catalog.records.unpublish | BibliographicRecordController@unpublish | UnpublishBibliographicRecordRequest | BibliographicRecordPublishService | bibliographic_records | modules/catalog/records/index.blade.php | catalog.records.unpublish, BibliographicRecordPolicy | unpublish_record | BUS_409_STATE_TRANSITION_INVALID | TS-CAT-004 | linked |
| TM-CAT-006 | Arsipkan katalog | archive record | Katalog > Daftar Katalog | admin.catalog.records.archive | BibliographicRecordController@archive | request aksi archive | BibliographicRecordPublishService | bibliographic_records | modules/catalog/records/index.blade.php | catalog.records.archive, BibliographicRecordPolicy | archive_record | BUS_409_STATE_TRANSITION_INVALID | TS regression lanjutan | linked |
| TM-CAT-007 | Detail katalog admin | lihat detail record | Katalog > Daftar Katalog | admin.catalog.records.show | BibliographicRecordController@show | tidak wajib | BibliographicRecordService | bibliographic_records, physical_items, digital_assets | modules/catalog/records/show.blade.php | catalog.records.view, BibliographicRecordPolicy | tidak wajib | RES_404_RECORD_NOT_FOUND | TS-CAT-001 follow up | linked |

## 17. Matriks Traceability COLLECTION

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-COLL-001 | List item fisik | lihat item | Koleksi > Item Fisik | admin.collection.items.index | PhysicalItemController@index | PhysicalItemFilterRequest | PhysicalItemService, PhysicalItemIndexQuery | physical_items | modules/collection/items/index.blade.php | collection.items.view, PhysicalItemPolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-COLL-001 sebagian | linked |
| TM-COLL-002 | Tambah item fisik | tambah item | Koleksi > Tambah Item | admin.collection.items.store | PhysicalItemController@store | StorePhysicalItemRequest | PhysicalItemService | physical_items | modules/collection/items/create.blade.php, _form.blade.php | collection.items.create, PhysicalItemPolicy | create_item | VAL_422_DUPLICATE_VALUE, VAL_422_INVALID_FOREIGN_KEY | TS-COLL-001, TS-COLL-002 | linked |
| TM-COLL-003 | Ubah item fisik | update item | Koleksi > Item Fisik | admin.collection.items.update | PhysicalItemController@update | UpdatePhysicalItemRequest | PhysicalItemService | physical_items | modules/collection/items/edit.blade.php, _form.blade.php | collection.items.update, PhysicalItemPolicy | update_item | VAL_422_INVALID_INPUT | TS-COLL update analog | linked |
| TM-COLL-004 | Ubah status item | change item status | Koleksi > Item Fisik | admin.collection.items.change_status | PhysicalItemController@changeStatus | ChangePhysicalItemStatusRequest | PhysicalItemStatusService | physical_items, physical_item_status_histories | modules/collection/items/index.blade.php, _status_modal.blade.php | collection.items.change_status, PhysicalItemPolicy | change_item_status | BUS_409_ITEM_STATUS_CHANGE_NOT_ALLOWED | TS-COLL-003, TS-COLL-004 | linked |
| TM-COLL-005 | Lihat detail item | detail item | Koleksi > Item Fisik | admin.collection.items.show | PhysicalItemController@show | tidak wajib | PhysicalItemService | physical_items, physical_item_status_histories | modules/collection/items/show.blade.php | collection.items.view, PhysicalItemPolicy | tidak wajib | RES_404_ITEM_NOT_FOUND | TS-COLL follow up | linked |

## 18. Matriks Traceability MEMBER

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-MEMBER-001 | List anggota | lihat anggota | Anggota > Daftar Anggota | admin.members.index | MemberController@index | MemberFilterRequest | MemberService | members, faculties, study_programs | modules/member/members/index.blade.php | members.view, MemberPolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-MEMBER-001 sebagian | linked |
| TM-MEMBER-002 | Tambah anggota | buat anggota | Anggota > Tambah Anggota | admin.members.store | MemberController@store | StoreMemberRequest | MemberService | members | modules/member/members/create.blade.php, _form.blade.php | members.create, MemberPolicy | create_member | VAL_422_DUPLICATE_VALUE, VAL_422_INVALID_FOREIGN_KEY | TS-MEMBER-001 | linked |
| TM-MEMBER-003 | Ubah anggota | update anggota | Anggota > Daftar Anggota | admin.members.update | MemberController@update | UpdateMemberRequest | MemberService | members | modules/member/members/edit.blade.php, _form.blade.php | members.update, MemberPolicy | update_member | VAL_422_INVALID_INPUT | TS-MEMBER update analog | linked |
| TM-MEMBER-004 | Block anggota | blokir anggota | Anggota > Daftar Anggota | admin.members.block | MemberController@block | UpdateMemberBlockStatusRequest | MemberBlockingService | members | modules/member/members/index.blade.php, _block_modal.blade.php | members.block, MemberPolicy | block_member | BUS_409_STATE_TRANSITION_INVALID | TS-MEMBER-002 | linked |
| TM-MEMBER-005 | Unblock anggota | buka blokir | Anggota > Daftar Anggota | admin.members.unblock | MemberController@unblock | UpdateMemberBlockStatusRequest | MemberBlockingService | members | modules/member/members/index.blade.php, _block_modal.blade.php | members.unblock, MemberPolicy | unblock_member | BUS_409_STATE_TRANSITION_INVALID | TS-MEMBER-003 | linked |
| TM-MEMBER-006 | Detail anggota | lihat detail anggota | Anggota > Daftar Anggota | admin.members.show | MemberController@show | tidak wajib | MemberService | members, loans, fines | modules/member/members/show.blade.php | members.view, MemberPolicy | tidak wajib | RES_404_MEMBER_NOT_FOUND | TS-MEMBER follow up | linked |

## 19. Matriks Traceability CIRCULATION

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-CIRC-001 | List pinjaman | lihat transaksi pinjam | Sirkulasi > Peminjaman | admin.circulation.loans.index | LoanController@index | LoanFilterRequest | LoanTransactionService atau query terkait | loans, members, physical_items | modules/reporting atau modules sirkulasi bila ada daftar operasional | loans.view, LoanPolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-CIRC list analog | linked |
| TM-CIRC-002 | Buat peminjaman | proses pinjam | Sirkulasi > Peminjaman Baru | admin.circulation.loans.store | LoanController@store | StoreLoanRequest | LoanTransactionService | loans, physical_items, members | modules sirkulasi operasional atau modal transaksi | loans.create, LoanPolicy | create_loan | BUS_409_MEMBER_BLOCKED, BUS_409_ITEM_NOT_AVAILABLE, BUS_409_MEMBER_LOAN_LIMIT_REACHED | TS-CIRC-001, TS-CIRC-002, TS-CIRC-003 | linked |
| TM-CIRC-003 | Proses pengembalian | return loan | Sirkulasi > Pengembalian | admin.circulation.returns.store | ReturnTransactionController@store | ReturnLoanRequest | ReturnProcessingService, FineCalculationService | loans, return_transactions, fines, physical_items | view operasional pengembalian | loans.return, LoanPolicy | return_loan, create_fine | BUS_409_RETURN_NOT_ALLOWED | TS-CIRC-004, TS-CIRC-005, TS-CIRC-008 | linked |
| TM-CIRC-004 | Perpanjang pinjaman | renew loan | Sirkulasi > Peminjaman | admin.circulation.loans.renew | LoanRenewalController@store | RenewLoanRequest | LoanRenewalService | loans, loan_renewals | view operasional pinjaman | loans.renew, LoanPolicy | renew_loan | BUS_409_RENEWAL_NOT_ALLOWED | TS-CIRC-006, TS-CIRC-007 | linked |
| TM-CIRC-005 | Lihat denda | kelola denda | Sirkulasi > Denda | admin.circulation.fines.index | FineController@index | FineFilterRequest | FineCalculationService dan query | fines, loans, members | daftar denda operasional atau report terkait | fines.view, FinePolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-CIRC-005, TS-RPT-004 | linked |

## 20. Matriks Traceability DIGITAL REPOSITORY

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-DIG-001 | List asset digital | lihat asset | Repositori Digital > Asset | admin.digital.assets.index | DigitalAssetController@index | filter asset | DigitalAssetService, DigitalAssetIndexQuery | digital_assets, bibliographic_records | modules/digital_repository/assets/index.blade.php | digital_assets.view, DigitalAssetPolicy | tidak wajib | RPT_500_QUERY_FAILED | TS-DIG-001 sebagian | linked |
| TM-DIG-002 | Upload asset digital | upload PDF | Repositori Digital > Upload Asset | admin.digital.assets.store | DigitalAssetController@store | StoreDigitalAssetRequest | DigitalAssetUploadService, DigitalAssetService | digital_assets, storage private | modules/digital_repository/assets/create.blade.php,_form.blade.php | digital_assets.create, DigitalAssetPolicy | upload_asset | FILE_422_INVALID_TYPE, FILE_500_STORAGE_WRITE_FAILED | TS-DIG-001, TS-DIG-002 | linked |
| TM-DIG-003 | Update asset digital | edit metadata asset | Repositori Digital > Asset | admin.digital.assets.update | DigitalAssetController@update | UpdateDigitalAssetRequest | DigitalAssetService | digital_assets | modules/digital_repository/assets/edit.blade.php,_form.blade.php | digital_assets.update, DigitalAssetPolicy | update_asset | VAL_422_INVALID_INPUT | TS-DIG update analog | linked |
| TM-DIG-004 | Publish asset | publish asset digital | Repositori Digital > Asset | admin.digital.assets.publish | DigitalAssetController@publish | request publish asset | DigitalAssetService | digital_assets | modules/digital_repository/assets/index.blade.php | digital_assets.publish, DigitalAssetPolicy | publish_asset | BUS_409_ASSET_PUBLISH_NOT_ALLOWED | TS-DIG-003 | linked |
| TM-DIG-005 | Unpublish asset | unpublish asset | Repositori Digital > Asset | admin.digital.assets.unpublish | DigitalAssetController@unpublish | request unpublish asset | DigitalAssetService | digital_assets | modules/digital_repository/assets/index.blade.php | digital_assets.unpublish, DigitalAssetPolicy | unpublish_asset | BUS_409_STATE_TRANSITION_INVALID | TS-DIG unpublish analog | linked |
| TM-DIG-006 | Preview privat internal | preview file internal | Repositori Digital > Asset | admin.digital.assets.preview | AssetPreviewController@show | tidak wajib | DigitalAssetAccessService, AssetStreamingService | digital_assets, digital_asset_access_rules, storage private | modules/digital_repository/assets/preview.blade.php | digital_assets.preview_private, DigitalAssetPolicy | opsional access event | SEC_403_PRIVATE_ASSET_ACCESS_DENIED, FILE_404_NOT_FOUND | TS-DIG-004, TS-DIG-005 | linked |
| TM-DIG-007 | Kelola access rule | atur hak akses asset | Repositori Digital > Asset | admin.digital.assets.access_rules.store, update | DigitalAssetAccessRuleController@store, update | StoreDigitalAssetAccessRuleRequest, UpdateDigitalAssetAccessRuleRequest | DigitalAssetAccessService | digital_asset_access_rules | modules/digital_repository/assets/show.blade.php,_access_rule_form.blade.php | digital_assets.access_rules.manage, DigitalAssetAccessRulePolicy | update_asset_access | VAL_422_INVALID_INPUT | TS-DIG access analog | linked |
| TM-DIG-008 | Run OCR | jalankan OCR | Repositori Digital > Asset | admin.digital.assets.run_ocr | DigitalAssetController@runOcr | RunDigitalAssetOcrRequest | OcrProcessingService | digital_assets, ocr_texts, jobs | modules/digital_repository/assets/show.blade.php,_ocr_status_card.blade.php | digital_assets.run_ocr, DigitalAssetPolicy | run_ocr | OCR_409_ALREADY_QUEUED, OCR_500_PROCESS_FAILED | TS-OCR-001 | linked |
| TM-DIG-009 | Retry OCR | ulang OCR | Repositori Digital > Asset | admin.digital.assets.retry_ocr | DigitalAssetController@retryOcr | RunDigitalAssetOcrRequest | OcrProcessingService | digital_assets, ocr_texts | modules/digital_repository/assets/show.blade.php,_ocr_status_card.blade.php | digital_assets.run_ocr, DigitalAssetPolicy | retry_ocr | OCR_500_PROCESS_FAILED | TS-OCR-004 | linked |
| TM-DIG-010 | Reindex asset atau record | jalankan reindex | Repositori Digital > Asset atau Katalog | admin.digital.assets.reindex | DigitalAssetController@reindex | ReindexDigitalAssetRequest | SearchIndexService atau DigitalAssetService | digital_assets, bibliographic_records | modules/digital_repository/assets/show.blade.php | digital_assets.reindex, DigitalAssetPolicy | run_reindex | IDX_409_ALREADY_QUEUED, IDX_500_REINDEX_FAILED | TS-IDX-001 | linked |

## 21. Matriks Traceability OPAC

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-OPAC-001 | Beranda OPAC | buka beranda publik | OPAC Home | opac.home | OpacHomeController@index | tidak wajib | OpacBrowseService | institution_profiles, public records summary | opac/home.blade.php | publik | tidak wajib | SYS_500_UNEXPECTED_ERROR | TS-OPAC-001 | linked |
| TM-OPAC-002 | Search OPAC | cari koleksi publik | Pencarian | opac.search | OpacSearchController@index | OpacSearchRequest | OpacSearchService | meilisearch plus bibliographic_records hydration | opac/search/index.blade.php | publik | tidak wajib | OPAC_404_EMPTY_SEARCH_RESULT, OPAC_503_SEARCH_UNAVAILABLE | TS-OPAC-002, TS-OPAC-003, TS-OPAC-006 | linked |
| TM-OPAC-003 | Detail record publik | lihat detail record | Hasil Pencarian | opac.records.show | OpacRecordController@show | tidak wajib | OpacBrowseService, OpacHydrationQuery | bibliographic_records, physical_items, digital_assets | opac/records/show.blade.php | publik filtered | tidak wajib | OPAC_404_RECORD_NOT_PUBLIC | TS-OPAC-004 | linked |
| TM-OPAC-004 | Preview asset publik | preview publik | Detail Record Publik | opac.assets.preview | PublicAssetPreviewController@show | tidak wajib | PublicAssetPreviewService, DigitalAssetAccessService, AssetStreamingService | digital_assets, digital_asset_access_rules | PDF.js viewer dan route preview | publik filtered | tidak wajib | OPAC_404_ASSET_PREVIEW_NOT_PUBLIC, BUS_409_ASSET_EMBARGO_ACTIVE | TS-OPAC-005, TS-DIG-006 | linked |
| TM-OPAC-005 | Suggestion publik | suggestion search publik | Search box | api.public.opac.suggestion atau route publik terkait | OpacSearchController@suggest atau controller suggestion | PublicSuggestionRequest | OpacSuggestionBuilder | meilisearch plus public filters | tidak wajib view terpisah | publik rate limited | tidak wajib | OPAC_503_SEARCH_UNAVAILABLE | TS-API-003 | partial kecil |

## 22. Matriks Traceability REPORTING

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-RPT-001 | Dashboard laporan | lihat ringkasan statistik | Laporan > Dashboard | admin.reports.dashboard | DashboardReportController@index | DashboardReportFilterRequest | ReportingDashboardService, DashboardMetricsQuery | bibliographic_records, physical_items, members, loans, fines, digital_assets | modules/reporting/dashboard.blade.php | reports.view_dashboard | tidak wajib | RPT_500_AGGREGATION_FAILED | TS-RPT-001 | linked |
| TM-RPT-002 | Laporan koleksi | lihat laporan koleksi | Laporan > Koleksi | admin.reports.collections.index | CollectionReportController@index | CollectionReportFilterRequest | CollectionReportService, CollectionReportQuery | bibliographic_records dan relasi terkait | modules/reporting/collections/index.blade.php | reports.view_collections | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT-002 | linked |
| TM-RPT-003 | Laporan anggota | lihat laporan anggota | Laporan > Anggota | admin.reports.members.index | MemberReportController@index | MemberReportFilterRequest | MemberReportService, MemberReportQuery | members, faculties, study_programs | modules/reporting/members/index.blade.php | reports.view_members | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT member analog | linked |
| TM-RPT-004 | Laporan sirkulasi | lihat laporan sirkulasi | Laporan > Sirkulasi | admin.reports.circulation.index | CirculationReportController@index | CirculationReportFilterRequest | CirculationReportService, CirculationReportQuery | loans, members, physical_items, return_transactions | modules/reporting/circulation/index.blade.php | reports.view_circulation | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT-003 | linked |
| TM-RPT-005 | Laporan denda | lihat laporan denda | Laporan > Denda | admin.reports.fines.index | FineReportController@index | FineReportFilterRequest | FineReportService, FineReportQuery | fines, loans, members | modules/reporting/fines/index.blade.php | reports.view_fines | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT-004 | linked |
| TM-RPT-006 | Laporan koleksi populer | lihat ranking populer | Laporan > Koleksi Populer | admin.reports.popular_collections.index | PopularCollectionReportController@index | PopularCollectionReportFilterRequest | PopularCollectionReportService, PopularCollectionQuery | loans, physical_items, bibliographic_records | modules/reporting/popular_collections/index.blade.php | reports.view_popular_collections | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT-005 | linked |
| TM-RPT-007 | Laporan digital repository | lihat readiness aset digital | Laporan > Digital Repository | admin.reports.digital_access.index | DigitalAccessReportController@index | DigitalAccessReportFilterRequest | DigitalAccessReportService, DigitalAccessReportQuery | digital_assets, ocr_texts, bibliographic_records | modules/reporting/digital_access/index.blade.php | reports.view_digital_access | tidak wajib | RPT_422_INVALID_FILTER, RPT_500_QUERY_FAILED | TS-RPT-006 | linked |

## 23. Matriks Traceability IMPORT

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-IMP-001 | Halaman import anggota | buka import anggota | Anggota > Import Anggota | admin.members.import.index | MemberImportController@index | tidak wajib | MemberImportService, MemberImportTemplateDefinition | members, faculties, study_programs | modules/member/imports/index.blade.php | members.import | tidak wajib | SEC_403_PERMISSION_DENIED | TS-IMP-004 | linked |
| TM-IMP-002 | Download template import | unduh template | Anggota > Import Anggota | admin.members.import.template | MemberImportController@downloadTemplate | tidak wajib | MemberImportTemplateDefinition | tidak langsung ke tabel | modules/member/imports/index.blade.php | members.import | tidak wajib | RES_404_NOT_FOUND | TS manual template | linked |
| TM-IMP-003 | Proses import anggota | import massal anggota | Anggota > Import Anggota | admin.members.import.store | MemberImportController@store | MemberImportRequest | MemberImportService, MemberImportRowValidator | members, faculties, study_programs | modules/member/imports/index.blade.php, _result_summary.blade.php | members.import | import_members | IMP_422_INVALID_HEADER, IMP_409_DUPLICATE_IN_DATABASE, IMP_500_PROCESS_FAILED | TS-IMP-001, TS-IMP-002, TS-IMP-003 | linked |

## 24. Matriks Traceability EXPORT

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-EXP-001 | Export laporan koleksi | unduh laporan koleksi | Laporan > Koleksi | admin.reports.collections.export | CollectionReportController@export | CollectionReportExportRequest | ReportExportService, CollectionReportService, CollectionReportExporter | bibliographic_records | modules/reporting/collections/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP-001 | linked |
| TM-EXP-002 | Export laporan anggota | unduh laporan anggota | Laporan > Anggota | admin.reports.members.export | MemberReportController@export | MemberReportExportRequest | ReportExportService, MemberReportService, MemberReportExporter | members | modules/reporting/members/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP-001 | linked |
| TM-EXP-003 | Export laporan sirkulasi | unduh laporan sirkulasi | Laporan > Sirkulasi | admin.reports.circulation.export | CirculationReportController@export | CirculationReportExportRequest | ReportExportService, CirculationReportService, CirculationReportExporter | loans, members, physical_items | modules/reporting/circulation/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP general | linked |
| TM-EXP-004 | Export laporan denda | unduh laporan denda | Laporan > Denda | admin.reports.fines.export | FineReportController@export | FineReportExportRequest | ReportExportService, FineReportService, FineReportExporter | fines | modules/reporting/fines/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP general | linked |
| TM-EXP-005 | Export koleksi populer | unduh ranking populer | Laporan > Koleksi Populer | admin.reports.popular_collections.export | PopularCollectionReportController@export | PopularCollectionReportExportRequest | ReportExportService, PopularCollectionReportService, PopularCollectionReportExporter | loans, physical_items, bibliographic_records | modules/reporting/popular_collections/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP general | linked |
| TM-EXP-006 | Export digital repository | unduh laporan digital | Laporan > Digital Repository | admin.reports.digital_access.export | DigitalAccessReportController@export | DigitalAccessReportExportRequest | ReportExportService, DigitalAccessReportService, DigitalAccessReportExporter | digital_assets, ocr_texts | modules/reporting/digital_access/index.blade.php | reports.export | export_report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED | TS-EXP-003 | linked |

## 25. Matriks Traceability AUDIT

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-AUDIT-001 | Lihat daftar audit | review aktivitas | Audit > Log Aktivitas | admin.audit.logs.index | AuditLogController@index | AuditLogIndexRequest | AuditLogService, AuditLogQuery | activity_logs | modules/audit/logs/index.blade.php | audit_logs.view, ActivityLogPolicy | tidak berlaku karena ini viewer | SEC_403_PERMISSION_DENIED | TS-AUDIT-003 | linked |
| TM-AUDIT-002 | Lihat detail audit | detail aktivitas | Audit > Log Aktivitas | admin.audit.logs.show | AuditLogController@show | tidak wajib | AuditLogService | activity_logs | modules/audit/logs/show.blade.php | audit_logs.view_detail, ActivityLogPolicy | tidak berlaku | RES_404_NOT_FOUND, SEC_403_PERMISSION_DENIED | TS-AUDIT-002, TS-AUDIT-003 | linked |
| TM-AUDIT-003 | Catat aksi sensitif | audit domain | tersebar | dipicu service | AuditLogService@record | tidak langsung request | AuditLogService | activity_logs | tidak langsung view | sesuai aksi domain | create_user, publish_record, block_member, create_loan, upload_asset, export_report, import_members dan lain lain | tidak langsung | TS-AUDIT-001, TS-ERR-001 | linked |

## 26. Matriks Traceability API INTERNAL

| Kode | Fitur atau Aksi | Use Case | Menu | Route Name | Controller Method | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-API-001 | Lookup member | autocomplete anggota | dipakai form pinjam | api.internal.lookup.members | lookup controller | request lookup | MemberService | members | tidak wajib | members.view atau loans.create context | tidak wajib | API_422_INVALID_PAYLOAD, AUTH_401_UNAUTHENTICATED | TS-API-001, TS-API-002 | linked |
| TM-API-002 | Lookup bibliographic record | autocomplete record | dipakai form item atau asset | api.internal.lookup.records | lookup controller | request lookup | BibliographicRecordService | bibliographic_records | tidak wajib | catalog.records.view | tidak wajib | API_422_INVALID_PAYLOAD | TS-API analog | linked |
| TM-API-003 | Item availability summary | cek ketersediaan | dipakai sirkulasi dan OPAC internal | api.internal.collection.item_availability | controller internal | request terkait | ItemAvailabilityQuery, PhysicalItemService | physical_items | tidak wajib | collection.items.view | tidak wajib | RES_404_ITEM_NOT_FOUND | TS integration | linked |
| TM-API-004 | Member eligibility summary | cek kelayakan pinjam | dipakai sirkulasi | api.internal.members.eligibility | controller internal | request terkait | LoanEligibilityService | members, loans | tidak wajib | loans.create | tidak wajib | BUS_409_MEMBER_BLOCKED | TS integration | linked |
| TM-API-005 | Dispatch OCR internal | trigger OCR | dipakai detail asset | api.internal.digital_assets.run_ocr | controller internal | RunDigitalAssetOcrRequest | OcrProcessingService | digital_assets | tidak wajib | digital_assets.run_ocr | run_ocr | OCR_409_ALREADY_QUEUED | TS-OCR-001 | linked |
| TM-API-006 | Suggestion publik | suggestion pencarian | OPAC search box | api.public.opac.suggestion | OpacSearchController@suggest | PublicSuggestionRequest | OpacSuggestionBuilder | meilisearch plus public hydration | tidak wajib | publik rate limited | tidak wajib | OPAC_503_SEARCH_UNAVAILABLE | TS-API-003 | partial kecil |

## 27. Matriks Traceability INTEGRATION

| Kode | Fitur atau Aksi | Use Case | Menu | Route atau Job | Controller atau Trigger | Request | Service Utama | Model atau Tabel | View | Permission atau Policy | Audit Event | Error Code Utama | Test Scenario | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TM-INT-001 | Publish record memicu reindex | sinkronisasi katalog ke search | Katalog | job reindex record | BibliographicRecordController@publish trigger | PublishBibliographicRecordRequest | BibliographicRecordPublishService, SearchIndexService | bibliographic_records | admin index, OPAC search | catalog.records.publish | publish_record, run_reindex | IDX_500_REINDEX_FAILED | TS-CAT-002, TS-INT-002 | linked |
| TM-INT-002 | Loan mengubah item status | integrasi sirkulasi dan koleksi | Sirkulasi | admin.circulation.loans.store | LoanController@store | StoreLoanRequest | LoanTransactionService, PhysicalItemStatusService | loans, physical_items | transaksi sirkulasi | loans.create | create_loan, change_item_status | BUS_409_ITEM_NOT_AVAILABLE | TS-CIRC-001, TS-INT-001 | linked |
| TM-INT-003 | Return membuat fine bila overdue | integrasi sirkulasi dan denda | Sirkulasi | admin.circulation.returns.store | ReturnTransactionController@store | ReturnLoanRequest | ReturnProcessingService, FineCalculationService | loans, return_transactions, fines | transaksi pengembalian | loans.return | return_loan, create_fine | BUS_409_RETURN_NOT_ALLOWED | TS-CIRC-005 | linked |
| TM-INT-004 | Upload asset memicu OCR | integrasi storage dan OCR | Repositori Digital | admin.digital.assets.store plus OCR job | DigitalAssetController@store | StoreDigitalAssetRequest | DigitalAssetUploadService, OcrProcessingService | digital_assets, ocr_texts, storage private | create asset view | digital_assets.create | upload_asset, run_ocr | FILE_500_STORAGE_WRITE_FAILED, OCR_500_PROCESS_FAILED | TS-DIG-001, TS-OCR-001 | linked |
| TM-INT-005 | OCR success memicu reindex | integrasi OCR dan search | background | ProcessDigitalAssetOcrJob | job trigger | tidak berlaku | OcrProcessingService, SearchIndexService | ocr_texts, digital_assets, bibliographic_records | tidak wajib | worker internal | retry_ocr atau run_reindex bila manual, log teknis bila otomatis | IDX_500_REINDEX_FAILED | TS-OCR-002, TS-INT-002 | linked |
| TM-INT-006 | Export report membuat file temp aman | integrasi reporting dan storage | Laporan | export route plus BuildReportExportJob opsional | report controller export | export request | ReportExportService | report_export_histories, storage private | halaman report asal | reports.export | export_report | EXP_500_BUILD_FAILED, FILE_500_STORAGE_WRITE_FAILED | TS-EXP-001, TS-INT-003 | linked |
| TM-INT-007 | Import anggota memetakan faculty dan study program | integrasi member dan master data | Anggota > Import | admin.members.import.store | MemberImportController@store | MemberImportRequest | MemberImportService, FacultyService, StudyProgramService | members, faculties, study_programs | modules/member/imports/index.blade.php | members.import | import_members | IMP_422_INVALID_REFERENCE_CODE | TS-IMP-001, TS-IMP-003 | linked |

## 28. Matriks Traceability Use Case ke Menu Utama

| Use Case Utama | Menu Utama | Modul |
|---|---|---|
| Login admin | Login | AUTH |
| Kelola pengguna | Identity > Users | AUTH, ACCESS |
| Kelola role permission | Identity > Roles | AUTH, ACCESS |
| Kelola profil institusi | Core > Profil Institusi | CORE |
| Kelola pengaturan sistem | Core > Pengaturan Sistem | CORE |
| Kelola master data | Master Data | MASTER |
| Kelola bibliographic record | Katalog | CATALOG |
| Kelola item fisik | Koleksi > Item Fisik | COLLECTION |
| Kelola anggota | Anggota | MEMBER |
| Import anggota | Anggota > Import Anggota | IMPORT |
| Proses pinjam kembali perpanjang | Sirkulasi | CIRCULATION |
| Kelola asset digital | Repositori Digital | DIGITAL |
| Preview publik dan privat | OPAC dan Repositori Digital | DIGITAL, OPAC |
| Lihat laporan | Laporan | REPORT |
| Export laporan | Laporan | EXPORT |
| Lihat audit | Audit > Log Aktivitas | AUDIT |
| Cari koleksi publik | OPAC | OPAC |

## 29. Matriks Traceability Permission ke Fitur Kritis

| Permission | Fitur Kritis Terkait | Modul |
|---|---|---|
| users.view | list user, detail user | AUTH |
| users.create | tambah user | AUTH |
| users.update | update user | AUTH |
| users.reset_password | reset password | AUTH |
| roles.view | list role | ACCESS |
| roles.update_permissions | ubah permission role | ACCESS |
| institution_profile.update | ubah profil institusi | CORE |
| system_settings.update | ubah setting operasional | CORE |
| catalog.records.view | lihat katalog admin | CATALOG |
| catalog.records.create | tambah katalog | CATALOG |
| catalog.records.update | ubah katalog | CATALOG |
| catalog.records.publish | publish katalog | CATALOG |
| catalog.records.unpublish | unpublish katalog | CATALOG |
| collection.items.view | lihat item | COLLECTION |
| collection.items.create | tambah item | COLLECTION |
| collection.items.update | ubah item | COLLECTION |
| collection.items.change_status | ubah status item | COLLECTION |
| members.view | lihat anggota | MEMBER |
| members.create | tambah anggota | MEMBER |
| members.update | ubah anggota | MEMBER |
| members.block | block atau unblock anggota | MEMBER |
| members.import | import anggota | IMPORT |
| loans.view | lihat pinjaman | CIRCULATION |
| loans.create | proses pinjam | CIRCULATION |
| loans.return | proses kembali | CIRCULATION |
| loans.renew | perpanjang pinjaman | CIRCULATION |
| digital_assets.view | lihat asset | DIGITAL |
| digital_assets.create | upload asset | DIGITAL |
| digital_assets.update | ubah asset | DIGITAL |
| digital_assets.publish | publish asset | DIGITAL |
| digital_assets.preview_private | preview privat | DIGITAL |
| digital_assets.run_ocr | jalankan OCR | DIGITAL |
| digital_assets.access_rules.manage | kelola access rule | DIGITAL |
| reports.view_dashboard | lihat dashboard report | REPORT |
| reports.view_collections | lihat laporan koleksi | REPORT |
| reports.view_members | lihat laporan anggota | REPORT |
| reports.view_circulation | lihat laporan sirkulasi | REPORT |
| reports.view_fines | lihat laporan denda | REPORT |
| reports.view_popular_collections | lihat laporan populer | REPORT |
| reports.view_digital_access | lihat laporan digital | REPORT |
| reports.export | export report | EXPORT |
| audit_logs.view | lihat audit list | AUDIT |
| audit_logs.view_detail | lihat detail audit | AUDIT |

## 30. Matriks Traceability Aksi Sensitif ke Audit Event

| Aksi Sensitif | Audit Event |
|---|---|
| reset password user | reset_user_password |
| update role permission | update_role_permissions |
| update institution profile | update_institution_profile |
| update system setting | update_system_setting |
| create bibliographic record | create_record |
| update bibliographic record | update_record |
| publish record | publish_record |
| unpublish record | unpublish_record |
| archive record | archive_record |
| create physical item | create_item |
| update physical item | update_item |
| change item status | change_item_status |
| create member | create_member |
| update member | update_member |
| block member | block_member |
| unblock member | unblock_member |
| create loan | create_loan |
| return loan | return_loan |
| renew loan | renew_loan |
| create fine | create_fine |
| upload asset | upload_asset |
| update asset | update_asset |
| publish asset | publish_asset |
| unpublish asset | unpublish_asset |
| update asset access rule | update_asset_access |
| run OCR | run_ocr |
| retry OCR | retry_ocr |
| run reindex | run_reindex |
| import anggota | import_members |
| export report | export_report |
| retry queue job | retry_queue_job |

## 31. Matriks Traceability Aksi Sensitif ke Error Code Dominan

| Aksi | Error Code Dominan |
|---|---|
| login | AUTH_401_INVALID_CREDENTIALS, AUTH_403_ACCOUNT_INACTIVE |
| reset password | AUTH_403_PASSWORD_RESET_NOT_ALLOWED, SEC_403_PERMISSION_DENIED |
| create record | VAL_422_INVALID_INPUT, VAL_422_INVALID_FOREIGN_KEY |
| publish record | BUS_409_RECORD_PUBLISH_NOT_ALLOWED |
| create item | VAL_422_DUPLICATE_VALUE, VAL_422_INVALID_FOREIGN_KEY |
| change item status | BUS_409_ITEM_STATUS_CHANGE_NOT_ALLOWED |
| create member | VAL_422_DUPLICATE_VALUE |
| block member | BUS_409_STATE_TRANSITION_INVALID |
| create loan | BUS_409_MEMBER_BLOCKED, BUS_409_ITEM_NOT_AVAILABLE, BUS_409_MEMBER_LOAN_LIMIT_REACHED |
| return loan | BUS_409_RETURN_NOT_ALLOWED |
| renew loan | BUS_409_RENEWAL_NOT_ALLOWED |
| upload asset | FILE_422_INVALID_TYPE, FILE_500_STORAGE_WRITE_FAILED |
| preview private asset | SEC_403_PRIVATE_ASSET_ACCESS_DENIED, FILE_404_NOT_FOUND |
| run OCR | OCR_409_ALREADY_QUEUED, OCR_500_PROCESS_FAILED |
| reindex | IDX_409_ALREADY_QUEUED, IDX_500_REINDEX_FAILED |
| import members | IMP_422_INVALID_HEADER, IMP_409_DUPLICATE_IN_DATABASE |
| export report | EXP_403_NOT_ALLOWED, EXP_500_BUILD_FAILED |
| view audit | SEC_403_PERMISSION_DENIED |

## 32. Matriks Traceability Aksi ke Test Scenario Utama

| Aksi | Test Scenario Utama |
|---|---|
| login sukses | TS-AUTH-001 |
| login gagal | TS-AUTH-002 |
| akun inactive ditolak | TS-AUTH-003 |
| reset password | TS-AUTH-005 |
| menu sesuai role | TS-ACCESS-001 |
| route sensitif ditolak | TS-ACCESS-002 |
| update institution profile | TS-CORE-001 |
| update system settings | TS-CORE-002 |
| create author | TS-MASTER-001 |
| duplicate master data ditolak | TS-MASTER-002 |
| master data in use ditolak hapus | TS-MASTER-003 |
| create draft record | TS-CAT-001 |
| publish record | TS-CAT-002 |
| publish record ditolak jika belum lengkap | TS-CAT-003 |
| unpublish record | TS-CAT-004 |
| update author relasi dan reindex | TS-CAT-005 |
| create item | TS-COLL-001 |
| duplicate barcode ditolak | TS-COLL-002 |
| change item status | TS-COLL-003 |
| invalid item status transition ditolak | TS-COLL-004 |
| create member | TS-MEMBER-001 |
| block member | TS-MEMBER-002 |
| unblock member | TS-MEMBER-003 |
| guest member tanpa prodi | TS-MEMBER-004 |
| create loan | TS-CIRC-001 |
| loan ditolak member blocked | TS-CIRC-002 |
| loan ditolak item unavailable | TS-CIRC-003 |
| return normal | TS-CIRC-004 |
| return dengan fine | TS-CIRC-005 |
| renew loan | TS-CIRC-006 |
| renew ditolak | TS-CIRC-007 |
| upload asset | TS-DIG-001 |
| upload non pdf ditolak | TS-DIG-002 |
| publish asset | TS-DIG-003 |
| preview private asset | TS-DIG-004 |
| preview private asset denied | TS-DIG-005 |
| embargo preview publik ditolak | TS-DIG-006 |
| run OCR | TS-OCR-001 |
| OCR success | TS-OCR-002 |
| OCR failed | TS-OCR-003 |
| retry OCR | TS-OCR-004 |
| reindex manual | TS-IDX-001 |
| OPAC home | TS-OPAC-001 |
| OPAC search public | TS-OPAC-002 |
| OPAC hide non public | TS-OPAC-003 |
| OPAC detail | TS-OPAC-004 |
| OPAC public preview | TS-OPAC-005 |
| OPAC empty state | TS-OPAC-006 |
| report dashboard | TS-RPT-001 |
| report collection filter | TS-RPT-002 |
| report circulation overdue | TS-RPT-003 |
| report fine nominal | TS-RPT-004 |
| report popular collection | TS-RPT-005 |
| report digital repository | TS-RPT-006 |
| import members valid | TS-IMP-001 |
| import invalid header | TS-IMP-002 |
| import partial success | TS-IMP-003 |
| import denied | TS-IMP-004 |
| export members | TS-EXP-001 |
| export denied | TS-EXP-002 |
| export digital safe fields | TS-EXP-003 |
| audit publish record | TS-AUDIT-001 |
| audit no sensitive data | TS-AUDIT-002 |
| audit access denied | TS-AUDIT-003 |
| api lookup member | TS-API-001 |
| api internal denied without auth | TS-API-002 |
| public suggestion safe | TS-API-003 |
| error code member blocked | TS-ERR-001 |
| file missing handled safely | TS-ERR-002 |
| csrf protection | TS-SEC-001 |
| private asset guessed URL denied | TS-SEC-002 |
| public error no stack trace | TS-SEC-003 |
| loan integration with item status | TS-INT-001 |
| OCR public vs private search visibility | TS-INT-002 |
| export storage policy | TS-INT-003 |

## 33. Matriks Traceability Model ke Tabel

| Model | Tabel |
|---|---|
| User | users |
| InstitutionProfile | institution_profiles |
| SystemSetting | system_settings |
| Author | authors |
| Publisher | publishers |
| Language | languages |
| Classification | classifications |
| Subject | subjects |
| CollectionType | collection_types |
| RackLocation | rack_locations |
| ItemCondition | item_conditions |
| Faculty | faculties |
| StudyProgram | study_programs |
| BibliographicRecord | bibliographic_records |
| BibliographicRecordAuthor | bibliographic_record_authors |
| BibliographicRecordSubject | bibliographic_record_subjects |
| PhysicalItem | physical_items |
| PhysicalItemStatusHistory | physical_item_status_histories |
| Member | members |
| Loan | loans |
| LoanRenewal | loan_renewals |
| ReturnTransaction | return_transactions |
| Fine | fines |
| DigitalAsset | digital_assets |
| DigitalAssetAccessRule | digital_asset_access_rules |
| OcrText | ocr_texts |
| ActivityLog | activity_logs |
| ReportExportHistory | report_export_histories |
| QueueMonitorSnapshot | queue_monitor_snapshots |

## 34. Matriks Traceability Service ke Model Utama

| Service | Model atau Tabel Utama |
|---|---|
| AuthenticationService | users |
| UserService | users |
| RoleService | roles, permissions, model_has_roles, role_has_permissions |
| InstitutionProfileService | institution_profiles |
| SystemSettingService | system_settings |
| AuthorService | authors |
| PublisherService | publishers |
| LanguageService | languages |
| ClassificationService | classifications |
| SubjectService | subjects |
| CollectionTypeService | collection_types |
| RackLocationService | rack_locations |
| FacultyService | faculties |
| StudyProgramService | study_programs |
| BibliographicRecordService | bibliographic_records, bibliographic_record_authors, bibliographic_record_subjects |
| BibliographicRecordPublishService | bibliographic_records |
| PhysicalItemService | physical_items |
| PhysicalItemStatusService | physical_items, physical_item_status_histories |
| MemberService | members |
| MemberBlockingService | members |
| MemberImportService | members, faculties, study_programs |
| LoanTransactionService | loans, members, physical_items |
| LoanRenewalService | loans, loan_renewals |
| ReturnProcessingService | loans, return_transactions, fines, physical_items |
| FineCalculationService | fines, loans |
| DigitalAssetUploadService | digital_assets, storage private |
| DigitalAssetService | digital_assets, digital_asset_access_rules |
| DigitalAssetAccessService | digital_assets, digital_asset_access_rules |
| AssetStreamingService | digital_assets, storage |
| OcrProcessingService | digital_assets, ocr_texts |
| SearchIndexService | bibliographic_records, digital_assets, ocr_texts, meilisearch |
| ReportingDashboardService | banyak tabel agregat |
| CollectionReportService | bibliographic_records dan relasi |
| MemberReportService | members, faculties, study_programs |
| CirculationReportService | loans, return_transactions, members, physical_items |
| FineReportService | fines, loans, members |
| PopularCollectionReportService | loans, physical_items, bibliographic_records |
| DigitalAccessReportService | digital_assets, ocr_texts |
| ReportExportService | report_export_histories, storage private |
| AuditLogService | activity_logs |
| QueueMonitorService | queue_monitor_snapshots atau monitor runtime |

## 35. Matriks Traceability View ke Route Utama

| View | Route Utama |
|---|---|
| auth/login.blade.php | admin.auth.login |
| modules/core/dashboard/index.blade.php | admin.dashboard |
| modules/core/institution_profile/edit.blade.php | admin.core.institution_profile.edit |
| modules/core/system_settings/edit.blade.php | admin.core.system_settings.edit |
| modules/master_data/authors/index.blade.php | admin.master_data.authors.index |
| modules/catalog/records/index.blade.php | admin.catalog.records.index |
| modules/catalog/records/create.blade.php | admin.catalog.records.create |
| modules/catalog/records/edit.blade.php | admin.catalog.records.edit |
| modules/catalog/records/show.blade.php | admin.catalog.records.show |
| modules/collection/items/index.blade.php | admin.collection.items.index |
| modules/collection/items/create.blade.php | admin.collection.items.create |
| modules/collection/items/edit.blade.php | admin.collection.items.edit |
| modules/member/members/index.blade.php | admin.members.index |
| modules/member/members/create.blade.php | admin.members.create |
| modules/member/members/edit.blade.php | admin.members.edit |
| modules/member/imports/index.blade.php | admin.members.import.index |
| modules/digital_repository/assets/index.blade.php | admin.digital.assets.index |
| modules/digital_repository/assets/create.blade.php | admin.digital.assets.create |
| modules/digital_repository/assets/edit.blade.php | admin.digital.assets.edit |
| modules/digital_repository/assets/show.blade.php | admin.digital.assets.show |
| modules/digital_repository/assets/preview.blade.php | admin.digital.assets.preview |
| modules/reporting/dashboard.blade.php | admin.reports.dashboard |
| modules/reporting/collections/index.blade.php | admin.reports.collections.index |
| modules/reporting/members/index.blade.php | admin.reports.members.index |
| modules/reporting/circulation/index.blade.php | admin.reports.circulation.index |
| modules/reporting/fines/index.blade.php | admin.reports.fines.index |
| modules/reporting/popular_collections/index.blade.php | admin.reports.popular_collections.index |
| modules/reporting/digital_access/index.blade.php | admin.reports.digital_access.index |
| modules/audit/logs/index.blade.php | admin.audit.logs.index |
| modules/audit/logs/show.blade.php | admin.audit.logs.show |
| opac/home.blade.php | opac.home |
| opac/search/index.blade.php | opac.search |
| opac/records/show.blade.php | opac.records.show |

## 36. Daftar Gap yang Tidak Boleh Terjadi

Berikut gap yang tidak boleh ada saat implementasi:

1. menu ada tetapi route belum ada
2. route ada tetapi controller belum ada
3. controller ada tetapi request validation write belum ada
4. controller memproses bisnis inti tanpa service
5. service memproses domain tetapi tidak punya model atau tabel pendukung
6. view ada tetapi route name berbeda dari route map
7. action sensitif ada tetapi tanpa permission
8. action sensitif ada tetapi tanpa audit
9. action domain utama ada tetapi tanpa error code dominan
10. fitur inti ada tetapi tanpa test scenario
11. route ekspor ada tetapi file export tidak mengikuti storage policy
12. preview asset ada tetapi tidak melewati access service
13. search publik ada tetapi tidak terfilter visibilitas publik
14. report menampilkan metrik yang tidak didukung schema
15. import berjalan tanpa validasi template dan row validation

## 37. Daftar Area yang Masih Memerlukan Verifikasi Saat Coding

Walau blueprint sudah linked, area berikut wajib diverifikasi ketat saat implementasi:

1. route operasional sirkulasi detail, agar nama final sinkron dengan tree
2. route action khusus katalog dan asset, publish, unpublish, archive, run OCR, retry OCR, reindex
3. route API internal lookup, agar sesuai API contract final
4. view operasional sirkulasi jika akan dipisah dari report list
5. permission granular master data bila disederhanakan atau dipisah per entitas
6. detail queue monitor UI bila diputuskan tampil sebagai halaman admin
7. support class export builder final dan strategi async sinkron

Status item ini:

1. linked secara blueprint besar
2. perlu verifikasi implementasi rinci pada tahap coding dan backend checklist

## 38. Aturan Review Menggunakan Traceability Matrix

Saat review implementasi, lakukan urutan berikut:

1. pilih use case
2. cek menu terkait
3. cek route name
4. cek controller dan request
5. cek service utama
6. cek model dan tabel
7. cek view
8. cek permission dan policy
9. cek audit event
10. cek error code dominan
11. cek test scenario
12. cek file nyata di TREE

Jika salah satu mata rantai hilang, fitur dianggap belum lengkap.

## 39. Aturan Traceability untuk AI Agent

AI Agent yang mengerjakan coding wajib:

1. membaca item traceability sebelum membuat file
2. memastikan setiap fitur dibuat dari route sampai test
3. tidak membuat file di luar TREE
4. tidak mengganti nama route, controller, service, request, model, dan view tanpa pembaruan matriks
5. memeriksa permission, audit, error code, dan test scenario untuk setiap aksi write dan aksi sensitif

## 40. Hubungan dengan Dokumen Lanjutan

Dokumen ini menjadi acuan utama untuk:

1. 41_BACKEND_CHECKLIST.md
2. 42_FRONTEND_CHECKLIST.md
3. 43_INTEGRATION_CHECKLIST.md
4. 44_SECURITY_HARDENING_CHECKLIST.md
5. 45_SMOKE_TEST_CHECKLIST.md
6. 46_UAT_CHECKLIST.md

Hubungan:

1. backend checklist memverifikasi mata rantai backend pada matriks ini
2. frontend checklist memverifikasi menu, route, view, action button, dan state UI
3. integration checklist memverifikasi hubungan antar service dan antar modul
4. security checklist memverifikasi permission, policy, asset access, audit, dan error aman
5. smoke test memverifikasi item P1 yang paling kritis
6. UAT checklist memverifikasi use case operasional end to end

## 41. Prioritas Review Berdasarkan Traceability

Prioritas review tertinggi:

### Prioritas P1

1. AUTH
2. ACCESS
3. CATALOG publish flow
4. COLLECTION item status flow
5. MEMBER block and eligibility flow
6. CIRCULATION loan return renew flow
7. DIGITAL upload preview OCR flow
8. OPAC public visibility flow
9. REPORT dashboard and circulation
10. IMPORT members
11. EXPORT report
12. AUDIT critical events

### Prioritas P2

1. master data detail
2. popular collection report
3. digital access report
4. queue monitor
5. public suggestion

## 42. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. seluruh modul inti punya traceability item
2. fitur utama sudah terhubung dari use case sampai test
3. permission, audit, dan error code sudah masuk pada aksi sensitif
4. view utama sudah terhubung ke route dan controller
5. model sudah terhubung ke tabel yang benar
6. service sudah terhubung ke model atau tabel yang relevan
7. tidak ada fitur inti yang berstatus partial tanpa penjelasan
8. tidak ada fitur luar scope fase 1 yang diperlakukan linked tanpa dasar

## 43. Kesimpulan

Dokumen Traceability Matrix ini menetapkan keterlacakan resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 38. Dokumen ini memastikan bahwa seluruh fitur inti, dari login, master data, katalog, item fisik, anggota, sirkulasi, aset digital, OPAC, reporting, import export, audit, hingga API internal, memiliki jejak implementasi yang jelas dari use case sampai test scenario. Semua verifikasi kesiapan coding, testing, integrasi, dan go live PERPUSQU wajib merujuk dokumen ini.

END OF 39_TRACEABILITY_MATRIX.md
