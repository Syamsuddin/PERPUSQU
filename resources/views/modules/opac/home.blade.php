@extends('layouts.opac')
@section('title', 'Beranda')
@section('meta-description', 'Cari koleksi perpustakaan PERPUSQU — buku, jurnal, e-book, dan aset digital.')

@section('hero')
<div class="opac-hero">
    <div class="container">
        <h1><i class="bi bi-search me-2"></i>Cari Koleksi Perpustakaan</h1>
        <p>Temukan buku, jurnal, e-book, dan aset digital di perpustakaan kami</p>
        <div class="search-box">
            <form action="{{ route('opac.search') }}" method="GET" class="d-flex">
                <input type="text" class="form-control" name="keyword" placeholder="Ketik judul, penulis, ISBN, kata kunci..." autofocus>
                <button type="submit" class="btn"><i class="bi bi-search me-1"></i>Cari</button>
            </form>
        </div>
        {{-- Quick Pill Links --}}
        <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
            @foreach($collectionTypes as $ct)
            <a href="{{ route('opac.search', ['collection_type_id' => $ct->id]) }}" class="pill-link">{{ $ct->name }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4">
        <div class="fs-2 fw-bold" style="color:var(--opac-accent)">{{ number_format($stats['total_titles']) }}</div>
        <small class="text-muted">Judul Koleksi</small>
    </div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4">
        <div class="fs-2 fw-bold" style="color:var(--opac-accent)">{{ number_format($stats['total_items']) }}</div>
        <small class="text-muted">Eksemplar Tersedia</small>
    </div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm text-center p-4">
        <div class="fs-2 fw-bold" style="color:var(--opac-accent)">{{ number_format($stats['total_digital']) }}</div>
        <small class="text-muted">Aset Digital</small>
    </div></div>
</div>

{{-- Latest Records --}}
<h4 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Koleksi Terbaru</h4>
<div class="row g-3">
    @forelse($latestRecords as $record)
    <div class="col-md-3">
        <div class="record-card p-3 h-100">
            <div class="card-title"><a href="{{ route('opac.record.show', $record->id) }}">{{ Str::limit($record->title, 60) }}</a></div>
            <p class="text-muted small mb-1">{{ $record->authors->pluck('name')->join(', ') ?: 'Anonim' }}</p>
            <p class="text-muted small mb-1">{{ $record->publisher->name ?? '' }} {{ $record->publication_year ? '(' . $record->publication_year . ')' : '' }}</p>
            @if($record->isbn)<p class="small mb-0"><code>{{ $record->isbn }}</code></p>@endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada koleksi publik.</div>
    </div>
    @endforelse
</div>
@endsection
