@extends('layouts.admin')

@section('title', 'Panduan Pustakawan')
@section('page-title', 'Panduan Pustakawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Panduan Pustakawan</li>
@endsection

@push('styles')
<style>
    .guide-toc { position: sticky; top: 1rem; }
    .guide-toc .toc-link {
        display: flex; align-items: center; gap: .5rem;
        padding: .4rem .75rem; border-radius: 8px;
        color: var(--pq-text-secondary); font-size: .83rem; font-weight: 500;
        text-decoration: none; transition: .15s;
    }
    .guide-toc .toc-link:hover { background: var(--pq-bg); color: var(--pq-primary); }
    .guide-toc .toc-link.active { background: rgba(var(--pq-primary-rgb),.1); color: var(--pq-primary); font-weight: 600; }
    .guide-toc .toc-link i { font-size: .9rem; width: 18px; text-align: center; }

    .step-badge {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .9rem; flex-shrink: 0;
    }
    .section-icon-wrap {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .guide-step { border-left: 3px solid var(--bs-border-color); padding-left: 1.25rem; margin-left: .5rem; }
    .guide-step:last-child { border-left-color: transparent; }

    .tip-box { background: #fff8e1; border: 1px solid #ffe082; border-radius: 10px; padding: .9rem 1.1rem; }
    .tip-box i { color: #f59e0b; }
    .warn-box { background: #fff3e0; border: 1px solid #ffcc80; border-radius: 10px; padding: .9rem 1.1rem; }
    .warn-box i { color: #f97316; }
    .info-box { background: #e8f4fd; border: 1px solid #90caf9; border-radius: 10px; padding: .9rem 1.1rem; }
    .info-box i { color: #2196f3; }

    .perm-badge {
        display: inline-block; padding: .2rem .6rem;
        background: rgba(var(--pq-primary-rgb),.1); color: var(--pq-primary);
        border-radius: 6px; font-size: .75rem; font-weight: 600; font-family: monospace;
    }
    .state-row td, .state-row th { font-size: .83rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="row g-4">

    {{-- ───────────────────────────────────────────────────────────────────
         MAIN CONTENT
    ─────────────────────────────────────────────────────────────────── --}}
    <div class="col-lg-9">

        {{-- Header --}}
        <div class="pq-card p-5 mb-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-badge text-success" style="font-size:3.5rem"></i>
                <h2 class="fw-bold mt-3">Panduan Lengkap Pustakawan</h2>
                <p class="text-muted">Panduan operasional harian untuk mengelola data bibliografi, koleksi fisik, dan master data perpustakaan.</p>
            </div>

            {{-- Scope overview --}}
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-success bg-opacity-10">
                        <i class="bi bi-book-fill text-success fs-4 d-block mb-1"></i>
                        <small class="fw-semibold text-success">Katalog</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-info bg-opacity-10">
                        <i class="bi bi-boxes text-info fs-4 d-block mb-1"></i>
                        <small class="fw-semibold text-info">Koleksi Fisik</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10">
                        <i class="bi bi-tags-fill text-warning fs-4 d-block mb-1"></i>
                        <small class="fw-semibold text-warning">Master Data</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-3 bg-secondary bg-opacity-10">
                        <i class="bi bi-eye text-secondary fs-4 d-block mb-1"></i>
                        <small class="fw-semibold text-secondary">Monitoring</small>
                    </div>
                </div>
            </div>

            <div class="info-box mt-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Lingkup Akses Pustakawan:</strong> Anda dapat mengelola data bibliografi, koleksi fisik, dan master data. Untuk proses peminjaman/pengembalian, koordinasikan dengan <strong>Petugas Sirkulasi</strong>.
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN A: PROFIL & AKUN
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="akun">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-secondary bg-opacity-10">
                    <i class="bi bi-person-circle text-secondary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">A. Pengaturan Akun & Profil</h4>
                    <small class="text-muted">Lakukan saat pertama login atau kapan saja dibutuhkan</small>
                </div>
            </div>

            <div class="guide-step mb-4">
                <div class="d-flex gap-3 mb-3">
                    <div class="step-badge bg-secondary text-white">A1</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Update Profil & Foto</h6>
                        <p class="text-muted small mb-2">Pastikan nama dan foto profil sudah sesuai — data ini muncul di log aktivitas sistem.</p>
                        <ol class="small mb-0">
                            <li class="mb-1">Klik ikon akun di pojok kanan atas → <strong>Profil Saya</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Lengkap</strong> dan <strong>Nomor Telepon</strong>.</li>
                            <li class="mb-1">Unggah <strong>Foto Profil</strong> (JPG/PNG, maks. 2 MB).</li>
                            <li>Klik <strong>Simpan Perubahan</strong>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="guide-step">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-secondary text-white">A2</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Ganti Password</h6>
                        <ol class="small mb-0">
                            <li class="mb-1">Di halaman <strong>Profil Saya</strong>, geser ke bagian <strong>Keamanan</strong>.</li>
                            <li class="mb-1">Masukkan password lama, lalu password baru (min. 8 karakter).</li>
                            <li>Klik <strong>Perbarui Password</strong>.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN B: MASTER DATA
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="master-data">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-warning bg-opacity-10">
                    <i class="bi bi-tags-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">B. Mengelola Master Data</h4>
                    <small class="text-muted">Siapkan data referensi sebelum menginput koleksi</small>
                </div>
            </div>

            <div class="info-box mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Mengapa penting?</strong> Master data adalah fondasi. Data bibliografi memerlukan Penulis, Penerbit, Bahasa, dan Klasifikasi yang sudah ada di sistem. Lengkapi dulu sebelum mulai input katalog.
            </div>

            {{-- B1 Penulis --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3 mb-2">
                    <div class="step-badge bg-warning text-dark">B1</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Penulis (Authors)</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Master Data &rsaquo; Penulis</strong></p>
                        <ol class="small mb-2">
                            <li class="mb-1">Klik <strong>+ Tambah Penulis</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Penulis</strong> — gunakan format "<em>Nama Depan Nama Belakang</em>" secara konsisten.</li>
                            <li class="mb-1">Isi <strong>Biografi</strong> singkat (opsional).</li>
                            <li>Klik <strong>Simpan</strong>.</li>
                        </ol>
                        <div class="tip-box">
                            <i class="bi bi-lightbulb-fill me-1"></i>
                            <strong>Tips:</strong> Sebelum menambah, gunakan fitur <strong>pencarian</strong> untuk memastikan penulis belum ada. Nama ganda akan membingungkan hasil pencarian OPAC.
                        </div>
                    </div>
                </div>
            </div>

            {{-- B2 Penerbit --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3 mb-2">
                    <div class="step-badge bg-warning text-dark">B2</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Penerbit (Publishers)</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Master Data &rsaquo; Penerbit</strong></p>
                        <ol class="small mb-0">
                            <li class="mb-1">Klik <strong>+ Tambah Penerbit</strong>.</li>
                            <li class="mb-1">Isi <strong>Nama Penerbit</strong> dan <strong>Kota</strong> penerbitan.</li>
                            <li>Klik <strong>Simpan</strong>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- B3 Bahasa, Klasifikasi, Subjek --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3 mb-2">
                    <div class="step-badge bg-warning text-dark">B3</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Bahasa, Klasifikasi, dan Subjek</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Master Data &rsaquo; Bahasa / Klasifikasi / Subjek</strong></p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered state-row">
                                <thead class="table-light">
                                    <tr><th>Data</th><th>Isi yang Dibutuhkan</th><th>Contoh</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Bahasa</td>
                                        <td>Nama bahasa koleksi</td>
                                        <td>Indonesia, Inggris, Arab</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Klasifikasi</td>
                                        <td>Kode DDC + nama kelas</td>
                                        <td>300 – Ilmu Sosial</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Subjek</td>
                                        <td>Topik/kata kunci tematik</td>
                                        <td>Akuntansi, Fiqih Muamalah</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B4 Jenis Koleksi, Lokasi Rak --}}
            <div class="guide-step">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-warning text-dark">B4</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Jenis Koleksi & Lokasi Rak</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Master Data &rsaquo; Jenis Koleksi / Lokasi Rak</strong></p>
                        <ul class="small mb-2">
                            <li class="mb-1"><strong>Jenis Koleksi</strong> — contoh: Buku, Jurnal, Majalah, Skripsi, Laporan. Digunakan sebagai filter di OPAC.</li>
                            <li><strong>Lokasi Rak</strong> — definisikan posisi fisik di perpustakaan, misal "Lantai 2 – Rak A03". Ditautkan ke eksemplar fisik.</li>
                        </ul>
                        <div class="tip-box">
                            <i class="bi bi-lightbulb-fill me-1"></i>
                            Lokasi rak yang jelas memudahkan anggota dan petugas menemukan buku di rak fisik.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN C: KATALOGISASI
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="katalog">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-success bg-opacity-10">
                    <i class="bi bi-book-fill text-success fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">C. Katalogisasi Koleksi</h4>
                    <small class="text-muted">Entri data bibliografi ke dalam sistem</small>
                </div>
            </div>

            {{-- Alur status --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Status Katalog & Alur Penerbitan</h6>
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="badge bg-secondary">Draft</span>
                    <i class="bi bi-arrow-right text-muted"></i>
                    <span class="badge bg-success">Published</span>
                    <i class="bi bi-arrow-right text-muted"></i>
                    <span class="badge bg-warning text-dark">Unpublished</span>
                    <i class="bi bi-arrow-right text-muted"></i>
                    <span class="badge bg-danger">Archived</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered state-row">
                        <thead class="table-light">
                            <tr><th>Status</th><th>Artinya</th><th>Aksi Tersedia</th><th>Tampil di OPAC?</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-secondary">Draft</span></td>
                                <td>Data sedang diisi/diverifikasi</td>
                                <td>Edit, Publish</td>
                                <td class="text-danger fw-semibold">Tidak</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Published</span></td>
                                <td>Aktif dan dapat dicari publik</td>
                                <td>Edit, Unpublish</td>
                                <td class="text-success fw-semibold">Ya</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Unpublished</span></td>
                                <td>Disembunyikan sementara</td>
                                <td>Edit, Publish, Archive</td>
                                <td class="text-danger fw-semibold">Tidak</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Archived</span></td>
                                <td>Koleksi tidak aktif</td>
                                <td>Lihat saja</td>
                                <td class="text-danger fw-semibold">Tidak</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- C1 Input baru --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-success text-white">C1</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Menambah Entri Katalog Baru</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Katalog &rsaquo; + Tambah</strong></p>
                        <ol class="small mb-2">
                            <li class="mb-1">Isi <strong>Judul</strong> lengkap sesuai halaman judul buku.</li>
                            <li class="mb-1">Pilih satu atau lebih <strong>Penulis</strong> — klik "+ Tambah Penulis" untuk menambah nama ganda.</li>
                            <li class="mb-1">Pilih <strong>Penerbit</strong>, <strong>Tahun Terbit</strong>, dan <strong>Edisi</strong>.</li>
                            <li class="mb-1">Isi <strong>ISBN</strong> jika ada (10 atau 13 digit).</li>
                            <li class="mb-1">Pilih <strong>Bahasa</strong>, <strong>Klasifikasi DDC</strong>, <strong>Jenis Koleksi</strong>.</li>
                            <li class="mb-1">Tambahkan satu atau lebih <strong>Subjek</strong> yang relevan.</li>
                            <li class="mb-1">Tulis <strong>Abstrak/Sinopsis</strong> untuk membantu pencarian semantik.</li>
                            <li class="mb-1">Unggah <strong>Foto Cover</strong> (JPG/PNG, maks. 5 MB) jika tersedia.</li>
                            <li>Klik <strong>Simpan sebagai Draft</strong> untuk disimpan dulu, atau <strong>Simpan & Publikasikan</strong> agar langsung tampil di OPAC.</li>
                        </ol>
                        <div class="tip-box">
                            <i class="bi bi-lightbulb-fill me-1"></i>
                            <strong>Best practice:</strong> Simpan sebagai Draft terlebih dahulu, periksa kembali semua data, baru Publikasikan. Koleksi yang belum punya eksemplar fisik tetap boleh dipublikasikan agar anggota bisa melihat ketersediaan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- C2 Edit --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-success text-white">C2</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Mengedit Data Katalog</h6>
                        <ol class="small mb-0">
                            <li class="mb-1">Di halaman <strong>Katalog</strong>, temukan judul via pencarian atau filter.</li>
                            <li class="mb-1">Klik tombol <strong>Edit</strong> (ikon pensil).</li>
                            <li class="mb-1">Ubah data yang diperlukan.</li>
                            <li>Klik <strong>Simpan Perubahan</strong>. Sistem mencatat riwayat perubahan secara otomatis.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- C3 Publish/Unpublish --}}
            <div class="guide-step">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-success text-white">C3</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Publikasikan / Sembunyikan Katalog</h6>
                        <ol class="small mb-2">
                            <li class="mb-1">Buka detail katalog.</li>
                            <li class="mb-1">Klik <strong>Publikasikan</strong> untuk membuat koleksi terlihat di OPAC.</li>
                            <li>Klik <strong>Sembunyikan</strong> untuk menarik sementara (misal sedang ada revisi data).</li>
                        </ol>
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Menyembunyikan katalog tidak menghapus eksemplar fisik dan tidak membatalkan pinjaman aktif yang sudah ada.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN D: KOLEKSI FISIK
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="koleksi">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-info bg-opacity-10">
                    <i class="bi bi-boxes text-info fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">D. Mengelola Koleksi Fisik (Eksemplar)</h4>
                    <small class="text-muted">Setiap salinan buku yang dimiliki perpustakaan dicatat sebagai satu eksemplar</small>
                </div>
            </div>

            {{-- Status fisik --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-2">Status Eksemplar Fisik</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered state-row">
                        <thead class="table-light">
                            <tr><th>Status</th><th>Artinya</th><th>Siapa yang ubah?</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-success">Available</span></td>
                                <td>Ada di rak, siap dipinjam</td>
                                <td>Pustakawan / Petugas Sirkulasi</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Loaned</span></td>
                                <td>Sedang dipinjam anggota</td>
                                <td>Otomatis saat transaksi pinjam</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Damaged</span></td>
                                <td>Rusak, belum diperbaiki</td>
                                <td>Pustakawan (lapangan)</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Repair</span></td>
                                <td>Sedang dalam perbaikan</td>
                                <td>Pustakawan</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Lost</span></td>
                                <td>Hilang / tidak ditemukan</td>
                                <td>Pustakawan (setelah investigasi)</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-dark">Inactive</span></td>
                                <td>Dinyatakan tidak aktif / dimusnahkan</td>
                                <td>Pustakawan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- D1 Tambah eksemplar --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-info text-white">D1</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Menambah Eksemplar Baru</h6>
                        <p class="text-muted small mb-2">Navigasi: <strong>Katalog &rsaquo; [Judul] &rsaquo; Koleksi Fisik &rsaquo; + Tambah Eksemplar</strong></p>
                        <ol class="small mb-2">
                            <li class="mb-1">Buka detail katalog bibliografi yang sudah ada.</li>
                            <li class="mb-1">Di tab <strong>Koleksi Fisik</strong>, klik <strong>+ Tambah Eksemplar</strong>.</li>
                            <li class="mb-1">Isi <strong>Nomor Panggil</strong> (call number) — kombinasi kode DDC + kode penulis, contoh: <code>330 MAR k</code>.</li>
                            <li class="mb-1">Isi <strong>Nomor Barcode</strong> (unik untuk setiap eksemplar).</li>
                            <li class="mb-1">Pilih <strong>Lokasi Rak</strong> tempat buku disimpan.</li>
                            <li class="mb-1">Isi <strong>Harga Pengadaan</strong> (opsional, untuk data inventaris).</li>
                            <li>Klik <strong>Simpan</strong>. Status awal otomatis <span class="badge bg-success">Available</span>.</li>
                        </ol>
                        <div class="tip-box">
                            <i class="bi bi-lightbulb-fill me-1"></i>
                            Jika perpustakaan mendapat 5 eksemplar buku yang sama, tambahkan 5 kali dengan barcode berbeda. Setiap eksemplar adalah entitas terpisah.
                        </div>
                    </div>
                </div>
            </div>

            {{-- D2 Ubah status --}}
            <div class="guide-step mb-4">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-info text-white">D2</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Mengubah Status Eksemplar</h6>
                        <p class="text-muted small mb-2">Gunakan saat kondisi fisik buku berubah (rusak, hilang, perbaikan selesai).</p>
                        <ol class="small mb-2">
                            <li class="mb-1">Buka daftar koleksi fisik → temukan eksemplar via barcode atau nomor panggil.</li>
                            <li class="mb-1">Klik <strong>Ubah Status</strong>.</li>
                            <li class="mb-1">Pilih status baru dan tulis <strong>Catatan</strong> (misal: "Halaman 40–42 robek, ditemukan 10 Mei 2025").</li>
                            <li>Klik <strong>Simpan</strong>. Riwayat perubahan status tersimpan otomatis.</li>
                        </ol>
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Eksemplar berstatus <strong>Loaned</strong> tidak dapat diubah statusnya secara manual — tunggu sampai dikembalikan oleh Petugas Sirkulasi.
                        </div>
                    </div>
                </div>
            </div>

            {{-- D3 Riwayat --}}
            <div class="guide-step">
                <div class="d-flex gap-3">
                    <div class="step-badge bg-info text-white">D3</div>
                    <div class="w-100">
                        <h6 class="fw-bold mb-1">Melihat Riwayat Eksemplar</h6>
                        <ol class="small mb-0">
                            <li class="mb-1">Buka detail eksemplar.</li>
                            <li>Tab <strong>Riwayat</strong> menampilkan seluruh perubahan status beserta waktu dan petugas yang melakukannya.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN E: MONITORING
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="monitoring">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-secondary bg-opacity-10">
                    <i class="bi bi-eye-fill text-secondary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">E. Monitoring (View Only)</h4>
                    <small class="text-muted">Data yang bisa Anda pantau tanpa kewenangan edit</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-people me-1 text-primary"></i>Data Anggota</h6>
                        <p class="text-muted small mb-0">Lihat profil anggota dan detail keanggotaan. Berguna saat memverifikasi identitas anggota yang meminta bantuan. <strong>Tidak dapat mengedit data anggota.</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-arrow-left-right me-1 text-success"></i>Sirkulasi Aktif</h6>
                        <p class="text-muted small mb-0">Lihat daftar pinjaman aktif dan riwayat transaksi. Berguna untuk mengecek apakah suatu eksemplar sedang dipinjam. <strong>Proses pinjam/kembali dilakukan Petugas Sirkulasi.</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-cloud-arrow-down me-1 text-info"></i>Repositori Digital</h6>
                        <p class="text-muted small mb-0">Lihat dan pratinjau aset digital yang tersedia. Upload dan manajemen aset digital dilakukan oleh <strong>Operator Repositori Digital</strong>.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-bar-chart me-1 text-warning"></i>Laporan Koleksi</h6>
                        <p class="text-muted small mb-0">Akses laporan dashboard dan laporan koleksi (jumlah judul, eksemplar, jenis koleksi). Berguna untuk memantau kelengkapan katalog.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN F: ALUR KERJA HARIAN
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="alur-harian">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-primary bg-opacity-10">
                    <i class="bi bi-calendar-check-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">F. Alur Kerja Harian yang Disarankan</h4>
                    <small class="text-muted">Rutinitas untuk menjaga data tetap akurat</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary rounded-pill">Pagi</span>
                            <span class="small fw-semibold">Cek Dashboard</span>
                        </div>
                        <ul class="small text-muted mb-0">
                            <li>Lihat ringkasan koleksi di Dashboard</li>
                            <li>Periksa apakah ada katalog berstatus Draft yang perlu diselesaikan</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success rounded-pill">Siang</span>
                            <span class="small fw-semibold">Input Koleksi Baru</span>
                        </div>
                        <ul class="small text-muted mb-0">
                            <li>Katalogisasi buku/koleksi baru yang diterima</li>
                            <li>Tambah eksemplar fisik + cetak label barcode</li>
                            <li>Publikasikan setelah data lengkap</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark rounded-pill">Sore</span>
                            <span class="small fw-semibold">Update Status Fisik</span>
                        </div>
                        <ul class="small text-muted mb-0">
                            <li>Catat buku yang rusak/hilang ditemukan saat shelving</li>
                            <li>Update status eksemplar sesuai kondisi fisik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             BAGIAN G: PERTANYAAN UMUM
        ══════════════════════════════════════════════════════════════ --}}
        <div class="pq-card p-4 mb-4" id="faq">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="section-icon-wrap bg-danger bg-opacity-10">
                    <i class="bi bi-question-circle-fill text-danger fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">G. Pertanyaan Umum (FAQ)</h4>
                </div>
            </div>

            <div class="accordion" id="faqAccordion">
                <div class="accordion-item border mb-2 rounded-3" style="overflow:hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold small" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq1">
                            Saya tidak menemukan nama penulis saat input katalog — apa yang harus dilakukan?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Buka tab baru ke <strong>Master Data &rsaquo; Penulis</strong>, tambahkan nama penulis tersebut, lalu kembali ke form katalog dan refresh dropdown. Data akan langsung tersedia.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border mb-2 rounded-3" style="overflow:hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold small" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq2">
                            Koleksi sudah dipublikasi tapi tidak muncul di OPAC — mengapa?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Periksa dua hal: (1) Status koleksi memang <span class="badge bg-success">Published</span>, bukan Draft atau Unpublished. (2) Pastikan browser OPAC tidak menyimpan cache — coba buka mode incognito atau tekan Ctrl+Shift+R.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border mb-2 rounded-3" style="overflow:hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold small" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq3">
                            Bagaimana menangani buku yang baru dikembalikan dalam kondisi rusak?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Setelah Petugas Sirkulasi memproses pengembalian, status eksemplar kembali ke <span class="badge bg-success">Available</span>. Sebagai Pustakawan, Anda kemudian ubah statusnya ke <span class="badge bg-warning text-dark">Damaged</span> dengan catatan kerusakan, lalu koordinasikan dengan pimpinan untuk penanganan lebih lanjut.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border mb-2 rounded-3" style="overflow:hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold small" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq4">
                            Bisakah Pustakawan menghapus katalog atau data master?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Tidak. Akses Anda dibatasi pada operasi <em>create</em> dan <em>update</em>. Penghapusan data bibliografi dan data master hanya dapat dilakukan oleh <strong>Admin Perpustakaan</strong> atau <strong>Super Admin</strong>. Hubungi admin jika ada data yang perlu dihapus.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border rounded-3" style="overflow:hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold small" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq5">
                            Bagaimana cara mengecek apakah suatu buku sedang dipinjam?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Ada dua cara: (1) Buka detail eksemplar fisik di modul Koleksi — statusnya <span class="badge bg-primary">Loaned</span> jika sedang dipinjam. (2) Buka <strong>Sirkulasi &rsaquo; Pinjaman Aktif</strong> dan cari berdasarkan judul atau barcode.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permission summary --}}
        <div class="pq-card p-4 mb-4" id="permissions">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="section-icon-wrap bg-dark bg-opacity-10">
                    <i class="bi bi-shield-check text-dark fs-4"></i>
                </div>
                <h4 class="fw-bold mb-0">H. Ringkasan Hak Akses Pustakawan</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered state-row">
                    <thead class="table-light">
                        <tr><th>Modul</th><th>Dapat Dilakukan</th><th>Tidak Dapat</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">Master Data</td>
                            <td class="text-success">Lihat, Tambah, Edit</td>
                            <td class="text-danger">Hapus</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Katalog</td>
                            <td class="text-success">Lihat, Tambah, Edit, Publish/Unpublish</td>
                            <td class="text-danger">Hapus, Archive</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Koleksi Fisik</td>
                            <td class="text-success">Lihat, Tambah, Edit, Ubah Status, Lihat Riwayat</td>
                            <td class="text-danger">Hapus eksemplar</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Anggota</td>
                            <td class="text-success">Lihat detail</td>
                            <td class="text-danger">Tambah, Edit, Hapus</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Sirkulasi</td>
                            <td class="text-success">Lihat pinjaman aktif & riwayat</td>
                            <td class="text-danger">Proses pinjam/kembali/denda</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Repositori Digital</td>
                            <td class="text-success">Lihat & pratinjau</td>
                            <td class="text-danger">Upload, edit, hapus aset</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Laporan</td>
                            <td class="text-success">Dashboard & laporan koleksi</td>
                            <td class="text-danger">Laporan anggota, sirkulasi, denda</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Pengaturan Sistem</td>
                            <td class="text-success">Profil sendiri</td>
                            <td class="text-danger">Pengaturan institusi, manajemen user</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ───────────────────────────────────────────────────────────────────
         SIDEBAR TOC
    ─────────────────────────────────────────────────────────────────── --}}
    <div class="col-lg-3">
        <div class="pq-card p-3 guide-toc">
            <p class="small fw-bold text-muted text-uppercase mb-2" style="letter-spacing:.6px">Daftar Isi</p>
            <nav class="d-flex flex-column gap-1">
                <a href="#akun" class="toc-link"><i class="bi bi-person-circle"></i> A. Akun & Profil</a>
                <a href="#master-data" class="toc-link"><i class="bi bi-tags"></i> B. Master Data</a>
                <a href="#katalog" class="toc-link"><i class="bi bi-book"></i> C. Katalogisasi</a>
                <a href="#koleksi" class="toc-link"><i class="bi bi-boxes"></i> D. Koleksi Fisik</a>
                <a href="#monitoring" class="toc-link"><i class="bi bi-eye"></i> E. Monitoring</a>
                <a href="#alur-harian" class="toc-link"><i class="bi bi-calendar-check"></i> F. Alur Harian</a>
                <a href="#faq" class="toc-link"><i class="bi bi-question-circle"></i> G. FAQ</a>
                <a href="#permissions" class="toc-link"><i class="bi bi-shield-check"></i> H. Hak Akses</a>
            </nav>
            <hr class="my-3">
            <div class="d-flex flex-column gap-1">
                <a href="{{ route('admin.guides.superadmin') }}" class="toc-link">
                    <i class="bi bi-arrow-left-short"></i> Panduan Admin
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
