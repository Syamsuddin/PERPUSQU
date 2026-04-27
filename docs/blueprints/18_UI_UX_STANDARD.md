# 18_UI_UX_STANDARD.md

## 1. Nama Dokumen

UI UX Standard Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint standar UI dan UX aplikasi

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan tampilan, layout, navigasi, komponen, pola halaman, interaksi pengguna, dan konsistensi frontend

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan standar UI dan UX resmi PERPUSQU untuk seluruh area admin internal dan OPAC publik. Dokumen ini menjadi acuan wajib bagi AI Agent, frontend developer, backend developer, reviewer, dan tester agar semua halaman, komponen, tabel, form, dan interaksi aplikasi tampil konsisten, mudah digunakan, responsif, dan sesuai dengan blueprint yang telah ditulis sebelumnya.

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

Aturan wajib:

1. Semua halaman harus mengikuti view map resmi.
2. Semua menu dan tombol harus mengikuti permission matrix.
3. Semua aksi UI harus mengikuti state machine resmi.
4. Semua form harus mengikuti validation rules.
5. Semua halaman list, detail, dan form harus mengikuti standar ini.
6. Tidak boleh ada halaman yang memakai layout liar di luar standar dokumen ini.
7. Tidak boleh ada tombol aksi yang tampil jika state atau permission tidak mengizinkan.

## 4. Prinsip Umum UI dan UX PERPUSQU

Prinsip resmi UI dan UX PERPUSQU adalah:

1. Jelas.
2. Ringkas.
3. Konsisten.
4. Cepat dipahami operator kampus.
5. Responsif untuk desktop dan laptop.
6. Tetap layak dipakai di tablet.
7. Fokus ke efisiensi kerja pustakawan dan petugas.
8. Fokus ke pencarian cepat pada OPAC publik.
9. Tidak berlebihan secara visual.
10. Mendukung akses informasi dan operasional dengan sedikit klik.

## 5. Sasaran UX

Standar UX PERPUSQU harus mendukung sasaran berikut:

1. Petugas baru dapat memahami navigasi dasar tanpa pelatihan panjang.
2. Pustakawan dapat membuat katalog dan item dengan alur kerja yang jelas.
3. Petugas sirkulasi dapat memproses pinjam, kembali, dan perpanjang dengan cepat.
4. Operator repositori dapat unggah PDF dan mengelola akses file secara mudah.
5. Pimpinan dapat melihat statistik dan laporan tanpa kebingungan.
6. Pengguna OPAC publik dapat mencari koleksi fisik dan digital dari satu titik masuk.

## 6. Area UI Utama

PERPUSQU memiliki 3 area UI utama:

1. UI Auth Internal
2. UI Admin Internal
3. UI OPAC Publik

## 7. Stack Frontend Resmi

Sesuai blueprint sebelumnya, stack frontend resmi adalah:

1. Blade
2. Livewire 4
3. Bootstrap 5.3
4. Vite
5. PDF.js untuk preview PDF

Aturan:

1. UI utama dibangun dengan Blade dan Bootstrap.
2. Interaksi dinamis terbatas dapat memakai Livewire.
3. Jangan membuat kompleksitas frontend berlebihan bila tidak diperlukan.
4. JQuery tidak menjadi standar wajib.
5. Komponen harus tetap sederhana dan stabil.

## 8. Layout Utama Resmi

### 8.1 Layout Auth

Dipakai untuk:

1. Halaman login

Karakter:

1. Bersih
2. Minimal
3. Fokus pada form login
4. Tidak banyak distraksi

Komponen:

1. Logo atau nama PERPUSQU
2. Nama institusi atau perpustakaan
3. Card form login
4. Pesan error
5. Footer ringan

### 8.2 Layout Admin

Dipakai untuk:

1. Semua halaman internal admin

Karakter:

1. Dashboard admin modern
2. Rapi
3. Struktur tetap
4. Navigasi jelas
5. Konsisten di seluruh modul

Komponen tetap:

1. Sidebar kiri
2. Header atas
3. Breadcrumb
4. Area page header
5. Flash message area
6. Content area
7. Footer

### 8.3 Layout OPAC Publik

Dipakai untuk:

1. Beranda OPAC
2. Halaman pencarian
3. Detail koleksi
4. Halaman tentang
5. Halaman bantuan
6. Preview aset publik

Karakter:

1. Ringan
2. Fokus pada pencarian
3. Ramah pengguna umum
4. Bersih
5. Cepat dipahami

## 9. Gaya Visual Umum

### 9.1 Karakter Visual

PERPUSQU menggunakan karakter visual:

1. Formal
2. Modern
3. Bersih
4. Profesional
5. Tidak terlalu gelap
6. Tidak terlalu dekoratif

### 9.2 Palet Warna Utama

Warna disarankan:

1. Primer: Biru tua atau biru kampus
2. Sekunder: Putih
3. Aksen: Abu abu netral
4. Sukses: Hijau
5. Peringatan: Kuning
6. Bahaya: Merah
7. Info: Biru muda

Rekomendasi fungsi warna:

1. Navbar dan sidebar aktif memakai warna primer
2. Background utama halaman memakai putih atau abu sangat muda
3. Badge status memakai warna sesuai state
4. Tombol aksi utama memakai warna primer
5. Tombol proses sukses memakai hijau
6. Tombol destruktif memakai merah

### 9.3 Prinsip Penggunaan Warna

1. Satu warna primer dominan.
2. Jangan memakai terlalu banyak warna mencolok dalam satu halaman.
3. Warna status harus konsisten antar modul.
4. Status bahaya tidak boleh memakai warna yang ambigu.

## 10. Tipografi

Standar tipografi:

1. Gunakan font sistem atau font web yang netral dan mudah dibaca.
2. Ukuran teks body standar 14 px sampai 16 px.
3. Judul halaman 24 px sampai 28 px.
4. Subjudul section 18 px sampai 20 px.
5. Label form 13 px sampai 14 px.
6. Text muted untuk bantuan kecil.
7. Line height cukup longgar agar nyaman dibaca.

Aturan:

1. Jangan memakai terlalu banyak variasi font.
2. Maksimal 2 bobot utama yang sering dipakai.
3. Pastikan tabel dan form tetap terbaca jelas.

## 11. Grid dan Spacing

Aturan spacing:

1. Gunakan grid Bootstrap resmi.
2. Gunakan margin dan padding konsisten.
3. Jarak antar card, form section, dan tabel harus cukup.
4. Jangan membuat halaman padat tanpa ruang napas.

Rekomendasi:

1. Container content admin memakai padding sedang.
2. Card body memakai padding cukup.
3. Jarak antar tombol minimum nyaman untuk klik.

## 12. Standar Sidebar Admin

### 12.1 Struktur Sidebar

Sidebar wajib memuat:

1. Brand PERPUSQU
2. Daftar menu utama sesuai 08_MENU_MAP.md
3. Submenu expand collapse
4. Penanda menu aktif
5. Penanda submenu aktif

### 12.2 Perilaku Sidebar

1. Sidebar tampil tetap di desktop.
2. Sidebar dapat collapse.
3. Sidebar dapat dibuka tutup di layar kecil.
4. Menu parent terbuka otomatis jika submenu aktif.
5. Menu tanpa permission tidak boleh tampil.

### 12.3 Urutan Menu Sidebar

Urutan menu wajib sesuai blueprint:

1. Dashboard
2. Master Data
3. Katalog
4. Koleksi Fisik
5. Anggota
6. Sirkulasi
7. Repositori Digital
8. Laporan
9. Audit dan Monitoring
10. Pengaturan Sistem
11. Manajemen Akses

## 13. Standar Header Admin

Header admin wajib memuat:

1. Tombol toggle sidebar
2. Judul halaman
3. Breadcrumb
4. Shortcut OPAC
5. Dropdown user
6. Aksi profil
7. Aksi ubah password
8. Logout

Aturan:

1. Header harus tetap ringan.
2. Breadcrumb harus jelas.
3. Judul halaman mengikuti page aktif.
4. Shortcut OPAC disarankan selalu tersedia.

## 14. Standar Footer Admin

Footer admin sederhana dan konsisten, memuat:

1. Nama sistem
2. Tahun
3. Nama institusi singkat bila diperlukan

Aturan:

1. Footer tidak dominan.
2. Footer tidak memuat informasi yang terlalu banyak.

## 15. Standar Page Header

Setiap halaman admin wajib punya page header yang konsisten.

Komponen page header:

1. Judul halaman
2. Deskripsi singkat opsional
3. Breadcrumb
4. Tombol aksi utama di sisi kanan

Contoh tombol aksi utama:

1. Tambah Pengarang
2. Tambah Katalog
3. Tambah Item
4. Tambah Anggota
5. Unggah Aset Digital
6. Export Laporan

Aturan:

1. Tombol aksi utama ditempatkan konsisten.
2. Tombol hanya tampil bila permission mengizinkan.
3. Jangan menumpuk terlalu banyak tombol primer.

## 16. Standar Halaman Dashboard

### 16.1 Dashboard Admin

Dashboard admin wajib memuat:

1. Kartu statistik
2. Quick action
3. Ringkasan aktivitas
4. Ringkasan operasional sesuai role

Widget minimum per role:

1. Super Admin: user aktif, log aktivitas, ringkasan katalog, ringkasan sistem
2. Admin Perpustakaan: total katalog, total item, total anggota, total pinjaman aktif
3. Pustakawan: total katalog, total item, aset digital, quick action katalog
4. Petugas Sirkulasi: pinjaman aktif, keterlambatan, quick action pinjam dan kembali
5. Operator Repositori: total aset digital, OCR pending, index pending
6. Pimpinan: statistik koleksi, sirkulasi, anggota, akses digital

### 16.2 Kartu Statistik

Standar kartu statistik:

1. Nilai utama besar
2. Label jelas
3. Icon ringan bila perlu
4. Warna netral atau mengikuti status
5. Tidak terlalu ramai

## 17. Standar Halaman Daftar

Semua halaman daftar admin wajib mengikuti pola yang sama.

### 17.1 Struktur Halaman Daftar

Komponen wajib:

1. Page header
2. Filter bar
3. Search box
4. Tabel data
5. Empty state
6. Pagination
7. Informasi jumlah data

### 17.2 Search Box

Aturan:

1. Tampil di area atas tabel
2. Placeholder harus spesifik
3. Tidak terlalu panjang
4. Input search dapat dipakai Enter atau tombol cari
5. Search tidak boleh menutup filter lain

Contoh placeholder:

1. Cari nama pengarang
2. Cari judul, ISBN, pengarang
3. Cari barcode atau kode inventaris
4. Cari anggota
5. Cari aset digital

### 17.3 Filter Bar

Aturan:

1. Filter relevan dengan modul
2. Tidak terlalu banyak dalam satu baris
3. Gunakan select, date range, atau checkbox bila perlu
4. Tombol reset filter wajib ada bila filter lebih dari satu
5. Filter harus mempertahankan nilai setelah submit

### 17.4 Informasi Jumlah Data

Wajib tampil:

1. Jumlah total data
2. Jumlah data hasil filter bila memungkinkan
3. Posisi pagination

Contoh:
Menampilkan 1 sampai 10 dari 243 data

## 18. Standar Tabel

Tabel admin wajib konsisten.

### 18.1 Struktur Tabel

Komponen:

1. Header tabel
2. Body tabel
3. Aksi per baris
4. Empty state row bila kosong

### 18.2 Prinsip Tabel

1. Header jelas
2. Kolom tidak terlalu padat
3. Aksi ditempatkan konsisten di ujung kanan
4. Gunakan badge untuk status
5. Data utama ditonjolkan di kolom pertama atau kedua
6. Jangan menaruh teks terlalu panjang tanpa pemotongan aman

### 18.3 Kolom Aksi

Aksi per baris yang umum:

1. Detail
2. Edit
3. Hapus
4. Publish
5. Unpublish
6. Block
7. Unblock
8. Ubah status
9. Histori

Aturan:

1. Gunakan tombol kecil atau dropdown aksi bila banyak.
2. Aksi destruktif harus butuh konfirmasi.
3. Aksi harus sesuai permission dan state.

### 18.4 Empty State Tabel

Saat data kosong tampilkan:

1. Ikon ringan
2. Pesan utama
3. Penjelasan singkat
4. Tombol aksi bila relevan

Contoh:
Belum ada data katalog. Silakan tambahkan katalog baru.

## 19. Standar Pagination

Aturan pagination:

1. Pilihan per page: 10, 25, 50, 100
2. Tampilkan nomor halaman
3. Tampilkan informasi jumlah data
4. Pertahankan filter dan search saat pindah halaman

## 20. Standar Form

### 20.1 Prinsip Form

1. Ringkas
2. Terstruktur
3. Validasi jelas
4. Tidak membingungkan
5. Mendukung keyboard navigation

### 20.2 Struktur Form

Komponen:

1. Label field
2. Input field
3. Keterangan bantuan bila perlu
4. Pesan error dekat field
5. Tombol simpan
6. Tombol batal atau kembali

### 20.3 Field Wajib

Aturan:

1. Field wajib diberi penanda jelas
2. Penanda tidak berlebihan
3. Pesan validasi harus mudah dipahami

### 20.4 Grouping Form

Untuk form panjang, gunakan section atau card:

1. Metadata utama
2. Relasi master data
3. Status dan publikasi
4. File dan lampiran
5. Catatan tambahan

### 20.5 Tombol Form

Standar:

1. Tombol utama: Simpan atau Update
2. Tombol sekunder: Batal atau Kembali
3. Tombol tambahan bila relevan: Simpan dan lanjut item

Aturan:

1. Tombol utama di posisi konsisten.
2. Tombol destruktif tidak dicampur dengan tombol simpan.
3. Tombol disabled saat submit bila perlu.

## 21. Standar Validasi Visual

Validasi visual wajib mengikuti 16_VALIDATION_RULES.md

Aturan UI:

1. Field error diberi highlight jelas
2. Pesan error tampil dekat field
3. Error global dapat ditampilkan di atas form
4. Input lama tetap tampil setelah gagal validasi kecuali password dan file
5. Error tidak boleh disembunyikan

## 22. Standar Halaman Detail

### 22.1 Struktur Umum

Komponen:

1. Page header
2. Ringkasan objek
3. Informasi inti
4. Section relasi
5. Badge status
6. Tombol aksi lanjutan
7. Tombol kembali

### 22.2 Detail Katalog

Wajib menampilkan:

1. Judul
2. Pengarang
3. Penerbit
4. Bahasa
5. Klasifikasi
6. Jenis koleksi
7. Abstrak
8. Status publikasi
9. Daftar item fisik
10. Daftar aset digital

### 22.3 Detail Item

Wajib menampilkan:

1. Barcode
2. Kode inventaris
3. Judul induk
4. Lokasi rak
5. Kondisi
6. Status item
7. Histori item ringkas

### 22.4 Detail Anggota

Wajib menampilkan:

1. Nomor anggota
2. Nama
3. Tipe anggota
4. Fakultas
5. Program studi
6. Status aktif
7. Status blokir
8. Histori ringkas

### 22.5 Detail Aset Digital

Wajib menampilkan:

1. Judul aset
2. Tipe aset
3. Record induk
4. Status publikasi
5. Rule akses
6. OCR status
7. Index status
8. Preview PDF bila diizinkan

## 23. Standar Badge Status

Status harus divisualkan dengan badge yang konsisten.

### 23.1 Badge User

1. Active = hijau
2. Inactive = abu atau merah lembut

### 23.2 Badge Member

1. Active = hijau
2. Inactive = abu
3. Blocked = merah

### 23.3 Badge Bibliographic Record

1. Draft = abu
2. Published = hijau
3. Unpublished = kuning atau abu gelap
4. Archived = gelap

### 23.4 Badge Item

1. Available = hijau
2. Loaned = biru
3. Damaged = merah
4. Lost = merah tua
5. Repair = kuning
6. Inactive = abu

### 23.5 Badge Loan

1. Active = biru
2. Returned = hijau
3. Cancelled = abu
4. Overdue = merah, sebagai badge tambahan derived state

### 23.6 Badge Fine

1. Outstanding = merah
2. Settled = hijau
3. Waived = biru muda
4. Cancelled = abu

### 23.7 Badge Digital Asset

1. Draft = abu
2. Published = hijau
3. Unpublished = kuning
4. Archived = abu tua
5. OCR success = hijau
6. OCR failed = merah
7. Indexed = hijau
8. Failed indexing = merah

## 24. Standar Modal

Modal dipakai untuk aksi singkat.

Modal yang direkomendasikan:

1. Konfirmasi hapus
2. Konfirmasi publish atau unpublish
3. Reset password user
4. Block atau unblock member
5. Ubah status item
6. Retry queue job

Aturan:

1. Modal tidak dipakai untuk form panjang.
2. Modal harus punya tombol batal dan konfirmasi.
3. Modal destruktif harus jelas.
4. Fokus keyboard harus benar.

## 25. Standar Flash Message

Setelah aksi berhasil atau gagal, gunakan flash message.

Kategori:

1. Success
2. Error
3. Warning
4. Info

Aturan:

1. Pesan ringkas
2. Terkait aksi terbaru
3. Tidak terlalu panjang
4. Dapat ditutup
5. Tidak menutupi konten terlalu lama

Contoh:

1. Data berhasil disimpan.
2. Katalog berhasil diterbitkan.
3. Anggota berhasil diblokir.
4. File PDF gagal diunggah.

## 26. Standar Halaman Sirkulasi

Modul sirkulasi adalah area operasional cepat. UX harus lebih efisien dari modul lain.

### 26.1 Halaman Peminjaman

Wajib memuat:

1. Input anggota yang mudah dicari
2. Informasi kelayakan anggota
3. Input atau scan barcode item
4. Daftar item yang akan dipinjam
5. Informasi due date
6. Tombol proses pinjam yang menonjol

Aturan UX:

1. Fokus input harus mendukung kerja cepat.
2. Error harus muncul jelas.
3. Informasi status anggota dan item harus mudah dibaca.

### 26.2 Halaman Pengembalian

Wajib memuat:

1. Input atau scan barcode item
2. Informasi pinjaman aktif
3. Informasi keterlambatan
4. Informasi denda
5. Pilihan kondisi item saat kembali
6. Tombol proses kembali

### 26.3 Halaman Perpanjangan

Wajib memuat:

1. Daftar pinjaman yang bisa diperpanjang
2. Informasi due date lama
3. Informasi due date baru
4. Tombol perpanjang

## 27. Standar Halaman Digital Repository

### 27.1 Halaman Unggah Aset

Wajib memuat:

1. Pilihan bibliographic record
2. Input metadata file
3. Upload PDF
4. Status publikasi
5. Rule akses
6. Opsi embargo bila perlu
7. Tombol simpan

### 27.2 Preview PDF

Preview PDF memakai PDF.js.

Aturan:

1. Viewer harus stabil.
2. File publik dan privat harus dibedakan oleh backend access control.
3. Preview tidak boleh membocorkan URL storage mentah.
4. Tampilkan metadata singkat di atas atau samping viewer.

### 27.3 Halaman OCR dan Reindex

Wajib memuat:

1. Status OCR
2. Status indexing
3. Tombol run OCR
4. Tombol reindex
5. Log ringkas hasil proses

## 28. Standar Halaman Laporan

Setiap halaman laporan wajib memuat:

1. Filter periode
2. Filter domain yang relevan
3. Ringkasan total
4. Tabel atau grafik
5. Tombol export bila diizinkan

Aturan:

1. Grafik tidak wajib di semua laporan.
2. Data tabel tetap harus tersedia.
3. Informasi summary diletakkan di bagian atas.

## 29. Standar Halaman Audit dan Monitoring

Halaman audit dan monitoring harus sederhana dan data centric.

### 29.1 Audit Log

Komponen:

1. Filter user
2. Filter modul
3. Filter aksi
4. Filter tanggal
5. Tabel audit
6. Detail audit

### 29.2 Queue Monitor

Komponen:

1. Daftar job
2. Status job
3. Error ringkas
4. Tombol retry bila diizinkan

Aturan:

1. Tampilan tidak perlu terlalu dekoratif.
2. Fokus pada keterbacaan dan aksi administratif.

## 30. Standar OPAC Publik

### 30.1 Prinsip OPAC

1. Search first
2. Mudah dipahami pengguna umum
3. Fokus pada hasil pencarian
4. Tidak terlalu banyak menu
5. Ringan dan cepat

### 30.2 Header OPAC

Komponen:

1. Logo atau nama sistem
2. Beranda
3. Katalog atau Pencarian
4. Tentang
5. Bantuan

### 30.3 Hero Search

Beranda OPAC wajib memuat search bar utama yang menonjol.

Komponen:

1. Input pencarian besar
2. Tombol cari
3. Placeholder yang jelas
4. Shortcut kategori bila perlu

Contoh placeholder:
Cari judul, pengarang, subjek, atau ISBN

### 30.4 Hasil Pencarian OPAC

Setiap hasil wajib menampilkan:

1. Cover kecil bila ada
2. Judul
3. Pengarang
4. Tahun
5. Jenis koleksi
6. Ketersediaan fisik
7. Ketersediaan digital
8. Tombol detail

### 30.5 Detail Koleksi OPAC

Wajib menampilkan:

1. Cover
2. Metadata bibliografi
3. Status ketersediaan item fisik
4. Lokasi rak bila layak tampil
5. Informasi aset digital
6. Tombol preview bila diizinkan

### 30.6 Halaman Tentang dan Bantuan

Harus sederhana, informatif, dan tidak panjang berlebihan.

## 31. Standar Responsif

Target responsif utama:

1. Desktop
2. Laptop
3. Tablet

Aturan:

1. Sidebar admin collapse di layar kecil.
2. Tabel besar harus mendukung scroll horizontal aman.
3. Form harus tetap rapi di layar kecil.
4. OPAC publik harus tetap nyaman di ponsel, walau fase 1 fokus utama desktop dan laptop.

## 32. Standar Aksesibilitas Dasar

PERPUSQU wajib memenuhi aksesibilitas dasar berikut:

1. Kontras teks memadai.
2. Label form jelas.
3. Tombol punya teks jelas.
4. Status tidak hanya dibedakan warna, gunakan teks badge.
5. Fokus keyboard terlihat.
6. Link dan tombol dapat dibedakan jelas.
7. Error form dapat dikenali cepat.

## 33. Standar Icon

Aturan icon:

1. Gunakan icon seperlunya.
2. Icon membantu pemahaman, bukan dekorasi.
3. Icon konsisten pada menu dan aksi.
4. Jangan memakai terlalu banyak style icon berbeda.

## 34. Standar Breadcrumb

Aturan:

1. Semua halaman admin selain dashboard harus punya breadcrumb.
2. Breadcrumb mengikuti menu nyata.
3. Halaman detail dan edit harus menyertakan parent page.

Contoh:

1. Dashboard / Katalog / Daftar Katalog
2. Dashboard / Katalog / Detail Katalog
3. Dashboard / Koleksi Fisik / Edit Item
4. Dashboard / Sirkulasi / Pengembalian

## 35. Standar Loading State

Untuk aksi yang butuh waktu:

1. Tombol submit bisa disabled
2. Tampilkan spinner ringan
3. Tampilkan teks proses bila perlu

Contoh:

1. Menyimpan...
2. Memproses pengembalian...
3. Mengunggah file...
4. Menjalankan OCR...

Aturan:

1. Loading state tidak boleh menghilangkan konteks halaman.
2. Jangan memakai animasi berlebihan.

## 36. Standar Empty State

Empty state wajib ada untuk:

1. List kosong
2. Hasil pencarian kosong
3. Histori kosong
4. Laporan kosong
5. Audit kosong

Elemen:

1. Judul singkat
2. Penjelasan singkat
3. Aksi berikutnya bila relevan

## 37. Standar Error State

Error state harus dibedakan dari empty state.

### 37.1 Form Error

1. Error di dekat field
2. Ringkasan error bila perlu

### 37.2 Page Error

1. Halaman 403
2. Halaman 404
3. Halaman 419
4. Halaman 500

Aturan:

1. Pesan ramah dan jelas
2. Ada tombol kembali atau kembali ke dashboard
3. Branding tetap konsisten

## 38. Standar Aksi Destruktif

Aksi destruktif meliputi:

1. Hapus
2. Nonaktifkan
3. Block
4. Unpublish
5. Archive
6. Cancel tertentu

Aturan:

1. Wajib konfirmasi
2. Warna tombol bahaya konsisten
3. Penjelasan singkat efek aksi
4. Tidak boleh terlalu mudah terklik tanpa sengaja

## 39. Standar Halaman Berdasar Role

### 39.1 Super Admin

Fokus UI:

1. Monitoring
2. Manajemen akses
3. Pengaturan sistem
4. Audit

### 39.2 Admin Perpustakaan

Fokus UI:

1. Operasional lengkap
2. Katalog
3. Koleksi
4. Anggota
5. Sirkulasi
6. Repositori
7. Laporan

### 39.3 Pustakawan

Fokus UI:

1. Katalog
2. Koleksi
3. Master data tertentu
4. Repositori terbatas

### 39.4 Petugas Sirkulasi

Fokus UI:

1. Peminjaman
2. Pengembalian
3. Perpanjangan
4. Pinjaman aktif
5. Denda

### 39.5 Operator Repositori Digital

Fokus UI:

1. Aset digital
2. OCR
3. Reindex
4. Rule akses

### 39.6 Pimpinan Perpustakaan

Fokus UI:

1. Dashboard
2. Laporan
3. View only pada modul tertentu

### 39.7 Pengguna OPAC Publik

Fokus UI:

1. Search
2. Detail koleksi
3. Preview aset publik

## 40. Standar Komponen Reusable

Komponen reusable minimum:

1. Page header
2. Breadcrumb
3. Alert message
4. Empty state
5. Status badge
6. Confirm modal
7. Data table toolbar
8. Pagination block
9. Form input text
10. Form select
11. Form textarea
12. File input
13. Date input
14. Card statistik

Aturan:

1. Semua komponen harus dipakai konsisten.
2. Jangan membuat variasi visual yang tidak perlu untuk fungsi yang sama.

## 41. Standar Naming UI

Nama label, judul, tombol, dan pesan harus:

1. Menggunakan Bahasa Indonesia
2. Singkat
3. Jelas
4. Konsisten dengan menu dan field bisnis

Contoh yang dianjurkan:

1. Simpan
2. Update
3. Kembali
4. Detail
5. Histori
6. Terbitkan
7. Batalkan Publikasi
8. Ubah Status

## 42. Standar Interaksi Permission dan State

UI wajib mematuhi:

1. Tombol hanya tampil jika permission mengizinkan
2. Tombol hanya aktif jika state mengizinkan
3. Halaman hanya bisa diakses jika route mengizinkan
4. Badge status harus membantu user memahami kenapa tombol tidak ada atau tidak aktif

Contoh:

1. Tombol Publish tidak tampil untuk role tanpa permission catalog.publish
2. Tombol Loan tidak tampil untuk item selain available
3. Tombol Renew tidak tampil untuk loan returned

## 43. Standar Interaksi OPAC dan Aset Digital

1. Preview publik hanya tampil jika asset boleh diakses publik.
2. Jika asset tidak boleh dipreview, tampilkan informasi yang jelas.
3. Detail record tetap bisa tampil bila record publik.
4. Jangan tampilkan pesan teknis storage ke pengguna publik.

## 44. Standar Konsistensi dengan State Machine

UI wajib selaras dengan 17_WORKFLOW_STATE_MACHINE.md.

Contoh:

1. Bibliographic record draft tidak boleh diberi badge published
2. Item loaned tidak boleh menampilkan tombol pinjam
3. Member blocked harus jelas ditandai
4. OCR failed harus menampilkan opsi retry hanya untuk role yang sah

## 45. Halaman Prioritas UI Fase 1

Halaman yang wajib paling dulu konsisten secara UI:

1. Login
2. Dashboard
3. Daftar Katalog
4. Form Tambah Katalog
5. Detail Katalog
6. Daftar Item
7. Form Tambah Item
8. Daftar Anggota
9. Form Tambah Anggota
10. Peminjaman
11. Pengembalian
12. Pinjaman Aktif
13. Daftar Aset Digital
14. Unggah Aset Digital
15. Dashboard Statistik
16. Audit Log
17. OPAC Beranda
18. OPAC Hasil Pencarian
19. OPAC Detail Koleksi
20. Preview PDF Publik

## 46. Halaman yang Tidak Boleh Menyimpang dari Standar

Halaman berikut wajib paling disiplin terhadap standar ini:

1. Semua halaman list
2. Semua halaman form
3. Peminjaman
4. Pengembalian
5. Daftar aset digital
6. OPAC search
7. OPAC detail
8. Audit log
9. Manajemen akses

## 47. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib bagi:

1. 21_SEARCH_INDEXING_SPEC.md
2. 22_STORAGE_FILE_POLICY.md
3. 23_OCR_AND_DIGITAL_PROCESSING.md
4. 25_REPORTING_SPEC.md
5. 31_TEST_PLAN.md
6. 32_TEST_SCENARIO.md
7. 38_TREE.md
8. 39_TRACEABILITY_MATRIX.md
9. 40_PAGE_BUILD_PRIORITY.md
10. 42_FRONTEND_CHECKLIST.md
11. 45_SMOKE_TEST_CHECKLIST.md
12. 46_UAT_CHECKLIST.md

Aturan:

1. Frontend checklist harus memeriksa seluruh standar ini.
2. Test scenario harus memeriksa konsistensi layout, tabel, form, dan state action.
3. Page build priority harus mengacu pada prioritas UI fase 1 di dokumen ini.

## 48. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. Semua halaman di 10_VIEW_MAP.md punya pola UI yang jelas.
2. Semua aksi di 08_MENU_MAP.md dan 09_ROUTE_MAP.md punya titik tampil UI yang tepat.
3. Semua form di 16_VALIDATION_RULES.md punya penanganan error UI yang jelas.
4. Semua state di 17_WORKFLOW_STATE_MACHINE.md tercermin pada badge dan tombol aksi.
5. Semua role di 07_ROLE_PERMISSION_MATRIX.md menghasilkan tampilan menu dan tombol yang sesuai.
6. Tidak ada layout liar di luar auth, admin, dan OPAC.

## 49. Kesimpulan

Dokumen UI UX Standard ini menetapkan standar visual, pola interaksi, dan konsistensi pengalaman pengguna resmi PERPUSQU secara lengkap dan selaras dengan blueprint 01 sampai 17. Dokumen ini memastikan bahwa semua halaman admin dan OPAC publik memiliki struktur yang seragam, mudah dipahami, efisien digunakan, dan aman terhadap kesalahan operasional. Semua implementasi frontend PERPUSQU wajib merujuk dokumen ini.

END OF 18_UI_UX_STANDARD.md
