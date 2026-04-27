@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="pq-stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($widgets['total_catalog']) }}</div>
                        <div class="stat-label">Total Katalog</div>
                    </div>
                    <i class="bi bi-journal-bookmark stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-stat-card" style="background: linear-gradient(135deg, #38b2ac, #0694a2);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($widgets['total_items']) }}</div>
                        <div class="stat-label">Total Item Fisik</div>
                    </div>
                    <i class="bi bi-box-seam stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-stat-card" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($widgets['total_members']) }}</div>
                        <div class="stat-label">Total Anggota</div>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-stat-card" style="background: linear-gradient(135deg, #e53e3e, #c53030);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($widgets['active_loans']) }}</div>
                        <div class="stat-label">Pinjaman Aktif</div>
                    </div>
                    <i class="bi bi-arrow-left-right stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary stats --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:#fed7d7;color:#c53030;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div style="font-size:1.25rem;font-weight:700;color:#2d3748;">{{ number_format($widgets['overdue_loans']) }}</div>
                        <div style="font-size:0.8rem;color:#a0aec0;">Pinjaman Terlambat</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:#c6f6d5;color:#22543d;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div style="font-size:1.25rem;font-weight:700;color:#2d3748;">{{ number_format($widgets['available_items']) }}</div>
                        <div style="font-size:0.8rem;color:#a0aec0;">Item Tersedia</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:#bee3f8;color:#2a4365;">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div>
                        <div style="font-size:1.25rem;font-weight:700;color:#2d3748;">{{ number_format($widgets['total_digital_assets']) }}</div>
                        <div style="font-size:0.8rem;color:#a0aec0;">Aset Digital</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:#e9d8fd;color:#553c9a;">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <div style="font-size:1.25rem;font-weight:700;color:#2d3748;">{{ number_format($widgets['total_users']) }}</div>
                        <div style="font-size:0.8rem;color:#a0aec0;">Total Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="pq-card p-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                <div class="d-flex flex-wrap gap-2">
                    @can('catalog.create')
                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Katalog</a>
                    @endcan
                    @can('members.create')
                    <a href="#" class="btn btn-sm btn-outline-success"><i class="bi bi-person-plus me-1"></i>Tambah Anggota</a>
                    @endcan
                    @can('circulation.process_loan')
                    <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-box-arrow-right me-1"></i>Proses Peminjaman</a>
                    @endcan
                    @can('circulation.process_return')
                    <a href="#" class="btn btn-sm btn-outline-info"><i class="bi bi-box-arrow-in-left me-1"></i>Proses Pengembalian</a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="pq-card p-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2"></i>Info Sistem</h6>
                <ul class="list-unstyled mb-0" style="font-size:0.85rem;color:#4a5568;">
                    <li class="mb-2"><strong>Versi:</strong> 1.0.0</li>
                    <li class="mb-2"><strong>Laravel:</strong> {{ app()->version() }}</li>
                    <li class="mb-2"><strong>PHP:</strong> {{ phpversion() }}</li>
                    <li><strong>Timezone:</strong> {{ config('app.timezone') }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
