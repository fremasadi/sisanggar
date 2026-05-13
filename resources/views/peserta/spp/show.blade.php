<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-0">Detail Tagihan SPP</h1>
        <a href="{{ route('peserta.spp.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white">Informasi Tagihan</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td width="40%">Bulan</td>
                            <td>{{ $tagihan->bulan_tagihan->translatedFormat('F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td class="fw-bold">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ ucfirst($tagihan->status) }}</td>
                        </tr>
                        <tr>
                            <td>Order ID</td>
                            <td><code>{{ $tagihan->order_id ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td>Metode</td>
                            <td>{{ $tagihan->payment_type ? ucfirst(str_replace('_', ' ', $tagihan->payment_type)) : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Bank / VA</td>
                            <td>
                                {{ strtoupper($tagihan->bank ?? '-') }}
                                @if($tagihan->va_number)
                                    - <code>{{ $tagihan->va_number }}</code>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">Aksi Pembayaran</div>
                <div class="card-body">
                    @if($tagihan->status === 'dibayar')
                        <div class="alert alert-success mb-0">Tagihan ini sudah lunas.</div>
                    @else
                        <form action="{{ route('peserta.spp.pay', $tagihan) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100 mb-3">Buat / Perbarui Token Pembayaran</button>
                        </form>

                        @if($tagihan->payment_token)
                            <button id="pay-button" class="btn btn-success w-100">Bayar Sekarang</button>
                        @else
                            <p class="text-muted mb-0">Buat token pembayaran terlebih dahulu.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($tagihan->payment_token && $tagihan->status !== 'dibayar')
        @push('scripts')
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                document.getElementById('pay-button')?.addEventListener('click', function () {
                    window.snap.pay(@json($tagihan->payment_token), {
                        onSuccess: function () { window.location.reload(); },
                        onPending: function () { window.location.reload(); },
                        onError: function () { window.location.reload(); },
                        onClose: function () {}
                    });
                });
            </script>
        @endpush
    @endif
</x-app-layout>
