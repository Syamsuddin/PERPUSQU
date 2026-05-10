@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<style>
    /* ── Dashboard Specific Premium Styles ── */
    .pq-glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }
    
    .stat-card-modern {
        position: relative;
        overflow: hidden;
        z-index: 1;
        border: none;
        border-radius: 1.25rem;
    }
    
    .stat-card-modern::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -20%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        z-index: -1;
    }

    .stat-card-modern .stat-icon-bg {
        position: absolute;
        right: 1.5rem;
        bottom: 1rem;
        font-size: 4rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        transition: transform 0.3s ease;
    }
    
    .stat-card-modern:hover .stat-icon-bg {
        transform: rotate(0deg) scale(1.1);
    }

    .action-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pq-action-card:hover .action-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #1a202c;
        position: relative;
        padding-left: 1rem;
    }

    .section-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 18px;
        background: var(--pq-accent);
        border-radius: 2px;
    }

    .activity-feed-item {
        transition: background 0.2s ease;
        border-radius: 0.75rem;
    }
    
    .activity-feed-item:hover {
        background: rgba(0,0,0,0.02);
    }
</style>

<div class="container-fluid p-0">
    {{-- ── Header Welcome Section ── --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
        <p class="text-muted small">Berikut adalah ringkasan operasional perpustakaan Anda hari ini.</p>
    </div>

    {{-- ── Primary Stat Cards ───────────────────────────────────────── --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern pq-stat-card h-100 p-4" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <i class="bi bi-journal-bookmark stat-icon-bg"></i>
                <div class="stat-label fw-medium text-white-50">Total Katalog</div>
                <div class="stat-value text-white mt-1">{{ number_format($widgets['total_catalog']) }}</div>
                <div class="mt-3 small text-white-50">
                    <i class="bi bi-plus-circle me-1"></i> Koleksi Terdaftar
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern pq-stat-card h-100 p-4" style="background: linear-gradient(135deg, #0d9488, #14b8a6);">
                <i class="bi bi-box-seam stat-icon-bg"></i>
                <div class="stat-label fw-medium text-white-50">Item Fisik</div>
                <div class="stat-value text-white mt-1">{{ number_format($widgets['total_items']) }}</div>
                <div class="mt-3 small text-white-50">
                    <i class="bi bi-check2-square me-1"></i> Eksemplar Tersedia
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern pq-stat-card h-100 p-4" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                <i class="bi bi-people stat-icon-bg"></i>
                <div class="stat-label fw-medium text-white-50">Total Anggota</div>
                <div class="stat-value text-white mt-1">{{ number_format($widgets['total_members']) }}</div>
                <div class="mt-3 small text-white-50">
                    <i class="bi bi-person-badge me-1"></i> Patron Terdaftar
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern pq-stat-card h-100 p-4" style="background: linear-gradient(135deg, #e11d48, #fb7185);">
                <i class="bi bi-arrow-left-right stat-icon-bg"></i>
                <div class="stat-label fw-medium text-white-50">Pinjaman Aktif</div>
                <div class="stat-value text-white mt-1">{{ number_format($widgets['active_loans']) }}</div>
                <div class="mt-3 small text-white-50">
                    <i class="bi bi-clock-history me-1"></i> Dalam Peminjaman
                </div>
            </div>
        </div>
    </div>

    {{-- ── Secondary Stat Cards ─────────────────────────────────────── --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="pq-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="action-icon-wrapper" style="background: #fff1f2; color: #e11d48;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5 text-dark">{{ number_format($widgets['overdue_loans']) }}</div>
                        <div class="text-muted small">Terlambat</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="action-icon-wrapper" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-check2-all fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5 text-dark">{{ number_format($widgets['available_items']) }}</div>
                        <div class="text-muted small">Tersedia</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="action-icon-wrapper" style="background: #f0f9ff; color: #0ea5e9;">
                        <i class="bi bi-cloud-arrow-up fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5 text-dark">{{ number_format($widgets['total_digital_assets']) }}</div>
                        <div class="text-muted small">Aset Digital</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="action-icon-wrapper" style="background: #faf5ff; color: #9333ea;">
                        <i class="bi bi-person-gear fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5 text-dark">{{ number_format($widgets['total_users']) }}</div>
                        <div class="text-muted small">Operator</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Quick Actions ────────────────────────────────────────────── --}}
    <div class="mb-5">
        <h6 class="section-title mb-4">Aksi Cepat & Pintasan</h6>
        <div class="row g-3">
            @can('catalog.create')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.catalog.quick-entry.index') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#f0fdfa;color:#0d9488;">
                            <i class="bi bi-plus-circle fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">Katalog Baru</div>
                    </div>
                </a>
            </div>
            @endcan

            @can('members.create')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.members.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#eff6ff;color:#2563eb;">
                            <i class="bi bi-person-plus fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">Anggota</div>
                    </div>
                </a>
            </div>
            @endcan

            @can('circulation.process_loan')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.circulation.loans.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#fff7ed;color:#ea580c;">
                            <i class="bi bi-box-arrow-right fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">Pinjam</div>
                    </div>
                </a>
            </div>
            @endcan

            @can('circulation.process_return')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.circulation.returns.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#f0fdf4;color:#16a34a;">
                            <i class="bi bi-box-arrow-in-left fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">Kembali</div>
                    </div>
                </a>
            </div>
            @endcan

            @can('digital_assets.create')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.digital-assets.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#fdf4ff;color:#c026d3;">
                            <i class="bi bi-cloud-upload fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">E-Book</div>
                    </div>
                </a>
            </div>
            @endcan

            @can('reports.view_dashboard')
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('admin.reports.index') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card border-0 shadow-sm h-100">
                        <div class="action-icon-wrapper mx-auto mb-3" style="background:#fefce8;color:#ca8a04;">
                            <i class="bi bi-bar-chart fs-3"></i>
                        </div>
                        <div class="fw-bold text-dark small">Laporan</div>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>

    {{-- ── Recent Catalogs & Recent Loans ──────────────────────────── --}}
    <div class="row g-4">

        {{-- Katalog Terbaru --}}
        <div class="col-lg-7">
            <div class="pq-card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 1.25rem;">
                <div class="px-4 py-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-plus me-2 text-primary"></i>Katalog Terbaru
                    </h6>
                    <a href="{{ route('admin.catalog.records.index') }}" class="btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold" style="font-size: 0.75rem;">Semua</a>
                </div>
                <div class="p-2">
                    @forelse($widgets['recent_catalogs'] as $catalog)
                    <div class="activity-feed-item d-flex align-items-center gap-3 px-3 py-3">
                        <div class="rounded-3 flex-shrink-0 overflow-hidden d-flex align-items-center justify-content-center bg-light" style="width:48px;height:64px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                            @if($catalog->cover_path)
                                <img src="{{ asset('storage/' . $catalog->cover_path) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <i class="bi bi-book text-muted fs-4"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-bold text-dark text-truncate" style="font-size:0.95rem;">{{ $catalog->title }}</div>
                            <div class="text-muted small mt-1 d-flex align-items-center gap-2">
                                <i class="bi bi-person"></i> {{ $catalog->authors->pluck('name')->join(', ') ?: 'Anonim' }}
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                             <span class="badge rounded-pill {{ $catalog->publication_status === 'published' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }} px-3 py-2" style="font-size:0.7rem; font-weight: 700;">
                                {{ strtoupper($catalog->publication_status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        Belum ada data katalog.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Aktivitas Peminjaman --}}
        <div class="col-lg-5">
            <div class="pq-card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 1.25rem;">
                <div class="px-4 py-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Sirkulasi
                    </h6>
                    <a href="{{ route('admin.circulation.loans.history') }}" class="btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold" style="font-size: 0.75rem;">Histori</a>
                </div>
                <div class="p-2">
                    @forelse($widgets['recent_loans'] as $loan)
                    <div class="activity-feed-item d-flex align-items-center gap-3 px-3 py-3">
                        <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-bold text-dark text-truncate" style="font-size:0.9rem;">{{ $loan->member->name ?? 'Anggota' }}</div>
                            <div class="text-muted text-truncate mt-1" style="font-size:0.75rem;">{{ $loan->physicalItem->bibliographicRecord->title ?? 'Buku' }}</div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @if($loan->loan_status === 'active' && isset($loan->due_date) && \Carbon\Carbon::parse($loan->due_date)->isPast())
                                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size:0.65rem;">LATE</span>
                            @elseif($loan->loan_status === 'active')
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size:0.65rem;">LOAN</span>
                            @elseif($loan->loan_status === 'returned')
                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size:0.65rem;">DONE</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size:0.65rem;">{{ strtoupper($loan->loan_status) }}</span>
                            @endif
                            <div class="text-muted x-small mt-1" style="font-size:0.65rem;">{{ \Carbon\Carbon::parse($loan->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-activity fs-2 d-block mb-2 opacity-25"></i>
                        Belum ada aktivitas.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
