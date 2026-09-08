@extends('layouts.admin')
@section('title', 'Denda Saya')
@section('page-title', 'Denda Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Denda Saya</li>
@endsection
@section('content')
@include('modules.member.portal._nav')

@if($outstanding > 0)
    <div class="alert alert-warning d-flex align-items-center">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <div>
            Total denda belum lunas: <strong>Rp {{ number_format($outstanding, 0, ',', '.') }}</strong>.
            Selama denda belum diselesaikan, Anda belum dapat meminjam koleksi baru.
        </div>
    </div>
@endif

<div class="pq-card p-4">
    @if($fines->isEmpty())
        <p class="text-muted mb-0 text-center py-4">
            <i class="bi bi-check-circle me-1"></i>Tidak ada catatan denda.
        </p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($fines as $fine)
                    <tr>
                        <td class="fw-semibold">{{ $fine->loan?->physicalItem?->bibliographicRecord?->title ?? '—' }}</td>
                        <td><small class="text-muted">{{ $fine->notes ?? '—' }}</small></td>
                        <td class="text-end">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td>
                            @switch($fine->status)
                                @case('outstanding')<span class="badge bg-danger">Belum lunas</span>@break
                                @case('settled')<span class="badge bg-success">Lunas</span>@break
                                @case('waived')<span class="badge bg-secondary">Dihapuskan</span>@break
                                @default<span class="badge bg-light text-dark">{{ $fine->status }}</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $fines->links() }}</div>
    @endif
</div>
@endsection
