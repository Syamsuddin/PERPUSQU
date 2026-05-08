@extends('layouts.admin')
@section('title', 'Tambah E-Book')
@section('page-title', 'Tambah E-Book')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.records.index') }}">Katalog</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.catalog.quick-entry.index') }}">Tambah Koleksi</a></li>
    <li class="breadcrumb-item active">E-Book</li>
@endsection

@section('content')
<form action="{{ route('admin.catalog.quick-entry.ebook.store') }}" method="POST" enctype="multipart/form-data">
@csrf

{{-- Track Indicator --}}
<div class="d-flex align-items-center gap-2 mb-3">
    <span class="badge fs-6 px-3 py-2" style="background:#38b2ac;">
        <i class="bi bi-file-pdf me-1"></i> Jalur: E-Book (Digital)
    </span>
    <a href="{{ route('admin.catalog.quick-entry.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Ganti Jalur
    </a>
</div>

<div class="row g-3">

    {{-- KIRI: Metadata Katalog --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem">
            <div class="card-header bg-white border-bottom fw-semibold py-3" style="border-radius:.75rem .75rem 0 0">
                <i class="bi bi-journal-bookmark me-1 text-success"></i> Identitas E-Book
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-medium">Judul E-Book <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Masukkan judul lengkap e-book..." required>
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
                        <label class="form-label fw-medium">ISBN / e-ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="978-...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Edisi</label>
                        <input type="text" name="edition" class="form-control" value="{{ old('edition') }}" placeholder="Cth: Ed. Revisi">
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
                    {{-- hidden: paksa jenis koleksi = E-Book --}}
                    <input type="hidden" name="collection_type_id" value="{{ $defaultTypeId }}">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Kata Kunci</label>
                        <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}" placeholder="Pisahkan dengan koma">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Abstrak / Sinopsis</label>
                        <textarea name="abstract" class="form-control" rows="3" placeholder="Ringkasan isi e-book...">{{ old('abstract') }}</textarea>
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

    {{-- KANAN: Upload File Digital --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem; border-top: 3px solid #38b2ac !important;">
            <div class="card-header bg-white border-bottom fw-semibold py-3" style="border-radius:.75rem .75rem 0 0">
                <i class="bi bi-file-pdf me-1" style="color:#38b2ac;"></i> File E-Book
            </div>
            <div class="card-body p-3">

                {{-- Drop zone visual --}}
                <div class="border border-2 border-dashed rounded-3 p-4 text-center mb-3" id="dropzone"
                    style="border-color:#38b2ac !important; background: #f0fdf9; cursor:pointer;">
                    <i class="bi bi-file-earmark-pdf text-muted" style="font-size:3rem;"></i>
                    <p class="fw-semibold mb-1 mt-2">Klik atau seret file PDF ke sini</p>
                    <p class="text-muted small mb-2">Maksimum 50 MB</p>
                    <label class="btn btn-sm btn-outline-success mb-0" for="fileInput">
                        <i class="bi bi-upload me-1"></i> Pilih File PDF
                    </label>
                    <input type="file" name="file" id="fileInput" class="d-none @error('file') is-invalid @enderror"
                        accept=".pdf" required onchange="updateDropzone(this)">
                    <p id="fileNameDisplay" class="text-success small mt-2 mb-0"></p>
                </div>
                @error('file')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                <div class="mb-3">
                    <label class="form-label fw-medium">Deskripsi File (opsional)</label>
                    <textarea name="ebook_description" class="form-control" rows="2"
                        placeholder="Deskripsi singkat tentang file ini...">{{ old('ebook_description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Status Publikasi</label>
                    <select name="publication_status" class="form-select">
                        <option value="draft" {{ old('publication_status', 'draft') == 'draft' ? 'selected' : '' }}>Draft (Tidak Tampil)</option>
                        <option value="published" {{ old('publication_status') == 'published' ? 'selected' : '' }}>Published (Langsung Aktif)</option>
                    </select>
                </div>

                <div class="alert alert-secondary py-2 px-3 small mb-3">
                    <i class="bi bi-cpu me-1"></i>
                    OCR dapat dijalankan setelah simpan melalui menu Repositori Digital.
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-lg text-white" style="background:#38b2ac;">
                <i class="bi bi-check-circle me-2"></i> Simpan E-Book
            </button>
            <a href="{{ route('admin.catalog.quick-entry.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x me-1"></i> Batal
            </a>
        </div>
    </div>

</div>
</form>

@push('scripts')
<script>
function updateDropzone(input) {
    const display = document.getElementById('fileNameDisplay');
    if (input.files && input.files[0]) {
        const size = (input.files[0].size / 1048576).toFixed(1);
        display.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + input.files[0].name + ' (' + size + ' MB)';
    }
}

// Drag & drop support
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');

dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.style.background = '#dcfce7'; });
dropzone.addEventListener('dragleave', () => { dropzone.style.background = '#f0fdf9'; });
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.style.background = '#f0fdf9';
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        updateDropzone(fileInput);
    }
});
dropzone.addEventListener('click', () => fileInput.click());
</script>
@endpush
@endsection
