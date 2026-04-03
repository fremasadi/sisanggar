<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-0">Detail Booking</h1>
        <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- Info Booking --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="fas fa-file-alt me-1"></i> Informasi Booking
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Order ID</td>
                            <td><code>{{ $booking->order_id }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pengunjung</td>
                            <td>{{ $booking->pengunjung->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $booking->pengunjung->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tgl Booking</td>
                            <td>{{ $booking->tanggal_booking->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pengambilan</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal_pengambilan)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pengembalian</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal_pengembalian)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Biaya</td>
                            <td class="fw-bold">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
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
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Info Pembayaran --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="fas fa-money-bill-wave me-1"></i> Informasi Pembayaran
                </div>
                <div class="card-body">
                    @if($booking->pembayaran)
                        @php $p = $booking->pembayaran; @endphp
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="40%">Transaction ID</td>
                                <td><code>{{ $p->transaction_id ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Metode</td>
                                <td>{{ strtoupper($p->payment_type ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank / VA</td>
                                <td>
                                    {{ strtoupper($p->bank ?? '-') }}
                                    @if($p->va_number)
                                        — <code>{{ $p->va_number }}</code>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jumlah</td>
                                <td class="fw-bold">Rp {{ number_format($p->gross_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Waktu Transaksi</td>
                                <td>{{ $p->transaction_time ? $p->transaction_time->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Settlement</td>
                                <td>{{ $p->settlement_time ? $p->settlement_time->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    <span class="badge bg-{{ $p->getStatusBadgeClass() }}">
                                        {{ $p->getStatusLabel() }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted mb-0">Belum ada data pembayaran.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Detail Item Kostum --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="fas fa-tshirt me-1"></i> Item Kostum ({{ $booking->details->count() }} item)
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="text-black">
                    <tr>
                        <th>No</th>
                        <th>Nama Kostum</th>
                        <th>Ukuran</th>
                        <th>Qty</th>
                        <th>Harga Sewa</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($booking->details as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail->kostum->nama_kostum ?? '-' }}</td>
                            <td>{{ $detail->kostum->ukuran ?? '-' }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->harga_sewa, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="5" class="text-end">Total</td>
                        <td>Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="fas fa-edit me-1"></i> Update Status Booking
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.booking.update-status', $booking) }}" class="row g-2">
                @csrf
                @method('PATCH')
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        @foreach(['menunggu', 'dibayar', 'diambil', 'selesai', 'dibatalkan'] as $s)
                            <option value="{{ $s }}" {{ $booking->status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
