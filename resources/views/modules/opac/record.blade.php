@extends('layouts.opac')
@section('title', $record->title)
@section('meta-description', Str::limit($record->abstract ?? 'Detail koleksi perpustakaan', 160))

@push('styles')
<style>
    .meta-table th {
        width: 170px; font-weight: 600; color: var(--green-dark);
        border-bottom-color: var(--border); font-size: .88rem;
    }
    .meta-table td { color: var(--text-body); border-bottom-color: var(--border); font-size: .88rem; }
    .subject-badge {
        display: inline-block; padding: .25rem .7rem;
        background: var(--green-pale); color: var(--green-main);
        border-radius: 20px; font-size: .78rem; font-weight: 500;
    }
    .asset-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: .65rem .9rem; border: 1px solid var(--border); border-radius: 8px;
        background: var(--green-mist);
    }
    .cover-empty {
        height: 250px; background: var(--green-pale);
        border: 1px dashed var(--border); border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .detail-card {
        border: 1px solid var(--border); border-radius: 14px; background: #fff;
        box-shadow: 0 2px 12px rgba(46,125,82,.06);
    }
    .breadcrumb-item a { color: var(--green-main); text-decoration: none; }
    .breadcrumb-item a:hover { color: var(--green-dark); }
    .avail-count { font-size: 2.2rem; font-weight: 800; }
    .btn-preview {
        background: linear-gradient(135deg, var(--green-main), var(--green-mid));
        border: none; color: #fff; font-size: .82rem; font-weight: 600; border-radius: 6px;
    }
    .btn-preview:hover { background: var(--green-dark); color: #fff; }
</style>
@endpush

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('opac.home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('opac.search') }}">Pencarian</a></li>
        <li class="breadcrumb-item active" style="color:var(--text-muted)">{{ Str::limit($record->title, 45) }}</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- Main Detail --}}
    <div class="col-md-8">
        {{-- Title & Authors --}}
        <div class="detail-card p-4 mb-3">
            <h3 class="fw-bold mb-1" style="color:var(--green-dark); line-height:1.35">{{ $record->title }}</h3>
            @if($record->authors->count())
            <p style="color:var(--text-muted); font-size:.95rem" class="mb-3">
                <i class="bi bi-person me-1"></i>{{ $record->authors->pluck('name')->join(', ') }}
            </p>
            @endif

            <table class="table table-sm meta-table mb-0">
                @if($record->publisher)
                <tr><th>Penerbit</th><td>{{ $record->publisher->name }}</td></tr>
                @endif
                @if($record->publication_year)
                <tr><th>Tahun Terbit</th><td>{{ $record->publication_year }}</td></tr>
                @endif
                @if($record->isbn)
                <tr><th>ISBN</th><td><code style="color:var(--green-main)">{{ $record->isbn }}</code></td></tr>
                @endif
                @if($record->edition)
                <tr><th>Edisi</th><td>{{ $record->edition }}</td></tr>
                @endif
                @if($record->language)
                <tr><th>Bahasa</th><td>{{ $record->language->name }}</td></tr>
                @endif
                @if($record->classification)
                <tr><th>Klasifikasi</th><td>{{ $record->classification->code }} — {{ $record->classification->name }}</td></tr>
                @endif
                @if($record->collectionType)
                <tr><th>Jenis Koleksi</th>
                    <td>
                        <span class="subject-badge" style="background:rgba(200,151,42,.12); color:#7a5a00">
                            {{ $record->collectionType->name }}
                        </span>
                    </td>
                </tr>
                @endif
                @if($record->subjects->count())
                <tr><th>Subjek</th>
                    <td>
                        @foreach($record->subjects as $s)
                        <span class="subject-badge me-1">{{ $s->name }}</span>
                        @endforeach
                    </td>
                </tr>
                @endif
                @if($record->keywords)
                <tr><th>Kata Kunci</th><td style="color:var(--text-muted)">{{ $record->keywords }}</td></tr>
                @endif
            </table>
        </div>

        {{-- Abstract --}}
        @if($record->abstract)
        <div class="detail-card p-4 mb-3">
            <h5 class="fw-bold mb-3" style="color:var(--green-dark)">
                <i class="bi bi-text-paragraph me-2" style="color:var(--gold)"></i>Abstrak
            </h5>
            <p style="color:var(--text-body); line-height:1.85; margin-bottom:0">{{ $record->abstract }}</p>
        </div>
        @endif

        {{-- Digital Assets --}}
        @if($record->digitalAssets->count())
        <div class="detail-card p-4">
            <h5 class="fw-bold mb-3" style="color:var(--green-dark)">
                <i class="bi bi-file-earmark-pdf me-2" style="color:var(--gold)"></i>Aset Digital
            </h5>
            @foreach($record->digitalAssets as $asset)
            <div class="asset-row mb-2">
                <div>
                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                    <strong class="small" style="color:var(--text-dark)">{{ $asset->title ?: $asset->original_file_name }}</strong>
                    <span class="small ms-2" style="color:var(--text-muted)">
                        ({{ number_format($asset->file_size / 1048576, 1) }} MB)
                    </span>
                </div>
                <a href="{{ route('opac.asset.preview', $asset->id) }}" class="btn btn-sm btn-preview" target="_blank">
                    <i class="bi bi-eye me-1"></i>Preview
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-md-4">
        {{-- Cover --}}
        <div class="detail-card p-4 mb-3 text-center">
            @if($record->cover_path)
                <img src="{{ asset('storage/' . $record->cover_path) }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Cover {{ $record->title }}"
                     style="max-height:380px; object-fit:contain; border-radius:8px!important">
            @else
                <div class="cover-empty">
                    <i class="bi bi-image" style="font-size:3.5rem; color:var(--green-light)"></i>
                </div>
                <p class="small mt-2 mb-0" style="color:var(--text-muted)">Belum ada cover</p>
            @endif
        </div>

        {{-- Physical Availability --}}
        <div class="detail-card p-4 mb-3">
            <h6 class="fw-bold mb-3" style="color:var(--green-dark)">
                <i class="bi bi-building me-2" style="color:var(--green-mid)"></i>Ketersediaan Fisik
            </h6>
            @if($totalItems > 0)
            <div class="text-center mb-3">
                <div class="avail-count {{ $availableCount > 0 ? '' : '' }}"
                     style="color:{{ $availableCount > 0 ? 'var(--green-main)' : '#dc2626' }}">
                    {{ $availableCount }}
                </div>
                <small style="color:var(--text-muted)">dari {{ $totalItems }} eksemplar tersedia</small>
            </div>
            <div class="progress mb-3" style="height:7px; background:var(--green-pale); border-radius:4px">
                <div class="progress-bar"
                     style="width:{{ $totalItems > 0 ? ($availableCount / $totalItems * 100) : 0 }}%;
                            background:{{ $availableCount > 0 ? 'var(--green-mid)' : '#dc2626' }};
                            border-radius:4px">
                </div>
            </div>
            <div class="text-center">
                @if($availableCount > 0)
                <span class="badge badge-available px-3 py-2 fs-7">
                    <i class="bi bi-check-circle me-1"></i>Tersedia untuk dipinjam
                </span>
                @else
                <span class="badge badge-unavailable px-3 py-2 fs-7">
                    <i class="bi bi-x-circle me-1"></i>Semua sedang dipinjam
                </span>
                @endif
            </div>
            @else
            <p class="small text-center mb-0" style="color:var(--text-muted)">
                <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                Tidak ada eksemplar fisik.
            </p>
            @endif
        </div>

        {{-- Digital Availability --}}
        @if($record->digitalAssets->count())
        <div class="detail-card p-4">
            <h6 class="fw-bold mb-2" style="color:var(--green-dark)">
                <i class="bi bi-cloud me-2" style="color:var(--green-mid)"></i>Akses Digital
            </h6>
            <p class="small mb-0" style="color:var(--green-main)">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ $record->digitalAssets->count() }} aset digital tersedia
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
