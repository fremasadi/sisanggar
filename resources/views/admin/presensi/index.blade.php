<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-0">Manajemen Presensi</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Kelompok / Judul</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari presensi...">
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
                    <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary w-100">Reset</a>
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
                            <th>Judul Pertemuan</th>
                            <th>Dibuat Oleh</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $presensi)
                            <tr>
                                <td>{{ $presensi->tanggal_presensi->format('d/m/Y') }}</td>
                                <td>{{ $presensi->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>{{ $presensi->judul_pertemuan ?: '-' }}</td>
                                <td>{{ $presensi->dibuatOleh->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.presensi.show', $presensi) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $presensis->links() }}
        </div>
    </div>
</x-app-layout>
