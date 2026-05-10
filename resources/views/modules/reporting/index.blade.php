@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Laporan Perpustakaan</h5>
        <small class="text-muted">Data statistik dan ringkasan operasional</small>
    </div>
    <form class="d-flex gap-2 align-items-center flex-wrap" method="GET" action="{{ route('admin.reports.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <select name="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        @if($tab === 'circulation')
        <select name="month" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="">Semua Bulan</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
            @endforeach
        </select>
        @endif
    </form>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" id="reportTabs">
    @can('reports.view_collections')
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'collections' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'collections', 'year' => $year]) }}">
            <i class="bi bi-book me-1"></i> Koleksi
        </a>
    </li>
    @endcan
    @can('reports.view_members')
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'members' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'members', 'year' => $year]) }}">
            <i class="bi bi-people me-1"></i> Anggota
        </a>
    </li>
    @endcan
    @can('reports.view_circulation')
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'circulation' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'circulation', 'year' => $year]) }}">
            <i class="bi bi-arrow-left-right me-1"></i> Sirkulasi
        </a>
    </li>
    @endcan
    @can('reports.view_fines')
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'fines' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['tab' => 'fines', 'year' => $year]) }}">
            <i class="bi bi-cash-stack me-1"></i> Denda
        </a>
    </li>
    @endcan
</ul>

{{-- ============================================================ --}}
{{-- TAB: KOLEKSI --}}
{{-- ============================================================ --}}
@if($tab === 'collections')
<div class="row g-3 mb-4">
    @php
        $statusLabels = ['draft' => 'Draft', 'published' => 'Dipublikasikan', 'unpublished' => 'Disembunyikan', 'archived' => 'Diarsipkan'];
        $statusColors = ['draft' => 'secondary', 'published' => 'success', 'unpublished' => 'warning', 'archived' => 'dark'];
    @endphp
    @foreach($statusLabels as $key => $label)
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-{{ $statusColors[$key] ?? 'secondary' }}">{{ number_format($byStatus[$key] ?? 0) }}</div>
            <div class="small text-muted mt-1">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-tag me-1"></i>Koleksi per Jenis</h6>
            @forelse($byType as $item)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small">{{ $item['label'] }}</span>
                <span class="badge bg-primary rounded-pill">{{ number_format($item['total']) }}</span>
            </div>
            @php $maxType = $byType->max('total') ?: 1; @endphp
            <div class="progress mb-3" style="height: 4px;">
                <div class="progress-bar" style="width: {{ round($item['total'] / $maxType * 100) }}%"></div>
            </div>
            @empty
            <p class="text-muted small">Belum ada data koleksi.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-6">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-archive me-1"></i>Eksemplar per Status</h6>
            @php
                $itemStatusLabels = ['available' => 'Tersedia', 'loaned' => 'Dipinjam', 'damaged' => 'Rusak', 'repair' => 'Perbaikan', 'lost' => 'Hilang', 'inactive' => 'Nonaktif'];
                $itemStatusColors = ['available' => 'success', 'loaned' => 'primary', 'damaged' => 'danger', 'repair' => 'warning', 'lost' => 'dark', 'inactive' => 'secondary'];
                $totalItems = $itemsByStatus->sum() ?: 1;
            @endphp
            @foreach($itemStatusLabels as $key => $label)
            @if(($itemsByStatus[$key] ?? 0) > 0)
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small"><span class="badge bg-{{ $itemStatusColors[$key] }} me-1">{{ $label }}</span></span>
                <span class="small fw-semibold">{{ number_format($itemsByStatus[$key] ?? 0) }}</span>
            </div>
            <div class="progress mb-2" style="height: 4px;">
                <div class="progress-bar bg-{{ $itemStatusColors[$key] }}" style="width: {{ round(($itemsByStatus[$key] ?? 0) / $totalItems * 100) }}%"></div>
            </div>
            @endif
            @endforeach
            <div class="mt-3 pt-2 border-top d-flex justify-content-between">
                <small class="text-muted">Total Eksemplar</small>
                <small class="fw-bold">{{ number_format($itemsByStatus->sum()) }}</small>
            </div>
        </div>
    </div>
</div>

@can('reports.view_popular_collections')
<div class="pq-card p-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-trophy me-1 text-warning"></i>10 Koleksi Paling Banyak Dipinjam</h6>
    @forelse($popular as $i => $loan)
    <div class="d-flex align-items-center gap-3 mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">{{ $i + 1 }}</div>
        <div class="flex-grow-1 min-width-0">
            <div class="fw-semibold small text-truncate">{{ $loan->physicalItem->bibliographicRecord->title }}</div>
            <div class="text-muted" style="font-size: 0.75rem;">Barcode: {{ $loan->physicalItem->barcode }}</div>
        </div>
        <span class="badge bg-primary rounded-pill flex-shrink-0">{{ $loan->loan_count }}x</span>
    </div>
    @empty
    <p class="text-muted small">Belum ada data peminjaman.</p>
    @endforelse
</div>
@endcan
@endif

{{-- ============================================================ --}}
{{-- TAB: ANGGOTA --}}
{{-- ============================================================ --}}
@if($tab === 'members')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-primary">{{ number_format($statusSummary['total']) }}</div>
            <div class="small text-muted mt-1">Total Anggota</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-success">{{ number_format($statusSummary['active']) }}</div>
            <div class="small text-muted mt-1">Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-secondary">{{ number_format($statusSummary['inactive']) }}</div>
            <div class="small text-muted mt-1">Tidak Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-danger">{{ number_format($statusSummary['blocked']) }}</div>
            <div class="small text-muted mt-1">Diblokir</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i>Anggota per Jenis</h6>
            @forelse($byType as $type => $total)
            @php $maxT = $byType->max() ?: 1; @endphp
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small text-capitalize">{{ $type ?? 'Tidak Diketahui' }}</span>
                <span class="badge bg-info text-dark rounded-pill">{{ number_format($total) }}</span>
            </div>
            <div class="progress mb-3" style="height: 4px;">
                <div class="progress-bar bg-info" style="width: {{ round($total / $maxT * 100) }}%"></div>
            </div>
            @empty
            <p class="text-muted small">Belum ada data anggota.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-6">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-building me-1"></i>Anggota per Fakultas</h6>
            @forelse($byFaculty as $item)
            @php $maxF = $byFaculty->max('total') ?: 1; @endphp
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small text-truncate me-2" style="max-width: 70%">{{ $item['label'] }}</span>
                <span class="badge bg-primary rounded-pill">{{ number_format($item['total']) }}</span>
            </div>
            <div class="progress mb-2" style="height: 4px;">
                <div class="progress-bar" style="width: {{ round($item['total'] / $maxF * 100) }}%"></div>
            </div>
            @empty
            <p class="text-muted small">Data fakultas tidak tersedia.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="pq-card p-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-trophy me-1 text-warning"></i>10 Anggota Paling Aktif ({{ $year ?? now()->year }})</h6>
    @forelse($topBorrowers as $i => $member)
    <div class="d-flex align-items-center gap-3 mb-2 {{ !$loop->last ? 'pb-2 border-bottom' : '' }}">
        <span class="text-muted small fw-semibold" style="min-width: 22px;">{{ $i + 1 }}</span>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $member->name }}</div>
            <div class="text-muted" style="font-size: 0.75rem;">{{ $member->member_number }} · {{ $member->member_type }}</div>
        </div>
        <span class="badge bg-success rounded-pill">{{ $member->loans_count }} pinjaman</span>
    </div>
    @empty
    <p class="text-muted small">Belum ada data peminjaman tahun ini.</p>
    @endforelse
</div>
@endif

{{-- ============================================================ --}}
{{-- TAB: SIRKULASI --}}
{{-- ============================================================ --}}
@if($tab === 'circulation')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-primary">{{ number_format($summary['total_loans']) }}</div>
            <div class="small text-muted mt-1">Total Pinjaman {{ $year }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-success">{{ number_format($summary['returned']) }}</div>
            <div class="small text-muted mt-1">Dikembalikan {{ $year }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-warning">{{ number_format($summary['active']) }}</div>
            <div class="small text-muted mt-1">Masih Dipinjam</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pq-card p-4 text-center">
            <div class="fs-2 fw-bold text-danger">{{ number_format($summary['overdue']) }}</div>
            <div class="small text-muted mt-1">Terlambat</div>
        </div>
    </div>
</div>

<div class="pq-card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-1"></i>Tren Peminjaman Bulanan — {{ $year }}</h6>
    @php $maxLoans = $months->max() ?: 1; @endphp
    <div class="d-flex gap-2 align-items-end" style="height: 140px;">
        @foreach($months as $m => $count)
        <div class="d-flex flex-column align-items-center flex-grow-1">
            <div class="small text-muted mb-1 fw-semibold" style="font-size: 0.7rem;">{{ $count > 0 ? $count : '' }}</div>
            <div class="w-100 rounded-top {{ $count > 0 ? 'bg-primary' : 'bg-light border' }}" style="height: {{ max(4, round($count / $maxLoans * 110)) }}px; min-height: 4px;"></div>
            <div class="small text-muted mt-1" style="font-size: 0.7rem;">{{ \Carbon\Carbon::create()->month($m)->format('M') }}</div>
        </div>
        @endforeach
    </div>
</div>

@if($overdueList->isNotEmpty())
<div class="pq-card p-4 mb-4">
    <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Pinjaman Terlambat ({{ $overdueList->count() }} data ditampilkan)</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Keterlambatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueList as $loan)
                <tr>
                    <td>{{ $loan->member?->name ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $loan->physicalItem?->bibliographicRecord?->title ?? '-' }}</td>
                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="text-danger fw-semibold">{{ $loan->due_date->format('d/m/Y') }}</td>
                    <td><span class="badge bg-danger">{{ $loan->due_date->diffInDays(now()) }} hari</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="pq-card p-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i>Transaksi Terbaru — {{ $year }}{{ $month ? ' / '.str_pad($month,2,'0',STR_PAD_LEFT) : '' }}</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover small mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLoans as $loan)
                <tr>
                    <td class="text-muted">{{ $loan->id }}</td>
                    <td>{{ $loan->member?->name ?? '-' }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $loan->physicalItem?->bibliographicRecord?->title ?? '-' }}</td>
                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                    <td>
                        @if($loan->loan_status === 'active' && $loan->due_date->isPast())
                            <span class="badge bg-danger">Terlambat</span>
                        @elseif($loan->loan_status === 'active')
                            <span class="badge bg-primary">Aktif</span>
                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ============================================================ --}}
{{-- TAB: DENDA --}}
{{-- ============================================================ --}}
@if($tab === 'fines')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="pq-card p-4 text-center">
            <div class="fs-4 fw-bold text-danger">Rp {{ number_format($summary['outstanding'], 0, ',', '.') }}</div>
            <div class="small text-muted mt-1">Belum Dilunasi</div>
            <div class="badge bg-danger mt-1">{{ $summary['count_outstanding'] }} tagihan</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="pq-card p-4 text-center">
            <div class="fs-4 fw-bold text-success">Rp {{ number_format($summary['settled'], 0, ',', '.') }}</div>
            <div class="small text-muted mt-1">Dilunasi ({{ $year }})</div>
            <div class="badge bg-success mt-1">{{ $summary['count_settled'] }} tagihan</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="pq-card p-4 text-center">
            <div class="fs-4 fw-bold text-secondary">Rp {{ number_format($summary['waived'], 0, ',', '.') }}</div>
            <div class="small text-muted mt-1">Dihapuskan ({{ $year }})</div>
            <div class="badge bg-secondary mt-1">{{ $summary['count_waived'] }} tagihan</div>
        </div>
    </div>
</div>

<div class="pq-card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-1"></i>Denda Timbul per Bulan — {{ $year }}</h6>
    @php $maxFine = $months->max('total') ?: 1; @endphp
    <div class="d-flex gap-2 align-items-end" style="height: 140px;">
        @foreach($months as $item)
        <div class="d-flex flex-column align-items-center flex-grow-1">
            <div class="small text-muted mb-1" style="font-size: 0.65rem;">{{ $item['count'] > 0 ? $item['count'] : '' }}</div>
            <div class="w-100 rounded-top {{ $item['total'] > 0 ? 'bg-danger' : 'bg-light border' }}" style="height: {{ max(4, round($item['total'] / $maxFine * 110)) }}px; min-height: 4px;"></div>
            <div class="small text-muted mt-1" style="font-size: 0.7rem;">{{ \Carbon\Carbon::create()->month($item['month'])->format('M') }}</div>
        </div>
        @endforeach
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-person-x me-1"></i>Anggota dengan Denda Terbesar</h6>
            @forelse($topDebtors as $i => $member)
            <div class="d-flex align-items-center gap-2 mb-2 {{ !$loop->last ? 'pb-2 border-bottom' : '' }}">
                <span class="text-muted small" style="min-width: 20px;">{{ $i+1 }}</span>
                <div class="flex-grow-1">
                    <div class="fw-semibold small">{{ $member->name }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $member->member_number }}</div>
                </div>
                <span class="badge bg-danger small">Rp {{ number_format($member->outstanding_amount, 0, ',', '.') }}</span>
            </div>
            @empty
            <p class="text-muted small">Tidak ada denda outstanding.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-7">
        <div class="pq-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-list-ul me-1"></i>Denda Terbaru — {{ $year }}</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Anggota</th>
                            <th>Judul Buku</th>
                            <th>Jumlah</th>
                            <th>Hari</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentFines as $fine)
                        <tr>
                            <td>{{ $fine->member?->name ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 140px;">{{ $fine->loan?->physicalItem?->bibliographicRecord?->title ?? '-' }}</td>
                            <td class="fw-semibold text-danger">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                            <td>{{ $fine->late_days }}h</td>
                            <td>
                                @if($fine->status === 'outstanding')
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @elseif($fine->status === 'settled')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-secondary">Dihapus</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">Tidak ada data denda tahun ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
