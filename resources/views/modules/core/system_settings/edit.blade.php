@extends('layouts.admin')
@section('title', 'Aturan Operasional')
@section('page-title', 'Aturan Operasional')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Aturan Operasional</li>
@endsection
@section('content')
<div class="pq-card p-4" style="max-width:820px;">
    <form method="POST" action="{{ route('admin.settings.operational_rules.update') }}">
        @csrf @method('PUT')

        <h6 class="fw-semibold mb-1"><i class="bi bi-calendar-range me-2"></i>Lama Peminjaman per Jenis Anggota</h6>
        <p class="text-muted small mb-3">Jumlah hari sejak tanggal pinjam sampai jatuh tempo.</p>
        <div class="row g-3">
            @foreach ([
                'loan_days_student'  => 'Mahasiswa',
                'loan_days_lecturer' => 'Dosen',
                'loan_days_staff'    => 'Staf',
                'loan_days_alumni'   => 'Alumni',
                'loan_days_guest'    => 'Tamu',
            ] as $key => $label)
                <div class="col-md-4">
                    <label class="form-label">{{ $label }} (hari) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error($key) is-invalid @enderror" name="{{ $key }}"
                           value="{{ old($key, $settings[$key] ?? '') }}" min="1" max="365" required>
                    @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
            <div class="col-md-4">
                <label class="form-label">Jenis lainnya (hari) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_default_days') is-invalid @enderror" name="loan_default_days"
                       value="{{ old('loan_default_days', $settings['loan_default_days'] ?? 14) }}" min="1" max="365" required>
                <div class="form-text">Dipakai untuk jenis anggota di luar kelima di atas.</div>
                @error('loan_default_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-4">

        <h6 class="fw-semibold mb-3"><i class="bi bi-sliders me-2"></i>Batas dan Denda</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Maks. Pinjaman Aktif <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_max_active_loans') is-invalid @enderror" name="loan_max_active_loans"
                       value="{{ old('loan_max_active_loans', $settings['loan_max_active_loans'] ?? 5) }}" min="1" max="50" required>
                @error('loan_max_active_loans')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Maks. Perpanjangan <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_max_renewal_count') is-invalid @enderror" name="loan_max_renewal_count"
                       value="{{ old('loan_max_renewal_count', $settings['loan_max_renewal_count'] ?? 2) }}" min="0" max="10" required>
                @error('loan_max_renewal_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Lama Perpanjangan (hari) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('loan_renewal_days') is-invalid @enderror" name="loan_renewal_days"
                       value="{{ old('loan_renewal_days', $settings['loan_renewal_days'] ?? 7) }}" min="1" max="365" required>
                <div class="form-text">Tambahan hari dari jatuh tempo yang berlaku saat ini.</div>
                @error('loan_renewal_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Denda Per Hari (Rp) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('fine_daily_amount') is-invalid @enderror" name="fine_daily_amount"
                       value="{{ old('fine_daily_amount', $settings['fine_daily_amount'] ?? 1000) }}" min="0" step="100" required>
                <div class="form-text">Isi 0 untuk menonaktifkan denda keterlambatan.</div>
                @error('fine_daily_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-4">

        <h6 class="fw-semibold mb-3"><i class="bi bi-toggles me-2"></i>Kebijakan Layanan</h6>
        @foreach ([
            'allow_renewal'            => ['Izinkan perpanjangan pinjaman', 'Bila dimatikan, seluruh permintaan perpanjangan ditolak.'],
            'require_active_member'    => ['Wajib berstatus anggota aktif', 'Anggota nonaktif tidak dapat meminjam.'],
            'require_unblocked_member' => ['Wajib tidak sedang diblokir', 'Anggota yang diblokir tidak dapat meminjam.'],
        ] as $key => [$label, $help])
            @include('modules.core.system_settings.partials.switch', ['key' => $key, 'label' => $label, 'help' => $help])
        @endforeach

        <hr class="my-4">

        <h6 class="fw-semibold mb-3"><i class="bi bi-file-earmark-pdf me-2"></i>Repositori Digital</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Batas Ukuran Unggahan (MB) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('asset_max_upload_size_mb') is-invalid @enderror" name="asset_max_upload_size_mb"
                       value="{{ old('asset_max_upload_size_mb', $settings['asset_max_upload_size_mb'] ?? 50) }}" min="1" max="2048" required>
                <div class="form-text">Berlaku untuk unggahan aset digital baru maupun penggantian berkas.</div>
                @error('asset_max_upload_size_mb')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        @foreach ([
            'ocr_enabled'            => ['Aktifkan OCR', 'Butuh mesin pengenal teks di server. Bila mati, permintaan OCR ditolak alih-alih mengantre tanpa diproses.'],
            'public_preview_enabled' => ['Izinkan pratinjau publik di OPAC', 'Bila dimatikan, seluruh berkas tertutup untuk pengunjung tanpa perlu mengubah status tiap aset.'],
        ] as $key => [$label, $help])
            @include('modules.core.system_settings.partials.switch', ['key' => $key, 'label' => $label, 'help' => $help])
        @endforeach

        <hr class="my-4">

        <h6 class="fw-semibold mb-3"><i class="bi bi-gear me-2"></i>Umum</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Aplikasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('app_name') is-invalid @enderror" name="app_name"
                       value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" minlength="2" maxlength="100" required>
                <div class="form-text">Tampil di judul tab, sidebar admin, dan header OPAC.</div>
                @error('app_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Versi Aplikasi</label>
                <input type="text" class="form-control" value="{{ $settings['app_version'] ?? '1.0.0' }}" disabled readonly>
                <div class="form-text">Menggambarkan kode yang terpasang, diperbarui saat rilis — bukan kebijakan yang diatur di sini.</div>
            </div>
        </div>
        @include('modules.core.system_settings.partials.switch', [
            'key' => 'maintenance_mode',
            'label' => 'Tutup katalog publik untuk pemeliharaan',
            'help' => 'Pengunjung melihat halaman pemeliharaan. Area admin tetap terbuka, dan pengguna yang sudah masuk tetap dapat membuka OPAC untuk memeriksa.',
            'default' => false,
        ])

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
