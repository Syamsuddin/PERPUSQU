@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-logo">
        <h1><i class="bi bi-book-half"></i> GIBTHA LIBRARY</h1>
        <p>Sistem Informasi Perpustakaan Hibrid Kampus</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-sm py-2 px-3" style="font-size:0.85rem">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('auth.login.attempt') }}">
        @csrf

        <div class="mb-3">
            <label for="login" class="form-label">Username / Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control @error('login') is-invalid @enderror"
                       id="login" name="login" value="{{ old('login') }}"
                       placeholder="Masukkan username atau email" autofocus>
            </div>
            @error('login')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="Masukkan password">
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-login w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
    </form>

    <div class="mt-4 pt-3 border-top text-center" style="font-size: 0.75rem; color: #718096;">
        &copy; {{ date('Y') }} GIBTHA LIBRARY — Hak Cipta <strong>Syamsuddin</strong>
        <a href="https://wa.me/6281349694696" target="_blank" rel="noopener" title="Hubungi via WhatsApp" style="color: #25D366; margin-left: 0.5rem; font-size: 1.1rem; vertical-align: middle;">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>
@endsection
