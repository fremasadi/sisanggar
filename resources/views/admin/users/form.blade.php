<div class="row">
    <div class="col-md-6 mb-3">
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror">
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>Role</label>
        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
            @foreach(['admin', 'pelatih', 'peserta', 'pengunjung'] as $role)
                <option value="{{ $role }}" {{ old('role', $user->role ?? 'peserta') == $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>Password {{ isset($user->id) ? '(Kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user->id) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" {{ isset($user->id) ? '' : 'required' }}>
    </div>
    <div class="col-md-12 mb-3">
        <input type="hidden" name="status_aktif" value="0">
        <div class="form-check">
            <input type="checkbox" class="form-check-input @error('status_aktif') is-invalid @enderror" id="status_aktif" name="status_aktif" value="1"
                   {{ old('status_aktif', $user->status_aktif) ? 'checked' : '' }}>
            <label class="form-check-label" for="status_aktif">Aktif</label>
        </div>
        @error('status_aktif')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<button type="submit" class="btn btn-danger">Simpan</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
