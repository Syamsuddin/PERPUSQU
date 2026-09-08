@extends('layouts.admin')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')
@section('breadcrumb')
    <li class="breadcrumb-item active">Riwayat</li>
@endsection
@section('content')
@include('modules.member.portal._nav')

<div class="pq-card p-4">
    @if($loans->isEmpty())
        <p class="text-muted mb-0 text-center py-4">Belum ada riwayat peminjaman.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Dipinjam</th>
                        <th>Dikembalikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($loans as $loan)
                    <tr>
                        <td class="fw-semibold">{{ $loan->physicalItem?->bibliographicRecord?->title ?? '—' }}</td>
                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                        <td>{{ $loan->returned_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if($loan->loan_status === 'returned')
                                @if(($loan->returnTransaction?->late_days ?? 0) > 0)
                                    <span class="badge bg-warning text-dark">
                                        Terlambat {{ $loan->returnTransaction->late_days }} hari
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Tepat waktu</span>
                                @endif
                            @else
                                <span class="badge bg-primary">Sedang dipinjam</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $loans->links() }}</div>
    @endif
</div>
@endsection
