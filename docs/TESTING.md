# Rancangan Pengujian PERPUSQU

Dokumen ini menjelaskan bentuk test suite: apa yang diuji, mengapa dibagi
seperti ini, dan cara menjalankannya.

## Integrasi berkelanjutan

`.github/workflows/ci.yml` menjalankan empat pekerjaan pada setiap push ke
`main` dan setiap pull request:

| Job | Yang dijaga |
|---|---|
| `test-sqlite` | seluruh suite pada PHP 8.4 |
| `test-mysql` | suite yang sama pada driver produksi |
| `analyse` | analisis statis Larastan (level 5) |
| `lint` | gaya kode Pint, **hanya pada berkas yang diubah PR** |
| `migrations` | `migrate` → `db:seed` → `migrate:rollback` di MySQL |

Dua pilihan yang perlu diketahui:

**Lint hanya menuntut berkas yang berubah.** Kode ini belum pernah diformat
Pint sepenuhnya; 94 berkas lama masih menyimpang. Gate yang menuntut seluruh
repositori akan merah sejak hari pertama, dan gate yang selalu merah cepat
diabaikan orang. Dengan `pint --diff`, repositori merapat sendiri setiap kali
sebuah berkas disentuh. Bila kelak ingin gate penuh, jalankan `vendor/bin/pint`
sekali pada seluruh repositori lalu ganti perintahnya menjadi `pint --test`.

**Hanya PHP 8.4.** Jendela versi yang sesungguhnya sempit: paket terkunci
menuntut PHP ≥ 8.4 (Symfony 8 / Laravel 13), sementara `phpspreadsheet` — lewat
`maatwebsite/excel` — membatasi < 8.5. `composer.json` sempat menyatakan `^8.3`,
yang keliru: `composer install` pada 8.3 gagal sebelum satu test pun berjalan.
`config.platform.php` disematkan ke `8.4.0` supaya resolusi di mesin pengembang
sama dengan CI dan produksi.

**Job `migrations` menguji jalur `down()`.** Test suite membangun skema dari nol
setiap kali, jadi jalur `up` sudah terjamin. Yang tidak pernah tersentuh adalah
rollback — dan migrasi yang tidak dapat dibatalkan baru ketahuan saat rilis
gagal dan seseorang perlu mundur.

## Menjalankan

```bash
composer test            # seluruh suite di atas SQLite in-memory (~17 detik)
composer test:unit       # aturan bisnis murni saja (~0,1 detik)
composer test:feature    # service + endpoint di atas basis data
composer test:mysql      # suite yang sama di atas MySQL (driver produksi)
```

`composer test:mysql` memerlukan basis data kosong sekali buat:

```sql
CREATE DATABASE perpusqu_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Kredensialnya diambil dari `.env`; hanya nama basis data yang dipaksa, sehingga
data pengembangan di `perpusqu` tidak pernah tersentuh.

## Dua lapisan, dua tujuan

| Lapisan | Letak | Isi | Basis data |
|---|---|---|---|
| Unit | `tests/Unit` | Kalkulator dan penjaga status: `DueDateCalculator`, `FineAmountCalculator`, dua mesin status, resolver kelayakan anggota | tidak ada |
| Feature | `tests/Feature` | Service di atas basis data sungguhan, endpoint HTTP lengkap dengan middleware izin, render Blade | SQLite in-memory / MySQL |

Lapisan unit sengaja dijaga bebas basis data. Matriks transisi status diuji
**lengkap** di sana — setiap pasangan status yang mungkin, bukan contoh pilihan —
karena harganya nol milidetik dan justru pasangan yang tak terpikirkanlah yang
biasanya lolos.

Lapisan feature memanggil aplikasi lewat pintu yang sama dengan pengguna: route,
middleware izin, form request, service, lalu Blade. Karena view ikut dirender,
kesalahan template ikut tertangkap.

## Yang dijaga tiap berkas

**Sirkulasi** (`tests/Feature/Circulation`) — inti aplikasi.
Kelayakan pinjam diuji per aturan penolakan secara terpisah lalu bersamaan;
peminjaman menguji bahwa penolakan tidak meninggalkan jejak apa pun (item tidak
tertinggal berstatus `loaned` tanpa baris pinjaman); pengembalian menguji kelima
efeknya sekaligus, termasuk yang **tidak** boleh terjadi saat tepat waktu;
perpanjangan menguji batas dua kali dan penolakan atas pinjaman lewat tempo.

**Katalog & koleksi** — siklus hidup publikasi dan mesin status item, masing-masing
dengan pasangan transisi sah dan tidak sah, plus riwayat status yang menyertainya.

**Identitas** — login (username/email, akun nonaktif, penguncian setelah lima
percobaan, regenerasi sesi), pengelolaan pengguna, dan pengelolaan role.

**OPAC** (`tests/Feature/Opac`) — satu-satunya permukaan tanpa login. Aturannya
sempit: hanya record `published` **dan** `is_public` yang terlihat. Keempat
keadaan tersembunyi diuji satu per satu, ditambah pemeriksaan bahwa barcode dan
kode inventaris tidak pernah bocor ke halaman publik.

**Repositori digital** — unggahan diuji dengan PDF sungguhan (bukan
`UploadedFile::fake()`, yang menghasilkan berkas kosong dan tidak pernah
melewati pemeriksaan MIME biner) dan dengan berkas yang menyamar sebagai PDF.

**Master data** (`tests/Feature/MasterData`) — sembilan entitas berpola sama
dijalankan lewat satu data provider: buat, ubah, hapus, render, dan pemisahan
izin baca dari izin tulis. Aturan khas tiap entitas diuji tersendiri.

**Kontrol akses** (`tests/Feature/Security/`) — dua jaring pengaman lintas modul.

`PermissionNamesAreConsistentTest` memindai policy, Blade, dan middleware route,
lalu mencocokkan setiap nama izin dan peran dengan yang didaftarkan
`PermissionSeeder`. Ini menjaga satu kelas cacat yang sudah muncul enam kali:
kode memeriksa izin yang tidak pernah ada, Gate menjawab `false` tanpa error,
dan tombol atau cabang policy mati diam-diam — tak terlihat oleh Super Admin,
yang lolos lebih dulu lewat `Gate::before`.

`RouteAccessControlTest` membaca **tabel route yang sesungguhnya**, lalu:

1. memastikan setiap route `admin/*` berada di belakang middleware `auth`;
2. memastikan setiap route menyebut izin yang dibutuhkannya, kecuali yang
   terdaftar eksplisit di `PERMISSIONLESS_BY_DESIGN`;
3. benar-benar memanggil setiap route sebagai tamu dan memastikan semuanya
   dialihkan ke halaman login;
4. memanggil setiap route sebagai pengguna tanpa izin dan memastikan semuanya
   dijawab 403.

Konsekuensinya: **route baru yang lupa dipasangi penjaga, dan nama izin baru
yang salah tulis, akan menggagalkan test tanpa ada yang perlu menambah test
baru.**

## Mode ketat: mengubah kegagalan senyap menjadi berisik

Ketujuh cacat yang ditemukan suite ini punya bentuk yang sama: **sistem gagal
tanpa bersuara.** Mass assignment membuang atribut, Gate menjawab `false` untuk
izin yang tidak ada, `update()` pada baris yang belum ada tidak melakukan apa pun.
Menambah test satu per satu hanya menangkap instansnya; dua penjaga di bawah
menyerang kelasnya.

**`Model::shouldBeStrict()` di luar produksi** (`AppServiceProvider::boot()`).
Atribut yang tidak fillable, relasi yang lazy-load pada koleksi, dan atribut
yang tidak ada kini melempar exception. Menyalakannya langsung menemukan satu
cacat yang belum diketahui: `LoanRenewal` mematikan `$timestamps` sehingga
`created_at` harus datang dari aplikasi, tetapi kolomnya tidak fillable — yang
tersimpan selama ini adalah jam server basis data, bukan jam aplikasi.

Catatan: Eloquent hanya menegakkan larangan lazy-load ketika sebuah kueri
menghidrasi lebih dari satu baris. N+1 memang baru bermakna pada koleksi.

**Penjaga nama izin** (`guardAgainstUnknownPermission`). Memeriksa izin bergaya
`modul.aksi` yang tidak pernah didaftarkan akan melempar exception di
lingkungan `local`. Pembagian tugasnya disengaja: di CI, pemindaian statis
`PermissionNamesAreConsistentTest` yang bekerja; di mesin pengembang, penjaga
ini berteriak saat tombol baru diklik. Di produksi keduanya diam, karena di
sana kegagalan keras lebih merugikan daripada tombol yang tidak muncul.

Keduanya dikunci `StrictModeTest` dan `UnknownPermissionGuardTest` — jaring
seperti ini mudah sekali hilang saat seseorang merapikan AppServiceProvider.

## Analisis statis

`composer analyse` menjalankan Larastan pada level 5 atas `app`, `database`,
`routes`, dan `tests`. Level itu memeriksa tipe argumen dan nilai kembali —
cukup untuk menangkap kelas kesalahan yang sudah beberapa kali terjadi di
proyek ini, tanpa menuntut anotasi generik di seluruh berkas sekaligus.

Menyalakannya menemukan tiga cacat nyata:

- `BulkImportController` mengimpor `App\Modules\Catalog\Models\Author` dan
  `...\Subject` yang **tidak pernah ada** — kelasnya berada di modul
  `MasterData`. Setiap baris impor massal yang membuat pengarang atau subjek
  baru akan fatal.
- `DueDateCalculator` memberi type hint `\DateTime` pada parameter yang
  memanggil `addDays()` — metode milik Carbon. Memanggilnya dengan objek
  `DateTime` biasa lolos pemeriksaan tetapi fatal saat berjalan.
- `config/permission.php` menunjuk model bawaan Spatie, bukan model
  `App\Modules\Identity\Models\Role` dan `Permission` milik aplikasi —
  sehingga scope `keyword()` yang didefinisikan di sana tidak pernah tersedia.

Sisa 48 temuan dicatat di `phpstan-baseline.neon`. Isinya sebagian besar
gesekan tipe Eloquent yang memang tidak dapat disimpulkan analisis statis —
alias hasil `DB::raw`, scope kueri, dan sejenisnya. Seperti gate Pint, daftar
itu **hanya boleh menyusut**; temuan baru tidak masuk baseline dan menggagalkan
CI. Cara menguranginya adalah menambah anotasi `@property` dan `@method` pada
model, yang sudah dilakukan untuk Loan, Member, PhysicalItem,
BibliographicRecord, DigitalAsset, dan DailyStatistic — dan itu sendiri
menurunkan temuan dari 111 menjadi 48.

## Dua lapis wewenang

Otorisasi di aplikasi ini punya dua lapis, masing-masing satu tugas:

1. **Middleware `permission:` pada route** menjawab *"boleh tidak peran ini
   memakai fitur tersebut"*. Setiap route admin memilikinya, dan
   `RouteAccessControlTest` memastikan tidak ada yang terlewat.
2. **Policy** menjawab *"boleh tidak pengguna ini bertindak atas record
   tertentu"*. Hanya aturan yang bergantung pada isi record yang pantas berada
   di sini — kepemilikan (`created_by`, `uploaded_by`) dan status publikasinya.

Policy yang isinya sekadar mengulang izin yang sudah diperiksa route adalah
duplikasi. Duplikasi itulah yang membuat `LoanPolicy`, `MemberPolicy`, dan
`PhysicalItemPolicy` hidup tanpa pernah dipanggil siapa pun, sambil menyimpan
tujuh nama izin yang tidak pernah didaftarkan. Ketiganya dihapus; aturan
per-record yang benar-benar mereka bawa memang sudah ditegakkan layanan
masing-masing — dan di sana pesannya sampai ke petugas alih-alih berubah
menjadi 403 tanpa penjelasan.

`AuthorizationLayersTest` menjaga aturan itu: setiap policy terdaftar harus
benar-benar dipanggil controller, dan harus membawa setidaknya satu aturan yang
menyentuh isi record.

## Pekerjaan terjadwal

`routes/console.php` mendaftarkan tiga pekerjaan harian/mingguan: pengingat
jatuh tempo (07:00), pelepasan embargo kedaluwarsa (01:00), dan pembersihan
jejak audit (Senin 02:00, retensi 730 hari).

Perintah artisan tiap modul ditemukan otomatis dari `app/Modules/*/Console`
lewat `withCommands()` di `bootstrap/app.php`, sejalan dengan cara route modul
dimuat. Modul baru tidak perlu didaftarkan manual — pendaftaran manual adalah
sumber drift yang sudah berulang di proyek ini.

Agar benar-benar berjalan, server produksi memerlukan satu baris cron:

```
* * * * * cd /path/ke/perpusqu && php artisan schedule:run >> /dev/null 2>&1
```

`ScheduledMaintenanceTest` menguji perintahnya **dan** pendaftarannya: perintah
yang benar tetapi tidak pernah terjadwal sama tidak bergunanya dengan perintah
yang tidak ada. Ikut diperiksa `withoutOverlapping()` dan `onOneServer()` —
tanpa keduanya, satu anggota dapat menerima pengingat yang sama beberapa kali.

## Pencarian OPAC: dua jalur, dua cara menguji

Pencarian berjalan dua tahap — FULLTEXT lebih dulu, lalu jatuh ke pencocokan
substring bila tahap pertama tidak menghasilkan apa pun atau basis datanya
bukan MySQL. Karena itu pengujiannya juga terbagi dua:

`OpacSearchTest` menguji **apa yang ditemukan pengguna**, tanpa peduli jalur
mana yang terpakai. Berjalan di kedua driver.

`OpacFullTextSearchTest` menguji **jalur FULLTEXT itu sendiri** dan hanya
berjalan di MySQL. Berkas ini memakai `DatabaseMigrations`, bukan
`RefreshDatabase`, karena indeks FULLTEXT InnoDB **tidak melihat baris yang
masih berada dalam transaksi yang belum di-commit** — di bawah
`RefreshDatabase` setiap `MATCH` mengembalikan kosong, dan test justru akan
menguji jalur cadangan tanpa disadari. Harganya migrasi ulang per test, dan
itu sebabnya berkas tersebut dijaga tetap kecil.

Sifat MySQL itu juga alasan jalur cadangan ada sejak awal. Selain transaksi,
FULLTEXT mengabaikan token lebih pendek dari `innodb_ft_min_token_size`
(bawaan 3), seluruh kata dalam daftar stopword, dan tidak mencakup ISBN maupun
nama pengarang. Tanpa cadangan, pencarian yang dulu berhasil bisa mendadak
tidak menemukan apa-apa — kemunduran yang jauh lebih merugikan daripada kueri
yang lambat.

Satu test memeriksa rencana kueri lewat `EXPLAIN` dan menuntut
`type=fulltext`. Optimizer MySQL baru memilih jalur itu bila tabelnya cukup
besar, jadi test tersebut menyemai puluhan baris lebih dulu — pada tabel satu
baris ia memilih indeks lain, dan itu keputusan yang benar.

## Perkakas

`tests/Concerns/ActsAsLibraryUser.php` menyediakan `userWith([...])` — pengguna
dengan **hanya** izin yang disebutkan. Ini disengaja: sebuah route hanya terbukti
dijaga bila diuji dengan pengguna yang tidak punya izin lain yang kebetulan
meloloskannya.

`tests/Concerns/BuildsLibraryFixtures.php` menyediakan fixture tingkat domain
(`availableItem()`, `eligibleMember()`, `activeLoan()`, `overdueLoan()`) supaya
test bicara soal aturan bisnis, bukan soal merangkai enam factory.

Factory untuk seluruh model ada di `database/factories/`. Model tersebar di
`app/Modules/*/Models`, sehingga `AppServiceProvider::register()` memetakan
model → `Database\Factories\{Model}Factory` yang datar.

## Temuan yang muncul dari suite ini

Menyusun test suite ini membongkar tujuh cacat nyata — dari tamu yang menerima
error 500 di setiap URL admin, sampai seluruh tombol meja sirkulasi yang tidak
pernah terlihat oleh Petugas Sirkulasi. Semuanya sudah diperbaiki dan kini
dijaga test. Riwayatnya ada di
[TEMUAN-PENGUJIAN.md](TEMUAN-PENGUJIAN.md).

Bila kelak ada cacat yang ditemukan tetapi belum boleh diperbaiki, konvensinya
adalah menamai test-nya berawalan `documented_defect_`: test semacam itu
**mengunci perilaku yang berlaku sekarang, bukan menyatakannya benar**, sehingga
perbaikannya nanti muncul sebagai perubahan yang disengaja. Saat ini tidak ada
satu pun yang tersisa.

## Basis data

Suite harian berjalan di SQLite in-memory: migrasi dijalankan sekali per proses,
tiap test dibungkus transaksi. Foreign key ditegakkan (`DB_FOREIGN_KEYS=true`),
sehingga pelanggaran relasi ikut tertangkap.

Dua bagian skema khas MySQL diberi cabang setara untuk SQLite:

- indeks `FULLTEXT` pada `bibliographic_records` dilewati (pencarian aplikasi
  memakai `LIKE`, bukan `MATCH`);
- invarian "satu item hanya boleh punya satu pinjaman aktif" diwujudkan sebagai
  generated column + unique key di MySQL, dan sebagai partial unique index di
  SQLite — keduanya menegakkan aturan yang sama, dan keduanya diuji.

Jalur MySQL tidak berubah sedikit pun. `composer test:mysql` menjalankan suite
yang sama di atas driver produksi untuk membuktikannya.
