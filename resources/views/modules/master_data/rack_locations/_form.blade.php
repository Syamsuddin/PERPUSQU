<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-medium">Kode Rak <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $item->code ?? '') }}" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-medium">Nama Rak <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $item->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Lantai</label>
        <input type="text" class="form-control @error('floor') is-invalid @enderror" name="floor" value="{{ old('floor', $item->floor ?? '') }}">
        @error('floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-medium">Ruangan</label>
        <input type="text" class="form-control @error('room') is-invalid @enderror" name="room" value="{{ old('room', $item->room ?? '') }}">
        @error('room')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-medium">Deskripsi</label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4"><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}><label class="form-check-label" for="isActive">Aktif</label></div></div>
</div>