@extends('layouts.admin')
@section('title', 'Pinjaman Aktif')
@section('page-title', 'Pinjaman Aktif')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pinjaman Aktif</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Pinjaman Aktif</h5><small class="text-muted">{{ $items->total() }} pinjaman aktif</small></div>
    <div class="d-flex gap-2">
        @can('circulation.create')<a href="{{ route('admin.circulation.loans.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Pinjam Baru</a>@endcan
        @can('circulation.return')<a href="{{ route('admin.circulation.returns.create') }}" class="btn btn-sm btn-success"><i class="bi bi-box-arrow-in-left me-1"></i>Pengembalian</a>@endcan
    </div>
</div>
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari anggota/barcode..."></div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="is_overdue">
                <option value="">Semua</option>
                <option value="1" {{ request('is_overdue')==='1'?'selected':'' }}>Overdue Saja</option>
            </select>
        </div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}" placeholder="Dari"></div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}" placeholder="Sampai"></div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.circulation.loans.active') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
        </div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Anggota</th><th>Item</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th class="text-center" style="width:120px">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            @php $isOverdue = $item->due_date->isPast(); @endphp
            <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td><a href="{{ route('admin.circulation.loans.show', $item) }}" class="text-decoration-none fw-medium">{{ $item->member->name ?? '-' }}</a><br><small class="text-muted">{{ $item->member->member_number ?? '' }}</small></td>
                <td><code>{{ $item->physicalItem->barcode ?? '-' }}</code><br><small class="text-muted">{{ Str::limit($item->physicalItem->bibliographicRecord->title ?? '', 40) }}</small></td>
                <td>{{ $item->loan_date->format('d/m/Y') }}</td>
                <td>{{ $item->due_date->format('d/m/Y') }}</td>
                <td>
                    @if($isOverdue)
                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Overdue ({{ now()->diffInDays($item->due_date) }} hari)</span>
                    @else
                    <span class="badge bg-success">Normal</span>
                    @endif
                </td>
                <td class="text-center"><div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.circulation.loans.show', $item) }}" class="btn btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada pinjaman aktif.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
