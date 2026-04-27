@extends('layouts.admin')
@section('title', 'Riwayat — ' . $member->name)
@section('page-title', 'Riwayat Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Anggota</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->name }}</a></li>
    <li class="breadcrumb-item active">Riwayat</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-semibold mb-0">Riwayat Aktivitas: {{ $member->name }}</h5>
        <small class="text-muted">{{ $member->member_number }}</small>
    </div>
    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label small">Dari</label><input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}"></div>
        <div class="col-md-3"><label class="form-label small">Sampai</label><input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}"></div>
        <div class="col-md-3 d-flex gap-1 align-items-end">
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.members.history', $member) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
        </div>
    </form>
</div></div>
<div class="card shadow-sm border-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th style="width:50px">#</th><th>Waktu</th><th>Aktivitas</th><th>Oleh</th></tr></thead>
        <tbody>
            @forelse($activities as $a)
            <tr>
                <td>{{ $activities->firstItem() + $loop->index }}</td>
                <td>{{ $a->created_at->format('d M Y H:i:s') }}</td>
                <td>{{ $a->description }}</td>
                <td>{{ $a->causer?->name ?? 'System' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat aktivitas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($activities->hasPages())<div class="p-3">{{ $activities->withQueryString()->links() }}</div>@endif</div>
@endsection
