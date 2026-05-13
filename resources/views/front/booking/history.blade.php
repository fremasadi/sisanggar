@extends('front.frontapp')

@section('title', 'Cek Booking Tamu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Cek Riwayat Booking</h1>
            <p class="text-gray-600 mb-6">Masukkan nama dan nomor HP yang sama seperti saat booking.</p>

            <form action="{{ route('guest-booking.history.search') }}" method="POST" class="grid md:grid-cols-3 gap-4">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <button class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-3 rounded-lg">
                        Cari Booking
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 border border-green-200 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($searched)
            <div class="space-y-4">
                @forelse($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex flex-wrap justify-between gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Order ID</p>
                                <p class="font-mono font-bold text-red-600">{{ $booking->order_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status Booking</p>
                                <p class="font-semibold">{{ ucfirst($booking->status) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status Verifikasi</p>
                                <p class="font-semibold">{{ ucfirst($booking->verification_status ?? 'pending') }}</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-4">
                            <div class="bg-red-50 rounded-xl p-4">
                                <p class="font-semibold text-gray-800 mb-2">Periode Sewa</p>
                                <p class="text-sm text-gray-700">Pengambilan: {{ $booking->tanggal_pengambilan->format('d M Y') }}</p>
                                <p class="text-sm text-gray-700">Pengembalian: {{ $booking->tanggal_pengembalian->format('d M Y') }}</p>
                                <p class="text-sm font-semibold text-red-600 mt-2">Total: Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="font-semibold text-gray-800 mb-2">Catatan Admin</p>
                                <p class="text-sm text-gray-700">{{ $booking->verification_notes ?: 'Belum ada catatan verifikasi.' }}</p>
                                @if($booking->no_hp_pemesan_normalized)
                                    <a href="https://wa.me/{{ $booking->no_hp_pemesan_normalized }}" target="_blank" class="inline-block mt-3 text-sm text-green-600 font-semibold">
                                        Hubungi via WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800 mb-3">Item Kostum</p>
                            <div class="space-y-2">
                                @foreach($booking->details as $detail)
                                    <div class="flex justify-between text-sm border-b pb-2">
                                        <span>{{ $detail->kostum->nama_kostum ?? '-' }} ({{ $detail->kostum->ukuran ?? '-' }}) x {{ $detail->quantity }}</span>
                                        <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-10 text-center text-gray-500">
                        Booking dengan nama dan nomor HP tersebut belum ditemukan.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection
