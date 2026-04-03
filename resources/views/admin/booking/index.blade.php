<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Manajemen Booking</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.booking.index') }}" class="row g-2">

                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}" placeholder="Order ID / Nama Pengunjung">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu"    {{ request('status') == 'menunggu'    ? 'selected' : '' }}>Menunggu</option>
                        <option value="dibayar"     {{ request('status') == 'dibayar'     ? 'selected' : '' }}>Dibayar</option>
                        <option value="diambil"     {{ request('status') == 'diambil'     ? 'selected' : '' }}>Diambil</option>
                        <option value="selesai"     {{ request('status') == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan"  {{ request('status') == 'dibatalkan'  ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-danger w-100">Filter</button>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="text-black">
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Pengunjung</th>
                        <th>Tgl Booking</th>
                        <th>Pengambilan</th>
                        <th>Pengembalian</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $key => $booking)
                        <tr>
                            <td>{{ $bookings->firstItem() + $key }}</td>
                            <td><code>{{ $booking->order_id }}</code></td>
                            <td>{{ $booking->pengunjung->name ?? '-' }}</td>
                            <td>{{ $booking->tanggal_booking->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal_pengambilan)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal_pengembalian)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'menunggu'   => 'warning',
                                        'dibayar'    => 'info',
                                        'diambil'    => 'primary',
                                        'selesai'    => 'success',
                                        'dibatalkan' => 'danger',
                                    ];
                                    $badge = $badges[$booking->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.booking.show', $booking) }}"
                                   class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
