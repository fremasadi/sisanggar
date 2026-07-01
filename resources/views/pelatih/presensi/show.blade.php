<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Detail Presensi</h1>
            <small class="text-muted">{{ $presensi->kelompok->nama_kelompok }} - {{ $presensi->tanggal_presensi->format('d/m/Y') }}</small>
        </div>
        <a href="{{ route('pelatih.presensi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">Informasi Pertemuan</div>
        <div class="card-body">
            <form action="{{ route('pelatih.presensi.update', $presensi) }}" method="POST" class="row g-3">
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
                    <button class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">Absensi Peserta</div>
        <div class="card-body">
            <form action="{{ route('pelatih.presensi-detail.update-bulk', $presensi) }}" method="POST">
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
                                    <td colspan="3" class="text-center text-muted">Belum ada data peserta pada presensi ini.</td>
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
    <div class="card shadow mt-4">
        <div class="card-header bg-success text-white">
            Rekap Absensi Bulan {{ \Carbon\Carbon::parse($presensiMonth)->translatedFormat('F Y') }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr class="text-center">
                            <th class="text-start">Peserta</th>
                            <th>Total Pertemuan</th>
                            <th class="text-success">Hadir</th>
                            <th class="text-primary">Izin</th>
                            <th class="text-warning">Sakit</th>
                            <th class="text-danger">Alpa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap as $r)
                            <tr class="text-center">
                                <td class="text-start">{{ $r->name }}</td>
                                <td>{{ $r->total_pertemuan }}</td>
                                <td>{{ $r->total_hadir }}</td>
                                <td>{{ $r->total_izin }}</td>
                                <td>{{ $r->total_sakit }}</td>
                                <td>{{ $r->total_alpa }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data rekap presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
