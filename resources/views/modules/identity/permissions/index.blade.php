@extends('layouts.admin')
@section('title', 'Permission Matrix')
@section('page-title', 'Permission Matrix')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Permission</li>
@endsection
@section('content')
<div class="pq-card p-4">
    <h6 class="fw-semibold mb-3"><i class="bi bi-key me-2"></i>Daftar Permission ({{ collect($permissionGroups)->flatten()->count() }})</h6>
    @foreach($permissionGroups as $module => $permissions)
    <div class="mb-4">
        <h6 class="text-uppercase fw-semibold" style="font-size:0.8rem;color:#4a5568;letter-spacing:1px;">
            <i class="bi bi-folder me-1"></i>{{ $module }}
            <span class="badge bg-secondary ms-1">{{ count($permissions) }}</span>
        </h6>
        <div class="row">
            @foreach($permissions as $perm)
            <div class="col-md-4 col-lg-3 mb-1">
                <span class="badge bg-light text-dark border" style="font-size:0.78rem;">{{ $perm->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
