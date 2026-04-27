@extends('layouts.admin')
@section('title', $record->title)
@section('page-title', 'Detail Katalog')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="row g-3">
    {{-- Left: Main Info --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1">{{ $record->title }}</h4>
                    <div class="text-muted small">
                        @if($record->isbn)<span class="me-3">ISBN: {{ $record->isbn }}</span>@endif
                        @if($record->edition)<span class="me-3">Edisi: {{ $record->edition }}</span>@endif
                        @if($record->publication_year)<span>Tahun: {{ $record->publication_year }}</span>@endif
                    </div>
                </div>
                @php
                    $statusBg = match($record->publication_status) {
                        'draft' => 'warning', 'published' => 'success',
                        'unpublished' => 'secondary', 'archived' => 'dark', default => 'light',
                    };
                @endphp
                <span class="badge bg-{{ $statusBg }} fs-6">{{ ucfirst($record->publication_status) }}</span>
            </div>

            {{-- State Actions --}}
            <div class="d-flex gap-2 mb-3">
                @can('catalog.update')
                <a href="{{ route('admin.catalog.records.edit', $record) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
                @endcan
                @can('catalog.publish')
                    @if(in_array($record->publication_status, ['draft', 'unpublished']))
                    <form method="POST" action="{{ route('admin.catalog.records.publish', $record) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Publikasikan katalog ini?')"><i class="bi bi-check-circle me-1"></i>Publish</button>
                    </form>
                    @endif
                @endcan
                @can('catalog.unpublish')
                    @if($record->publication_status === 'published')
                    <form method="POST" action="{{ route('admin.catalog.records.unpublish', $record) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Unpublish katalog ini?')"><i class="bi bi-pause-circle me-1"></i>Unpublish</button>
                    </form>
                    @endif
                @endcan
                @can('catalog.publish')
                    @if(in_array($record->publication_status, ['draft', 'published', 'unpublished']))
                    <form method="POST" action="{{ route('admin.catalog.records.archive', $record) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-dark" onclick="return confirm('Arsipkan katalog ini?')"><i class="bi bi-archive me-1"></i>Arsipkan</button>
                    </form>
                    @endif
                    @if($record->publication_status === 'archived')
                    <form method="POST" action="{{ route('admin.catalog.records.reactivate', $record) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-info" onclick="return confirm('Reaktivasi katalog ini?')"><i class="bi bi-arrow-counterclockwise me-1"></i>Reaktivasi</button>
                    </form>
                    @endif
                @endcan
            </div>

            {{-- Detail Table --}}
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Jenis Koleksi</th><td>{{ $record->collectionType->name ?? '-' }}</td></tr>
                <tr><th>Penerbit</th><td>{{ $record->publisher->name ?? '-' }}</td></tr>
                <tr><th>Bahasa</th><td>{{ $record->language->name ?? '-' }}</td></tr>
                <tr><th>Klasifikasi</th><td>{{ $record->classification ? '['.$record->classification->code.'] '.$record->classification->name : '-' }}</td></tr>
                <tr><th>Pengarang</th><td>@forelse($record->authors as $a)<span class="badge bg-primary me-1">{{ $a->name }}</span>@empty <span class="text-muted">-</span> @endforelse</td></tr>
                <tr><th>Subjek</th><td>@forelse($record->subjects as $s)<span class="badge bg-info me-1">{{ $s->name }}</span>@empty <span class="text-muted">-</span> @endforelse</td></tr>
                <tr><th>Kata Kunci</th><td>{{ $record->keywords ?: '-' }}</td></tr>
                <tr><th>Visibilitas</th><td>{!! $record->is_public ? '<span class="badge bg-success"><i class="bi bi-globe2 me-1"></i>Publik</span>' : '<span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Internal</span>' !!}</td></tr>
            </table>

            @if($record->abstract)
            <div class="mt-3"><h6 class="fw-semibold">Abstrak</h6><p class="text-muted">{{ $record->abstract }}</p></div>
            @endif
        </div></div>

        {{-- Physical Items Tab --}}
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-box-seam me-1"></i>Item Fisik ({{ $record->physicalItems->count() }})</h6>
            @if($record->physicalItems->count() > 0)
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light"><tr><th>Barcode</th><th>No. Panggil</th><th>Status</th><th>Kondisi</th></tr></thead>
                <tbody>
                    @foreach($record->physicalItems as $pi)
                    <tr>
                        <td><code>{{ $pi->barcode }}</code></td>
                        <td>{{ $pi->call_number ?? '-' }}</td>
                        <td><span class="badge bg-{{ $pi->item_status === 'available' ? 'success' : 'secondary' }}">{{ ucfirst($pi->item_status) }}</span></td>
                        <td>{{ $pi->itemCondition->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Belum ada item fisik terkait.</p>
            @endif
        </div></div>

        {{-- Digital Assets Tab --}}
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-cloud-arrow-up me-1"></i>Aset Digital ({{ $record->digitalAssets->count() }})</h6>
            @if($record->digitalAssets->count() > 0)
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light"><tr><th>Judul</th><th>Tipe</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($record->digitalAssets as $da)
                    <tr>
                        <td>{{ $da->title }}</td>
                        <td>{{ $da->mime_type }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($da->publication_status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Belum ada aset digital terkait.</p>
            @endif
        </div></div>
    </div>

    {{-- Right: Cover & Meta --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3"><div class="card-body text-center">
            @if($record->cover_path)
            <img src="{{ asset('storage/' . $record->cover_path) }}" class="img-fluid rounded mb-2" alt="Cover" style="max-height:300px">
            @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:200px">
                <i class="bi bi-image text-muted" style="font-size:3rem"></i>
            </div>
            <small class="text-muted">Belum ada cover</small>
            @endif
        </div></div>

        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3">Informasi Sistem</h6>
            <table class="table table-sm mb-0">
                <tr><th>ID</th><td>{{ $record->id }}</td></tr>
                <tr><th>Slug</th><td><small>{{ $record->slug }}</small></td></tr>
                <tr><th>Dibuat</th><td>{{ $record->created_at->format('d M Y H:i') }}</td></tr>
                <tr><th>Diperbarui</th><td>{{ $record->updated_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div></div>
    </div>
</div>
@endsection
