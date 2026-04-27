@extends('layouts.admin')
@section('title', 'Tambah Fakultas')
@section('page-title', 'Tambah Fakultas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.master-data.faculties.index') }}">Fakultas</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.master-data.faculties.store') }}">@csrf
        @include('modules.master_data.faculties._form')
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.master-data.faculties.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
</div></div>
@endsection