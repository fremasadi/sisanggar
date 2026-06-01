<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Ujian Saya</h1>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Ujian</th>
                        <th>Kelompok</th>
                        <th>Tanggal</th>
                        <th>Hasil</th>
                        <th>Nilai</th>
                        <th>Kelompok Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasils as $hasil)
                        <tr>
                            <td>{{ $hasil->ujianKelompok->nama_ujian }}</td>
                            <td>{{ $hasil->ujianKelompok->kelompok->nama_kelompok }}</td>
                            <td>{{ $hasil->ujianKelompok->tanggal_ujian->format('d/m/Y') }}</td>
                            <td>{{ $hasil->hasil_label }}</td>
                            <td>{{ $hasil->nilai ?? '-' }}</td>
                            <td>{{ $hasil->ujianKelompok->kelompokTujuan->nama_kelompok ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $hasils->links() }}
        </div>
    </div>
</x-app-layout>
