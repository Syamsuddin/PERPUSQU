# 42_UI_STITCH_PROMPTS.md

## 1. Nama Dokumen
Master Prompt UI Design untuk AI Agent Google Stitch - Sistem PERPUSQU

## 2. Definisi Dokumen
Dokumen ini berisi *System Prompt* dan *Page-Specific Prompts* yang dirancang khusus untuk diberikan kepada AI Agent pembuat UI (terutama Google Stitch / Cursor / AI Code Generator lainnya). Prompt ini mengonversi spesifikasi blueprint menjadi instruksi generatif yang presisi untuk merakit tampilan antarmuka.

---

## 3. PROMPT UMUM (GENERAL SYSTEM PROMPT)
*Gunakan prompt ini sebagai `System Prompt` atau instruksi awal ruang obrolan (context initialization) sebelum meminta AI Stitch membuat halaman apa pun.*

**[Copy start]**
```text
You are an Expert UI/UX Developer and Frontend Engineer building the 'PERPUSQU' Hybrid Campus Library System. 
Your UI stack must strictly use: Blade Templating, Bootstrap 5.3 (via Vite), and Vanilla CSS/JS for custom styling (avoid Tailwind and heavy jQuery unless absolutely necessary).

GENERAL DESIGN RULES:
1. Vibe & Aesthetic: Clean, modern, professional, slightly formal but accessible. Priority is given to functional readability and fast operational data entry. 
2. Color Palette: Primary is Campus Blue/Dark Blue. Backgrounds are clean white or very light gray. Use standardized semantic colors for states (Success: Green, Warning: Yellow/Orange, Danger: Red, Neutral/Draft: Gray).
3. Layout Structures:
   - Auth Structure: Clean centered card layout, minimalist.
   - Admin Structure: Fixed sidebar with collapse/expand toggle, top navbar with breadcrumbs, clear page header with CTAs on the right, large spacious content area.
   - OPAC Public Structure: Search-first approach. Dominant center search bar, minimalist navigation, fast-loading, clean result cards (avoid dashboard-look).
4. Reusability: Always use standard components: `alerts`, `badge`, `datatable skeleton` with top search & filter, `pagination block`, and `empty state` with a generic icon and text.
5. Interactive Elements: Add subtle hover effects to buttons and table rows to make the app feel alive. Avoid excessive animations. Ensure contrast ratio is accessible.

When generating a page, focus on implementing realistic form controls (with standard floating labels or clear labels), clear validation text placeholders, and realistic dummy data in tables to showcase the layout perfectly.
```
**[Copy end]**

---

## 4. PROMPT SPESIFIK: HALAMAN PRIORITAS FASE 1
*Gunakan prompt di bawah ini satu per satu (per chat/per request) kepada Google Stitch untuk merender halaman secara akurat.*

### 4.1 Halaman Login
**Prompt:**
```text
Build the 'Login Page' (VW-AUTH-001) for PERPUSQU using the Auth Layout.
Requirements:
1. Centered login card on a subtle, clean background (perhaps a blurred library image or soft blue gradient).
2. Contains the PERPUSQU logo/branding prominently.
3. Form fields: Email/Username, Password (with eye toggle for visibility), and a "Remember Me" checkbox.
4. Button: Large primary "Masuk / Login" button.
5. Error state: Add a dummy error message slot above the form (e.g., alert-danger "Kredensial tidak valid").
6. Keep the footer minimalistic (System Name & Year).
```

### 4.2 Dashboard Utama Admin / Statistik
**Prompt:**
```text
Build the 'Admin Dashboard Page' (VW-DASH-001) for PERPUSQU using the Admin Layout.
Requirements:
1. Page Header: Title "Dashboard", Breadcrumb (Home > Dashboard), and an "OPAC Shortcut" button on the right.
2. Stat Cards Grid (Top): Create 4 responsive summary cards (Total Katalog, Total Item Fisik, Pinjaman Aktif, Total Aset Digital). Use nice, subtle icons and clean typography.
3. Quick Actions: A row of outline buttons below stats (Pinjam Baru, Tambah Katalog, Kembalikan Buku).
4. Data section: Split layout. Left: "Aktivitas Sirkulasi Terkini" (a compact list/table of latest loans/returns). Right: "Statistik Koleksi" (a placeholder area for an analytical donut chart).
5. Ensure empty states are gracefully handled if data is zero.
```

### 4.3 Daftar Katalog (Bibliographic Records)
**Prompt:**
```text
Build the 'Catalog Index Page' (VW-CAT-001) for PERPUSQU.
Requirements:
1. Page Header: Title "Daftar Katalog", Button "Tambah Katalog" prominently on the right.
2. Filter Bar: Search input (Cari judul, pengarang, ISBN), and dropdown filters (Jenis Koleksi, Bahasa, Tahun, Status Publikasi). Place a "Reset" button.
3. Data Table: Columns: Judul (make it bold/primary), Pengarang, ISBN, Jenis, Status (Draft/Published/Unpublished/Archived badges), Aksi (Detail, Edit dropdown).
4. Include realistic dummy rows demonstrating different collection types (Buku, Jurnal) and states.
5. Add a pagination block at the bottom right, and "Showing X to Y of Z entries" on the bottom left.
```

### 4.4 Form Tambah Katalog Utama
**Prompt:**
```text
Build the 'Create Catalog Form Page' (VW-CAT-002) for PERPUSQU.
Requirements:
1. Form spanning a card, divided into logical sections using headers or horizontal rules.
2. Section 1 (Metadata Utama): Judul (text, required), ISBN (text), Jenis Koleksi (select).
3. Section 2 (Relasi & Taksonomi): Pengarang (multi-select placeholder), Penerbit (select), Bahasa (select), Subjek (multi-select), Tahun Terbit (number).
4. Section 3 (Deskripsi): Abstrak / Sinopsis (textarea, 4 rows).
5. Upload Area: A clean drag-and-drop or styled file input for the "Cover Image" with note (Max 2MB, JPG/PNG).
6. Footer Actions: "Batal" (outline), "Simpan Draft" (secondary), "Simpan & Lanjut Tambah Item" (primary). 
7. Show inline validation error state on the 'Judul' input to demonstrate validation design.
```

### 4.5 Detail Katalog (Show Bibliographic)
**Prompt:**
```text
Build the 'Catalog Detail Page' (VW-CAT-003) for PERPUSQU.
Requirements:
1. Split layout: Left column (narrow) for Cover Image and Publication Status Badge. Right column (wide) for Metadata.
2. Metadata area: Large readable Title, under it Author & Publisher info. Use a clean definition list (`<dl>`) or table for detailed metadata (ISBN, Tahun, Klasifikasi, Subjek).
3. Below metadata, create a Tabs interface:
   - Tab 1: "Item Fisik" (Shows a compact table of related physical copies and their conditions).
   - Tab 2: "Aset Digital" (Shows linked PDFs and their OCR/Visibility status).
4. Page Header Actions: "Kembali", "Edit Katalog". In the Tab areas, put "Tambah Item" and "Unggah Aset" buttons.
```

### 4.6 Daftar Item Fisik (Eksemplar)
**Prompt:**
```text
Build the 'Physical Item Index Page' (VW-COL-001) for PERPUSQU.
Requirements:
1. Page Header: Title "Item Fisik Koleksi".
2. Filter Bar: Search (Barcode/Kode Inventaris), Lokasi Rak (Select), Status Ketersediaan (Select).
3. Data Table: Columns: Barcode, Judul Induk, Lokasi Rak, Kondisi, Status (Available/Loaned/Damaged), Aksi.
4. Implement semantic status badges (Green = Available, Blue = Loaned, Red = Damaged).
```

### 4.7 Form Tambah Item Fisik
**Prompt:**
```text
Build the 'Create Physical Item Page' (VW-COL-002) for PERPUSQU.
Requirements:
1. Notice area at top displaying the selected Parent Catalog Title.
2. Form fields: Barcode (text with "Generate Auto" small button), Kode Inventaris (text), Lokasi Rak (select), Kondisi (select).
3. CTAs: Simpan, Batal. 
4. Keep the layout narrow, perhaps in a centered card to avoid stretching inputs too wide.
```

### 4.8 Daftar Anggota
**Prompt:**
```text
Build the 'Member Index Page' for PERPUSQU.
Requirements:
1. Similar list structure to previous index pages. 
2. Search input (Nama/NIM/NIDN).
3. Columns: ID Anggota, Nama, Tipe (Mahasiswa/Dosen), Prodi, Status. 
4. Badges: Active (Green), Blocked (Red).
5. Actions: Detail, Edit, Block (Danger Action).
```

### 4.9 Form Tambah Anggota
**Prompt:**
```text
Build the 'Create Member Form Page' for PERPUSQU.
Requirements:
1. Standard form layout: Nama Lengkap, Nomor Induk (NIM/NIDN), Email.
2. Tipe Anggota (Select), Fakultas (Select), Prodi (Select).
3. Status Awal (Radio button: Aktif / Tidak Aktif).
4. Save and Cancel buttons.
```

### 4.10 Sirkulasi - Peminjaman (Fast Operations)
**Prompt:**
```text
Build the 'Circulation: New Loan Page' for PERPUSQU. This page must prioritize extreme speed and UX efficiency.
Requirements:
1. Two-pane layout. 
2. Pane 1 (Member Detail): Input to scan "ID Anggota". Once "scanned", display a robust member card showing photo, name, quota available, and active fines (if any). If there are fines, show a warning alert.
3. Pane 2 (Item Cart): Input to scan "Barcode Buku". Below it, a table acting as a 'Cart' showing scanned books, Title, and Due Date calculation. 
4. Large, prominent "Proses Peminjaman (F9)" primary button at the bottom of the Cart.
```

### 4.11 Sirkulasi - Pengembalian & Denda
**Prompt:**
```text
Build the 'Circulation: Return Library Item Page' for PERPUSQU.
Requirements:
1. Single focus input: Large "Scan Barcode Item" at the center-top.
2. Upon scanning, display a prominent Card representing the active loan: Member Name, Book Title, Loan Date, Due Date.
3. Overdue Logic: If overdue, display a noticeable Red/Orange alert box stating "Terlambat X Hari" and "Estimasi Denda: Rp. Y".
4. Action area: Dropdown for "Kondisi saat kembali" (Baik, Hilang, Rusak).
5. Large "Proses Pengembalian" button. Use high-contrast design to avoid operational errors.
```

### 4.12 Sirkulasi - Daftar Pinjaman Aktif
**Prompt:**
```text
Build the 'Active Loans Index Page' for PERPUSQU.
Requirements:
1. Focus on monitoring. Filters: Overdue status, Member Type.
2. Data Table: Peminjam, Barcode, Judul, Tanggal Pinjam, Jatuh Tempo, Status (Normal/Overdue Badge).
3. Quick Actions in row: "Perpanjang (Renew)", "Proses Kembali".
```

### 4.13 Repositori Digital - Daftar Aset
**Prompt:**
```text
Build the 'Digital Assets Index Page' for PERPUSQU.
Requirements:
1. Similar to Catalog index but focused on files.
2. Columns: Judul File, Format, Ukuran, Status Visibilitas (Public/Restricted/Embargo), Status OCR (Success/Pending/Failed). Use distinct badges for OCR states.
3. Include a "Run Pending OCR" global utility button in the header.
```

### 4.14 Repositori Digital - Form Unggah Aset
**Prompt:**
```text
Build the 'Upload Digital Asset Form' for PERPUSQU.
Requirements:
1. Target Catalog selector (Autocomplete input design).
2. Large prominent File Dropzone for PDF upload.
3. Settings block: Rule Akses (Select: Publik, Login Required, Restricted), Embargo Date (optional date input).
4. Checkboxes for "Jalankan OCR Otomatis".
5. Save buttons.
```

### 4.15 Audit Log
**Prompt:**
```text
Build the 'System Audit Log Page' (VW-AUD-001) for PERPUSQU.
Requirements:
1. Heavy duty data grid layout. 
2. Filters: Rentang Waktu (Date Range), User (Select), Modul (Select), Aksi (Select).
3. Table: Waktu, User, IP Address, Modul, Aksi, Pesan Singkat, Tombol Detail (JSON payload). Compact rows (small padding) to fit more data on screen.
```

### 4.16 OPAC Publik - Beranda
**Prompt:**
```text
Build the 'OPAC Public Home Page' (VW-OPA-001) for PERPUSQU using the OPAC Public Layout.
Requirements:
1. Visuals: Search-First Hero design. A large full-width or prominent central container with a clean background (library facade blurred).
2. Huge search input with a clear search button. Placeholder: "Cari judul, pengarang, subjek, atau ISBN...".
3. Under the search box, put quick pill-links (Pencarian Populer: Skripsi, Jurnal Komputer, dll).
4. Minimal Top Navbar: Logo, Beranda, Pencarian Lanjut, Bantuan, Login Admin (subtle).
5. Do NOT clutter with sidebars.
```

### 4.17 OPAC Publik - Hasil Pencarian
**Prompt:**
```text
Build the 'OPAC Search Results Page' for PERPUSQU.
Requirements:
1. Navbar with the search bar persistent at the top (smaller than home page hero).
2. Split view layout: 
   - Left Sidebar (25% width): Filters (Tahun Terbit, Jenis Koleksi, Bahasa) using checkboxes.
   - Right Main Area (75% width): Search Result list.
3. Search Result Card: Horizontal layout. Mini cover on left. Details on right: Title (clickable), Author, Year. Below text, show Semantic Badges: "Tersedia Fisik" (Green outline), "Tersedia Digital" (Blue outline).
```

### 4.18 OPAC Publik - Detail Koleksi
**Prompt:**
```text
Build the 'OPAC Public Record Detail Page' for PERPUSQU.
Requirements:
1. Clean layout focusing on the book. 
2. Left: Medium cover image. Right: Title (H1), Metadata list (Pengarang, Penerbit, dll), and Abstract paragraph.
3. Below abstract, two distinctive containers/cards:
   - "Ketersediaan Fisik": Shows Rak "004.1/ABC", Status "Tersedia (2 eksemplar)". 
   - "Akses Digital": If available, a large "Baca Online / Preview PDF" button. If restricted, show "Akses Digital Terbatas".
4. Ensure no internal admin data (like barcodes or exact inventory status codes) is leaked. Keep it user-friendly.
```

### 4.19 OPAC Publik - Preview PDF
**Prompt:**
```text
Build the 'OPAC PDF Preview Viewer Page' for PERPUSQU.
Requirements:
1. Distraction-free interface. Dark or heavy gray background.
2. Very thin top navbar with "Kembali", Book Title, and "Page 1 of X" indicator.
3. The main area is a UI mockup of a PDF canvas (you don't need real PDF.js, just create a white container center-aligned resembling a document page).
4. Bottom floating toolbar for "Zoom In/Out". 
5. No download button unless explicitly told.
```

---

## 5. PROMPT SPESIFIK: MASTER DATA GENERIK
*Untuk sisa halaman Master Data (Penerbit, Jenis Koleksi, Subjek, Lokasi Rak, Fakultas, Prodi, Kondisi Item), instruksi berikut dapat didaur ulang karena formatnya identik.*

**Prompt untuk Master Data List:**
```text
Build a standard 'Master Data Index Page' for the entity [NAMA_ENTITAS, e.g. Penerbit].
Requirements: Uses Admin Layout. Page header with "Tambah [Entitas]". A simple Datatable with columns: ID, Nama, Status Aktif, Aksi (Edit/Hapus). Include Pagination empty state.
```

**Prompt untuk Master Data Form:**
```text
Build a standard 'Master Data Form Page' for the entity [NAMA_ENTITAS].
Requirements: Uses Admin Layout. Simple Card container. Form input for Nama [Entitas], Deskripsi (optional textarea), and Save/Cancel buttons.
```

---

## 6. PROMPT SPESIFIK: MANAJEMEN AKSES & PENGATURAN SISTEM (P1 WAJIB)

### 6.1 Daftar Pengguna (VW-ACC-001)
**Prompt:**
```text
Build the 'User Management Index Page' (VW-ACC-001) for PERPUSQU using the Admin Layout.
Requirements:
1. Page Header: Title "Manajemen Pengguna", Button "Tambah Pengguna" on the right.
2. Filter Bar: Search input (Nama/Email), Filter Role (Select), Filter Status Aktif (Select: Aktif/Nonaktif).
3. Data Table: Columns: Nama, Email, Role, Status (Active/Inactive badge), Aksi (Detail, Edit, Reset Password, Aktivasi/Nonaktif).
4. Reset Password action should open a confirm modal.
5. Standard pagination and empty state.
```

### 6.2 Form Tambah/Edit Pengguna (VW-ACC-002/004)
**Prompt:**
```text
Build the 'User Create/Edit Form Page' (VW-ACC-002) for PERPUSQU.
Requirements:
1. Card form layout. Fields: Nama Lengkap, Email, Password (create only), Role (Select).
2. Status toggle (Aktif/Nonaktif).
3. Buttons: Simpan, Batal.
4. Inline validation error demonstration on Email field.
```

### 6.3 Daftar Role (VW-ACC-005)
**Prompt:**
```text
Build the 'Role Management Index Page' (VW-ACC-005) for PERPUSQU.
Requirements:
1. Page Header: Title "Manajemen Role", Button "Tambah Role".
2. Data Table: Columns: Nama Role, Jumlah User, Aksi (Edit, Kelola Permission).
3. "Kelola Permission" button links to the Permission Matrix page.
```

### 6.4 Form Edit Role & Permission Matrix (VW-ACC-007/008)
**Prompt:**
```text
Build the 'Role Permission Matrix Page' (VW-ACC-008) for PERPUSQU.
Requirements:
1. Page Header: Title "Matriks Permission".
2. Matrix Table: Rows = Permission names (grouped by module: Catalog, Collection, Circulation, etc.). Columns = Role names.
3. Each cell is a checkbox to toggle permission per role.
4. A "Simpan Perubahan" button at the bottom.
5. Filter by Module dropdown at the top to narrow the matrix view.
```

### 6.5 Profil Institusi (VW-SET-001)
**Prompt:**
```text
Build the 'Institution Profile Settings Page' (VW-SET-001) for PERPUSQU.
Requirements:
1. Single card form. Fields: Nama Institusi, Nama Perpustakaan, Alamat (textarea), Kontak (text), Logo Upload (file).
2. Button: Update.
3. Display current logo preview if exists.
```

### 6.6 Aturan Operasional (VW-SET-002)
**Prompt:**
```text
Build the 'Operational Rules Settings Page' (VW-SET-002) for PERPUSQU.
Requirements:
1. Card form with clearly labeled sections.
2. Section 1: Lama Pinjam Default (number, days), Batas Maksimum Pinjam (number).
3. Section 2: Denda Per Hari (number, currency format), Batas Perpanjangan (number).
4. Button: Simpan Pengaturan.
5. Use descriptive help text under each input explaining the business impact.
```

---

## 7. PROMPT SPESIFIK: HALAMAN DETAIL, EDIT, DAN HISTORI

### 7.1 Edit Katalog (VW-CAT-004)
**Prompt:**
```text
Build the 'Edit Catalog Form Page' (VW-CAT-004) for PERPUSQU.
Requirements:
1. Identical structure to Create Catalog (VW-CAT-002) but pre-filled with existing data.
2. Cover Image area shows existing cover with "Ganti Cover" option.
3. Buttons: Batal, Update Katalog.
4. Breadcrumb: Dashboard > Katalog > Detail Katalog > Edit.
```

### 7.2 Detail Item Fisik (VW-COL-003)
**Prompt:**
```text
Build the 'Physical Item Detail Page' (VW-COL-003) for PERPUSQU.
Requirements:
1. Item summary card: Barcode, Kode Inventaris, Judul Induk (linked to catalog detail), Lokasi Rak, Kondisi, Status Badge.
2. Section "Riwayat Transaksi Singkat": Compact table of recent loans/returns for this item.
3. Actions: Edit Item, Lihat Histori Lengkap, Ubah Status (opens confirm modal).
```

### 7.3 Halaman Perpanjangan (VW-CIR-003)
**Prompt:**
```text
Build the 'Loan Renewal Page' (VW-CIR-003) for PERPUSQU.
Requirements:
1. Search input to find active loan by Member or Barcode.
2. Table: Peminjam, Judul, Jatuh Tempo Lama, Jatuh Tempo Baru (calculated), Sisa Perpanjangan.
3. Per-row action: "Perpanjang" button. Disabled if renewal limit reached.
4. Confirmation modal after clicking Perpanjang.
```

### 7.4 Denda dan Keterlambatan (VW-CIR-007)
**Prompt:**
```text
Build the 'Fines Index Page' (VW-CIR-007) for PERPUSQU.
Requirements:
1. Filter: Periode (date range), Status Denda (Outstanding/Settled/Waived/Cancelled).
2. Summary cards at top: Total Outstanding, Total Settled, Total Waived.
3. Data Table: Anggota, Judul Buku, Jumlah Denda, Status Badge (Outstanding = Merah, Settled = Hijau, Waived = Biru, Cancelled = Abu).
4. Actions: Settle, Waive (with confirm modal for each).
```

### 7.5 Detail Aset Digital (VW-DIG-003)
**Prompt:**
```text
Build the 'Digital Asset Detail Page' (VW-DIG-003) for PERPUSQU.
Requirements:
1. File info card: Judul, Tipe File, Ukuran, linked Bibliographic Record.
2. Status area: Publication Status Badge, OCR Status Badge, Index Status Badge, Access Rule indicator.
3. PDF Preview area (embedded viewer or placeholder).
4. Action buttons: Edit Metadata, Run OCR, Reindex, Publish/Unpublish toggle.
```

---

## 8. PROMPT SPESIFIK: ERROR PAGES

**Prompt untuk Error Pages (403/404/419/500):**
```text
Build error pages (403, 404, 419, 500) for PERPUSQU.
Requirements:
1. Centered layout with PERPUSQU branding.
2. Large error code number prominently displayed.
3. Clear, friendly Indonesian message:
   - 403: "Akses Ditolak — Anda tidak memiliki izin untuk halaman ini."
   - 404: "Halaman Tidak Ditemukan — Halaman yang Anda cari tidak tersedia."
   - 419: "Sesi Kedaluwarsa — Silakan muat ulang halaman."
   - 500: "Terjadi Kesalahan Sistem — Silakan coba beberapa saat lagi."
4. Button: "Kembali ke Dashboard" or "Kembali ke Beranda".
5. Keep design consistent with the application's color scheme.
```

---

## 9. PROMPT SPESIFIK: OPAC PUBLIK PELENGKAP

### 9.1 Tentang Perpustakaan (VW-OPA-005)
**Prompt:**
```text
Build the 'About Library Page' (VW-OPA-005) for PERPUSQU using the OPAC Layout.
Requirements:
1. Clean, simple static page.
2. Sections: Nama Institusi, Nama Perpustakaan, Alamat, Kontak, Profil Singkat.
3. Optional: A subtle map embed or campus photo placeholder.
4. Keep consistent with OPAC layout (same header/footer).
```

### 9.2 Bantuan Pencarian (VW-OPA-006)
**Prompt:**
```text
Build the 'Search Help Page' (VW-OPA-006) for PERPUSQU using the OPAC Layout.
Requirements:
1. Step-by-step guide sections using clear cards or accordion:
   - "Cara Mencari Koleksi"
   - "Cara Menggunakan Filter"
   - "Memahami Status Ketersediaan"
   - "Cara Membuka Preview Digital"
2. Use simple icons and short explanatory text.
3. Include a search bar at the top so users can search immediately.
```

---

## 10. PROMPT SPESIFIK: LAPORAN

**Prompt Generik untuk Halaman Laporan (VW-REP-002..007):**
```text
Build the '[NAMA_LAPORAN]' Report Page (VW-REP-XXX) for PERPUSQU.
Requirements:
1. Page Header: Title "[Nama Laporan]".
2. Top Filters: Periode (date range picker), [domain-specific filters].
3. Summary Cards: Key totals related to the report.
4. Data Table: Relevant columns with realistic dummy data.
5. Export Button: "Export PDF" and "Export Excel" on the top right (only visible to roles with reports.export permission).
6. Standard pagination.
```

*Gunakan template di atas dan sesuaikan nama serta filter domain untuk:*
- VW-REP-002: Laporan Koleksi (filter: Jenis Koleksi)
- VW-REP-003: Laporan Anggota (filter: Tipe Anggota, Fakultas)
- VW-REP-004: Laporan Sirkulasi (filter: Petugas, Kategori Anggota)
- VW-REP-005: Laporan Denda (filter: Status Denda)
- VW-REP-006: Laporan Koleksi Populer (filter: Jenis Koleksi, Periode)
- VW-REP-007: Laporan Akses Digital (filter: Tipe Aset, Periode)

END OF 42_UI_STITCH_PROMPTS.md

