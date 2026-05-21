<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">Sertifikat Saya</h1>
            <small class="text-muted">Daftar sertifikat yang sudah diupload oleh admin.</small>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Nama Sertifikat</th>
                            <th>Tanggal Terbit</th>
                            <th>File</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sertifikats as $sertifikat)
                            <tr>
                                <td>
                                    <strong>{{ $sertifikat->nama_sertifikat }}</strong>
                                    @if($sertifikat->catatan)
                                        <span class="d-block text-muted small">{{ $sertifikat->catatan }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($sertifikat->tanggal_terbit)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $sertifikat->file_name }}</td>
                                <td>
                                    <a href="{{ route('peserta.sertifikat.download', $sertifikat) }}" class="btn btn-sm btn-danger">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada sertifikat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $sertifikats->links() }}
        </div>
    </div>
</x-app-layout>
