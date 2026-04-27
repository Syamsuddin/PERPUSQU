Berikut adalah daftar prompt instruksi teknis satu per satu untuk setiap laman **PERPUSQU** yang telah diklasifikasikan berdasarkan dokumen blueprint. Gunakan prompt ini kepada Google Stitch atau AI Code Generator lainnya untuk menghasilkan kode UI yang presisi.

---

### 1. Area Autentikasi (Layout Auth)

* **VW-AUTH-001: Halaman Login**
    > "Build the 'Login Page' (VW-AUTH-001) for PERPUSQU using the Auth Layout. Requirements: Centered card layout with branding logo, form fields for Email/Username and Password (with eye toggle), and a large primary 'Login' button. Include a placeholder slot for 'alert-danger' error messages above the form."

---

### 2. Area Admin - Dashboard & Profil (Layout Admin)

* **VW-DASH-001: Dashboard Admin**
    > "Build the 'Admin Dashboard' (VW-DASH-001) using the Admin Layout. Requirements: Page Header with Breadcrumb. Include 4 responsive Stat Cards (Total Catalog, Total Items, Active Loans, Digital Assets). Add a 'Quick Actions' section with buttons for 'New Loan' and 'Add Catalog'. Include a split section: Left for 'Recent Activities' table and Right for a collection statistics chart placeholder."
* **VW-PROF-001: Profil Saya**
    > "Build the 'My Profile Page' (VW-PROF-001) using the Admin Layout. Requirements: Display user details (Name, Role, Email, Employee ID) in a clean card format. Add buttons for 'Edit Profile' and 'Change Password'."
* **VW-PROF-003: Ubah Password**
    > "Build the 'Change Password Page' (VW-PROF-003). Requirements: Form with fields for 'Current Password', 'New Password', and 'Confirm New Password'. Use standard validation styling for password mismatch errors."

---

### 3. Area Admin - Master Data (Pola Generic CRUD)

* **VW-MAS (Index): Daftar Master Data (Contoh: Pengarang)**
    > "Build the 'Authors Index Page' (VW-MAS-001) using the Admin Layout. Requirements: Page Header with 'Add Author' CTA. Include a Data Table with columns: ID, Name, and Actions (Edit, Delete). Implement a Top Toolbar with a Search Box and Pagination at the bottom."
* **VW-MAS (Form): Tambah/Edit Master Data**
    > "Build the 'Author Form Page' (VW-MAS-002/003) using the Admin Layout. Requirements: A single card container with input fields for 'Author Name' and 'Notes/Description'. Include 'Save' and 'Cancel' buttons with standard primary/secondary colors."

*(Catatan: Gunakan pola ini untuk Penerbit, Bahasa, Klasifikasi, Subjek, Jenis Koleksi, Lokasi Rak, Fakultas, Program Studi, dan Kondisi Item sesuai kode VW-MAS-004 hingga VW-MAS-030).*

---

### 4. Area Admin - Modul Katalog & Koleksi Fisik

* **VW-CAT-001: Daftar Katalog**
    > "Build the 'Catalog Index Page' (VW-CAT-001). Requirements: Advanced Filter Bar (Collection Type, Year, Publication Status). Data Table columns: Title (Primary Bold), Author, ISBN, Status Badge (Draft/Published), and Actions. Add a 'Add Catalog' button in the page header."
* **VW-CAT-002: Form Tambah Katalog**
    > "Build the 'Create Catalog Form' (VW-CAT-002). Requirements: Divide into sections: 1. Metadata (Title, ISBN, Year), 2. Relations (Publisher Select, Author multi-select), 3. Description (Synopsis textarea). Add a file upload area for 'Cover Image'."
* **VW-CAT-003: Detail Katalog**
    > "Build the 'Catalog Detail Page' (VW-CAT-003). Requirements: Left column for Cover Image, Right column for Metadata details. Below metadata, add a Tabs interface: 'Physical Items' (table of copies) and 'Digital Assets' (list of linked files)."
* **VW-COL-001: Daftar Item Fisik**
    > "Build the 'Physical Items Index' (VW-COL-001). Requirements: Search by Barcode or Inventory Code. Table columns: Barcode, Parent Title, Shelf Location, Condition, and Status Badge (Available/Loaned/Damaged)."
* **VW-COL-002: Form Tambah Item**
    > "Build the 'Create Physical Item Form' (VW-COL-002). Requirements: Parent Catalog info display, Input for Barcode (with auto-generate button), Select Shelf Location, and Select Condition."

---

### 5. Area Admin - Modul Anggota & Sirkulasi

* **VW-MEM-001: Daftar Anggota**
    > "Build the 'Member Index Page' (VW-MEM-001). Requirements: Filters for Member Category and Status (Active/Blocked). Table columns: ID, Name, Type (Student/Staff), and Status Badge."
* **VW-CIR-001: Halaman Peminjaman (Fast UI)**
    > "Build the 'New Loan Page' (VW-CIR-001) for fast operations. Requirements: Two-pane layout. Left: Member Search/Scan with card display for member status and fines alert. Right: Item Cart for scanning multiple book barcodes, displaying Titles and Due Dates. Large 'Process Loan' button."
* **VW-CIR-002: Halaman Pengembalian**
    > "Build the 'Return Item Page' (VW-CIR-002). Requirements: Large central Barcode Scan input. Display Active Loan details (Member, Book, Due Date) upon scan. Show 'Overdue Alert' in red with fine calculation if late. Add a selector for 'Return Condition'."

---

### 6. Area Admin - Modul Repositori Digital

* **VW-DIG-001: Daftar Aset Digital**
    > "Build the 'Digital Assets Index' (VW-DIG-001). Requirements: Table showing File Title, Type, OCR Status Badge (Success/Pending/Failed), and Access Rule (Public/Restricted)."
* **VW-DIG-002: Unggah Aset Digital**
    > "Build the 'Upload Digital Asset Form' (VW-DIG-002). Requirements: Parent Catalog selector, large Drag-and-Drop file zone for PDF, Access Rule selection, and 'Run OCR' checkbox."
* **VW-DIG-005: Halaman OCR & Indexing**
    > "Build the 'OCR & Indexing Monitor Page' (VW-DIG-005). Requirements: List of assets with processing status, a 'Run Pending OCR' button, and a compact log area for recent process results."

---

### 7. Area Admin - Laporan & Monitoring

* **VW-REP-001: Dashboard Laporan Statistik**
    > "Build the 'Report Dashboard' (VW-REP-001). Requirements: Analytical layout with Stat Cards for totals and Chart placeholders for 'Circulation Trends' and 'Collection Growth'. Include Date Range filters at the top."
* **VW-AUD-001: Audit Log**
    > "Build the 'Audit Log Index' (VW-AUD-001). Requirements: Heavy data grid for logs. Filters for Date, User, Module, and Action. Table columns: Timestamp, User, Module, Action, and IP Address."

---

### 8. Area OPAC Publik (Layout OPAC)

* **VW-OPA-001: Beranda OPAC (Search-First)**
    > "Build the 'OPAC Home Page' (VW-OPA-001) using the OPAC Layout. Requirements: Hero Search section with a large central search bar and placeholder 'Search by title, author, or ISBN...'. Add 'Popular Categories' pill-links below the search bar."
* **VW-OPA-002: Hasil Pencarian OPAC**
    > "Build the 'OPAC Search Results' (VW-OPA-002). Requirements: Split view with Left Sidebar for facet filters (Author, Year, Type). Right Main area for Result Cards. Cards must show cover thumbnail, Title, Author, and Availability badges (Physical/Digital)."
* **VW-OPA-003: Detail Koleksi OPAC**
    > "Build the 'OPAC Record Detail' (VW-OPA-003). Requirements: Prominent book cover and metadata. Separate sections for 'Physical Availability' (Shelf/Status) and 'Digital Access' with a large 'Read Online' button if permitted."
* **VW-OPA-004: Preview Aset Publik**
    > "Build the 'Digital Asset Viewer' (VW-OPA-004). Requirements: Distraction-free full-page viewer for PDF (PDF.js mockup), minimalist top bar with title and 'Page X of Y'."
