@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Audit Log</li>
@endsection

@section('content')
<div class="pq-card p-4 mb-4">
    <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label small fw-semibold mb-1">Kata Kunci</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Cari deskripsi...">
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label small fw-semibold mb-1">Modul</label>
            <select name="log_name" class="form-select form-select-sm">
                <option value="">Semua Modul</option>
                @foreach($logNames as $name)
                <option value="{{ $name }}" {{ request('log_name') === $name ? 'selected' : '' }}>
                    {{ ucfirst($name) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<div class="pq-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-semibold small">{{ number_format($logs->total()) }} catatan ditemukan</span>
        <span class="text-muted small">Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover small mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 150px;">Waktu</th>
                    <th style="width: 100px;">Modul</th>
                    <th>Deskripsi</th>
                    <th style="width: 140px;">Dilakukan Oleh</th>
                    <th style="width: 100px;">Subjek</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge bg-secondary rounded-pill">{{ $log->log_name }}</span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td>
                        @if($log->causer)
                            <span class="fw-semibold">{{ $log->causer->name }}</span>
                        @else
                            <span class="text-muted fst-italic">Sistem</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        @if($log->subject_type)
                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data audit log.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="p-3 border-top d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
