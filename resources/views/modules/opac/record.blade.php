@extends('layouts.opac')
@section('title', $record->title)
@section('meta-description', Str::limit($record->abstract ?? 'Detail koleksi perpustakaan', 160))

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('opac.home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('opac.search') }}">Pencarian</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($record->title, 40) }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 mb-3">
            <h3 class="fw-bold mb-1" style="color:var(--opac-primary)">{{ $record->title }}</h3>
            @if($record->authors->count())
            <p class="text-muted mb-3">{{ $record->authors->pluck('name')->join(', ') }}</p>
            @endif

            <table class="table table-sm mb-0">
                @if($record->publisher)<tr><th style="width:180px">Penerbit</th><td>{{ $record->publisher->name }}</td></tr>@endif
                @if($record->publication_year)<tr><th>Tahun Terbit</th><td>{{ $record->publication_year }}</td></tr>@endif
                @if($record->isbn)<tr><th>ISBN</th><td><code>{{ $record->isbn }}</code></td></tr>@endif
                @if($record->edition)<tr><th>Edisi</th><td>{{ $record->edition }}</td></tr>@endif
                @if($record->language)<tr><th>Bahasa</th><td>{{ $record->language->name }}</td></tr>@endif
                @if($record->classification)<tr><th>Klasifikasi</th><td>{{ $record->classification->code }} — {{ $record->classification->name }}</td></tr>@endif
                @if($record->collectionType)<tr><th>Jenis Koleksi</th><td>{{ $record->collectionType->name }}</td></tr>@endif
                @if($record->subjects->count())<tr><th>Subjek</th><td>
                    @foreach($record->subjects as $s)
                    <span class="badge bg-light text-dark me-1">{{ $s->name }}</span>
                    @endforeach
                </td></tr>@endif
                @if($record->keywords)<tr><th>Kata Kunci</th><td>{{ $record->keywords }}</td></tr>@endif
            </table>
        </div>

        {{-- Abstrak --}}
        @if($record->abstract)
        <div class="card border-0 shadow-sm p-4 mb-3">
            <h5 class="fw-bold mb-2"><i class="bi bi-text-paragraph me-1"></i>Abstrak</h5>
            <p class="text-muted mb-0" style="line-height:1.8">{{ $record->abstract }}</p>
        </div>
        @endif

        {{-- Digital Assets --}}
        @if($record->digitalAssets->count())
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-pdf me-1"></i>Aset Digital</h5>
            @foreach($record->digitalAssets as $asset)
            <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">
                <div>
                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                    <strong class="small">{{ $asset->title ?: $asset->original_file_name }}</strong>
                    <span class="text-muted small ms-2">({{ number_format($asset->file_size / 1048576, 1) }} MB)</span>
                </div>
                <a href="{{ route('opac.asset.preview', $asset->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                    <i class="bi bi-eye me-1"></i>Preview
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Availability --}}
        <div class="card border-0 shadow-sm p-4 mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-building me-1"></i>Ketersediaan Fisik</h6>
            @if($totalItems > 0)
            <div class="text-center mb-3">
                <div class="fs-2 fw-bold {{ $availableCount > 0 ? 'text-success' : 'text-danger' }}">{{ $availableCount }}</div>
                <small class="text-muted">dari {{ $totalItems }} eksemplar tersedia</small>
            </div>
            <div class="progress" style="height:8px">
                <div class="progress-bar bg-success" style="width:{{ $totalItems > 0 ? ($availableCount / $totalItems * 100) : 0 }}%"></div>
            </div>
            <div class="mt-3 text-center">
                @if($availableCount > 0)
                <span class="badge badge-available px-3 py-2"><i class="bi bi-check-circle me-1"></i>Tersedia untuk dipinjam</span>
                @else
                <span class="badge badge-unavailable px-3 py-2"><i class="bi bi-x-circle me-1"></i>Semua sedang dipinjam</span>
                @endif
            </div>
            @else
            <p class="text-muted small text-center mb-0">Tidak ada eksemplar fisik.</p>
            @endif
        </div>

        {{-- Digital Availability --}}
        @if($record->digitalAssets->count())
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-2"><i class="bi bi-cloud me-1"></i>Akses Digital</h6>
            <p class="text-success small mb-0"><i class="bi bi-check-circle me-1"></i>{{ $record->digitalAssets->count() }} aset digital tersedia</p>
        </div>
        @endif
    </div>
</div>
@endsection
