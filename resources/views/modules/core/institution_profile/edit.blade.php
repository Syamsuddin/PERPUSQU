@extends('layouts.admin')
@section('title', 'Profil Institusi')
@section('page-title', 'Profil Institusi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profil Institusi</li>
@endsection
@section('content')
<div class="pq-card p-4" style="max-width:800px;">
    <form method="POST" action="{{ route('admin.settings.institution_profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Institusi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('institution_name') is-invalid @enderror" name="institution_name" value="{{ old('institution_name', $profile->institution_name ?? '') }}" required>
                @error('institution_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Perpustakaan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('library_name') is-invalid @enderror" name="library_name" value="{{ old('library_name', $profile->library_name ?? '') }}" required>
                @error('library_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $profile->address ?? '') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Telepon</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $profile->phone ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $profile->email ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Website</label>
                <input type="url" class="form-control" name="website" value="{{ old('website', $profile->website ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Tentang Perpustakaan</label>
                <textarea class="form-control" name="about_text" rows="4">{{ old('about_text', $profile->about_text ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" class="form-control" name="logo" accept=".jpg,.jpeg,.png,.webp">
                @if($profile && $profile->logo_path)
                <small class="text-muted mt-1 d-block">Logo saat ini: {{ basename($profile->logo_path) }}</small>
                @endif
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
