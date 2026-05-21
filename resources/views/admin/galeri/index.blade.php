<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Galeri</h1>
            <small class="text-muted">Upload banyak foto untuk ditampilkan di halaman depan.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">Upload Galeri</div>
        <div class="card-body">
            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="row g-2">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Foto Galeri</label>
                    <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple required>
                    <small class="text-muted">Bisa pilih banyak foto sekaligus. Format JPG, PNG, WEBP. Maksimal 4 MB per foto.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" placeholder="Judul opsional">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-control" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check mt-2">
                        <input type="hidden" name="status_aktif" value="0">
                        <input type="checkbox" name="status_aktif" id="status_aktif" class="form-check-input" value="1" {{ old('status_aktif', true) ? 'checked' : '' }}>
                        <label for="status_aktif" class="form-check-label">Tampilkan</label>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-danger w-100">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Cari Judul</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari galeri...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Tampil</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Disembunyikan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($galeris as $galeri)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card shadow h-100">
                    <img src="{{ asset('galeri/' . $galeri->image) }}" alt="{{ $galeri->judul ?: 'Galeri' }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                    <div class="card-body">
                        <form action="{{ route('admin.galeri.update', $galeri) }}" method="POST" class="row g-2">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-8">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}" class="form-control form-control-sm" placeholder="Judul">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" value="{{ old('urutan', $galeri->urutan) }}" class="form-control form-control-sm" min="0">
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="hidden" name="status_aktif" value="0">
                                    <input type="checkbox" name="status_aktif" id="galeri-status-{{ $galeri->id }}" class="form-check-input" value="1" {{ $galeri->status_aktif ? 'checked' : '' }}>
                                    <label for="galeri-status-{{ $galeri->id }}" class="form-check-label">Tampilkan di halaman depan</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-warning">Update</button>
                            </div>
                        </form>
                        <form action="{{ route('admin.galeri.destroy', $galeri) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus foto galeri ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                    <div class="card-footer small text-muted">
                        Diupload oleh {{ $galeri->uploader->name ?? '-' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center text-muted">Belum ada foto galeri.</div>
                </div>
            </div>
        @endforelse
    </div>

    {{ $galeris->links() }}
</x-app-layout>
