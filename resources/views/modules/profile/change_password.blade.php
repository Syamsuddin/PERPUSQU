@extends('layouts.admin')
@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.profile.show') }}">Profil</a></li>
    <li class="breadcrumb-item active">Ubah Password</li>
@endsection
@section('content')
<div class="pq-card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('admin.profile.password.update') }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Password Lama <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="new_password_confirmation" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Ubah Password</button>
            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
