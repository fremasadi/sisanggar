<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Detail Presensi</h1>
            <small class="text-muted">{{ $presensi->kelompok->nama_kelompok }} - {{ $presensi->tanggal_presensi->format('d/m/Y') }}</small>
        </div>
        <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">Informasi Pertemuan</div>
        <div class="card-body">
            <form action="{{ route('admin.presensi.update', $presensi) }}" method="POST" class="row g-3">
                @csrf
                @method('PATCH')
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_presensi" value="{{ old('tanggal_presensi', $presensi->tanggal_presensi->format('Y-m-d')) }}" class="form-control" required>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Judul Pertemuan</label>
                    <input type="text" name="judul_pertemuan" value="{{ old('judul_pertemuan', $presensi->judul_pertemuan) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Materi</label>
                    <textarea name="materi" class="form-control" rows="3">{{ old('materi', $presensi->materi) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $presensi->catatan) }}</textarea>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-danger">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">Daftar Kehadiran Peserta</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Status Kehadiran</th>
                            <th>Catatan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensi->details as $detail)
                            <tr>
                                <td>{{ $detail->peserta->name ?? '-' }}</td>
                                <td>
                                    <select name="status_kehadiran" form="presensi-detail-{{ $detail->id }}" class="form-select form-select-sm">
                                        @foreach(['hadir', 'izin', 'sakit', 'alpa'] as $status)
                                            <option value="{{ $status }}" {{ $detail->status_kehadiran === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="catatan" value="{{ $detail->catatan }}" form="presensi-detail-{{ $detail->id }}" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <form id="presensi-detail-{{ $detail->id }}" action="{{ route('admin.presensi-detail.update', $detail) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-warning w-100">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada detail presensi peserta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
