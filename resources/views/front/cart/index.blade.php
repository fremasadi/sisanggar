@extends('front.frontapp')

@section('title', 'Keranjang Belanja - Sanggar Tari Kembang Sore')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="grid lg:grid-cols-3 gap-6">
                
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Kostum</th>
                                        <th class="px-6 py-4 text-left">Ukuran</th>
                                        <th class="px-6 py-4 text-right">Harga/Hari</th>
                                        <th class="px-6 py-4 text-center">Jumlah</th>
                                        <th class="px-6 py-4 text-right">Subtotal</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($cart as $id => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-800">{{ $item['nama_kostum'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $item['ukuran'] }}</td>
                                        <td class="px-6 py-4 text-right text-gray-800">
                                            Rp {{ number_format($item['harga_sewa'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       max="{{ $item['stok'] }}"
                                                       class="w-20 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-red-500"
                                                       onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold text-gray-800" id="subtotal-{{ $id }}">
                                            Rp {{ number_format($item['harga_sewa'] * $item['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 font-semibold transition"
                                                        onclick="return confirm('Hapus item ini?')">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Rental Period Form -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Periode Sewa
                        </h3>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Tanggal Pengambilan *
                                </label>
                                <input type="date" 
                                       id="tanggal_pengambilan"
                                       name="tanggal_pengambilan"
                                       value="{{ old('tanggal_pengambilan', $tanggal_pengambilan ?? '') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Tanggal Pengembalian *
                                </label>
                                <input type="date" 
                                       id="tanggal_pengembalian"
                                       name="tanggal_pengembalian"
                                       value="{{ old('tanggal_pengembalian', $tanggal_pengembalian ?? '') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                       required>
                            </div>
                        </div>

                        @isset($durasi)
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-semibold">Durasi Sewa:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $durasi }} Hari</span>
                            </div>
                        </div>
                        @endisset

                        <!--<button onclick="calculateTotal()" 
                                class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                            🧮 Hitung Total
                        </button> -->
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-gray-700">
                                <span>Total Item</span>
                                <span class="font-semibold">{{ array_sum(array_column($cart, 'quantity')) }} kostum</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Harga per Hari</span>
                                <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @isset($durasi)
                            <div class="flex justify-between text-gray-700">
                                <span>Durasi</span>
                                <span class="font-semibold">{{ $durasi }} hari</span>
                            </div>
                            @endisset
                        </div>

                        <hr class="my-4">

                        @isset($total_biaya)
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-4 mb-6">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                <p class="text-3xl font-bold text-red-600">
                                    Rp {{ number_format($total_biaya, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $subtotal }} × {{ $durasi }} hari</p>
                            </div>
                        </div>

                        @auth
                            <form action="{{ route('payment.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tanggal_pengambilan" value="{{ $tanggal_pengambilan }}">
                                <input type="hidden" name="tanggal_pengembalian" value="{{ $tanggal_pengembalian }}">
                                <input type="hidden" name="total_biaya" value="{{ $total_biaya }}">
                                
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-4 rounded-lg hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg mb-3">
                                    💳 Lanjutkan Pembayaran
                                </button>
                            </form>
                        @else
                            <form action="{{ route('guest-booking.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="tanggal_pengambilan" value="{{ $tanggal_pengambilan }}">
                                <input type="hidden" name="tanggal_pengembalian" value="{{ $tanggal_pengembalian }}">
                                <input type="hidden" name="total_biaya" value="{{ $total_biaya }}">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>
                                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                    <p class="text-xs text-gray-500 mt-1">Admin akan menghubungi nomor ini untuk verifikasi booking.</p>
                                </div>

                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-4 rounded-lg hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg mb-3">
                                    📝 Kirim Booking Tamu
                                </button>
                            </form>
                        @endauth
                        @else
                        <div class="text-center py-4 text-gray-500 text-sm">
                            Silakan pilih tanggal pengambilan dan pengembalian terlebih dahulu
                        </div>
                        @endisset

                        <div class="flex gap-2">
                            <a href="{{ route('home') }}" 
                               class="flex-1 text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                Lanjut Belanja
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
                                        onclick="return confirm('Kosongkan keranjang?')">
                                    Kosongkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-500 mb-6">Belum ada kostum yang ditambahkan ke keranjang</p>
                <a href="{{ route('home') }}" 
                   class="inline-block px-8 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 font-semibold">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function calculateTotal() {
    const tanggalPengambilan = document.getElementById('tanggal_pengambilan').value;
    const tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;
    
    if (!tanggalPengambilan || !tanggalPengembalian) {
        return; // Hapus alert agar tidak mengganggu, cukup hentikan fungsi jika ada yang kosong
    }
    
    // Validasi tanggal
    const dateAmbil = new Date(tanggalPengambilan);
    const dateKembali = new Date(tanggalPengembalian);
    
    if (dateKembali <= dateAmbil) {
        return; // Hentikan jika tanggal kembali salah
    }
    
    // Submit form secara otomatis
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("cart.calculate") }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    
    const ambilInput = document.createElement('input');
    ambilInput.type = 'hidden';
    ambilInput.name = 'tanggal_pengambilan';
    ambilInput.value = tanggalPengambilan;
    
    const kembaliInput = document.createElement('input');
    kembaliInput.type = 'hidden';
    kembaliInput.name = 'tanggal_pengembalian';
    kembaliInput.value = tanggalPengembalian;
    
    form.appendChild(csrfInput);
    form.appendChild(ambilInput);
    form.appendChild(kembaliInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Fungsi untuk mengecek dan trigger hitung otomatis
function checkAndCalculate() {
    const ambil = document.getElementById('tanggal_pengambilan').value;
    const kembali = document.getElementById('tanggal_pengembalian').value;

    if (ambil && kembali) {
        const dateAmbil = new Date(ambil);
        const dateKembali = new Date(kembali);

        if (dateKembali > dateAmbil) {
            calculateTotal();
        }
    }
}

// Auto set minimum date for return date when pickup date changes
document.getElementById('tanggal_pengambilan')?.addEventListener('change', function() {
    const pickupDate = new Date(this.value);
    pickupDate.setDate(pickupDate.getDate() + 1);
    
    const returnDateInput = document.getElementById('tanggal_pengembalian');
    const minReturnDate = pickupDate.toISOString().split('T')[0];
    
    returnDateInput.min = minReturnDate;
    
    // Reset return date if it's before new minimum
    if (returnDateInput.value && returnDateInput.value < minReturnDate) {
        returnDateInput.value = '';
    } else {
        // Trigger perhitungan jika tanggal kembalinya sudah valid
        checkAndCalculate();
    }
});

// Trigger otomatis saat tanggal pengembalian diubah
document.getElementById('tanggal_pengembalian')?.addEventListener('change', function() {
    checkAndCalculate();
});
</script>
@endpush

@endsection
