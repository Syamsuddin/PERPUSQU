@extends('layouts.admin')
@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.access.roles.index') }}">Role</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="pq-card p-4">
    <form method="POST" action="{{ route('admin.access.roles.store') }}">
        @csrf
        <div class="mb-3" style="max-width:400px;">
            <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <hr>
        <h6 class="fw-semibold mb-3">Pilih Permission</h6>
        @foreach($permissionGroups as $module => $permissions)
        <div class="mb-3">
            <strong class="text-uppercase" style="font-size:0.8rem;color:#4a5568;">{{ $module }}</strong>
            <div class="row mt-1">
                @foreach($permissions as $perm)
                <div class="col-md-4 col-lg-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="permission_ids[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                            {{ in_array($perm->id, old('permission_ids', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $perm->id }}" style="font-size:0.8rem;">{{ $perm->name }}</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Simpan</button>
            <a href="{{ route('admin.access.roles.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
