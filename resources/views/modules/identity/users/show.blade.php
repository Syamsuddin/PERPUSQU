@extends('layouts.admin')
@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.access.users.index') }}">Pengguna</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="pq-card p-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-person-circle me-2"></i>Informasi Pengguna</h6>
            <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                <tr><td class="text-muted" style="width:140px;">Nama</td><td class="fw-medium">{{ $user->name }}</td></tr>
                <tr><td class="text-muted">Username</td><td><code>{{ $user->username }}</code></td></tr>
                <tr><td class="text-muted">Email</td><td>{{ $user->email }}</td></tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td><span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                </tr>
                <tr><td class="text-muted">Login Terakhir</td><td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}</td></tr>
                <tr><td class="text-muted">Terdaftar</td><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="pq-card p-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-shield-lock me-2"></i>Role & Permission</h6>
            <div class="mb-3">
                <strong class="d-block mb-2" style="font-size:0.85rem;">Role:</strong>
                @foreach($user->roles as $role)
                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                @endforeach
            </div>
            <strong class="d-block mb-2" style="font-size:0.85rem;">Permission ({{ $user->getAllPermissions()->count() }}):</strong>
            <div style="max-height:200px;overflow-y:auto;font-size:0.8rem;">
                @foreach($user->getAllPermissions()->sortBy('name') as $perm)
                <span class="badge bg-light text-dark border me-1 mb-1">{{ $perm->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="mt-3 d-flex gap-2">
    @can('users.update')
    <a href="{{ route('admin.access.users.edit', $user) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
    @endcan
    <a href="{{ route('admin.access.users.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
</div>
@endsection
