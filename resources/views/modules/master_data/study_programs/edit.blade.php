@extends('layouts.admin')
@section('title', 'Edit Program Studi')
@section('page-title', 'Edit Program Studi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.master-data.study-programs.index') }}">Program Studi</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.master-data.study-programs.update', $studyProgram) }}">@csrf @method('PUT')
        @include('modules.master_data.study_programs._form', ['item' => $studyProgram])
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.master-data.study-programs.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
</div></div>
@endsection