<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Ujian Kelompok</h1>
            <small class="text-muted">Buat jadwal ujian dan kelola hasil kenaikan kelompok.</small>
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
        <div class="card-header bg-success text-white">Buat Ujian Kelompok</div>
        <div class="card-body">
            <form action="{{ route('admin.ujian-kelompok.store-from-index') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Kelompok</label>
                    <select name="kelompok_id" class="form-select" required>
                        <option value="">Pilih kelompok</option>
                        @foreach($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}" {{ (string) old('kelompok_id') === (string) $kelompok->id ? 'selected' : '' }}>
                                {{ $kelompok->label_tingkatan ?? $kelompok->nama_kelompok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Ujian</label>
                    <input type="text" name="nama_ujian" class="form-control" value="{{ old('nama_ujian') }}" placeholder="Nama ujian" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_ujian" class="form-control" value="{{ old('tanggal_ujian') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['draft', 'dibuka', 'selesai'] as $status)
                            <option value="{{ $status }}" {{ old('status', 'draft') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelompok Tujuan</label>
                    <select name="kelompok_tujuan_id" class="form-select">
                        <option value="">Tanpa kelompok tujuan</option>
                        @foreach($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}" {{ (string) old('kelompok_tujuan_id') === (string) $kelompok->id ? 'selected' : '' }}>
                                {{ $kelompok->label_tingkatan ?? $kelompok->nama_kelompok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Lokasi">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Keterangan">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-success w-100">Buat Ujian</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Ujian / Kelompok</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari ujian...">
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
                    <a href="{{ route('admin.ujian-kelompok.index') }}" class="btn btn-secondary w-100">Reset</a>
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
                            <th>Nama Ujian</th>
                            <th>Kelompok</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Kelompok Tujuan</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ujians as $ujian)
                            <tr>
                                <td>{{ $ujian->nama_ujian }}</td>
                                <td>{{ $ujian->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>{{ $ujian->tanggal_ujian->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($ujian->status) }}</td>
                                <td>{{ $ujian->kelompokTujuan->nama_kelompok ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.ujian-kelompok.show', $ujian) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data ujian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $ujians->links() }}
        </div>
    </div>
</x-app-layout>
