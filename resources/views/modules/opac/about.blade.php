@extends('layouts.opac')
@section('title', 'Tentang Perpustakaan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-5">
            <h2 class="fw-bold mb-4" style="color:var(--opac-primary)"><i class="bi bi-info-circle me-2"></i>Tentang PERPUSQU</h2>

            <h5 class="fw-bold mb-2">Selamat Datang</h5>
            <p class="text-muted">PERPUSQU adalah sistem manajemen perpustakaan terintegrasi yang melayani civitas akademika. Kami menyediakan akses ke koleksi buku, jurnal, majalah, dan aset digital untuk mendukung kegiatan pendidikan, penelitian, dan pengabdian masyarakat.</p>

            <h5 class="fw-bold mb-2 mt-4">Layanan Kami</h5>
            <ul class="text-muted">
                <li>Peminjaman dan pengembalian buku</li>
                <li>Akses ke repositori digital (e-book, jurnal, skripsi)</li>
                <li>Ruang baca dan fasilitas belajar</li>
                <li>Layanan referensi dan konsultasi</li>
                <li>Penelusuran informasi dan bantuan riset</li>
            </ul>

            <h5 class="fw-bold mb-2 mt-4">Jam Operasional</h5>
            <table class="table table-sm text-muted" style="max-width:400px">
                <tr><td>Senin – Jumat</td><td>08:00 – 16:00 WIB</td></tr>
                <tr><td>Sabtu</td><td>08:00 – 12:00 WIB</td></tr>
                <tr><td>Minggu & Hari Libur</td><td>Tutup</td></tr>
            </table>

            <h5 class="fw-bold mb-2 mt-4">Kontak</h5>
            <p class="text-muted mb-0">
                <i class="bi bi-envelope me-1"></i>perpustakaan@perpusqu.ac.id<br>
                <i class="bi bi-telephone me-1"></i>(021) 123-4567
            </p>
        </div>
    </div>
</div>
@endsection
