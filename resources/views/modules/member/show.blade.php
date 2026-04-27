@extends('layouts.admin')
@section('title', $member->name)
@section('page-title', 'Detail Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Anggota</a></li>
    <li class="breadcrumb-item active">{{ $member->name }}</li>
@endsection
@section('content')
@php $resolver = \App\Modules\Member\Support\MemberEligibilityResolver::class; @endphp
<div class="row g-3">
    <div class="col-md-8">
        {{-- Main Info --}}
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1">{{ $member->name }}</h4>
                    <div class="text-muted small">{{ $resolver::typeLabel($member->member_type) }} · {{ $member->member_number }}</div>
                </div>
                <span class="badge bg-{{ $resolver::stateBadgeClass($derivedState) }} fs-6">{{ $resolver::stateLabel($derivedState) }}</span>
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @can('members.update')
                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
                @endcan
                <a href="{{ route('admin.members.history', $member) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-clock-history me-1"></i>Riwayat</a>

                @can('members.update')
                    @if(!$member->is_active)
                    <form method="POST" action="{{ route('admin.members.activate', $member) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Aktifkan anggota ini?')"><i class="bi bi-check-circle me-1"></i>Aktifkan</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.members.deactivate', $member) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Nonaktifkan anggota ini?')"><i class="bi bi-x-circle me-1"></i>Nonaktifkan</button>
                    </form>
                    @endif
                @endcan

                @can('members.block')
                    @if(!$member->is_blocked)
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#blockModal"><i class="bi bi-shield-exclamation me-1"></i>Blokir</button>
                    @endif
                @endcan
                @can('members.unblock')
                    @if($member->is_blocked)
                    <form method="POST" action="{{ route('admin.members.unblock', $member) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Unblock anggota ini?')"><i class="bi bi-shield-check me-1"></i>Unblock</button>
                    </form>
                    @endif
                @endcan
            </div>

            {{-- Blocked Warning --}}
            @if($member->is_blocked)
            <div class="alert alert-danger py-2 mb-3">
                <i class="bi bi-shield-exclamation me-1"></i><strong>Diblokir</strong>: {{ $member->blocked_reason }}
                @if($member->blocked_at)<br><small class="text-muted">Diblokir pada: {{ \Carbon\Carbon::parse($member->blocked_at)->format('d M Y H:i') }}</small>@endif
            </div>
            @endif

            {{-- Detail Table --}}
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">No. Anggota</th><td><code>{{ $member->member_number }}</code></td></tr>
                <tr><th>No. Identitas</th><td>{{ $member->identity_number ?: '-' }}</td></tr>
                <tr><th>Tipe</th><td>{{ $resolver::typeLabel($member->member_type) }}</td></tr>
                <tr><th>Email</th><td>{{ $member->email ?: '-' }}</td></tr>
                <tr><th>Telepon</th><td>{{ $member->phone ?: '-' }}</td></tr>
                <tr><th>Fakultas</th><td>{{ $member->faculty->name ?? '-' }}</td></tr>
                <tr><th>Program Studi</th><td>{{ $member->studyProgram->name ?? '-' }}</td></tr>
                <tr><th>Catatan</th><td>{{ $member->notes ?: '-' }}</td></tr>
                <tr><th>Eligibility Pinjam</th><td>{!! $resolver::canBorrow($member) ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Layak Meminjam</span>' : '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Tidak Layak</span>' !!}</td></tr>
            </table>
        </div></div>

        {{-- Stats --}}
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-bar-chart me-1"></i>Statistik</h6>
            <div class="row text-center">
                <div class="col-4"><div class="border rounded p-3"><div class="fs-4 fw-bold text-primary">{{ $member->loans_count }}</div><small class="text-muted">Total Pinjaman</small></div></div>
                <div class="col-4"><div class="border rounded p-3"><div class="fs-4 fw-bold text-warning">{{ $member->fines_count }}</div><small class="text-muted">Total Denda</small></div></div>
                <div class="col-4"><div class="border rounded p-3"><div class="fs-4 fw-bold text-info">{{ $member->created_at->format('Y') }}</div><small class="text-muted">Tahun Daftar</small></div></div>
            </div>
        </div></div>
    </div>

    {{-- Right --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3">Informasi Sistem</h6>
            <table class="table table-sm mb-0">
                <tr><th>ID</th><td>{{ $member->id }}</td></tr>
                <tr><th>Terdaftar</th><td>{{ $member->created_at->format('d M Y H:i') }}</td></tr>
                <tr><th>Diperbarui</th><td>{{ $member->updated_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div></div>
    </div>
</div>

{{-- Block Modal --}}
@can('members.block')
<div class="modal fade" id="blockModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="{{ route('admin.members.block', $member) }}">@csrf
        <div class="modal-header"><h5 class="modal-title">Blokir Anggota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p>Blokir anggota <strong>{{ $member->name }}</strong>? Anggota yang diblokir tidak dapat meminjam buku.</p>
            <div class="mb-3">
                <label class="form-label fw-medium">Alasan Pemblokiran <span class="text-danger">*</span></label>
                <textarea class="form-control @error('blocked_reason') is-invalid @enderror" name="blocked_reason" rows="3" required minlength="3">{{ old('blocked_reason') }}</textarea>
                @error('blocked_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-shield-exclamation me-1"></i>Blokir</button>
        </div>
    </form>
</div></div></div>
@endcan
@endsection
