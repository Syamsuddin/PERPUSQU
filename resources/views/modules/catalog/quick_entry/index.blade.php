@extends('layouts.admin')
@section('title', 'Tambah Koleksi')
@section('page-title', 'Tambah Koleksi Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item active">Tambah Koleksi</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Header --}}
        <div class="text-center mb-5">
            <div class="mb-3">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-6">
                    <i class="bi bi-lightning-fill me-1"></i> Pilih Jalur Penginputan
                </span>
            </div>
            <h2 class="fw-bold mb-2">Ingin menambahkan koleksi jenis apa?</h2>
            <p class="text-muted">Pilih jalur sesuai jenis koleksi. Sistem akan menampilkan form yang relevan secara otomatis.</p>
        </div>

        {{-- Pilihan --}}
        <div class="row g-4">

            {{-- Jalur 1: Buku Cetak --}}
            <div class="col-md-6">
                <a href="{{ route('admin.catalog.quick-entry.cetak.create') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm quick-entry-card" style="border-top: 4px solid #1e3a5f !important; border-radius: 1rem;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <span style="font-size: 3.5rem;">📚</span>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: #1e3a5f;">Buku Cetak</h4>
                            <p class="text-muted small mb-3">Koleksi fisik yang ada di rak perpustakaan. Sistem akan langsung membuat item & barcode secara otomatis.</p>
                            <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                                <span class="badge bg-light text-dark"><i class="bi bi-journal-bookmark me-1"></i>Metadata Katalog</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-upc-scan me-1"></i>Barcode Otomatis</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-bookshelf me-1"></i>Lokasi Rak</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-stack me-1"></i>Jumlah Eksemplar</span>
                            </div>
                            <div class="btn btn-primary w-100" style="background: #1e3a5f; border: none;">
                                <i class="bi bi-arrow-right me-2"></i>Pilih Jalur Ini
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Jalur 2: E-Book --}}
            <div class="col-md-6">
                <a href="{{ route('admin.catalog.quick-entry.ebook.create') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm quick-entry-card" style="border-top: 4px solid #38b2ac !important; border-radius: 1rem;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <span style="font-size: 3.5rem;">💻</span>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: #2c7a7b;">E-Book</h4>
                            <p class="text-muted small mb-3">Koleksi digital dalam format PDF. Sistem langsung menyimpan file dan mendaftarkan aset digital.</p>
                            <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                                <span class="badge bg-light text-dark"><i class="bi bi-journal-bookmark me-1"></i>Metadata Katalog</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-file-pdf me-1"></i>Upload PDF</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-globe me-1"></i>Akses Publik/Privat</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-cpu me-1"></i>OCR Ready</span>
                            </div>
                            <div class="btn btn-success w-100" style="background: #38b2ac; border: none;">
                                <i class="bi bi-arrow-right me-2"></i>Pilih Jalur Ini
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- Alternatif --}}
        <div class="text-center mt-4">
            <p class="text-muted small">
                Ingin input metadata katalog saja tanpa item/file?
                <a href="{{ route('admin.catalog.records.create') }}" class="text-decoration-none">Gunakan form katalog standar →</a>
            </p>
        </div>

    </div>
</div>

@push('styles')
<style>
.quick-entry-card { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
.quick-entry-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important; }
</style>
@endpush
@endsection
