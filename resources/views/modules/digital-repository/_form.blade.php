@php $typeLabels = ['ebook'=>'E-Book','thesis'=>'Skripsi','dissertation'=>'Disertasi','journal_article'=>'Artikel Jurnal','module'=>'Modul','scanned_book'=>'Buku Scan','supplementary'=>'Suplemen','other'=>'Lainnya']; @endphp
<div class="row g-3">
    {{-- Bibliographic Record --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Katalog Bibliografi <span class="text-danger">*</span></label>
        <select class="form-select @error('bibliographic_record_id') is-invalid @enderror" name="bibliographic_record_id" required {{ isset($asset) ? 'disabled' : '' }}>
            <option value="">— Pilih Katalog —</option>
            @foreach($records as $r)
            <option value="{{ $r->id }}" {{ old('bibliographic_record_id', $asset->bibliographic_record_id ?? '') == $r->id ? 'selected' : '' }}>{{ $r->title }}</option>
            @endforeach
        </select>
        @error('bibliographic_record_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Asset Type --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Tipe Aset <span class="text-danger">*</span></label>
        <select class="form-select @error('asset_type') is-invalid @enderror" name="asset_type" required>
            <option value="">— Pilih —</option>
            @foreach($typeLabels as $v => $l)
            <option value="{{ $v }}" {{ old('asset_type', $asset->asset_type ?? '') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('asset_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Title --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Judul Aset</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $asset->title ?? '') }}">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Publication Status --}}
    <div class="col-md-3">
        <label class="form-label fw-medium">Status Publikasi</label>
        <select class="form-select" name="publication_status">
            @foreach(['draft'=>'Draft','published'=>'Published','unpublished'=>'Unpublished','archived'=>'Archived'] as $v => $l)
            <option value="{{ $v }}" {{ old('publication_status', $asset->publication_status ?? 'draft') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    {{-- Visibility --}}
    <div class="col-md-3">
        <label class="form-label fw-medium">Visibilitas</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic" {{ old('is_public', $asset->is_public ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="isPublic">Publik</label>
        </div>
    </div>
    {{-- File Upload --}}
    @if(!isset($asset))
    <div class="col-md-6">
        <label class="form-label fw-medium">File PDF <span class="text-danger">*</span></label>
        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept=".pdf" required>
        <small class="text-muted">Maks. 50 MB, format PDF</small>
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @else
    <div class="col-md-6">
        <label class="form-label fw-medium">File Pengganti (opsional)</label>
        <input type="file" class="form-control" name="replacement_file" accept=".pdf">
        <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Maks. 50 MB</small>
    </div>
    @endif
    {{-- Embargo --}}
    <div class="col-md-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="is_embargoed" value="1" id="isEmbargoed" {{ old('is_embargoed', $asset->is_embargoed ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="isEmbargoed">Embargo</label>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Embargo Sampai</label>
        <input type="date" class="form-control form-control-sm" name="embargo_until" value="{{ old('embargo_until', isset($asset) && $asset->embargo_until ? $asset->embargo_until->format('Y-m-d') : '') }}">
    </div>
    {{-- Description --}}
    <div class="col-12">
        <label class="form-label fw-medium">Deskripsi</label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $asset->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
