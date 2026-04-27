# 13_MODEL_MAP.md

## 1. Nama Dokumen
Model Map Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem
PERPUSQU

### 2.2 Jenis Dokumen
Dokumen blueprint peta model aplikasi

### 2.3 Status Dokumen
Resmi, acuan wajib pengembangan model Eloquent, relasi data, fillable, casts, scopes, policy binding, query layer, dan integrasi ke schema database

### 2.4 Tujuan Dokumen
Dokumen ini menetapkan seluruh model resmi PERPUSQU, relasi antar model, tanggung jawab model, atribut inti, casts, scopes, dan batas peran model dalam arsitektur monolith modular. Dokumen ini menjadi acuan wajib agar tidak ada tabel tanpa model, tidak ada model tanpa tujuan domain, tidak ada relasi liar antar modul, dan tidak ada inkonsistensi antara controller, service, dan schema.

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

Aturan wajib:
1. Nama aplikasi resmi tetap PERPUSQU.
2. Konsep sistem tetap perpustakaan hibrid kampus.
3. Arsitektur tetap monolith modular.
4. Model harus selaras dengan modul domain pada dokumen 03.
5. Model harus mendukung service layer pada dokumen 12.
6. Model harus mendukung controller pada dokumen 11.
7. Model tidak boleh memuat logika bisnis berat yang seharusnya ada di service.
8. Model harus selaras dengan route, view, use case, dan permission yang telah disepakati.
9. Dokumen ini menjadi jembatan utama menuju 14_SCHEMA.sql.

## 4. Prinsip Umum Model PERPUSQU
Prinsip resmi model PERPUSQU adalah:

1. Setiap model mewakili entity domain atau data sistem yang jelas.
2. Setiap model harus memiliki tanggung jawab data yang tegas.
3. Relasi model harus mengikuti domain bisnis, bukan kebutuhan tampilan sesaat.
4. Model tidak boleh menjadi tempat logika bisnis lintas modul yang berat.
5. Model boleh memiliki accessor, mutator, scope, helper status ringan, dan relasi.
6. Model harus mendukung audit, policy, dan query yang efisien.
7. Model harus siap dipakai dalam service layer dan schema database.
8. Model harus memakai penamaan konsisten dengan controller, service, dan route.

## 5. Klasifikasi Model
Model dalam PERPUSQU dibagi menjadi kelompok berikut:

1. Model Identity and Access
2. Model Core
3. Model Master Data
4. Model Catalog
5. Model Collection
6. Model Member
7. Model Circulation
8. Model Digital Repository
9. Model Audit
10. Model Pendukung Teknis

## 6. Aturan Penamaan Model
Aturan penamaan model:

1. Gunakan PascalCase singular.
2. Nama model harus mewakili entity tunggal.
3. Nama file model harus sama dengan nama class.
4. Gunakan namespace sesuai modul.
5. Nama tabel akan ditentukan pada schema, tetapi model tetap memakai nama singular.

Contoh:
1. `User`
2. `Role`
3. `Permission`
4. `BibliographicRecord`
5. `PhysicalItem`
6. `Member`
7. `Loan`
8. `DigitalAsset`
9. `ActivityLog`

## 7. Struktur Folder Model yang Disarankan
Struktur model yang disarankan mengikuti modul:

```text
app/
  Modules/
    Identity/
      Models/
    Core/
      Models/
    MasterData/
      Models/
    Catalog/
      Models/
    Collection/
      Models/
    Member/
      Models/
    Circulation/
      Models/
    DigitalRepository/
      Models/
    Audit/
      Models/
````

## 8. Aturan Umum Model

Setiap model wajib mematuhi aturan berikut:

1. Menetapkan nama tabel secara eksplisit bila perlu.
2. Menetapkan fillable atau guarded secara aman.
3. Menetapkan casts untuk field yang relevan.
4. Menetapkan relasi secara lengkap.
5. Menetapkan scope yang relevan untuk query umum.
6. Menjaga agar helper method tetap ringan.
7. Tidak melakukan query kompleks lintas domain berlebihan.
8. Tidak memproses upload file langsung.
9. Tidak memproses OCR, indexing, denda, atau workflow berat di model.

## 9. Daftar Model Resmi PERPUSQU

### 9.1 Modul Identity and Access

1. User
2. Role
3. Permission

### 9.2 Modul Core

4. SystemSetting
5. InstitutionProfile

### 9.3 Modul Master Data

6. Author
7. Publisher
8. Language
9. Classification
10. Subject
11. CollectionType
12. RackLocation
13. Faculty
14. StudyProgram
15. ItemCondition

### 9.4 Modul Catalog

16. BibliographicRecord
17. BibliographicRecordAuthor
18. BibliographicRecordSubject

### 9.5 Modul Collection

19. PhysicalItem
20. PhysicalItemStatusHistory

### 9.6 Modul Member

21. Member

### 9.7 Modul Circulation

22. Loan
23. LoanRenewal
24. ReturnTransaction
25. Fine

### 9.8 Modul Digital Repository

26. DigitalAsset
27. DigitalAssetAccessRule
28. OcrText

### 9.9 Modul Audit

29. ActivityLog

### 9.10 Model Tambahan Opsional Terkontrol

30. ReportExportHistory
31. QueueMonitorSnapshot

Catatan:

1. Model nomor 30 dan 31 bersifat opsional untuk fase awal.
2. Model pivot dapat dibuat sebagai model eksplisit bila perlu alasan audit atau metadata tambahan.
3. Tidak ada model acquisition, reservation lanjutan, RFID, SSO, atau payment pada fase 1.

## 10. Model Map Per Modul

## 10.1 Modul Identity and Access

### 10.1.1 Model User

Nama Class:
`User`

Namespace:
`App\Modules\Identity\Models\User`

Nama File:
`app/Modules/Identity/Models/User.php`

Tujuan:

1. Mewakili akun pengguna internal sistem.
2. Menyimpan identitas login, status aktif, dan metadata pengguna internal.
3. Menjadi aktor utama untuk audit dan authorization.

Dipakai oleh:

1. LoginController
2. UserController
3. ProfileController
4. PasswordController
5. AuditLogService
6. Semua service yang memerlukan user pelaku aksi

Atribut inti:

1. id
2. name
3. username
4. email
5. password
6. is_active
7. last_login_at
8. created_at
9. updated_at
10. deleted_at bila memakai soft delete

Fillable rekomendasi:

1. name
2. username
3. email
4. password
5. is_active

Casts rekomendasi:

1. is_active => boolean
2. last_login_at => datetime
3. created_at => datetime
4. updated_at => datetime

Relasi:

1. belongsToMany Role
2. hasMany ActivityLog

Scope rekomendasi:

1. scopeActive
2. scopeInactive
3. scopeKeyword

Helper ringan:

1. isActive()
2. hasRoleName(string $roleName)

Policy terkait:

1. UserPolicy

Catatan:

1. User tidak mewakili anggota perpustakaan publik pada fase 1.
2. Mahasiswa dan dosen fase 1 diperlakukan sebagai pengguna OPAC publik, bukan user login admin.

### 10.1.2 Model Role

Nama Class:
`Role`

Namespace:
`App\Modules\Identity\Models\Role`

Nama File:
`app/Modules/Identity/Models/Role.php`

Tujuan:

1. Mewakili role authorization sistem.
2. Menjadi kumpulan permission.

Atribut inti:

1. id
2. name
3. guard_name
4. created_at
5. updated_at

Relasi:

1. belongsToMany Permission
2. belongsToMany User

Scope rekomendasi:

1. scopeKeyword

Catatan:

1. Role utama sudah ditetapkan pada 07_ROLE_PERMISSION_MATRIX.md.
2. Role harus sinkron dengan permission matrix resmi.

### 10.1.3 Model Permission

Nama Class:
`Permission`

Namespace:
`App\Modules\Identity\Models\Permission`

Nama File:
`app/Modules/Identity/Models/Permission.php`

Tujuan:

1. Mewakili permission granular sistem.

Atribut inti:

1. id
2. name
3. guard_name
4. created_at
5. updated_at

Relasi:

1. belongsToMany Role

Scope rekomendasi:

1. scopeByModule
2. scopeKeyword

Catatan:

1. Daftar permission resmi mengacu dokumen 07.

## 10.2 Modul Core

### 10.2.1 Model SystemSetting

Nama Class:
`SystemSetting`

Namespace:
`App\Modules\Core\Models\SystemSetting`

Nama File:
`app/Modules/Core/Models/SystemSetting.php`

Tujuan:

1. Menyimpan konfigurasi sistem umum.
2. Menyimpan parameter operasional yang tidak layak di-hardcode.

Atribut inti:

1. id
2. key
3. value
4. type
5. group_name
6. is_public
7. created_at
8. updated_at

Fillable rekomendasi:

1. key
2. value
3. type
4. group_name
5. is_public

Casts rekomendasi:

1. is_public => boolean

Scope rekomendasi:

1. scopeGroup
2. scopePublic
3. scopeByKey

Catatan:

1. Nilai operasional seperti denda per hari dan batas pinjam dapat disimpan di model ini atau diturunkan ke model InstitutionProfile tergantung desain schema.
2. Key harus unik.

### 10.2.2 Model InstitutionProfile

Nama Class:
`InstitutionProfile`

Namespace:
`App\Modules\Core\Models\InstitutionProfile`

Nama File:
`app/Modules/Core/Models/InstitutionProfile.php`

Tujuan:

1. Menyimpan profil institusi dan perpustakaan.
2. Menyimpan informasi publik yang bisa dipakai halaman OPAC.

Atribut inti:

1. id
2. institution_name
3. library_name
4. address
5. phone
6. email
7. website
8. logo_path
9. about_text
10. created_at
11. updated_at

Fillable rekomendasi:

1. institution_name
2. library_name
3. address
4. phone
5. email
6. website
7. logo_path
8. about_text

Catatan:

1. Umumnya hanya satu record aktif.
2. Halaman OPAC About dapat memakai data dari model ini.

## 10.3 Modul Master Data

### 10.3.1 Model Author

Nama Class:
`Author`

Namespace:
`App\Modules\MasterData\Models\Author`

Nama File:
`app/Modules/MasterData/Models/Author.php`

Tujuan:

1. Menyimpan data pengarang karya.

Atribut inti:

1. id
2. name
3. normalized_name
4. notes
5. is_active
6. created_at
7. updated_at

Relasi:

1. belongsToMany BibliographicRecord melalui BibliographicRecordAuthor

Casts:

1. is_active => boolean

Scope:

1. scopeActive
2. scopeKeyword

### 10.3.2 Model Publisher

Nama Class:
`Publisher`

Namespace:
`App\Modules\MasterData\Models\Publisher`

Nama File:
`app/Modules/MasterData/Models/Publisher.php`

Tujuan:

1. Menyimpan data penerbit.

Atribut inti:

1. id
2. name
3. city
4. notes
5. is_active
6. created_at
7. updated_at

Relasi:

1. hasMany BibliographicRecord

### 10.3.3 Model Language

Nama Class:
`Language`

Namespace:
`App\Modules\MasterData\Models\Language`

Nama File:
`app/Modules/MasterData/Models/Language.php`

Tujuan:

1. Menyimpan bahasa karya.

Atribut inti:

1. id
2. code
3. name
4. is_active
5. created_at
6. updated_at

Relasi:

1. hasMany BibliographicRecord

### 10.3.4 Model Classification

Nama Class:
`Classification`

Namespace:
`App\Modules\MasterData\Models\Classification`

Nama File:
`app/Modules/MasterData/Models/Classification.php`

Tujuan:

1. Menyimpan data klasifikasi koleksi.

Atribut inti:

1. id
2. code
3. name
4. parent_id
5. is_active
6. created_at
7. updated_at

Relasi:

1. belongsTo Classification parent
2. hasMany Classification children
3. hasMany BibliographicRecord

Scope:

1. scopeRoot
2. scopeActive

### 10.3.5 Model Subject

Nama Class:
`Subject`

Namespace:
`App\Modules\MasterData\Models\Subject`

Nama File:
`app/Modules/MasterData/Models/Subject.php`

Tujuan:

1. Menyimpan subjek karya.

Atribut inti:

1. id
2. name
3. notes
4. is_active
5. created_at
6. updated_at

Relasi:

1. belongsToMany BibliographicRecord melalui BibliographicRecordSubject

### 10.3.6 Model CollectionType

Nama Class:
`CollectionType`

Namespace:
`App\Modules\MasterData\Models\CollectionType`

Nama File:
`app/Modules/MasterData/Models/CollectionType.php`

Tujuan:

1. Menyimpan jenis koleksi, misalnya buku, skripsi, tesis, jurnal, modul.

Atribut inti:

1. id
2. name
3. code
4. is_active
5. created_at
6. updated_at

Relasi:

1. hasMany BibliographicRecord

### 10.3.7 Model RackLocation

Nama Class:
`RackLocation`

Namespace:
`App\Modules\MasterData\Models\RackLocation`

Nama File:
`app/Modules/MasterData/Models/RackLocation.php`

Tujuan:

1. Menyimpan lokasi rak fisik.

Atribut inti:

1. id
2. code
3. name
4. floor
5. room
6. description
7. is_active
8. created_at
9. updated_at

Relasi:

1. hasMany PhysicalItem

### 10.3.8 Model Faculty

Nama Class:
`Faculty`

Namespace:
`App\Modules\MasterData\Models\Faculty`

Nama File:
`app/Modules/MasterData/Models/Faculty.php`

Tujuan:

1. Menyimpan fakultas untuk data anggota.

Atribut inti:

1. id
2. code
3. name
4. is_active
5. created_at
6. updated_at

Relasi:

1. hasMany StudyProgram
2. hasMany Member

### 10.3.9 Model StudyProgram

Nama Class:
`StudyProgram`

Namespace:
`App\Modules\MasterData\Models\StudyProgram`

Nama File:
`app/Modules/MasterData/Models/StudyProgram.php`

Tujuan:

1. Menyimpan program studi anggota.

Atribut inti:

1. id
2. faculty_id
3. code
4. name
5. is_active
6. created_at
7. updated_at

Relasi:

1. belongsTo Faculty
2. hasMany Member

### 10.3.10 Model ItemCondition

Nama Class:
`ItemCondition`

Namespace:
`App\Modules\MasterData\Models\ItemCondition`

Nama File:
`app/Modules/MasterData/Models/ItemCondition.php`

Tujuan:

1. Menyimpan kondisi item seperti baik, rusak ringan, rusak berat.

Atribut inti:

1. id
2. code
3. name
4. severity_level
5. is_active
6. created_at
7. updated_at

Relasi:

1. hasMany PhysicalItem
2. hasMany ReturnTransaction bila kondisi kembali dicatat terpisah

## 10.4 Modul Catalog

### 10.4.1 Model BibliographicRecord

Nama Class:
`BibliographicRecord`

Namespace:
`App\Modules\Catalog\Models\BibliographicRecord`

Nama File:
`app/Modules/Catalog/Models/BibliographicRecord.php`

Tujuan:

1. Menjadi induk metadata karya atau judul.
2. Menyatukan koleksi fisik dan digital.

Atribut inti:

1. id
2. title
3. slug
4. publisher_id
5. language_id
6. classification_id
7. collection_type_id
8. publication_year
9. isbn
10. edition
11. keywords
12. abstract
13. cover_path
14. publication_status
15. is_public
16. metadata_json
17. created_by
18. updated_by
19. created_at
20. updated_at
21. deleted_at

Fillable rekomendasi:

1. title
2. slug
3. publisher_id
4. language_id
5. classification_id
6. collection_type_id
7. publication_year
8. isbn
9. edition
10. keywords
11. abstract
12. cover_path
13. publication_status
14. is_public
15. metadata_json

Casts:

1. is_public => boolean
2. metadata_json => array
3. created_at => datetime
4. updated_at => datetime

Relasi:

1. belongsTo Publisher
2. belongsTo Language
3. belongsTo Classification
4. belongsTo CollectionType
5. belongsToMany Author melalui BibliographicRecordAuthor
6. belongsToMany Subject melalui BibliographicRecordSubject
7. hasMany PhysicalItem
8. hasMany DigitalAsset
9. belongsTo User createdBy
10. belongsTo User updatedBy

Scope:

1. scopePublic
2. scopePublished
3. scopeKeyword
4. scopeByCollectionType
5. scopeByYear
6. scopeByLanguage

Helper ringan:

1. isPublished()
2. hasPhysicalItems()
3. hasDigitalAssets()

Policy:

1. BibliographicRecordPolicy

Catatan:

1. Model ini adalah pusat domain koleksi.
2. Search index publik terutama diturunkan dari model ini.

### 10.4.2 Model BibliographicRecordAuthor

Nama Class:
`BibliographicRecordAuthor`

Namespace:
`App\Modules\Catalog\Models\BibliographicRecordAuthor`

Nama File:
`app/Modules/Catalog/Models/BibliographicRecordAuthor.php`

Tujuan:

1. Menjadi model pivot eksplisit relasi record ke author.
2. Memungkinkan urutan pengarang dan metadata relasi.

Atribut inti:

1. id
2. bibliographic_record_id
3. author_id
4. author_order
5. role_label
6. created_at
7. updated_at

Relasi:

1. belongsTo BibliographicRecord
2. belongsTo Author

Catatan:

1. Model pivot eksplisit dipilih agar lebih siap untuk urutan penulis dan audit.

### 10.4.3 Model BibliographicRecordSubject

Nama Class:
`BibliographicRecordSubject`

Namespace:
`App\Modules\Catalog\Models\BibliographicRecordSubject`

Nama File:
`app/Modules/Catalog/Models/BibliographicRecordSubject.php`

Tujuan:

1. Menjadi model pivot eksplisit relasi record ke subject.

Atribut inti:

1. id
2. bibliographic_record_id
3. subject_id
4. created_at
5. updated_at

Relasi:

1. belongsTo BibliographicRecord
2. belongsTo Subject

## 10.5 Modul Collection

### 10.5.1 Model PhysicalItem

Nama Class:
`PhysicalItem`

Namespace:
`App\Modules\Collection\Models\PhysicalItem`

Nama File:
`app/Modules/Collection/Models/PhysicalItem.php`

Tujuan:

1. Mewakili satu eksemplar fisik dari sebuah bibliographic record.

Atribut inti:

1. id
2. bibliographic_record_id
3. rack_location_id
4. item_condition_id
5. barcode
6. inventory_code
7. acquisition_date
8. item_status
9. notes
10. created_at
11. updated_at
12. deleted_at

Fillable rekomendasi:

1. bibliographic_record_id
2. rack_location_id
3. item_condition_id
4. barcode
5. inventory_code
6. acquisition_date
7. item_status
8. notes

Casts:

1. acquisition_date => date
2. created_at => datetime
3. updated_at => datetime

Relasi:

1. belongsTo BibliographicRecord
2. belongsTo RackLocation
3. belongsTo ItemCondition
4. hasMany Loan
5. hasMany ReturnTransaction
6. hasMany PhysicalItemStatusHistory

Scope:

1. scopeAvailable
2. scopeLoaned
3. scopeKeyword
4. scopeByStatus
5. scopeByRack

Helper ringan:

1. isAvailable()
2. isLoaned()

Policy:

1. PhysicalItemPolicy

### 10.5.2 Model PhysicalItemStatusHistory

Nama Class:
`PhysicalItemStatusHistory`

Namespace:
`App\Modules\Collection\Models\PhysicalItemStatusHistory`

Nama File:
`app/Modules/Collection/Models/PhysicalItemStatusHistory.php`

Tujuan:

1. Menyimpan histori perubahan status item.

Atribut inti:

1. id
2. physical_item_id
3. old_status
4. new_status
5. reason
6. changed_by
7. created_at

Relasi:

1. belongsTo PhysicalItem
2. belongsTo User changedBy

Catatan:

1. Model ini mendukung histori item dan audit operasional yang lebih mudah dibaca.

## 10.6 Modul Member

### 10.6.1 Model Member

Nama Class:
`Member`

Namespace:
`App\Modules\Member\Models\Member`

Nama File:
`app/Modules/Member/Models/Member.php`

Tujuan:

1. Mewakili anggota perpustakaan.
2. Menyimpan identitas anggota non-admin.

Atribut inti:

1. id
2. member_number
3. member_type
4. identity_number
5. name
6. email
7. phone
8. faculty_id
9. study_program_id
10. is_active
11. is_blocked
12. blocked_reason
13. blocked_at
14. notes
15. created_at
16. updated_at
17. deleted_at

Fillable rekomendasi:

1. member_number
2. member_type
3. identity_number
4. name
5. email
6. phone
7. faculty_id
8. study_program_id
9. is_active
10. is_blocked
11. blocked_reason
12. blocked_at
13. notes

Casts:

1. is_active => boolean
2. is_blocked => boolean
3. blocked_at => datetime
4. created_at => datetime
5. updated_at => datetime

Relasi:

1. belongsTo Faculty
2. belongsTo StudyProgram
3. hasMany Loan
4. hasMany Fine

Scope:

1. scopeActive
2. scopeBlocked
3. scopeByType
4. scopeKeyword

Helper ringan:

1. isEligibleForLoanBasic()
2. isBlocked()

Policy:

1. MemberPolicy

Catatan:

1. Member berbeda dari User.
2. Integrasi member ke akun kampus dapat menjadi fase lanjutan.

## 10.7 Modul Circulation

### 10.7.1 Model Loan

Nama Class:
`Loan`

Namespace:
`App\Modules\Circulation\Models\Loan`

Nama File:
`app/Modules/Circulation/Models/Loan.php`

Tujuan:

1. Mewakili transaksi pinjam item fisik.

Atribut inti:

1. id
2. member_id
3. physical_item_id
4. loan_date
5. due_date
6. returned_at
7. loan_status
8. loaned_by
9. closed_by
10. notes
11. created_at
12. updated_at

Fillable rekomendasi:

1. member_id
2. physical_item_id
3. loan_date
4. due_date
5. returned_at
6. loan_status
7. loaned_by
8. closed_by
9. notes

Casts:

1. loan_date => datetime
2. due_date => datetime
3. returned_at => datetime

Relasi:

1. belongsTo Member
2. belongsTo PhysicalItem
3. belongsTo User loanedBy
4. belongsTo User closedBy
5. hasMany LoanRenewal
6. hasOne ReturnTransaction
7. hasOne Fine

Scope:

1. scopeActive
2. scopeReturned
3. scopeOverdue
4. scopeByMember
5. scopeByItem

Helper ringan:

1. isActive()
2. isOverdue()

Policy:

1. LoanPolicy

### 10.7.2 Model LoanRenewal

Nama Class:
`LoanRenewal`

Namespace:
`App\Modules\Circulation\Models\LoanRenewal`

Nama File:
`app/Modules/Circulation/Models/LoanRenewal.php`

Tujuan:

1. Menyimpan histori perpanjangan pinjaman.

Atribut inti:

1. id
2. loan_id
3. old_due_date
4. new_due_date
5. renewed_by
6. notes
7. created_at

Relasi:

1. belongsTo Loan
2. belongsTo User renewedBy

### 10.7.3 Model ReturnTransaction

Nama Class:
`ReturnTransaction`

Namespace:
`App\Modules\Circulation\Models\ReturnTransaction`

Nama File:
`app/Modules/Circulation/Models/ReturnTransaction.php`

Tujuan:

1. Menyimpan detail pengembalian item.

Atribut inti:

1. id
2. loan_id
3. physical_item_id
4. returned_at
5. returned_by
6. returned_condition_id
7. late_days
8. fine_amount
9. notes
10. created_at
11. updated_at

Relasi:

1. belongsTo Loan
2. belongsTo PhysicalItem
3. belongsTo User returnedBy
4. belongsTo ItemCondition returnedCondition

Casts:

1. returned_at => datetime
2. fine_amount => decimal:2

### 10.7.4 Model Fine

Nama Class:
`Fine`

Namespace:
`App\Modules\Circulation\Models\Fine`

Nama File:
`app/Modules/Circulation/Models/Fine.php`

Tujuan:

1. Menyimpan denda dari keterlambatan atau kebijakan lain yang disetujui.

Atribut inti:

1. id
2. loan_id
3. member_id
4. fine_type
5. amount
6. late_days
7. status
8. notes
9. created_at
10. updated_at

Relasi:

1. belongsTo Loan
2. belongsTo Member

Casts:

1. amount => decimal:2

Scope:

1. scopeOutstanding
2. scopePaid bila nanti ada status pelunasan
3. scopeByType

Catatan:

1. Pada fase 1, status pembayaran denda dapat dibuat sederhana.
2. Bila kampus belum memerlukan pelunasan formal, field status tetap disiapkan untuk perluasan.

## 10.8 Modul Digital Repository

### 10.8.1 Model DigitalAsset

Nama Class:
`DigitalAsset`

Namespace:
`App\Modules\DigitalRepository\Models\DigitalAsset`

Nama File:
`app/Modules/DigitalRepository/Models/DigitalAsset.php`

Tujuan:

1. Mewakili file digital yang terkait dengan bibliographic record.

Atribut inti:

1. id
2. bibliographic_record_id
3. asset_type
4. file_name
5. original_file_name
6. file_path
7. mime_type
8. file_extension
9. file_size
10. checksum
11. title
12. description
13. publication_status
14. is_public
15. is_embargoed
16. embargo_until
17. uploaded_by
18. uploaded_at
19. created_at
20. updated_at
21. deleted_at

Fillable rekomendasi:

1. bibliographic_record_id
2. asset_type
3. file_name
4. original_file_name
5. file_path
6. mime_type
7. file_extension
8. file_size
9. checksum
10. title
11. description
12. publication_status
13. is_public
14. is_embargoed
15. embargo_until
16. uploaded_by
17. uploaded_at

Casts:

1. is_public => boolean
2. is_embargoed => boolean
3. embargo_until => datetime
4. uploaded_at => datetime
5. created_at => datetime
6. updated_at => datetime

Relasi:

1. belongsTo BibliographicRecord
2. belongsTo User uploadedBy
3. hasOne OcrText
4. hasMany DigitalAssetAccessRule

Scope:

1. scopePublic
2. scopePublished
3. scopeEmbargoed
4. scopeByType
5. scopeKeyword

Helper ringan:

1. isPreviewable()
2. isCurrentlyPublic()
3. isUnderEmbargo()

Policy:

1. DigitalAssetPolicy

### 10.8.2 Model DigitalAssetAccessRule

Nama Class:
`DigitalAssetAccessRule`

Namespace:
`App\Modules\DigitalRepository\Models\DigitalAssetAccessRule`

Nama File:
`app/Modules/DigitalRepository/Models/DigitalAssetAccessRule.php`

Tujuan:

1. Menyimpan aturan akses aset digital secara eksplisit bila dibutuhkan.
2. Mendukung rule akses yang lebih fleksibel daripada hanya is_public.

Atribut inti:

1. id
2. digital_asset_id
3. access_scope
4. role_name
5. member_type
6. allow_preview
7. allow_download
8. created_at
9. updated_at

Relasi:

1. belongsTo DigitalAsset

Casts:

1. allow_preview => boolean
2. allow_download => boolean

Catatan:

1. Bila fase 1 ingin sederhana, tabel ini tetap disiapkan namun rule minimum tetap bisa bergantung pada status publikasi.
2. Model ini menjadi penting untuk fase lanjut.

### 10.8.3 Model OcrText

Nama Class:
`OcrText`

Namespace:
`App\Modules\DigitalRepository\Models\OcrText`

Nama File:
`app/Modules/DigitalRepository/Models/OcrText.php`

Tujuan:

1. Menyimpan hasil OCR atau teks ekstraksi dokumen digital.

Atribut inti:

1. id
2. digital_asset_id
3. source_type
4. extracted_text
5. extraction_status
6. extracted_at
7. error_message
8. created_at
9. updated_at

Relasi:

1. belongsTo DigitalAsset

Casts:

1. extracted_at => datetime

Scope:

1. scopeSuccess
2. scopeFailed

Catatan:

1. Teks OCR dapat digunakan untuk indexing.
2. Model ini tidak langsung ditampilkan ke publik.

## 10.9 Modul Audit

### 10.9.1 Model ActivityLog

Nama Class:
`ActivityLog`

Namespace:
`App\Modules\Audit\Models\ActivityLog`

Nama File:
`app/Modules/Audit/Models/ActivityLog.php`

Tujuan:

1. Menyimpan jejak aktivitas penting sistem.

Atribut inti:

1. id
2. user_id
3. action
4. module_name
5. subject_type
6. subject_id
7. description
8. old_values
9. new_values
10. ip_address
11. user_agent
12. created_at

Fillable rekomendasi:

1. user_id
2. action
3. module_name
4. subject_type
5. subject_id
6. description
7. old_values
8. new_values
9. ip_address
10. user_agent

Casts:

1. old_values => array
2. new_values => array
3. created_at => datetime

Relasi:

1. belongsTo User

Scope:

1. scopeByModule
2. scopeByAction
3. scopeByUser
4. scopeBetweenDates

Policy:

1. ActivityLogPolicy

## 10.10 Model Tambahan Opsional

### 10.10.1 Model ReportExportHistory

Nama Class:
`ReportExportHistory`

Namespace:
`App\Modules\Reporting\Models\ReportExportHistory`

Nama File:
`app/Modules/Reporting/Models/ReportExportHistory.php`

Status:
Opsional terkontrol

Tujuan:

1. Menyimpan histori ekspor laporan.

Atribut inti:

1. id
2. exported_by
3. report_type
4. filter_payload
5. file_name
6. created_at

### 10.10.2 Model QueueMonitorSnapshot

Nama Class:
`QueueMonitorSnapshot`

Namespace:
`App\Modules\Audit\Models\QueueMonitorSnapshot`

Nama File:
`app/Modules/Audit/Models/QueueMonitorSnapshot.php`

Status:
Opsional terkontrol

Tujuan:

1. Menyimpan snapshot monitoring queue bila ingin dicatat persisten.

## 11. Relasi Antar Model Utama

### 11.1 Relasi BibliographicRecord

1. BibliographicRecord belongsTo Publisher
2. BibliographicRecord belongsTo Language
3. BibliographicRecord belongsTo Classification
4. BibliographicRecord belongsTo CollectionType
5. BibliographicRecord belongsToMany Author
6. BibliographicRecord belongsToMany Subject
7. BibliographicRecord hasMany PhysicalItem
8. BibliographicRecord hasMany DigitalAsset

### 11.2 Relasi PhysicalItem

1. PhysicalItem belongsTo BibliographicRecord
2. PhysicalItem belongsTo RackLocation
3. PhysicalItem belongsTo ItemCondition
4. PhysicalItem hasMany Loan
5. PhysicalItem hasMany ReturnTransaction
6. PhysicalItem hasMany PhysicalItemStatusHistory

### 11.3 Relasi Member

1. Member belongsTo Faculty
2. Member belongsTo StudyProgram
3. Member hasMany Loan
4. Member hasMany Fine

### 11.4 Relasi Loan

1. Loan belongsTo Member
2. Loan belongsTo PhysicalItem
3. Loan hasMany LoanRenewal
4. Loan hasOne ReturnTransaction
5. Loan hasOne Fine

### 11.5 Relasi DigitalAsset

1. DigitalAsset belongsTo BibliographicRecord
2. DigitalAsset belongsTo User uploadedBy
3. DigitalAsset hasOne OcrText
4. DigitalAsset hasMany DigitalAssetAccessRule

### 11.6 Relasi Audit

1. ActivityLog belongsTo User

## 12. Matriks Model ke Modul

| Model                      | Modul             |
| -------------------------- | ----------------- |
| User                       | Identity          |
| Role                       | Identity          |
| Permission                 | Identity          |
| SystemSetting              | Core              |
| InstitutionProfile         | Core              |
| Author                     | MasterData        |
| Publisher                  | MasterData        |
| Language                   | MasterData        |
| Classification             | MasterData        |
| Subject                    | MasterData        |
| CollectionType             | MasterData        |
| RackLocation               | MasterData        |
| Faculty                    | MasterData        |
| StudyProgram               | MasterData        |
| ItemCondition              | MasterData        |
| BibliographicRecord        | Catalog           |
| BibliographicRecordAuthor  | Catalog           |
| BibliographicRecordSubject | Catalog           |
| PhysicalItem               | Collection        |
| PhysicalItemStatusHistory  | Collection        |
| Member                     | Member            |
| Loan                       | Circulation       |
| LoanRenewal                | Circulation       |
| ReturnTransaction          | Circulation       |
| Fine                       | Circulation       |
| DigitalAsset               | DigitalRepository |
| DigitalAssetAccessRule     | DigitalRepository |
| OcrText                    | DigitalRepository |
| ActivityLog                | Audit             |

## 13. Matriks Model ke Controller

| Model               | Controller Utama              |
| ------------------- | ----------------------------- |
| User                | UserController                |
| Role                | RoleController                |
| Permission          | PermissionController          |
| InstitutionProfile  | InstitutionProfileController  |
| SystemSetting       | OperationalRuleController     |
| Author              | AuthorController              |
| Publisher           | PublisherController           |
| Language            | LanguageController            |
| Classification      | ClassificationController      |
| Subject             | SubjectController             |
| CollectionType      | CollectionTypeController      |
| RackLocation        | RackLocationController        |
| Faculty             | FacultyController             |
| StudyProgram        | StudyProgramController        |
| ItemCondition       | ItemConditionController       |
| BibliographicRecord | BibliographicRecordController |
| PhysicalItem        | PhysicalItemController        |
| Member              | MemberController              |
| Loan                | LoanController                |
| ReturnTransaction   | ReturnController              |
| Fine                | FineController                |
| DigitalAsset        | DigitalAssetController        |
| ActivityLog         | AuditLogController            |

## 14. Matriks Model ke Service

| Model                      | Service Utama                                                                                 |
| -------------------------- | --------------------------------------------------------------------------------------------- |
| User                       | UserManagementService, AuthenticationService                                                  |
| Role                       | RoleManagementService, RolePermissionAssignmentService                                        |
| Permission                 | PermissionMatrixService, RolePermissionAssignmentService                                      |
| SystemSetting              | OperationalRuleService                                                                        |
| InstitutionProfile         | InstitutionProfileService                                                                     |
| Author                     | AuthorService, BibliographicRecordService                                                     |
| Publisher                  | PublisherService, BibliographicRecordService                                                  |
| Language                   | LanguageService, BibliographicRecordService                                                   |
| Classification             | ClassificationService, BibliographicRecordService                                             |
| Subject                    | SubjectService, BibliographicRecordService                                                    |
| CollectionType             | CollectionTypeService, BibliographicRecordService                                             |
| RackLocation               | RackLocationService, PhysicalItemService                                                      |
| Faculty                    | FacultyService, MemberService                                                                 |
| StudyProgram               | StudyProgramService, MemberService                                                            |
| ItemCondition              | ItemConditionService, PhysicalItemService, ReturnProcessingService                            |
| BibliographicRecord        | BibliographicRecordService, CatalogPublicationService, SearchIndexService                     |
| BibliographicRecordAuthor  | BibliographicRecordService                                                                    |
| BibliographicRecordSubject | BibliographicRecordService                                                                    |
| PhysicalItem               | PhysicalItemService, PhysicalItemStatusService, LoanTransactionService                        |
| PhysicalItemStatusHistory  | PhysicalItemHistoryService, PhysicalItemStatusService                                         |
| Member                     | MemberService, MemberStatusService, MemberBlockingService, LoanTransactionService             |
| Loan                       | LoanTransactionService, ReturnProcessingService, RenewalService, ActiveLoanService            |
| LoanRenewal                | RenewalService, RenewalListService                                                            |
| ReturnTransaction          | ReturnProcessingService                                                                       |
| Fine                       | FineCalculationService, FineReportingService, FineReportService                               |
| DigitalAsset               | DigitalAssetService, DigitalAssetUploadService, DigitalAssetAccessService, SearchIndexService |
| DigitalAssetAccessRule     | DigitalAssetAccessService                                                                     |
| OcrText                    | OcrProcessingService, SearchIndexService                                                      |
| ActivityLog                | AuditLogService, AuditLogQueryService                                                         |

## 15. Scope dan Helper Rekomendasi per Model

### 15.1 Scope Wajib Bernilai Tinggi

Model yang sebaiknya punya scope resmi:

User:

1. active
2. inactive
3. keyword

Author:

1. active
2. keyword

BibliographicRecord:

1. public
2. published
3. keyword
4. byCollectionType
5. byYear

PhysicalItem:

1. available
2. loaned
3. byStatus
4. byRack
5. keyword

Member:

1. active
2. blocked
3. byType
4. keyword

Loan:

1. active
2. returned
3. overdue
4. byMember
5. byItem

DigitalAsset:

1. public
2. published
3. embargoed
4. byType
5. keyword

ActivityLog:

1. byModule
2. byAction
3. byUser
4. betweenDates

### 15.2 Helper Ringan yang Direkomendasikan

BibliographicRecord:

1. isPublished()
2. hasPhysicalItems()
3. hasDigitalAssets()

PhysicalItem:

1. isAvailable()
2. isLoaned()

Member:

1. isBlocked()
2. isEligibleForLoanBasic()

Loan:

1. isActive()
2. isOverdue()

DigitalAsset:

1. isPreviewable()
2. isCurrentlyPublic()
3. isUnderEmbargo()

Catatan:

1. Helper ini ringan dan hanya berbasis atribut atau relasi yang sudah dimuat.
2. Helper tidak boleh berkembang menjadi logic service berat.

## 16. Aturan Fillable, Guarded, dan Hidden

Aturan umum:

1. Gunakan fillable yang eksplisit pada model bisnis.
2. Hindari guarded kosong tanpa alasan.
3. Sembunyikan field sensitif dari serialisasi.

Field yang harus hidden minimal:

1. User.password
2. User.remember_token bila dipakai

Field JSON yang harus di-cast:

1. BibliographicRecord.metadata_json
2. ActivityLog.old_values
3. ActivityLog.new_values

## 17. Aturan Soft Delete

Soft delete direkomendasikan untuk model berikut:

1. User
2. BibliographicRecord
3. PhysicalItem
4. Member
5. DigitalAsset

Pertimbangan:

1. Data transaksi tidak sebaiknya hilang langsung.
2. Koleksi dan aset perlu jejak histori.
3. Soft delete memudahkan audit dan pemulihan data.

Model yang tidak wajib soft delete:

1. Pivot relasi
2. ActivityLog
3. LoanRenewal
4. ReturnTransaction
5. Fine
6. Permission
7. Role, tergantung kebijakan implementasi

## 18. Aturan Policy Binding

Model yang wajib memiliki policy:

1. User
2. BibliographicRecord
3. PhysicalItem
4. Member
5. Loan
6. DigitalAsset
7. ActivityLog

Model yang boleh memakai permission level route tanpa policy detail:

1. Master data tertentu
2. SystemSetting
3. InstitutionProfile
4. Role
5. Permission

Catatan:

1. Policy detail lebih penting untuk model yang memerlukan otorisasi berbasis resource.

## 19. Aturan Model Binding pada Route

Route model binding direkomendasikan untuk:

1. User
2. Role
3. Author
4. Publisher
5. Language
6. Classification
7. Subject
8. CollectionType
9. RackLocation
10. Faculty
11. StudyProgram
12. ItemCondition
13. BibliographicRecord
14. PhysicalItem
15. Member
16. Loan
17. DigitalAsset
18. ActivityLog

Aturan:

1. Gunakan id atau uuid konsisten pada schema.
2. Untuk OPAC publik, slug pada BibliographicRecord bisa dipakai bila disepakati pada schema.
3. Bila slug dipakai, harus tetap unik.

## 20. Aturan Model dan Search Index

Model yang paling berkaitan dengan search index:

1. BibliographicRecord
2. Author
3. Subject
4. CollectionType
5. DigitalAsset
6. OcrText

Aturan:

1. Search index dibangun dari BibliographicRecord sebagai pusat.
2. Atribut author dan subject diturunkan melalui relasi.
3. Atribut digital dan OCR hanya ikut bila layak tampil publik dan diizinkan.

## 21. Aturan Model dan Storage File

Model yang terkait storage file:

1. InstitutionProfile untuk logo
2. BibliographicRecord untuk cover
3. DigitalAsset untuk file utama

Aturan:

1. Model hanya menyimpan path dan metadata file.
2. Penyimpanan file nyata dikelola oleh service.
3. Model tidak boleh membaca atau menulis file ke storage langsung dalam logic berat.

## 22. Aturan Model dan Audit

Model yang wajib menimbulkan audit saat berubah:

1. User
2. Role dan Permission melalui service assignment
3. InstitutionProfile
4. SystemSetting
5. Semua master data
6. BibliographicRecord
7. PhysicalItem
8. Member
9. Loan
10. ReturnTransaction
11. Fine
12. DigitalAsset
13. DigitalAssetAccessRule

Catatan:

1. Audit dicatat oleh service, bukan model event otomatis sebagai kewajiban utama.
2. Model event boleh dipakai hati-hati, tetapi service tetap pusat audit resmi.

## 23. Aturan Model Event

Penggunaan model event diperbolehkan secara terbatas untuk hal ringan, misalnya:

1. normalisasi slug
2. normalisasi nama
3. set default value

Tidak boleh memakai model event untuk:

1. logika pinjam
2. logika denda
3. publish katalog
4. upload aset
5. OCR
6. reindex besar
7. role assignment

## 24. Model yang Tidak Boleh Ada pada Fase 1

Model berikut tidak boleh dibuat sebagai model resmi fase 1:

1. Payment
2. Invoice
3. Reservation lanjutan
4. AcquisitionWorkflow
5. RfidTag
6. SsoToken
7. MultiCampus
8. Tenant
9. ExternalApiClientData
10. MobileDeviceSession

Catatan:

1. Bila nanti diperlukan, harus melalui revisi blueprint formal.

## 25. Matriks Prioritas Implementasi Model

### Prioritas P1

1. User
2. Role
3. Permission
4. SystemSetting
5. InstitutionProfile
6. Author
7. Publisher
8. Language
9. Classification
10. Subject
11. CollectionType
12. RackLocation
13. Faculty
14. StudyProgram
15. ItemCondition
16. BibliographicRecord
17. BibliographicRecordAuthor
18. BibliographicRecordSubject
19. PhysicalItem
20. Member
21. Loan
22. ReturnTransaction
23. Fine
24. DigitalAsset
25. OcrText
26. ActivityLog

### Prioritas P2

1. PhysicalItemStatusHistory
2. LoanRenewal
3. DigitalAssetAccessRule

### Prioritas P3

1. ReportExportHistory
2. QueueMonitorSnapshot
3. Model fase lanjutan lain bila sudah diaktifkan

## 26. Mapping ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 14_SCHEMA.sql
2. 15_SEED.sql
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

1. Semua model di dokumen ini harus punya tabel atau pivot di 14_SCHEMA.sql.
2. Semua relasi model harus tervalidasi di schema.
3. Semua field inti model harus terpetakan ke kolom nyata.
4. Semua status yang disebut model harus cocok dengan workflow state machine.
5. Semua kebutuhan seed harus mengacu model ini.

## 27. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua controller utama pada 11_CONTROLLER_MAP.md memiliki model pendukung yang jelas.
2. Semua service utama pada 12_SERVICE_LAYER.md memiliki model domain yang jelas.
3. Semua modul inti pada 03_ARSITEKTUR_MODULAR.md sudah memiliki model utama.
4. Semua relasi penting katalog, item, anggota, sirkulasi, dan aset digital sudah terdefinisi.
5. Tidak ada model liar di luar ruang lingkup fase 1.
6. Tidak ada entity bisnis penting yang hilang dari model map.

## 28. Kesimpulan

Dokumen Model Map ini menetapkan fondasi entity dan relasi data resmi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 12. Dokumen ini menutup rantai dari controller dan service menuju desain data yang konkret, sekaligus memastikan bahwa katalog, koleksi fisik, anggota, sirkulasi, repositori digital, OPAC, dan audit memiliki model yang jelas, terstruktur, dan siap diturunkan ke schema database. Semua implementasi model dan desain tabel PERPUSQU wajib merujuk dokumen ini.

END OF 13_MODEL_MAP.md
