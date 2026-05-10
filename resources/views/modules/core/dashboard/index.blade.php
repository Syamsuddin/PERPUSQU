@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

    {{-- ── Primary Stat Cards ───────────────────────────────────────── --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="pq-stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value">{{ number_format($widgets['total_catalog']) }}</div>
                        <div class="stat-label mt-1">Total Katalog</div>
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
                        <div class="stat-label mt-1">Total Item Fisik</div>
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
                        <div class="stat-label mt-1">Total Anggota</div>
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
                        <div class="stat-label mt-1">Pinjaman Aktif</div>
                    </div>
                    <i class="bi bi-arrow-left-right stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Secondary Stat Cards ─────────────────────────────────────── --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#fed7d7;color:#c53030;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:700;color:#2d3748;line-height:1.2;">{{ number_format($widgets['overdue_loans']) }}</div>
                        <div class="text-muted mt-1" style="font-size:0.82rem;">Pinjaman Terlambat</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#c6f6d5;color:#22543d;">
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:700;color:#2d3748;line-height:1.2;">{{ number_format($widgets['available_items']) }}</div>
                        <div class="text-muted mt-1" style="font-size:0.82rem;">Item Tersedia</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#bee3f8;color:#2a4365;">
                        <i class="bi bi-cloud-arrow-up fs-5"></i>
                    </div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:700;color:#2d3748;line-height:1.2;">{{ number_format($widgets['total_digital_assets']) }}</div>
                        <div class="text-muted mt-1" style="font-size:0.82rem;">Aset Digital</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pq-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;background:#e9d8fd;color:#553c9a;">
                        <i class="bi bi-person-gear fs-5"></i>
                    </div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:700;color:#2d3748;line-height:1.2;">{{ number_format($widgets['total_users']) }}</div>
                        <div class="text-muted mt-1" style="font-size:0.82rem;">Total Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Quick Actions ────────────────────────────────────────────── --}}
    <div class="mb-5">
        <h6 class="fw-bold mb-3" style="color:var(--pq-primary);">
            <i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat
        </h6>
        <div class="row g-3">
            @can('catalog.create')
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.catalog.quick-entry.index') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;background:#e6fffa;color:#319795;">
                            <i class="bi bi-plus-circle fs-4"></i>
                        </div>
                        <div class="fw-semibold text-dark">Tambah Koleksi</div>
                        <small class="text-muted">Cetak & E-Book</small>
                    </div>
                </a>
            </div>
            @endcan

            @can('members.create')
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.members.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;background:#ebf8ff;color:#2b6cb0;">
                            <i class="bi bi-person-plus fs-4"></i>
                        </div>
                        <div class="fw-semibold text-dark">Daftar Anggota</div>
                        <small class="text-muted">Tambah Anggota Baru</small>
                    </div>
                </a>
            </div>
            @endcan

            @can('circulation.process_loan')
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.circulation.loans.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;background:#fffaf0;color:#dd6b20;">
                            <i class="bi bi-box-arrow-right fs-4"></i>
                        </div>
                        <div class="fw-semibold text-dark">Peminjaman</div>
                        <small class="text-muted">Proses Pinjam Baru</small>
                    </div>
                </a>
            </div>
            @endcan

            @can('circulation.process_return')
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.circulation.returns.create') }}" class="text-decoration-none">
                    <div class="pq-card p-4 text-center pq-action-card">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;background:#f0fff4;color:#38a169;">
                            <i class="bi bi-box-arrow-in-left fs-4"></i>
                        </div>
                        <div class="fw-semibold text-dark">Pengembalian</div>
                        <small class="text-muted">Terima Buku Kembali</small>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>

    {{-- ── Recent Catalogs & Recent Loans ──────────────────────────── --}}
    <div class="row g-4">

        {{-- Katalog Terbaru --}}
        <div class="col-lg-6">
            <div class="pq-card overflow-hidden h-100">
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center" style="background:#f7fafc;">
                    <h6 class="fw-bold mb-0" style="color:var(--pq-primary);">
                        <i class="bi bi-journal-plus me-2"></i>Katalog Terbaru
                    </h6>
                    <a href="{{ route('admin.catalog.records.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 small">Lihat Semua →</a>
                </div>
                <div>
                    @forelse($widgets['recent_catalogs'] as $catalog)
                    <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="rounded flex-shrink-0 overflow-hidden d-flex align-items-center justify-content-center bg-light border" style="width:42px;height:54px;">
                            @if($catalog->cover_path)
                                <img src="{{ asset('storage/' . $catalog->cover_path) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <i class="bi bi-book text-muted"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold text-truncate" style="color:#2d3748;font-size:0.9rem;">{{ Str::limit($catalog->title, 42) }}</div>
                            <div class="text-muted mt-1" style="font-size:0.78rem;">{{ $catalog->authors->pluck('name')->join(', ') ?: 'Anonim' }}</div>
                        </div>
                        <span class="badge flex-shrink-0 {{ $catalog->publication_status === 'published' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}" style="font-size:0.72rem;">
                            {{ ucfirst($catalog->publication_status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>
                        Belum ada data katalog.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Aktivitas Peminjaman --}}
        <div class="col-lg-6">
            <div class="pq-card overflow-hidden h-100">
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center" style="background:#f7fafc;">
                    <h6 class="fw-bold mb-0" style="color:var(--pq-primary);">
                        <i class="bi bi-clock-history me-2"></i>Aktivitas Peminjaman
                    </h6>
                    <a href="{{ route('admin.circulation.loans.history') }}" class="btn btn-sm btn-link text-decoration-none p-0 small">Lihat Semua →</a>
                </div>
                <div>
                    @forelse($widgets['recent_loans'] as $loan)
                    <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#edf2f7;color:#4a5568;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold text-truncate" style="color:#2d3748;font-size:0.9rem;">{{ $loan->member->name ?? 'Anggota Terhapus' }}</div>
                            <div class="text-muted text-truncate mt-1" style="font-size:0.78rem;">{{ Str::limit($loan->physicalItem->bibliographicRecord->title ?? 'Buku Terhapus', 40) }}</div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @if($loan->loan_status === 'active' && isset($loan->due_date) && \Carbon\Carbon::parse($loan->due_date)->isPast())
                                <span class="badge bg-danger" style="font-size:0.72rem;">Terlambat</span>
                            @elseif($loan->loan_status === 'active')
                                <span class="badge bg-warning text-dark" style="font-size:0.72rem;">Dipinjam</span>
                            @elseif($loan->loan_status === 'returned')
                                <span class="badge bg-success" style="font-size:0.72rem;">Dikembalikan</span>
                            @else
                                <span class="badge bg-secondary" style="font-size:0.72rem;">{{ ucfirst($loan->loan_status) }}</span>
                            @endif
                            <div class="text-muted mt-1" style="font-size:0.72rem;">{{ \Carbon\Carbon::parse($loan->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2 opacity-25"></i>
                        Belum ada aktivitas sirkulasi.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
