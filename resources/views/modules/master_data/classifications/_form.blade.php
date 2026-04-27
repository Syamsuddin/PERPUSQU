<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label fw-medium">Kode <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $item->code ?? '') }}" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-medium">Nama Klasifikasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $item->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium">Induk</label>
        <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
            <option value="">— Tanpa Induk —</option>
            @foreach($parents as $p)<option value="{{ $p->id }}" {{ old('parent_id', $item->parent_id ?? '') == $p->id ? 'selected' : '' }}>[{{ $p->code }}] {{ $p->name }}</option>@endforeach
        </select>
        @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4"><div class="form-check form-switch mt-4"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}><label class="form-check-label" for="isActive">Aktif</label></div></div>
</div>