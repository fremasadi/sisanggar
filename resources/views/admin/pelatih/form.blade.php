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
</div>

<div class="mb-3">
    <label for="bidang_tari" class="form-label">Bidang Tari</label>
    <input type="text" name="bidang_tari" id="bidang_tari" class="form-control"
           value="{{ old('bidang_tari', $pelatih->bidang_tari ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="jadwal_tetap" class="form-label">Jadwal Tetap</label>
    <textarea name="jadwal_tetap" id="jadwal_tetap" class="form-control" rows="3"
              placeholder="Contoh: Senin & Rabu 15:00 - 17:00">{{ old('jadwal_tetap', $pelatih->jadwal_tetap ?? '') }}</textarea>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Simpan
    </button>
    <a href="{{ route('admin.pelatih.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
