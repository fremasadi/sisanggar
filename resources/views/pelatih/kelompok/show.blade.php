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
</x-app-layout>
