@extends('layouts.opac')
@section('title', 'Pencarian')
@section('meta-description', 'Cari koleksi perpustakaan GIBTHA LIBRARY berdasarkan judul, penulis, ISBN.')

@section('content')
<div class="row g-4">
    {{-- Left Filters --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 sticky-top" style="top:1rem">
            <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-1"></i>Filter</h6>
            <form method="GET" action="{{ route('opac.search') }}">
                <div class="mb-3">
                    <label class="form-label small fw-medium">Kata Kunci</label>
                    <input type="text" class="form-control form-control-sm" name="keyword" value="{{ request('keyword') }}" placeholder="Judul, penulis, ISBN...">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Jenis Koleksi</label>
                    <select class="form-select form-select-sm" name="collection_type_id">
                        <option value="">Semua</option>
                        @foreach($collectionTypes as $ct)
                        <option value="{{ $ct->id }}" {{ request('collection_type_id') == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Bahasa</label>
                    <select class="form-select form-select-sm" name="language_id">
                        <option value="">Semua</option>
                        @foreach($languages as $lang)
                        <option value="{{ $lang->id }}" {{ request('language_id') == $lang->id ? 'selected' : '' }}>{{ $lang->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Tahun Terbit</label>
                    <input type="number" class="form-control form-control-sm" name="publication_year" value="{{ request('publication_year') }}" min="1900" max="{{ date('Y') }}" placeholder="Contoh: 2024">
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100 mb-2"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('opac.search') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Reset</a>
            </form>
        </div>
    </div>

    {{-- Right Results --}}
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                @if(request('keyword'))
                Hasil Pencarian: "{{ request('keyword') }}"
                @else
                Semua Koleksi
                @endif
            </h5>
            <small class="text-muted">{{ $results->total() }} ditemukan</small>
        </div>

        @forelse($results as $record)
        <div class="record-card p-3 mb-3">
            <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                    <a href="{{ route('opac.record.show', $record->id) }}">
                        @if($record->cover_path)
                            <img src="{{ asset('storage/' . $record->cover_path) }}" 
                                 class="rounded shadow-sm" 
                                 style="width:80px; height:110px; object-fit:cover;" 
                                 alt="{{ $record->title }}">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:80px; height:110px; border: 1px solid #e2e8f0;">
                                <i class="bi bi-book fs-2 text-muted"></i>
                            </div>
                        @endif
                    </a>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">
                        <a href="{{ route('opac.record.show', $record->id) }}" class="text-decoration-none" style="color:var(--opac-primary)">{{ $record->title }}</a>
                    </h6>
                    <p class="text-muted small mb-1">{{ $record->authors->pluck('name')->join(', ') ?: 'Anonim' }}</p>
                    <div class="d-flex flex-wrap gap-2 small text-muted">
                        @if($record->publisher)<span><i class="bi bi-building me-1"></i>{{ $record->publisher->name }}</span>@endif
                        @if($record->publication_year)<span><i class="bi bi-calendar me-1"></i>{{ $record->publication_year }}</span>@endif
                        @if($record->isbn)<span><i class="bi bi-upc me-1"></i>{{ $record->isbn }}</span>@endif
                        @if($record->collectionType)<span class="badge bg-light text-dark">{{ $record->collectionType->name }}</span>@endif
                    </div>
                    @if($record->abstract)
                    <p class="text-muted small mt-1 mb-0">{{ Str::limit($record->abstract, 150) }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 d-block mb-3 text-muted"></i>
            <h6 class="text-muted">Tidak ada hasil ditemukan</h6>
            <p class="text-muted small">Coba gunakan kata kunci yang berbeda atau perluas filter pencarian.</p>
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
