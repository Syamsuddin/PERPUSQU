# CODING_PROMPT.md

# Master Full-Stack Coding Prompt untuk AI Agent — PERPUSQU

## 1. Identitas Dokumen

### 1.1 Nama Sistem
PERPUSQU — Sistem Informasi Perpustakaan Hibrid Kampus

### 1.2 Jenis Dokumen
Master Coding Prompt step-by-step untuk AI Agent full-stack (Backend + Frontend)

### 1.3 Tujuan Dokumen
Dokumen ini berisi instruksi langkah-langkah (step-by-step) untuk AI Coding Agent agar dapat membangun seluruh kode sumber PERPUSQU secara terstruktur, konsisten, dan selaras dengan seluruh blueprint resmi. Setiap langkah wajib merujuk file blueprint spesifik yang relevan. AI Agent WAJIB membaca file acuan yang disebutkan sebelum menulis kode.

---

## 2. ATURAN WAJIB UNTUK AI AGENT

Sebelum memulai coding, AI Agent WAJIB mematuhi aturan berikut:

1. **Selalu baca file blueprint acuan** yang disebutkan di setiap langkah SEBELUM menulis kode.
2. **Folder Blueprint**: `Blueprint Document/` — berisi 41 file blueprint resmi (01 s.d. 43).
3. **Folder Frontend Design**: `Frontend Design/` — berisi folder desain UI per halaman (code.html + screen.png). Gunakan sebagai acuan visual. Jika halaman tidak ada di folder ini, bangun sendiri dengan mengacu pada desain halaman sejenis yang tersedia.
4. **Stack Wajib**: Laravel 13, PHP 8.4, Blade, Livewire 4, Bootstrap 5.3, Vite, MySQL 8.4, Redis, Meilisearch.
5. **Arsitektur**: Monolith Modular. Semua modul di `app/Modules/`. Lihat `38_TREE.md` untuk path file.
6. **Pola Kode**: Thin Controller → Fat Service → Model → View. Semua write action WAJIB punya Request Validation.
7. **Keamanan**: CSRF aktif, Permission check di middleware dan policy, input divalidasi server-side, file privat lewat controller terproteksi.
8. **Audit**: Semua aksi sensitif WAJIB dicatat ke audit log.
9. **Urutan Build**: WAJIB mengikuti tahapan di bawah ini. Jangan lompat tahap.
10. **Verifikasi per Tahap**: Setelah menyelesaikan satu tahap, lakukan verifikasi route berfungsi, permission bekerja, view tampil benar, dan tidak ada broken link.

---

## 3. PETA ACUAN BLUEPRINT

| Aspek | File Acuan Utama |
|---|---|
| Stack & Arsitektur | `02_STACK_TECHNOLOGY.md`, `03_MODULAR_ARCHITECTURE.md` |
| Kebutuhan Bisnis | `04_PRD.md`, `05_SRS.md`, `06_USE_CASE.md` |
| Role & Permission | `07_ROLE_PERMISSION_MATRIX.md` |
| Navigation & Menu | `08_MENU_MAP.md` |
| Route | `09_ROUTE_MAP.md` |
| View / Halaman | `10_VIEW_MAP.md` |
| Controller | `11_CONTROLLER_MAP.md` |
| Service Layer | `12_SERVICE_LAYER.md` |
| Model & Relasi | `13_MODEL_MAP.md` |
| Database Schema | `14_SCHEMA.sql` |
| Seed Data | `15_SEED.sql` |
| Validasi | `16_VALIDATION_RULES.md` |
| State Machine | `17_WORKFLOW_STATE_MACHINE.md` |
| UI Standard | `18_UI_UX_STANDARD.md` |
| OPAC Flow | `19_OPAC_UX_FLOW.md` |
| API | `20_API_CONTRACT.md` |
| Search & Index | `21_SEARCH_INDEXING_SPEC.md` |
| File Storage | `22_STORAGE_FILE_POLICY.md` |
| OCR | `23_OCR_AND_DIGITAL_PROCESSING.md` |
| Reporting | `25_REPORTING_SPEC.md` |
| Import/Export | `26_IMPORT_EXPORT_SPEC.md` |
| Security | `28_SECURITY_POLICY.md` |
| Audit Log | `29_AUDIT_LOG_SPEC.md` |
| Error Code | `30_ERROR_CODE.md` |
| Coding Standard | `37_CODING_STANDARD.md` |
| File Tree | `38_TREE.md` |
| Traceability | `39_TRACEBILITY_MATRIX.md` |
| Build Priority | `40_PAGE_BUILD_PRIORITY.md` |
| UI Assets | `41_REUSABLE_UI_ASSETS.md` |
| UI Prompts | `42_UI_STITCH_PROMPTS.md` |
| Desain Visual | Folder `Frontend Design/` |

---

## 4. LANGKAH CODING STEP-BY-STEP

---

### TAHAP 0: FONDASI PROYEK

> **Tujuan**: Inisialisasi proyek Laravel, database, dan fondasi UI yang akan dipakai semua modul.

#### Step 0.1 — Inisialisasi Proyek Laravel

**Acuan WAJIB dibaca:**
- `02_STACK_TECHNOLOGY.md` (stack resmi)
- `34_ENV_CONFIGURATION.md` (konfigurasi environment)
- `38_TREE.md` (struktur folder resmi)

**Instruksi:**
1. Buat proyek Laravel 13 baru.
2. Setup `.env` sesuai `34_ENV_CONFIGURATION.md`: DB MySQL 8.4, Cache Redis, Queue Redis, Session Redis.
3. Install dependencies wajib: `spatie/laravel-permission`, `spatie/laravel-activitylog`, `spatie/laravel-medialibrary`, `laravel/horizon`.
4. Buat folder `app/Modules/` dan semua subfolder modul sesuai `38_TREE.md` §4.
5. Konfigurasi `RouteServiceProvider` untuk autoload route per modul.
6. Konfigurasi `config/perpusqu/` semua file (app, audit, features, ocr, reporting, search, security, storage) sesuai `38_TREE.md`.

#### Step 0.2 — Database Schema & Migration

**Acuan WAJIB dibaca:**
- `14_SCHEMA.sql` (DDL lengkap 29 tabel)
- `13_MODEL_MAP.md` (relasi model)
- `37_CODING_STANDARD.md` §36-§37 (aturan migration & seeder)

**Instruksi:**
1. Buat semua migration file mengikuti urutan di `38_TREE.md` §database/migrations.
2. Pastikan setiap tabel, foreign key, index, dan constraint sesuai `14_SCHEMA.sql`.
3. Jalankan `php artisan migrate` dan verifikasi semua tabel terbentuk.

#### Step 0.3 — Seeder Data Awal

**Acuan WAJIB dibaca:**
- `15_SEED.sql` (data seed lengkap)
- `07_ROLE_PERMISSION_MATRIX.md` (role dan permission)

**Instruksi:**
1. Buat semua seeder sesuai `38_TREE.md` §database/seeders.
2. Seed: Permission → Role → RolePermission → SuperAdmin → InstitutionProfile → SystemSetting → semua Master Data.
3. Jalankan `php artisan db:seed` dan verifikasi data terbentuk.

#### Step 0.4 — Eloquent Models

**Acuan WAJIB dibaca:**
- `13_MODEL_MAP.md` (semua model, relation, fillable, casts, scope)
- `37_CODING_STANDARD.md` §28-§32 (aturan model)

**Instruksi:**
1. Buat semua Model di folder modul masing-masing sesuai `38_TREE.md`.
2. Definisikan: `$table`, `$fillable`, `$casts`, semua `relation()`, semua `scope` yang disebutkan di `13_MODEL_MAP.md`.
3. **JANGAN** menaruh logika bisnis di model. Model hanya untuk data representation dan relation.

#### Step 0.5 — Layout & Komponen UI Dasar

**Acuan WAJIB dibaca:**
- `18_UI_UX_STANDARD.md` (standar visual lengkap)
- `41_REUSABLE_UI_ASSETS.md` (komponen reusable)
- `42_UI_STITCH_PROMPTS.md` §3 (General System Prompt)
- `38_TREE.md` §resources/views (struktur view)

**Acuan Visual:**
- `Frontend Design/login_page_vw_auth_001/` (contoh layout auth)
- `Frontend Design/admin_dashboard_vw_dash_001/` (contoh layout admin)
- `Frontend Design/opac_public_home_vw_opac_001/` (contoh layout OPAC)

**Instruksi:**
1. Buat `resources/views/layouts/auth.blade.php` — layout auth centered card.
2. Buat `resources/views/layouts/admin.blade.php` — layout admin dengan sidebar responsif + role-based menu, header breadcrumb, flash message area, footer.
3. Buat `resources/views/layouts/opac.blade.php` — layout OPAC publik search-first.
4. Buat semua komponen di `resources/views/components/admin/`:
   - `_breadcrumb.blade.php`
   - `_datatable_pagination.blade.php`
   - `_empty_state.blade.php`
   - `_filter_bar.blade.php`
   - `_flash_message.blade.php`
   - `_page_header.blade.php`
   - `_status_badge.blade.php` — dengan SEMUA state dari `17_WORKFLOW_STATE_MACHINE.md` dan `41_REUSABLE_UI_ASSETS.md` §4.6.
   - `_summary_cards.blade.php`
5. Buat komponen di `resources/views/components/opac/`:
   - `_record_card.blade.php`, `_search_filter_bar.blade.php`, `_search_empty_state.blade.php`, `_search_result_meta.blade.php`, `_asset_preview_button.blade.php`
6. Buat Error Pages: `resources/views/errors/` (403, 404, 419, 429, 500).
7. Setup CSS (`resources/css/admin.css`, `opac.css`) dan JS (`resources/js/admin.js`, `opac.js`).
8. Konfigurasi `vite.config.js` untuk build asset.

#### Step 0.6 — Middleware & Exception Handler

**Acuan WAJIB dibaca:**
- `28_SECURITY_POLICY.md` (security baseline)
- `30_ERROR_CODE.md` (error codes)
- `37_CODING_STANDARD.md` §27 (exception handling)
- `38_TREE.md` §app/Http/Middleware dan §app/Exceptions

**Instruksi:**
1. Buat middleware: `Authenticate`, `ForceHttps`, `SecurityHeaders`, `RedirectIfAuthenticated`.
2. Buat Exception classes: `DomainException`, `BusinessRuleException`, `FileStorageException`, dll sesuai `38_TREE.md`.
3. Setup `Handler.php` untuk mapping error ke view error dan flash message.
4. Buat `app/Support/ErrorCode/` classes sesuai `38_TREE.md`.

---

### TAHAP 1: AUTENTIKASI & MANAJEMEN AKSES

> **Tujuan**: Login berfungsi, user management aktif, role-permission terkelola, system settings dan institution profile tersedia.

#### Step 1.1 — Login & Logout

**Acuan WAJIB dibaca:**
- `09_ROUTE_MAP.md` §Auth routes
- `10_VIEW_MAP.md` §10 (VW-AUTH-001)
- `11_CONTROLLER_MAP.md` §Identity/AuthController
- `12_SERVICE_LAYER.md` §AuthenticationService
- `16_VALIDATION_RULES.md` §LoginRequest
- `28_SECURITY_POLICY.md` §rate limiting, brute force
- `29_AUDIT_LOG_SPEC.md` §login events

**Acuan Visual:**
- `Frontend Design/login_page_vw_auth_001/`

**Instruksi:**
1. Buat `AuthController` dengan method `showLogin()`, `login()`, `logout()`.
2. Buat `LoginRequest` dengan validasi sesuai `16_VALIDATION_RULES.md`.
3. Buat `AuthenticationService` — verifikasi kredensial, cek `is_active`, rate limiting, audit log.
4. Buat view `auth/login.blade.php` mengacu pada desain di `Frontend Design/login_page_vw_auth_001/code.html`.
5. Setup route auth sesuai `09_ROUTE_MAP.md`.

#### Step 1.2 — Dashboard Admin

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §11 (VW-DASH-001)
- `11_CONTROLLER_MAP.md` §Core/DashboardController
- `12_SERVICE_LAYER.md` §DashboardService

**Acuan Visual:**
- `Frontend Design/admin_dashboard_vw_dash_001/`

**Instruksi:**
1. Buat `DashboardController@index`.
2. Buat `DashboardService` — stat cards (total katalog, item, pinjaman aktif, aset digital), aktivitas terkini.
3. Buat view `modules/core/dashboard/index.blade.php` mengacu pada desain di `Frontend Design/admin_dashboard_vw_dash_001/code.html`.

#### Step 1.3 — User Management (CRUD)

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §21 (VW-ACC-001..004)
- `11_CONTROLLER_MAP.md` §Identity/UserController
- `12_SERVICE_LAYER.md` §UserService
- `16_VALIDATION_RULES.md` §StoreUserRequest, UpdateUserRequest
- `07_ROLE_PERMISSION_MATRIX.md`
- `17_WORKFLOW_STATE_MACHINE.md` §9 (User State)
- `42_UI_STITCH_PROMPTS.md` §6.1-6.2

**Acuan Visual:** Bangun sendiri mengacu pada pola index/form dari `Frontend Design/member_index_page_vw_mem_001/` dan `Frontend Design/authors_index_page_vw_mas_001/`.

**Instruksi:**
1. Buat `UserController` (index, create, store, show, edit, update, resetPassword, activate, deactivate).
2. Buat `UserService`, `UserPolicy`.
3. Buat Request: `StoreUserRequest`, `UpdateUserRequest`, `ResetUserPasswordRequest`.
4. Buat views: `identity/users/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, `_form.blade.php`, `_table.blade.php`, `_reset_password_modal.blade.php`.
5. Permission check di route middleware.

#### Step 1.4 — Role & Permission Management

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §21.5-21.8 (VW-ACC-005..008)
- `11_CONTROLLER_MAP.md` §Identity/RoleController, PermissionController
- `12_SERVICE_LAYER.md` §RoleService, PermissionService
- `07_ROLE_PERMISSION_MATRIX.md` (seluruh matriks)
- `42_UI_STITCH_PROMPTS.md` §6.3-6.4

**Instruksi:**
1. Buat `RoleController` dan `PermissionController`.
2. Buat `RoleService`, `PermissionService`.
3. Buat views: `identity/roles/index.blade.php`, `create.blade.php`, `edit.blade.php`, `_permission_matrix.blade.php`.
4. Permission matrix page: Rows=permission (grouped by module), Columns=role, cells=checkbox.

#### Step 1.5 — Institution Profile & System Settings

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §20 (VW-SET-001, VW-SET-002)
- `11_CONTROLLER_MAP.md` §Core/InstitutionProfileController, SystemSettingController
- `12_SERVICE_LAYER.md` §InstitutionProfileService, SystemSettingService
- `16_VALIDATION_RULES.md` §UpdateInstitutionProfileRequest, UpdateSystemSettingRequest
- `42_UI_STITCH_PROMPTS.md` §6.5-6.6

**Instruksi:**
1. Buat controller, service, request, policy untuk keduanya.
2. Buat views: `core/institution_profile/edit.blade.php`, `core/system_settings/edit.blade.php`.
3. System settings adalah parameter operasional (lama pinjam, batas pinjam, denda per hari, batas perpanjangan).

#### Step 1.6 — Sidebar Menu Role-Based

**Acuan WAJIB dibaca:**
- `08_MENU_MAP.md` (seluruh menu sidebar)
- `07_ROLE_PERMISSION_MATRIX.md` (visibility per role)

**Instruksi:**
1. Implementasikan sidebar di `layouts/admin.blade.php` dengan menu sesuai `08_MENU_MAP.md` §7.
2. Setiap menu item hanya tampil jika user memiliki permission yang sesuai (`@can` directive).
3. Submenu collapse/expand untuk grup menu.

**✅ VERIFIKASI TAHAP 1:**
- [ ] Login berhasil dan redirect ke dashboard
- [ ] Dashboard menampilkan stat cards
- [ ] User CRUD berfungsi lengkap
- [ ] Role + Permission matrix berfungsi
- [ ] System Settings tersimpan
- [ ] Sidebar menu sesuai role

---

### TAHAP 2: MASTER DATA INTI

> **Tujuan**: 10 entitas master data berfungsi penuh (CRUD) sebagai fondasi Katalog, Koleksi, Anggota.

#### Step 2.1 — Master Data CRUD (10 Entitas)

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §12 (VW-MAS-001..030)
- `11_CONTROLLER_MAP.md` §MasterData (9 controller)
- `12_SERVICE_LAYER.md` §MasterData (9 service)
- `13_MODEL_MAP.md` §MasterData (9 model + ItemCondition)
- `16_VALIDATION_RULES.md` §Master Data (Store/Update per entitas)
- `37_CODING_STANDARD.md` §23 (pola CRUD controller)
- `42_UI_STITCH_PROMPTS.md` §5 (Master Data Generik)

**Acuan Visual:**
- `Frontend Design/authors_index_page_vw_mas_001/`
- `Frontend Design/author_form_page_vw_mas_002_1/`
- `Frontend Design/author_form_page_vw_mas_002_2/`
- `Frontend Design/publisher_index_page_vw_mas_004/`

**Instruksi per entitas (ulangi untuk setiap entitas):**

Entitas: `Author`, `Publisher`, `Language`, `Classification`, `Subject`, `CollectionType`, `RackLocation`, `Faculty`, `StudyProgram`, `ItemCondition`.

Per entitas:
1. Buat Controller (`AuthorController`, dst.) dengan method: index, create, store, edit, update, destroy.
2. Buat Service (`AuthorService`, dst.).
3. Buat Request (`StoreAuthorRequest`, `UpdateAuthorRequest`).
4. Buat Policy (`AuthorPolicy`).
5. Buat View: `master_data/authors/index.blade.php` (datatable + search + pagination + empty state), `_form.blade.php` (partial shared create/edit).
6. Daftarkan route sesuai `09_ROUTE_MAP.md`.
7. Gunakan desain index dari `Frontend Design/authors_index_page_vw_mas_001/code.html` sebagai template.
8. Untuk entitas lain yang tidak punya desain di folder `Frontend Design/`, duplikasi layout dari Authors.

**Catatan khusus StudyProgram:** RelASI `belongsTo(Faculty)`. Sediakan filter Fakultas di index.

**✅ VERIFIKASI TAHAP 2:**
- [ ] Semua 10 master data bisa CRUD
- [ ] Pagination dan empty state benar
- [ ] Permission bekerja per entitas

---

### TAHAP 3: KATALOG (BIBLIOGRAPHIC RECORD)

> **Tujuan**: Pencatatan data bibliografis lengkap (CRUD + Publish/Unpublish/Archive).

#### Step 3.1 — Catalog CRUD

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §13 (VW-CAT-001..004)
- `11_CONTROLLER_MAP.md` §Catalog/BibliographicRecordController
- `12_SERVICE_LAYER.md` §BibliographicRecordService, BibliographicRecordPublishService
- `13_MODEL_MAP.md` §BibliographicRecord, BibliographicRecordAuthor, BibliographicRecordSubject
- `16_VALIDATION_RULES.md` §Catalog
- `17_WORKFLOW_STATE_MACHINE.md` §11 (Record State Machine)
- `42_UI_STITCH_PROMPTS.md` §4.3, §4.4, §4.5, §7.1

**Acuan Visual:**
- `Frontend Design/catalog_index_page_vw_cat_001/`
- `Frontend Design/create_catalog_form_vw_cat_002/`
- `Frontend Design/catalog_detail_page_vw_cat_003/`

**Instruksi:**
1. Buat `BibliographicRecordController` (index, create, store, show, edit, update, publish, unpublish, archive).
2. Buat `BibliographicRecordService` dan `BibliographicRecordPublishService`.
3. Buat Request: `StoreBibliographicRecordRequest`, `UpdateBibliographicRecordRequest`, `BibliographicRecordFilterRequest`, `PublishBibliographicRecordRequest`.
4. Buat Support: `BibliographicRecordStateGuard` — validasi transisi sesuai `17_WORKFLOW_STATE_MACHINE.md` §11.4.
5. Buat views:
   - `catalog/records/index.blade.php` — filter: Jenis Koleksi, Bahasa, Tahun, Status Publikasi. Desain: `Frontend Design/catalog_index_page_vw_cat_001/code.html`.
   - `catalog/records/create.blade.php` — field sesuai `10_VIEW_MAP.md` §13.2. Desain: `Frontend Design/create_catalog_form_vw_cat_002/code.html`.
   - `catalog/records/show.blade.php` — tabs (Item Fisik, Aset Digital). Desain: `Frontend Design/catalog_detail_page_vw_cat_003/code.html`.
   - `catalog/records/edit.blade.php` — pre-filled dari data existing.
   - `catalog/records/_form.blade.php` — partial shared.
6. Multi-select untuk Author dan Subject (relasi pivot).
7. Cover image upload via `22_STORAGE_FILE_POLICY.md`.
8. Status badge: Draft/Published/Unpublished/Archived.

**✅ VERIFIKASI TAHAP 3:**
- [ ] Buat katalog baru → status Draft
- [ ] Publish → status Published
- [ ] Unpublish → status Unpublished
- [ ] Cover image terupload
- [ ] Filter dan search berfungsi

---

### TAHAP 4: KOLEKSI FISIK (PHYSICAL ITEM)

> **Tujuan**: Manajemen eksemplar fisik terhubung ke bibliographic record.

#### Step 4.1 — Physical Item CRUD

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §14 (VW-COL-001..005)
- `11_CONTROLLER_MAP.md` §Collection/PhysicalItemController
- `12_SERVICE_LAYER.md` §PhysicalItemService, PhysicalItemStatusService
- `13_MODEL_MAP.md` §PhysicalItem, PhysicalItemStatusHistory
- `16_VALIDATION_RULES.md` §Collection
- `17_WORKFLOW_STATE_MACHINE.md` §12 (Physical Item State Machine — 6 state)
- `42_UI_STITCH_PROMPTS.md` §4.6, §4.7, §7.2

**Acuan Visual:**
- `Frontend Design/create_physical_item_form_vw_col_002/`

**Instruksi:**
1. Buat `PhysicalItemController` (index, create, store, show, edit, update, changeStatus, history).
2. Buat `PhysicalItemService`, `PhysicalItemStatusService`.
3. Buat `PhysicalItemStateGuard` — enforce transisi status sesuai `17_WORKFLOW_STATE_MACHINE.md` §12.3.
4. Buat views sesuai View Map. Untuk halaman yang tidak ada di `Frontend Design/`, bangun mengacu pola dari katalog pages.
5. Status badges: Available(Hijau), Loaned(Biru), Damaged(Merah), Lost(Merah Tua), Repair(Kuning), Inactive(Abu).
6. Histori status change tersimpan di `PhysicalItemStatusHistory`.

---

### TAHAP 5: ANGGOTA (MEMBER)

> **Tujuan**: Data anggota perpustakaan (CRUD + Block/Unblock + Histori).

#### Step 5.1 — Member CRUD

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §15 (VW-MEM-001..005)
- `11_CONTROLLER_MAP.md` §Member/MemberController
- `12_SERVICE_LAYER.md` §MemberService, MemberBlockingService
- `13_MODEL_MAP.md` §Member
- `16_VALIDATION_RULES.md` §Member
- `17_WORKFLOW_STATE_MACHINE.md` §10 (Member State Machine — derived states)
- `42_UI_STITCH_PROMPTS.md` §4.8, §4.9

**Acuan Visual:**
- `Frontend Design/member_index_page_vw_mem_001/`

**Instruksi:**
1. Buat `MemberController` (index, create, store, show, edit, update, block, unblock, history).
2. Buat `MemberService`, `MemberBlockingService`, `MemberEligibilityResolver`.
3. Derived states: Active Ready, Active Blocked, Inactive Unblocked, Inactive Blocked — sesuai `17_WORKFLOW_STATE_MACHINE.md` §10.1.
4. Buat views. Halaman detail dan histori yang tidak ada desainnya, bangun mengacu pola dari catalog detail.

---

### TAHAP 6: SIRKULASI

> **Tujuan**: Peminjaman, Pengembalian, Perpanjangan, Daftar Pinjaman Aktif, Histori, dan Denda — inti operasional perpustakaan.

#### Step 6.1 — Peminjaman Baru (Loan)

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §16.1 (VW-CIR-001)
- `11_CONTROLLER_MAP.md` §Circulation/LoanController
- `12_SERVICE_LAYER.md` §LoanTransactionService, LoanEligibilityService
- `16_VALIDATION_RULES.md` §StoreLoanRequest
- `17_WORKFLOW_STATE_MACHINE.md` §13 (Loan), §12 (Item → LOANED)
- `42_UI_STITCH_PROMPTS.md` §4.10

**Acuan Visual:**
- `Frontend Design/circulation_new_loan_page_vw_circ_001/`

**Instruksi:**
1. Buat `LoanController@create`, `LoanController@store`.
2. Buat `LoanTransactionService` — cek member eligibility, cek item available, hitung due date, buat loan, ubah item status ke LOANED.
3. Buat `LoanEligibilityService` dan `DueDateCalculator`.
4. UI: Two-pane layout (Member Card + Item Cart). Desain: `Frontend Design/circulation_new_loan_page_vw_circ_001/code.html`.

#### Step 6.2 — Pengembalian (Return)

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §16.2 (VW-CIR-002)
- `11_CONTROLLER_MAP.md` §Circulation/ReturnTransactionController
- `12_SERVICE_LAYER.md` §ReturnProcessingService, FineCalculationService
- `17_WORKFLOW_STATE_MACHINE.md` §13 (Loan → RETURNED), §14 (Fine)
- `42_UI_STITCH_PROMPTS.md` §4.11

**Acuan Visual:**
- `Frontend Design/circulation_return_page_vw_circ_002/`

**Instruksi:**
1. Buat `ReturnTransactionController@create`, `ReturnTransactionController@store`.
2. Buat `ReturnProcessingService` — close loan, hitung denda jika overdue, update item status, catat return transaction.
3. Buat `FineCalculationService` dan `FineAmountCalculator`.
4. UI: Scan barcode → tampilkan loan info → overdue alert → kondisi kembali → proses. Desain: `Frontend Design/circulation_return_page_vw_circ_002/code.html`.

#### Step 6.3 — Perpanjangan, Pinjaman Aktif, Histori, Denda

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §16.3-16.7 (VW-CIR-003..007)
- `11_CONTROLLER_MAP.md` §Circulation (LoanRenewalController, FineController)
- `12_SERVICE_LAYER.md` §LoanRenewalService
- `42_UI_STITCH_PROMPTS.md` §4.12, §7.3, §7.4

**Instruksi:**
1. Perpanjangan (VW-CIR-003): Cari pinjaman aktif → perpanjang → hitung due date baru. Gunakan guard `17_WORKFLOW_STATE_MACHINE.md` §13.6.
2. Pinjaman Aktif (VW-CIR-004): Datatable monitor dengan filter overdue, badge Normal/Overdue.
3. Detail Pinjaman (VW-CIR-005): Buat sendiri mengacu catalog detail style.
4. Histori Sirkulasi (VW-CIR-006): Datatable read-only semua transaksi.
5. Denda (VW-CIR-007): Summary cards + datatable + Settle/Waive actions. Badge states: Outstanding/Settled/Waived/Cancelled.

**✅ VERIFIKASI TAHAP 6:**
- [ ] Pinjam buku → item jadi LOANED, loan ACTIVE
- [ ] Kembalikan buku → item AVAILABLE, loan RETURNED, denda otomatis jika overdue
- [ ] Perpanjang pinjaman → due date berubah
- [ ] Pinjaman aktif tampil dengan badge overdue

---

### TAHAP 7: REPOSITORI DIGITAL

> **Tujuan**: Upload, metadata, preview privat, OCR, indexing aset digital.

#### Step 7.1 — Digital Asset CRUD & Upload

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §17 (VW-DIG-001..004)
- `11_CONTROLLER_MAP.md` §DigitalRepository/DigitalAssetController
- `12_SERVICE_LAYER.md` §DigitalAssetService, DigitalAssetUploadService
- `16_VALIDATION_RULES.md` §DigitalAsset
- `17_WORKFLOW_STATE_MACHINE.md` §15 (Digital Asset Publication), §16 (Access State)
- `22_STORAGE_FILE_POLICY.md` (aturan upload, mime type, ukuran file)
- `42_UI_STITCH_PROMPTS.md` §4.13, §4.14, §7.5

**Acuan Visual:**
- `Frontend Design/digital_assets_index_vw_dig_001/`
- `Frontend Design/upload_digital_asset_form_vw_dig_002/`

**Instruksi:**
1. Buat `DigitalAssetController` (index, create, store, show, edit, update, publish, unpublish, archive, runOcr, reindex).
2. Buat `DigitalAssetUploadService` — validasi file, simpan ke object storage, buat metadata di DB, dispatch OCR job jika diminta.
3. Buat views sesuai desain yang tersedia.
4. Preview privat via `AssetPreviewController` + `AssetStreamingService`.

#### Step 7.2 — OCR & Indexing

**Acuan WAJIB dibaca:**
- `23_OCR_AND_DIGITAL_PROCESSING.md`
- `21_SEARCH_INDEXING_SPEC.md`
- `17_WORKFLOW_STATE_MACHINE.md` §17 (OCR State), §18 (Index State)

**Acuan Visual:**
- `Frontend Design/ocr_indexing_monitor_vw_dig_005/`

**Instruksi:**
1. Buat `ProcessDigitalAssetOcrJob` — Tesseract OCR, simpan ke `OcrText`.
2. Buat `ReindexBibliographicRecordJob` — sync ke Meilisearch.
3. Buat `OcrProcessingService` — manage state: NOT_REQUESTED → QUEUED → PROCESSING → SUCCESS/FAILED.

---

### TAHAP 8: OPAC PUBLIK

> **Tujuan**: Antarmuka pencarian publik untuk mahasiswa/dosen.

#### Step 8.1 — OPAC Home, Search, Detail, Preview

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §23 (VW-OPA-001..006)
- `19_OPAC_UX_FLOW.md` (seluruh alur UX publik)
- `21_SEARCH_INDEXING_SPEC.md` (Meilisearch integration)
- `28_SECURITY_POLICY.md` §public visibility (OPAC hanya tampilkan PUBLIC_VISIBLE)
- `42_UI_STITCH_PROMPTS.md` §4.16-4.19, §9.1-9.2

**Acuan Visual:**
- `Frontend Design/opac_public_home_vw_opac_001/`
- `Frontend Design/opac_search_results_vw_opac_002/`
- `Frontend Design/opac_record_detail_vw_opa_003/`
- `Frontend Design/digital_asset_viewer_vw_opa_004/`

**Instruksi:**
1. Buat `OpacHomeController`, `OpacSearchController`, `OpacRecordController`, `PublicAssetPreviewController`.
2. Buat `OpacSearchService` — query Meilisearch, hydrate dari MySQL, filter hanya `publication_status=PUBLISHED` dan `is_public=1`.
3. Buat `PublicAssetPreviewService` — cek access rule sesuai `17_WORKFLOW_STATE_MACHINE.md` §16.
4. OPAC Home: Hero search, quick pill-links. Desain: `Frontend Design/opac_public_home_vw_opac_001/code.html`.
5. Search Results: Split view (left filters, right cards). Desain: `Frontend Design/opac_search_results_vw_opac_002/code.html`.
6. Record Detail: Metadata + ketersediaan fisik + akses digital. Desain: `Frontend Design/opac_record_detail_vw_opa_003/code.html`.
7. PDF Preview: PDF.js viewer. Desain: `Frontend Design/digital_asset_viewer_vw_opa_004/code.html`.
8. About & Help: Static pages OPAC.
9. **KEAMANAN**: Pastikan TIDAK ADA data internal (barcode, inventory code) bocor ke publik.

---

### TAHAP 9: REPORTING

> **Tujuan**: Dashboard statistik dan semua laporan operasional.

#### Step 9.1 — Report Dashboard & Laporan Detail

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §18 (VW-REP-001..007)
- `11_CONTROLLER_MAP.md` §Reporting (7 controller)
- `12_SERVICE_LAYER.md` §Reporting (8 service)
- `25_REPORTING_SPEC.md` (spesifikasi setiap laporan)
- `42_UI_STITCH_PROMPTS.md` §10

**Acuan Visual:**
- `Frontend Design/report_dashboard_vw_rep_001/`

**Instruksi:**
1. Buat `DashboardReportController@index` — summary charts dan metrics.
2. Untuk setiap report (Koleksi, Anggota, Sirkulasi, Denda, Koleksi Populer, Akses Digital):
   - Buat Controller, Service, Query class, Filter Request, Export Request.
   - Buat view: filters + summary cards + datatable + export buttons.
3. Export via `ReportExportService` → job → file download.
4. Desain dashboard: `Frontend Design/report_dashboard_vw_rep_001/code.html`. Laporan detail dibangun sendiri mengacu pola dashboard + datatable.

---

### TAHAP 10: IMPORT, EXPORT & AUDIT

> **Tujuan**: Import anggota, export laporan, dan audit log.

#### Step 10.1 — Import Anggota

**Acuan WAJIB dibaca:**
- `26_IMPORT_EXPORT_SPEC.md`
- `11_CONTROLLER_MAP.md` §Member/MemberImportController
- `12_SERVICE_LAYER.md` §MemberImportService

**Instruksi:**
1. Buat `MemberImportController` dan `MemberImportService`.
2. Validasi row-level via `MemberImportRowValidator`.
3. Process via queue job untuk file besar. Tampilkan summary result.

#### Step 10.2 — Export Report

**Acuan WAJIB dibaca:**
- `26_IMPORT_EXPORT_SPEC.md`
- `25_REPORTING_SPEC.md`

**Instruksi:**
1. Buat exporter per laporan di `app/Modules/Reporting/Support/Export/`.
2. Export PDF via `barryvdh/laravel-dompdf`, Export Excel via `maatwebsite/excel`.

#### Step 10.3 — Audit Log

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §19 (VW-AUD-001..003)
- `29_AUDIT_LOG_SPEC.md`
- `11_CONTROLLER_MAP.md` §Audit/AuditLogController
- `12_SERVICE_LAYER.md` §AuditLogService
- `42_UI_STITCH_PROMPTS.md` §4.15

**Acuan Visual:**
- `Frontend Design/audit_log_index_vw_aud_001/`

**Instruksi:**
1. Buat `AuditLogController` (index, show).
2. Buat `AuditLogService` dan `AuditLogQuery`.
3. Buat views: index (filter: tanggal, user, modul, aksi) + show (JSON payload detail).
4. Buat `QueueMonitorController` dan `QueueMonitorService` untuk queue monitoring page.
5. Desain: `Frontend Design/audit_log_index_vw_aud_001/code.html`.

---

### TAHAP 11: PROFIL, HELP, DAN FINAL POLISH

> **Tujuan**: Halaman pendukung dan penyempurnaan akhir.

#### Step 11.1 — Profil Pengguna

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §22 (VW-PROF-001..003)
- `11_CONTROLLER_MAP.md` §Profile/ProfileController
- `12_SERVICE_LAYER.md` §ProfileService

**Acuan Visual:**
- `Frontend Design/my_profile_page_vw_prof_001/`
- `Frontend Design/edit_profile_vw_prof_002/`
- `Frontend Design/change_password_page_vw_prof_003/`

**Instruksi:**
1. Buat `ProfileController` (show, edit, update, changePassword).
2. Buat views mengacu desain di `Frontend Design/`.

#### Step 11.2 — OPAC About & Help

**Acuan WAJIB dibaca:**
- `10_VIEW_MAP.md` §23.5-23.6 (VW-OPA-005, VW-OPA-006)
- `42_UI_STITCH_PROMPTS.md` §9.1-9.2

**Instruksi:**
1. Buat static pages `opac/about.blade.php` dan `opac/help.blade.php`.
2. About: informasi institusi dari `InstitutionProfile`.
3. Help: panduan pencarian step-by-step.

#### Step 11.3 — Final Polish

**Acuan WAJIB dibaca:**
- `18_UI_UX_STANDARD.md` (review semua halaman vs standar)
- `39_TRACEBILITY_MATRIX.md` (cek kelengkapan traceability)
- `31_TEST_PLAN.md`, `32_TEST_SCENARIO.md`

**Instruksi:**
1. Review semua halaman — pastikan breadcrumb, pagination, empty state, flash message konsisten.
2. Pastikan semua link antar halaman tidak broken.
3. Pastikan semua permission bekerja — user tanpa izin tidak bisa akses.
4. Jalankan smoke test pada halaman kritis (login, dashboard, catalog CRUD, loan, return, OPAC search).
5. Pastikan semua audit log tercatat untuk aksi sensitif.

---

## 5. CHECKLIST VERIFIKASI AKHIR

| # | Item Verifikasi | Status |
|---|---|---|
| 1 | Login dan logout berfungsi | [ ] |
| 2 | Role permission bekerja di semua halaman | [ ] |
| 3 | Dashboard stat cards akurat | [ ] |
| 4 | 10 Master Data CRUD berfungsi | [ ] |
| 5 | Katalog CRUD + publish workflow berfungsi | [ ] |
| 6 | Physical Item CRUD + status change berfungsi | [ ] |
| 7 | Member CRUD + block/unblock berfungsi | [ ] |
| 8 | Peminjaman baru sukses | [ ] |
| 9 | Pengembalian + denda otomatis sukses | [ ] |
| 10 | Perpanjangan pinjaman sukses | [ ] |
| 11 | Digital asset upload + preview privat berfungsi | [ ] |
| 12 | OCR dispatch + status tracking berfungsi | [ ] |
| 13 | OPAC search menampilkan data publik saja | [ ] |
| 14 | OPAC detail tidak bocorkan data internal | [ ] |
| 15 | Report dashboard + minimal 2 laporan berfungsi | [ ] |
| 16 | Export PDF/Excel berfungsi | [ ] |
| 17 | Audit log tercatat untuk aksi sensitif | [ ] |
| 18 | Semua error page tampil benar | [ ] |
| 19 | Sidebar menu sesuai role | [ ] |
| 20 | Tidak ada broken link | [ ] |

---

## 6. MAPPING FRONTEND DESIGN TERSEDIA

Berikut daftar folder desain yang tersedia di `Frontend Design/`:

| Folder | View Code | Tersedia |
|---|---|---|
| `login_page_vw_auth_001` | VW-AUTH-001 | ✅ |
| `admin_dashboard_vw_dash_001` | VW-DASH-001 | ✅ |
| `authors_index_page_vw_mas_001` | VW-MAS-001 | ✅ |
| `author_form_page_vw_mas_002_1` | VW-MAS-002 | ✅ |
| `author_form_page_vw_mas_002_2` | VW-MAS-002 | ✅ |
| `publisher_index_page_vw_mas_004` | VW-MAS-004 | ✅ |
| `catalog_index_page_vw_cat_001` | VW-CAT-001 | ✅ |
| `create_catalog_form_vw_cat_002` | VW-CAT-002 | ✅ |
| `catalog_detail_page_vw_cat_003` | VW-CAT-003 | ✅ |
| `create_physical_item_form_vw_col_002` | VW-COL-002 | ✅ |
| `member_index_page_vw_mem_001` | VW-MEM-001 | ✅ |
| `circulation_new_loan_page_vw_circ_001` | VW-CIR-001 | ✅ |
| `circulation_return_page_vw_circ_002` | VW-CIR-002 | ✅ |
| `digital_assets_index_vw_dig_001` | VW-DIG-001 | ✅ |
| `upload_digital_asset_form_vw_dig_002` | VW-DIG-002 | ✅ |
| `ocr_indexing_monitor_vw_dig_005` | VW-DIG-005 | ✅ |
| `audit_log_index_vw_aud_001` | VW-AUD-001 | ✅ |
| `report_dashboard_vw_rep_001` | VW-REP-001 | ✅ |
| `opac_public_home_vw_opac_001` | VW-OPA-001 | ✅ |
| `opac_search_results_vw_opac_002` | VW-OPA-002 | ✅ |
| `opac_record_detail_vw_opa_003` | VW-OPA-003 | ✅ |
| `digital_asset_viewer_vw_opa_004` | VW-OPA-004 | ✅ |
| `my_profile_page_vw_prof_001` | VW-PROF-001 | ✅ |
| `edit_profile_vw_prof_002` | VW-PROF-002 | ✅ |
| `change_password_page_vw_prof_003` | VW-PROF-003 | ✅ |

**Halaman tanpa desain** harus dibangun sendiri dengan mengacu pada halaman sejenis yang tersedia. Gunakan pola komponen yang konsisten dari `41_REUSABLE_UI_ASSETS.md`.

---

END OF CODING_PROMPT.md
