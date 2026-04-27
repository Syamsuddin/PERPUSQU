@extends('layouts.admin')
@section('title', 'Histori Sirkulasi')
@section('page-title', 'Histori Sirkulasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Histori Sirkulasi</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h5 class="fw-semibold mb-0">Histori Sirkulasi</h5><small class="text-muted">{{ $items->total() }} transaksi</small></div>
</div>
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Cari anggota/barcode..."></div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}"></div>
        <div class="col-md-2"><input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}"></div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.circulation.loans.history') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
        </div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr>
            <th style="width:50px">#</th><th>Anggota</th><th>Item</th><th>Pinjam</th><th>Kembali</th><th>Status</th><th class="text-center">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>{{ $item->member->name ?? '-' }}<br><small class="text-muted">{{ $item->member->member_number ?? '' }}</small></td>
                <td><code>{{ $item->physicalItem->barcode ?? '-' }}</code></td>
                <td>{{ $item->loan_date->format('d/m/Y') }}</td>
                <td>{{ $item->returned_at ? $item->returned_at->format('d/m/Y') : '-' }}</td>
                <td><span class="badge bg-{{ $item->loan_status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($item->loan_status) }}</span></td>
                <td class="text-center"><a href="{{ route('admin.circulation.loans.show', $item) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($items->hasPages())<div class="p-3">{{ $items->withQueryString()->links() }}</div>@endif</div>
@endsection
