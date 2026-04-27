@extends('layouts.auth')
@section('title', '403 - Akses Ditolak')
@section('content')
<div class="text-center">
    <div style="font-size:4rem;color:#e53e3e;"><i class="bi bi-shield-exclamation"></i></div>
    <h3 class="fw-bold mt-2" style="color:#1e3a5f;">403</h3>
    <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url('/') }}" class="btn btn-login px-4"><i class="bi bi-house me-1"></i>Kembali</a>
</div>
@endsection
