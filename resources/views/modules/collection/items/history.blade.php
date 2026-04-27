@extends('layouts.admin')
@section('title', 'Riwayat Status — ' . $item->barcode)
@section('page-title', 'Riwayat Status Item')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.collections.items.index') }}">Item Fisik</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.collections.items.show', $item) }}">{{ $item->barcode }}</a></li>
    <li class="breadcrumb-item active">Riwayat</li>
@endsection
@section('content')
@php $guard = \App\Modules\Collection\Support\PhysicalItemStateGuard::class; @endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-semibold mb-0">Riwayat Status: <code>{{ $item->barcode }}</code></h5>
        <small class="text-muted">{{ $item->bibliographicRecord->title ?? '-' }}</small>
    </div>
    <a href="{{ route('admin.collections.items.show', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
{{-- Filter --}}
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label small">Dari Tanggal</label><input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}"></div>
        <div class="col-md-3"><label class="form-label small">Sampai Tanggal</label><input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}"></div>
        <div class="col-md-3 d-flex gap-1 align-items-end">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.collections.items.history', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Reset</a>
        </div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th style="width:50px">#</th><th>Waktu</th><th>Status Lama</th><th>Status Baru</th><th>Diubah Oleh</th><th>Catatan</th></tr></thead>
        <tbody>
            @forelse($histories as $h)
            <tr>
                <td>{{ $histories->firstItem() + $loop->index }}</td>
                <td>{{ $h->created_at->format('d M Y H:i:s') }}</td>
                <td>@if($h->old_status)<span class="badge bg-{{ $guard::statusBadgeClass($h->old_status) }}">{{ $guard::statusLabel($h->old_status) }}</span>@else <span class="text-muted">—</span> @endif</td>
                <td><span class="badge bg-{{ $guard::statusBadgeClass($h->new_status) }}">{{ $guard::statusLabel($h->new_status) }}</span></td>
                <td>{{ $h->changedByUser->name ?? '-' }}</td>
                <td>{{ $h->reason ?: '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat perubahan status.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($histories->hasPages())<div class="p-3">{{ $histories->withQueryString()->links() }}</div>@endif</div>
@endsection
