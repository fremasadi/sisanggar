@csrf

<div class="mb-3">
    <label class="form-label">Nama Kostum</label>
    <input type="text" name="nama_kostum" class="form-control"
           value="{{ old('nama_kostum', $kostum->nama_kostum ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Ukuran</label>
    <select name="ukuran" class="form-control" required>
        @php
            $ukuranList = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        @endphp

        <option value="">-- Pilih Ukuran --</option>

        @foreach ($ukuranList as $u)
            <option value="{{ $u }}"
                {{ old('ukuran', $kostum->ukuran ?? '') == $u ? 'selected' : '' }}>
                {{ $u }}
            </option>
        @endforeach
    </select>
</div>


<div class="mb-3">
    <label class="form-label">Harga Sewa (Rp)</label>
    <input type="number" step="0.01" name="harga_sewa" class="form-control"
           value="{{ old('harga_sewa', $kostum->harga_sewa ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Stok</label>
    <input type="number" name="stok" class="form-control"
           value="{{ old('stok', $kostum->stok ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="tersedia" {{ old('status', $kostum->status ?? '')=='tersedia'?'selected':'' }}>Tersedia</option>
        <option value="tidak" {{ old('status', $kostum->status ?? '')=='tidak'?'selected':'' }}>Tidak</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Gambar Kostum</label>
    <input type="file" name="image" class="form-control" accept="image/*">

    @if(isset($kostum) && $kostum->image)
    <div class="mt-2">
        <img src="{{ asset('kostum/' . $kostum->image) }}" 
             alt="Preview" 
             height="120">
    </div>
@endif

</div>


<a href="{{ route('admin.kostum.index') }}" class="btn btn-secondary">Kembali</a>
