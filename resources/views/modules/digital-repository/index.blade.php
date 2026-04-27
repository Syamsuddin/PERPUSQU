@extends('layouts.admin')
@section('title', 'Repositori Digital')
@section('page-title', 'Repositori Digital')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Repositori Digital</li>
@endsection
@section('content')
@php
$statusBadge = ['draft'=>'secondary','published'=>'success','unpublished'=>'warning','archived'=>'dark'];
$ocrBadge = ['not_requested'=>'light','queued'=>'info','processing'=>'primary','success'=>'success','failed'=>'danger'];
$typeLabels = ['ebook'=>'E-Book','thesis'=>'Skripsi','dissertation'=>'Disertasi','journal_article'=>'Artikel Jurnal','module'=>'Modul','scanned_book'=>'Buku Scan','supplementary'=>'Suplemen','other'=>'Lainnya'];
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Aset Digital</h5><small class="text-muted">{{ $items->total() }} aset terdaftar</small></div>
    @can('digital_assets.create')
    <a href="{{ route('admin.digital-assets.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-cloud-upload me-1"></i>Upload Aset</a>
    @endcan
</div>
{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul, file..."></div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="asset_type">
                <option value="">Semua Tipe</option>
                @foreach($typeLabels as $v => $l)<option value="{{ $v }}" {{ request('asset_type')===$v?'selected':'' }}>{{ $l }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="publication_status">
                <option value="">Semua Status</option>
                @foreach(['draft'=>'Draft','published'=>'Published','unpublished'=>'Unpublished','archived'=>'Archived'] as $v => $l)
                <option value="{{ $v }}" {{ request('publication_status')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="ocr_status">
                <option value="">Semua OCR</option>
                @foreach(['not_requested'=>'Belum','queued'=>'Antrian','processing'=>'Proses','success'=>'Sukses','failed'=>'Gagal'] as $v => $l)
                <option value="{{ $v }}" {{ request('ocr_status')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.digital-assets.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
        </div>
    </form>
</div></div>
{{-- Table --}}
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Judul / File</th><th>Tipe</th><th>Ukuran</th><th>Publikasi</th><th>OCR</th><th>Visibilitas</th><th class="text-center" style="width:140px">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>
                    <a href="{{ route('admin.digital-assets.show', $item) }}" class="text-decoration-none fw-medium">{{ $item->title ?: $item->original_file_name }}</a>
                    <br><small class="text-muted">{{ Str::limit($item->bibliographicRecord->title ?? '', 50) }}</small>
                </td>
                <td><small>{{ $typeLabels[$item->asset_type] ?? ucfirst($item->asset_type) }}</small></td>
                <td><small>{{ $item->file_size_formatted }}</small></td>
                <td><span class="badge bg-{{ $statusBadge[$item->publication_status] ?? 'light' }}">{{ ucfirst($item->publication_status) }}</span></td>
                <td><span class="badge bg-{{ $ocrBadge[$item->ocr_status] ?? 'light' }}">{{ ucfirst($item->ocr_status) }}</span></td>
                <td>{!! $item->is_public ? '<i class="bi bi-globe text-success"></i> Publik' : '<i class="bi bi-lock text-muted"></i> Privat' !!}</td>
                <td class="text-center"><div class="btn-group btn-group-sm">
                    @can('digital_assets.view_detail')<a href="{{ route('admin.digital-assets.show', $item) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>@endcan
                    @can('digital_assets.update')<a href="{{ route('admin.digital-assets.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                    @can('digital_assets.delete')
                    <form method="POST" action="{{ route('admin.digital-assets.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus aset ini?')">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                    </form>@endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-cloud-slash fs-3 d-block mb-2"></i>Belum ada aset digital.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
