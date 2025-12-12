<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pengguna</h1>

    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Cari Nama / Email</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Cari pengguna...">
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Semua</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pelatih" {{ request('role') == 'pelatih' ? 'selected' : '' }}>Pelatih</option>
                    <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                    <option value="pengunjung" {{ request('role') == 'pengunjung' ? 'selected' : '' }}>Pengunjung</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status_aktif" class="form-label">Status</label>
                <select name="status_aktif" id="status_aktif" class="form-select">
                    <option value="">Semua</option>
                    <option value="1" {{ request('status_aktif') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status_aktif') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>


    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="text-black">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                           <td>
                                <span class="badge bg-primary text-white">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->status_aktif)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-success"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
