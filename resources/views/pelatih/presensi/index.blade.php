<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Presensi Peserta</h1>
            <small class="text-muted">Lihat dan kelola presensi dari kelompok yang Anda latih.</small>
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
