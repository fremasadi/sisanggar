<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-0">Manajemen Kelompok</h1>
        <a href="{{ route('admin.kelompok.create') }}" class="btn btn-danger">Tambah Kelompok</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kelompok">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary w-100">Filter</button>
                </div>
            </form>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Pelatih</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelompoks as $kelompok)
                        <tr>
                            <td>{{ $kelompok->nama_kelompok }}</td>
                            <td>{{ $kelompok->level_urutan }}</td>
                            <td>{{ $kelompok->pelatih->name ?? '-' }}</td>
                            <td>{{ $kelompok->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>
                                <a href="{{ route('admin.kelompok.show', $kelompok) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                <a href="{{ route('admin.kelompok.edit', $kelompok) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.kelompok.destroy', $kelompok) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kelompok ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada kelompok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $kelompoks->links() }}
        </div>
    </div>
</x-app-layout>
