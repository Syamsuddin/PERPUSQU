@extends('layouts.admin')
@section('title', 'Layanan Anggota')
@section('page-title', 'Layanan Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item active">Layanan Anggota</li>
@endsection
@section('content')
<div class="pq-card p-4" style="max-width:680px;">
    <div class="text-center py-4">
        <i class="bi bi-person-x" style="font-size:3rem; color:#94a3b8;"></i>
        <h5 class="mt-3 mb-2">Akun Anda belum tertaut ke data keanggotaan</h5>
        <p class="text-muted mb-0">
            Halaman ini menampilkan pinjaman dan denda milik Anda, tetapi akun ini
            belum dihubungkan dengan kartu anggota mana pun. Silakan hubungi petugas
            perpustakaan untuk menautkannya.
        </p>
    </div>
</div>
@endsection
