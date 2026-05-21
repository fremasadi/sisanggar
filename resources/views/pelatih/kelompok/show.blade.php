<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $kelompok->nama_kelompok }}</h1>
            <small class="text-muted">{{ $kelompok->label_tingkatan }} - Kelompok binaan Anda</small>
        </div>
        <a href="{{ route('pelatih.kelompok.index') }}" class="btn btn-secondary">Kembali</a>
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
                <div class="card-header bg-primary text-white">Jadwal Latihan</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
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
                                    <td colspan="3" class="text-center text-muted">Belum ada jadwal latihan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-warning text-dark">Buat Sesi Presensi</div>
                <div class="card-body">
                    <form action="{{ route('pelatih.presensi.store', $kelompok) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <input type="date" name="tanggal_presensi" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="judul_pertemuan" class="form-control" placeholder="Judul pertemuan">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="materi" class="form-control" placeholder="Materi">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="catatan" class="form-control" placeholder="Catatan">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-warning">Buat Presensi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">Peserta yang Dilatih</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th>Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelompok->anggota as $anggota)
                        <tr>
                            <td>{{ $anggota->peserta->name ?? '-' }}</td>
                            <td>{{ $anggota->peserta->no_hp ?? '-' }}</td>
                            <td>{{ ucfirst($anggota->status) }}</td>
                            <td>{{ optional($anggota->tanggal_masuk)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada peserta di kelompok ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">Riwayat Presensi</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pertemuan</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->presensis as $presensi)
                                <tr>
                                    <td>{{ $presensi->tanggal_presensi->format('d/m/Y') }}</td>
                                    <td>{{ $presensi->judul_pertemuan ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('pelatih.presensi.show', $presensi) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">Jadwal Ujian</div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Ujian</th>
                                <th>Tanggal</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->ujians as $ujian)
                                <tr>
                                    <td>{{ $ujian->nama_ujian }}</td>
                                    <td>{{ $ujian->tanggal_ujian->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('pelatih.ujian.show', $ujian) }}" class="btn btn-sm btn-success">Nilai</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada jadwal ujian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
