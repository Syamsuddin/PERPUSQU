@extends('layouts.admin')
@section('title', 'Pinjaman Saya')
@section('page-title', 'Pinjaman Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pinjaman Saya</li>
@endsection
@section('content')
@include('modules.member.portal._nav')

<div class="pq-card p-4">
    @if($loans->isEmpty())
        <p class="text-muted mb-0 text-center py-4">
            <i class="bi bi-emoji-smile me-1"></i>Tidak ada pinjaman yang sedang berjalan.
        </p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($loans as $loan)
                    @php
                        $dueDate = $loan->due_date;
                        $isOverdue = $dueDate->isPast();
                        $daysLeft = (int) now()->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false);
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $loan->physicalItem?->bibliographicRecord?->title ?? '—' }}</div>
                            <small class="text-muted">
                                {{ $loan->physicalItem?->bibliographicRecord?->authors->pluck('name')->join(', ') ?: 'Tanpa pengarang' }}
                            </small>
                        </td>
                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                        <td>{{ $dueDate->format('d M Y') }}</td>
                        <td>
                            @if($isOverdue)
                                <span class="badge bg-danger">Terlambat {{ abs($daysLeft) }} hari</span>
                            @elseif($daysLeft <= 3)
                                <span class="badge bg-warning text-dark">{{ $daysLeft }} hari lagi</span>
                            @else
                                <span class="badge bg-success">{{ $daysLeft }} hari lagi</span>
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
