<div class="row g-3">
    {{-- Member Number --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">No. Anggota <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('member_number') is-invalid @enderror" name="member_number" value="{{ old('member_number', $member->member_number ?? '') }}" required>
        @error('member_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Name --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $member->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Type --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Tipe Anggota <span class="text-danger">*</span></label>
        <select class="form-select @error('member_type') is-invalid @enderror" name="member_type" required>
            <option value="">— Pilih —</option>
            @foreach(['student'=>'Mahasiswa','lecturer'=>'Dosen','staff'=>'Staf','alumni'=>'Alumni','guest'=>'Tamu'] as $v => $l)
            <option value="{{ $v }}" {{ old('member_type', $member->member_type ?? '') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('member_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Identity Number --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">No. Identitas (NIM/NIP/KTP)</label>
        <input type="text" class="form-control @error('identity_number') is-invalid @enderror" name="identity_number" value="{{ old('identity_number', $member->identity_number ?? '') }}">
        @error('identity_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Email --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $member->email ?? '') }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Phone --}}
    <div class="col-md-4">
        <label class="form-label fw-medium">Telepon</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $member->phone ?? '') }}">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Faculty --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Fakultas</label>
        <select class="form-select @error('faculty_id') is-invalid @enderror" name="faculty_id">
            <option value="">— Pilih —</option>
            @foreach($faculties as $f)
            <option value="{{ $f->id }}" {{ old('faculty_id', $member->faculty_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
            @endforeach
        </select>
        @error('faculty_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Study Program --}}
    <div class="col-md-6">
        <label class="form-label fw-medium">Program Studi</label>
        <select class="form-select @error('study_program_id') is-invalid @enderror" name="study_program_id">
            <option value="">— Pilih —</option>
            @foreach($studyPrograms as $sp)
            <option value="{{ $sp->id }}" {{ old('study_program_id', $member->study_program_id ?? '') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
            @endforeach
        </select>
        @error('study_program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Notes --}}
    <div class="col-12">
        <label class="form-label fw-medium">Catatan</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $member->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    {{-- Active (on create) --}}
    <div class="col-md-4">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="isActive">Aktif</label>
        </div>
    </div>
</div>
