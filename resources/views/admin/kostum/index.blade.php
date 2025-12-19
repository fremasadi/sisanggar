<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Manajemen Kostum</h1>

    <a href="{{ route('admin.kostum.create') }}" class="btn btn-danger mb-3">
        <i class="fas fa-plus"></i> Tambah Kostum
    </a>

    @if(session('danger'))
        <div class="alert alert-danger">{{ session('danger') }}</div>
    @endif

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.kostum.index') }}" class="row g-2">

                <div class="col-md-3">
                    <label class="form-label">Cari Kostum</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Nama / Ukuran">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ request('status')=='tersedia'?'selected':'' }}>Tersedia</option>
                        <option value="tidak" {{ request('status')=='tidak'?'selected':'' }}>Tidak</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.kostum.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-black">
                    <tr>
                        <th>No</th>
                        <th>Nama Kostum</th>
                        <th>Ukuran</th>
                        <th>Harga Sewa</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kostums as $key => $k)
                        <tr>
                            <td>{{ $kostums->firstItem() + $key }}</td>
                            <td>{{ $k->nama_kostum }}</td>
                            <td>{{ $k->ukuran }}</td>
                            <td>Rp {{ number_format($k->harga_sewa, 0, ',', '.') }}</td>
                            <td>{{ $k->stok }}</td>
                            <td>
                                @if($k->status == 'tersedia')
                                    <span class="badge bg-danger text-white">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary text-white">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.kostum.edit', $k) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.kostum.destroy', $k) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus kostum ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data kostum.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $kostums->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
