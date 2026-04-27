# 20_API_CONTRACT.md

## 1. Nama Dokumen

API Contract Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint kontrak API internal dan endpoint terkontrol

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan endpoint API internal, payload request, payload response, error contract, authorization, dan integrasi frontend terukur

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan kontrak API resmi PERPUSQU untuk kebutuhan internal yang terkontrol, terutama untuk lookup cepat, pencarian terarah, validasi operasional tertentu, preview metadata, dan pengembangan bertahap ke fitur frontend dinamis tanpa mengubah prinsip utama aplikasi sebagai monolith modular berbasis web. Dokumen ini menjadi acuan wajib agar endpoint API tidak liar, tidak duplikatif terhadap web route biasa, dan tetap konsisten dengan blueprint sebelumnya.

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

Aturan wajib:

1. API pada fase 1 bersifat internal dan terkontrol.
2. API bukan jalur utama semua fitur aplikasi.
3. API hanya dibuat untuk kebutuhan yang jelas.
4. Semua endpoint API harus punya use case atau kebutuhan frontend nyata.
5. Semua endpoint API harus konsisten dengan model, service, dan authorization.
6. Endpoint API tidak boleh membuka data sensitif tanpa kontrol yang sah.
7. Endpoint API publik harus sangat terbatas.

## 4. Ruang Lingkup API pada Fase 1

Pada fase 1, PERPUSQU tetap berorientasi web monolith modular. API disiapkan untuk kebutuhan berikut:

1. Lookup data cepat pada form admin
2. Search dan autocomplete terkontrol
3. Fetch metadata ringkas untuk komponen dinamis
4. Operasional sirkulasi tertentu yang butuh respons cepat
5. Preview metadata aset digital
6. Statistik ringan dashboard tertentu bila diperlukan
7. Integrasi frontend bertahap dengan Livewire atau komponen AJAX sederhana

Yang tidak menjadi fokus fase 1:

1. Public API penuh
2. Integrasi mobile app khusus
3. API multi tenant
4. API pihak ketiga umum
5. API pembayaran
6. API SSO aktif
7. API acquisition lanjutan
8. API RFID

## 5. Prinsip Umum API PERPUSQU

Prinsip resmi API PERPUSQU adalah:

1. Minimalis
2. Jelas
3. Konsisten
4. Aman
5. Berbasis kebutuhan nyata
6. Tidak mengulang semua web page sebagai API
7. Mudah diuji
8. Mudah dipahami AI Agent dan developer

## 6. Tipe API Resmi

### 6.1 Internal Authenticated API

Dipakai oleh:

1. Halaman admin internal
2. Komponen dinamis frontend internal
3. Lookup dan pencarian internal
4. Dashboard widget ringan

Proteksi:

1. Auth session web
2. Permission
3. Policy bila perlu

Prefix:
`/api/internal`

### 6.2 Public Read Only API Terbatas

Dipakai oleh:

1. OPAC publik tertentu bila frontend membutuhkan endpoint JSON kecil
2. Search suggestion publik ringan bila diputuskan perlu

Proteksi:

1. Publik
2. Rate limit
3. Hanya data publik

Prefix:
`/api/public`

Catatan:

1. Public API pada fase 1 dibuat sangat terbatas.
2. Bila tidak diperlukan, frontend OPAC tetap bisa memakai route web biasa.

## 7. Standar Format Response

Semua response JSON resmi harus konsisten.

### 7.1 Response Sukses Data Tunggal

Format:

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {},
  "meta": null,
  "errors": null
}
````

### 7.2 Response Sukses List

Format:

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 10,
    "total": 100
  },
  "errors": null
}
```

### 7.3 Response Gagal Validasi

Format:

```json
{
  "success": false,
  "message": "Validasi gagal.",
  "data": null,
  "meta": null,
  "errors": {
    "field_name": [
      "Pesan error"
    ]
  }
}
```

### 7.4 Response Gagal Otorisasi

Format:

```json
{
  "success": false,
  "message": "Anda tidak memiliki akses.",
  "data": null,
  "meta": null,
  "errors": null
}
```

### 7.5 Response Gagal Tidak Ditemukan

Format:

```json
{
  "success": false,
  "message": "Data tidak ditemukan.",
  "data": null,
  "meta": null,
  "errors": null
}
```

### 7.6 Response Gagal Proses Bisnis

Format:

```json
{
  "success": false,
  "message": "Proses tidak dapat dilanjutkan.",
  "data": null,
  "meta": null,
  "errors": {
    "business_rule": [
      "Penjelasan singkat"
    ]
  }
}
```

## 8. Standar Format Request

Aturan umum request API:

1. Gunakan JSON untuk payload write
2. Gunakan query string untuk filter read
3. Gunakan `multipart/form-data` untuk upload file
4. Gunakan snake_case untuk nama field
5. Gunakan id numerik untuk relasi fase 1

## 9. Standar HTTP Method

1. GET untuk fetch data
2. POST untuk create atau aksi proses baru
3. PUT untuk update penuh
4. PATCH untuk update parsial atau perubahan status
5. DELETE untuk hapus

## 10. Standar Header

Header umum:

1. `Accept: application/json`
2. `Content-Type: application/json`, kecuali upload file
3. Session auth atau mekanisme auth internal Laravel

Untuk public endpoint terbatas:

1. `Accept: application/json`

## 11. Standar Status Code

Gunakan:

1. 200 untuk sukses baca
2. 201 untuk create sukses
3. 202 untuk proses diterima async bila dipakai
4. 400 untuk request tidak valid umum
5. 401 untuk belum login
6. 403 untuk tidak berwenang
7. 404 untuk data tidak ditemukan
8. 422 untuk validasi gagal
9. 429 untuk rate limit
10. 500 untuk error tak terduga

## 12. Standar Pagination

Semua endpoint list yang besar harus mendukung:

1. `page`
2. `per_page`

Nilai `per_page` resmi:

1. 10
2. 25
3. 50
4. 100

Meta standar:

```json
{
  "page": 1,
  "per_page": 10,
  "total": 245
}
```

## 13. Standar Filter dan Search

Standar:

1. `keyword` untuk pencarian umum internal
2. `q` untuk OPAC publik
3. Filter enum harus mengikuti validation rules
4. Filter foreign key berbasis id
5. Semua query difilter whitelist, bukan field bebas

## 14. Aturan Authorization API

Setiap endpoint internal wajib:

1. Melewati autentikasi session atau guard resmi
2. Melewati permission
3. Melewati policy jika menyentuh resource tertentu

Endpoint publik terbatas:

1. Hanya untuk data publik
2. Tidak boleh mengembalikan file privat
3. Tidak boleh mengembalikan metadata internal

## 15. Aturan Versioning

Fase 1:

1. Belum wajib versioning publik penuh
2. Secara internal gunakan namespace persiapan `v1`

Rekomendasi prefix:

1. `/api/internal/v1/...`
2. `/api/public/v1/...`

Catatan:

1. Route dapat memakai `/api/internal/...` dahulu bila tim ingin sederhana
2. Namun blueprint ini merekomendasikan `v1` agar siap tumbuh

## 16. Struktur Namespace API yang Disarankan

```text
app/
  Modules/
    Api/
      Internal/
        V1/
          Controllers/
      Public/
        V1/
          Controllers/
```

Atau tetap per modul:

```text
app/
  Modules/
    Catalog/
      Http/
        Controllers/
          Api/
            Internal/
            Public/
```

Aturan:

1. Pilih satu pola saja
2. Jangan campur pola sembarangan
3. Disarankan tetap per modul agar konsisten dengan arsitektur modular

## 17. Daftar Endpoint API Resmi Fase 1

### 17.1 Internal Lookup and Search

1. Lookup users
2. Lookup roles
3. Lookup authors
4. Lookup publishers
5. Lookup languages
6. Lookup classifications
7. Lookup subjects
8. Lookup collection types
9. Lookup rack locations
10. Lookup faculties
11. Lookup study programs
12. Lookup item conditions
13. Lookup bibliographic records
14. Lookup members
15. Lookup physical items by barcode
16. Lookup digital assets

### 17.2 Internal Operational

1. Fetch member loan eligibility summary
2. Fetch physical item availability summary
3. Fetch loan summary by item
4. Fetch dashboard counters ringan
5. Run OCR dispatch
6. Run reindex dispatch

### 17.3 Public OPAC API Terbatas

1. Public OPAC search suggestion
2. Public record summary
3. Public asset preview metadata

## 18. Internal API, Lookup Users

### Endpoint

`GET /api/internal/v1/users/lookup`

### Tujuan

Menyediakan daftar user ringkas untuk komponen admin tertentu.

### Permission

1. `users.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

### Response Sukses

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": [
    {
      "id": 1,
      "name": "Super Admin PERPUSQU",
      "username": "superadmin",
      "email": "superadmin@perpusqu.local",
      "is_active": true
    }
  ],
  "meta": {
    "limit": 10
  },
  "errors": null
}
```

## 19. Internal API, Lookup Roles

### Endpoint

`GET /api/internal/v1/roles/lookup`

### Permission

1. `roles.view`

### Query Parameter

1. `keyword` => optional|string|max:100
2. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 1,
    "name": "Super Admin"
  }
]
```

## 20. Internal API, Lookup Authors

### Endpoint

`GET /api/internal/v1/authors/lookup`

### Permission

1. `authors.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 12,
    "name": "John Doe"
  }
]
```

## 21. Internal API, Lookup Publishers

### Endpoint

`GET /api/internal/v1/publishers/lookup`

### Permission

1. `publishers.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 22. Internal API, Lookup Languages

### Endpoint

`GET /api/internal/v1/languages/lookup`

### Permission

1. `languages.view`

### Query Parameter

1. `keyword` => optional|string|max:100
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 23. Internal API, Lookup Classifications

### Endpoint

`GET /api/internal/v1/classifications/lookup`

### Permission

1. `classifications.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `parent_id` => optional|integer|exists:classifications,id
3. `is_active` => optional|boolean
4. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 5,
    "code": "300",
    "name": "Ilmu Sosial",
    "parent_id": null
  }
]
```

## 24. Internal API, Lookup Subjects

### Endpoint

`GET /api/internal/v1/subjects/lookup`

### Permission

1. `subjects.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 25. Internal API, Lookup Collection Types

### Endpoint

`GET /api/internal/v1/collection-types/lookup`

### Permission

1. `collection_types.view`

### Query Parameter

1. `keyword` => optional|string|max:100
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 26. Internal API, Lookup Rack Locations

### Endpoint

`GET /api/internal/v1/rack-locations/lookup`

### Permission

1. `rack_locations.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 27. Internal API, Lookup Faculties

### Endpoint

`GET /api/internal/v1/faculties/lookup`

### Permission

1. `faculties.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 28. Internal API, Lookup Study Programs

### Endpoint

`GET /api/internal/v1/study-programs/lookup`

### Permission

1. `study_programs.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `faculty_id` => optional|integer|exists:faculties,id
3. `is_active` => optional|boolean
4. `limit` => optional|integer|in:5,10,20,50

## 29. Internal API, Lookup Item Conditions

### Endpoint

`GET /api/internal/v1/item-conditions/lookup`

### Permission

1. `item_conditions.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `is_active` => optional|boolean
3. `limit` => optional|integer|in:5,10,20,50

## 30. Internal API, Lookup Bibliographic Records

### Endpoint

`GET /api/internal/v1/bibliographic-records/lookup`

### Tujuan

Dipakai untuk:

1. Form tambah item
2. Form unggah aset digital
3. Komponen pencarian internal record

### Permission

1. `catalog.view`

### Query Parameter

1. `keyword` => optional|string|max:255
2. `collection_type_id` => optional|integer|exists:collection_types,id
3. `publication_status` => optional|string|in:draft,published,unpublished,archived
4. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 101,
    "title": "Administrasi Publik Modern",
    "publication_year": 2024,
    "collection_type": {
      "id": 1,
      "name": "Buku"
    }
  }
]
```

## 31. Internal API, Lookup Members

### Endpoint

`GET /api/internal/v1/members/lookup`

### Tujuan

Dipakai untuk:

1. Halaman peminjaman
2. Halaman laporan
3. Pencarian anggota cepat

### Permission

1. `members.view`
   atau
2. `circulation.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `member_type` => optional|string|in:student,lecturer,staff,alumni,guest
3. `is_active` => optional|boolean
4. `is_blocked` => optional|boolean
5. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 55,
    "member_number": "AGT-2026-0001",
    "name": "Ahmad",
    "member_type": "student",
    "is_active": true,
    "is_blocked": false
  }
]
```

## 32. Internal API, Lookup Physical Items by Barcode or Keyword

### Endpoint

`GET /api/internal/v1/physical-items/lookup`

### Permission

1. `collections.view`
   atau
2. `circulation.view`

### Query Parameter

1. `keyword` => optional|string|max:150
2. `item_status` => optional|string|in:available,loaned,damaged,lost,repair,inactive
3. `limit` => optional|integer|in:5,10,20,50

### Response Data Shape

```json
[
  {
    "id": 999,
    "barcode": "BC-00001",
    "inventory_code": "INV-2026-001",
    "item_status": "available",
    "record": {
      "id": 101,
      "title": "Administrasi Publik Modern"
    }
  }
]
```

## 33. Internal API, Lookup Digital Assets

### Endpoint

`GET /api/internal/v1/digital-assets/lookup`

### Permission

1. `digital_assets.view`

### Query Parameter

1. `keyword` => optional|string|max:255
2. `bibliographic_record_id` => optional|integer|exists:bibliographic_records,id
3. `asset_type` => optional|string|in:ebook,thesis,dissertation,journal_article,module,scanned_book,supplementary,other
4. `publication_status` => optional|string|in:draft,published,unpublished,archived
5. `limit` => optional|integer|in:5,10,20,50

## 34. Internal API, Member Loan Eligibility Summary

### Endpoint

`GET /api/internal/v1/members/{member}/loan-eligibility`

### Tujuan

Dipakai pada halaman peminjaman untuk menampilkan ringkasan cepat kelayakan anggota.

### Permission

1. `circulation.view`
   atau
2. `members.view`

### Response Sukses

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {
    "member_id": 55,
    "member_number": "AGT-2026-0001",
    "name": "Ahmad",
    "member_type": "student",
    "is_active": true,
    "is_blocked": false,
    "active_loan_count": 1,
    "max_active_loans": 3,
    "eligible_for_loan": true,
    "reason": null
  },
  "meta": null,
  "errors": null
}
```

### Catatan

1. Penentuan `eligible_for_loan` berasal dari service layer.
2. Endpoint ini tidak menggantikan validasi final saat create loan.

## 35. Internal API, Physical Item Availability Summary

### Endpoint

`GET /api/internal/v1/physical-items/{item}/availability`

### Tujuan

Menampilkan ringkasan status item pada form atau halaman dinamis.

### Permission

1. `collections.view`
   atau
2. `circulation.view`

### Response Data Shape

```json
{
  "id": 999,
  "barcode": "BC-00001",
  "item_status": "available",
  "is_available": true,
  "record": {
    "id": 101,
    "title": "Administrasi Publik Modern"
  }
}
```

## 36. Internal API, Loan Summary by Item

### Endpoint

`GET /api/internal/v1/physical-items/{item}/active-loan`

### Tujuan

Menyediakan ringkasan pinjaman aktif pada item tertentu untuk halaman pengembalian atau pengecekan status.

### Permission

1. `circulation.view`

### Response Bila Ada Loan Aktif

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {
    "loan_id": 5001,
    "member": {
      "id": 55,
      "member_number": "AGT-2026-0001",
      "name": "Ahmad"
    },
    "loan_date": "2026-04-01 08:00:00",
    "due_date": "2026-04-08 08:00:00",
    "loan_status": "active",
    "is_overdue": false
  },
  "meta": null,
  "errors": null
}
```

### Response Bila Tidak Ada Loan Aktif

```json
{
  "success": true,
  "message": "Tidak ada pinjaman aktif.",
  "data": null,
  "meta": null,
  "errors": null
}
```

## 37. Internal API, Dashboard Counters Ringan

### Endpoint

`GET /api/internal/v1/dashboard/counters`

### Tujuan

Dipakai widget ringan dashboard internal bila pendekatan komponen AJAX dipilih.

### Permission

1. `core.view_dashboard`

### Response Data Shape

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {
    "total_records": 1500,
    "total_items": 3200,
    "total_members": 450,
    "active_loans": 38,
    "digital_assets": 670
  },
  "meta": null,
  "errors": null
}
```

### Catatan

1. Output dapat berbeda حسب role.
2. Service harus menyaring data sesuai role bila perlu.

## 38. Internal API, Dispatch OCR

### Endpoint

`POST /api/internal/v1/digital-assets/{asset}/ocr`

### Tujuan

Menjalankan dispatch OCR secara terkontrol dari UI dinamis.

### Permission

1. `digital_assets.run_ocr`

### Request Body

```json
{
  "mode": "queue"
}
```

### Validasi

1. `mode` => optional|string|in:queue,immediate

### Response Sukses

```json
{
  "success": true,
  "message": "Proses OCR berhasil dijadwalkan.",
  "data": {
    "asset_id": 300,
    "ocr_status": "queued"
  },
  "meta": null,
  "errors": null
}
```

### Catatan

1. Mode `immediate` opsional dan hanya untuk kondisi tertentu.
2. Service tetap memutuskan mode final.

## 39. Internal API, Dispatch Reindex

### Endpoint

`POST /api/internal/v1/digital-assets/{asset}/reindex`

### Permission

1. `digital_assets.reindex`

### Response Data Shape

```json
{
  "success": true,
  "message": "Proses reindex berhasil dijadwalkan.",
  "data": {
    "asset_id": 300,
    "index_status": "queued"
  },
  "meta": null,
  "errors": null
}
```

## 40. Public API, OPAC Search Suggestion

### Endpoint

`GET /api/public/v1/opac/suggestions`

### Status

Opsional fase 1, aktif hanya bila autocomplete dipakai

### Tujuan

Memberikan saran kata atau record publik ringan.

### Query Parameter

1. `q` => required|string|min:2|max:100
2. `limit` => optional|integer|in:5,10

### Response Data Shape

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": [
    {
      "record_id": 101,
      "title": "Administrasi Publik Modern",
      "slug": "administrasi-publik-modern"
    }
  ],
  "meta": {
    "limit": 5
  },
  "errors": null
}
```

### Guard Rule

1. Hanya record publik
2. Rate limit wajib
3. Tidak menampilkan metadata internal

## 41. Public API, Record Summary

### Endpoint

`GET /api/public/v1/opac/records/{record}`

### Status

Opsional fase 1, aktif bila komponen publik memerlukan JSON khusus

### Tujuan

Mengambil ringkasan detail koleksi publik dalam JSON.

### Response Data Shape

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {
    "id": 101,
    "title": "Administrasi Publik Modern",
    "slug": "administrasi-publik-modern",
    "publication_year": 2024,
    "authors": [
      {
        "id": 12,
        "name": "John Doe"
      }
    ],
    "collection_type": {
      "id": 1,
      "name": "Buku"
    },
    "physical_availability": {
      "total_items": 3,
      "available_items": 1
    },
    "digital_assets": [
      {
        "id": 300,
        "asset_type": "ebook",
        "can_preview": true
      }
    ]
  },
  "meta": null,
  "errors": null
}
```

### Guard Rule

1. Record harus PUBLIC_VISIBLE
2. Aset digital hanya tampil dalam bentuk yang sah untuk publik

## 42. Public API, Asset Preview Metadata

### Endpoint

`GET /api/public/v1/opac/assets/{asset}/metadata`

### Status

Opsional fase 1

### Tujuan

Mengambil metadata preview publik secara aman sebelum viewer dibuka.

### Response Data Shape

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": {
    "id": 300,
    "title": "Administrasi Publik Modern",
    "asset_type": "ebook",
    "can_preview": true
  },
  "meta": null,
  "errors": null
}
```

### Guard Rule

1. Hanya aset yang PUBLIC_ACCESSIBLE
2. Tidak mengembalikan file_path mentah

## 43. Standar Error Code Konseptual

Walau detail final ada pada dokumen error code terpisah, API contract ini merekomendasikan kode internal berikut:

1. `AUTH_401_UNAUTHENTICATED`
2. `AUTH_403_FORBIDDEN`
3. `VAL_422_INVALID_INPUT`
4. `RES_404_NOT_FOUND`
5. `BUS_409_RULE_VIOLATION`
6. `SYS_500_UNEXPECTED_ERROR`

Contoh:

```json
{
  "success": false,
  "message": "Proses tidak dapat dilanjutkan.",
  "data": null,
  "meta": null,
  "errors": {
    "code": [
      "BUS_409_RULE_VIOLATION"
    ]
  }
}
```

## 44. Standar Payload Ringan

Payload API lookup harus tetap ringan.

Aturan:

1. Jangan kirim field besar yang tidak perlu
2. Jangan kirim abstract panjang pada lookup
3. Jangan kirim metadata_json penuh pada lookup
4. Jangan kirim rule akses detail pada endpoint publik

## 45. Standar Upload Endpoint

Untuk fase 1, upload file utama tetap direkomendasikan lewat form web biasa.

Jika upload API dipakai:

1. Harus `multipart/form-data`
2. Harus melalui endpoint internal authenticated
3. Harus divalidasi file type dan size
4. Harus mengembalikan payload metadata aman

Fase 1:

1. Endpoint upload API umum belum wajib
2. Upload lewat web form dan controller biasa tetap standar utama

## 46. Standar Rate Limit

Rate limit disarankan untuk:

1. Public OPAC suggestion
2. Public record JSON bila aktif
3. Public asset metadata

Rate limit awal disarankan:

1. 60 request per menit per IP untuk endpoint publik ringan
2. Internal authenticated API mengikuti rate limit lebih longgar bila diperlukan

## 47. Standar Caching

Caching boleh dipakai untuk:

1. Lookup master data aktif
2. Public suggestion ringan
3. Dashboard counters ringan

Aturan:

1. Cache tidak boleh mengabaikan permission
2. Cache harus mudah diinvalidasi saat master data berubah
3. Cache publik hanya untuk data publik

## 48. Standar Logging

Semua endpoint API sensitif harus mendukung logging pada:

1. akses ditolak
2. validasi gagal penting
3. proses OCR dispatch
4. proses reindex dispatch
5. queue retry
6. akses asset metadata sensitif internal

Catatan:

1. Logging rinci utama tetap mengikuti audit log spec
2. Tidak semua read endpoint perlu masuk audit detail

## 49. Standar Controller API

Setiap endpoint API harus ditangani oleh controller API khusus atau method khusus yang jelas.

Contoh controller yang disarankan:

1. InternalLookupController
2. MemberEligibilityApiController
3. PhysicalItemStatusApiController
4. DashboardApiController
5. DigitalAssetProcessingApiController
6. PublicOpacApiController

Aturan:

1. Jangan campur response HTML dan JSON dalam method yang sama
2. Controller API harus mengembalikan JSON konsisten
3. Controller API tetap tipis dan memakai service layer

## 50. Standar Service untuk API

Endpoint API wajib memakai service yang sudah didefinisikan di 12_SERVICE_LAYER.md.

Mapping inti:

1. users lookup -> UserManagementService
2. role lookup -> RoleManagementService
3. author lookup -> AuthorService
4. record lookup -> BibliographicRecordService
5. member lookup -> MemberService
6. member eligibility -> MemberStatusService, MemberBlockingService, LoanTransactionService
7. item availability -> PhysicalItemService, ActiveLoanService
8. OCR dispatch -> OcrProcessingService
9. reindex dispatch -> SearchIndexService
10. public search suggestion -> CatalogSearchService
11. public record summary -> OpacRecordDetailService
12. asset preview metadata -> DigitalAssetAccessService

## 51. Standar Naming Route API

Penamaan route API disarankan:

Internal:

1. `api.internal.v1.users.lookup`
2. `api.internal.v1.roles.lookup`
3. `api.internal.v1.members.lookup`
4. `api.internal.v1.members.loan_eligibility`
5. `api.internal.v1.physical_items.lookup`
6. `api.internal.v1.digital_assets.ocr`
7. `api.internal.v1.digital_assets.reindex`

Public:

1. `api.public.v1.opac.suggestions`
2. `api.public.v1.opac.records.show`
3. `api.public.v1.opac.assets.metadata`

## 52. Matriks Endpoint ke Permission

| Endpoint                                                | Permission Minimum                   |
| ------------------------------------------------------- | ------------------------------------ |
| GET /api/internal/v1/users/lookup                       | users.view                           |
| GET /api/internal/v1/roles/lookup                       | roles.view                           |
| GET /api/internal/v1/authors/lookup                     | authors.view                         |
| GET /api/internal/v1/publishers/lookup                  | publishers.view                      |
| GET /api/internal/v1/languages/lookup                   | languages.view                       |
| GET /api/internal/v1/classifications/lookup             | classifications.view                 |
| GET /api/internal/v1/subjects/lookup                    | subjects.view                        |
| GET /api/internal/v1/collection-types/lookup            | collection_types.view                |
| GET /api/internal/v1/rack-locations/lookup              | rack_locations.view                  |
| GET /api/internal/v1/faculties/lookup                   | faculties.view                       |
| GET /api/internal/v1/study-programs/lookup              | study_programs.view                  |
| GET /api/internal/v1/item-conditions/lookup             | item_conditions.view                 |
| GET /api/internal/v1/bibliographic-records/lookup       | catalog.view                         |
| GET /api/internal/v1/members/lookup                     | members.view or circulation.view     |
| GET /api/internal/v1/physical-items/lookup              | collections.view or circulation.view |
| GET /api/internal/v1/digital-assets/lookup              | digital_assets.view                  |
| GET /api/internal/v1/members/{member}/loan-eligibility  | circulation.view or members.view     |
| GET /api/internal/v1/physical-items/{item}/availability | collections.view or circulation.view |
| GET /api/internal/v1/physical-items/{item}/active-loan  | circulation.view                     |
| GET /api/internal/v1/dashboard/counters                 | core.view_dashboard                  |
| POST /api/internal/v1/digital-assets/{asset}/ocr        | digital_assets.run_ocr               |
| POST /api/internal/v1/digital-assets/{asset}/reindex    | digital_assets.reindex               |
| GET /api/public/v1/opac/suggestions                     | public                               |
| GET /api/public/v1/opac/records/{record}                | public                               |
| GET /api/public/v1/opac/assets/{asset}/metadata         | public                               |

## 53. Matriks Endpoint ke View atau Komponen Pemakai

| Endpoint                     | Dipakai Oleh                                         |
| ---------------------------- | ---------------------------------------------------- |
| users lookup                 | halaman manajemen akses tertentu                     |
| roles lookup                 | form user dan role assignment                        |
| authors lookup               | form katalog                                         |
| publishers lookup            | form katalog                                         |
| classifications lookup       | form katalog                                         |
| subjects lookup              | form katalog                                         |
| bibliographic-records lookup | form item dan form aset digital                      |
| members lookup               | halaman peminjaman                                   |
| member loan eligibility      | halaman peminjaman                                   |
| physical-items lookup        | pengembalian dan pengecekan item                     |
| item active-loan             | halaman pengembalian                                 |
| dashboard counters           | dashboard admin                                      |
| OCR dispatch                 | detail aset digital                                  |
| reindex dispatch             | detail aset digital                                  |
| public suggestions           | beranda OPAC atau search box jika autocomplete aktif |
| public record summary        | komponen OPAC dinamis jika dipakai                   |
| public asset metadata        | halaman preview publik jika dipakai                  |

## 54. Data yang Dilarang Keluar dari API Publik

API publik tidak boleh mengembalikan:

1. file_path mentah storage
2. checksum
3. uploaded_by internal
4. OCR error detail internal
5. access rule internal penuh
6. audit log
7. user internal
8. member internal yang tidak relevan
9. path cover atau file yang tidak aman tanpa URL terproteksi

## 55. Data yang Harus Disaring pada API Internal

API internal tetap harus menyaring:

1. password
2. remember_token
3. old_values dan new_values audit yang tidak relevan
4. path storage yang tidak perlu
5. metadata_json besar pada lookup ringan
6. field teknis internal queue bila tidak diperlukan

## 56. Aturan Contract Stability

1. Nama field response tidak berubah sembarangan
2. Penambahan field baru harus backward compatible
3. Hapus field hanya boleh melalui revisi blueprint dan migrasi kontrak
4. Client internal tidak boleh bergantung pada field liar di luar kontrak ini

## 57. Aturan Integrasi dengan Frontend

1. Frontend tetap mengutamakan Blade dan Livewire
2. API dipakai hanya bila interaksi dinamis lebih tepat memakai JSON
3. Jangan membuat frontend hybrid yang kacau
4. Setiap penggunaan API harus punya alasan UX atau performa yang jelas

## 58. Endpoint yang Tidak Boleh Ada pada Fase 1

Endpoint berikut tidak boleh dibuat pada fase 1:

1. POST /api/public/v1/login
2. POST /api/public/v1/register
3. POST /api/public/v1/checkout
4. POST /api/public/v1/payments
5. GET /api/public/v1/private-assets
6. POST /api/internal/v1/rfid/*
7. POST /api/internal/v1/acquisition/*
8. GET /api/public/v1/full-export/*
9. API CRUD penuh untuk semua resource publik

## 59. Prioritas Implementasi API

### Prioritas P1

1. bibliographic-records lookup
2. members lookup
3. member loan eligibility
4. physical-items lookup
5. item active-loan
6. authors lookup
7. subjects lookup
8. OCR dispatch
9. reindex dispatch

### Prioritas P2

1. dashboard counters
2. publishers lookup
3. classifications lookup
4. study programs lookup
5. digital assets lookup

### Prioritas P3

1. public suggestions
2. public record summary
3. public asset metadata

## 60. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 21_SEARCH_INDEXING_SPEC.md
2. 22_STORAGE_FILE_POLICY.md
3. 23_OCR_AND_DIGITAL_PROCESSING.md
4. 25_REPORTING_SPEC.md
5. 28_SECURITY_POLICY.md
6. 29_AUDIT_LOG_SPEC.md
7. 30_ERROR_CODE.md
8. 31_TEST_PLAN.md
9. 32_TEST_SCENARIO.md
10. 38_TREE.md
11. 39_TRACEABILITY_MATRIX.md
12. 41_BACKEND_CHECKLIST.md
13. 42_FRONTEND_CHECKLIST.md

Aturan:

1. Search indexing spec harus konsisten dengan endpoint search related
2. Storage policy harus menjaga asset metadata API tetap aman
3. OCR spec harus menjabarkan endpoint OCR dispatch ini
4. Security policy harus memuat auth, rate limit, filtering, dan data exposure
5. Error code document harus merinci code konseptual dari kontrak ini

## 61. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua endpoint punya tujuan jelas
2. Semua endpoint punya permission atau status public yang jelas
3. Semua payload konsisten
4. Tidak ada endpoint liar di luar ruang lingkup fase 1
5. Semua endpoint memakai service layer resmi
6. Tidak ada data sensitif bocor ke API publik
7. API tetap bersifat pendukung, bukan menggantikan arsitektur web utama

## 62. Kesimpulan

Dokumen API Contract ini menetapkan spesifikasi endpoint JSON resmi PERPUSQU secara minimal, aman, dan konsisten dengan arsitektur monolith modular yang telah disepakati. Dokumen ini memastikan kebutuhan lookup, komponen dinamis, operasi OCR dan reindex, serta kemungkinan OPAC publik ringan memiliki kontrak yang jelas tanpa mengubah karakter utama sistem sebagai aplikasi web berbasis Laravel, Blade, Livewire, dan Bootstrap. Semua implementasi endpoint API PERPUSQU wajib merujuk dokumen ini.

END OF 20_API_CONTRACT.md
