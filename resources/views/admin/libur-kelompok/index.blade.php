<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Libur Kelompok</h1>
            <small class="text-muted">Tetapkan tanggal latihan libur dan kirim notifikasi ke pelatih serta peserta.</small>
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
        <div class="card-header bg-danger text-white">Tambah Libur</div>
        <div class="card-body">
            <form action="{{ route('admin.libur-kelompok.store') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-12">
                    <label class="form-label">Kelompok</label>
                    <div class="border rounded p-3" style="max-height: 220px; overflow-y: auto;">
                        <div class="row">
                            @foreach($kelompoks as $kelompok)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            name="kelompok_ids[]"
                                            value="{{ $kelompok->id }}"
                                            id="kelompok_{{ $kelompok->id }}"
                                            class="form-check-input"
                                            {{ in_array($kelompok->id, old('kelompok_ids', [])) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="kelompok_{{ $kelompok->id }}">
                                            {{ $kelompok->nama_kelompok }}
                                            <span class="text-muted">({{ $kelompok->jadwals->count() }} jadwal)</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', 'Latihan Diliburkan') }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Alasan</label>
                    <input type="text" name="alasan" class="form-control" value="{{ old('alasan') }}" placeholder="Contoh: pelatih mengikuti kegiatan sanggar">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-danger w-100">Simpan & Kirim Notif</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kelompok, judul, alasan...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelompok</label>
                    <select name="kelompok_id" class="form-select">
                        <option value="">Semua Kelompok</option>
                        @foreach($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}" {{ (string) request('kelompok_id') === (string) $kelompok->id ? 'selected' : '' }}>
                                {{ $kelompok->nama_kelompok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('admin.libur-kelompok.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelompok</th>
                            <th>Jadwal</th>
                            <th>Judul</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liburs as $libur)
                            <tr>
                                <td>{{ $libur->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $libur->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>
                                    @if($libur->jadwal)
                                        {{ $libur->jadwal->hari }} {{ substr($libur->jadwal->jam_mulai, 0, 5) }} - {{ substr($libur->jadwal->jam_selesai, 0, 5) }}
                                    @else
                                        Semua jadwal
                                    @endif
                                </td>
                                <td>{{ $libur->judul }}</td>
                                <td>{{ $libur->alasan ?: '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $libur->status === 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($libur->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($libur->status === 'aktif')
                                        <form action="{{ route('admin.libur-kelompok.destroy', $libur) }}" method="POST" onsubmit="return confirm('Batalkan libur ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-secondary">Batalkan</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data libur kelompok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $liburs->links() }}
        </div>
    </div>
</x-app-layout>
