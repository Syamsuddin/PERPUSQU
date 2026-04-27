@extends('layouts.admin')
@section('title', 'Daftar Katalog')
@section('page-title', 'Katalog Bibliografi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Katalog</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Daftar Katalog</h5><small class="text-muted">{{ $items->total() }} data terdaftar</small></div>
    @can('catalog.create')
    <a href="{{ route('admin.catalog.records.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Katalog</a>
    @endcan
</div>

{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari judul, ISBN, kata kunci...">
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="collection_type_id">
                <option value="">Semua Jenis</option>
                @foreach($collectionTypes as $ct)
                <option value="{{ $ct->id }}" {{ request('collection_type_id')==$ct->id?'selected':'' }}>{{ $ct->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="language_id">
                <option value="">Semua Bahasa</option>
                @foreach($languages as $lang)
                <option value="{{ $lang->id }}" {{ request('language_id')==$lang->id?'selected':'' }}>{{ $lang->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="publication_status">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('publication_status')==='draft'?'selected':'' }}>Draft</option>
                <option value="published" {{ request('publication_status')==='published'?'selected':'' }}>Published</option>
                <option value="unpublished" {{ request('publication_status')==='unpublished'?'selected':'' }}>Unpublished</option>
                <option value="archived" {{ request('publication_status')==='archived'?'selected':'' }}>Archived</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.catalog.records.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        </div>
    </form>
</div></div>

{{-- Table --}}
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:50px">#</th>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Tahun</th>
                <th>Status</th>
                <th class="text-center">Item</th>
                <th class="text-center" style="width:140px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>
                    <a href="{{ route('admin.catalog.records.show', $item) }}" class="text-decoration-none fw-medium">{{ $item->title }}</a>
                    <div class="small text-muted">
                        {{ $item->isbn ? 'ISBN: '.$item->isbn : '' }}
                        {{ $item->authors_count > 0 ? '· '.$item->authors_count.' pengarang' : '' }}
                    </div>
                </td>
                <td><small>{{ $item->collectionType->name ?? '-' }}</small></td>
                <td>{{ $item->publication_year ?? '-' }}</td>
                <td>
                    @php
                        $statusBg = match($item->publication_status) {
                            'draft' => 'warning',
                            'published' => 'success',
                            'unpublished' => 'secondary',
                            'archived' => 'dark',
                            default => 'light',
                        };
                    @endphp
                    <span class="badge bg-{{ $statusBg }}">{{ ucfirst($item->publication_status) }}</span>
                    @if($item->is_public)<i class="bi bi-globe2 text-info ms-1" title="Publik"></i>@endif
                </td>
                <td class="text-center"><span class="badge bg-info">{{ $item->physical_items_count }}</span></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        @can('catalog.view')
                        <a href="{{ route('admin.catalog.records.show', $item) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                        @endcan
                        @can('catalog.update')
                        <a href="{{ route('admin.catalog.records.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        @endcan
                        @can('catalog.delete')
                        <form method="POST" action="{{ route('admin.catalog.records.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus katalog ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada katalog.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif
</div>
@endsection
