<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $kelompok->nama_kelompok }}</h1>
            <small class="text-muted">Level {{ $kelompok->level_urutan }} - Pelatih: {{ $kelompok->pelatih->name ?? '-' }}</small>
        </div>
        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white">Tambah Peserta</div>
                <div class="card-body">
                    <form action="{{ route('admin.kelompok-peserta.store', $kelompok) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-12">
                            <select name="peserta_id" class="form-select" required>
                                <option value="">- Pilih Peserta -</option>
                                @foreach($pesertas as $peserta)
                                    <option value="{{ $peserta->id }}">{{ $peserta->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="tanggal_masuk" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="col-md-6">
                            <select name="status" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="lulus">Lulus</option>
                                <option value="pindah">Pindah</option>
                                <option value="keluar">Keluar</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-danger">Tambah Peserta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">Tambah Jadwal</div>
                <div class="card-body">
                    <form action="{{ route('admin.jadwal-kelompok.store', $kelompok) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <input type="text" name="hari" class="form-control" placeholder="Hari" required>
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="lokasi" class="form-control" placeholder="Lokasi">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="catatan" class="form-control" placeholder="Catatan">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary">Tambah Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">Buat Ujian Kelompok</div>
        <div class="card-body">
            <form action="{{ route('admin.ujian-kelompok.store', $kelompok) }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="nama_ujian" class="form-control" placeholder="Nama ujian" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_ujian" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="time" name="jam_mulai" class="form-control">
                </div>
                <div class="col-md-2">
                    <input type="text" name="lokasi" class="form-control" placeholder="Lokasi">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="dibuka">Dibuka</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="kelompok_tujuan_id" class="form-select">
                        <option value="">- Kelompok Tujuan -</option>
                        @foreach($targetKelompoks as $target)
                            <option value="{{ $target->id }}">{{ $target->nama_kelompok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">Buat Ujian</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">Anggota Kelompok</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->anggota as $anggota)
                                <tr>
                                    <td>{{ $anggota->peserta->name ?? '-' }}</td>
                                    <td>{{ ucfirst($anggota->status) }}</td>
                                    <td>
                                        <form action="{{ route('admin.kelompok-peserta.update', $anggota) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm">
                                                @foreach(['aktif', 'lulus', 'pindah', 'keluar'] as $status)
                                                    <option value="{{ $status }}" {{ $anggota->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="catatan" value="{{ $anggota->catatan }}">
                                            <button class="btn btn-sm btn-warning">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada anggota.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">Jadwal Kelompok</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->hari }}</td>
                                    <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                    <td>{{ $jadwal->lokasi ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada jadwal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">Daftar Ujian</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama Ujian</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Kelompok Tujuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelompok->ujians as $ujian)
                        <tr>
                            <td>{{ $ujian->nama_ujian }}</td>
                            <td>{{ $ujian->tanggal_ujian->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($ujian->status) }}</td>
                            <td>{{ $ujian->kelompokTujuan->nama_kelompok ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.ujian-kelompok.show', $ujian) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
