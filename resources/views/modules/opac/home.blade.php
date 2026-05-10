@extends('layouts.opac')
@section('title', 'Beranda')
@section('meta-description', 'Cari koleksi perpustakaan GIBTHA LIBRARY — buku, jurnal, e-book, dan aset digital.')

@section('hero')
<div class="opac-hero">
    <div class="hero-inner">
        <div class="container">
            {{-- Bismillah --}}
            <p class="mb-2" style="font-family:'Amiri',serif; font-size:1.4rem; opacity:.9; letter-spacing:.5px;">
                بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ
            </p>
            <h1><i class="bi bi-search me-2" style="opacity:.85"></i>Cari Koleksi Perpustakaan</h1>
            <p>Temukan buku, jurnal, e-book, dan aset digital di perpustakaan kami</p>
            <div class="search-box">
                <form action="{{ route('opac.search') }}" method="GET" class="d-flex">
                    <input type="text" class="form-control" name="keyword"
                           placeholder="Ketik judul, penulis, ISBN, kata kunci..." autofocus>
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </form>
            </div>
            {{-- Quick Pill Links --}}
            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                @foreach($collectionTypes as $ct)
                <a href="{{ route('opac.search', ['collection_type_id' => $ct->id]) }}" class="pill-link">
                    {{ $ct->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
{{-- Stats band --}}
<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="stat-card-opac p-4 text-center h-100">
            <div class="stat-num">{{ number_format($stats['total_titles']) }}</div>
            <div class="stat-label"><i class="bi bi-journals me-1"></i>Judul Koleksi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-opac p-4 text-center h-100" style="border-left-color:var(--gold)">
            <div class="stat-num" style="color:var(--gold)">{{ number_format($stats['total_items']) }}</div>
            <div class="stat-label"><i class="bi bi-collection me-1"></i>Eksemplar Tersedia</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-opac p-4 text-center h-100" style="border-left-color:var(--green-light)">
            <div class="stat-num" style="color:var(--green-light)">{{ number_format($stats['total_digital']) }}</div>
            <div class="stat-label"><i class="bi bi-cloud-arrow-down me-1"></i>Aset Digital</div>
        </div>
    </div>
</div>

{{-- Quranic quote ornament --}}
<div class="text-center mb-5 py-3" style="border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
    <p class="mb-1" style="font-family:'Amiri',serif; font-size:1.5rem; color:var(--green-dark); letter-spacing:.5px;">
        اِقْرَأْ بِاسْمِ رَبِّكَ الَّذِيْ خَلَقَ
    </p>
    <p class="small mb-0" style="color:var(--text-muted);">
        "Bacalah dengan (menyebut) nama Tuhanmu yang menciptakan" — QS. Al-'Alaq: 1
    </p>
</div>

{{-- Latest Records --}}
<h4 class="section-heading mb-4">
    <i class="bi bi-clock-history"></i>Koleksi Terbaru
</h4>
<div class="row g-3">
    @forelse($latestRecords as $record)
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="record-card p-3 h-100">
            <div class="card-title mb-1">
                <a href="{{ route('opac.record.show', $record->id) }}">{{ Str::limit($record->title, 55) }}</a>
            </div>
            <p class="small mb-1" style="color:var(--text-muted)">
                {{ $record->authors->pluck('name')->join(', ') ?: 'Anonim' }}
            </p>
            <p class="small mb-0" style="color:var(--text-muted)">
                {{ $record->publisher->name ?? '' }}
                @if($record->publication_year) ({{ $record->publication_year }})@endif
            </p>
            @if($record->isbn)
            <p class="small mb-0 mt-1"><code style="color:var(--green-mid)">{{ $record->isbn }}</code></p>
            @endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5" style="color:var(--text-muted)">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Belum ada koleksi publik.
        </div>
    </div>
    @endforelse
</div>
@endsection
