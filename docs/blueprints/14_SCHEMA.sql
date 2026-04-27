-- =====================================================================
-- 14_SCHEMA.sql
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
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- =====================================================================
-- 1. IDENTITY AND ACCESS
-- =====================================================================

CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` DATETIME NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_username` (`username`),
    UNIQUE KEY `uq_users_email` (`email`),
    KEY `idx_users_is_active` (`is_active`),
    KEY `idx_users_last_login_at` (`last_login_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Akun pengguna internal sistem';

CREATE TABLE IF NOT EXISTS `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `guard_name` VARCHAR(100) NOT NULL DEFAULT 'web',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_roles_name_guard` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Role sistem';

CREATE TABLE IF NOT EXISTS `permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `guard_name` VARCHAR(100) NOT NULL DEFAULT 'web',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_permissions_name_guard` (`name`, `guard_name`),
    KEY `idx_permissions_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Permission granular sistem';

CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `role_id`),
    KEY `idx_user_roles_role_id` (`role_id`),
    CONSTRAINT `fk_user_roles_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_user_roles_role`
        FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relasi many to many user ke role';

CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`role_id`, `permission_id`),
    KEY `idx_role_permissions_permission_id` (`permission_id`),
    CONSTRAINT `fk_role_permissions_role`
        FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_role_permissions_permission`
        FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relasi many to many role ke permission';

-- =====================================================================
-- 2. CORE
-- =====================================================================

CREATE TABLE IF NOT EXISTS `institution_profiles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `institution_name` VARCHAR(255) NOT NULL,
    `library_name` VARCHAR(255) NOT NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(150) NULL,
    `website` VARCHAR(255) NULL,
    `logo_path` VARCHAR(255) NULL,
    `about_text` LONGTEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Profil institusi dan perpustakaan';

CREATE TABLE IF NOT EXISTS `system_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(150) NOT NULL,
    `value` LONGTEXT NULL,
    `type` VARCHAR(30) NOT NULL DEFAULT 'string',
    `group_name` VARCHAR(100) NOT NULL DEFAULT 'general',
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_system_settings_key` (`key`),
    KEY `idx_system_settings_group_name` (`group_name`),
    KEY `idx_system_settings_is_public` (`is_public`),
    CONSTRAINT `chk_system_settings_type`
        CHECK (`type` IN ('string', 'integer', 'decimal', 'boolean', 'json', 'text', 'date', 'datetime'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pengaturan sistem dan aturan operasional';

-- =====================================================================
-- 3. MASTER DATA
-- =====================================================================

CREATE TABLE IF NOT EXISTS `authors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `normalized_name` VARCHAR(200) NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_authors_name` (`name`),
    KEY `idx_authors_normalized_name` (`normalized_name`),
    KEY `idx_authors_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master pengarang';

CREATE TABLE IF NOT EXISTS `publishers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `city` VARCHAR(150) NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_publishers_name` (`name`),
    KEY `idx_publishers_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master penerbit';

CREATE TABLE IF NOT EXISTS `languages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_languages_code` (`code`),
    UNIQUE KEY `uq_languages_name` (`name`),
    KEY `idx_languages_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master bahasa';

CREATE TABLE IF NOT EXISTS `classifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_classifications_code` (`code`),
    KEY `idx_classifications_parent_id` (`parent_id`),
    KEY `idx_classifications_is_active` (`is_active`),
    CONSTRAINT `fk_classifications_parent`
        FOREIGN KEY (`parent_id`) REFERENCES `classifications` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master klasifikasi koleksi';

CREATE TABLE IF NOT EXISTS `subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_subjects_name` (`name`),
    KEY `idx_subjects_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master subjek';

CREATE TABLE IF NOT EXISTS `collection_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_collection_types_code` (`code`),
    UNIQUE KEY `uq_collection_types_name` (`name`),
    KEY `idx_collection_types_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master jenis koleksi';

CREATE TABLE IF NOT EXISTS `rack_locations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `floor` VARCHAR(50) NULL,
    `room` VARCHAR(100) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_rack_locations_code` (`code`),
    KEY `idx_rack_locations_name` (`name`),
    KEY `idx_rack_locations_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master lokasi rak';

CREATE TABLE IF NOT EXISTS `faculties` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_faculties_code` (`code`),
    UNIQUE KEY `uq_faculties_name` (`name`),
    KEY `idx_faculties_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master fakultas';

CREATE TABLE IF NOT EXISTS `study_programs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `faculty_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_study_programs_code` (`code`),
    KEY `idx_study_programs_faculty_id` (`faculty_id`),
    KEY `idx_study_programs_is_active` (`is_active`),
    CONSTRAINT `fk_study_programs_faculty`
        FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master program studi';

CREATE TABLE IF NOT EXISTS `item_conditions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `severity_level` INT NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_item_conditions_code` (`code`),
    UNIQUE KEY `uq_item_conditions_name` (`name`),
    KEY `idx_item_conditions_is_active` (`is_active`),
    CONSTRAINT `chk_item_conditions_severity_level`
        CHECK (`severity_level` >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master kondisi item';

-- =====================================================================
-- 4. CATALOG
-- =====================================================================

CREATE TABLE IF NOT EXISTS `bibliographic_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `publisher_id` BIGINT UNSIGNED NULL,
    `language_id` BIGINT UNSIGNED NULL,
    `classification_id` BIGINT UNSIGNED NULL,
    `collection_type_id` BIGINT UNSIGNED NOT NULL,
    `publication_year` YEAR NULL,
    `isbn` VARCHAR(50) NULL,
    `edition` VARCHAR(100) NULL,
    `keywords` TEXT NULL,
    `abstract` LONGTEXT NULL,
    `cover_path` VARCHAR(255) NULL,
    `publication_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `metadata_json` JSON NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_bibliographic_records_slug` (`slug`),
    KEY `idx_bibliographic_records_publisher_id` (`publisher_id`),
    KEY `idx_bibliographic_records_language_id` (`language_id`),
    KEY `idx_bibliographic_records_classification_id` (`classification_id`),
    KEY `idx_bibliographic_records_collection_type_id` (`collection_type_id`),
    KEY `idx_bibliographic_records_publication_year` (`publication_year`),
    KEY `idx_bibliographic_records_publication_status` (`publication_status`),
    KEY `idx_bibliographic_records_is_public` (`is_public`),
    KEY `idx_bibliographic_records_created_by` (`created_by`),
    KEY `idx_bibliographic_records_updated_by` (`updated_by`),
    KEY `idx_bibliographic_records_isbn` (`isbn`),
    FULLTEXT KEY `ft_bibliographic_records_search` (`title`, `keywords`, `abstract`),
    CONSTRAINT `fk_bibliographic_records_publisher`
        FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_bibliographic_records_language`
        FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_bibliographic_records_classification`
        FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_bibliographic_records_collection_type`
        FOREIGN KEY (`collection_type_id`) REFERENCES `collection_types` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_bibliographic_records_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_bibliographic_records_updated_by`
        FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_bibliographic_records_publication_status`
        CHECK (`publication_status` IN ('draft', 'published', 'unpublished', 'archived'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Metadata induk bibliografi';

CREATE TABLE IF NOT EXISTS `bibliographic_record_authors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bibliographic_record_id` BIGINT UNSIGNED NOT NULL,
    `author_id` BIGINT UNSIGNED NOT NULL,
    `author_order` INT NOT NULL DEFAULT 1,
    `role_label` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_bibliographic_record_authors_unique` (`bibliographic_record_id`, `author_id`),
    KEY `idx_bibliographic_record_authors_author_id` (`author_id`),
    KEY `idx_bibliographic_record_authors_order` (`author_order`),
    CONSTRAINT `fk_bibliographic_record_authors_record`
        FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_bibliographic_record_authors_author`
        FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `chk_bibliographic_record_authors_author_order`
        CHECK (`author_order` >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pivot bibliographic record ke author';

CREATE TABLE IF NOT EXISTS `bibliographic_record_subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bibliographic_record_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_bibliographic_record_subjects_unique` (`bibliographic_record_id`, `subject_id`),
    KEY `idx_bibliographic_record_subjects_subject_id` (`subject_id`),
    CONSTRAINT `fk_bibliographic_record_subjects_record`
        FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_bibliographic_record_subjects_subject`
        FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pivot bibliographic record ke subject';

-- =====================================================================
-- 5. COLLECTION
-- =====================================================================

CREATE TABLE IF NOT EXISTS `physical_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bibliographic_record_id` BIGINT UNSIGNED NOT NULL,
    `rack_location_id` BIGINT UNSIGNED NULL,
    `item_condition_id` BIGINT UNSIGNED NULL,
    `barcode` VARCHAR(100) NOT NULL,
    `inventory_code` VARCHAR(100) NULL,
    `acquisition_date` DATE NULL,
    `item_status` VARCHAR(30) NOT NULL DEFAULT 'available',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_physical_items_barcode` (`barcode`),
    UNIQUE KEY `uq_physical_items_inventory_code` (`inventory_code`),
    KEY `idx_physical_items_record_id` (`bibliographic_record_id`),
    KEY `idx_physical_items_rack_location_id` (`rack_location_id`),
    KEY `idx_physical_items_item_condition_id` (`item_condition_id`),
    KEY `idx_physical_items_item_status` (`item_status`),
    KEY `idx_physical_items_acquisition_date` (`acquisition_date`),
    CONSTRAINT `fk_physical_items_record`
        FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_physical_items_rack_location`
        FOREIGN KEY (`rack_location_id`) REFERENCES `rack_locations` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_physical_items_item_condition`
        FOREIGN KEY (`item_condition_id`) REFERENCES `item_conditions` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_physical_items_item_status`
        CHECK (`item_status` IN ('available', 'loaned', 'damaged', 'lost', 'repair', 'inactive'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Eksemplar fisik koleksi';

CREATE TABLE IF NOT EXISTS `physical_item_status_histories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `physical_item_id` BIGINT UNSIGNED NOT NULL,
    `old_status` VARCHAR(30) NULL,
    `new_status` VARCHAR(30) NOT NULL,
    `reason` TEXT NULL,
    `changed_by` BIGINT UNSIGNED NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_physical_item_status_histories_item_id` (`physical_item_id`),
    KEY `idx_physical_item_status_histories_changed_by` (`changed_by`),
    KEY `idx_physical_item_status_histories_created_at` (`created_at`),
    CONSTRAINT `fk_item_status_histories_item`
        FOREIGN KEY (`physical_item_id`) REFERENCES `physical_items` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_item_status_histories_changed_by`
        FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_item_status_histories_old_status`
        CHECK (`old_status` IS NULL OR `old_status` IN ('available', 'loaned', 'damaged', 'lost', 'repair', 'inactive')),
    CONSTRAINT `chk_item_status_histories_new_status`
        CHECK (`new_status` IN ('available', 'loaned', 'damaged', 'lost', 'repair', 'inactive'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Riwayat perubahan status item';

-- =====================================================================
-- 6. MEMBER
-- =====================================================================

CREATE TABLE IF NOT EXISTS `members` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `member_number` VARCHAR(100) NOT NULL,
    `member_type` VARCHAR(30) NOT NULL,
    `identity_number` VARCHAR(100) NULL,
    `name` VARCHAR(200) NOT NULL,
    `email` VARCHAR(150) NULL,
    `phone` VARCHAR(50) NULL,
    `faculty_id` BIGINT UNSIGNED NULL,
    `study_program_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_blocked` TINYINT(1) NOT NULL DEFAULT 0,
    `blocked_reason` TEXT NULL,
    `blocked_at` DATETIME NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_members_member_number` (`member_number`),
    UNIQUE KEY `uq_members_identity_number` (`identity_number`),
    KEY `idx_members_member_type` (`member_type`),
    KEY `idx_members_faculty_id` (`faculty_id`),
    KEY `idx_members_study_program_id` (`study_program_id`),
    KEY `idx_members_is_active` (`is_active`),
    KEY `idx_members_is_blocked` (`is_blocked`),
    KEY `idx_members_name` (`name`),
    KEY `idx_members_email` (`email`),
    CONSTRAINT `fk_members_faculty`
        FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_members_study_program`
        FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_members_member_type`
        CHECK (`member_type` IN ('student', 'lecturer', 'staff', 'alumni', 'guest'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Anggota perpustakaan';

-- =====================================================================
-- 7. CIRCULATION
-- =====================================================================

CREATE TABLE IF NOT EXISTS `loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `member_id` BIGINT UNSIGNED NOT NULL,
    `physical_item_id` BIGINT UNSIGNED NOT NULL,
    `loan_date` DATETIME NOT NULL,
    `due_date` DATETIME NOT NULL,
    `returned_at` DATETIME NULL,
    `loan_status` VARCHAR(30) NOT NULL DEFAULT 'active',
    `loaned_by` BIGINT UNSIGNED NULL,
    `closed_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `active_physical_item_id` BIGINT GENERATED ALWAYS AS (
        CASE WHEN `loan_status` = 'active' THEN `physical_item_id` ELSE NULL END
    ) STORED,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_loans_active_physical_item_id` (`active_physical_item_id`),
    KEY `idx_loans_member_id` (`member_id`),
    KEY `idx_loans_physical_item_id` (`physical_item_id`),
    KEY `idx_loans_loan_status` (`loan_status`),
    KEY `idx_loans_loan_date` (`loan_date`),
    KEY `idx_loans_due_date` (`due_date`),
    KEY `idx_loans_loaned_by` (`loaned_by`),
    KEY `idx_loans_closed_by` (`closed_by`),
    CONSTRAINT `fk_loans_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_loans_physical_item`
        FOREIGN KEY (`physical_item_id`) REFERENCES `physical_items` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_loans_loaned_by`
        FOREIGN KEY (`loaned_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_loans_closed_by`
        FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_loans_loan_status`
        CHECK (`loan_status` IN ('active', 'returned', 'cancelled')),
    CONSTRAINT `chk_loans_due_after_loan`
        CHECK (`due_date` >= `loan_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Transaksi pinjam item fisik';

CREATE TABLE IF NOT EXISTS `loan_renewals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `loan_id` BIGINT UNSIGNED NOT NULL,
    `old_due_date` DATETIME NOT NULL,
    `new_due_date` DATETIME NOT NULL,
    `renewed_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_loan_renewals_loan_id` (`loan_id`),
    KEY `idx_loan_renewals_renewed_by` (`renewed_by`),
    CONSTRAINT `fk_loan_renewals_loan`
        FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_loan_renewals_renewed_by`
        FOREIGN KEY (`renewed_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_loan_renewals_new_due_after_old_due`
        CHECK (`new_due_date` >= `old_due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Riwayat perpanjangan pinjaman';

CREATE TABLE IF NOT EXISTS `return_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `loan_id` BIGINT UNSIGNED NOT NULL,
    `physical_item_id` BIGINT UNSIGNED NOT NULL,
    `returned_at` DATETIME NOT NULL,
    `returned_by` BIGINT UNSIGNED NULL,
    `returned_condition_id` BIGINT UNSIGNED NULL,
    `late_days` INT NOT NULL DEFAULT 0,
    `fine_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_return_transactions_loan_id` (`loan_id`),
    KEY `idx_return_transactions_physical_item_id` (`physical_item_id`),
    KEY `idx_return_transactions_returned_by` (`returned_by`),
    KEY `idx_return_transactions_returned_condition_id` (`returned_condition_id`),
    KEY `idx_return_transactions_returned_at` (`returned_at`),
    CONSTRAINT `fk_return_transactions_loan`
        FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_return_transactions_physical_item`
        FOREIGN KEY (`physical_item_id`) REFERENCES `physical_items` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_return_transactions_returned_by`
        FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `fk_return_transactions_returned_condition`
        FOREIGN KEY (`returned_condition_id`) REFERENCES `item_conditions` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_return_transactions_late_days`
        CHECK (`late_days` >= 0),
    CONSTRAINT `chk_return_transactions_fine_amount`
        CHECK (`fine_amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detail transaksi pengembalian';

CREATE TABLE IF NOT EXISTS `fines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `loan_id` BIGINT UNSIGNED NOT NULL,
    `member_id` BIGINT UNSIGNED NOT NULL,
    `fine_type` VARCHAR(30) NOT NULL DEFAULT 'overdue',
    `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `late_days` INT NOT NULL DEFAULT 0,
    `status` VARCHAR(30) NOT NULL DEFAULT 'outstanding',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_fines_loan_id` (`loan_id`),
    KEY `idx_fines_member_id` (`member_id`),
    KEY `idx_fines_status` (`status`),
    KEY `idx_fines_fine_type` (`fine_type`),
    CONSTRAINT `fk_fines_loan`
        FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_fines_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `chk_fines_amount`
        CHECK (`amount` >= 0),
    CONSTRAINT `chk_fines_late_days`
        CHECK (`late_days` >= 0),
    CONSTRAINT `chk_fines_fine_type`
        CHECK (`fine_type` IN ('overdue', 'damage', 'loss', 'manual_adjustment')),
    CONSTRAINT `chk_fines_status`
        CHECK (`status` IN ('outstanding', 'settled', 'waived', 'cancelled'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Denda anggota';

-- =====================================================================
-- 8. DIGITAL REPOSITORY
-- =====================================================================

CREATE TABLE IF NOT EXISTS `digital_assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bibliographic_record_id` BIGINT UNSIGNED NOT NULL,
    `asset_type` VARCHAR(40) NOT NULL DEFAULT 'other',
    `file_name` VARCHAR(255) NOT NULL,
    `original_file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `mime_type` VARCHAR(150) NOT NULL,
    `file_extension` VARCHAR(30) NOT NULL,
    `file_size` BIGINT UNSIGNED NOT NULL,
    `checksum` VARCHAR(128) NULL,
    `title` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `publication_status` VARCHAR(30) NOT NULL DEFAULT 'draft',
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `is_embargoed` TINYINT(1) NOT NULL DEFAULT 0,
    `embargo_until` DATETIME NULL,
    `ocr_status` VARCHAR(30) NOT NULL DEFAULT 'not_requested',
    `index_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `ocr_attempted_at` DATETIME NULL,
    `last_indexed_at` DATETIME NULL,
    `uploaded_by` BIGINT UNSIGNED NULL,
    `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_digital_assets_file_path` (`file_path`),
    KEY `idx_digital_assets_record_id` (`bibliographic_record_id`),
    KEY `idx_digital_assets_asset_type` (`asset_type`),
    KEY `idx_digital_assets_publication_status` (`publication_status`),
    KEY `idx_digital_assets_is_public` (`is_public`),
    KEY `idx_digital_assets_is_embargoed` (`is_embargoed`),
    KEY `idx_digital_assets_uploaded_by` (`uploaded_by`),
    KEY `idx_digital_assets_ocr_status` (`ocr_status`),
    KEY `idx_digital_assets_index_status` (`index_status`),
    KEY `idx_digital_assets_uploaded_at` (`uploaded_at`),
    KEY `idx_digital_assets_checksum` (`checksum`),
    CONSTRAINT `fk_digital_assets_record`
        FOREIGN KEY (`bibliographic_record_id`) REFERENCES `bibliographic_records` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT `fk_digital_assets_uploaded_by`
        FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `chk_digital_assets_asset_type`
        CHECK (`asset_type` IN ('ebook', 'thesis', 'dissertation', 'journal_article', 'module', 'scanned_book', 'supplementary', 'other')),
    CONSTRAINT `chk_digital_assets_publication_status`
        CHECK (`publication_status` IN ('draft', 'published', 'unpublished', 'archived')),
    CONSTRAINT `chk_digital_assets_ocr_status`
        CHECK (`ocr_status` IN ('not_requested', 'queued', 'processing', 'success', 'failed')),
    CONSTRAINT `chk_digital_assets_index_status`
        CHECK (`index_status` IN ('pending', 'queued', 'processing', 'indexed', 'failed'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Aset digital koleksi';

CREATE TABLE IF NOT EXISTS `digital_asset_access_rules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `digital_asset_id` BIGINT UNSIGNED NOT NULL,
    `access_scope` VARCHAR(30) NOT NULL DEFAULT 'public',
    `role_name` VARCHAR(100) NULL,
    `member_type` VARCHAR(30) NULL,
    `allow_preview` TINYINT(1) NOT NULL DEFAULT 1,
    `allow_download` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_digital_asset_access_rules_asset_id` (`digital_asset_id`),
    KEY `idx_digital_asset_access_rules_scope` (`access_scope`),
    KEY `idx_digital_asset_access_rules_role_name` (`role_name`),
    KEY `idx_digital_asset_access_rules_member_type` (`member_type`),
    CONSTRAINT `fk_digital_asset_access_rules_asset`
        FOREIGN KEY (`digital_asset_id`) REFERENCES `digital_assets` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `chk_digital_asset_access_rules_scope`
        CHECK (`access_scope` IN ('public', 'internal', 'role_based', 'member_type_based')),
    CONSTRAINT `chk_digital_asset_access_rules_member_type`
        CHECK (`member_type` IS NULL OR `member_type` IN ('student', 'lecturer', 'staff', 'alumni', 'guest'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Aturan akses aset digital';

CREATE TABLE IF NOT EXISTS `ocr_texts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `digital_asset_id` BIGINT UNSIGNED NOT NULL,
    `source_type` VARCHAR(30) NOT NULL DEFAULT 'ocr',
    `extracted_text` LONGTEXT NULL,
    `extraction_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `extracted_at` DATETIME NULL,
    `error_message` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ocr_texts_digital_asset_id` (`digital_asset_id`),
    KEY `idx_ocr_texts_extraction_status` (`extraction_status`),
    CONSTRAINT `fk_ocr_texts_asset`
        FOREIGN KEY (`digital_asset_id`) REFERENCES `digital_assets` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `chk_ocr_texts_source_type`
        CHECK (`source_type` IN ('ocr', 'extracted_text', 'manual')),
    CONSTRAINT `chk_ocr_texts_extraction_status`
        CHECK (`extraction_status` IN ('pending', 'processing', 'success', 'failed'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hasil OCR atau ekstraksi teks aset digital';

-- =====================================================================
-- 9. AUDIT
-- =====================================================================

CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `module_name` VARCHAR(100) NOT NULL,
    `subject_type` VARCHAR(150) NULL,
    `subject_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(64) NULL,
    `user_agent` VARCHAR(500) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_activity_logs_user_id` (`user_id`),
    KEY `idx_activity_logs_action` (`action`),
    KEY `idx_activity_logs_module_name` (`module_name`),
    KEY `idx_activity_logs_subject` (`subject_type`, `subject_id`),
    KEY `idx_activity_logs_created_at` (`created_at`),
    CONSTRAINT `fk_activity_logs_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail aktivitas sistem';

-- =====================================================================
-- 10. OPTIONAL SUPPORT TABLES
-- Tabel di bawah ini sesuai blueprint, tetapi bersifat opsional terkontrol.
-- Tetap disiapkan agar perluasan fase berikutnya tidak memerlukan desain ulang.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `report_export_histories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exported_by` BIGINT UNSIGNED NULL,
    `report_type` VARCHAR(100) NOT NULL,
    `filter_payload` JSON NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_report_export_histories_exported_by` (`exported_by`),
    KEY `idx_report_export_histories_report_type` (`report_type`),
    KEY `idx_report_export_histories_created_at` (`created_at`),
    CONSTRAINT `fk_report_export_histories_exported_by`
        FOREIGN KEY (`exported_by`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Histori ekspor laporan, opsional';

CREATE TABLE IF NOT EXISTS `queue_monitor_snapshots` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `snapshot_name` VARCHAR(150) NOT NULL,
    `payload` JSON NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_queue_monitor_snapshots_snapshot_name` (`snapshot_name`),
    KEY `idx_queue_monitor_snapshots_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Snapshot monitoring queue, opsional';

-- =====================================================================
-- 11. RECOMMENDED SEED REFERENCE
-- Nilai berikut harus di-seed pada 15_SEED.sql, bukan di file ini.
-- 1. roles
-- 2. permissions
-- 3. collection_types
-- 4. item_conditions
-- 5. system_settings
-- 6. institution_profiles
-- =====================================================================

-- =====================================================================
-- 12. INDEX REVIEW CHECKLIST
-- 1. Search katalog publik utama tetap memakai Meilisearch
-- 2. Index MySQL di sini dipakai untuk lookup admin, filter, dan integritas
-- 3. Query berat laporan tetap harus dioptimalkan pada service layer
-- =====================================================================

-- END OF 14_SCHEMA.sql