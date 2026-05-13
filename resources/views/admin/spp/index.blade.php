<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Manajemen SPP Peserta</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.spp.generate') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Bulan Tagihan</label>
                    <input type="month" name="bulan_tagihan" value="{{ $selectedMonth }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Peserta aktif</p>
                    <p class="h5 mb-0">{{ $pesertaCount }} peserta</p>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger w-100">Generate Tagihan Rp 75.000</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">Daftar Tagihan {{ $monthDate->translatedFormat('F Y') }}</div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="month" name="bulan_tagihan" value="{{ $selectedMonth }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari peserta">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach(['menunggu', 'dibayar', 'gagal', 'dibatalkan'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Order ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihans as $tagihan)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $tagihan->peserta->name }}</div>
                                    <small class="text-muted">{{ $tagihan->peserta->email }}</small>
                                </td>
                                <td>{{ $tagihan->bulan_tagihan->translatedFormat('F Y') }}</td>
                                <td>Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($tagihan->status) }}</td>
                                <td><code>{{ $tagihan->order_id ?? '-' }}</code></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada tagihan pada bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tagihans->links() }}
        </div>
    </div>
</x-app-layout>
