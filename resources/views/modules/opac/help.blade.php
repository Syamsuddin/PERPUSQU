@extends('layouts.opac')
@section('title', 'Bantuan')

@section('hero')
<div class="opac-hero" style="padding:2.5rem 0 2rem;">
    <div class="hero-inner">
        <div class="container">
            <h1 style="font-size:1.9rem"><i class="bi bi-question-circle me-2"></i>Bantuan OPAC</h1>
            <p>Panduan menggunakan katalog publik online perpustakaan</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="content-card p-5">

            <h2 class="mb-1"><i class="bi bi-question-circle me-2" style="color:var(--gold)"></i>Bantuan OPAC</h2>
            <p class="small mb-4" style="color:var(--text-muted)">Online Public Access Catalog — GIBTHA LIBRARY</p>

            {{-- What is OPAC --}}
            <h5 class="mb-2">Apa itu OPAC?</h5>
            <p style="color:var(--text-body)">
                OPAC (Online Public Access Catalog) adalah layanan pencarian koleksi perpustakaan secara online.
                Anda dapat mencari buku, jurnal, dan aset digital yang tersedia di perpustakaan tanpa perlu
                mengunjungi langsung meja katalog.
            </p>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- Steps --}}
            <h5 class="mb-3">Langkah Pencarian</h5>
            <ol style="color:var(--text-body); line-height:1.9">
                <li>Masukkan kata kunci di kotak pencarian (judul, nama penulis, atau ISBN)</li>
                <li>Gunakan filter di sisi kiri untuk mempersempit hasil berdasarkan jenis, bahasa, atau tahun</li>
                <li>Klik judul koleksi untuk melihat detail lengkap</li>
                <li>Periksa ketersediaan eksemplar fisik pada bagian informasi ketersediaan</li>
                <li>Akses aset digital (jika tersedia) melalui tombol <strong>Preview</strong></li>
            </ol>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- Tips --}}
            <h5 class="mb-3">Tips Pencarian Efektif</h5>
            <div class="table-responsive">
                <table class="table table-sm" style="color:var(--text-body)">
                    <thead>
                        <tr>
                            <th style="width:45%">Contoh Input</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code style="color:var(--green-main)">manajemen keuangan</code></td>
                            <td>Cari judul atau penulis yang mengandung kata tersebut</td>
                        </tr>
                        <tr>
                            <td><code style="color:var(--green-main)">978-602-xxxx</code></td>
                            <td>Cari berdasarkan nomor ISBN</td>
                        </tr>
                        <tr>
                            <td>Filter <em>Jenis Koleksi</em></td>
                            <td>Pilih "Buku", "Jurnal", dll. untuk mempersempit hasil</td>
                        </tr>
                        <tr>
                            <td>Filter <em>Bahasa</em></td>
                            <td>Tampilkan koleksi dalam bahasa tertentu saja</td>
                        </tr>
                        <tr>
                            <td>Filter <em>Tahun Terbit</em></td>
                            <td>Batasi hasil hanya pada tahun tertentu</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- More help --}}
            <h5 class="mb-2">Butuh Bantuan Lebih Lanjut?</h5>
            <p class="mb-0" style="color:var(--text-body)">
                Hubungi pustakawan kami melalui email
                <a href="mailto:perpustakaan@gibthalibrary.ac.id" style="color:var(--green-main); font-weight:600">
                    perpustakaan@gibthalibrary.ac.id
                </a>
                atau kunjungi langsung meja layanan di lantai 1 gedung perpustakaan.
            </p>

        </div>
    </div>
</div>
@endsection
