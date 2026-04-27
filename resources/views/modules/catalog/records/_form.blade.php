<div class="row g-3">
    {{-- Title --}}
    <div class="col-md-8">
        <label class="form-label fw-medium">Judul <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $record->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Collection Type --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Jenis Koleksi <span class="text-danger">*</span></label>
        <select class="form-select @error('collection_type_id') is-invalid @enderror" name="collection_type_id" required>
            <option value="">— Pilih —</option>
            @foreach($collectionTypes as $ct)
            <option value="{{ $ct->id }}" {{ old('collection_type_id', $record->collection_type_id ?? '') == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
            @endforeach
        </select>
        @error('collection_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Authors (multi-select) --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Pengarang <span class="text-danger">*</span></label>
        <select class="form-select @error('author_ids') is-invalid @enderror" name="author_ids[]" multiple size="5" required>
            @php $selectedAuthors = old('author_ids', isset($record) ? $record->authors->pluck('id')->toArray() : []); @endphp
            @foreach($authors as $author)
            <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthors) ? 'selected' : '' }}>{{ $author->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu</small>
        @error('author_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Subjects (multi-select) --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Subjek</label>
        <select class="form-select @error('subject_ids') is-invalid @enderror" name="subject_ids[]" multiple size="5">
            @php $selectedSubjects = old('subject_ids', isset($record) ? $record->subjects->pluck('id')->toArray() : []); @endphp
            @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ in_array($subject->id, $selectedSubjects) ? 'selected' : '' }}>{{ $subject->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu</small>
        @error('subject_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Publisher --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Penerbit</label>
        <select class="form-select @error('publisher_id') is-invalid @enderror" name="publisher_id">
            <option value="">— Pilih —</option>
            @foreach($publishers as $pub)
            <option value="{{ $pub->id }}" {{ old('publisher_id', $record->publisher_id ?? '') == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
            @endforeach
        </select>
        @error('publisher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Language --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Bahasa</label>
        <select class="form-select @error('language_id') is-invalid @enderror" name="language_id">
            <option value="">— Pilih —</option>
            @foreach($languages as $lang)
            <option value="{{ $lang->id }}" {{ old('language_id', $record->language_id ?? '') == $lang->id ? 'selected' : '' }}>{{ $lang->name }}</option>
            @endforeach
        </select>
        @error('language_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Classification --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Klasifikasi</label>
        <select class="form-select @error('classification_id') is-invalid @enderror" name="classification_id">
            <option value="">— Pilih —</option>
            @foreach($classifications as $cls)
            <option value="{{ $cls->id }}" {{ old('classification_id', $record->classification_id ?? '') == $cls->id ? 'selected' : '' }}>[{{ $cls->code }}] {{ $cls->name }}</option>
            @endforeach
        </select>
        @error('classification_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Year, ISBN, Edition --}}
    <div class="col-md-3">
        <label class="form-label fw-medium">Tahun Terbit</label>
        <input type="number" class="form-control @error('publication_year') is-invalid @enderror" name="publication_year" value="{{ old('publication_year', $record->publication_year ?? '') }}" min="1000" max="9999">
        @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">ISBN</label>
        <input type="text" class="form-control @error('isbn') is-invalid @enderror" name="isbn" value="{{ old('isbn', $record->isbn ?? '') }}">
        @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Edisi</label>
        <input type="text" class="form-control @error('edition') is-invalid @enderror" name="edition" value="{{ old('edition', $record->edition ?? '') }}">
        @error('edition')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-medium">Cover</label>
        <input type="file" class="form-control form-control-sm @error('cover') is-invalid @enderror" name="cover" accept=".jpg,.jpeg,.png,.webp">
        @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(isset($record) && $record->cover_path)
        <small class="text-muted">Cover saat ini: {{ basename($record->cover_path) }}</small>
        @endif
    </div>

    {{-- Keywords --}}
    <div class="col-12">
        <label class="form-label fw-medium">Kata Kunci</label>
        <input type="text" class="form-control @error('keywords') is-invalid @enderror" name="keywords" value="{{ old('keywords', $record->keywords ?? '') }}" placeholder="Pisahkan dengan koma">
        @error('keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Abstract --}}
    <div class="col-12">
        <label class="form-label fw-medium">Abstrak</label>
        <textarea class="form-control @error('abstract') is-invalid @enderror" name="abstract" rows="4">{{ old('abstract', $record->abstract ?? '') }}</textarea>
        @error('abstract')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Is Public --}}
    <div class="col-md-4">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic" {{ old('is_public', $record->is_public ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="isPublic">Tampilkan di OPAC Publik</label>
        </div>
    </div>
</div>
