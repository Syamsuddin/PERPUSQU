@extends('layouts.admin')
@section('title', 'Pengembalian')
@section('page-title', 'Pengembalian Buku')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.circulation.loans.active') }}">Sirkulasi</a></li>
    <li class="breadcrumb-item active">Pengembalian</li>
@endsection
@section('content')
<div class="card shadow-sm border-0"><div class="card-body">
    <h5 class="fw-semibold mb-3"><i class="bi bi-box-arrow-in-left me-1"></i>Form Pengembalian</h5>
    <form method="POST" action="{{ route('admin.circulation.returns.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Barcode Item <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" value="{{ old('barcode') }}" placeholder="Scan barcode item..." required autofocus>
                @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Kondisi Kembali</label>
                <select class="form-select" name="returned_condition_id">
                    <option value="">— Pilih Kondisi —</option>
                    @foreach($itemConditions as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-medium">Catatan</label>
                <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Proses Kembali</button>
            <a href="{{ route('admin.circulation.loans.active') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
