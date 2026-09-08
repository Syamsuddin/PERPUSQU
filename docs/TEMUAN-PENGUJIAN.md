# Temuan dari Penyusunan Test Suite

Tujuh masalah nyata muncul saat suite dibangun. Ketujuhnya sudah diperbaiki.

---

## Sudah diperbaiki

### 1. Tamu yang membuka URL admin menerima error 500

`bootstrap/app.php`

Middleware `auth` mengarahkan pengguna yang belum masuk ke route bernama `login`.
Aplikasi menamai halaman loginnya `auth.login`, sehingga route `login` tidak
pernah ada — setiap tamu yang membuka URL `admin/*` (dari tautan lama, bookmark,
atau hasil pencarian) menerima `RouteNotFoundException`, bukan halaman login.

Perbaikan: `$middleware->redirectGuestsTo(fn () => route('auth.login'))`.

Dijaga oleh `RouteAccessControlTest::a_guest_is_never_served_an_admin_route`,
yang memanggil **setiap** route admin sebagai tamu.

### 2. Waktu login terakhir tidak pernah tersimpan

`app/Modules/Identity/Models/User.php`

`AuthenticationService` menulis `$user->update(['last_login_at' => now()])`, tetapi
`last_login_at` tidak terdaftar di `$fillable`. Mass assignment membuangnya tanpa
error, jadi kolomnya selalu `NULL` — padahal migrasi, cast, dan kode penulisnya
semua sudah ada.

Perbaikan: menambahkan `'last_login_at'` ke `$fillable`.

Dijaga oleh `AuthenticationTest::a_successful_sign_in_stamps_the_last_login_time`.

### 3. Laporan sirkulasi dan denda memakai SQL khas MySQL

`app/Modules/Reporting/Http/Controllers/ReportController.php`

Dua kueri memakai `MONTH()` dan satu memakai `HAVING` tanpa `GROUP BY`. Keduanya
sah di MySQL, ditolak SQLite — tidak merusak produksi, tetapi membuat modul
laporan mustahil diuji di luar MySQL.

Perbaikan: ekspresi bulan dibuat sadar-driver lewat `monthExpression()`, dan
`having()` dilengkapi `groupBy('members.id')`. **SQL yang dihasilkan di MySQL
tidak berubah sama sekali**; `composer test:mysql` membuktikannya.

### 4. Pustakawan tidak dapat menyunting katalog sama sekali

`BibliographicRecordPolicy::update()` meloloskan tiga cabang, dan dua di antaranya
mustahil terpenuhi:

- `catalog.update` **dan** `created_by === user->id` — tetapi `created_by` tidak
  ada di `$fillable` dan tidak pernah diisi oleh `BibliographicRecordService`.
  Nilainya selalu `NULL`, sehingga perbandingannya selalu gagal.
- `catalog.update_any` — izin ini tidak pernah didaftarkan `PermissionSeeder`.

Yang tersisa hanya cabang Super Admin. Pustakawan lolos middleware
`permission:catalog.update`, melihat form edit, lalu menerima 403 setiap menyimpan.

Perbaikan mengembalikan rancangan dua-tingkat yang jelas dimaksudkan policy:

| Berkas | Perubahan |
|---|---|
| `Catalog/Models/BibliographicRecord.php` | `created_by`, `updated_by` masuk `$fillable` |
| `Catalog/Services/BibliographicRecordService.php` | mengisi `created_by` saat create, `updated_by` saat create dan update — dari `auth()->id()`, bukan dari input |
| `database/seeders/PermissionSeeder.php` | mendaftarkan `catalog.update_any` dan `catalog.delete_own` yang dirujuk policy |
| `database/seeders/RolePermissionSeeder.php` | memberikan `catalog.update_any` kepada Pustakawan |

Hasilnya dua tingkat wewenang yang berbeda:

- `catalog.update` saja → hanya boleh menyunting record buatan sendiri;
- `catalog.update` + `catalog.update_any` → boleh menyunting record siapa pun
  (dipegang Pustakawan, Admin Perpustakaan, dan Super Admin).

Kepemilikan diisi server, tidak pernah dari formulir; upaya menitipkan
`created_by` lewat request diuji dan diabaikan.

Dijaga oleh lima test di `BibliographicRecordCrudTest` dan — yang terpenting —
oleh `SeededRoleCapabilityTest`, yang menjalankan **seeder yang sesungguhnya**
lalu membuktikan seorang Pustakawan hasil seeder benar-benar dapat memperbaiki
katalog yang dibuat orang lain. Berkas itu juga menolak izin yatim, yaitu izin
terdaftar yang tidak pernah sampai ke satu peran pun — persis keadaan yang
membuat `catalog.update_any` tidak berguna selama ini.

### 5. Penghapusan permanen pada tabel yang dirancang untuk penghapusan lunak

`PhysicalItem`, `Member`, `BibliographicRecord` punya kolom `deleted_at` di
migrasi, tetapi modelnya tidak memakai trait `SoftDeletes` — sementara
`DigitalAsset` dan `User` memakainya. Akibatnya `delete()` menghapus baris secara
permanen, dan karena `loans`, `fines`, serta `return_transactions` menahan
`physical_items` dan `members` dengan `restrictOnDelete`, **item atau anggota yang
pernah terlibat transaksi tidak dapat dihapus sama sekali**: petugas menerima
error basis data, bukan pesan yang bisa dibaca.

Perbaikannya mengikuti apa yang sudah dinyatakan skema:

**a. Trait `SoftDeletes` ditambahkan** pada ketiga model. Penghapusan kini
mengisi `deleted_at`, baris hilang dari seluruh kueri, dan foreign key tidak lagi
terpicu.

**b. Relasi historis membaca induk yang terhapus** lewat `withTrashed()` pada
`Loan::member()`, `Loan::physicalItem()`, `Fine::member()`,
`ReturnTransaction::physicalItem()`, `PhysicalItemStatusHistory::physicalItem()`,
`PhysicalItem::bibliographicRecord()`, dan `DigitalAsset::bibliographicRecord()`.
Tanpa ini, menghapus seorang anggota akan membuat seluruh riwayat pinjaman dan
tagihan dendanya kehilangan nama pemiliknya.

**c. Data pendukung tidak lagi ikut dimusnahkan.** Sebelumnya
`PhysicalItemService::delete()` menghapus riwayat status dan
`BibliographicRecordService::delete()` melepas relasi pengarang/subjek serta
membuang berkas sampul. Pada penghapusan lunak hal itu membuat pemulihan menjadi
lossy — baris kembali, tetapi kosong. Semuanya kini dipertahankan.

**d. Pembatasan yang tadinya dijaga foreign key ditegakkan di layanan.**
Penghapusan lunak melewati `restrictOnDelete` di tingkat basis data, jadi
`BibliographicRecordService::delete()` menolak katalog yang masih memiliki
eksemplar fisik atau aset digital, dengan pesan yang menyebut jumlahnya.
`BibliographicRecordController::destroy()` menangkap pesan itu sebagai flash
error, bukan meneruskannya sebagai 500.

Catatan yang perlu diketahui: unique index pada `barcode`, `member_number`, dan
`slug` tidak bersyarat `deleted_at`, sehingga baris yang dihapus lunak **tetap
memegang identifikatornya**. Barcode item yang dihapus tidak bisa dipakai ulang.
Ini konsisten antara basis data dan validasi, dan memang perilaku yang benar
untuk barcode perpustakaan — tetapi perlu dijelaskan ke petugas, karena pesan
"Barcode sudah digunakan" akan merujuk item yang tidak terlihat di layar mana pun.

Dijaga oleh sembilan test baru yang mencakup penghapusan lunak, pemulihan utuh
beserta relasinya, riwayat yang tetap terbaca setelah induknya dihapus, hilangnya
record dari OPAC, dan kedua pembatasan penghapusan katalog.

**Tindak lanjut yang belum ada:** antarmuka untuk melihat dan memulihkan baris
yang terhapus. Saat ini pemulihan hanya mungkin lewat `restore()` di tinker.
Sebelum antarmuka itu ada, penghapusan tetap terasa permanen bagi petugas.

### 6. Seluruh halaman Aturan Operasional tidak terhubung ke apa pun

Bukan hanya empat kunci seperti dugaan awal — **ketiga belas kunci di
`system_settings` tidak pernah dibaca satu baris kode pun** di luar halaman yang
menulisnya. Administrator dapat mengubah semuanya, melihat pesan "berhasil
diperbarui", dan tidak ada satu pun perilaku sistem yang berubah.

Ganjalan yang membuat "tinggal disambungkan saja" bukan jawaban yang benar:
halaman menyediakan `loan_default_days` — **satu** angka — sementara sistem
memakai tabel **per jenis anggota** (dosen 30 hari, mahasiswa 14, tamu 7).
Menyambungkannya apa adanya akan meratakan semuanya dan mencabut hak pinjam 30
hari milik dosen.

Perbaikannya membalik arah pembacaan, dengan rancangan lengkap di
[USULAN-ATURAN-OPERASIONAL.md](USULAN-ATURAN-OPERASIONAL.md):

| Berkas | Peran |
|---|---|
| `Core/Services/OperationalRules.php` | pembaca tunggal; singleton, tabel dibaca sekali per request, konstanta `DEFAULTS` = angka yang berlaku sebelumnya |
| `Circulation/Support/DueDateCalculator.php` | tetap fungsi murni; menerima lama pinjam sebagai argumen, tidak lagi menyimpan tabel kebijakan |
| `Circulation/Support/FineAmountCalculator.php` | idem, menerima tarif harian sebagai argumen |
| `Circulation/Services/*` | empat layanan menyuntikkan `OperationalRules` |
| `2026_09_08_000001_seed_operational_rule_defaults.php` | pengaman rilis |
| halaman Aturan Operasional | lama pinjam per jenis anggota, lama perpanjangan, dan tiga saklar kebijakan |

Yang kini dikendalikan dari halaman pengaturan: lama pinjam per jenis anggota
(plus cadangan untuk jenis lain), lama perpanjangan, batas perpanjangan, batas
pinjaman aktif, tarif denda harian, serta saklar `allow_renewal`,
`require_active_member`, dan `require_unblocked_member`.

**Pengaman rilis.** Migrasi menuliskan angka yang berlaku di kode ke
`system_settings` sebelum sirkulasi mulai membacanya, dan hanya mengisi kunci
yang belum ada — nilai yang pernah diatur administrator tidak disentuh. Tanpa
langkah ini, instalasi yang sudah berjalan akan berubah perilaku diam-diam pada
detik penerapan. Jalur upgrade diverifikasi langsung: instalasi dengan empat
kunci lama dan tarif denda 2500 keluar dengan periode per jenis anggota kembali
utuh dan tarif 2500 tetap terjaga.

**Kalkulator sengaja tidak dibuat membaca basis data.** Keduanya tetap fungsi
murni yang menerima angka sebagai argumen, sehingga unit test-nya tetap berjalan
dalam milidetik tanpa basis data. Menaruh pembacaan pengaturan di dalamnya akan
menyeret seluruh lapisan unit ke basis data demi kenyamanan sesaat.

Dijaga oleh 21 test baru: `OperationalRulesReaderTest` menguji rantai
penelusuran (kunci khusus → cadangan → bawaan) dan membuktikan instalasi hasil
migrasi berperilaku persis seperti sebelumnya; `OperationalRulesTest` menguji
bahwa aturan yang disimpan benar-benar mengubah jatuh tempo, batas pinjam, batas
dan lama perpanjangan, tarif denda, serta ketiga saklar.

### 6b. Enam kunci sisanya

Putaran pertama menyambungkan tujuh kunci sirkulasi. Enam sisanya —
`asset_max_upload_size_mb`, `ocr_enabled`, `public_preview_enabled`, `app_name`,
`app_version`, dan `maintenance_mode` — kini juga bekerja, masing-masing di
tempat yang sesuai sifatnya.

| Kunci | Tempatnya bekerja |
|---|---|
| `asset_max_upload_size_mb` | aturan `max:` pada StoreDigitalAssetRequest dan UpdateDigitalAssetRequest, **dan** di DigitalAssetUploadService — unggahan dari job atau perintah artisan tidak melewati validasi HTTP |
| `ocr_enabled` | OcrProcessingService menolak permintaan selama saklarnya mati, alih-alih menandai aset `queued` selamanya tanpa ada pemrosesnya |
| `public_preview_enabled` | PublicAssetPreviewService — menutup pratinjau seluruh aset sekaligus tanpa menyentuh status publikasi satu per satu |
| `app_name` | judul tab, sidebar admin, header dan footer OPAC, serta cadangan nama di halaman depan — lewat view composer |
| `app_version` | footer admin dan OPAC; **ditampilkan tetapi tidak dapat disunting**, karena menggambarkan kode yang terpasang, bukan kebijakan |
| `maintenance_mode` | middleware EnsurePublicCatalogueIsOpen |

**Mode pemeliharaan sengaja tidak memakai `php artisan down`.** Perintah itu
menutup seluruh aplikasi termasuk area admin, sehingga saklar yang dinyalakan
lewat antarmuka akan mengunci orang yang harus mematikannya kembali. Di sini
hanya katalog publik yang tertutup (503 + halaman pemeliharaan); halaman login,
area admin, dan halaman pengaturannya sendiri tetap terbuka, dan pengguna yang
sudah masuk tetap dapat membuka OPAC untuk memeriksa hasil pekerjaan.

**Pengaman rilis.** Migrasi `2026_09_08_000002` mengisi kunci yang belum ada
dengan perilaku yang berlaku sebelumnya, lalu **memaksa `maintenance_mode`
menjadi `false`**. Sampai kini nilai kunci itu diabaikan sepenuhnya, sehingga
sebuah `true` yang terlanjur mengendap di basis data akan menutup katalog publik
pada detik middleware aktif — tanpa ada yang memutuskannya.

Struktur pembacanya dipisah dua tingkat: `SystemSettings` membaca tabel (satu
kali per request, dibagi seluruh aplikasi), dan `OperationalRules` menjadi
pembungkus khusus sirkulasi di atasnya. Satu tabel, satu cache, dua kosakata.

---

### 7. Enam nama izin yang diperiksa kode tetapi tidak pernah didaftarkan

Ditemukan saat menyambungkan `ocr_enabled`: policy OCR memeriksa
`digital_assets.ocr`, sedangkan yang didaftarkan `PermissionSeeder` adalah
`digital_assets.run_ocr`. Penelusuran menyeluruh menemukan pola yang sama di
banyak tempat — kelas cacat yang sama dengan butir 4.

**Mengapa berbahaya.** Gate menjawab `false` untuk izin yang tidak ada, tanpa
error dan tanpa catatan di log. Cabang policy atau tombol yang bersangkutan mati
diam-diam. Yang paling mungkin menguji aplikasi — Super Admin — tidak akan
pernah menyadarinya, karena `Gate::before` meloloskannya lebih dulu.

Yang sudah berdampak pada pengguna dan kini diperbaiki:

| Tempat | Diperiksa | Seharusnya | Akibat sebelumnya |
|---|---|---|---|
| `DigitalAssetPolicy::runOcr` | `digital_assets.ocr` | `digital_assets.run_ocr` | Operator Repositori Digital tidak pernah bisa menjalankan OCR |
| `loans/active.blade.php` | `circulation.create` | `circulation.process_loan` | tombol **Pinjam Baru** tak terlihat |
| `loans/active.blade.php` | `circulation.return` | `circulation.process_return` | tombol **Pengembalian** tak terlihat |
| `loans/show.blade.php` | `circulation.renew` | `circulation.process_renewal` | tombol **Perpanjang** tak terlihat |
| `fines/index.blade.php` | `circulation.fine` | `circulation.view_fines` | tombol lunasi/hapuskan denda tak terlihat |
| `LoanPolicy` (2 tempat) | peran `super-admin` | peran `Super Admin` | cabang yang tak pernah tercapai |

Artinya **seluruh tombol meja sirkulasi tidak terlihat oleh Petugas Sirkulasi** —
peran yang justru dibuat untuk memakainya. Rutenya berfungsi; hanya tombolnya
yang tidak pernah muncul, sehingga tampak seperti fitur yang tidak ada.

Memperbaiki tombol **Perpanjang** sekaligus menyingkap satu regresi tersembunyi:
`loans/show.blade.php` memanggil `DueDateCalculator::maxRenewals()` yang sudah
dihapus pada butir 6. Pemanggilan itu berada di dalam blok `@can` yang rusak,
sehingga tidak pernah dieksekusi. Batas perpanjangan kini disuplai
`LoanController::show()` — view tidak lagi menjangkau layanan sendiri.

**Sebelas rujukan lain dibiarkan**, semuanya di metode policy yang tidak pernah
dipanggil controller mana pun (`LoanPolicy` dan `MemberPolicy` tidak lewat
`authorize()`), jadi belum berdampak. Memperbaikinya berarti memutuskan siapa
yang berhak menghapus denda atau memaksa pengembalian — keputusan kebijakan,
bukan salah tulis. Daftarnya tercatat sebagai baseline di
`PermissionNamesAreConsistentTest::KNOWN_UNRESOLVED`.

**Penjaganya.** `tests/Feature/Security/PermissionNamesAreConsistentTest.php`
memindai kode yang sesungguhnya — policy, Blade, dan middleware route — lalu
mencocokkan setiap nama izin dan peran dengan yang didaftarkan seeder. Baseline
hanya boleh menyusut; nama baru yang tidak terdaftar akan menggagalkan test.
