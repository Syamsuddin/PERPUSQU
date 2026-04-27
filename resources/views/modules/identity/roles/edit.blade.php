@extends('layouts.admin')
@section('title', 'Edit Role')
@section('page-title', 'Edit Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.access.roles.index') }}">Role</a></li>
    <li class="breadcrumb-item active">Edit: {{ $role->name }}</li>
@endsection
@section('content')
<div class="pq-card p-4">
    <form method="POST" action="{{ route('admin.access.roles.update', $role) }}">
        @csrf @method('PUT')
        <div class="mb-3" style="max-width:400px;">
            <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <hr>
        <h6 class="fw-semibold mb-3">Permission</h6>
        @php $rolePermIds = $role->permissions->pluck('id')->toArray(); @endphp
        @foreach($permissionGroups as $module => $permissions)
        <div class="mb-3">
            <strong class="text-uppercase" style="font-size:0.8rem;color:#4a5568;">{{ $module }}</strong>
            <div class="row mt-1">
                @foreach($permissions as $perm)
                <div class="col-md-4 col-lg-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="permission_ids[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                            {{ in_array($perm->id, old('permission_ids', $rolePermIds)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $perm->id }}" style="font-size:0.8rem;">{{ $perm->name }}</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Perbarui</button>
            <a href="{{ route('admin.access.roles.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
