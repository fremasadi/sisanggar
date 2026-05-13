<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Tagihan SPP Saya</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihans as $tagihan)
                            <tr>
                                <td>{{ $tagihan->bulan_tagihan->translatedFormat('F Y') }}</td>
                                <td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($tagihan->status) }}</td>
                                <td>
                                    <a href="{{ route('peserta.spp.show', $tagihan) }}" class="btn btn-sm btn-danger">Lihat Tagihan</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada tagihan SPP.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tagihans->links() }}
        </div>
    </div>
</x-app-layout>
