@extends('layouts.admin')
@section('title', 'Upload Aset Digital')
@section('page-title', 'Upload Aset Digital')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.digital-assets.index') }}">Repositori Digital</a></li>
    <li class="breadcrumb-item active">Upload</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <h5 class="fw-semibold mb-3"><i class="bi bi-cloud-upload me-1"></i>Form Upload Aset Digital</h5>
    <form method="POST" action="{{ route('admin.digital-assets.store') }}" enctype="multipart/form-data">
        @csrf
        @include('modules.digital-repository._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i>Upload</button>
            <a href="{{ route('admin.digital-assets.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
