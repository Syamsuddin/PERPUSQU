@extends('layouts.admin')
@section('title', 'Daftar Item Fisik')
@section('page-title', 'Koleksi Fisik')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Item Fisik</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Daftar Item Fisik</h5><small class="text-muted">{{ $items->total() }} data terdaftar</small></div>
    @can('collections.create')
    <a href="{{ route('admin.collections.items.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Item</a>
    @endcan
</div>
{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari barcode, kode inventaris..."></div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="item_status">
                <option value="">Semua Status</option>
                @foreach(['available'=>'Tersedia','loaned'=>'Dipinjam','damaged'=>'Rusak','lost'=>'Hilang','repair'=>'Perbaikan','inactive'=>'Nonaktif'] as $val => $lbl)
                <option value="{{ $val }}" {{ request('item_status')===$val?'selected':'' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="rack_location_id">
                <option value="">Semua Rak</option>
                @foreach($rackLocations as $rl)<option value="{{ $rl->id }}" {{ request('rack_location_id')==$rl->id?'selected':'' }}>{{ $rl->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="item_condition_id">
                <option value="">Semua Kondisi</option>
                @foreach($itemConditions as $ic)<option value="{{ $ic->id }}" {{ request('item_condition_id')==$ic->id?'selected':'' }}>{{ $ic->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.collections.items.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        </div>
    </form>
</div></div>
{{-- Table --}}
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Barcode</th><th>Judul Katalog</th><th>Rak</th><th>Kondisi</th><th>Status</th><th class="text-center" style="width:140px">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td><code class="fw-bold">{{ $item->barcode }}</code><br><small class="text-muted">{{ $item->inventory_code ?? '-' }}</small></td>
                <td><a href="{{ route('admin.collections.items.show', $item) }}" class="text-decoration-none">{{ Str::limit($item->bibliographicRecord->title ?? '-', 50) }}</a></td>
                <td><small>{{ $item->rackLocation->name ?? '-' }}</small></td>
                <td><small>{{ $item->itemCondition->name ?? '-' }}</small></td>
                <td>
                    @php $bg = \App\Modules\Collection\Support\PhysicalItemStateGuard::statusBadgeClass($item->item_status); @endphp
                    <span class="badge bg-{{ $bg }}">{{ \App\Modules\Collection\Support\PhysicalItemStateGuard::statusLabel($item->item_status) }}</span>
                </td>
                <td class="text-center"><div class="btn-group btn-group-sm">
                    @can('collections.view')<a href="{{ route('admin.collections.items.show', $item) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>@endcan
                    @can('collections.update')<a href="{{ route('admin.collections.items.edit', $item) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                    @can('collections.delete')
                    <form method="POST" action="{{ route('admin.collections.items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus item ini?')">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                    </form>@endcan
                </div></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada item fisik.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
