@extends('layouts.admin')
@section('title', 'Daftar Denda')
@section('page-title', 'Manajemen Denda')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Denda</li>
@endsection
@section('content')
{{-- Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body text-center">
        <div class="fs-4 fw-bold text-warning">Rp {{ number_format($summary['total_outstanding'], 0, ',', '.') }}</div>
        <small class="text-muted">Outstanding ({{ $summary['count_outstanding'] }})</small>
    </div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body text-center">
        <div class="fs-4 fw-bold text-success">Rp {{ number_format($summary['total_settled'], 0, ',', '.') }}</div>
        <small class="text-muted">Lunas ({{ $summary['count_settled'] }})</small>
    </div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body text-center">
        <div class="fs-4 fw-bold text-info">Rp {{ number_format($summary['total_waived'], 0, ',', '.') }}</div>
        <small class="text-muted">Dihapuskan ({{ $summary['count_waived'] }})</small>
    </div></div></div>
</div>
{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="status">
                <option value="">Semua Status</option>
                @foreach(['outstanding'=>'Outstanding','settled'=>'Lunas','waived'=>'Dihapuskan'] as $v => $l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select form-select-sm" name="fine_type">
                <option value="">Semua Tipe</option>
                @foreach(['overdue'=>'Keterlambatan','damage'=>'Kerusakan','loss'=>'Kehilangan'] as $v => $l)
                <option value="{{ $v }}" {{ request('fine_type')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}"></div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}"></div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.circulation.fines.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
        </div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Anggota</th><th>Tipe</th><th>Hari Terlambat</th><th class="text-end">Jumlah</th><th>Status</th><th class="text-center" style="width:140px">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>{{ $item->member->name ?? '-' }}</td>
                <td><small>{{ ucfirst($item->fine_type) }}</small></td>
                <td>{{ $item->late_days }} hari</td>
                <td class="text-end fw-medium">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                <td><span class="badge bg-{{ match($item->status) { 'outstanding' => 'warning', 'settled' => 'success', 'waived' => 'info', default => 'secondary' } }}">{{ ucfirst($item->status) }}</span></td>
                <td class="text-center">
                    @if($item->status === 'outstanding')
                    <div class="btn-group btn-group-sm">
                        @can('circulation.fine')
                        <form method="POST" action="{{ route('admin.circulation.fines.settle', $item) }}" class="d-inline" onsubmit="return confirm('Lunasi denda ini?')">@csrf
                            <button type="submit" class="btn btn-outline-success" title="Lunasi"><i class="bi bi-check-circle"></i></button>
                        </form>
                        <form method="POST" action="{{ route('admin.circulation.fines.waive', $item) }}" class="d-inline" onsubmit="return confirm('Hapuskan denda ini?')">@csrf
                            <button type="submit" class="btn btn-outline-info" title="Hapuskan"><i class="bi bi-x-circle"></i></button>
                        </form>
                        @endcan
                    </div>
                    @else
                    <small class="text-muted">—</small>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada denda.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
