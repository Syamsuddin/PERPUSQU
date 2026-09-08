# Usulan: Menyambungkan Aturan Operasional

> **Status: pilihan C sudah diterapkan.** Dokumen ini dipertahankan sebagai
> catatan rancangan — alasan di balik bentuk yang dipilih, dan dua alternatif
> yang ditolak beserta sebabnya. Ringkasan hasilnya ada di
> [TEMUAN-PENGUJIAN.md](TEMUAN-PENGUJIAN.md) butir 6.

Halaman **Pengaturan → Aturan Operasional** menyimpan tiga belas kunci ke
`system_settings`, dan tidak satu pun dibaca oleh sistem. Dokumen ini
membandingkan tiga jalan keluar dan mengusulkan satu.

## Ganjalan yang menentukan pilihan

Satu detail membuat "tinggal disambungkan saja" bukan jawaban yang benar:

- **Halaman pengaturan** menyediakan `loan_default_days` — **satu** angka.
- **Sistem** memakai `DueDateCalculator::$loanPeriods` — **tabel per jenis
  anggota**: mahasiswa 14 hari, dosen 30, staf 14, alumni 7, tamu 7.

Menyambungkan `loan_default_days` apa adanya akan meratakan semua jenis anggota
ke satu angka. Dosen kehilangan hak pinjam 30 harinya. Itu bukan perbaikan
melainkan kemunduran fungsi, dan kemunduran yang tidak akan terlihat sampai ada
dosen yang mengeluh.

Ganjalan yang sama muncul lebih kecil pada `DueDateCalculator::$renewalPeriod`
(7 hari): nilai ini bahkan tidak punya kunci pengaturan sama sekali.

---

## Tiga pilihan

### A. Hapus halaman Aturan Operasional

Buang halaman, route, izin, dan tabel isiannya. Sistem tetap berjalan persis
seperti sekarang, dan tidak ada lagi kendali yang berbohong.

*Untung:* paling murah, langsung jujur.
*Rugi:* membuang kemampuan yang jelas sengaja dibangun — ada UI, dua izin
terpisah untuk lihat dan ubah, seeder, dan aturan validasi lengkap. Setiap
perubahan kebijakan denda atau lama pinjam kembali menjadi permintaan ke
pengembang, bukan sesuatu yang bisa diputuskan kepala perpustakaan.

### B. Sambungkan keempat kunci apa adanya

*Untung:* perubahan paling sedikit.
*Rugi:* menimbulkan kemunduran yang dijelaskan di atas. Tidak dianjurkan.

### C. Jadikan pengaturan sebagai sumber kebenaran, dengan nilai kode sekarang
sebagai bawaan — **usulan saya**

Lengkapi skema pengaturan sampai cukup untuk menyatakan kebijakan yang memang
sudah berlaku, lalu balik arah pembacaannya.

---

## Rancangan pilihan C

### 1. Lengkapi kunci pengaturan

Tambah lima kunci lama pinjam per jenis anggota, dan satu kunci lama perpanjangan:

```
loan_days_student   = 14      loan_renewal_days   = 7
loan_days_lecturer  = 30
loan_days_staff     = 14      (loan_default_days tetap ada, berubah peran
loan_days_alumni    = 7        menjadi cadangan untuk jenis anggota yang
loan_days_guest     = 7        belum punya kuncinya sendiri)
```

### 2. Satu pembaca aturan

Kelas `OperationalRules` di modul Core, memuat `system_settings` **sekali per
request**, dengan getter bertipe dan nilai bawaan **sama persis dengan angka yang
berlaku hari ini**:

```php
$rules->loanPeriodDays('lecturer');   // 30 bila kunci kosong
$rules->renewalPeriodDays();          // 7
$rules->maxRenewals();                // 2
$rules->maxActiveLoans();             // 5
$rules->fineDailyAmount();            // 1000.0
```

Kunci yang belum diisi jatuh ke bawaan, jadi instalasi dengan tabel pengaturan
kosong tetap berperilaku seperti sekarang.

### 3. Kalkulator tetap murni

`DueDateCalculator` dan `FineAmountCalculator` **tidak** dibuat membaca basis
data. Keduanya tetap fungsi murni, hanya berubah dari membaca tabel statis
menjadi menerima angka sebagai argumen:

```php
DueDateCalculator::calculate($rules->loanPeriodDays($member->member_type), $loanDate);
FineAmountCalculator::calculate($lateDays, $rules->fineDailyAmount());
```

Yang menyuntikkan `OperationalRules` adalah `LoanTransactionService`,
`LoanRenewalService`, `ReturnProcessingService`, dan `LoanEligibilityService`.

Ini disengaja: unit test kedua kalkulator sekarang berjalan dalam milidetik tanpa
basis data. Menaruh pembacaan pengaturan di dalamnya akan menyeret seluruh
lapisan unit ke basis data demi kenyamanan sesaat.

### 4. Tiga saklar yang menganggur ikut disambungkan

`allow_renewal`, `require_active_member`, dan `require_unblocked_member`
diperiksa di `LoanRenewalService` dan `LoanEligibilityService` — tempatnya sudah
ada, tinggal menambahkan syarat.

### 5. Kunci selebihnya

*Catatan penerapan: bagian ini akhirnya dikerjakan pada putaran kedua, dengan
kesimpulan yang sedikit berbeda dari usulan awal.*

`asset_max_upload_size_mb`, `ocr_enabled`, dan `public_preview_enabled`
mendapat bagiannya sendiri di halaman ini. `app_name` menjadi kolom teks;
`app_version` hanya ditampilkan, tidak dapat disunting. `maintenance_mode`
ternyata punya tempat yang jelas dan berbeda dari `php artisan down`: menutup
katalog publik saja, sehingga jalan masuk petugas tetap terbuka. Rinciannya di
[TEMUAN-PENGUJIAN.md](TEMUAN-PENGUJIAN.md) butir 6b.

### 6. Pengaman saat rilis

Sebelum pembacaan dibalik, satu seeder menuliskan **nilai yang berlaku di kode
hari ini** ke `system_settings`. Dengan begitu perilaku sistem pada detik
penerapan tidak berubah sama sekali; instalasi yang tabel pengaturannya sudah
berisi angka lain tidak mendadak berganti kebijakan tanpa disadari.

Ini penting: tanpa langkah tersebut, `perpusqu` yang sekarang menyimpan
`fine_daily_amount = 1000` aman, tetapi instalasi mana pun yang pernah
mengubahnya akan langsung berganti tarif denda begitu kode baru naik.

---

## Cakupan pengujian yang menyertainya

- Unit: kedua kalkulator diuji dengan angka yang disuntikkan, bukan angka tetap.
- Feature: mengubah aturan lewat halaman pengaturan lalu membuktikan pinjaman
  berikutnya memakai aturan baru — jatuh tempo, batas pinjam, batas perpanjangan,
  dan tarif denda.
- Regresi: dengan `system_settings` kosong, seluruh perilaku sirkulasi identik
  dengan hari ini.

Test `documented_defect_the_saved_rules_do_not_reach_the_circulation_engine`
di `OperationalRulesTest` dibalik menjadi pembuktian bahwa aturannya sampai.

## Perkiraan

Sedang — sekitar sepuluh berkas tersentuh, sebagian besar berupa penggantian
konstanta dengan pemanggilan getter. Bagian yang menuntut ketelitian bukan
kodenya, melainkan langkah 6.

## Bila anggarannya lebih ketat

Kerjakan langkah 1–3 dan 6 saja (lama pinjam, perpanjangan, batas pinjam, tarif
denda), lalu **hapus** tiga saklar di langkah 4 dan tiga kunci di langkah 5 dari
halaman. Hasilnya halaman yang lebih kecil tetapi seluruhnya jujur — dan itu
lebih baik daripada halaman lengkap yang separuhnya hiasan.
