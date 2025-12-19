<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pelatih</h1>

    <a href="{{ route('admin.pelatih.create') }}" class="btn btn-danger mb-3">
        <i class="fas fa-plus"></i> Tambah Pelatih
    </a>

    @if(session('danger'))
        <div class="alert alert-danger">{{ session('danger') }}</div>
    @endif

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-black">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Bidang Tari</th>
                        <th>Jadwal Tetap</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelatihs as $key => $p)
                        <tr>
                            <td>{{ $pelatihs->firstItem() + $key }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ $p->user->email ?? '-' }}</td>
                            <td>{{ $p->user->no_hp ?? '-' }}</td>
                            <td>{{ $p->bidang_tari ?? '-' }}</td>
                            <td>{{ $p->jadwal_tetap ?? '-' }}</td>
                            <td>
                                @if(optional($p->user)->status_aktif)
                                    <span class="badge bg-danger text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pelatih.edit', $p->id_pelatih) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pelatih.destroy', $p->id_pelatih) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Hapus pelatih ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data pelatih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $pelatihs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
