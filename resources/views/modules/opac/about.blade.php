@extends('layouts.opac')
@section('title', 'Tentang Perpustakaan')

@section('hero')
<div class="opac-hero" style="padding:2.5rem 0 2rem;">
    <div class="hero-inner">
        <div class="container">
            <h1 style="font-size:1.9rem"><i class="bi bi-info-circle me-2"></i>Tentang Perpustakaan</h1>
            <p>Kenali layanan dan fasilitas GIBTHA LIBRARY</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="content-card p-5">

            {{-- Welcome --}}
            <h2 class="mb-1"><i class="bi bi-book-half me-2" style="color:var(--gold)"></i>GIBTHA LIBRARY</h2>
            <p class="small mb-4" style="color:var(--text-muted)">Sistem Manajemen Perpustakaan Terintegrasi</p>

            <p style="color:var(--text-body)">
                GIBTHA LIBRARY adalah sistem manajemen perpustakaan terintegrasi yang melayani civitas akademika.
                Kami menyediakan akses ke koleksi buku, jurnal, majalah, dan aset digital untuk mendukung kegiatan
                pendidikan, penelitian, dan pengabdian masyarakat.
            </p>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- Services --}}
            <h5 class="mb-3">Layanan Kami</h5>
            <ul class="list-unstyled" style="color:var(--text-body)">
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill mt-1" style="color:var(--green-mid); flex-shrink:0"></i>
                    Peminjaman dan pengembalian buku
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill mt-1" style="color:var(--green-mid); flex-shrink:0"></i>
                    Akses ke repositori digital (e-book, jurnal, skripsi)
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill mt-1" style="color:var(--green-mid); flex-shrink:0"></i>
                    Ruang baca dan fasilitas belajar
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill mt-1" style="color:var(--green-mid); flex-shrink:0"></i>
                    Layanan referensi dan konsultasi
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill mt-1" style="color:var(--green-mid); flex-shrink:0"></i>
                    Penelusuran informasi dan bantuan riset
                </li>
            </ul>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- Hours --}}
            <h5 class="mb-3">Jam Operasional</h5>
            <table class="table table-sm" style="max-width:380px; color:var(--text-body)">
                <tbody>
                    <tr>
                        <td class="fw-semibold" style="color:var(--text-dark)">Senin – Jumat</td>
                        <td>08:00 – 16:00 WIB</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold" style="color:var(--text-dark)">Sabtu</td>
                        <td>08:00 – 12:00 WIB</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold" style="color:var(--text-muted)">Minggu &amp; Hari Libur</td>
                        <td style="color:var(--text-muted)">Tutup</td>
                    </tr>
                </tbody>
            </table>

            <hr style="border-color:var(--border); margin:1.5rem 0">

            {{-- Contact --}}
            <h5 class="mb-3">Hubungi Kami</h5>
            <p class="mb-1" style="color:var(--text-body)">
                <i class="bi bi-envelope me-2" style="color:var(--green-mid)"></i>
                <a href="mailto:perpustakaan@gibthalibrary.ac.id" style="color:var(--green-main)">
                    perpustakaan@gibthalibrary.ac.id
                </a>
            </p>
            <p class="mb-0" style="color:var(--text-body)">
                <i class="bi bi-telephone me-2" style="color:var(--green-mid)"></i>(021) 123-4567
            </p>

        </div>
    </div>
</div>
@endsection
