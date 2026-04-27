# 21_SEARCH_INDEXING_SPEC.md

## 1. Nama Dokumen

Search and Indexing Specification Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi pencarian, indexing, dan discovery layer

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan mesin pencarian, struktur dokumen index, pipeline indexing, trigger reindex, aturan visibilitas data, sinkronisasi MySQL dan Meilisearch, serta perilaku pencarian OPAC dan pencarian internal

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan spesifikasi resmi fitur pencarian dan indexing PERPUSQU agar seluruh proses discovery koleksi fisik dan digital berjalan cepat, konsisten, aman, dan selaras dengan arsitektur monolith modular yang telah ditetapkan. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar implementasi search tidak liar, tidak menyimpang dari state machine, tidak membocorkan data privat, dan tetap konsisten dengan model, service, API, UI UX, dan schema database yang telah disepakati.

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

Aturan wajib:

1. MySQL tetap sumber kebenaran final.
2. Meilisearch adalah engine discovery utama untuk pencarian cepat.
3. Semua hasil pencarian publik wajib tunduk pada aturan visibilitas.
4. Tidak ada data privat yang boleh masuk ke index publik.
5. Semua proses indexing wajib dipicu melalui service layer resmi.
6. Search harus mendukung konsep perpustakaan hibrid, yaitu fisik dan digital dalam satu hasil discovery.
7. Indexing wajib konsisten dengan workflow OCR dan publication state.

## 4. Ruang Lingkup Spesifikasi

Dokumen ini mencakup:

1. Tujuan fitur search
2. Peran MySQL dan Meilisearch
3. Entity yang diindex
4. Struktur dokumen index
5. Aturan visibilitas indexing
6. Trigger reindex
7. Lifecycle indexing
8. Proses full reindex dan incremental reindex
9. Query behavior OPAC
10. Query behavior internal
11. Search suggestion
12. OCR contribution ke search
13. Ranking dan relevansi
14. Synonym dan typo tolerance
15. Fallback behavior
16. Monitoring dan kegagalan indexing
17. Strategi testing search

## 5. Tujuan Search PERPUSQU

Fitur search PERPUSQU harus mencapai tujuan berikut:

1. Memungkinkan pengguna publik menemukan koleksi fisik dan digital dari satu titik pencarian.
2. Memungkinkan petugas internal menemukan bibliographic record dengan cepat.
3. Memungkinkan operator repositori menemukan aset digital secara cepat.
4. Mendukung pencarian berdasarkan judul, pengarang, subjek, ISBN, dan metadata lain yang relevan.
5. Mendukung pencarian OPAC yang sederhana dan mudah dipahami.
6. Tetap menjaga keamanan data privat.

## 6. Prinsip Umum Search dan Indexing

Prinsip resmi search dan indexing PERPUSQU adalah:

1. Search berpusat pada bibliographic record.
2. Bibliographic record menjadi unit discovery utama.
3. Item fisik dan aset digital tidak menjadi entry pencarian publik utama yang berdiri sendiri, tetapi menjadi atribut turunan dari bibliographic record.
4. OCR hanya menambah kualitas pencarian, bukan mengganti metadata utama.
5. Perubahan visibilitas harus memengaruhi hasil pencarian.
6. Index harus ringan, relevan, dan aman.
7. Hasil pencarian harus cepat, tetapi data akhir tetap diverifikasi ke MySQL.

## 7. Arsitektur Search Resmi

Arsitektur search resmi adalah:

1. MySQL menyimpan data transaksi dan data master resmi.
2. Meilisearch menyimpan dokumen index untuk discovery.
3. SearchIndexService bertanggung jawab membangun dan mengirim dokumen index.
4. CatalogSearchService bertanggung jawab melakukan query pencarian.
5. OcrProcessingService dapat memperkaya payload pencarian.
6. OPAC dan endpoint search internal membaca hasil dari Meilisearch, lalu melakukan hydration dari MySQL untuk memastikan data final sah.

Alur standar:

1. User mengirim query pencarian.
2. Sistem mengirim query ke Meilisearch.
3. Meilisearch mengembalikan daftar id record terurut.
4. CatalogSearchService memuat data final dari MySQL.
5. Sistem menyaring ulang sesuai visibilitas jika perlu.
6. View atau API menerima hasil akhir.

## 8. Use Case Search Utama

### 8.1 Use Case Publik

1. Pengguna mencari judul buku
2. Pengguna mencari nama pengarang
3. Pengguna mencari subjek
4. Pengguna mencari ISBN
5. Pengguna mencari koleksi yang memiliki preview digital publik

### 8.2 Use Case Internal

1. Pustakawan mencari bibliographic record
2. Petugas mencari record untuk tambah item
3. Operator digital mencari record untuk unggah PDF
4. Admin mencari aset digital atau status index
5. Pimpinan melihat discovery data lewat OPAC, bukan search internal teknis

## 9. Entity yang Menjadi Sumber Index

Entity utama yang membentuk dokumen index publik:

1. BibliographicRecord
2. Author
3. Subject
4. Publisher
5. Language
6. Classification
7. CollectionType
8. PhysicalItem, sebagai informasi turunan ketersediaan
9. DigitalAsset, sebagai informasi turunan akses digital
10. OcrText, sebagai sumber teks tambahan bila aman untuk publik

Catatan:

1. Entity utama yang benar benar diindex sebagai dokumen utama tetap BibliographicRecord.
2. Author dan Subject tidak menjadi dokumen publik terpisah pada fase 1.
3. Aset digital tidak menjadi dokumen discovery utama publik terpisah pada fase 1.

## 10. Entity yang Tidak Boleh Diindex ke Search Publik

Data berikut tidak boleh masuk ke index publik:

1. User internal
2. Role
3. Permission
4. Member
5. Loan
6. Fine
7. ActivityLog
8. Physical item private metadata yang tidak perlu
9. Digital asset file_path
10. Checksum
11. Access rule internal detail
12. Audit context
13. OCR error detail
14. Queue internal detail

## 11. Index Utama Fase 1

### 11.1 Nama Index Publik

Nama index utama yang direkomendasikan:
`opac_records`

### 11.2 Nama Index Internal Opsional

Jika pencarian internal perlu dipisah:

1. `admin_records`
2. `admin_digital_assets`

Catatan:

1. Fase 1 minimal wajib punya `opac_records`
2. Index internal tambahan bersifat opsional, tergantung kebutuhan performa
3. Bila index internal tidak dibuat terpisah, pencarian admin tetap dapat memanfaatkan index record utama dengan filter tambahan dan hydration MySQL

## 12. Dokumen Index Utama

Setiap dokumen pada `opac_records` mewakili satu bibliographic record.

### 12.1 Primary Key

Primary key index:
`id`

### 12.2 Struktur Dokumen Index Wajib

Struktur minimum yang direkomendasikan:

```json
{
  "id": 101,
  "title": "Administrasi Publik Modern",
  "slug": "administrasi-publik-modern",
  "isbn": "9781234567890",
  "publication_year": 2024,
  "publication_status": "published",
  "is_public": true,
  "collection_type_id": 1,
  "collection_type_name": "Buku",
  "language_id": 1,
  "language_name": "Bahasa Indonesia",
  "classification_id": 5,
  "classification_code": "300",
  "classification_name": "Ilmu Sosial",
  "publisher_id": 10,
  "publisher_name": "Penerbit Kampus",
  "author_names": [
    "John Doe",
    "Jane Doe"
  ],
  "subject_names": [
    "Administrasi Publik",
    "Kebijakan Publik"
  ],
  "keywords": "administrasi publik, birokrasi",
  "abstract": "Ringkasan karya...",
  "ocr_public_text": "teks OCR publik yang diizinkan",
  "has_physical_items": true,
  "physical_total_items": 3,
  "physical_available_items": 1,
  "has_digital_assets": true,
  "has_public_preview": true,
  "public_asset_types": [
    "ebook"
  ],
  "cover_path": "/storage/public/covers/sample.jpg",
  "updated_at": "2026-04-20 09:00:00"
}
````

## 13. Field Dokumen Index Resmi

### 13.1 Field Identitas

1. `id`
2. `title`
3. `slug`
4. `isbn`

### 13.2 Field Klasifikasi

1. `collection_type_id`
2. `collection_type_name`
3. `language_id`
4. `language_name`
5. `classification_id`
6. `classification_code`
7. `classification_name`
8. `publisher_id`
9. `publisher_name`

### 13.3 Field Relasi Multi Value

1. `author_names`
2. `subject_names`
3. `public_asset_types`

### 13.4 Field Konten

1. `keywords`
2. `abstract`
3. `ocr_public_text`

### 13.5 Field Ketersediaan

1. `has_physical_items`
2. `physical_total_items`
3. `physical_available_items`
4. `has_digital_assets`
5. `has_public_preview`

### 13.6 Field Status

1. `publication_status`
2. `is_public`

### 13.7 Field Display

1. `cover_path`
2. `publication_year`
3. `updated_at`

## 14. Field Searchable Resmi

Field yang harus searchable pada index publik:

1. `title`
2. `isbn`
3. `author_names`
4. `subject_names`
5. `keywords`
6. `abstract`
7. `classification_code`
8. `classification_name`
9. `publisher_name`
10. `ocr_public_text`

Catatan:

1. `ocr_public_text` dipakai setelah field metadata utama
2. OCR tidak boleh mengalahkan pentingnya judul dan pengarang

## 15. Field Filterable Resmi

Field yang harus filterable:

1. `collection_type_id`
2. `language_id`
3. `publication_year`
4. `publication_status`
5. `is_public`
6. `has_physical_items`
7. `has_digital_assets`
8. `has_public_preview`

Catatan:

1. OPAC publik pada fase 1 hanya memakai sebagian field filterable
2. Field filterable internal boleh bertambah bila disetujui

## 16. Field Sortable Resmi

Field yang harus sortable:

1. `title`
2. `publication_year`
3. `updated_at`
4. `physical_available_items`

Catatan:

1. Fase 1 OPAC utama tetap memakai ranking relevansi
2. Sorting lanjutan bersifat opsional

## 17. Ranking Rules yang Direkomendasikan

Ranking rules yang direkomendasikan pada Meilisearch:

1. typo
2. words
3. proximity
4. attribute
5. sort
6. exactness

Prioritas field untuk attribute ranking:

1. `title`
2. `author_names`
3. `subject_names`
4. `isbn`
5. `keywords`
6. `classification_name`
7. `publisher_name`
8. `abstract`
9. `ocr_public_text`

## 18. Search Behavior OPAC

### 18.1 Query Input

OPAC menerima:

1. query teks umum
2. filter jenis koleksi
3. filter bahasa
4. filter tahun

### 18.2 Tujuan Search OPAC

1. Menemukan record paling relevan
2. Menampilkan satu hasil terpadu untuk fisik dan digital
3. Menjaga alur pencarian sederhana

### 18.3 Field Prioritas OPAC

Urutan relevansi logis:

1. Judul tepat
2. ISBN tepat
3. Pengarang
4. Subjek
5. Kata kunci
6. Abstrak
7. OCR publik

### 18.4 Query Kosong

Perlakuan query kosong fase 1:

1. Tidak dianjurkan sebagai pencarian utama
2. Sistem dapat menampilkan hasil umum terbatas atau trending bila fitur disiapkan
3. Bila tidak disiapkan, query kosong dikembalikan sebagai hasil kosong terkontrol atau daftar publik terbatas

## 19. Search Behavior Internal

Pencarian internal berbeda dari OPAC.

Tujuan:

1. Menemukan record cepat untuk pekerjaan admin
2. Menemukan asset digital atau member dengan lookup ringan
3. Mendukung autocomplete dan pemilihan relasi

Aturan:

1. Internal search boleh membaca data yang lebih luas, tetapi tetap sesuai permission
2. Internal search tidak boleh menampilkan data yang tidak diizinkan oleh role
3. Lookup ringan tidak perlu memuat seluruh field besar

## 20. Search Suggestion

Fase 1 mendukung suggestion publik secara opsional.

### 20.1 Source Suggestion

Suggestion publik boleh berasal dari:

1. title
2. author_names
3. subject_names

### 20.2 Aturan Suggestion

1. Hanya untuk record publik
2. Hanya query dengan panjang minimum 2 karakter
3. Limit kecil, 5 atau 10 hasil
4. Tidak menampilkan abstrak
5. Tidak menampilkan data internal

## 21. Visibilitas Index Publik

### 21.1 Rule Dasar

BibliographicRecord masuk ke index publik hanya bila:

1. publication_status = published
2. is_public = 1

### 21.2 Rule Ketersediaan Aset Digital

Aset digital dapat memengaruhi dokumen index publik hanya bila:

1. Aset terkait record yang publik
2. Aset berstatus published
3. Aturan akses mengizinkan tampilan publik yang relevan
4. Embargo tidak aktif untuk preview publik
5. OCR text aman untuk masuk search publik

### 21.3 Rule OCR

OCR text boleh masuk index publik hanya bila:

1. asset public accessible
2. asset publication_status = published
3. record publik
4. tidak ada larangan akses
5. text extraction status = success

### 21.4 Rule Record Internal

Record berikut tidak boleh ada di index publik:

1. draft
2. unpublished
3. archived
4. published tetapi is_public = 0

## 22. Visibilitas Index Internal

Index internal, bila diaktifkan, boleh berisi:

1. draft
2. published
3. unpublished
4. archived

Namun tetap harus mematuhi:

1. permission
2. policy resource
3. scope backend yang sah

## 23. Trigger Reindex Resmi

Trigger reindex wajib mengacu dokumen 17_WORKFLOW_STATE_MACHINE.md.

Trigger utama:

1. create bibliographic record
2. update bibliographic record
3. publish bibliographic record
4. unpublish bibliographic record
5. archive bibliographic record
6. reactivate bibliographic record
7. create physical item
8. update physical item
9. change physical item status
10. create digital asset
11. update digital asset metadata
12. publish digital asset
13. unpublish digital asset
14. archive digital asset
15. reactivate digital asset
16. update digital asset access rule
17. OCR success
18. OCR retry success
19. delete soft atau restore entity yang memengaruhi visibilitas

## 24. Trigger Reindex per Entity

### 24.1 BibliographicRecord

Harus memicu reindex bila berubah:

1. title
2. isbn
3. publication_year
4. collection_type_id
5. language_id
6. classification_id
7. publisher_id
8. keywords
9. abstract
10. publication_status
11. is_public
12. cover_path

### 24.2 Author Relasi

Harus memicu reindex record terkait bila berubah:

1. nama author
2. relasi author ke record
3. urutan author

### 24.3 Subject Relasi

Harus memicu reindex record terkait bila berubah:

1. nama subject
2. relasi subject ke record

### 24.4 PhysicalItem

Harus memicu reindex record terkait bila berubah:

1. item_status
2. add or remove item
3. perubahan yang memengaruhi available count

### 24.5 DigitalAsset

Harus memicu reindex record terkait bila berubah:

1. publication_status
2. is_public
3. is_embargoed
4. embargo_until
5. asset_type
6. perubahan rule akses
7. delete or restore asset

### 24.6 OcrText

Harus memicu reindex record terkait bila berubah:

1. extraction_status menjadi success
2. extracted_text berubah
3. asset tidak lagi publik atau OCR tidak layak tampil

## 25. Lifecycle Indexing

Indexing lifecycle mengikuti state machine index status:

1. PENDING
2. QUEUED
3. PROCESSING
4. INDEXED
5. FAILED

Aturan:

1. Service yang memicu perubahan harus menandai reindex needed
2. SearchIndexService memutuskan queue atau sync
3. Worker menjalankan indexing
4. Status diperbarui ke indexed atau failed
5. Retry dimungkinkan untuk failed

## 26. Full Reindex

Full reindex wajib didukung untuk kebutuhan berikut:

1. pertama kali deploy
2. perubahan skema dokumen index
3. perubahan ranking rules
4. pemulihan dari kerusakan index
5. sinkronisasi besar setelah migrasi data

### 26.1 Aturan Full Reindex

1. Dibuat sebagai command atau job khusus
2. Diproses per chunk
3. Tidak memuat seluruh record ke memori sekaligus
4. Hanya mengambil record yang layak diindex untuk index publik
5. Menyertakan logging progress

### 26.2 Chunking yang Direkomendasikan

Chunk size awal yang direkomendasikan:

1. 100
2. 250
3. 500

Pilihan final ditentukan saat implementasi sesuai performa server.

## 27. Incremental Reindex

Incremental reindex adalah mekanisme utama fase 1.

Aturan:

1. Dipicu setelah perubahan entity terkait
2. Mengindex ulang hanya satu record atau sekumpulan kecil record terkait
3. Lebih efisien dari full reindex
4. Menjadi jalur default pada operasi harian

## 28. Hapus Dokumen dari Index

Dokumen harus dihapus dari index publik bila:

1. record berubah dari published ke unpublished
2. record berubah dari public ke internal
3. record diarchive
4. record dihapus lunak dan dianggap tidak lagi publik
5. service menyatakan record tidak lagi layak tampil

Aturan:

1. remove dari index dipicu via SearchIndexService
2. hydration MySQL tetap jadi verifikasi terakhir, tetapi index juga harus dibersihkan

## 29. Sinkronisasi MySQL dan Meilisearch

Aturan sinkronisasi:

1. MySQL adalah source of truth
2. Index adalah salinan pencarian
3. Hasil search harus dihydrate dari MySQL
4. Jika index mengandung id yang tidak lagi valid, service harus menyaring
5. Jika Meilisearch gagal sementara, sistem boleh fallback terbatas sesuai kebutuhan

## 30. Hydration Result

Setelah Meilisearch mengembalikan id, sistem harus:

1. Memuat record final dari MySQL
2. Memuat relasi minimum yang diperlukan
3. Menjaga urutan id sesuai ranking search
4. Menyaring record yang sudah tidak valid secara publik
5. Menyusun payload akhir untuk OPAC atau API

Relasi minimum yang biasanya dimuat:

1. authors
2. collectionType
3. language
4. digitalAssets publik ringkas
5. physicalItems ringkas atau summary count

## 31. Fallback Behavior

Jika Meilisearch tidak tersedia, perilaku fallback fase 1:

1. OPAC publik dapat menampilkan pesan pencarian sementara tidak tersedia
2. Internal lookup dapat fallback terbatas ke MySQL untuk kebutuhan penting, bila diimplementasikan
3. Fallback tidak boleh menghasilkan kebocoran data
4. Fallback tidak wajib untuk semua endpoint fase 1, tetapi harus jujur dan aman

## 32. Ranking dan Relevansi

### 32.1 Prinsip Ranking

Ranking harus mengutamakan:

1. ketepatan judul
2. ISBN exact match
3. author match
4. subject match
5. keyword match
6. abstract match
7. OCR text match

### 32.2 Exact Match

Query exact terhadap:

1. title
2. isbn
   harus lebih tinggi dari match longgar pada OCR atau abstract.

### 32.3 OCR Weight

OCR text harus diberi bobot lebih rendah daripada:

1. title
2. author_names
3. subject_names
4. isbn

### 32.4 Duplicate Suppression

Karena satu dokumen index mewakili satu bibliographic record, duplikasi hasil utama harus rendah secara desain.

## 33. Typo Tolerance

Typo tolerance direkomendasikan aktif untuk:

1. title
2. author_names
3. subject_names

Namun harus hati hati pada:

1. ISBN
2. code tertentu

Aturan:

1. ISBN lebih baik diperlakukan sebagai exact style search
2. Kode klasifikasi dapat tetap searchable tetapi tidak jadi fokus typo heavy

## 34. Synonym Strategy

Fase 1 boleh menyiapkan synonym terbatas.

Contoh arah synonym:

1. skripsi <-> thesis lokal
2. tugas akhir <-> skripsi
3. buku ajar <-> modul
4. kebijakan publik <-> public policy, bila bilingual diperlukan

Aturan:

1. Synonym harus terkontrol
2. Jangan membuat synonym berlebihan
3. Synonym disimpan dalam konfigurasi engine search atau service konfigurasi search

## 35. Stop Words

Stop words boleh diatur sesuai bahasa utama sistem.

Bahasa utama:

1. Bahasa Indonesia
2. dukungan istilah Inggris terbatas
3. Arab tidak menjadi fokus stop words fase 1

Aturan:

1. Stop words tidak boleh terlalu agresif
2. Kata penting domain perpustakaan tidak boleh dibuang

## 36. Normalisasi Search

Normalisasi sebelum query:

1. trim whitespace
2. collapse multiple spaces
3. lowercase untuk query umum
4. normalisasi simbol dasar
5. sanitasi input query sesuai validation rules

Normalisasi data index:

1. nama author normalized bila perlu
2. subject normalized bila perlu
3. slug tetap untuk routing, bukan search utama

## 37. Search Scope Publik

OPAC publik hanya boleh mengembalikan:

1. record yang PUBLIC_VISIBLE
2. aset digital yang aman untuk disimpulkan publik
3. summary fisik yang aman untuk ditampilkan

OPAC tidak boleh mengembalikan:

1. draft
2. unpublished
3. archived
4. private internal asset
5. access rule detail internal
6. OCR text privat

## 38. Search Scope Internal

Internal search dapat mengembalikan data lebih luas dengan syarat:

1. sesuai permission
2. sesuai policy
3. tidak membuka field sensitif yang tidak perlu

Contoh:

1. Pustakawan dapat mencari draft record
2. Operator digital dapat mencari asset unpublished
3. Petugas sirkulasi tidak perlu melihat OCR raw text

## 39. Search Result Card OPAC

Output akhir untuk OPAC hasil pencarian harus memuat minimal:

1. id
2. title
3. slug
4. cover_path
5. author_names ringkas
6. publication_year
7. collection_type_name
8. has_physical_items
9. physical_available_items
10. has_digital_assets
11. has_public_preview

## 40. Search Result Detail OPAC

Detail record OPAC harus mengambil dari MySQL, bukan dari index mentah saja.

Alasan:

1. data lebih lengkap
2. keamanan lebih baik
3. visibilitas akhir diverifikasi
4. relasi aktual lebih akurat

## 41. Search untuk Lookup Internal

Lookup internal berbeda dari OPAC.

Karakter:

1. cepat
2. hasil sedikit
3. ringan
4. cocok untuk autocomplete atau select2 style component

Batas hasil:

1. 5
2. 10
3. 20
4. 50

Lookup internal tidak perlu memuat:

1. abstract penuh
2. OCR text
3. relasi besar

## 42. Search Index Document Builder

Pembangunan payload index wajib dipusatkan pada SearchIndexService.

Tanggung jawab utama:

1. membaca BibliographicRecord
2. membaca author_names
3. membaca subject_names
4. menghitung summary item fisik
5. menentukan has_public_preview
6. mengambil OCR publik yang sah
7. menyusun dokumen index final

Controller dilarang:

1. membangun dokumen index langsung
2. menulis payload index manual
3. menentukan visibilitas index manual

## 43. Summary Fisik dalam Index

Data fisik yang boleh dimasukkan ke dokumen index publik:

1. has_physical_items
2. physical_total_items
3. physical_available_items

Data fisik yang tidak perlu diindex publik:

1. barcode
2. inventory_code
3. item notes internal
4. status per item secara sangat rinci bila tidak perlu

## 44. Summary Digital dalam Index

Data digital yang boleh dimasukkan ke dokumen index publik:

1. has_digital_assets
2. has_public_preview
3. public_asset_types

Data digital yang tidak boleh masuk index publik:

1. file_path
2. checksum
3. uploaded_by
4. OCR error_message
5. access rule internal detail

## 45. OCR Contribution Rules

OCR harus memperkaya pencarian secara hati hati.

### 45.1 OCR Masuk Index Bila

1. extraction_status = success
2. asset publik
3. record publik
4. text aman untuk dipakai pada discovery publik

### 45.2 OCR Tidak Masuk Index Bila

1. asset privat
2. asset unpublished
3. record tidak publik
4. extraction_status gagal
5. asset embargoed yang tidak boleh memberi preview publik, bila kebijakan mengharuskan demikian

### 45.3 Prioritas OCR

OCR text prioritasnya lebih rendah dari metadata katalog.

## 46. Search Query Logging

Search publik boleh mencatat statistik anonim atau ringkas pada fase lanjutan, tetapi fase 1 tidak wajib.

Jika dicatat:

1. jangan menyimpan data sensitif berlebihan
2. jangan menambah beban operasional berat
3. fokus pada analitik sederhana

Fase 1:

1. logging query publik bukan prioritas wajib
2. audit log formal tidak diwajibkan untuk setiap query pencarian publik

## 47. Monitoring Search dan Index

Monitoring minimum yang disarankan:

1. jumlah record publik terindex
2. jumlah job indexing gagal
3. jumlah job OCR gagal
4. timestamp full reindex terakhir
5. timestamp reindex terakhir per asset bila perlu

Sumber:

1. queue monitor
2. index status
3. ocr status
4. log aplikasi

## 48. Failure Handling Indexing

Jika indexing gagal:

1. status diubah ke FAILED
2. error dicatat di log sistem
3. queue retry dapat dilakukan
4. hasil lama pada index dapat tetap ada sementara, tetapi hydration MySQL harus menyaring bila visibilitas berubah

Aturan:

1. kegagalan index tidak boleh merusak transaksi utama create atau update record
2. namun sistem harus memiliki cara pemulihan

## 49. Failure Handling OCR

Jika OCR gagal:

1. status menjadi FAILED
2. record tetap bisa dicari lewat metadata biasa
3. reindex OCR tambahan tidak boleh memblok akses utama ke katalog
4. retry boleh dilakukan oleh role berwenang

## 50. Security Rules Search

Aturan keamanan search:

1. Query harus divalidasi
2. Filter harus whitelist
3. Index publik tidak boleh memuat data privat
4. Hydration MySQL harus memverifikasi visibilitas final
5. Endpoint publik harus rate limited bila perlu
6. Tidak boleh ada expose raw query DSL ke user publik

## 51. Search Settings yang Direkomendasikan di Meilisearch

Settings awal yang direkomendasikan untuk `opac_records`:

1. primary key: `id`
2. searchable attributes:

   1. title
   2. isbn
   3. author_names
   4. subject_names
   5. keywords
   6. classification_name
   7. publisher_name
   8. abstract
   9. ocr_public_text
3. filterable attributes:

   1. collection_type_id
   2. language_id
   3. publication_year
   4. publication_status
   5. is_public
   6. has_physical_items
   7. has_digital_assets
   8. has_public_preview
4. sortable attributes:

   1. title
   2. publication_year
   3. updated_at
   4. physical_available_items
5. displayed attributes:

   1. semua field yang aman dan diperlukan tampilan
6. stop words:

   1. disesuaikan bertahap
7. synonyms:

   1. opsional bertahap

## 52. Full Reindex Command yang Direkomendasikan

Command yang direkomendasikan:

1. `php artisan perpusqu:search:reindex-public-records`
2. `php artisan perpusqu:search:reindex-record {recordId}`
3. `php artisan perpusqu:search:sync-settings`

Catatan:

1. Nama final dapat disesuaikan saat coding
2. Makna fungsinya wajib dipertahankan

## 53. Job yang Direkomendasikan

Job queue yang direkomendasikan:

1. `ReindexBibliographicRecordJob`
2. `BulkReindexPublicRecordsJob`
3. `ProcessDigitalAssetOcrJob`
4. `SyncSearchSettingsJob`

Aturan:

1. Job search dan OCR harus dapat di-retry
2. Job harus idempotent sejauh mungkin
3. Job harus mencatat hasil minimal di log dan status field

## 54. Service yang Wajib Terlibat

Service yang wajib menjadi inti implementasi:

1. SearchIndexService
2. CatalogSearchService
3. BibliographicRecordService
4. CatalogPublicationService
5. DigitalAssetService
6. DigitalAssetAccessService
7. OcrProcessingService
8. OpacRecordDetailService

## 55. Mapping Trigger ke Service

| Trigger            | Service                                        |
| ------------------ | ---------------------------------------------- |
| publish record     | CatalogPublicationService, SearchIndexService  |
| unpublish record   | CatalogPublicationService, SearchIndexService  |
| update record      | BibliographicRecordService, SearchIndexService |
| create item        | PhysicalItemService, SearchIndexService        |
| change item status | PhysicalItemStatusService, SearchIndexService  |
| upload asset       | DigitalAssetUploadService, SearchIndexService  |
| publish asset      | DigitalAssetService, SearchIndexService        |
| update access rule | DigitalAssetAccessService, SearchIndexService  |
| OCR success        | OcrProcessingService, SearchIndexService       |

## 56. Mapping Search ke Controller

| Fungsi                        | Controller                                        |
| ----------------------------- | ------------------------------------------------- |
| OPAC search                   | SearchController                                  |
| OPAC detail record            | RecordController                                  |
| public suggestion, bila aktif | Public OPAC API controller                        |
| internal record lookup        | Internal API controller atau modul lookup terkait |

## 57. Testing Requirements Search

Pengujian minimum wajib mencakup:

1. record published dan public muncul di OPAC search
2. record draft tidak muncul di OPAC search
3. record unpublished tidak muncul di OPAC search
4. record archived tidak muncul di OPAC search
5. exact title match muncul di urutan atas
6. ISBN exact match muncul di urutan atas
7. pencarian author mengembalikan record terkait
8. pencarian subject mengembalikan record terkait
9. perubahan title memicu reindex
10. perubahan author memicu reindex
11. perubahan status item memengaruhi availability summary
12. publish asset memengaruhi has_public_preview
13. OCR success memperkaya hasil pencarian bila layak
14. asset privat tidak membuat OCR text bocor ke publik
15. remove public flag menghapus record dari index publik
16. hydration MySQL menyaring hasil yang tidak lagi valid
17. failed indexing dapat di-retry
18. public suggestion tidak mengembalikan data privat

## 58. Anti Pattern yang Dilarang

Implementasi search tidak boleh:

1. menjadikan MySQL like query sebagai jalur utama discovery publik bila Meilisearch aktif
2. mengindex seluruh tabel secara mentah tanpa kurasi
3. memasukkan data privat ke index publik
4. membangun payload index di controller
5. mengabaikan hydration MySQL
6. menyimpan file content mentah di tabel record utama
7. membiarkan OCR mengalahkan relevansi judul
8. mengabaikan workflow publication state

## 59. Prioritas Implementasi Search

### Prioritas P1

1. index publik `opac_records`
2. full reindex command publik
3. incremental reindex bibliographic record
4. OPAC search via Meilisearch
5. hydration result via MySQL
6. availability summary fisik dalam dokumen index
7. has_public_preview dalam dokumen index

### Prioritas P2

1. OCR contribution publik
2. public suggestion
3. internal record lookup berbasis search
4. reindex trigger dari asset access changes

### Prioritas P3

1. synonym management
2. analitik query
3. tuning ranking lanjutan
4. search internal index tambahan khusus admin assets

## 60. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 22_STORAGE_FILE_POLICY.md
2. 23_OCR_AND_DIGITAL_PROCESSING.md
3. 25_REPORTING_SPEC.md
4. 28_SECURITY_POLICY.md
5. 29_AUDIT_LOG_SPEC.md
6. 30_ERROR_CODE.md
7. 31_TEST_PLAN.md
8. 32_TEST_SCENARIO.md
9. 38_TREE.md
10. 39_TRACEABILITY_MATRIX.md
11. 41_BACKEND_CHECKLIST.md
12. 42_FRONTEND_CHECKLIST.md
13. 45_SMOKE_TEST_CHECKLIST.md
14. 46_UAT_CHECKLIST.md

Aturan:

1. storage policy harus menjaga field file tidak bocor ke index publik
2. OCR spec harus mengikuti aturan kontribusi OCR pada dokumen ini
3. security policy harus memuat perlindungan search endpoint
4. testing plan harus memuat uji relevansi dan visibilitas search
5. traceability matrix harus memetakan use case OPAC search ke service dan index

## 61. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. search engine utama sudah jelas
2. unit index utama sudah jelas, yaitu bibliographic record
3. field searchable, filterable, dan sortable sudah jelas
4. visibilitas index publik sudah jelas
5. trigger reindex sudah sesuai workflow
6. OCR contribution sudah diatur dengan aman
7. MySQL tetap menjadi sumber kebenaran akhir
8. tidak ada data privat yang diwajibkan masuk index publik

## 62. Kesimpulan

Dokumen Search and Indexing Specification ini menetapkan fondasi discovery layer PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 20. Dokumen ini memastikan bahwa pencarian OPAC dan pencarian internal berjalan di atas struktur yang tertib, aman, dan terukur, dengan BibliographicRecord sebagai unit discovery utama, MySQL sebagai sumber kebenaran final, dan Meilisearch sebagai engine pencarian cepat. Semua implementasi search, reindex, OCR contribution, dan relevansi hasil pada PERPUSQU wajib merujuk dokumen ini.

END OF 21_SEARCH_INDEXING_SPEC.md
