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

    <div class="row g-4 mb-4">
        {{-- Quick Actions Modern UI --}}
        <div class="col-12">
            <h6 class="fw-bold mb-3" style="color:var(--pq-primary);"><i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat (Quick Actions)</h6>
            <div class="row g-3">
                @can('catalog.create')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.catalog.quick-entry.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:1rem; transition: transform 0.2s;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px;background:#e6fffa;color:#319795;">
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
                    <a href="{{ route('admin.members.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:1rem; transition: transform 0.2s;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px;background:#ebf8ff;color:#2b6cb0;">
                                <i class="bi bi-person-plus fs-4"></i>
                            </div>
                            <div class="fw-semibold text-dark">Daftar Anggota</div>
                            <small class="text-muted">Kelola Keanggotaan</small>
                        </div>
                    </a>
                </div>
                @endcan

                @can('circulation.process_loan')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.circulation.loans.create') ?? '#' }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:1rem; transition: transform 0.2s;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px;background:#fffaf0;color:#dd6b20;">
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
                    <a href="{{ route('admin.circulation.returns.create') ?? '#' }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:1rem; transition: transform 0.2s;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px;background:#f0fff4;color:#38a169;">
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
    </div>

    <div class="row g-4">
        {{-- Recent Catalogs --}}
        <div class="col-lg-6">
            <div class="pq-card p-0 h-100 overflow-hidden">
                <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="color:var(--pq-primary);"><i class="bi bi-journal-plus me-2"></i>Katalog Terbaru</h6>
                    <a href="{{ route('admin.catalog.records.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($widgets['recent_catalogs'] as $catalog)
                            <tr>
                                <td class="ps-4" style="width:50px;">
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:40px;height:50px;border:1px solid #e2e8f0;">
                                        @if($catalog->cover_path)
                                            <img src="{{ asset('storage/' . $catalog->cover_path) }}" style="width:100%;height:100%;object-fit:cover;border-radius:4px;">
                                        @else
                                            <i class="bi bi-book text-muted"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color:#2d3748;">{{ Str::limit($catalog->title, 40) }}</div>
                                    <small class="text-muted">{{ $catalog->authors->pluck('name')->join(', ') ?: 'Anonim' }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-{{ $catalog->publication_status == 'published' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $catalog->publication_status == 'published' ? 'success' : 'secondary' }} px-2 py-1">
                                        {{ ucfirst($catalog->publication_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data katalog.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Loans --}}
        <div class="col-lg-6">
            <div class="pq-card p-0 h-100 overflow-hidden">
                <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="color:var(--pq-primary);"><i class="bi bi-clock-history me-2"></i>Aktivitas Peminjaman</h6>
                    <a href="{{ route('admin.circulation.loans.history') ?? '#' }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($widgets['recent_loans'] as $loan)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:36px;height:36px;background:#e2e8f0;color:#4a5568;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $loan->member->name ?? 'Anggota Terhapus' }}</div>
                                            <small class="text-muted">{{ $loan->physicalItem->bibliographicRecord->title ?? 'Buku Terhapus' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    @if($loan->loan_status === 'active')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Dipinjam</span>
                                    @elseif($loan->loan_status === 'returned')
                                        <span class="badge bg-success"><i class="bi bi-check me-1"></i>Dikembalikan</span>
                                    @elseif($loan->loan_status === 'overdue')
                                        <span class="badge bg-danger"><i class="bi bi-exclamation me-1"></i>Terlambat</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($loan->loan_status) }}</span>
                                    @endif
                                    <div class="small text-muted mt-1">{{ \Carbon\Carbon::parse($loan->created_at)->diffForHumans() }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">Belum ada aktivitas sirkulasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
