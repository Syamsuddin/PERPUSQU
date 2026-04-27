@extends('layouts.admin')
@section('title', 'Edit Aset Digital')
@section('page-title', 'Edit Aset Digital')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.digital-assets.index') }}">Repositori Digital</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <h5 class="fw-semibold mb-3"><i class="bi bi-pencil me-1"></i>Edit Aset Digital</h5>
    <div class="alert alert-info py-2 mb-3">
        <small><i class="bi bi-file-earmark-pdf me-1"></i>File saat ini: <strong>{{ $asset->original_file_name }}</strong> ({{ $asset->file_size_formatted }})</small>
    </div>
    <form method="POST" action="{{ route('admin.digital-assets.update', $asset) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('modules.digital-repository._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            <a href="{{ route('admin.digital-assets.show', $asset) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
