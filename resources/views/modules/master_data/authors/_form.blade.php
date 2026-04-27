<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-medium">Nama Pengarang <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $item->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Nama Normalisasi</label>
        <input type="text" class="form-control @error('normalized_name') is-invalid @enderror" name="normalized_name" value="{{ old('normalized_name', $item->normalized_name ?? '') }}">
        @error('normalized_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-medium">Catatan</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $item->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="isActive">Aktif</label>
        </div>
    </div>
</div>
