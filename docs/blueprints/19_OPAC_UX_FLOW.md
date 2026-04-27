# 19_OPAC_UX_FLOW.md

## 1. Nama Dokumen

OPAC UX Flow Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint alur pengalaman pengguna OPAC publik

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan alur navigasi OPAC, search journey, discovery flow, detail flow, preview flow, empty state, access state, dan interaksi publik

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan alur pengalaman pengguna untuk area OPAC publik PERPUSQU, mulai dari beranda, pencarian, filter, detail koleksi, hingga preview aset digital. Dokumen ini menjadi acuan wajib agar OPAC publik memiliki alur yang jelas, cepat dipahami, aman, dan konsisten dengan blueprint sebelumnya, terutama Menu Map, Route Map, View Map, State Machine, Validation Rules, dan UI UX Standard.

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

Aturan wajib:

1. OPAC hanya menampilkan data publik yang sah.
2. OPAC tidak boleh membocorkan data internal atau file privat.
3. Alur pencarian harus sederhana dan fokus pada penemuan koleksi.
4. Semua interaksi OPAC harus konsisten dengan route dan view resmi.
5. Tombol preview publik hanya boleh muncul bila state akses mengizinkan.
6. OPAC harus tetap ringan dan cepat dipahami oleh pengguna umum.

## 4. Definisi OPAC dalam PERPUSQU

OPAC adalah Online Public Access Catalog publik yang menjadi pintu masuk utama bagi pengguna untuk:

1. Mencari bibliographic record
2. Melihat metadata koleksi
3. Mengetahui ketersediaan item fisik
4. Mengetahui ketersediaan aset digital
5. Melakukan preview aset digital publik bila diizinkan

OPAC pada PERPUSQU bersifat hibrid, artinya:

1. Data koleksi fisik masuk ke database dan tampil pada pencarian
2. Data koleksi digital masuk ke database dan tampil pada pencarian
3. Satu record dapat memiliki item fisik, aset digital, atau keduanya

## 5. Sasaran Pengguna OPAC

Pengguna utama OPAC:

1. Mahasiswa
2. Dosen
3. Tenaga Kependidikan
4. Tamu perpustakaan
5. Pengguna umum yang diizinkan melihat katalog publik

Karakter pengguna:

1. Tidak semuanya memahami istilah teknis perpustakaan
2. Lebih suka pencarian sederhana
3. Ingin cepat mengetahui ada atau tidaknya koleksi
4. Ingin tahu apakah koleksi tersedia fisik, digital, atau keduanya
5. Tidak ingin proses pencarian yang rumit

## 6. Prinsip UX OPAC

Prinsip UX OPAC PERPUSQU adalah:

1. Search first
2. Sederhana
3. Cepat dipahami
4. Fokus pada hasil
5. Minim langkah
6. Jelas membedakan koleksi fisik dan digital
7. Aman dari kebocoran akses
8. Stabil pada berbagai ukuran layar

## 7. Tujuan UX OPAC

Sasaran pengalaman pengguna OPAC adalah:

1. Pengguna dapat memulai pencarian kurang dari 5 detik setelah halaman terbuka
2. Pengguna dapat memahami hasil pencarian tanpa membaca petunjuk panjang
3. Pengguna dapat mengetahui lokasi fisik atau akses digital dengan cepat
4. Pengguna dapat berpindah dari hasil pencarian ke detail koleksi dengan satu klik
5. Pengguna dapat membuka preview PDF publik tanpa kebingungan

## 8. Struktur Halaman OPAC

Area OPAC resmi terdiri dari:

1. Beranda OPAC
2. Hasil Pencarian OPAC
3. Detail Koleksi OPAC
4. Preview Aset Publik
5. Tentang Perpustakaan
6. Bantuan Pencarian

Mapping view:

1. `modules/opac/home.blade.php`
2. `modules/opac/search/index.blade.php`
3. `modules/opac/records/show.blade.php`
4. `modules/opac/assets/preview.blade.php`
5. `modules/opac/about.blade.php`
6. `modules/opac/help.blade.php`

Mapping route:

1. `opac.home`
2. `opac.search.index`
3. `opac.records.show`
4. `opac.assets.preview`
5. `opac.about`
6. `opac.help`

## 9. Entry Point OPAC

Entry point resmi pengguna adalah:

1. Beranda OPAC
2. Halaman pencarian OPAC
3. Detail koleksi dari tautan langsung yang valid
4. Preview aset publik dari detail koleksi yang sah

Prioritas utama:

1. Beranda OPAC sebagai pintu masuk utama
2. Search bar sebagai elemen pertama yang menarik perhatian

## 10. Peta Perjalanan Pengguna Utama

### 10.1 Journey A, Pencarian Umum

Alur:

1. Pengguna membuka beranda OPAC
2. Pengguna mengetik kata kunci
3. Pengguna menekan cari
4. Sistem menampilkan hasil pencarian
5. Pengguna membaca ringkasan hasil
6. Pengguna membuka detail koleksi
7. Pengguna melihat ketersediaan fisik dan digital

### 10.2 Journey B, Filter Pencarian

Alur:

1. Pengguna membuka hasil pencarian
2. Pengguna menambahkan filter
3. Sistem memperbarui hasil
4. Pengguna mempersempit hasil
5. Pengguna membuka detail koleksi

### 10.3 Journey C, Cek Ketersediaan Fisik

Alur:

1. Pengguna mencari judul
2. Pengguna membuka detail koleksi
3. Pengguna melihat jumlah item fisik
4. Pengguna melihat status item dan lokasi rak
5. Pengguna memutuskan datang ke perpustakaan

### 10.4 Journey D, Akses Preview Digital

Alur:

1. Pengguna mencari judul
2. Pengguna membuka detail koleksi
3. Pengguna melihat bahwa aset digital tersedia
4. Pengguna menekan tombol preview
5. Sistem memeriksa rule akses
6. Sistem menampilkan preview PDF publik bila diizinkan

### 10.5 Journey E, Pencarian Gagal

Alur:

1. Pengguna memasukkan kata kunci
2. Hasil kosong
3. Sistem menampilkan empty state
4. Sistem memberi saran pencarian lain
5. Pengguna memperbaiki query atau memakai filter

## 11. Flow 1, Beranda OPAC

### 11.1 Tujuan Halaman

Tujuan utama beranda adalah:

1. Menjadi pintu masuk pencarian
2. Menjelaskan singkat fungsi sistem
3. Mengarahkan pengguna ke hasil pencarian

### 11.2 Komponen Wajib

1. Header publik
2. Hero search besar
3. Placeholder pencarian yang jelas
4. Tombol cari
5. Shortcut kategori bila diaktifkan
6. Informasi singkat perpustakaan
7. Link ke bantuan

### 11.3 Alur Interaksi

1. Halaman dimuat
2. Fokus visual utama ada di search bar
3. Pengguna mengetik query
4. Pengguna menekan Enter atau tombol cari
5. Sistem mengarahkan ke hasil pencarian dengan parameter query

### 11.4 Aturan UX

1. Search bar harus langsung terlihat tanpa scroll di desktop
2. Placeholder harus mudah dipahami
3. Tombol cari harus jelas
4. Beranda tidak boleh terlalu penuh konten

### 11.5 Contoh Placeholder

1. Cari judul, pengarang, subjek, atau ISBN
2. Cari buku, skripsi, tesis, atau jurnal

## 12. Flow 2, Hasil Pencarian OPAC

### 12.1 Tujuan Halaman

1. Menampilkan hasil pencarian yang relevan
2. Menyediakan filter pencarian
3. Mengarahkan ke detail koleksi

### 12.2 Input Utama

Berdasarkan `OpacSearchRequest`, parameter yang sah:

1. `q`
2. `collection_type_id`
3. `language_id`
4. `publication_year`
5. `page`
6. `per_page`

### 12.3 Komponen Wajib

1. Search bar tetap terlihat
2. Filter bar
3. Informasi jumlah hasil
4. Daftar hasil
5. Pagination
6. Empty state bila tidak ada hasil

### 12.4 Struktur Card Hasil

Setiap hasil minimal menampilkan:

1. Cover kecil bila ada
2. Judul
3. Pengarang utama atau ringkasan pengarang
4. Tahun terbit
5. Jenis koleksi
6. Badge ketersediaan fisik
7. Badge ketersediaan digital
8. Tombol atau area klik ke detail

### 12.5 Aturan Urutan Informasi

Urutan prioritas visual:

1. Judul
2. Pengarang
3. Jenis koleksi
4. Tahun
5. Ketersediaan
6. Aksi detail

### 12.6 Aturan Filter

Filter yang diizinkan:

1. Jenis koleksi
2. Bahasa
3. Tahun

Aturan:

1. Filter tidak boleh menutupi hasil
2. Filter harus mempertahankan query sebelumnya
3. Reset filter harus mudah dilakukan

### 12.7 Aturan Search Refinement

Pengguna harus dapat:

1. Mengubah query tanpa kembali ke beranda
2. Menambah atau menghapus filter
3. Melihat hasil baru dengan jelas
4. Melihat jumlah hasil setelah filter

### 12.8 Empty State Hasil Pencarian

Bila hasil kosong, tampilkan:

1. Pesan bahwa koleksi tidak ditemukan
2. Saran memperluas kata kunci
3. Saran menghapus filter
4. Tombol kembali ke pencarian umum

Contoh pesan:

1. Koleksi yang Anda cari belum ditemukan.
2. Coba kata kunci lain atau ubah filter pencarian.

## 13. Flow 3, Detail Koleksi OPAC

### 13.1 Tujuan Halaman

1. Menampilkan metadata koleksi secara lengkap
2. Menampilkan ketersediaan item fisik
3. Menampilkan ketersediaan aset digital
4. Menjadi titik keputusan pengguna

### 13.2 Komponen Wajib

1. Cover
2. Judul
3. Pengarang
4. Penerbit
5. Tahun
6. Bahasa
7. Klasifikasi
8. Jenis koleksi
9. Abstrak atau ringkasan bila ada
10. Daftar subjek
11. Section ketersediaan fisik
12. Section aset digital
13. Tombol preview publik bila sah

### 13.3 Alur Interaksi

1. Pengguna membuka detail dari hasil pencarian
2. Sistem menampilkan metadata publik
3. Sistem menampilkan status ketersediaan fisik
4. Sistem menampilkan aset digital publik atau restricted info yang sah
5. Pengguna memutuskan:
   1. kembali ke hasil
   2. melihat preview
   3. mencatat lokasi rak

### 13.4 Aturan Ketersediaan Fisik

Yang ditampilkan:

1. Jumlah item fisik
2. Status ringkas item yang layak tampil
3. Lokasi rak atau informasi lokasi
4. Ketersediaan saat ini

Aturan:

1. Jangan tampilkan data internal berlebihan
2. Fokus pada apakah item tersedia atau tidak
3. Bila banyak item, tampilkan ringkasan dan daftar ringkas

### 13.5 Aturan Aset Digital

Yang ditampilkan:

1. Ada atau tidaknya aset digital
2. Tipe aset
3. Status dapat dipreview atau tidak
4. Tombol preview bila diizinkan

Aturan:

1. Aset privat tidak boleh tampil sebagai dapat diakses publik
2. Jika ada embargo aktif, tampilkan informasi wajar tanpa membocorkan akses
3. Jika asset restricted, boleh tampil info terbatas sesuai kebijakan

### 13.6 Teks Bantuan di Detail

Contoh teks yang disarankan:

1. Tersedia 3 eksemplar fisik
2. Saat ini 1 eksemplar tersedia
3. Preview digital tersedia
4. Aset digital tidak tersedia untuk akses publik

## 14. Flow 4, Preview Aset Publik

### 14.1 Tujuan Halaman

1. Menampilkan preview file digital publik dengan aman
2. Memastikan rule akses terpenuhi sebelum file ditampilkan

### 14.2 Komponen Wajib

1. Header ringan
2. Judul aset atau judul koleksi
3. Informasi singkat aset
4. Viewer PDF.js
5. Tombol kembali ke detail koleksi

### 14.3 Alur Interaksi

1. Pengguna menekan tombol preview dari detail koleksi
2. Controller memanggil DigitalAssetAccessService
3. Sistem memeriksa:
   1. publication_status
   2. is_public
   3. embargo
   4. access rules
4. Jika lolos, sistem menampilkan viewer
5. Jika tidak lolos, sistem menampilkan error state yang aman

### 14.4 Aturan UX Preview

1. Viewer harus dominan di halaman
2. Informasi pendukung tidak terlalu banyak
3. Pengguna mudah kembali ke detail koleksi
4. Tidak ada URL storage mentah yang terlihat

### 14.5 Error State Preview

Jika akses ditolak:

1. Tampilkan pesan yang ramah
2. Jangan tampilkan alasan teknis detail
3. Sediakan tombol kembali

Contoh:
Preview file tidak tersedia untuk akses publik.

## 15. Flow 5, Halaman Tentang dan Bantuan

### 15.1 Halaman Tentang

Tujuan:

1. Menjelaskan identitas perpustakaan
2. Menampilkan informasi singkat institusi
3. Menambah kepercayaan pengguna

Konten minimum:

1. Nama institusi
2. Nama perpustakaan
3. Alamat
4. Kontak
5. Profil singkat

### 15.2 Halaman Bantuan

Tujuan:

1. Menjelaskan cara mencari koleksi
2. Menjelaskan arti hasil pencarian
3. Menjelaskan perbedaan fisik dan digital

Konten minimum:

1. Cara memakai search
2. Cara memakai filter
3. Cara membaca status ketersediaan
4. Cara membuka preview publik

## 16. State UX pada OPAC

### 16.1 Idle State

Kondisi:

1. Pengguna baru membuka beranda
2. Belum ada query aktif

Tampilan:

1. Search bar utama
2. Informasi singkat
3. Shortcut kategori bila ada

### 16.2 Searching State

Kondisi:

1. Pengguna mengirim query
2. Sistem memproses hasil

Tampilan:

1. Loading ringan
2. Indikasi pencarian berjalan

### 16.3 Results State

Kondisi:

1. Sistem menemukan satu atau lebih hasil

Tampilan:

1. Result list
2. Filter
3. Pagination
4. Total hasil

### 16.4 Empty State

Kondisi:

1. Tidak ada hasil

Tampilan:

1. Pesan tidak ditemukan
2. Saran perbaikan query
3. Reset filter

### 16.5 Detail State

Kondisi:

1. Pengguna membuka satu record

Tampilan:

1. Metadata detail
2. Ketersediaan fisik
3. Aset digital

### 16.6 Preview State

Kondisi:

1. Pengguna membuka preview file publik

Tampilan:

1. Viewer PDF
2. Informasi singkat
3. Tombol kembali

### 16.7 Access Denied State

Kondisi:

1. Preview atau data yang diminta tidak boleh diakses

Tampilan:

1. Pesan akses tidak tersedia
2. Tombol kembali

## 17. Decision Point Pengguna

OPAC harus membantu pengguna mengambil keputusan di titik berikut:

1. Apakah koleksi relevan dengan yang dicari
2. Apakah koleksi tersedia fisik
3. Apakah koleksi tersedia digital
4. Apakah preview digital bisa dibuka
5. Apakah saya harus mengubah query atau filter

UI harus memperjelas titik keputusan ini.

## 18. Search Behavior UX

### 18.1 Input Query

Aturan:

1. Query boleh kosong di beranda jika sistem menampilkan data default, tetapi fase 1 disarankan memprioritaskan query nyata.
2. Query maksimum 255 karakter.
3. Query yang terlalu panjang harus ditolak dengan validasi aman.

### 18.2 Relevance

Sumber pencarian utama:

1. Judul
2. Pengarang
3. Subjek
4. ISBN
5. Metadata publik
6. Teks aset digital yang sah untuk index publik

### 18.3 Search Feedback

Setelah pencarian:

1. Tampilkan query aktif
2. Tampilkan jumlah hasil
3. Tampilkan filter aktif

Contoh:
Hasil pencarian untuk "administrasi publik"

### 18.4 Search Reset

Pengguna harus mudah:

1. Menghapus query
2. Menghapus filter
3. Kembali ke hasil umum

## 19. Filter Behavior UX

### 19.1 Prinsip Filter

1. Filter harus sedikit tetapi berguna
2. Filter harus langsung relevan
3. Filter harus mudah dihapus

### 19.2 Filter Resmi Fase 1

1. Jenis koleksi
2. Bahasa
3. Tahun

### 19.3 Tampilan Filter Aktif

Filter aktif harus terlihat, misalnya:

1. Jenis: Buku
2. Bahasa: Bahasa Indonesia
3. Tahun: 2024

### 19.4 Reset Filter

Sediakan:

1. Tombol reset
atau
2. Link hapus semua filter

## 20. Rule Tampilan Ketersediaan

### 20.1 Ketersediaan Fisik

Kategori tampilan:

1. Tersedia
2. Sedang dipinjam
3. Tidak tersedia
4. Koleksi fisik tidak ada

### 20.2 Ketersediaan Digital

Kategori tampilan:

1. Preview tersedia
2. Aset digital tersedia tetapi tidak untuk publik
3. Aset digital tidak tersedia

### 20.3 Tampilan Gabungan

Record bisa tampil sebagai:

1. Fisik saja
2. Digital saja
3. Fisik dan digital

UX harus menampilkan kategori ini dengan jelas.

## 21. Guard Rule UX OPAC

UX OPAC wajib mematuhi guard rule berikut:

1. Record hanya boleh tampil di OPAC bila PUBLIC_VISIBLE
2. Tombol preview hanya boleh tampil bila asset PUBLIC_ACCESSIBLE
3. Asset PUBLIC_EMBARGOED tidak boleh menampilkan tombol preview aktif
4. Asset RESTRICTED_BY_RULE tidak boleh tampak terbuka publik tanpa syarat
5. Data internal seperti audit, OCR internal, path file, dan access rule detail tidak boleh ditampilkan

## 22. Error dan Empty State Khusus OPAC

### 22.1 Query Kosong

Jika query kosong pada halaman hasil:

1. Sistem boleh menampilkan hasil umum atau trending bila sudah didukung
2. Fase 1 disarankan menampilkan hasil umum terbatas atau halaman search normal

### 22.2 Record Tidak Ditemukan

Jika record tidak valid:

1. Tampilkan 404 yang ramah
2. Sediakan tautan kembali ke pencarian

### 22.3 Preview Tidak Sah

Jika asset tidak bisa dipreview:

1. Tampilkan pesan akses tidak tersedia
2. Sediakan tombol kembali ke detail

### 22.4 Hasil Pencarian Kosong

1. Jangan tampilkan halaman kosong polos
2. Sediakan saran pencarian

## 23. Performance UX OPAC

Target UX performa fase 1:

1. Beranda harus ringan
2. Hasil pencarian harus terasa cepat
3. Detail koleksi harus tidak bertele tele
4. Preview PDF harus tidak memuat elemen yang tidak perlu

Prinsip:

1. Search memakai Meilisearch
2. MySQL tetap sumber kebenaran final
3. Detail memuat data publik yang cukup, bukan seluruh data internal

## 24. Responsif OPAC

Aturan responsif:

1. Search bar tetap dominan di layar kecil
2. Filter bisa turun ke bawah search bar
3. Card hasil tetap terbaca di mobile
4. Metadata detail tidak boleh terlalu padat

Prioritas:

1. Desktop
2. Laptop
3. Tablet
4. Mobile tetap layak walau bukan target utama fase 1

## 25. Aksesibilitas Dasar OPAC

OPAC wajib memenuhi:

1. Search bar punya label jelas
2. Tombol preview jelas
3. Status ketersediaan tidak hanya memakai warna
4. Heading halaman jelas
5. Kontras teks cukup

## 26. Mapping Flow ke View

| Flow | View |
|---|---|
| Beranda OPAC | modules/opac/home.blade.php |
| Hasil Pencarian | modules/opac/search/index.blade.php |
| Detail Koleksi | modules/opac/records/show.blade.php |
| Preview Aset Publik | modules/opac/assets/preview.blade.php |
| Tentang Perpustakaan | modules/opac/about.blade.php |
| Bantuan Pencarian | modules/opac/help.blade.php |

## 27. Mapping Flow ke Controller dan Service

| Flow | Controller | Service |
|---|---|---|
| Beranda OPAC | HomeController | OpacHomeService |
| Hasil Pencarian | SearchController | CatalogSearchService |
| Detail Koleksi | RecordController | OpacRecordDetailService |
| Preview Aset Publik | AssetPreviewController | DigitalAssetAccessService |
| Tentang Perpustakaan | StaticPageController | StaticPageContentService |
| Bantuan Pencarian | StaticPageController | StaticPageContentService |

## 28. Mapping Flow ke Use Case

| Flow | Use Case |
|---|---|
| Beranda dan pencarian | UC-OPA-001 |
| Filter hasil pencarian | UC-OPA-002 |
| Detail koleksi | UC-OPA-003 |
| Ketersediaan fisik | UC-OPA-004 |
| Preview aset publik | UC-OPA-005 |

## 29. KPI UX OPAC

Indikator kualitas UX OPAC yang disarankan:

1. Pengguna dapat mencapai hasil pencarian dari beranda dalam 1 aksi utama
2. Pengguna dapat mencapai detail koleksi dari hasil dalam 1 klik
3. Pengguna dapat mengetahui ada atau tidaknya preview digital tanpa membuka halaman lain yang tidak perlu
4. Tidak ada kebocoran preview publik untuk asset privat
5. Empty state membantu memperbaiki pencarian, bukan membingungkan

## 30. Anti Pattern yang Dilarang

OPAC tidak boleh:

1. Menampilkan terlalu banyak filter yang membingungkan
2. Menampilkan metadata internal yang tidak relevan
3. Menampilkan tombol preview untuk asset yang tidak sah
4. Menampilkan hasil pencarian tanpa informasi ketersediaan
5. Menampilkan layout admin di area publik
6. Mengandalkan popup berlebihan
7. Memakai istilah teknis internal yang sulit dipahami pengguna umum

## 31. Prioritas Implementasi Flow OPAC

### Prioritas P1

1. Beranda OPAC
2. Hasil pencarian OPAC
3. Detail koleksi OPAC
4. Preview aset publik

### Prioritas P2

1. Tentang Perpustakaan
2. Bantuan Pencarian
3. Empty state dan error state yang lebih halus

### Prioritas P3

1. Shortcut kategori lanjutan
2. Discovery block atau rekomendasi koleksi
3. Pencarian yang lebih kaya dan analitik UX lanjutan

## 32. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 20_API_CONTRACT.md, bila OPAC nanti memakai endpoint khusus
2. 21_SEARCH_INDEXING_SPEC.md
3. 22_STORAGE_FILE_POLICY.md
4. 23_OCR_AND_DIGITAL_PROCESSING.md
5. 25_REPORTING_SPEC.md
6. 31_TEST_PLAN.md
7. 32_TEST_SCENARIO.md
8. 39_TRACEABILITY_MATRIX.md
9. 40_PAGE_BUILD_PRIORITY.md
10. 42_FRONTEND_CHECKLIST.md
11. 45_SMOKE_TEST_CHECKLIST.md
12. 46_UAT_CHECKLIST.md

Aturan:

1. Search indexing spec wajib mengikuti flow pencarian dokumen ini.
2. Storage policy wajib menjaga preview sesuai flow ini.
3. OCR processing tidak boleh mengganggu flow publik kecuali untuk peningkatan pencarian.
4. Test scenario wajib menguji semua journey utama OPAC.

## 33. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua flow OPAC sesuai route dan view map resmi
2. Semua action publik sesuai state machine
3. Search, detail, dan preview memakai service yang benar
4. Tidak ada data privat yang bocor ke flow publik
5. Journey pencarian fisik dan digital sudah jelas
6. Empty state dan access denied state sudah didefinisikan

## 34. Kesimpulan

Dokumen OPAC UX Flow ini menetapkan perjalanan pengguna publik PERPUSQU secara lengkap, mulai dari beranda, pencarian, filter, detail koleksi, hingga preview aset digital publik. Dokumen ini memastikan OPAC hibrid PERPUSQU benar benar berfungsi sebagai pintu masuk tunggal untuk koleksi fisik dan digital, dengan alur yang sederhana, aman, dan konsisten dengan seluruh blueprint sebelumnya. Semua implementasi OPAC PERPUSQU wajib merujuk dokumen ini.

END OF 19_OPAC_UX_FLOW.md
