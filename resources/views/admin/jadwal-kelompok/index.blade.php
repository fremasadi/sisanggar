<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Jadwal Kelompok</h1>
            <small class="text-muted">Atur jadwal latihan untuk setiap kelompok.</small>
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
        <div class="card-header bg-danger text-white">Tambah Jadwal</div>
        <div class="card-body">
            <form action="{{ route('admin.jadwal-kelompok.store-from-index') }}" method="POST" class="row g-2">
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
                    <label class="form-label">Hari</label>
                    <input type="text" name="hari" class="form-control" value="{{ old('hari') }}" placeholder="Senin" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Lokasi">
                </div>
                <div class="col-md-9">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Catatan">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-danger w-100">Tambah Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Kelompok / Lokasi / Hari</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari jadwal...">
                </div>
                <div class="col-md-4">
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
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.jadwal-kelompok.index') }}" class="btn btn-secondary w-100">Reset</a>
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
                            <th>Kelompok</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Lokasi</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                <td>{{ $jadwal->lokasi ?: '-' }}</td>
                                <td>{{ $jadwal->catatan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada jadwal kelompok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $jadwals->links() }}
        </div>
    </div>
</x-app-layout>
