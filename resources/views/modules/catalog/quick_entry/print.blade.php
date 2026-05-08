@extends('layouts.admin')
@section('title', 'Tambah Buku Cetak')
@section('page-title', 'Tambah Buku Cetak')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.quick-entry.index') }}">Tambah Koleksi</a></li>
    <li class="breadcrumb-item active">Buku Cetak</li>
@endsection

@section('content')
<form action="{{ route('admin.catalog.quick-entry.cetak.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Track Indicator --}}
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge fs-6 px-3 py-2" style="background:#1e3a5f;">
        <i class="bi bi-journal-bookmark me-1"></i> Jalur: Buku Cetak (Fisik)
    </span>
    <a href="{{ route('admin.catalog.quick-entry.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Ganti Jalur
    </a>
</div>

<div class="row g-3">

    {{-- KIRI: Metadata Katalog --}}
    <div class="col-lg-8">

        {{-- Identitas Buku --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem">
            <div class="card-header bg-white border-bottom fw-semibold py-3" style="border-radius:.75rem .75rem 0 0">
                <i class="bi bi-journal-bookmark me-1 text-primary"></i> Identitas Buku
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-medium">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Masukkan judul lengkap buku..." required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Pengarang <span class="text-danger">*</span></label>
                        <select name="author_ids[]" class="form-select @error('author_ids') is-invalid @enderror" multiple size="4" required>
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Tahan Ctrl / Cmd untuk memilih lebih dari satu</small>
                        @error('author_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Subjek</label>
                        <select name="subject_ids[]" class="form-select" multiple size="4">
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ in_array($s->id, old('subject_ids', [])) ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Penerbit</label>
                        <select name="publisher_id" class="form-select">
                            <option value="">— Pilih Penerbit —</option>
                            @foreach($publishers as $p)
                            <option value="{{ $p->id }}" {{ old('publisher_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Bahasa</label>
                        <select name="language_id" class="form-select">
                            <option value="">— Pilih Bahasa —</option>
                            @foreach($languages as $l)
                            <option value="{{ $l->id }}" {{ old('language_id') == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Tahun Terbit</label>
                        <input type="number" name="publication_year" class="form-control" value="{{ old('publication_year', date('Y')) }}" min="1000" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="978-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Edisi</label>
                        <input type="text" name="edition" class="form-control" value="{{ old('edition') }}" placeholder="Cth: 2nd Ed">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Klasifikasi (DDC)</label>
                        <select name="classification_id" class="form-select">
                            <option value="">— Pilih Klasifikasi —</option>
                            @foreach($classifications as $c)
                            <option value="{{ $c->id }}" {{ old('classification_id') == $c->id ? 'selected' : '' }}>[{{ $c->code }}] {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- hidden: paksa jenis koleksi = Buku --}}
                    <input type="hidden" name="collection_type_id" value="{{ $defaultTypeId }}">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Kata Kunci</label>
                        <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}" placeholder="Pisahkan dengan koma">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Abstrak / Sinopsis</label>
                        <textarea name="abstract" class="form-control" rows="3" placeholder="Ringkasan isi buku...">{{ old('abstract') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Foto Cover</label>
                        <input type="file" name="cover" class="form-control" accept="image/*">
                        <small class="text-muted">JPG/PNG/WebP, maks 4 MB</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_public" class="form-check-input" id="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Tampilkan di OPAC (Publik)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN: Data Item Fisik --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; border-top: 3px solid #1e3a5f !important;">
            <div class="card-header bg-white border-bottom fw-semibold py-3" style="border-radius:.75rem .75rem 0 0">
                <i class="bi bi-box-seam me-1" style="color:#1e3a5f;"></i> Data Item Fisik
                <span class="badge bg-primary ms-1" style="background:#1e3a5f !important;">Otomatis</span>
            </div>
            <div class="card-body p-3">
                <div class="alert alert-info py-2 px-3 mb-3 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Barcode akan digenerate otomatis. Setiap eksemplar mendapat barcode unik.
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Jumlah Eksemplar <span class="text-danger">*</span></label>
                    <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror"
                        value="{{ old('qty', 1) }}" min="1" max="100" required>
                    @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Maks 100 eksemplar sekaligus</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Lokasi Rak</label>
                    <select name="rack_location_id" class="form-select">
                        <option value="">— Pilih Rak —</option>
                        @foreach($rackLocations as $r)
                        <option value="{{ $r->id }}" {{ old('rack_location_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Kondisi Item</label>
                    <select name="item_condition_id" class="form-select">
                        <option value="">— Pilih Kondisi —</option>
                        @foreach($itemConditions as $c)
                        <option value="{{ $c->id }}" {{ old('item_condition_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Tanggal Pengadaan</label>
                    <input type="date" name="acquisition_date" class="form-control" value="{{ old('acquisition_date', date('Y-m-d')) }}">
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-lg text-white" style="background:#1e3a5f;">
                <i class="bi bi-check-circle me-2"></i> Simpan Buku Cetak
            </button>
            <a href="{{ route('admin.catalog.quick-entry.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x me-1"></i> Batal
            </a>
        </div>
    </div>

</div>
</form>
@endsection
