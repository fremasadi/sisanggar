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
        <div class="card-header bg-danger text-white">Daftar Kehadiran Peserta</div>
        <div class="card-body">
            <form action="{{ route('admin.presensi-detail.update-bulk', $presensi) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Status Kehadiran</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensi->details as $index => $detail)
                                <tr>
                                    <td>
                                        {{ $detail->peserta->name ?? '-' }}
                                        <input type="hidden" name="details[{{ $index }}][id]" value="{{ $detail->id }}">
                                    </td>
                                    <td>
                                        <select name="details[{{ $index }}][status_kehadiran]" class="form-select form-select-sm">
                                            @foreach(['hadir', 'izin', 'sakit', 'alpa'] as $status)
                                                <option value="{{ $status }}" {{ $detail->status_kehadiran === $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="details[{{ $index }}][catatan]" value="{{ $detail->catatan }}" class="form-control form-control-sm">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada detail presensi peserta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($presensi->details->isNotEmpty())
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-warning">Simpan Semua Kehadiran</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
