@extends('layouts.admin')
@section('title', 'Import Excel Buku Cetak')
@section('page-title', 'Import Excel Buku Cetak')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item active">Import Excel</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius:.75rem">
            <div class="card-header bg-white border-bottom fw-semibold py-3" style="border-radius:.75rem .75rem 0 0">
                <i class="bi bi-file-earmark-excel me-1 text-success"></i> Upload File Import
            </div>
            <div class="card-body p-4 text-center">
                
                <h5 class="mb-3">Langkah 1: Unduh Template</h5>
                <p class="text-muted mb-4">Silakan unduh template Excel yang telah disediakan, kemudian isi data buku cetak sesuai format. Jangan mengubah struktur kolom (Header) pada baris pertama.</p>
                <a href="{{ route('admin.catalog.bulk-import.template') }}" class="btn btn-outline-success mb-5">
                    <i class="bi bi-download me-2"></i> Unduh Template Excel
                </a>

                <hr class="mb-5">

                <h5 class="mb-3">Langkah 2: Upload File</h5>
                <form action="{{ route('admin.catalog.bulk-import.preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 text-start">
                        <label class="form-label fw-medium">Pilih File Excel (.xlsx, .xls)</label>
                        <input type="file" name="excel_file" class="form-control @error('excel_file') is-invalid @enderror" accept=".xlsx, .xls" required>
                        @error('excel_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary text-white" style="background:#1e3a5f;">
                            <i class="bi bi-upload me-2"></i> Unggah dan Pratinjau
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
