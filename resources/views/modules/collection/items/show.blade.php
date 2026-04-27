@extends('layouts.admin')
@section('title', 'Detail Item — ' . $item->barcode)
@section('page-title', 'Detail Item Fisik')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.collections.items.index') }}">Item Fisik</a></li>
    <li class="breadcrumb-item active">{{ $item->barcode }}</li>
@endsection
@section('content')
@php
    $guard = \App\Modules\Collection\Support\PhysicalItemStateGuard::class;
@endphp
<div class="row g-3">
    <div class="col-md-8">
        {{-- Main Info --}}
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1">{{ $item->barcode }}</h4>
                    <div class="text-muted small">{{ $item->inventory_code ? 'Inventaris: '.$item->inventory_code : '' }}</div>
                </div>
                <span class="badge bg-{{ $guard::statusBadgeClass($item->item_status) }} fs-6">{{ $guard::statusLabel($item->item_status) }}</span>
            </div>
            <div class="d-flex gap-2 mb-3">
                @can('collections.update')
                <a href="{{ route('admin.collections.items.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
                @endcan
                <a href="{{ route('admin.collections.items.history', $item) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-clock-history me-1"></i>Riwayat Status</a>
            </div>
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Katalog</th><td><a href="{{ route('admin.catalog.records.show', $item->bibliographic_record_id) }}">{{ $item->bibliographicRecord->title ?? '-' }}</a></td></tr>
                <tr><th>Pengarang</th><td>@forelse($item->bibliographicRecord->authors ?? [] as $a)<span class="badge bg-primary me-1">{{ $a->name }}</span>@empty - @endforelse</td></tr>
                <tr><th>Lokasi Rak</th><td>{{ $item->rackLocation->name ?? '-' }}</td></tr>
                <tr><th>Kondisi</th><td>{{ $item->itemCondition->name ?? '-' }}</td></tr>
                <tr><th>Tanggal Perolehan</th><td>{{ $item->acquisition_date ? $item->acquisition_date->format('d M Y') : '-' }}</td></tr>
                <tr><th>Catatan</th><td>{{ $item->notes ?: '-' }}</td></tr>
            </table>
        </div></div>

        {{-- Status Change --}}
        @can('collections.update')
        @if(count($allowedTransitions) > 0)
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-arrow-left-right me-1"></i>Ubah Status</h6>
            <form method="POST" action="{{ route('admin.collections.items.change_status', $item) }}" onsubmit="return confirm('Ubah status item ini?')">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="new_status" required>
                            <option value="">— Pilih Status Baru —</option>
                            @foreach($allowedTransitions as $status)
                            <option value="{{ $status }}">{{ $guard::statusLabel($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" name="reason" placeholder="Alasan perubahan (opsional)">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-warning w-100"><i class="bi bi-check-circle me-1"></i>Ubah Status</button>
                    </div>
                </div>
            </form>
        </div></div>
        @endif
        @endcan

        {{-- Recent History --}}
        <div class="card shadow-sm border-0"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0"><i class="bi bi-clock-history me-1"></i>Riwayat Status Terakhir</h6>
                <a href="{{ route('admin.collections.items.history', $item) }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            @if($item->statusHistories->count() > 0)
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light"><tr><th>Waktu</th><th>Dari</th><th>Ke</th><th>Oleh</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach($item->statusHistories->take(10) as $h)
                <tr>
                    <td><small>{{ $h->created_at->format('d/m/Y H:i') }}</small></td>
                    <td>@if($h->old_status)<span class="badge bg-{{ $guard::statusBadgeClass($h->old_status) }}">{{ $guard::statusLabel($h->old_status) }}</span>@else <span class="text-muted">-</span> @endif</td>
                    <td><span class="badge bg-{{ $guard::statusBadgeClass($h->new_status) }}">{{ $guard::statusLabel($h->new_status) }}</span></td>
                    <td><small>{{ $h->changedByUser->name ?? '-' }}</small></td>
                    <td><small>{{ $h->reason ?: '-' }}</small></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Belum ada riwayat.</p>
            @endif
        </div></div>
    </div>

    {{-- Right sidebar --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3">Informasi Sistem</h6>
            <table class="table table-sm mb-0">
                <tr><th>ID</th><td>{{ $item->id }}</td></tr>
                <tr><th>Dibuat</th><td>{{ $item->created_at->format('d M Y H:i') }}</td></tr>
                <tr><th>Diperbarui</th><td>{{ $item->updated_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div></div>
    </div>
</div>
@endsection
