@extends('layouts.opac')
@section('title', 'Pencarian')
@section('meta-description', 'Cari koleksi perpustakaan GIBTHA LIBRARY berdasarkan judul, penulis, ISBN.')

@push('styles')
<style>
    .cover-placeholder {
        width: 80px; height: 110px;
        background: var(--green-pale);
        border: 1px solid var(--border);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
    }
    .cover-placeholder i { color: var(--green-light); font-size: 1.8rem; }
    .result-title { font-weight: 700; color: var(--green-dark); font-size: .95rem; }
    .result-title:hover { color: var(--green-mid); }
    .meta-badge {
        display: inline-flex; align-items: center; gap: .25rem;
        background: var(--green-pale); color: var(--green-main);
        border-radius: 20px; padding: .2rem .65rem; font-size: .78rem; font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    {{-- Left Filters --}}
    <div class="col-md-3">
        <div class="filter-card p-3 sticky-top" style="top:1rem">
            <h6 class="fw-bold mb-3" style="color:var(--green-dark)">
                <i class="bi bi-funnel me-1" style="color:var(--gold)"></i>Filter Pencarian
            </h6>
            <form method="GET" action="{{ route('opac.search') }}">
                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color:var(--text-dark)">Kata Kunci</label>
                    <input type="text" class="form-control form-control-sm" name="keyword"
                           value="{{ request('keyword') }}" placeholder="Judul, penulis, ISBN...">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color:var(--text-dark)">Jenis Koleksi</label>
                    <select class="form-select form-select-sm" name="collection_type_id">
                        <option value="">Semua</option>
                        @foreach($collectionTypes as $ct)
                        <option value="{{ $ct->id }}" {{ request('collection_type_id') == $ct->id ? 'selected' : '' }}>
                            {{ $ct->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color:var(--text-dark)">Bahasa</label>
                    <select class="form-select form-select-sm" name="language_id">
                        <option value="">Semua</option>
                        @foreach($languages as $lang)
                        <option value="{{ $lang->id }}" {{ request('language_id') == $lang->id ? 'selected' : '' }}>
                            {{ $lang->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold" style="color:var(--text-dark)">Tahun Terbit</label>
                    <input type="number" class="form-control form-control-sm" name="publication_year"
                           value="{{ request('publication_year') }}" min="1900" max="{{ date('Y') }}" placeholder="Contoh: 2024">
                </div>
                <button type="submit" class="btn btn-filter-primary btn-sm w-100 mb-2">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
                <a href="{{ route('opac.search') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </a>
            </form>
        </div>
    </div>

    {{-- Right Results --}}
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-heading mb-0">
                @if(request('keyword'))
                    <i class="bi bi-search"></i>Hasil: "{{ request('keyword') }}"
                @else
                    <i class="bi bi-collection"></i>Semua Koleksi
                @endif
            </h5>
            <span class="small" style="color:var(--text-muted)">
                {{ number_format($results->total()) }} ditemukan
            </span>
        </div>

        @forelse($results as $record)
        <div class="record-card p-3 mb-3">
            <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                    <a href="{{ route('opac.record.show', $record->id) }}">
                        @if($record->cover_path)
                            <img src="{{ asset('storage/' . $record->cover_path) }}"
                                 class="rounded shadow-sm"
                                 style="width:80px; height:110px; object-fit:cover; border-radius:8px!important;"
                                 alt="{{ $record->title }}">
                        @else
                            <div class="cover-placeholder rounded">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif
                    </a>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        <a href="{{ route('opac.record.show', $record->id) }}"
                           class="result-title text-decoration-none">{{ $record->title }}</a>
                    </h6>
                    <p class="small mb-2" style="color:var(--text-muted)">
                        {{ $record->authors->pluck('name')->join(', ') ?: 'Anonim' }}
                    </p>
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        @if($record->publisher)
                        <span class="meta-badge"><i class="bi bi-building"></i>{{ $record->publisher->name }}</span>
                        @endif
                        @if($record->publication_year)
                        <span class="meta-badge"><i class="bi bi-calendar3"></i>{{ $record->publication_year }}</span>
                        @endif
                        @if($record->isbn)
                        <span class="meta-badge"><i class="bi bi-upc"></i>{{ $record->isbn }}</span>
                        @endif
                        @if($record->collectionType)
                        <span class="meta-badge" style="background:var(--gold-light); color:#7a5a00;">
                            {{ $record->collectionType->name }}
                        </span>
                        @endif
                    </div>
                    @if($record->abstract)
                    <p class="small mb-0" style="color:var(--text-muted)">
                        {{ Str::limit($record->abstract, 150) }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5" style="color:var(--text-muted)">
            <i class="bi bi-search fs-1 d-block mb-3"></i>
            <h6 style="color:var(--text-muted)">Tidak ada hasil ditemukan</h6>
            <p class="small">Coba gunakan kata kunci yang berbeda atau perluas filter pencarian.</p>
        </div>
        @endforelse

        @if($results->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $results->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
