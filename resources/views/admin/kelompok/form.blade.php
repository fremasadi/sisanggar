<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Kelompok</label>
        <input type="text" name="nama_kelompok" value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}" class="form-control" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Jalur Tingkatan</label>
        <input type="text" name="jalur_tingkatan" value="{{ old('jalur_tingkatan', $kelompok->jalur_tingkatan) }}" class="form-control" placeholder="Contoh: Pra Dasar">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Nomor Tingkat</label>
        <input type="number" min="1" name="tingkat_nomor" value="{{ old('tingkat_nomor', $kelompok->tingkat_nomor) }}" class="form-control" placeholder="Contoh: 1">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Level Urutan</label>
        <input type="number" min="1" name="level_urutan" value="{{ old('level_urutan', $kelompok->level_urutan ?? 1) }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Pelatih</label>
        <select name="pelatih_id" class="form-select">
            <option value="">- Pilih Pelatih -</option>
            @foreach($pelatihs as $pelatih)
                <option value="{{ $pelatih->id }}" {{ old('pelatih_id', $kelompok->pelatih_id) == $pelatih->id ? 'selected' : '' }}>
                    {{ $pelatih->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 mb-2">
        <small class="text-muted">
            Jika kelompok ini bagian dari jenjang kenaikan kelas, isi `Jalur Tingkatan` dan `Nomor Tingkat`.
            Contoh: `Pra Dasar` + `1`, lalu kelompok berikutnya `Pra Dasar` + `2`.
        </small>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kelompok->deskripsi) }}</textarea>
    </div>
    <div class="col-md-12 mb-3">
        <input type="hidden" name="status_aktif" value="0">
        <div class="form-check">
            <input type="checkbox" name="status_aktif" id="status_aktif" class="form-check-input" value="1" {{ old('status_aktif', $kelompok->status_aktif ?? true) ? 'checked' : '' }}>
            <label for="status_aktif" class="form-check-label">Kelompok Aktif</label>
        </div>
    </div>
</div>

<button class="btn btn-danger">Simpan</button>
<a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary">Batal</a>
