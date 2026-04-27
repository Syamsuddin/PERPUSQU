@extends('layouts.admin')
@section('title', 'Peminjaman Baru')
@section('page-title', 'Peminjaman Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.circulation.loans.active') }}">Sirkulasi</a></li>
    <li class="breadcrumb-item active">Peminjaman Baru</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <h5 class="fw-semibold mb-3"><i class="bi bi-book me-1"></i>Form Peminjaman</h5>
    <form method="POST" action="{{ route('admin.circulation.loans.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Anggota <span class="text-danger">*</span></label>
                <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" required>
                    <option value="">— Pilih Anggota —</option>
                    @foreach($members as $m)
                    <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>{{ $m->name }} ({{ $m->member_number }})</option>
                    @endforeach
                </select>
                @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Barcode Item <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" value="{{ old('barcode') }}" placeholder="Scan atau ketik barcode..." required autofocus>
                @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-medium">Catatan</label>
                <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Proses Pinjam</button>
            <a href="{{ route('admin.circulation.loans.active') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
