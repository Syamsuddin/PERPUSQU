<div class="row g-3">
    {{-- Bibliographic Record --}}
    @if(!isset($item))
    <div class="col-md-12">
        <label class="form-label fw-medium">Katalog (Bibliographic Record) <span class="text-danger">*</span></label>
        <select class="form-select @error('bibliographic_record_id') is-invalid @enderror" name="bibliographic_record_id" required>
            <option value="">— Pilih Katalog —</option>
            @foreach($bibliographicRecords as $br)
            <option value="{{ $br->id }}" {{ old('bibliographic_record_id') == $br->id ? 'selected' : '' }}>{{ $br->title }} @if($br->isbn)({{ $br->isbn }})@endif</option>
            @endforeach
        </select>
        @error('bibliographic_record_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    {{-- Barcode --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Barcode <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" value="{{ old('barcode', $item->barcode ?? '') }}" required>
        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Inventory Code --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Kode Inventaris</label>
        <input type="text" class="form-control @error('inventory_code') is-invalid @enderror" name="inventory_code" value="{{ old('inventory_code', $item->inventory_code ?? '') }}">
        @error('inventory_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Acquisition Date --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Tanggal Perolehan</label>
        <input type="date" class="form-control @error('acquisition_date') is-invalid @enderror" name="acquisition_date" value="{{ old('acquisition_date', isset($item) && $item->acquisition_date ? $item->acquisition_date->format('Y-m-d') : '') }}">
        @error('acquisition_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Rack Location --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Lokasi Rak</label>
        <select class="form-select @error('rack_location_id') is-invalid @enderror" name="rack_location_id">
            <option value="">— Pilih —</option>
            @foreach($rackLocations as $rl)
            <option value="{{ $rl->id }}" {{ old('rack_location_id', $item->rack_location_id ?? '') == $rl->id ? 'selected' : '' }}>{{ $rl->name }}</option>
            @endforeach
        </select>
        @error('rack_location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Item Condition --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Kondisi</label>
        <select class="form-select @error('item_condition_id') is-invalid @enderror" name="item_condition_id">
            <option value="">— Pilih —</option>
            @foreach($itemConditions as $ic)
            <option value="{{ $ic->id }}" {{ old('item_condition_id', $item->item_condition_id ?? '') == $ic->id ? 'selected' : '' }}>{{ $ic->name }}</option>
            @endforeach
        </select>
        @error('item_condition_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status (only on create) --}}
    @if(!isset($item))
    <div class="col-md-4">
        <label class="form-label fw-medium">Status Awal <span class="text-danger">*</span></label>
        <select class="form-select @error('item_status') is-invalid @enderror" name="item_status" required>
            <option value="available" {{ old('item_status', 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="damaged" {{ old('item_status') === 'damaged' ? 'selected' : '' }}>Rusak</option>
            <option value="repair" {{ old('item_status') === 'repair' ? 'selected' : '' }}>Perbaikan</option>
            <option value="inactive" {{ old('item_status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        @error('item_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    {{-- Notes --}}
    <div class="col-12">
        <label class="form-label fw-medium">Catatan</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $item->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
