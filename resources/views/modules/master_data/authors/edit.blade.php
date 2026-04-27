@extends('layouts.admin')
@section('title', 'Edit Pengarang')
@section('page-title', 'Edit Pengarang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.master-data.authors.index') }}">Pengarang</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.master-data.authors.update', $author) }}">
        @csrf @method('PUT')
        @include('modules.master_data.authors._form', ['item' => $author])
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            <a href="{{ route('admin.master-data.authors.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
