@extends('layouts.admin')
@section('title', 'Aturan Operasional')
@section('page-title', 'Aturan Operasional')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Aturan Operasional</li>
@endsection
@section('content')
<div class="pq-card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('admin.settings.operational_rules.update') }}">
        @csrf @method('PUT')
        <h6 class="fw-semibold mb-3"><i class="bi bi-sliders me-2"></i>Aturan Sirkulasi</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Lama Peminjaman (hari) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_default_days') is-invalid @enderror" name="loan_default_days" value="{{ old('loan_default_days', $settings['loan_default_days'] ?? 14) }}" min="1" max="365" required>
                @error('loan_default_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Maks. Pinjaman Aktif <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_max_active_loans') is-invalid @enderror" name="loan_max_active_loans" value="{{ old('loan_max_active_loans', $settings['loan_max_active_loans'] ?? 5) }}" min="1" max="50" required>
                @error('loan_max_active_loans')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Maks. Perpanjangan <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_max_renewal_count') is-invalid @enderror" name="loan_max_renewal_count" value="{{ old('loan_max_renewal_count', $settings['loan_max_renewal_count'] ?? 2) }}" min="0" max="10" required>
                @error('loan_max_renewal_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Denda Per Hari (Rp) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('fine_daily_amount') is-invalid @enderror" name="fine_daily_amount" value="{{ old('fine_daily_amount', $settings['fine_daily_amount'] ?? 1000) }}" min="0" step="100" required>
                @error('fine_daily_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
