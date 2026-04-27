@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profil</li>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="pq-card p-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-person-circle me-2"></i>Informasi Akun</h6>
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <div>@foreach($user->roles as $role)<span class="badge bg-primary me-1">{{ $role->name }}</span>@endforeach</div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="pq-card p-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-lock me-2"></i>Ubah Password</h6>
            <a href="{{ route('admin.profile.password.edit') }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-key me-1"></i>Ubah Password</a>
        </div>
    </div>
</div>
@endsection
