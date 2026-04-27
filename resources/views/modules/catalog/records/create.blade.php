@extends('layouts.admin')
@section('title', 'Tambah Katalog')
@section('page-title', 'Tambah Katalog')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.catalog.records.store') }}" enctype="multipart/form-data">
        @csrf
        @include('modules.catalog.records._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan sebagai Draft</button>
            <a href="{{ route('admin.catalog.records.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
