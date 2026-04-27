@extends('layouts.admin')
@section('title', 'Detail Pinjaman #' . $loan->id)
@section('page-title', 'Detail Pinjaman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.circulation.loans.active') }}">Pinjaman Aktif</a></li>
    <li class="breadcrumb-item active">#{{ $loan->id }}</li>
@endsection
@section('content')
@php $isOverdue = $loan->loan_status === 'active' && $loan->due_date->isPast(); @endphp
<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1">Pinjaman #{{ $loan->id }}</h4>
                    <span class="text-muted">{{ $loan->loan_date->format('d M Y H:i') }}</span>
                </div>
                @if($loan->loan_status === 'active')
                    @if($isOverdue)<span class="badge bg-danger fs-6"><i class="bi bi-exclamation-triangle me-1"></i>Overdue</span>
                    @else <span class="badge bg-success fs-6">Aktif</span>@endif
                @elseif($loan->loan_status === 'returned')
                    <span class="badge bg-secondary fs-6">Dikembalikan</span>
                @endif
            </div>
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Anggota</th><td><a href="{{ route('admin.members.show', $loan->member_id) }}">{{ $loan->member->name ?? '-' }}</a> ({{ $loan->member->member_number ?? '' }})</td></tr>
                <tr><th>Item</th><td><code>{{ $loan->physicalItem->barcode ?? '-' }}</code> — {{ $loan->physicalItem->bibliographicRecord->title ?? '-' }}</td></tr>
                <tr><th>Tanggal Pinjam</th><td>{{ $loan->loan_date->format('d M Y H:i') }}</td></tr>
                <tr><th>Jatuh Tempo</th><td>{{ $loan->due_date->format('d M Y') }} @if($isOverdue)<span class="text-danger">({{ now()->diffInDays($loan->due_date) }} hari terlambat)</span>@endif</td></tr>
                @if($loan->returned_at)<tr><th>Dikembalikan</th><td>{{ $loan->returned_at->format('d M Y H:i') }}</td></tr>@endif
                <tr><th>Petugas Pinjam</th><td>{{ $loan->loanedBy->name ?? '-' }}</td></tr>
                @if($loan->closedBy)<tr><th>Petugas Kembali</th><td>{{ $loan->closedBy->name ?? '-' }}</td></tr>@endif
                <tr><th>Catatan</th><td>{{ $loan->notes ?: '-' }}</td></tr>
            </table>
        </div></div>

        {{-- Renewal --}}
        @if($loan->loan_status === 'active')
        @can('circulation.renew')
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-arrow-repeat me-1"></i>Perpanjangan ({{ $loan->renewals->count() }}/{{ \App\Modules\Circulation\Support\DueDateCalculator::maxRenewals() }})</h6>
            @if($loan->renewals->count() < \App\Modules\Circulation\Support\DueDateCalculator::maxRenewals() && !$isOverdue)
            <form method="POST" action="{{ route('admin.circulation.loans.renew', $loan) }}" onsubmit="return confirm('Perpanjang pinjaman ini?')">
                @csrf
                <div class="row g-2">
                    <div class="col-md-8"><input type="text" class="form-control form-control-sm" name="notes" placeholder="Catatan perpanjangan (opsional)"></div>
                    <div class="col-md-4"><button type="submit" class="btn btn-sm btn-warning w-100"><i class="bi bi-arrow-repeat me-1"></i>Perpanjang</button></div>
                </div>
            </form>
            @else
            <p class="text-muted mb-0">@if($isOverdue) Pinjaman overdue tidak dapat diperpanjang. @else Batas perpanjangan tercapai. @endif</p>
            @endif
        </div></div>
        @endcan
        @endif

        {{-- Renewal History --}}
        @if($loan->renewals->count() > 0)
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-clock-history me-1"></i>Riwayat Perpanjangan</h6>
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light"><tr><th>#</th><th>Dari</th><th>Ke</th><th>Oleh</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach($loan->renewals as $i => $r)
                <tr><td>{{ $i+1 }}</td><td>{{ $r->old_due_date->format('d/m/Y') }}</td><td>{{ $r->new_due_date->format('d/m/Y') }}</td><td>{{ $r->renewedBy->name ?? '-' }}</td><td>{{ $r->notes ?: '-' }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div></div>
        @endif

        {{-- Return & Fine Info --}}
        @if($loan->returnTransaction)
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-box-arrow-in-left me-1"></i>Informasi Pengembalian</h6>
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Dikembalikan</th><td>{{ $loan->returnTransaction->returned_at->format('d M Y H:i') }}</td></tr>
                <tr><th>Hari Terlambat</th><td>{{ $loan->returnTransaction->late_days }} hari</td></tr>
                <tr><th>Denda</th><td>Rp {{ number_format($loan->returnTransaction->fine_amount, 0, ',', '.') }}</td></tr>
                <tr><th>Catatan</th><td>{{ $loan->returnTransaction->notes ?: '-' }}</td></tr>
            </table>
        </div></div>
        @endif

        @if($loan->fine)
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-cash-stack me-1"></i>Denda</h6>
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Jumlah</th><td>Rp {{ number_format($loan->fine->amount, 0, ',', '.') }}</td></tr>
                <tr><th>Status</th><td>
                    <span class="badge bg-{{ match($loan->fine->status) { 'outstanding' => 'warning', 'settled' => 'success', 'waived' => 'info', default => 'secondary' } }}">{{ ucfirst($loan->fine->status) }}</span>
                </td></tr>
            </table>
        </div></div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3">Informasi Sistem</h6>
            <table class="table table-sm mb-0">
                <tr><th>ID</th><td>{{ $loan->id }}</td></tr>
                <tr><th>Status</th><td>{{ ucfirst($loan->loan_status) }}</td></tr>
                <tr><th>Dibuat</th><td>{{ $loan->created_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div></div>
    </div>
</div>
@endsection
