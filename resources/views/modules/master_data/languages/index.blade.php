@extends('layouts.admin')
@section('title', 'Daftar Bahasa')
@section('page-title', 'Bahasa')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Bahasa</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Daftar Bahasa</h5><small class="text-muted">{{ $items->total() }} data terdaftar</small></div>
    @can('languages.create')<a href="{{ route('admin.master-data.languages.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah</a>@endcan
</div>
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari..."></div>
        <div class="col-md-3"><select class="form-select form-select-sm" name="is_active"><option value="">Semua Status</option><option value="1" {{ request('is_active')==='1'?'selected':'' }}>Aktif</option><option value="0" {{ request('is_active')==='0'?'selected':'' }}>Nonaktif</option></select></div>
        <div class="col-md-4 d-flex gap-1"><button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button><a href="{{ route('admin.master-data.languages.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a></div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th style="width:50px">#</th><th>Kode</th><th>Nama</th><th>Status</th><th class="text-center" style="width:120px">Aksi</th></tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                    <td>{{ $item->code }}</td>
                    <td class="fw-medium">{{ $item->name }}</td>
                    <td><span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td class="text-center"><div class="btn-group btn-group-sm">
                    @can('languages.update')<a href="{{ route('admin.master-data.languages.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                    @can('languages.delete')<form method="POST" action="{{ route('admin.master-data.languages.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>@endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection