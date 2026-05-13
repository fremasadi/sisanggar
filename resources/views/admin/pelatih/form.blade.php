@csrf

<div class="mb-3">
    <label for="user_id" class="form-label">Pilih User</label>
    <select name="id_pelatih" id="user_id" class="form-control" required>
        <option value="">-- Pilih User dengan Role Pelatih --</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('id_pelatih', $pelatih->id_pelatih ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    @error('id_pelatih')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="text-end">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Simpan
    </button>
    <a href="{{ route('admin.pelatih.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
