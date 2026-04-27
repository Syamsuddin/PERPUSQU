@extends('layouts.admin')
@section('title', 'Tambah Klasifikasi')
@section('page-title', 'Tambah Klasifikasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.master-data.classifications.index') }}">Klasifikasi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.master-data.classifications.store') }}">@csrf
        @include('modules.master_data.classifications._form')
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.master-data.classifications.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
</div></div>
@endsection