@extends('layouts.admin')
@section('title', $asset->title ?: $asset->original_file_name)
@section('page-title', 'Detail Aset Digital')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.digital-assets.index') }}">Repositori Digital</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($asset->title ?: $asset->original_file_name, 30) }}</li>
@endsection
@section('content')
@php
$statusBadge = ['draft'=>'secondary','published'=>'success','unpublished'=>'warning','archived'=>'dark'];
$ocrBadge = ['not_requested'=>'light','queued'=>'info','processing'=>'primary','success'=>'success','failed'=>'danger'];
$typeLabels = ['ebook'=>'E-Book','thesis'=>'Skripsi','dissertation'=>'Disertasi','journal_article'=>'Artikel Jurnal','module'=>'Modul','scanned_book'=>'Buku Scan','supplementary'=>'Suplemen','other'=>'Lainnya'];
@endphp
<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="fw-bold mb-1">{{ $asset->title ?: $asset->original_file_name }}</h4>
                    <small class="text-muted">{{ $typeLabels[$asset->asset_type] ?? ucfirst($asset->asset_type) }}</small>
                </div>
                <span class="badge bg-{{ $statusBadge[$asset->publication_status] ?? 'light' }} fs-6">{{ ucfirst($asset->publication_status) }}</span>
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @can('digital_assets.update')<a href="{{ route('admin.digital-assets.edit', $asset) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
                @can('digital_assets.preview')<a href="{{ route('admin.digital-assets.preview', $asset) }}" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-eye me-1"></i>Preview</a>@endcan

                @can('digital_assets.publish')
                    @if($asset->publication_status !== 'published')
                    <form method="POST" action="{{ route('admin.digital-assets.publish', $asset) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Publikasikan aset ini?')"><i class="bi bi-check-circle me-1"></i>Publish</button>
                    </form>
                    @endif
                @endcan
                @can('digital_assets.unpublish')
                    @if($asset->publication_status === 'published')
                    <form method="POST" action="{{ route('admin.digital-assets.unpublish', $asset) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Unpublish aset ini?')"><i class="bi bi-x-circle me-1"></i>Unpublish</button>
                    </form>
                    @endif
                @endcan

                @if($asset->publication_status !== 'archived')
                <form method="POST" action="{{ route('admin.digital-assets.archive', $asset) }}" class="d-inline">@csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark" onclick="return confirm('Arsipkan aset ini?')"><i class="bi bi-archive me-1"></i>Arsipkan</button>
                </form>
                @endif

                @can('digital_assets.run_ocr')
                    @if(in_array($asset->ocr_status, ['not_requested', 'failed']))
                    <form method="POST" action="{{ route('admin.digital-assets.ocr', $asset) }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-text me-1"></i>Minta OCR</button>
                    </form>
                    @endif
                @endcan
            </div>

            {{-- Detail Table --}}
            <table class="table table-sm mb-0">
                <tr><th style="width:180px">Katalog</th><td><a href="{{ route('admin.catalog.records.show', $asset->bibliographic_record_id) }}">{{ $asset->bibliographicRecord->title ?? '-' }}</a></td></tr>
                <tr><th>Tipe Aset</th><td>{{ $typeLabels[$asset->asset_type] ?? ucfirst($asset->asset_type) }}</td></tr>
                <tr><th>File</th><td><i class="bi bi-file-earmark-pdf text-danger me-1"></i>{{ $asset->original_file_name }}</td></tr>
                <tr><th>Ukuran</th><td>{{ $asset->file_size_formatted }}</td></tr>
                <tr><th>MIME Type</th><td><code>{{ $asset->mime_type }}</code></td></tr>
                <tr><th>Checksum</th><td><code class="small">{{ Str::limit($asset->checksum, 32) }}</code></td></tr>
                <tr><th>Visibilitas</th><td>{!! $asset->is_public ? '<span class="badge bg-success"><i class="bi bi-globe me-1"></i>Publik</span>' : '<span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Privat</span>' !!}</td></tr>
                <tr><th>Embargo</th><td>{{ $asset->is_embargoed ? ($asset->embargo_until ? 'Sampai ' . $asset->embargo_until->format('d M Y') : 'Ya') : 'Tidak' }}</td></tr>
                <tr><th>Deskripsi</th><td>{{ $asset->description ?: '-' }}</td></tr>
            </table>
        </div></div>

        {{-- OCR Info --}}
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3"><i class="bi bi-file-earmark-text me-1"></i>OCR & Indexing</h6>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm mb-0">
                        <tr><th>OCR Status</th><td><span class="badge bg-{{ $ocrBadge[$asset->ocr_status] ?? 'light' }}">{{ ucfirst(str_replace('_', ' ', $asset->ocr_status)) }}</span></td></tr>
                        @if($asset->ocr_attempted_at)<tr><th>Terakhir OCR</th><td>{{ $asset->ocr_attempted_at->format('d M Y H:i') }}</td></tr>@endif
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm mb-0">
                        <tr><th>Index Status</th><td><span class="badge bg-{{ ['pending'=>'warning','queued'=>'info','processing'=>'primary','indexed'=>'success','failed'=>'danger'][$asset->index_status] ?? 'light' }}">{{ ucfirst($asset->index_status) }}</span></td></tr>
                        @if($asset->last_indexed_at)<tr><th>Terakhir Index</th><td>{{ $asset->last_indexed_at->format('d M Y H:i') }}</td></tr>@endif
                    </table>
                </div>
            </div>
            @if($asset->ocrText && $asset->ocrText->extracted_text)
            <div class="mt-3">
                <label class="form-label fw-medium small">Hasil OCR ({{ strlen($asset->ocrText->extracted_text) }} karakter)</label>
                <div class="border rounded p-2 bg-light" style="max-height:200px;overflow-y:auto"><pre class="mb-0 small">{{ Str::limit($asset->ocrText->extracted_text, 2000) }}</pre></div>
            </div>
            @endif
        </div></div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="fw-semibold mb-3">Informasi Sistem</h6>
            <table class="table table-sm mb-0">
                <tr><th>ID</th><td>{{ $asset->id }}</td></tr>
                <tr><th>Diunggah</th><td>{{ $asset->uploaded_at->format('d M Y H:i') }}</td></tr>
                <tr><th>Oleh</th><td>{{ $asset->uploadedBy->name ?? '-' }}</td></tr>
                <tr><th>Diperbarui</th><td>{{ $asset->updated_at->format('d M Y H:i') }}</td></tr>
            </table>
        </div></div>
    </div>
</div>
@endsection
