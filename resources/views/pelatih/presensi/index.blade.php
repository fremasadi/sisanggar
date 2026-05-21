<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Presensi Peserta</h1>
            <small class="text-muted">Lihat dan kelola presensi dari kelompok yang Anda latih.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
        <div class="card-header bg-warning text-dark">Buat Sesi Presensi</div>
        <div class="card-body">
            <form action="{{ route('pelatih.presensi.store-from-index') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Kelompok</label>
                    <select name="kelompok_id" class="form-select" required>
                        <option value="">Pilih kelompok</option>
                        @foreach($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}" {{ (string) old('kelompok_id') === (string) $kelompok->id ? 'selected' : '' }}>
                                {{ $kelompok->nama_kelompok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_presensi" class="form-control" value="{{ old('tanggal_presensi', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Judul Pertemuan</label>
                    <input type="text" name="judul_pertemuan" class="form-control" value="{{ old('judul_pertemuan') }}" placeholder="Judul pertemuan">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Materi</label>
                    <input type="text" name="materi" class="form-control" value="{{ old('materi') }}" placeholder="Materi">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Catatan">
                </div>
                <div class="col-md-12">
                    <button class="btn btn-warning">Buat Presensi</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Presensi</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kelompok atau pertemuan...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('pelatih.presensi.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelompok</th>
                        <th>Pertemuan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensis as $presensi)
                        <tr>
                            <td>{{ $presensi->tanggal_presensi->format('d/m/Y') }}</td>
                            <td>{{ $presensi->kelompok->nama_kelompok ?? '-' }}</td>
                            <td>{{ $presensi->judul_pertemuan ?: '-' }}</td>
                            <td>
                                <a href="{{ route('pelatih.presensi.show', $presensi) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $presensis->links() }}
        </div>
    </div>
</x-app-layout>
