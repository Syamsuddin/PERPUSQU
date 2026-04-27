# 17_WORKFLOW_STATE_MACHINE.md

## 1. Nama Dokumen

Workflow State Machine Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint state machine, lifecycle status, dan aturan transisi proses

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan workflow, status enum, transisi aksi, guard rule, service orchestration, audit trail, dan validasi proses

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan state machine resmi untuk seluruh objek proses utama di PERPUSQU, agar setiap perubahan status pada akun, anggota, bibliographic record, item fisik, pinjaman, denda, aset digital, OCR, dan indexing berjalan konsisten. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, tester, dan reviewer agar tidak ada transisi status liar, tidak ada status yang bertabrakan, dan tidak ada inkonsistensi antara controller, service, model, schema, serta UI.

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

Aturan wajib:

1. Semua status harus sesuai dengan field dan enum pada schema.
2. Semua transisi harus sesuai dengan service layer resmi.
3. Semua transisi utama harus sesuai dengan use case resmi.
4. Semua transisi sensitif harus memicu audit log.
5. Semua guard rule wajib ditegakkan di service layer.
6. UI hanya boleh menampilkan aksi yang sah untuk state saat ini.
7. Tidak boleh ada transisi proses penting yang tidak tercatat di dokumen ini.

## 4. Ruang Lingkup State Machine

Dokumen ini mencakup state machine untuk:

1. User internal
2. Member
3. Bibliographic record
4. Physical item
5. Loan
6. Fine
7. Digital asset publication lifecycle
8. Digital asset access visibility derived state
9. OCR processing lifecycle
10. Search indexing lifecycle
11. Queue job lifecycle referensi teknis

## 5. Prinsip Umum Workflow

Prinsip resmi workflow PERPUSQU adalah:

1. Satu objek proses memiliki definisi state yang jelas.
2. Satu event hanya boleh memicu transisi yang sah.
3. Transisi wajib melalui service yang benar.
4. State turunan dihitung dari field dasar bila tidak disimpan langsung.
5. Guard rule wajib diperiksa sebelum transisi.
6. Side effect seperti audit, indexing, dan perubahan relasi harus terkendali.
7. Koreksi manual khusus hanya boleh dilakukan oleh role berwenang dan wajib diaudit.

## 6. Istilah dalam Dokumen Ini

### 6.1 State

Kondisi resmi sebuah objek pada satu waktu.

### 6.2 Event

Aksi atau kejadian yang memicu perubahan state.

### 6.3 Transition

Perpindahan dari state asal ke state tujuan.

### 6.4 Guard Rule

Syarat yang wajib terpenuhi agar transisi sah.

### 6.5 Side Effect

Aksi tambahan yang terjadi setelah transisi, misalnya audit log, update item status, pembuatan fine, dispatch OCR, atau reindex.

### 6.6 Derived State

State turunan yang tidak disimpan sebagai enum mandiri, tetapi dihitung dari kombinasi field.

## 7. Aturan Umum Implementasi State Machine

1. Controller memicu event melalui service.
2. Service memeriksa guard rule.
3. Service menjalankan transaction bila transisi menyentuh banyak entity.
4. Service memperbarui state utama.
5. Service menjalankan side effect yang relevan.
6. Service mencatat audit log.
7. Controller menerima hasil akhir dan melakukan redirect atau response.

## 8. Daftar State Machine Resmi

### 8.1 User Internal

Persisted field:

1. users.is_active

Persisted states:

1. ACTIVE
2. INACTIVE

### 8.2 Member

Persisted fields:

1. members.is_active
2. members.is_blocked

Derived states:

1. ACTIVE_READY
2. ACTIVE_BLOCKED
3. INACTIVE_UNBLOCKED
4. INACTIVE_BLOCKED

### 8.3 Bibliographic Record

Persisted fields:

1. bibliographic_records.publication_status
2. bibliographic_records.is_public

Persisted states:

1. DRAFT
2. PUBLISHED
3. UNPUBLISHED
4. ARCHIVED

Derived visibility states:

1. PUBLIC_VISIBLE
2. INTERNAL_ONLY

### 8.4 Physical Item

Persisted field:

1. physical_items.item_status

Persisted states:

1. AVAILABLE
2. LOANED
3. DAMAGED
4. LOST
5. REPAIR
6. INACTIVE

### 8.5 Loan

Persisted field:

1. loans.loan_status

Persisted states:

1. ACTIVE
2. RETURNED
3. CANCELLED

Derived states:

1. ACTIVE_ON_TIME
2. ACTIVE_OVERDUE

### 8.6 Fine

Persisted field:

1. fines.status

Persisted states:

1. OUTSTANDING
2. SETTLED
3. WAIVED
4. CANCELLED

### 8.7 Digital Asset Publication

Persisted field:

1. digital_assets.publication_status

Persisted states:

1. DRAFT
2. PUBLISHED
3. UNPUBLISHED
4. ARCHIVED

### 8.8 Digital Asset Access Derived State

Persisted fields:

1. digital_assets.is_public
2. digital_assets.is_embargoed
3. digital_assets.embargo_until
4. digital_asset_access_rules.*

Derived states:

1. PRIVATE_INTERNAL
2. PUBLIC_ACCESSIBLE
3. PUBLIC_EMBARGOED
4. RESTRICTED_BY_RULE

### 8.9 OCR Processing

Persisted field:

1. digital_assets.ocr_status
2. ocr_texts.extraction_status

Persisted states:

1. NOT_REQUESTED
2. QUEUED
3. PROCESSING
4. SUCCESS
5. FAILED

### 8.10 Search Indexing

Persisted field:

1. digital_assets.index_status

Persisted states:

1. PENDING
2. QUEUED
3. PROCESSING
4. INDEXED
5. FAILED

### 8.11 Queue Job Lifecycle Referensi

Referensi teknis, bukan domain utama fase 1

States:

1. QUEUED
2. PROCESSING
3. COMPLETED
4. FAILED
5. RETRIED

## 9. Workflow 1, User Internal

### 9.1 Definisi State

1. ACTIVE
   User boleh login dan memakai sistem sesuai role.
2. INACTIVE
   User tidak boleh login.

### 9.2 Event Resmi

1. create_user
2. activate_user
3. deactivate_user
4. reset_password
5. soft_delete_user

### 9.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_user | data user valid | ACTIVE atau INACTIVE | UserManagementService | audit log |
| INACTIVE | activate_user | aktor berwenang | ACTIVE | UserManagementService | audit log |
| ACTIVE | deactivate_user | aktor berwenang | INACTIVE | UserManagementService | audit log |
| ACTIVE | reset_password | aktor berwenang | ACTIVE | UserManagementService | audit log |
| INACTIVE | reset_password | aktor berwenang | INACTIVE | UserManagementService | audit log |

### 9.4 Guard Rule Penting

1. Username harus unik.
2. Email harus unik.
3. User internal wajib memiliki minimal satu role aktif.
4. Deaktivasi akun super admin terakhir harus ditolak di service.
5. Reset password hanya untuk aktor berwenang.

### 9.5 Transisi yang Dilarang

1. INACTIVE login sukses
2. User tanpa role aktif
3. Penghapusan paksa user yang masih diperlukan secara audit tanpa kebijakan resmi

## 10. Workflow 2, Member

### 10.1 Definisi Derived State

State member dihitung dari kombinasi dua field.

| is_active | is_blocked | Derived State |
|---|---|---|
| 1 | 0 | ACTIVE_READY |
| 1 | 1 | ACTIVE_BLOCKED |
| 0 | 0 | INACTIVE_UNBLOCKED |
| 0 | 1 | INACTIVE_BLOCKED |

### 10.2 Event Resmi

1. create_member
2. activate_member
3. deactivate_member
4. block_member
5. unblock_member
6. update_member_profile

### 10.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_member | data valid | ACTIVE_READY atau INACTIVE_UNBLOCKED | MemberService | audit log |
| INACTIVE_UNBLOCKED | activate_member | aktor berwenang | ACTIVE_READY | MemberStatusService | audit log |
| INACTIVE_BLOCKED | activate_member | aktor berwenang | ACTIVE_BLOCKED | MemberStatusService | audit log |
| ACTIVE_READY | deactivate_member | aktor berwenang | INACTIVE_UNBLOCKED | MemberStatusService | audit log |
| ACTIVE_BLOCKED | deactivate_member | aktor berwenang | INACTIVE_BLOCKED | MemberStatusService | audit log |
| ACTIVE_READY | block_member | alasan blokir valid | ACTIVE_BLOCKED | MemberBlockingService | audit log |
| INACTIVE_UNBLOCKED | block_member | alasan blokir valid | INACTIVE_BLOCKED | MemberBlockingService | audit log |
| ACTIVE_BLOCKED | unblock_member | aktor berwenang | ACTIVE_READY | MemberBlockingService | audit log |
| INACTIVE_BLOCKED | unblock_member | aktor berwenang | INACTIVE_UNBLOCKED | MemberBlockingService | audit log |
| semua state | update_member_profile | data valid | state tetap | MemberService | audit log |

### 10.4 Guard Rule Penting

1. Member number harus unik.
2. Identity number bila diisi harus unik.
3. Member type harus sah.
4. Hanya ACTIVE_READY yang boleh memulai peminjaman.
5. ACTIVE_BLOCKED tetap aktif secara administratif, tetapi tidak layak meminjam.
6. Deaktivasi member yang memiliki pinjaman aktif harus dibatasi oleh service sesuai kebijakan operasional.

### 10.5 Aturan Eligibility Loan

Member layak meminjam hanya bila:

1. is_active = 1
2. is_blocked = 0

### 10.6 Transisi yang Dilarang

1. Peminjaman oleh ACTIVE_BLOCKED
2. Peminjaman oleh INACTIVE_UNBLOCKED
3. Peminjaman oleh INACTIVE_BLOCKED

## 11. Workflow 3, Bibliographic Record

### 11.1 Definisi State

1. DRAFT
   Record masih dalam tahap penyusunan atau belum layak tayang.
2. PUBLISHED
   Record resmi diterbitkan.
3. UNPUBLISHED
   Record pernah atau belum diterbitkan lalu ditahan dari publikasi.
4. ARCHIVED
   Record diarsipkan dan tidak aktif untuk publikasi biasa.

### 11.2 Derived Visibility

Derived visibility dihitung dari:

1. publication_status
2. is_public

Rumus:

1. PUBLIC_VISIBLE bila publication_status = published dan is_public = 1
2. INTERNAL_ONLY untuk kondisi selain itu

### 11.3 Event Resmi

1. create_record
2. update_record
3. publish_record
4. unpublish_record
5. archive_record
6. reactivate_record

### 11.4 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_record | data valid | DRAFT | BibliographicRecordService | audit log |
| DRAFT | update_record | data valid | DRAFT | BibliographicRecordService | audit log |
| DRAFT | publish_record | metadata minimum terpenuhi | PUBLISHED | CatalogPublicationService | reindex, audit log |
| DRAFT | archive_record | aktor berwenang | ARCHIVED | BibliographicRecordService | audit log |
| PUBLISHED | update_record | data valid | PUBLISHED | BibliographicRecordService | reindex bila perlu, audit log |
| PUBLISHED | unpublish_record | aktor berwenang | UNPUBLISHED | CatalogPublicationService | reindex, audit log |
| PUBLISHED | archive_record | aktor berwenang | ARCHIVED | BibliographicRecordService | remove or reindex, audit log |
| UNPUBLISHED | update_record | data valid | UNPUBLISHED | BibliographicRecordService | audit log |
| UNPUBLISHED | publish_record | metadata minimum terpenuhi | PUBLISHED | CatalogPublicationService | reindex, audit log |
| UNPUBLISHED | archive_record | aktor berwenang | ARCHIVED | BibliographicRecordService | audit log |
| ARCHIVED | reactivate_record | aktor berwenang | DRAFT atau UNPUBLISHED | BibliographicRecordService | audit log |

### 11.5 Metadata Minimum untuk Publish

Guard publish minimal:

1. title terisi
2. collection_type_id terisi
3. minimal satu author
4. status record tidak archived
5. field wajib lain sesuai 16_VALIDATION_RULES.md

### 11.6 Aturan Visibilitas OPAC

Record tampil di OPAC hanya bila:

1. publication_status = published
2. is_public = 1

### 11.7 Transisi yang Dilarang

1. ARCHIVED langsung ke PUBLISHED tanpa reactivation formal
2. DRAFT tampil ke OPAC
3. UNPUBLISHED tampil ke OPAC

## 12. Workflow 4, Physical Item

### 12.1 Definisi State

1. AVAILABLE
   Item siap dipinjam.
2. LOANED
   Item sedang dipinjam.
3. DAMAGED
   Item rusak dan tidak layak pinjam normal.
4. LOST
   Item hilang.
5. REPAIR
   Item sedang perbaikan.
6. INACTIVE
   Item dinonaktifkan dari sirkulasi.

### 12.2 Event Resmi

1. create_item
2. update_item
3. loan_item
4. return_item_available
5. return_item_damaged
6. return_item_repair
7. return_item_lost
8. mark_damaged
9. mark_repair
10. mark_inactive
11. restore_available

### 12.3 Tabel Transisi Normal

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_item | data valid | AVAILABLE, DAMAGED, REPAIR, atau INACTIVE | PhysicalItemService | audit log |
| AVAILABLE | loan_item | member dan item valid | LOANED | LoanTransactionService, PhysicalItemStatusService | create loan, audit log |
| LOANED | return_item_available | pengembalian normal | AVAILABLE | ReturnProcessingService, PhysicalItemStatusService | close loan, fine, audit log |
| LOANED | return_item_damaged | kondisi kembali rusak | DAMAGED | ReturnProcessingService, PhysicalItemStatusService | close loan, fine bila perlu, audit log |
| LOANED | return_item_repair | perlu perbaikan | REPAIR | ReturnProcessingService, PhysicalItemStatusService | close loan, audit log |
| LOANED | return_item_lost | dinyatakan hilang | LOST | ReturnProcessingService, PhysicalItemStatusService | close loan, fine bila perlu, audit log |
| AVAILABLE | mark_damaged | aktor berwenang | DAMAGED | PhysicalItemStatusService | history, audit log |
| AVAILABLE | mark_repair | aktor berwenang | REPAIR | PhysicalItemStatusService | history, audit log |
| AVAILABLE | mark_inactive | aktor berwenang | INACTIVE | PhysicalItemStatusService | history, audit log |
| DAMAGED | mark_repair | aktor berwenang | REPAIR | PhysicalItemStatusService | history, audit log |
| DAMAGED | mark_inactive | aktor berwenang | INACTIVE | PhysicalItemStatusService | history, audit log |
| REPAIR | restore_available | perbaikan selesai | AVAILABLE | PhysicalItemStatusService | history, audit log |
| REPAIR | mark_damaged | hasil perbaikan gagal | DAMAGED | PhysicalItemStatusService | history, audit log |
| REPAIR | mark_inactive | aktor berwenang | INACTIVE | PhysicalItemStatusService | history, audit log |
| INACTIVE | restore_available | aktor berwenang | AVAILABLE | PhysicalItemStatusService | history, audit log |

### 12.4 Koreksi Administratif Khusus

Transisi berikut hanya untuk koreksi data oleh role berwenang tinggi dan wajib diaudit sangat rinci:

1. LOST ke AVAILABLE
2. DAMAGED ke AVAILABLE tanpa REPAIR
3. INACTIVE ke REPAIR
4. AVAILABLE ke LOST

### 12.5 Guard Rule Penting

1. Hanya AVAILABLE yang boleh dipinjam.
2. Item LOANED wajib memiliki tepat satu loan aktif.
3. Item non AVAILABLE tidak boleh masuk proses loan biasa.
4. Item dengan loan aktif tidak boleh dinonaktifkan normal tanpa mekanisme koreksi khusus.

### 12.6 Invariant Utama

1. item_status = loaned berarti harus ada satu loan aktif.
2. Jika loan aktif ada, item_status harus loaned.

## 13. Workflow 5, Loan

### 13.1 Definisi State

1. ACTIVE
   Pinjaman sedang berjalan.
2. RETURNED
   Pinjaman sudah selesai.
3. CANCELLED
   Pinjaman dibatalkan secara administratif.

### 13.2 Derived State

1. ACTIVE_ON_TIME bila loan_status = active dan due_date >= waktu saat ini
2. ACTIVE_OVERDUE bila loan_status = active dan due_date < waktu saat ini

Derived state ini tidak disimpan sebagai enum baru.

### 13.3 Event Resmi

1. create_loan
2. renew_loan
3. return_loan
4. cancel_loan_exception

### 13.4 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_loan | member layak dan item available | ACTIVE | LoanTransactionService | item jadi loaned, audit log |
| ACTIVE | renew_loan | loan layak diperpanjang | ACTIVE | RenewalService | new due date, create renewal, audit log |
| ACTIVE | return_loan | return valid | RETURNED | ReturnProcessingService | item updated, fine computed, audit log |
| ACTIVE | cancel_loan_exception | koreksi admin sah | CANCELLED | LoanTransactionService atau service khusus koreksi | item restored, audit log rinci |

### 13.5 Guard Rule Create Loan

1. Member ACTIVE_READY
2. Physical item AVAILABLE
3. Maksimum pinjaman aktif belum terlampaui
4. Aturan operasional mengizinkan
5. Tidak ada active loan lain untuk item tersebut

### 13.6 Guard Rule Renew Loan

1. loan_status = active
2. belum melewati batas perpanjangan
3. aturan operasional mengizinkan
4. kondisi khusus larangan perpanjangan tidak aktif

### 13.7 Guard Rule Return Loan

1. loan_status = active
2. barcode atau item cocok dengan loan aktif
3. waktu return sah

### 13.8 Aturan Overdue

ACTIVE_OVERDUE adalah state turunan. State persisted tetap ACTIVE.

### 13.9 Transisi yang Dilarang

1. RETURNED ke ACTIVE
2. CANCELLED ke ACTIVE
3. RETURNED di-renew
4. CANCELLED di-return

## 14. Workflow 6, Fine

### 14.1 Definisi State

1. OUTSTANDING
   Denda masih terbuka.
2. SETTLED
   Denda ditandai selesai.
3. WAIVED
   Denda dibebaskan.
4. CANCELLED
   Denda dibatalkan.

### 14.2 Event Resmi

1. create_fine
2. settle_fine
3. waive_fine
4. cancel_fine

### 14.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | create_fine | hasil perhitungan > 0 | OUTSTANDING | FineCalculationService, ReturnProcessingService | audit log |
| OUTSTANDING | settle_fine | kewenangan dan kebijakan tersedia | SETTLED | FineReportingService atau service pembayaran lanjutan | audit log |
| OUTSTANDING | waive_fine | kewenangan dan alasan sah | WAIVED | FineReportingService atau service admin fine | audit log |
| OUTSTANDING | cancel_fine | koreksi administratif sah | CANCELLED | FineReportingService atau service admin fine | audit log |

### 14.4 Catatan Fase 1

1. Fase 1 fokus pada pencatatan denda.
2. UI pelunasan formal belum wajib.
3. Status SETTLED, WAIVED, dan CANCELLED tetap disiapkan agar schema dan workflow siap berkembang.

### 14.5 Transisi yang Dilarang

1. SETTLED ke OUTSTANDING
2. WAIVED ke OUTSTANDING
3. CANCELLED ke OUTSTANDING

## 15. Workflow 7, Digital Asset Publication

### 15.1 Definisi State

1. DRAFT
   Aset baru diunggah atau belum layak diterbitkan.
2. PUBLISHED
   Aset diterbitkan.
3. UNPUBLISHED
   Aset ditahan dari publikasi.
4. ARCHIVED
   Aset diarsipkan.

### 15.2 Event Resmi

1. upload_asset
2. update_asset
3. publish_asset
4. unpublish_asset
5. archive_asset
6. reactivate_asset

### 15.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| tidak ada | upload_asset | file dan metadata valid | DRAFT | DigitalAssetUploadService | queue OCR or index, audit log |
| DRAFT | update_asset | metadata valid | DRAFT | DigitalAssetService | queue reindex bila perlu |
| DRAFT | publish_asset | metadata valid dan rule akses sah | PUBLISHED | DigitalAssetService | queue reindex, audit log |
| DRAFT | archive_asset | aktor berwenang | ARCHIVED | DigitalAssetService | audit log |
| PUBLISHED | update_asset | metadata valid | PUBLISHED | DigitalAssetService | queue reindex, audit log |
| PUBLISHED | unpublish_asset | aktor berwenang | UNPUBLISHED | DigitalAssetService | queue reindex, audit log |
| PUBLISHED | archive_asset | aktor berwenang | ARCHIVED | DigitalAssetService | queue reindex or remove, audit log |
| UNPUBLISHED | update_asset | metadata valid | UNPUBLISHED | DigitalAssetService | audit log |
| UNPUBLISHED | publish_asset | guard publish terpenuhi | PUBLISHED | DigitalAssetService | queue reindex, audit log |
| UNPUBLISHED | archive_asset | aktor berwenang | ARCHIVED | DigitalAssetService | audit log |
| ARCHIVED | reactivate_asset | aktor berwenang | DRAFT atau UNPUBLISHED | DigitalAssetService | audit log |

### 15.4 Guard Publish Digital Asset

1. bibliographic_record_id valid
2. file_path valid
3. mime_type valid
4. publication_status bukan archived
5. rule akses tidak bertentangan
6. embargo rule valid bila dipakai

### 15.5 Transisi yang Dilarang

1. ARCHIVED langsung ke PUBLISHED tanpa reactivation formal
2. DRAFT aset dianggap publik otomatis
3. UNPUBLISHED aset dianggap publik otomatis

## 16. Workflow 8, Digital Asset Access Derived State

### 16.1 Definisi Derived State

Derived access ditentukan oleh kombinasi:

1. publication_status
2. is_public
3. is_embargoed
4. embargo_until
5. digital_asset_access_rules

### 16.2 Rumus Derived State

#### 16.2.1 PRIVATE_INTERNAL

Kondisi:

1. publication_status bukan published
atau
2. is_public = 0 tanpa rule publik yang sah

#### 16.2.2 PUBLIC_ACCESSIBLE

Kondisi:

1. publication_status = published
2. is_public = 1
3. is_embargoed = 0 atau embargo_until sudah lewat
4. rule akses tidak menolak preview publik

#### 16.2.3 PUBLIC_EMBARGOED

Kondisi:

1. publication_status = published
2. is_public = 1
3. is_embargoed = 1
4. embargo_until masih di masa depan

#### 16.2.4 RESTRICTED_BY_RULE

Kondisi:

1. publication_status = published
2. rule akses membatasi ke role atau member_type tertentu

### 16.3 Event yang Mengubah Derived State

1. publish_asset
2. unpublish_asset
3. update_access_rule
4. set_embargo
5. embargo_expired
6. archive_asset

### 16.4 Guard Rule Penting

1. Akses publik tidak boleh diberikan ke aset draft.
2. Preview publik tidak boleh melanggar embargo.
3. Download privat wajib melewati DigitalAssetAccessService.

## 17. Workflow 9, OCR Processing

### 17.1 Definisi State

1. NOT_REQUESTED
   OCR belum diminta.
2. QUEUED
   Job OCR sudah dikirim ke antrian.
3. PROCESSING
   OCR sedang dikerjakan.
4. SUCCESS
   OCR selesai dan teks tersedia.
5. FAILED
   OCR gagal.

### 17.2 Event Resmi

1. request_ocr
2. start_ocr
3. complete_ocr_success
4. complete_ocr_failed
5. retry_ocr

### 17.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| NOT_REQUESTED | request_ocr | file layak OCR | QUEUED | OcrProcessingService | queue job, audit optional |
| FAILED | retry_ocr | aktor berwenang | QUEUED | OcrProcessingService | queue job |
| QUEUED | start_ocr | worker ambil job | PROCESSING | OcrProcessingService | update status |
| PROCESSING | complete_ocr_success | proses sukses | SUCCESS | OcrProcessingService | save ocr_text, queue reindex |
| PROCESSING | complete_ocr_failed | proses gagal | FAILED | OcrProcessingService | save error_message |

### 17.4 Guard Rule Penting

1. File harus mendukung OCR.
2. Asset harus masih ada dan file_path sah.
3. Retry hanya untuk aset yang relevan.

### 17.5 Transisi yang Dilarang

1. SUCCESS langsung ke PROCESSING tanpa event request atau retry
2. NOT_REQUESTED langsung ke SUCCESS
3. FAILED langsung ke SUCCESS

## 18. Workflow 10, Search Indexing

### 18.1 Definisi State

1. PENDING
   Belum pernah diindeks atau perlu indeks awal.
2. QUEUED
   Job indexing sudah dikirim.
3. PROCESSING
   Indexing sedang berjalan.
4. INDEXED
   Index terbaru sudah tersimpan.
5. FAILED
   Indexing gagal.

### 18.2 Event Resmi

1. mark_reindex_needed
2. queue_reindex
3. start_indexing
4. complete_index_success
5. complete_index_failed
6. retry_indexing

### 18.3 Tabel Transisi

| State Asal | Event | Guard Rule | State Tujuan | Service Utama | Side Effect |
|---|---|---|---|---|---|
| PENDING | queue_reindex | record atau asset relevan | QUEUED | SearchIndexService | queue job |
| INDEXED | mark_reindex_needed | metadata berubah | PENDING | SearchIndexService | none |
| FAILED | retry_indexing | aktor berwenang atau proses otomatis | QUEUED | SearchIndexService | queue job |
| QUEUED | start_indexing | worker ambil job | PROCESSING | SearchIndexService | update status |
| PROCESSING | complete_index_success | payload sah | INDEXED | SearchIndexService | update timestamp |
| PROCESSING | complete_index_failed | error indexing | FAILED | SearchIndexService | log error |

### 18.4 Trigger Reindex

Trigger utama:

1. publish_record
2. unpublish_record
3. update_record yang mempengaruhi metadata pencarian
4. upload_asset yang publik dan relevan
5. update_asset yang mempengaruhi visibilitas
6. OCR success
7. perubahan access rule yang mempengaruhi visibilitas

### 18.5 Transisi yang Dilarang

1. INDEXED langsung ke PROCESSING tanpa queue
2. PENDING langsung ke INDEXED tanpa proses
3. FAILED langsung ke INDEXED tanpa retry

## 19. Workflow 11, Queue Job Lifecycle Referensi

### 19.1 Definisi

State ini untuk acuan monitoring teknis, terutama OCR dan indexing.

### 19.2 State

1. QUEUED
2. PROCESSING
3. COMPLETED
4. FAILED
5. RETRIED

### 19.3 Event

1. dispatch_job
2. start_job
3. complete_job
4. fail_job
5. retry_job

### 19.4 Relasi ke UI

Monitoring Queue pada menu Audit dan Monitoring membaca lifecycle ini, walau penyimpanan final bergantung implementasi queue engine.

## 20. Derived State dan Invariant Lintas Objek

### 20.1 Invariant Loan dan Physical Item

1. Loan ACTIVE wajib berpasangan dengan PhysicalItem LOANED.
2. PhysicalItem LOANED wajib memiliki tepat satu Loan ACTIVE.

### 20.2 Invariant Return

1. Loan RETURNED wajib memiliki returned_at terisi.
2. Loan RETURNED wajib memiliki ReturnTransaction.
3. Setelah return, item tidak boleh tetap LOANED.

### 20.3 Invariant Record Visibility

1. BibliographicRecord PUBLIC_VISIBLE hanya bila publication_status = published dan is_public = 1.
2. Record selain itu tidak boleh tayang publik.

### 20.4 Invariant Digital Asset Visibility

1. DigitalAsset PUBLIC_ACCESSIBLE hanya bila publication_status = published, is_public = 1, embargo tidak aktif, dan rule akses mengizinkan.
2. Asset privat tidak boleh lolos ke preview publik.

### 20.5 Invariant Member Eligibility

1. Hanya ACTIVE_READY yang layak meminjam.
2. Status lain harus ditolak oleh LoanTransactionService.

## 21. Guard Rule Ringkas per Proses Kritis

### 21.1 Guard Loan

1. Member ACTIVE_READY
2. Item AVAILABLE
3. Batas pinjam belum lewat
4. Role petugas sah
5. Aturan operasional mengizinkan

### 21.2 Guard Return

1. Loan ACTIVE ditemukan
2. Item cocok dengan loan
3. Return actor sah

### 21.3 Guard Renew

1. Loan ACTIVE
2. Belum melampaui maksimum renew
3. Aturan perpanjangan mengizinkan

### 21.4 Guard Publish Record

1. Metadata minimum lengkap
2. Status bukan archived

### 21.5 Guard Publish Asset

1. File valid
2. Metadata valid
3. Rule akses valid
4. Status bukan archived

### 21.6 Guard OCR

1. File mendukung OCR
2. Asset masih valid
3. Queue atau worker tersedia

### 21.7 Guard Reindex

1. Entity relevan untuk pencarian
2. Visibilitas publik atau pembaruan internal memang memerlukan indeks

## 22. Mapping Event ke Service

| Event | Service Utama |
|---|---|
| create_user | UserManagementService |
| activate_user | UserManagementService |
| deactivate_user | UserManagementService |
| reset_password | UserManagementService |
| create_member | MemberService |
| activate_member | MemberStatusService |
| deactivate_member | MemberStatusService |
| block_member | MemberBlockingService |
| unblock_member | MemberBlockingService |
| create_record | BibliographicRecordService |
| publish_record | CatalogPublicationService |
| unpublish_record | CatalogPublicationService |
| create_item | PhysicalItemService |
| change_item_status | PhysicalItemStatusService |
| create_loan | LoanTransactionService |
| return_loan | ReturnProcessingService |
| renew_loan | RenewalService |
| create_fine | FineCalculationService, ReturnProcessingService |
| upload_asset | DigitalAssetUploadService |
| publish_asset | DigitalAssetService |
| unpublish_asset | DigitalAssetService |
| update_access_rule | DigitalAssetAccessService |
| request_ocr | OcrProcessingService |
| queue_reindex | SearchIndexService |
| retry_queue_job | QueueMonitorService |

## 23. Mapping Workflow ke Controller

| Workflow | Controller Entry Point |
|---|---|
| User internal | UserController |
| Member | MemberController |
| Bibliographic record | BibliographicRecordController |
| Physical item | PhysicalItemController |
| Loan | LoanController |
| Return | ReturnController |
| Fine view | FineController |
| Digital asset | DigitalAssetController |
| Asset access | AssetAccessController, AssetPreviewController |
| OCR processing | DigitalAssetController |
| Search indexing | DigitalAssetController, BibliographicRecordController |
| Queue monitor | QueueMonitorController |

## 24. Mapping Workflow ke Route

| Workflow | Route Utama |
|---|---|
| Activate user | admin.access.users.activate |
| Reset password user | admin.access.users.reset_password |
| Activate member | admin.members.activate |
| Deactivate member | admin.members.deactivate |
| Block member | admin.members.block |
| Unblock member | admin.members.unblock |
| Publish record | admin.catalog.records.publish |
| Unpublish record | admin.catalog.records.unpublish |
| Change item status | admin.collections.items.change_status |
| Create loan | admin.circulation.loans.store |
| Return loan | admin.circulation.returns.store |
| Renew loan | admin.circulation.loans.renew |
| Publish asset | admin.digital.assets.publish |
| Unpublish asset | admin.digital.assets.unpublish |
| Update asset access | admin.digital.assets.access.update |
| Run OCR | admin.digital.assets.ocr.run |
| Reindex | admin.digital.assets.reindex |
| Retry queue job | admin.audit.queue_monitor.retry |

## 25. Dampak ke UI dan Tombol Aksi

### 25.1 Bibliographic Record

1. Tombol Publish hanya tampil bila state DRAFT atau UNPUBLISHED.
2. Tombol Unpublish hanya tampil bila state PUBLISHED.
3. Tombol Archive tidak wajib muncul pada fase 1 kecuali diaktifkan.

### 25.2 Physical Item

1. Tombol Pinjam hanya tampil bila item AVAILABLE.
2. Tombol Return tidak muncul dari halaman item biasa, tetapi dari modul sirkulasi.
3. Tombol Restore Available hanya tampil bila state REPAIR atau INACTIVE sesuai kebijakan.

### 25.3 Member

1. Tombol Block hanya tampil bila member belum diblokir.
2. Tombol Unblock hanya tampil bila member diblokir.
3. Tombol Pinjam operasional harus menolak status selain ACTIVE_READY.

### 25.4 Loan

1. Tombol Renew hanya tampil bila loan ACTIVE dan guard rule lulus.
2. Tombol Return hanya berlaku untuk loan ACTIVE.

### 25.5 Digital Asset

1. Tombol Publish hanya tampil bila DRAFT atau UNPUBLISHED.
2. Tombol Unpublish hanya tampil bila PUBLISHED.
3. Tombol OCR tampil bila file layak OCR.
4. Tombol Reindex tampil bila asset relevan.

## 26. Dampak ke Audit Log

Transisi berikut wajib dicatat:

1. Aktivasi dan nonaktifasi user
2. Reset password user
3. Aktivasi, nonaktifasi, blokir, unblock member
4. Publish, unpublish, archive, reactivate record
5. Change status item
6. Loan create
7. Return process
8. Loan renew
9. Fine create, settle, waive, cancel
10. Asset upload
11. Asset publish, unpublish, archive, access rule update
12. OCR retry
13. Queue retry

## 27. Dampak ke Validation Rules

Dokumen ini memperkuat validasi pada:

1. ChangePhysicalItemStatusRequest
2. BlockMemberRequest
3. StoreLoanRequest
4. StoreReturnRequest
5. RenewLoanRequest
6. StoreDigitalAssetRequest
7. UpdateDigitalAssetRequest
8. UpdateDigitalAssetAccessRequest
9. QueueRetryRequest

Catatan:

1. Form request memeriksa format.
2. Guard rule state machine diperiksa lagi di service.

## 28. Dampak ke Testing

Pengujian minimum state machine wajib mencakup:

1. User INACTIVE tidak bisa login
2. Member ACTIVE_BLOCKED ditolak saat loan
3. Record DRAFT tidak tampil di OPAC
4. Record PUBLISHED dan is_public = 1 tampil di OPAC
5. Item AVAILABLE berhasil jadi LOANED saat loan
6. Item LOANED kembali normal jadi AVAILABLE saat return baik
7. Item LOANED berubah jadi REPAIR saat return butuh perbaikan
8. Loan ACTIVE berubah RETURNED saat return
9. Loan ACTIVE tetap ACTIVE setelah renew
10. Fine OUTSTANDING terbentuk bila telat
11. Asset DRAFT tidak preview publik
12. Asset PUBLISHED + public + non embargo preview publik
13. OCR FAILED bisa kembali QUEUED saat retry
14. Index FAILED bisa kembali QUEUED saat retry
15. Queue retry tercatat di audit

## 29. Transisi yang Bersifat Exception

Transisi exception hanya untuk koreksi administratif dan wajib:

1. Dibatasi permission tinggi
2. Dicatat penuh di audit log
3. Memiliki alasan tekstual
4. Memeriksa konsistensi lintas entity

Contoh:

1. ACTIVE loan dibatalkan karena input salah
2. LOST item dikoreksi kembali AVAILABLE
3. ARCHIVED record direaktivasi
4. ARCHIVED asset direaktivasi

## 30. Workflow yang Tidak Masuk Fase 1

Workflow berikut belum menjadi state machine aktif fase 1:

1. Acquisition workflow
2. Reservation workflow
3. Payment settlement workflow rinci
4. SSO workflow
5. RFID tracking workflow
6. Multi campus workflow
7. Notification workflow rinci

Catatan:

1. State untuk fine sudah disiapkan agar perluasan fase berikutnya lebih mudah.
2. Workflow tambahan hanya boleh masuk melalui revisi blueprint resmi.

## 31. Matriks Prioritas Implementasi State Machine

### Prioritas P1

1. Member eligibility state
2. Bibliographic record publication state
3. Physical item state
4. Loan state
5. Digital asset publication state
6. OCR state
7. Index state

### Prioritas P2

1. User activation state
2. Fine state
3. Derived access state digital asset
4. Queue monitor lifecycle referensi

### Prioritas P3

1. Exception correction workflow
2. Fine settlement UI workflow
3. Workflow fase lanjutan lain

## 32. Dokumen Turunan yang Wajib Mengacu

Dokumen ini menjadi acuan wajib bagi:

1. 18_UI_UX_STANDARD.md
2. 21_SEARCH_INDEXING_SPEC.md
3. 22_STORAGE_FILE_POLICY.md
4. 23_OCR_AND_DIGITAL_PROCESSING.md
5. 25_REPORTING_SPEC.md
6. 28_SECURITY_POLICY.md
7. 29_AUDIT_LOG_SPEC.md
8. 31_TEST_PLAN.md
9. 32_TEST_SCENARIO.md
10. 39_TRACEABILITY_MATRIX.md
11. 41_BACKEND_CHECKLIST.md
12. 42_FRONTEND_CHECKLIST.md
13. 45_SMOKE_TEST_CHECKLIST.md
14. 46_UAT_CHECKLIST.md

Aturan:

1. UI hanya menampilkan aksi yang sah menurut state machine ini.
2. Search indexing spec harus mengikuti trigger reindex di dokumen ini.
3. OCR spec harus mengikuti OCR lifecycle di dokumen ini.
4. Audit log spec harus memuat semua transisi sensitif di dokumen ini.
5. Test plan harus memiliki skenario transisi state normal dan exception.

## 33. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua enum schema memiliki definisi state yang jelas.
2. Semua transisi utama memiliki service penanggung jawab.
3. Semua objek proses kritis memiliki guard rule.
4. Semua aksi UI utama memiliki dasar state.
5. Semua transisi sensitif tercatat di audit.
6. Tidak ada transisi yang bertentangan dengan schema, service, atau validation rules.

## 34. Kesimpulan

Dokumen Workflow State Machine ini menetapkan aturan hidup status objek utama PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 16. Dokumen ini menjadi fondasi agar akun, anggota, katalog, item fisik, pinjaman, denda, aset digital, OCR, dan indexing bergerak dalam jalur status yang tertib, aman, dan mudah diuji. Semua implementasi service, UI aksi, audit, dan testing PERPUSQU wajib merujuk dokumen ini.

END OF 17_WORKFLOW_STATE_MACHINE.md
