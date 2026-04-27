@extends('layouts.auth')
@section('title', '404 - Halaman Tidak Ditemukan')
@section('content')
<div class="text-center">
    <div style="font-size:4rem;color:#ed8936;"><i class="bi bi-question-circle"></i></div>
    <h3 class="fw-bold mt-2" style="color:#1e3a5f;">404</h3>
    <p class="text-muted mb-4">Halaman yang Anda cari tidak ditemukan.</p>
    <a href="{{ url('/') }}" class="btn btn-login px-4"><i class="bi bi-house me-1"></i>Kembali</a>
</div>
@endsection
