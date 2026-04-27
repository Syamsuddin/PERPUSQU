-- =====================================================================
-- 15_SEED.sql
-- Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU
-- Acuan wajib:
-- 01_EXECUTIVE_SUMMARY.md
-- 02_STACK_TEKNOLOGI.md
-- 03_ARSITEKTUR_MODULAR.md
-- 04_PRD.md
-- 05_SRS.md
-- 06_USE_CASE.md
-- 07_ROLE_PERMISSION_MATRIX.md
-- 08_MENU_MAP.md
-- 09_ROUTE_MAP.md
-- 10_VIEW_MAP.md
-- 11_CONTROLLER_MAP.md
-- 12_SERVICE_LAYER.md
-- 13_MODEL_MAP.md
-- 14_SCHEMA.sql
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

-- =====================================================================
-- 1. ROLE
-- =====================================================================

INSERT INTO `roles` (`name`, `guard_name`, `created_at`, `updated_at`)
VALUES
    ('Super Admin', 'web', NOW(), NOW()),
    ('Admin Perpustakaan', 'web', NOW(), NOW()),
    ('Pustakawan', 'web', NOW(), NOW()),
    ('Petugas Sirkulasi', 'web', NOW(), NOW()),
    ('Operator Repositori Digital', 'web', NOW(), NOW()),
    ('Pimpinan Perpustakaan', 'web', NOW(), NOW()),
    ('Pengguna OPAC Publik', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `updated_at` = CURRENT_TIMESTAMP;

-- =====================================================================
-- 2. PERMISSION
-- =====================================================================

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
VALUES
    -- Core
    ('core.view_dashboard', 'web', NOW(), NOW()),
    ('core.view_settings', 'web', NOW(), NOW()),
    ('core.update_settings', 'web', NOW(), NOW()),
    ('core.view_institution_profile', 'web', NOW(), NOW()),
    ('core.update_institution_profile', 'web', NOW(), NOW()),
    ('core.view_operational_rules', 'web', NOW(), NOW()),
    ('core.update_operational_rules', 'web', NOW(), NOW()),

    -- Identity and Access
    ('users.view', 'web', NOW(), NOW()),
    ('users.create', 'web', NOW(), NOW()),
    ('users.update', 'web', NOW(), NOW()),
    ('users.delete', 'web', NOW(), NOW()),
    ('users.activate', 'web', NOW(), NOW()),
    ('users.reset_password', 'web', NOW(), NOW()),
    ('roles.view', 'web', NOW(), NOW()),
    ('roles.create', 'web', NOW(), NOW()),
    ('roles.update', 'web', NOW(), NOW()),
    ('roles.delete', 'web', NOW(), NOW()),
    ('roles.manage', 'web', NOW(), NOW()),
    ('permissions.view', 'web', NOW(), NOW()),
    ('permissions.manage', 'web', NOW(), NOW()),
    ('user_roles.assign', 'web', NOW(), NOW()),
    ('own_profile.view', 'web', NOW(), NOW()),
    ('own_profile.update', 'web', NOW(), NOW()),
    ('own_password.change', 'web', NOW(), NOW()),

    -- Master Data
    ('authors.view', 'web', NOW(), NOW()),
    ('authors.create', 'web', NOW(), NOW()),
    ('authors.update', 'web', NOW(), NOW()),
    ('authors.delete', 'web', NOW(), NOW()),

    ('publishers.view', 'web', NOW(), NOW()),
    ('publishers.create', 'web', NOW(), NOW()),
    ('publishers.update', 'web', NOW(), NOW()),
    ('publishers.delete', 'web', NOW(), NOW()),

    ('languages.view', 'web', NOW(), NOW()),
    ('languages.create', 'web', NOW(), NOW()),
    ('languages.update', 'web', NOW(), NOW()),
    ('languages.delete', 'web', NOW(), NOW()),

    ('classifications.view', 'web', NOW(), NOW()),
    ('classifications.create', 'web', NOW(), NOW()),
    ('classifications.update', 'web', NOW(), NOW()),
    ('classifications.delete', 'web', NOW(), NOW()),

    ('subjects.view', 'web', NOW(), NOW()),
    ('subjects.create', 'web', NOW(), NOW()),
    ('subjects.update', 'web', NOW(), NOW()),
    ('subjects.delete', 'web', NOW(), NOW()),

    ('collection_types.view', 'web', NOW(), NOW()),
    ('collection_types.create', 'web', NOW(), NOW()),
    ('collection_types.update', 'web', NOW(), NOW()),
    ('collection_types.delete', 'web', NOW(), NOW()),

    ('rack_locations.view', 'web', NOW(), NOW()),
    ('rack_locations.create', 'web', NOW(), NOW()),
    ('rack_locations.update', 'web', NOW(), NOW()),
    ('rack_locations.delete', 'web', NOW(), NOW()),

    ('faculties.view', 'web', NOW(), NOW()),
    ('faculties.create', 'web', NOW(), NOW()),
    ('faculties.update', 'web', NOW(), NOW()),
    ('faculties.delete', 'web', NOW(), NOW()),

    ('study_programs.view', 'web', NOW(), NOW()),
    ('study_programs.create', 'web', NOW(), NOW()),
    ('study_programs.update', 'web', NOW(), NOW()),
    ('study_programs.delete', 'web', NOW(), NOW()),

    ('item_conditions.view', 'web', NOW(), NOW()),
    ('item_conditions.create', 'web', NOW(), NOW()),
    ('item_conditions.update', 'web', NOW(), NOW()),
    ('item_conditions.delete', 'web', NOW(), NOW()),

    -- Catalog
    ('catalog.view', 'web', NOW(), NOW()),
    ('catalog.create', 'web', NOW(), NOW()),
    ('catalog.update', 'web', NOW(), NOW()),
    ('catalog.delete', 'web', NOW(), NOW()),
    ('catalog.publish', 'web', NOW(), NOW()),
    ('catalog.unpublish', 'web', NOW(), NOW()),
    ('catalog.manage_cover', 'web', NOW(), NOW()),
    ('catalog.link_authors', 'web', NOW(), NOW()),
    ('catalog.link_subjects', 'web', NOW(), NOW()),
    ('catalog.view_detail', 'web', NOW(), NOW()),

    -- Collection
    ('collections.view', 'web', NOW(), NOW()),
    ('collections.create', 'web', NOW(), NOW()),
    ('collections.update', 'web', NOW(), NOW()),
    ('collections.delete', 'web', NOW(), NOW()),
    ('collections.change_status', 'web', NOW(), NOW()),
    ('collections.view_history', 'web', NOW(), NOW()),
    ('collections.view_detail', 'web', NOW(), NOW()),

    -- Member
    ('members.view', 'web', NOW(), NOW()),
    ('members.create', 'web', NOW(), NOW()),
    ('members.update', 'web', NOW(), NOW()),
    ('members.delete', 'web', NOW(), NOW()),
    ('members.activate', 'web', NOW(), NOW()),
    ('members.deactivate', 'web', NOW(), NOW()),
    ('members.block', 'web', NOW(), NOW()),
    ('members.unblock', 'web', NOW(), NOW()),
    ('members.view_history', 'web', NOW(), NOW()),
    ('members.import', 'web', NOW(), NOW()),
    ('members.view_detail', 'web', NOW(), NOW()),

    -- Circulation
    ('circulation.view', 'web', NOW(), NOW()),
    ('circulation.process_loan', 'web', NOW(), NOW()),
    ('circulation.process_return', 'web', NOW(), NOW()),
    ('circulation.process_renewal', 'web', NOW(), NOW()),
    ('circulation.view_active_loans', 'web', NOW(), NOW()),
    ('circulation.view_history', 'web', NOW(), NOW()),
    ('circulation.view_fines', 'web', NOW(), NOW()),
    ('circulation.override_rules', 'web', NOW(), NOW()),

    -- Digital Repository
    ('digital_assets.view', 'web', NOW(), NOW()),
    ('digital_assets.create', 'web', NOW(), NOW()),
    ('digital_assets.update', 'web', NOW(), NOW()),
    ('digital_assets.delete', 'web', NOW(), NOW()),
    ('digital_assets.view_detail', 'web', NOW(), NOW()),
    ('digital_assets.preview', 'web', NOW(), NOW()),
    ('digital_assets.manage_access', 'web', NOW(), NOW()),
    ('digital_assets.publish', 'web', NOW(), NOW()),
    ('digital_assets.unpublish', 'web', NOW(), NOW()),
    ('digital_assets.run_ocr', 'web', NOW(), NOW()),
    ('digital_assets.reindex', 'web', NOW(), NOW()),
    ('digital_assets.download_private', 'web', NOW(), NOW()),

    -- OPAC
    ('opac.search', 'web', NOW(), NOW()),
    ('opac.view_detail', 'web', NOW(), NOW()),
    ('opac.view_availability', 'web', NOW(), NOW()),
    ('opac.preview_public_asset', 'web', NOW(), NOW()),

    -- Reporting
    ('reports.view_dashboard', 'web', NOW(), NOW()),
    ('reports.view_collections', 'web', NOW(), NOW()),
    ('reports.view_members', 'web', NOW(), NOW()),
    ('reports.view_circulation', 'web', NOW(), NOW()),
    ('reports.view_fines', 'web', NOW(), NOW()),
    ('reports.view_popular_collections', 'web', NOW(), NOW()),
    ('reports.view_digital_access', 'web', NOW(), NOW()),
    ('reports.export', 'web', NOW(), NOW()),

    -- Audit and Monitoring
    ('audit_logs.view', 'web', NOW(), NOW()),
    ('audit_logs.view_detail', 'web', NOW(), NOW()),
    ('queue_monitor.view', 'web', NOW(), NOW()),
    ('queue_monitor.manage_retry', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `updated_at` = CURRENT_TIMESTAMP;

-- =====================================================================
-- 3. USER AWAL
-- Password default: Admin@123
-- Hash bcrypt sudah disiapkan agar seed langsung runnable.
-- =====================================================================

INSERT INTO `users`
(
    `name`,
    `username`,
    `email`,
    `password`,
    `is_active`,
    `last_login_at`,
    `created_at`,
    `updated_at`
)
VALUES
(
    'Super Admin PERPUSQU',
    'superadmin',
    'superadmin@perpusqu.local',
    '$2y$12$y2BSYDPPDKI6ZS7JFGLSwutRgbrCG6i0DQ.9RarblIg.hFYebPSni',
    1,
    NULL,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `updated_at` = `updated_at`;

-- =====================================================================
-- 4. ROLE ASSIGNMENT USER AWAL
-- =====================================================================

INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`, `assigned_at`)
SELECT u.id, r.id, NOW()
FROM `users` u
JOIN `roles` r ON r.name = 'Super Admin'
WHERE u.username = 'superadmin';

-- =====================================================================
-- 5. ROLE PERMISSION ASSIGNMENT
-- =====================================================================

-- 5.1 Super Admin mendapat seluruh permission
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Super Admin';

-- 5.2 Admin Perpustakaan
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Admin Perpustakaan'
  AND p.name IN (
    'core.view_dashboard',
    'core.view_settings',
    'core.update_settings',
    'core.view_institution_profile',
    'core.update_institution_profile',
    'core.view_operational_rules',
    'core.update_operational_rules',

    'users.view',
    'users.create',
    'users.update',
    'users.activate',
    'users.reset_password',
    'roles.view',
    'permissions.view',
    'user_roles.assign',
    'own_profile.view',
    'own_profile.update',
    'own_password.change',

    'authors.view',
    'authors.create',
    'authors.update',
    'publishers.view',
    'publishers.create',
    'publishers.update',
    'languages.view',
    'languages.create',
    'languages.update',
    'classifications.view',
    'classifications.create',
    'classifications.update',
    'subjects.view',
    'subjects.create',
    'subjects.update',
    'collection_types.view',
    'collection_types.create',
    'collection_types.update',
    'rack_locations.view',
    'rack_locations.create',
    'rack_locations.update',
    'faculties.view',
    'faculties.create',
    'faculties.update',
    'study_programs.view',
    'study_programs.create',
    'study_programs.update',
    'item_conditions.view',
    'item_conditions.create',
    'item_conditions.update',

    'catalog.view',
    'catalog.create',
    'catalog.update',
    'catalog.publish',
    'catalog.unpublish',
    'catalog.manage_cover',
    'catalog.link_authors',
    'catalog.link_subjects',
    'catalog.view_detail',

    'collections.view',
    'collections.create',
    'collections.update',
    'collections.change_status',
    'collections.view_history',
    'collections.view_detail',

    'members.view',
    'members.create',
    'members.update',
    'members.activate',
    'members.deactivate',
    'members.block',
    'members.unblock',
    'members.view_history',
    'members.view_detail',

    'circulation.view',
    'circulation.process_loan',
    'circulation.process_return',
    'circulation.process_renewal',
    'circulation.view_active_loans',
    'circulation.view_history',
    'circulation.view_fines',

    'digital_assets.view',
    'digital_assets.create',
    'digital_assets.update',
    'digital_assets.view_detail',
    'digital_assets.preview',
    'digital_assets.manage_access',
    'digital_assets.publish',
    'digital_assets.unpublish',
    'digital_assets.run_ocr',
    'digital_assets.reindex',

    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset',

    'reports.view_dashboard',
    'reports.view_collections',
    'reports.view_members',
    'reports.view_circulation',
    'reports.view_fines',
    'reports.view_popular_collections',
    'reports.view_digital_access',
    'reports.export',

    'audit_logs.view',
    'audit_logs.view_detail',
    'queue_monitor.view'
  );

-- 5.3 Pustakawan
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Pustakawan'
  AND p.name IN (
    'own_profile.view',
    'own_profile.update',
    'own_password.change',

    'core.view_dashboard',
    'core.view_institution_profile',
    'core.view_operational_rules',

    'authors.view',
    'authors.create',
    'authors.update',
    'publishers.view',
    'publishers.create',
    'publishers.update',
    'languages.view',
    'classifications.view',
    'subjects.view',
    'subjects.create',
    'subjects.update',
    'collection_types.view',
    'rack_locations.view',
    'item_conditions.view',

    'catalog.view',
    'catalog.create',
    'catalog.update',
    'catalog.manage_cover',
    'catalog.link_authors',
    'catalog.link_subjects',
    'catalog.view_detail',

    'collections.view',
    'collections.create',
    'collections.update',
    'collections.change_status',
    'collections.view_history',
    'collections.view_detail',

    'members.view',
    'members.view_detail',
    'members.view_history',

    'digital_assets.view',
    'digital_assets.create',
    'digital_assets.update',
    'digital_assets.view_detail',
    'digital_assets.preview',
    'digital_assets.run_ocr',
    'digital_assets.reindex',

    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset',

    'reports.view_dashboard',
    'reports.view_collections',
    'reports.view_digital_access'
  );

-- 5.4 Petugas Sirkulasi
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Petugas Sirkulasi'
  AND p.name IN (
    'own_profile.view',
    'own_profile.update',
    'own_password.change',

    'core.view_dashboard',
    'core.view_operational_rules',

    'catalog.view',
    'catalog.view_detail',

    'collections.view',
    'collections.view_detail',

    'members.view',
    'members.view_detail',
    'members.view_history',

    'circulation.view',
    'circulation.process_loan',
    'circulation.process_return',
    'circulation.process_renewal',
    'circulation.view_active_loans',
    'circulation.view_history',
    'circulation.view_fines',

    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset',

    'reports.view_dashboard',
    'reports.view_circulation',
    'reports.view_fines'
  );

-- 5.5 Operator Repositori Digital
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Operator Repositori Digital'
  AND p.name IN (
    'own_profile.view',
    'own_profile.update',
    'own_password.change',

    'core.view_dashboard',
    'core.view_institution_profile',

    'authors.view',
    'publishers.view',
    'languages.view',
    'subjects.view',
    'collection_types.view',

    'catalog.view',
    'catalog.view_detail',

    'digital_assets.view',
    'digital_assets.create',
    'digital_assets.update',
    'digital_assets.delete',
    'digital_assets.view_detail',
    'digital_assets.preview',
    'digital_assets.manage_access',
    'digital_assets.publish',
    'digital_assets.unpublish',
    'digital_assets.run_ocr',
    'digital_assets.reindex',
    'digital_assets.download_private',

    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset',

    'reports.view_dashboard',
    'reports.view_digital_access'
  );

-- 5.6 Pimpinan Perpustakaan
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Pimpinan Perpustakaan'
  AND p.name IN (
    'own_profile.view',
    'own_profile.update',
    'own_password.change',

    'core.view_dashboard',
    'core.view_institution_profile',
    'core.view_operational_rules',

    'catalog.view',
    'catalog.view_detail',

    'collections.view',
    'collections.view_detail',

    'members.view',
    'members.view_detail',

    'circulation.view',
    'circulation.view_active_loans',
    'circulation.view_history',
    'circulation.view_fines',

    'digital_assets.view',
    'digital_assets.view_detail',
    'digital_assets.preview',

    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset',

    'reports.view_dashboard',
    'reports.view_collections',
    'reports.view_members',
    'reports.view_circulation',
    'reports.view_fines',
    'reports.view_popular_collections',
    'reports.view_digital_access',
    'reports.export'
  );

-- 5.7 Pengguna OPAC Publik
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `assigned_at`)
SELECT r.id, p.id, NOW()
FROM `roles` r
JOIN `permissions` p
WHERE r.name = 'Pengguna OPAC Publik'
  AND p.name IN (
    'opac.search',
    'opac.view_detail',
    'opac.view_availability',
    'opac.preview_public_asset'
  );

-- =====================================================================
-- 6. INSTITUTION PROFILE DEFAULT
-- =====================================================================

INSERT INTO `institution_profiles`
(
    `institution_name`,
    `library_name`,
    `address`,
    `phone`,
    `email`,
    `website`,
    `logo_path`,
    `about_text`,
    `created_at`,
    `updated_at`
)
SELECT
    'Nama Institusi Belum Diatur',
    'Perpustakaan Kampus',
    'Alamat belum diatur',
    NULL,
    NULL,
    NULL,
    NULL,
    'Profil perpustakaan belum diatur.',
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `institution_profiles`
);

-- =====================================================================
-- 7. SYSTEM SETTINGS DEFAULT
-- =====================================================================

INSERT INTO `system_settings`
(`key`, `value`, `type`, `group_name`, `is_public`, `created_at`, `updated_at`)
VALUES
    ('app.name', 'PERPUSQU', 'string', 'application', 1, NOW(), NOW()),
    ('app.locale', 'id', 'string', 'application', 0, NOW(), NOW()),
    ('app.timezone', 'Asia/Pontianak', 'string', 'application', 0, NOW(), NOW()),

    ('library.loan.default_days', '7', 'integer', 'circulation', 0, NOW(), NOW()),
    ('library.loan.max_active_loans', '3', 'integer', 'circulation', 0, NOW(), NOW()),
    ('library.loan.max_renewal_count', '1', 'integer', 'circulation', 0, NOW(), NOW()),
    ('library.circulation.allow_renewal', '1', 'boolean', 'circulation', 0, NOW(), NOW()),
    ('library.circulation.require_active_member', '1', 'boolean', 'circulation', 0, NOW(), NOW()),
    ('library.circulation.require_unblocked_member', '1', 'boolean', 'circulation', 0, NOW(), NOW()),

    ('library.fine.daily_amount', '1000.00', 'decimal', 'circulation', 0, NOW(), NOW()),

    ('library.asset.max_upload_size_mb', '50', 'integer', 'digital_repository', 0, NOW(), NOW()),
    ('library.asset.allowed_mime_types', '["application/pdf"]', 'json', 'digital_repository', 0, NOW(), NOW()),
    ('library.asset.allowed_extensions', '["pdf"]', 'json', 'digital_repository', 0, NOW(), NOW()),
    ('library.asset.ocr_enabled', '1', 'boolean', 'digital_repository', 0, NOW(), NOW()),
    ('library.asset.public_preview_enabled', '1', 'boolean', 'digital_repository', 0, NOW(), NOW()),

    ('library.opac.home_featured_limit', '8', 'integer', 'opac', 1, NOW(), NOW()),
    ('library.opac.search_result_limit', '20', 'integer', 'opac', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `value` = VALUES(`value`),
    `type` = VALUES(`type`),
    `group_name` = VALUES(`group_name`),
    `is_public` = VALUES(`is_public`),
    `updated_at` = CURRENT_TIMESTAMP;

-- =====================================================================
-- 8. MASTER DATA DEFAULT
-- =====================================================================

-- 8.1 Languages
INSERT INTO `languages`
(`code`, `name`, `is_active`, `created_at`, `updated_at`)
VALUES
    ('id', 'Bahasa Indonesia', 1, NOW(), NOW()),
    ('en', 'English', 1, NOW(), NOW()),
    ('ar', 'Bahasa Arab', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = CURRENT_TIMESTAMP;

-- 8.2 Collection Types
INSERT INTO `collection_types`
(`code`, `name`, `is_active`, `created_at`, `updated_at`)
VALUES
    ('BOOK', 'Buku', 1, NOW(), NOW()),
    ('THESIS', 'Skripsi', 1, NOW(), NOW()),
    ('MASTER_THESIS', 'Tesis', 1, NOW(), NOW()),
    ('DISSERTATION', 'Disertasi', 1, NOW(), NOW()),
    ('JOURNAL', 'Jurnal', 1, NOW(), NOW()),
    ('MODULE', 'Modul', 1, NOW(), NOW()),
    ('REFERENCE', 'Referensi', 1, NOW(), NOW()),
    ('OTHER', 'Lainnya', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = CURRENT_TIMESTAMP;

-- 8.3 Item Conditions
INSERT INTO `item_conditions`
(`code`, `name`, `severity_level`, `is_active`, `created_at`, `updated_at`)
VALUES
    ('GOOD', 'Baik', 1, 1, NOW(), NOW()),
    ('DMG_MINOR', 'Rusak Ringan', 2, 1, NOW(), NOW()),
    ('DMG_MEDIUM', 'Rusak Sedang', 3, 1, NOW(), NOW()),
    ('DMG_MAJOR', 'Rusak Berat', 4, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `severity_level` = VALUES(`severity_level`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = CURRENT_TIMESTAMP;

-- 8.4 Rack Locations
INSERT INTO `rack_locations`
(`code`, `name`, `floor`, `room`, `description`, `is_active`, `created_at`, `updated_at`)
VALUES
    ('RAK-UMUM-01', 'Rak Umum 01', '1', 'Ruang Koleksi Umum', 'Lokasi awal koleksi umum', 1, NOW(), NOW()),
    ('RAK-REF-01', 'Rak Referensi 01', '1', 'Ruang Referensi', 'Lokasi awal koleksi referensi', 1, NOW(), NOW()),
    ('RAK-TA-01', 'Rak Tugas Akhir 01', '1', 'Ruang Karya Ilmiah', 'Lokasi awal karya ilmiah kampus', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `floor` = VALUES(`floor`),
    `room` = VALUES(`room`),
    `description` = VALUES(`description`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = CURRENT_TIMESTAMP;

-- 8.5 Classifications, top level ringkas
INSERT INTO `classifications`
(`parent_id`, `code`, `name`, `is_active`, `created_at`, `updated_at`)
VALUES
    (NULL, '000', 'Karya Umum', 1, NOW(), NOW()),
    (NULL, '100', 'Filsafat dan Psikologi', 1, NOW(), NOW()),
    (NULL, '200', 'Agama', 1, NOW(), NOW()),
    (NULL, '300', 'Ilmu Sosial', 1, NOW(), NOW()),
    (NULL, '400', 'Bahasa', 1, NOW(), NOW()),
    (NULL, '500', 'Ilmu Murni', 1, NOW(), NOW()),
    (NULL, '600', 'Teknologi', 1, NOW(), NOW()),
    (NULL, '700', 'Seni dan Rekreasi', 1, NOW(), NOW()),
    (NULL, '800', 'Sastra', 1, NOW(), NOW()),
    (NULL, '900', 'Sejarah dan Geografi', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = CURRENT_TIMESTAMP;

COMMIT;

-- END OF 15_SEED.sql