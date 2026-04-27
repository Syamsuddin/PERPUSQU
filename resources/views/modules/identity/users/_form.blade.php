{{-- User form partial --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username ?? '') }}" required>
        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @if(!isset($user))
    <div class="col-md-6">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endif
    <div class="col-md-6">
        <label class="form-label">Role <span class="text-danger">*</span></label>
        @foreach($roles as $role)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="role_{{ $role->id }}" name="role_ids[]" value="{{ $role->id }}"
                {{ in_array($role->id, old('role_ids', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
            <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
        </div>
        @endforeach
        @error('role_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>
