@extends('layouts.admin')
@section('title', 'Pratinjau Import Excel')
@section('page-title', 'Pratinjau Import Excel')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.bulk-import.index') }}">Import Excel</a></li>
    <li class="breadcrumb-item active">Pratinjau</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm mb-4" style="border-radius:.75rem">
    <div class="card-header bg-white border-bottom fw-semibold py-3 d-flex justify-content-between align-items-center" style="border-radius:.75rem .75rem 0 0">
        <div>
            <i class="bi bi-eye me-1 text-primary"></i> Hasil Pratinjau
        </div>
        <div class="d-flex gap-3">
            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> {{ $totalValid }} Valid</span>
            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> {{ $totalError }} Error</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">Baris</th>
                        <th width="30%">Judul & Pengarang</th>
                        <th width="10%">Eksemplar</th>
                        <th width="20%">Penerbit & Tahun</th>
                        <th width="35%">Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($validatedRows as $row)
                    <tr>
                        <td class="text-center">{{ $row['row_number'] }}</td>
                        <td>
                            <strong>{{ $row['data']['judul_wajib'] ?? '(Kosong)' }}</strong><br>
                            <small class="text-muted">{{ $row['data']['pengarang_pisah_koma_wajib'] ?? '-' }}</small>
                        </td>
                        <td>{{ $row['data']['jumlah_eksemplar_wajib'] ?? 0 }}</td>
                        <td>
                            {{ $row['data']['penerbit'] ?? '-' }} <br>
                            <small class="text-muted">{{ $row['data']['tahun_terbit'] ?? '-' }}</small>
                        </td>
                        <td>
                            @if($row['is_valid'])
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Siap Diimpor
                                </span>
                            @else
                                <div class="text-danger small">
                                    <ul class="mb-0 ps-3">
                                        @foreach($row['errors'] as $err)
                                        <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.catalog.bulk-import.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    
    @if($totalValid > 0)
    <form action="{{ route('admin.catalog.bulk-import.process') }}" method="POST">
        @csrf
        <input type="hidden" name="file_path" value="{{ $path }}">
        <button type="submit" class="btn btn-primary" style="background:#1e3a5f;">
            <i class="bi bi-check2-all me-2"></i> Proses {{ $totalValid }} Data Valid
        </button>
    </form>
    @else
    <button class="btn btn-secondary" disabled>
        <i class="bi bi-check2-all me-2"></i> Tidak ada data valid untuk diproses
    </button>
    @endif
</div>
@endsection
