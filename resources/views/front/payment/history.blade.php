@extends('front.frontapp')

@section('title', 'Riwayat Booking - Sanggar Tari Kembang Sore')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 py-12">
    <div class="container mx-auto px-4">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Riwayat Booking</h1>
            <p class="text-gray-600">Daftar semua booking sewa kostum Anda</p>
        </div>

        @if($bookings->count() == 0)
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-32 h-32 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Booking</h3>
                <p class="text-gray-500 mb-6">Anda belum melakukan booking kostum</p>
                <a href="{{ route('home') }}" 
                   class="inline-block px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg">
                    Mulai Sewa Kostum
                </a>
            </div>
        @else
            <!-- Booking List -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    @php
                        $statusColors = [
                            'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'dibayar' => 'bg-green-100 text-green-800 border-green-300',
                            'diambil' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'selesai' => 'bg-gray-100 text-gray-800 border-gray-300',
                            'dibatalkan' => 'bg-red-100 text-red-800 border-red-300',
                        ];
                        
                        $statusLabels = [
                            'menunggu' => 'Menunggu Pembayaran',
                            'dibayar' => 'Sudah Dibayar',
                            'diambil' => 'Sedang Disewa',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ];
                        
                        $durasi = \Carbon\Carbon::parse($booking->tanggal_pengambilan)->diffInDays($booking->tanggal_pengembalian);
                    @endphp
                    
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-red-100 overflow-hidden hover:shadow-2xl transition">
                        
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b-2 border-red-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Order ID</p>
                                    <p class="font-mono font-bold text-lg text-red-700">
                                        {{ $booking->order_id }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 mb-1">Tanggal Booking</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ $booking->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="inline-block px-4 py-2 rounded-full font-bold text-sm border-2 
                                        {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                
                                <!-- Rental Period -->
                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                                    <h4 class="font-bold text-blue-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Periode Sewa
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Pengambilan:</span>
                                            <span class="font-bold">{{ \Carbon\Carbon::parse($booking->tanggal_pengambilan)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Pengembalian:</span>
                                            <span class="font-bold">{{ \Carbon\Carbon::parse($booking->tanggal_pengembalian)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex justify-between pt-2 border-t border-blue-300">
                                            <span class="text-gray-600">Durasi:</span>
                                            <span class="font-bold text-blue-700">{{ $durasi }} hari</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                                    <h4 class="font-bold text-green-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Info Pembayaran
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        @if($booking->pembayaran)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Status:</span>
                                                <span class="font-bold">{{ $booking->pembayaran->getStatusLabel() }}</span>
                                            </div>
                                            @if($booking->pembayaran->payment_type)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Metode:</span>
                                                    <span class="font-bold text-xs">
                                                        @if($booking->pembayaran->payment_type === 'bank_transfer' && $booking->pembayaran->bank)
                                                            {{ strtoupper($booking->pembayaran->bank) }} VA
                                                        @else
                                                            {{ ucfirst(str_replace('_', ' ', $booking->pembayaran->payment_type)) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-gray-500 text-center py-2">
                                                Data pembayaran tidak tersedia
                                            </div>
                                        @endif
                                        <div class="flex justify-between pt-2 border-t border-green-300">
                                            <span class="text-gray-600">Total:</span>
                                            <span class="font-bold text-xl text-green-700">
                                                Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Costume List -->
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    Kostum yang Disewa ({{ $booking->details->count() }} item)
                                </h4>
                                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($booking->details as $detail)
                                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border border-red-200">
                                            <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                                {{ substr($detail->kostum->nama_kostum, 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-gray-800 truncate">{{ $detail->kostum->nama_kostum }}</p>
                                                <p class="text-xs text-gray-600">{{ $detail->kostum->ukuran }} | Qty: {{ $detail->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3 pt-4 border-t-2 border-gray-200">
                                @if($booking->status === 'menunggu' && $booking->pembayaran)
                                    <a href="{{ route('payment.show', $booking->id) }}" 
                                       class="flex-1 min-w-[200px] text-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition font-bold shadow-lg">
                                        💳 Bayar Sekarang
                                    </a>
                                @elseif($booking->status === 'dibayar' || $booking->status === 'diambil')
                                    <a href="{{ route('payment.show', $booking->id) }}" 
                                       class="flex-1 min-w-[200px] text-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-bold shadow-lg">
                                        📄 Lihat Detail
                                    </a>
                                @else
                                    <a href="{{ route('payment.show', $booking->id) }}" 
                                       class="flex-1 min-w-[200px] text-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-bold shadow-lg">
                                        📄 Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>
</div>

@endsection