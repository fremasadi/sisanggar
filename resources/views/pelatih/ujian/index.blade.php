<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Nilai Ujian Peserta</h1>
            <small class="text-muted">Lihat jadwal ujian dan input nilai peserta pada kelompok yang Anda latih.</small>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari Ujian</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kelompok atau ujian...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('pelatih.ujian.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama Ujian</th>
                        <th>Kelompok</th>
                        <th>Tanggal</th>
                        <th>Status</th>
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
                            <td>
                                <a href="{{ route('pelatih.ujian.show', $ujian) }}" class="btn btn-sm btn-success">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $ujians->links() }}
        </div>
    </div>
</x-app-layout>
