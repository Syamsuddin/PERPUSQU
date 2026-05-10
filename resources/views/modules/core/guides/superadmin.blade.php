@extends('layouts.admin')

@section('title', 'Panduan Admin')
@section('page-title', 'Panduan Admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Panduan Admin</li>
@endsection

@section('content')
<div class="row">
    {{-- Main Content --}}
    <div class="col-lg-9">

        {{-- Header --}}
        <div class="pq-card p-5 mb-4">
            <div class="text-center mb-5">
                <i class="bi bi-journal-check text-primary" style="font-size: 3.5rem;"></i>
                <h2 class="fw-bold mt-3">Panduan Lengkap Admin GIBTHA LIBRARY</h2>
                <p class="text-muted">Panduan operasional langkah demi langkah untuk menjalankan sistem perpustakaan dari awal hingga operasional harian.</p>
            </div>

            <div class="alert alert-primary border-0 d-flex align-items-start gap-3" style="border-radius: 12px;">
                <i class="bi bi-lightbulb-fill fs-5 mt-1"></i>
                <div>
                    <strong>Urutan yang Disarankan untuk Implementasi Baru</strong><br>
                    <small>Ikuti langkah 1–4 terlebih dahulu sebelum mulai input koleksi dan melayani anggota. Langkah 5–9 adalah operasi harian.</small>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN A: SETUP AWAL --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-gear-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="setup-awal">A. Setup Awal Sistem</h4>
                    <small class="text-muted">Lakukan sekali saat pertama kali implementasi</small>
                </div>
            </div>

            {{-- Step A1 --}}
            <div class="d-flex mb-5" id="identitas-institusi">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">A1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Konfigurasi Identitas Institusi</h5>
                    <p class="text-muted small">Nama dan logo perpustakaan yang ditampilkan di Landing Page, OPAC, dan Laporan.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Pengaturan &rsaquo; Profil Institusi</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Institusi</strong> (nama universitas/lembaga induk).</li>
                            <li class="mb-1">Isi <strong>Nama Perpustakaan</strong>, <strong>Alamat</strong>, <strong>Telepon</strong>, dan <strong>Email</strong>.</li>
                            <li class="mb-1">Unggah <strong>Logo</strong> resmi (PNG transparan direkomendasikan).</li>
                            <li>Klik <strong>Simpan</strong>. Logo dan nama akan langsung muncul di semua halaman publik.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- Step A2 --}}
            <div class="d-flex mb-5" id="aturan-operasional">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">A2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Aturan Operasional & Sirkulasi</h5>
                    <p class="text-muted small">Parameter yang mengatur perhitungan denda dan batas peminjaman secara otomatis.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Pengaturan &rsaquo; Aturan Operasional</strong>.</li>
                            <li class="mb-1">Tentukan <strong>Batas Hari Pinjam</strong> (contoh: 7 hari untuk mahasiswa).</li>
                            <li class="mb-1">Tentukan <strong>Jumlah Maksimal Pinjaman Aktif</strong> per anggota (default sistem: 5 eksemplar).</li>
                            <li class="mb-1">Atur <strong>Besar Denda per Hari</strong> (Rp) untuk keterlambatan pengembalian.</li>
                            <li class="mb-1">Tentukan <strong>Batas Maksimal Perpanjangan</strong> pinjaman.</li>
                            <li>Klik <strong>Simpan Pengaturan</strong>. Sistem langsung menggunakan nilai ini untuk semua transaksi baru.</li>
                        </ol>
                    </div>
                    <div class="alert alert-warning border-0 mt-2 p-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Catatan:</strong> Perubahan aturan hanya berlaku untuk transaksi <em>baru</em> setelah disimpan. Transaksi yang sudah berjalan menggunakan aturan lama.
                    </div>
                </div>
            </div>

            {{-- Step A3 --}}
            <div class="d-flex mb-2" id="master-data">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">A3</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Persiapan Master Data</h5>
                    <p class="text-muted small">Data referensi yang wajib diisi sebelum entri katalog. Akses semua sub-menu di <strong>Master Data</strong>.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-tag me-1"></i>Jenis Koleksi</h6>
                                <p class="small text-muted mb-2">Kategorikan koleksi: Buku Teks, Referensi, Jurnal, Skripsi, dll. Wajib ada minimal satu sebelum membuat katalog.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Jenis Koleksi</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-diagram-3 me-1"></i>Klasifikasi (DDC)</h6>
                                <p class="small text-muted mb-2">Nomor kelas Dewey Decimal Classification. Tambahkan kode DDC yang relevan dengan koleksi perpustakaan Anda.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Klasifikasi</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-person-badge me-1"></i>Pengarang</h6>
                                <p class="small text-muted mb-2">Daftar nama pengarang. Dapat di-input satu per satu atau saat proses katalogisasi. Minimal 1 pengarang wajib ada per katalog untuk bisa dipublikasikan.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Pengarang</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-building me-1"></i>Penerbit</h6>
                                <p class="small text-muted mb-2">Daftar nama penerbit buku. Tambahkan penerbit yang sering muncul di koleksi Anda terlebih dahulu.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Penerbit</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-bookmarks me-1"></i>Subjek</h6>
                                <p class="small text-muted mb-2">Kata kunci tematik koleksi (misal: Ekonomi, Teknik Informatika). Digunakan untuk pencarian di OPAC.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Subjek</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-translate me-1"></i>Bahasa</h6>
                                <p class="small text-muted mb-2">Bahasa koleksi (Indonesia, Inggris, Arab, dll). Tambahkan semua bahasa koleksi yang dimiliki perpustakaan.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Bahasa</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-grid-3x3 me-1"></i>Lokasi Rak</h6>
                                <p class="small text-muted mb-2">Kode dan nama lokasi rak fisik (misal: Rak A1, Lantai 2). Diperlukan saat menambah eksemplar fisik.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Lokasi Rak</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-shield-check me-1"></i>Kondisi Item</h6>
                                <p class="small text-muted mb-2">Standar kondisi fisik buku (Baik, Cukup, Rusak Ringan, dll). Digunakan saat pencatatan pengembalian.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Kondisi Item</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-mortarboard me-1"></i>Fakultas & Program Studi</h6>
                                <p class="small text-muted mb-2">Untuk perpustakaan perguruan tinggi: daftarkan fakultas dan program studi agar dapat dilampirkan ke data anggota mahasiswa.</p>
                                <small class="text-muted">Menu: <strong>Master Data &rsaquo; Fakultas</strong> dan <strong>Program Studi</strong></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN B: MANAJEMEN PENGGUNA & AKSES --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-success bg-opacity-10 p-3">
                    <i class="bi bi-people-fill text-success fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="manajemen-pengguna">B. Manajemen Pengguna & Hak Akses</h4>
                    <small class="text-muted">Kelola akun petugas dan perizinan sistem</small>
                </div>
            </div>

            {{-- B1: Buat Pengguna --}}
            <div class="d-flex mb-5" id="buat-pengguna">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">B1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Membuat Akun Petugas Baru</h5>
                    <p class="text-muted small">Buat akun terpisah untuk setiap petugas perpustakaan. Jangan gunakan akun admin bersama-sama.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Akses &rsaquo; Pengguna &rsaquo; Tambah Pengguna</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Lengkap</strong>, <strong>Email</strong> (akan digunakan untuk login), dan <strong>Password</strong> awal.</li>
                            <li class="mb-1">Pilih <strong>Role</strong> yang sesuai (lihat B2 untuk penjelasan role).</li>
                            <li class="mb-1">Klik <strong>Simpan</strong>. Akun langsung aktif.</li>
                            <li>Bagikan email dan password awal kepada petugas, minta mereka ganti password setelah login pertama.</li>
                        </ol>
                    </div>
                    <div class="alert alert-info border-0 mt-2 p-2 small">
                        <i class="bi bi-info-circle me-1"></i>
                        Untuk <strong>reset password</strong> petugas: buka detail pengguna, klik <strong>Reset Password</strong>, masukkan password baru.
                    </div>
                </div>
            </div>

            {{-- B2: Roles --}}
            <div class="d-flex mb-2" id="roles">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">B2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Pengaturan Role & Izin</h5>
                    <p class="text-muted small">Role menentukan menu dan fitur yang dapat diakses petugas.</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered small">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Akses Utama</th>
                                    <th>Cocok Untuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">Superadmin</span></td>
                                    <td>Seluruh sistem termasuk pengaturan, role, dan pengguna</td>
                                    <td>Kepala sistem / IT administrator</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Admin</span></td>
                                    <td>Katalog, koleksi, anggota, sirkulasi, laporan, master data</td>
                                    <td>Kepala perpustakaan / admin senior</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Librarian</span></td>
                                    <td>Katalog, koleksi, anggota, sirkulasi</td>
                                    <td>Pustakawan operasional</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">Operator</span></td>
                                    <td>Sirkulasi (peminjaman & pengembalian) dan daftar anggota</td>
                                    <td>Petugas meja layanan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-light rounded-3 p-3 mt-2">
                        <p class="small mb-1 fw-bold">Untuk mengubah role pengguna:</p>
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Akses &rsaquo; Pengguna</strong>, klik nama pengguna.</li>
                            <li class="mb-1">Pada bagian <strong>Role</strong>, pilih role yang diinginkan lalu klik <strong>Perbarui Role</strong>.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN C: MANAJEMEN ANGGOTA --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-info bg-opacity-10 p-3">
                    <i class="bi bi-person-lines-fill text-info fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="manajemen-anggota">C. Manajemen Anggota</h4>
                    <small class="text-muted">Registrasi, aktivasi, pemblokiran, dan riwayat anggota</small>
                </div>
            </div>

            {{-- C1: Registrasi --}}
            <div class="d-flex mb-5" id="registrasi-anggota">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">C1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Registrasi Anggota Baru</h5>
                    <p class="text-muted small">Setiap anggota mendapatkan ID unik yang digunakan pada seluruh transaksi sirkulasi.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Anggota &rsaquo; Tambah Anggota</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Lengkap</strong>, <strong>NIM/NIK</strong>, dan <strong>Jenis Anggota</strong> (Mahasiswa, Dosen, Umum, dll).</li>
                            <li class="mb-1">Untuk anggota mahasiswa: pilih <strong>Fakultas</strong> dan <strong>Program Studi</strong>.</li>
                            <li class="mb-1">Isi <strong>Tanggal Berlaku</strong> keanggotaan.</li>
                            <li>Klik <strong>Simpan</strong>. Anggota otomatis berstatus <em>Aktif</em> dan langsung dapat meminjam.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- C2: Kelola Status --}}
            <div class="d-flex mb-5" id="status-anggota">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">C2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Pengelolaan Status Anggota</h5>
                    <p class="text-muted small">Status anggota menentukan eligibilitas peminjaman.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold small text-success mb-2"><i class="bi bi-check-circle me-1"></i>Aktifkan / Nonaktifkan</h6>
                                <p class="small text-muted mb-1">Pada halaman detail anggota, klik tombol <strong>Nonaktifkan</strong> (merah) atau <strong>Aktifkan</strong> (hijau).</p>
                                <p class="small text-muted mb-0"><i class="bi bi-exclamation-triangle text-warning me-1"></i>Anggota dengan <strong>pinjaman aktif</strong> tidak dapat dinonaktifkan.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold small text-danger mb-2"><i class="bi bi-slash-circle me-1"></i>Blokir / Buka Blokir</h6>
                                <p class="small text-muted mb-1">Klik <strong>Blokir Anggota</strong> dan isi alasan pemblokiran. Anggota yang diblokir tidak dapat meminjam.</p>
                                <p class="small text-muted mb-0">Untuk membuka blokir: klik tombol <strong>Buka Blokir</strong> pada halaman detail.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light rounded-3 p-3 mt-3">
                        <p class="small mb-1 fw-bold">Syarat Anggota Dapat Meminjam:</p>
                        <ul class="small mb-0">
                            <li>Status <span class="badge bg-success">Aktif</span> dan tidak diblokir.</li>
                            <li>Tidak memiliki denda yang belum dilunasi.</li>
                            <li>Jumlah pinjaman aktif belum mencapai batas maksimal.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- C3: Hapus --}}
            <div class="d-flex mb-2" id="hapus-anggota">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">C3</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Menghapus Anggota</h5>
                    <p class="text-muted small">Anggota hanya dapat dihapus jika tidak memiliki pinjaman aktif. Riwayat transaksi tetap tersimpan di sistem.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Pastikan anggota tidak memiliki pinjaman aktif (cek di tab <strong>Riwayat Pinjaman</strong> anggota).</li>
                            <li class="mb-1">Buka halaman detail anggota, klik <strong>Hapus</strong>.</li>
                            <li>Konfirmasi penghapusan. Data anggota dihapus, namun riwayat sirkulasi dipertahankan.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN D: KATALOGISASI --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-book-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="katalogisasi">D. Katalogisasi Koleksi</h4>
                    <small class="text-muted">Input, edit, dan publikasi data bibliografis</small>
                </div>
            </div>

            {{-- D1: Quick Entry Cetak --}}
            <div class="d-flex mb-5" id="quick-entry-cetak">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">D1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Quick Entry — Buku Cetak</h5>
                    <p class="text-muted small">Cara tercepat menambah koleksi buku fisik beserta eksemplarnya secara bersamaan.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Katalog &rsaquo; Quick Entry &rsaquo; Buku Cetak</strong>.</li>
                            <li class="mb-1">Isi data bibliografis: <strong>Judul</strong>, <strong>Pengarang</strong>, <strong>Penerbit</strong>, <strong>Tahun Terbit</strong>, <strong>ISBN</strong>, <strong>Klasifikasi DDC</strong>, <strong>Jenis Koleksi</strong>.</li>
                            <li class="mb-1">Unggah <strong>Foto Cover</strong> (opsional, tapi sangat disarankan untuk OPAC).</li>
                            <li class="mb-1">Pada bagian <strong>Eksemplar</strong>: isi <strong>Barcode</strong> unik, <strong>Lokasi Rak</strong>, dan <strong>Kondisi Awal</strong>. Tambah baris untuk eksemplar berikutnya.</li>
                            <li>Klik <strong>Simpan</strong>. Katalog dibuat dengan status <em>Draft</em> dan eksemplar langsung berstatus <em>Tersedia</em>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- D2: Quick Entry Ebook --}}
            <div class="d-flex mb-5" id="quick-entry-ebook">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">D2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Quick Entry — E-Book / Koleksi Digital</h5>
                    <p class="text-muted small">Untuk koleksi digital (PDF, EPUB). Tidak memerlukan data eksemplar fisik.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Katalog &rsaquo; Quick Entry &rsaquo; E-Book</strong>.</li>
                            <li class="mb-1">Isi metadata bibliografis seperti pada buku cetak.</li>
                            <li class="mb-1">Unggah <strong>File Digital</strong> (PDF/EPUB) pada bagian Aset Digital.</li>
                            <li class="mb-1">Tentukan <strong>Aksesibilitas</strong>: Publik (bisa diakses siapa saja di OPAC) atau Terbatas (hanya anggota tertentu).</li>
                            <li>Klik <strong>Simpan</strong>. File tersimpan dan siap dikelola melalui menu Repositori Digital.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- D3: Bulk Import --}}
            <div class="d-flex mb-5" id="bulk-import">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">D3</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Import Massal via Excel</h5>
                    <p class="text-muted small">Untuk migrasi data dari sistem lama atau input ratusan koleksi sekaligus.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Katalog &rsaquo; Import Excel</strong>.</li>
                            <li class="mb-1">Klik <strong>Unduh Template</strong> untuk mendapatkan format Excel yang benar.</li>
                            <li class="mb-1">Isi file Excel sesuai kolom yang tersedia (judul, pengarang, penerbit, tahun, ISBN, DDC, jenis koleksi, barcode, dll).</li>
                            <li class="mb-1">Unggah file Excel yang sudah diisi. Sistem akan menampilkan <strong>Preview Data</strong> sebelum diproses.</li>
                            <li class="mb-1">Periksa preview — baris yang bermasalah akan ditandai merah. Perbaiki file Excel jika ada error.</li>
                            <li>Klik <strong>Proses Import</strong>. Semua data valid akan masuk sebagai katalog berstatus <em>Draft</em>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- D4: Status Publikasi --}}
            <div class="d-flex mb-2" id="status-publikasi">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">D4</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Status Publikasi Katalog</h5>
                    <p class="text-muted small">Katalog harus dipublikasikan agar muncul di OPAC dan dapat ditemukan oleh pengunjung perpustakaan.</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered small">
                            <thead class="table-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Transisi yang Mungkin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-secondary">Draft</span></td>
                                    <td>Baru dibuat, belum muncul di OPAC</td>
                                    <td>Publish, Arsipkan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Dipublikasikan</span></td>
                                    <td>Tampil di OPAC, dapat ditemukan publik</td>
                                    <td>Sembunyikan, Arsipkan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">Disembunyikan</span></td>
                                    <td>Tidak tampil di OPAC, eksemplar tetap bisa dipinjam</td>
                                    <td>Publish kembali, Arsipkan</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">Diarsipkan</span></td>
                                    <td>Tidak aktif, tidak tampil di OPAC</td>
                                    <td>Reaktivasi ke Draft</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-light rounded-3 p-3">
                        <p class="small fw-bold mb-1">Syarat untuk Mempublikasikan:</p>
                        <ul class="small mb-0">
                            <li>Judul harus diisi.</li>
                            <li>Jenis Koleksi harus dipilih.</li>
                            <li>Minimal <strong>1 pengarang</strong> harus terdaftar.</li>
                            <li>Status saat ini bukan <em>Diarsipkan</em> (harus Reaktivasi dulu).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN E: KOLEKSI FISIK --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-secondary bg-opacity-10 p-3">
                    <i class="bi bi-archive-fill text-secondary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="koleksi-fisik">E. Manajemen Koleksi Fisik (Eksemplar)</h4>
                    <small class="text-muted">Tambah, edit, dan kelola status fisik buku</small>
                </div>
            </div>

            {{-- E1: Tambah Eksemplar --}}
            <div class="d-flex mb-5" id="tambah-eksemplar">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">E1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Menambah Eksemplar ke Katalog yang Ada</h5>
                    <p class="text-muted small">Satu judul buku bisa memiliki banyak eksemplar (copy). Setiap eksemplar memiliki barcode unik.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka detail katalog (<strong>Katalog &rsaquo; Data Katalog</strong>, klik judul).</li>
                            <li class="mb-1">Pada tab <strong>Eksemplar Fisik</strong>, klik <strong>Tambah Eksemplar</strong>.</li>
                            <li class="mb-1">Isi <strong>Barcode</strong> (unik, bisa berupa nomor inventaris), <strong>Lokasi Rak</strong>, <strong>Kondisi Item</strong>.</li>
                            <li>Klik <strong>Simpan</strong>. Eksemplar otomatis berstatus <span class="badge bg-success">Tersedia</span> dan siap dipinjam.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- E2: Status Item --}}
            <div class="d-flex mb-2" id="status-item">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">E2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Mengubah Status Eksemplar</h5>
                    <p class="text-muted small">Status eksemplar berubah otomatis saat dipinjam/dikembalikan. Untuk kondisi khusus, admin dapat mengubah secara manual.</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered small">
                            <thead class="table-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Arti</th>
                                    <th>Dapat Berpindah Ke</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">Tersedia</span></td>
                                    <td>Siap dipinjam</td>
                                    <td>Dipinjam, Rusak, Perbaikan, Nonaktif, Hilang</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Dipinjam</span></td>
                                    <td>Sedang dalam pinjaman aktif</td>
                                    <td>Tersedia (via proses pengembalian), Rusak, Perbaikan, Hilang</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">Rusak</span></td>
                                    <td>Kondisi fisik rusak</td>
                                    <td>Perbaikan, Nonaktif, Tersedia</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">Perbaikan</span></td>
                                    <td>Sedang diperbaiki</td>
                                    <td>Tersedia, Rusak, Nonaktif</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">Hilang</span></td>
                                    <td>Dilaporkan hilang</td>
                                    <td>Tersedia (ditemukan kembali)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">Nonaktif</span></td>
                                    <td>Ditarik dari sirkulasi</td>
                                    <td>Tersedia, Perbaikan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-light rounded-3 p-3">
                        <p class="small mb-1 fw-bold">Cara mengubah status manual:</p>
                        <ol class="small mb-0">
                            <li class="mb-1">Buka detail eksemplar (dari halaman Koleksi atau dari tab Eksemplar di detail katalog).</li>
                            <li class="mb-1">Klik <strong>Ubah Status</strong>, pilih status tujuan yang valid, isi alasan.</li>
                            <li>Setiap perubahan status tercatat di <strong>Riwayat Status</strong> beserta waktu dan pengguna yang mengubah.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN F: SIRKULASI --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-danger bg-opacity-10 p-3">
                    <i class="bi bi-arrow-left-right text-danger fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="sirkulasi">F. Sirkulasi (Peminjaman & Pengembalian)</h4>
                    <small class="text-muted">Proses transaksi harian peminjaman, pengembalian, perpanjangan, dan denda</small>
                </div>
            </div>

            {{-- F1: Peminjaman --}}
            <div class="d-flex mb-5" id="peminjaman">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">F1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Proses Peminjaman</h5>
                    <p class="text-muted small">Sistem otomatis memvalidasi eligibilitas anggota sebelum pinjaman diproses.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Sirkulasi &rsaquo; Pinjam Buku</strong>.</li>
                            <li class="mb-1">Pilih atau cari <strong>Anggota</strong> berdasarkan nama atau NIM.</li>
                            <li class="mb-1">Scan atau ketik <strong>Barcode</strong> eksemplar yang akan dipinjam.</li>
                            <li class="mb-1">Sistem akan memvalidasi:
                                <ul class="mt-1">
                                    <li>Anggota aktif dan tidak diblokir.</li>
                                    <li>Tidak ada denda outstanding.</li>
                                    <li>Jumlah pinjaman aktif belum melebihi batas.</li>
                                    <li>Eksemplar berstatus <em>Tersedia</em>.</li>
                                </ul>
                            </li>
                            <li class="mb-1">Jika valid, sistem menampilkan <strong>Tanggal Jatuh Tempo</strong>. Klik <strong>Proses Pinjaman</strong>.</li>
                            <li>Eksemplar otomatis berubah ke status <span class="badge bg-primary">Dipinjam</span>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- F2: Pengembalian --}}
            <div class="d-flex mb-5" id="pengembalian">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">F2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Proses Pengembalian</h5>
                    <p class="text-muted small">Sistem otomatis menghitung denda jika terlambat. Proses hanya memerlukan barcode buku.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Sirkulasi &rsaquo; Kembalikan Buku</strong>.</li>
                            <li class="mb-1">Scan atau ketik <strong>Barcode</strong> eksemplar yang dikembalikan.</li>
                            <li class="mb-1">Sistem otomatis menemukan pinjaman aktif yang terkait.</li>
                            <li class="mb-1">Pilih <strong>Kondisi Pengembalian</strong> (sesuai standar yang didefinisikan di Master Data &rsaquo; Kondisi Item).</li>
                            <li class="mb-1">Isi <strong>Catatan</strong> jika ada (opsional).</li>
                            <li class="mb-1">Jika terlambat, sistem menampilkan <strong>jumlah hari terlambat</strong> dan <strong>total denda</strong> yang harus dibayar.</li>
                            <li>Klik <strong>Proses Pengembalian</strong>. Eksemplar kembali ke status <span class="badge bg-success">Tersedia</span>. Denda dicatat secara otomatis.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- F3: Perpanjangan --}}
            <div class="d-flex mb-5" id="perpanjangan">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">F3</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Perpanjangan Pinjaman</h5>
                    <p class="text-muted small">Memperpanjang jatuh tempo pinjaman yang masih aktif dan belum melewati tanggal jatuh tempo.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Sirkulasi &rsaquo; Pinjaman Aktif</strong>, cari anggota atau buku.</li>
                            <li class="mb-1">Klik detail pinjaman, lalu klik <strong>Perpanjang</strong>.</li>
                            <li class="mb-1">Konfirmasi perpanjangan. Sistem akan memperbarui tanggal jatuh tempo.</li>
                        </ol>
                    </div>
                    <div class="alert alert-warning border-0 mt-2 p-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Batasan Perpanjangan:</strong> Hanya pinjaman yang <em>belum melewati jatuh tempo</em> yang dapat diperpanjang. Jika sudah terlambat, harus dikembalikan dahulu. Ada batas maksimal jumlah perpanjangan per pinjaman.
                    </div>
                </div>
            </div>

            {{-- F4: Denda --}}
            <div class="d-flex mb-2" id="denda">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">F4</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Manajemen Denda</h5>
                    <p class="text-muted small">Denda otomatis dibuat saat pengembalian terlambat. Admin dapat melunasi atau menghapus denda.</p>
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <p class="small fw-bold mb-1">Cara Melunasi Denda:</p>
                        <ol class="small mb-0">
                            <li class="mb-1">Buka <strong>Sirkulasi &rsaquo; Denda</strong>.</li>
                            <li class="mb-1">Filter berdasarkan anggota atau status denda (<em>Outstanding</em>).</li>
                            <li class="mb-1">Klik <strong>Lunaskan</strong> setelah anggota membayar denda. Status berubah ke <em>Lunas</em>.</li>
                        </ol>
                    </div>
                    <div class="bg-light rounded-3 p-3">
                        <p class="small fw-bold mb-1">Cara Menghapus Denda (Waive):</p>
                        <ol class="small mb-0">
                            <li class="mb-1">Pada daftar denda, klik <strong>Hapuskan</strong> jika denda akan dibebaskan.</li>
                            <li>Status berubah ke <em>Dihapuskan</em>. Gunakan fitur ini dengan bijak dan catat alasannya di sistem (gunakan kolom catatan).</li>
                        </ol>
                    </div>
                    <div class="alert alert-info border-0 mt-2 p-2 small">
                        <i class="bi bi-info-circle me-1"></i>
                        Anggota dengan denda <em>Outstanding</em> (belum lunas) tidak dapat meminjam buku baru hingga denda dilunasi atau dihapuskan.
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN G: REPOSITORI DIGITAL --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-purple bg-opacity-10 p-3" style="background-color: rgba(102, 16, 242, 0.1);">
                    <i class="bi bi-cloud-arrow-up-fill fs-4" style="color: #6610f2;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="repositori-digital">G. Repositori Digital</h4>
                    <small class="text-muted">Kelola aset digital: e-book, skripsi, jurnal, dan dokumen lainnya</small>
                </div>
            </div>

            {{-- G1: Upload Aset --}}
            <div class="d-flex mb-5" id="upload-aset">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem; background-color: #6610f2;">G1</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Upload Aset Digital</h5>
                    <p class="text-muted small">Aset digital terhubung ke katalog bibliografis yang sudah ada.</p>
                    <div class="bg-light rounded-3 p-3">
                        <ol class="mb-0 small">
                            <li class="mb-1">Buka <strong>Repositori Digital &rsaquo; Tambah Aset</strong>.</li>
                            <li class="mb-1">Pilih <strong>Katalog</strong> yang sudah ada (aset harus terhubung ke record katalog).</li>
                            <li class="mb-1">Pilih <strong>Jenis Aset</strong>: PDF, EPUB, atau lainnya.</li>
                            <li class="mb-1">Unggah file digital.</li>
                            <li class="mb-1">Tentukan <strong>Aksesibilitas</strong>: Publik atau Terbatas.</li>
                            <li>Klik <strong>Simpan</strong>. Aset tersimpan dengan status <em>Draft</em>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- G2: Publikasi & OCR --}}
            <div class="d-flex mb-2" id="publikasi-digital">
                <div class="me-4 flex-shrink-0">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem; background-color: #6610f2;">G2</div>
                </div>
                <div class="w-100">
                    <h5 class="fw-bold">Publikasi Aset & Proses OCR</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold small mb-2"><i class="bi bi-eye me-1 text-success"></i>Publikasi Aset</h6>
                                <p class="small text-muted mb-1">Buka detail aset digital, klik <strong>Publikasikan</strong>. Aset akan muncul di OPAC dan dapat diakses sesuai pengaturan aksesibilitas.</p>
                                <p class="small text-muted mb-0">Untuk menyembunyikan: klik <strong>Sembunyikan</strong>. Untuk menghapus permanen dari tampilan: klik <strong>Arsipkan</strong>.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold small mb-2"><i class="bi bi-text-paragraph me-1 text-primary"></i>Proses OCR</h6>
                                <p class="small text-muted mb-1">OCR (Optical Character Recognition) mengekstrak teks dari file PDF agar dapat dicari melalui OPAC.</p>
                                <p class="small text-muted mb-0">Buka detail aset, klik <strong>Jalankan OCR</strong>. Proses berjalan di background. Cek status di kolom <em>Status OCR</em>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN H: MONITORING --}}
        {{-- ============================================================ --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-3 bg-dark bg-opacity-10 p-3">
                    <i class="bi bi-bar-chart-fill text-dark fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0" id="monitoring">H. Monitoring & Laporan</h4>
                    <small class="text-muted">Dashboard, audit log, dan laporan statistik perpustakaan</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-speedometer2 me-1 text-primary"></i>Dashboard</h6>
                        <p class="small text-muted mb-0">Tampilan utama berisi statistik ringkas: total koleksi, anggota aktif, peminjaman hari ini, denda outstanding, dan buku terlambat. Buka dari menu <strong>Dashboard</strong>.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-clock-history me-1 text-warning"></i>Riwayat Pinjaman</h6>
                        <p class="small text-muted mb-0">Lihat semua transaksi sirkulasi (aktif dan selesai) di <strong>Sirkulasi &rsaquo; Riwayat Pinjaman</strong>. Filter berdasarkan tanggal, anggota, atau kata kunci.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-list-check me-1 text-success"></i>Audit Log</h6>
                        <p class="small text-muted mb-0">Rekam jejak setiap perubahan di sistem: siapa yang melakukan apa dan kapan. Akses melalui menu <strong>Audit</strong>. Berguna untuk investigasi dan pertanggungjawaban.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-graph-up me-1 text-danger"></i>Laporan</h6>
                        <p class="small text-muted mb-0">Laporan statistik koleksi, peminjaman, anggota, dan denda tersedia di menu <strong>Laporan</strong>. Dapat difilter per periode.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tips Keamanan --}}
        <div class="pq-card p-4 mb-4">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-shield-fill-check text-success fs-3 mt-1"></i>
                <div>
                    <h5 class="fw-bold">Tips Keamanan & Praktik Terbaik</h5>
                    <ul class="small mb-0 text-dark opacity-75">
                        <li class="mb-1">Jangan bagikan akun Anda kepada orang lain. Buat akun terpisah untuk setiap petugas.</li>
                        <li class="mb-1">Berikan role <em>Operator</em> untuk petugas meja layanan — cukup untuk proses sirkulasi tanpa akses pengaturan sistem.</li>
                        <li class="mb-1">Lakukan backup database secara berkala, terutama sebelum migrasi atau import data massal.</li>
                        <li class="mb-1">Gunakan fitur Audit Log untuk memantau aktivitas yang tidak biasa.</li>
                        <li class="mb-1">Periksa daftar <strong>Pinjaman Terlambat</strong> secara rutin (Sirkulasi &rsaquo; Pinjaman Aktif &rsaquo; filter Terlambat) dan tindak lanjuti dengan notifikasi kepada anggota.</li>
                        <li>Simpan password akun Superadmin di tempat yang aman. Jika perlu reset, gunakan fitur Reset Password di halaman detail pengguna.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================ --}}
    <div class="col-lg-3">
        <div class="pq-card p-4 sticky-top" style="top: 80px;">
            <h6 class="fw-bold mb-3">Daftar Isi</h6>
            <div class="list-group list-group-flush small">
                <a href="#setup-awal" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-primary">
                    <i class="bi bi-gear me-2"></i>A. Setup Awal
                </a>
                <a href="#identitas-institusi" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>A1 Identitas Institusi
                </a>
                <a href="#aturan-operasional" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>A2 Aturan Operasional
                </a>
                <a href="#master-data" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>A3 Master Data
                </a>

                <a href="#manajemen-pengguna" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-success mt-1">
                    <i class="bi bi-people me-2"></i>B. Pengguna & Akses
                </a>
                <a href="#buat-pengguna" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>B1 Buat Akun Petugas
                </a>
                <a href="#roles" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>B2 Pengaturan Role
                </a>

                <a href="#manajemen-anggota" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-info mt-1">
                    <i class="bi bi-person-lines-fill me-2"></i>C. Anggota
                </a>
                <a href="#registrasi-anggota" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>C1 Registrasi
                </a>
                <a href="#status-anggota" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>C2 Status Anggota
                </a>

                <a href="#katalogisasi" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-warning mt-1">
                    <i class="bi bi-book me-2"></i>D. Katalogisasi
                </a>
                <a href="#quick-entry-cetak" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>D1 Quick Entry Cetak
                </a>
                <a href="#quick-entry-ebook" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>D2 Quick Entry Ebook
                </a>
                <a href="#bulk-import" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>D3 Import Excel
                </a>
                <a href="#status-publikasi" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>D4 Status Publikasi
                </a>

                <a href="#koleksi-fisik" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-secondary mt-1">
                    <i class="bi bi-archive me-2"></i>E. Koleksi Fisik
                </a>
                <a href="#tambah-eksemplar" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>E1 Tambah Eksemplar
                </a>
                <a href="#status-item" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>E2 Status Eksemplar
                </a>

                <a href="#sirkulasi" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-danger mt-1">
                    <i class="bi bi-arrow-left-right me-2"></i>F. Sirkulasi
                </a>
                <a href="#peminjaman" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>F1 Peminjaman
                </a>
                <a href="#pengembalian" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>F2 Pengembalian
                </a>
                <a href="#perpanjangan" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>F3 Perpanjangan
                </a>
                <a href="#denda" class="list-group-item list-group-item-action py-1 px-2 border-0 text-muted">
                    <i class="bi bi-dot me-1"></i>F4 Denda
                </a>

                <a href="#repositori-digital" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold mt-1" style="color: #6610f2;">
                    <i class="bi bi-cloud-arrow-up me-2"></i>G. Repositori Digital
                </a>

                <a href="#monitoring" class="list-group-item list-group-item-action py-2 px-0 border-0 fw-semibold text-dark mt-1">
                    <i class="bi bi-bar-chart me-2"></i>H. Monitoring
                </a>
            </div>

            <hr>

            <div class="text-center">
                <p class="small text-muted mb-2">Butuh Bantuan Teknis?</p>
                <a href="https://wa.me/6281349694696" target="_blank" class="btn btn-success btn-sm w-100 py-2">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Pengembang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
