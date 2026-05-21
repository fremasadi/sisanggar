<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Presensi Saya</h1>
            <small class="text-muted">Daftar lengkap kehadiran latihan Anda.</small>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Kehadiran</label>
                    <select name="status_kehadiran" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['hadir', 'izin', 'sakit', 'alpa'] as $status)
                            <option value="{{ $status }}" {{ request('status_kehadiran') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('peserta.presensi.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelompok</th>
                            <th>Pertemuan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $detail)
                            <tr>
                                <td>{{ optional($detail->presensi?->tanggal_presensi)->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $detail->presensi->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $detail->presensi->judul_pertemuan ?: '-' }}</div>
                                    @if($detail->presensi->materi)
                                        <small class="text-muted">{{ $detail->presensi->materi }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'hadir' => 'success',
                                            'izin' => 'warning',
                                            'sakit' => 'info',
                                            'alpa' => 'danger',
                                        ][$detail->status_kehadiran] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($detail->status_kehadiran) }}</span>
                                </td>
                                <td>{{ $detail->catatan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $presensis->links() }}
        </div>
    </div>
</x-app-layout>
