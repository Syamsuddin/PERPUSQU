# 41_REUSABLE_UI_ASSETS.md

## 1. Nama Dokumen

Daftar Asset UI Reusable Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint spesifikasi komponen antarmuka yang dapat digunakan berulang (Reusable UI Assets).

### 2.3 Status Dokumen

Resmi, spesifikasi teknis komponen visual untuk menjadi acuan bagi AI Agent (seperti Google Stitch), Frontend Developer, dan UI/UX Designer.

### 2.4 Tujuan Dokumen

Dokumen ini mendata seluruh aset antarmuka (UI assets) yang bersifat dapat digunakan ulang (reusable) atau komponen parsial (partial components) pada lingkungan frontend PERPUSQU. Sebagai turunan dari `18_UI_UX_STANDARD.md`, `19_OPAC_UX_FLOW.md`, dan `10_VIEW_MAP.md`, dokumen ini memastikan konsistensi rancangan sistem desain.

## 3. Tata Letak (Layouts) Utama Terpusat

Sebagai landasan desain UI, aplikasi dilindungi oleh 3 layout terpusat yang harus dipisahkan strukturnya agar bisa dipakai ulang di setiap rute:

1.  **Layout Auth** (`layouts/auth`)
    *   **Konteks**: Dipakai untuk halaman seperti Login.
    *   **Komponen Pembentuk**: Branding/Logo PERPUSQU tengah, Container formulir kartu (Card), Notifikasi error global, Footer ringan.
2.  **Layout Admin** (`layouts/admin`)
    *   **Konteks**: Dipakai di fungsionalitas internal (Dashboard, Katalog, Sirkulasi, dll).
    *   **Komponen Pembentuk**: Sidebar responsif, System Header (navbar), Breadcrumb, Notifikasi Flash (Alert), Area Konten Utama, Footer aplikasi internal.
3.  **Layout OPAC Publik** (`layouts/opac`)
    *   **Konteks**: Dipakai untuk pengguna umum/front-end publik (Pencarian Katalog, Detail Rekod).
    *   **Komponen Pembentuk**: Header OPAC (sederhana, navigasi minimal), Hero Search Area (Pencarian utama), Area Konten Utama, Footer institusi publik.

## 4. Daftar Partial UI Components Master

Di area pengembangan antarmuka reaktif (Livewire/Blade/Bootstrap), komponen parsial (`components/*`) akan dikelompokkan ke dalam kategori berikut:

### 4.1 CUI-01: Alerts & Notifications Area

*   **Success Alert** (`alerts/success`): Kotak konfirmasi sukses (warna identitas hijau) untuk indikator setelah tindakan seperti menyimpan/menambahkan anggota. Dismissible (bisa ditutup).
*   **Error Alert** (`alerts/error`): Kotak gagal validasi global (warna merah lembut) terkait kegagalan jaringan atau server.
*   **Flash Toast**: Pesan toast ringan di pojok kanan agar layar tidak terganggu.

### 4.2 CUI-02: Forms & Input Controls

Semua komponen Input harus menyisipkan dukungan bawaan untuk menampilkan pesan validasi error (Validation inline).

*   **Text Input** (`forms/input-text`): Komponen base textbox (Barcode, Nama, dll).
*   **Select Input** (`forms/input-select`): Dropdown tunggal atau multi-pilihan (Fakultas, Jenis Koleksi).
*   **Textarea Input** (`forms/input-textarea`): Untuk isian multi-baris (Abstrak, Sinopsis, Catatan).
*   **File/Upload Input** (`forms/input-file`): Wrapper kustom file upload (Cover buku, PDF aset digital).
*   **Date Input** (`forms/input-date`): Untuk Tanggal terbit, Jatuh tempo.
*   **Checkbox / Radio Input** (`forms/input-checkbox`): Opsi biner.

### 4.3 CUI-03: Data Tables & Data Grids

Blok ini menjadi senjata utama di area admin yang menampilkan tabel data master & transaksional.

*   **Data Table Header / Toolbar** (`tables/data-table-header`): Berisi modul Search Box di kanan, slot filter komponen di bagian atas/kiri tabel, dan tombol utama CRUD di page-header atasnya.
*   **Pagination Block** (`tables/data-table-pagination`): Standarisasi posisi page info (halaman 1 dari 10) beserta tombol Next/Prev.
*   **Empty State Block** (`tables/empty-state`): Tampilan ilustrasi (atau icon ukuran besar), teks pemberitahuan, dan tombol "Tambah Data" jika array/table data kembalian bernilai 0.

### 4.4 CUI-04: Typography & Page Layout Builders

*   **Page Header** (`layouts/page-header`): Berisi Judul Modul Halaman, Deskripsi Halaman (opsional), serta posisi mutlak tombol tindakan-utama (CTAs) di sebelah kanan.
*   **Breadcrumb Nav** (`layouts/breadcrumb`): Komponen pelacakan langkah kembali hirarki navigasi. Selalu di posisi bawah/atas Page Header.
*   **Card Container Dasar**: Komponen card polos sebagai wrapper konten form atau visualisasi.

### 4.5 CUI-05: Modals

Komponen dialog pop-over di tengah layar, agar sistem tidak terlalu sering me-reload halaman untuk fungsi pendek.

*   **Confirm Delete Modal** (`modals/confirm-delete`): Pertanyaan kepastian hapus aset destruktif secara standar.
*   **Confirm Action Modal** (`modals/confirm-action`): Untuk menyetujui mutasi status yang memiliki efek ganda (Misal: Batalkan Pinjaman, Setujui Publikasi).

### 4.6 CUI-06: Badges & Status Indikator

Komponen pil warna yang merangkum kondisi (State) sebuah entity, terpadu dengan warna dari _UI UX Standard_.

*   **Status Badge** (`badges/status-badge`):
    *   _User_: Active (Hijau), Inactive (Abu).
    *   _Member_: Active (Hijau), Inactive (Abu), Blocked (Merah). Derived: Active Ready, Active Blocked, Inactive Unblocked, Inactive Blocked.
    *   _Bibliographic Record_: Draft (Abu), Published (Hijau), Unpublished (Kuning/Abu Gelap), Archived (Gelap). Derived visibility: Public Visible, Internal Only.
    *   _Physical Item_: Available (Hijau), Loaned (Biru), Damaged (Merah), Lost (Merah Tua), Repair (Kuning), Inactive (Abu).
    *   _Loan_: Active (Biru), Returned (Hijau), Cancelled (Abu). Derived: Overdue (Merah) — badge tambahan saat Active dan melewati due date.
    *   _Fine_: Outstanding (Merah), Settled (Hijau), Waived (Biru Muda), Cancelled (Abu).
    *   _Digital Asset Publication_: Draft (Abu), Published (Hijau), Unpublished (Kuning), Archived (Abu Tua).
    *   _Digital Asset Access_: Public Accessible (Hijau), Private Internal (Abu), Public Embargoed (Kuning), Restricted By Rule (Oranye).
    *   _OCR Status_: Not Requested (Abu), Queued (Biru Muda), Processing (Kuning), Success (Hijau), Failed (Merah).
    *   _Search Index_: Pending (Abu), Queued (Biru Muda), Processing (Kuning), Indexed (Hijau), Failed (Merah).

### 4.7 CUI-07: Data Visualizations

*   **Stat Card** (`cards/stat-card`): Kartu statistik berbentuk grid minimal untuk panel Dasbor (Total Buku, Total Pinjaman Aktif). Terdiri atas Angka besar utama, Label text di bawah, serta Icon tembus pandang atau minim.

## 5. Komponen Master Untuk OPAC Spesifik (UI OPAC Reusables)

Aset-aset UI spesifik publik (karena perilaku antarmukanya berkonsep "Search First"):

*   **Hero Search Bar**: Komponen formulir search dengan input berukuran *large* beserta Placeholder yang kuat, posisi terpusat (Center).
*   **OPAC Record List Card**: Card baris linear atau grid untuk hasil pencarian. Terdiri dari Cover miniatur katalog, Judul Dominan, Pengarang/Tahun, Jenis Koleksi, Badge Ketersediaan (Fisik) dan (Digital), dan Action/Link Detail.
*   **OPAC Filter Toggle Block**: Kumpulan Checkbox per sub-klasifikasi, dan dukungan penghapusan Filter (*Clear Filters*).
*   **PDF.js Public Viewer Component**: Viewer UI khusus (dilengkapi perlindungan access-rules) yang merender kanvas halaman PDF untuk pratinjau konten digital. Bebas kontrol unduh kecuali diperbolehkan rules.

## 6. Referensi Acuan (Untuk Model AI Pembentuk Layout)

Bagi AI Agent UI/UX designer, aset Reusable harus mengacu pada arahan desain berikut:

*   **Library**: Menggunakan spesifikasi dan properti *Bootstrap 5.3* (sebagai framework stylesheet panduan umum). Kompatibel secara utilitas.
*   **Konsistensi Visual**: *Border radius* kartu, tombol, maupun form control harus memiliki seragam (baik lengkungan besar maupun presisi modern tajam).
*   **Interaksi Wajib**: Komponen tabel maupun form tidak boleh kehilangan fitur responsif *Scroll Horizontal* atau penyesuaian susunan Grid responsif standar kolom hingga skala ukuran Tablet (`md`) maupun mobile (`sm`).
*   **Color Mapping Constraint**: Hindari palet tak terduga. Sesuaikan rujukan dokumen 18 dengan _Primary Color_ dominan perpustakaan, lalu _Danger_, _Warning_, _Success_ dan _Neutral/Grey_.

END OF 41_REUSABLE_UI_ASSETS.md
