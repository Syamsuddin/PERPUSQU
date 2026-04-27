{{-- Generic master data index template - @include with: $pageTitle, $permCreate, $routePrefix, $items, $columns, $createRoute --}}
@extends('layouts.admin')
@section('title', $pageTitle)
@section('page-title', $pageTitle)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-semibold mb-0">{{ $pageTitle }}</h5>
        <small class="text-muted">{{ $items->total() }} data terdaftar</small>
    </div>
    @can($permCreate)
    <a href="{{ route($createRoute) }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah</a>
    @endcan
</div>
<div class="pq-card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari...">
        </div>
        <div class="col-md-3">
            <select class="form-select form-select-sm" name="is_active">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        @yield('extra-filters')
        <div class="col-md-4 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route($routePrefix . '.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        </div>
    </form>
</div>
<div class="pq-card">
    <div class="table-responsive">
        <table class="table pq-table mb-0">
            <thead><tr>
                <th style="width:50px">#</th>
                @yield('table-headers')
                <th>Status</th>
                <th class="text-center" style="width:120px">Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $items->firstItem() + $loop->index }}</td>
                    @yield('table-columns')
                    <td><span class="badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            @can($permEdit ?? str_replace('.view', '.update', $permCreate))
                            <a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can($permDelete ?? str_replace('.view', '.delete', $permCreate))
                            <form method="POST" action="{{ route($routePrefix . '.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="99" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif
</div>
@endsection
