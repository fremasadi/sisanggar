@extends('front.frontapp')

@section('title', 'Pembayaran - Sanggar Tari Kembang Sore')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-8">
            @if($pembayaran->isSuccess())
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Berhasil! 🎉</h1>
                <p class="text-gray-600">Terima kasih, pembayaran Anda telah dikonfirmasi</p>
                <p class="text-sm text-green-600 mt-2">Booking Anda sekarang aktif!</p>
            @elseif($pembayaran->isFailed())
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Gagal</h1>
                <p class="text-gray-600">{{ $pembayaran->getStatusLabel() }}</p>
            @else
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-yellow-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Pembayaran Sewa Kostum</h1>
                <p class="text-gray-600">Silakan selesaikan pembayaran untuk melanjutkan booking</p>
            @endif

            <span class="inline-block mt-4 px-6 py-2 rounded-full text-sm font-semibold bg-{{ $pembayaran->getStatusBadgeClass() }}-100 text-{{ $pembayaran->getStatusBadgeClass() }}-800">
                {{ $pembayaran->getStatusLabel() }}
            </span>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-2 border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Transaction Details -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Detail Transaksi
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Order ID</span>
                            <span class="font-mono font-bold text-red-600">{{ $pembayaran->order_id }}</span>
                        </div>
                        
                        @if($pembayaran->transaction_id)
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Transaction ID</span>
                            <span class="font-mono text-sm">{{ $pembayaran->transaction_id }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Tanggal Pengambilan</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->tanggal_pengambilan)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Tanggal Pengembalian</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->tanggal_pengembalian)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Durasi</span>
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($booking->tanggal_pengambilan)->diffInDays($booking->tanggal_pengembalian) }} hari
                            </span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Metode Pembayaran</span>
                            <span class="font-semibold">
                                @if($pembayaran->payment_type)
                                    @if($pembayaran->payment_type === 'bank_transfer' && $pembayaran->bank)
                                        {{ strtoupper($pembayaran->bank) }} Virtual Account
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $pembayaran->payment_type)) }}
                                    @endif
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        @if($pembayaran->va_number)
                        <div class="bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Virtual Account</span>
                                <div class="text-right">
                                    <div class="font-mono font-bold text-lg text-blue-600">{{ $pembayaran->va_number }}</div>
                                    <button onclick="copyVA()" class="text-xs text-blue-500 hover:text-blue-700 mt-1">
                                        📋 Salin Nomor
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Tanggal Booking</span>
                            <span class="font-semibold">{{ $booking->created_at->format('d M Y H:i') }}</span>
                        </div>

                        @if($pembayaran->settlement_time)
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600 font-medium">Tanggal Pembayaran</span>
                            <span class="font-semibold text-green-600">{{ $pembayaran->settlement_time->format('d M Y H:i') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-lg font-bold text-gray-800">Total Pembayaran</span>
                            <span class="text-3xl font-bold text-red-600">
                                Rp {{ number_format($pembayaran->gross_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Costume List -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        Daftar Kostum
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($booking->details as $detail)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border border-red-200">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800">{{ $detail->kostum->nama_kostum }}</p>
                                    <p class="text-sm text-gray-600">Ukuran: {{ $detail->kostum->ukuran }} | Jumlah: {{ $detail->quantity }}</p>
                                </div>
                                <span class="font-bold text-red-600">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Action -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    @if($pembayaran->isPending())
                        <button id="pay-button" 
                                class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-4 rounded-lg hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg text-lg mb-3">
                            💳 Bayar Sekarang
                        </button>
                        
                        <button onclick="checkPaymentStatus()" 
                                id="checkStatusBtn"
                                class="w-full bg-yellow-500 text-white py-3 rounded-lg hover:bg-yellow-600 transition font-bold">
                            🔄 Cek Status Pembayaran
                        </button>

                        <p class="text-center text-gray-500 text-xs mt-3">
                            💡 Status akan diperbarui otomatis setiap 30 detik
                        </p>
                    @elseif($pembayaran->isSuccess())
                        <div class="bg-green-50 border-2 border-green-300 rounded-lg p-6 mb-4">
                            <div class="flex items-center justify-center gap-3 mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-lg font-bold text-green-800">Pembayaran Telah Dikonfirmasi</p>
                            </div>
                            <p class="text-sm text-green-700 text-center mb-4">
                                Bukti pembayaran telah dikirim ke email Anda. Silakan ambil kostum sesuai jadwal yang telah ditentukan.
                            </p>
                        </div>
                    @endif

                    <a href="{{ route('home') }}" 
                       class="block w-full text-center py-3 {{ $pembayaran->isSuccess() ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white hover:from-red-700 hover:to-orange-700 font-bold rounded-lg' : 'text-red-600 font-semibold hover:text-red-800' }} transition">
                        🏠 {{ $pembayaran->isSuccess() ? 'Kembali ke Beranda' : 'Kembali ke Beranda' }}
                    </a>
                </div>

                <!-- Warning Alert -->
                @if($pembayaran->isPending())
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Perhatian:</strong> Selesaikan pembayaran dalam 24 jam. Booking akan otomatis dibatalkan jika tidak dibayar.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Penting</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex gap-3">
                            <div class="text-3xl flex-shrink-0">🔒</div>
                            <div>
                                <p class="font-bold mb-1 text-gray-800">Pembayaran Aman</p>
                                <p class="text-gray-600">Transaksi dilindungi Midtrans Payment Gateway</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="text-3xl flex-shrink-0">⚡</div>
                            <div>
                                <p class="font-bold mb-1 text-gray-800">Konfirmasi Otomatis</p>
                                <p class="text-gray-600">Pembayaran dikonfirmasi dalam hitungan menit</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="text-3xl flex-shrink-0">💳</div>
                            <div>
                                <p class="font-bold mb-1 text-gray-800">Berbagai Metode</p>
                                <p class="text-gray-600">Bank Transfer, GoPay, ShopeePay, QRIS</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="text-3xl flex-shrink-0">🎭</div>
                            <div>
                                <p class="font-bold mb-1 text-gray-800">Ambil Kostum</p>
                                <p class="text-gray-600">Kostum siap diambil sesuai tanggal booking</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800 font-semibold mb-2">💡 Tips:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Selesaikan pembayaran dalam 24 jam</li>
                            <li>• Simpan bukti pembayaran Anda</li>
                            <li>• Ambil kostum sesuai jadwal</li>
                            <li>• Kembalikan dalam kondisi baik</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const snapToken = "{{ $pembayaran->payment_url }}";
    const payButton = document.getElementById('pay-button');
    
    @if($pembayaran->isPending())
        if (payButton) {
            payButton.addEventListener('click', function () {
                if (!snapToken || snapToken === '') {
                    alert('Error: Token pembayaran tidak ditemukan.');
                    return;
                }
                
                payButton.disabled = true;
                payButton.innerHTML = '⏳ Memproses...';
                
                try {
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            showNotification('Pembayaran berhasil!', 'success');
                            checkPaymentStatus();
                        },
                        onPending: function(result) {
                            showNotification('Pembayaran tertunda. Silakan selesaikan pembayaran.', 'info');
                            checkPaymentStatus();
                        },
                        onError: function(result) {
                            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                            checkPaymentStatus();
                        }
                    });
                } catch (error) {
                    showNotification('Error: ' + error.message, 'error');
                    payButton.disabled = false;
                    payButton.innerHTML = '💳 Bayar Sekarang';
                }
            });
        }

        function checkPaymentStatus() {
            fetch("{{ route('payment.check-status', $booking->id) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.is_paid) {
                        showNotification('Pembayaran berhasil dikonfirmasi! 🎉', 'success');
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        setInterval(checkPaymentStatus, 30000);
    @endif
});

function copyVA() {
    const vaNumber = '{{ $pembayaran->va_number }}';
    navigator.clipboard.writeText(vaNumber).then(() => {
        showNotification('Nomor VA berhasil disalin!', 'success');
    });
}

window.checkPaymentStatus = function() {
    const button = document.getElementById('checkStatusBtn');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '⏳ Mengecek...';

    fetch("{{ route('payment.check-status', $booking->id) }}")
        .then(response => response.json())
        .then(data => {
            button.disabled = false;
            button.innerHTML = originalText;
            
            if (data.success) {
                showNotification(data.message, data.is_paid ? 'success' : 'info');
                if (data.is_paid) {
                    setTimeout(() => location.reload(), 1500);
                } else {
                    setTimeout(() => location.reload(), 2000);
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('Gagal mengecek status', 'error');
        });
}

function showNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${colors[type]} transform transition-all duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>
@endpush

@endsection