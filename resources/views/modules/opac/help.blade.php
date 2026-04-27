@extends('layouts.opac')
@section('title', 'Bantuan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-5">
            <h2 class="fw-bold mb-4" style="color:var(--opac-primary)"><i class="bi bi-question-circle me-2"></i>Bantuan OPAC</h2>

            <h5 class="fw-bold mb-2">Cara Menggunakan OPAC</h5>
            <p class="text-muted">OPAC (Online Public Access Catalog) adalah layanan pencarian koleksi perpustakaan secara online. Anda dapat mencari buku, jurnal, dan aset digital yang tersedia di perpustakaan.</p>

            <h5 class="fw-bold mb-2 mt-4">Langkah Pencarian</h5>
            <ol class="text-muted">
                <li>Masukkan kata kunci di kotak pencarian (judul, nama penulis, atau ISBN)</li>
                <li>Gunakan filter di sisi kiri untuk mempersempit hasil</li>
                <li>Klik judul untuk melihat detail lengkap koleksi</li>
                <li>Periksa ketersediaan eksemplar fisik di bagian kanan</li>
                <li>Akses aset digital (jika tersedia) melalui tombol Preview</li>
            </ol>

            <h5 class="fw-bold mb-2 mt-4">Tips Pencarian</h5>
            <div class="table-responsive">
            <table class="table table-sm text-muted">
                <thead><tr><th>Contoh</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr><td><code>manajemen keuangan</code></td><td>Cari judul/penulis yang mengandung kata tersebut</td></tr>
                    <tr><td><code>978-602-xxxx</code></td><td>Cari berdasarkan ISBN</td></tr>
                    <tr><td>Filter Jenis Koleksi</td><td>Pilih "Buku", "Jurnal", dll. untuk mempersempit</td></tr>
                    <tr><td>Filter Bahasa</td><td>Pilih bahasa untuk menampilkan koleksi dalam bahasa tertentu</td></tr>
                </tbody>
            </table>
            </div>

            <h5 class="fw-bold mb-2 mt-4">Butuh Bantuan Lebih?</h5>
            <p class="text-muted mb-0">Hubungi pustakawan kami di <a href="mailto:perpustakaan@perpusqu.ac.id">perpustakaan@perpusqu.ac.id</a> atau kunjungi meja layanan di lantai 1.</p>
        </div>
    </div>
</div>
@endsection
