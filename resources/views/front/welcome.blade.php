@extends('front.frontapp')

@section('title', 'Sanggar Tari Kembang Sore - Lestarikan Budaya Indonesia')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-red-600 via-red-700 to-red-800 text-white py-20 hero-pattern">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Lestarikan Budaya<br>
                        <span class="text-green-300">Tari Indonesia</span>
                    </h1>
                    <p class="text-xl mb-8 text-red-100">
                        Sanggar Tari Kembang Sore - Belajar tari tradisional dengan guru berpengalaman dan kostum berkualitas
                    </p>
                    <div class="flex gap-4">
                        <a href="#kostum" class="bg-white text-red-700 px-8 py-3 rounded-full font-semibold hover:bg-red-50 transition duration-300 shadow-lg">
                            Sewa Kostum
                        </a>
                        <a href="#tentang" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-red-700 transition duration-300">
                            Tentang Kami
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Sanggar Tari Kembang Sore" class="w-80 h-80 object-contain drop-shadow-2xl ">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Tentang Sanggar Kami</h2>
                <div class="w-24 h-1 bg-red-600 mx-auto"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg hover:shadow-xl transition duration-300">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Pembelajaran Berkualitas</h3>
                    <p class="text-gray-600">Pengajar berpengalaman dengan metode pembelajaran yang mudah dipahami</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-xl transition duration-300">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Kostum Lengkap</h3>
                    <p class="text-gray-600">Koleksi kostum tari tradisional berbagai daerah dengan kualitas terbaik</p>
                </div>
                <div class="text-center p-6 rounded-lg hover:shadow-xl transition duration-300">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Komunitas Solid</h3>
                    <p class="text-gray-600">Bergabung dengan komunitas pecinta tari tradisional Indonesia</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Galeri Section -->
    @if(isset($galeris) && $galeris->count() > 0)
        <section id="galeri" class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Galeri Kegiatan</h2>
                    <div class="w-24 h-1 bg-red-600 mx-auto mb-4"></div>
                    <p class="text-gray-600 max-w-2xl mx-auto">Dokumentasi kegiatan dan penampilan Sanggar Tari Kembang Sore</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($galeris as $galeri)
                        <div class="group relative overflow-hidden rounded-lg shadow-lg bg-gray-100 h-72">
                            <img src="{{ asset('galeri/' . $galeri->image) }}"
                                alt="{{ $galeri->judul ?: 'Galeri Sanggar Tari Kembang Sore' }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @if($galeri->judul)
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-5">
                                    <h3 class="text-white text-lg font-semibold">{{ $galeri->judul }}</h3>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Kostum Section -->
    <section id="kostum" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Koleksi Kostum Kami</h2>
                <div class="w-24 h-1 bg-red-600 mx-auto mb-4"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Sewa kostum tari tradisional berkualitas tinggi untuk berbagai acara dan keperluan</p>
            </div>

            @if(isset($kostums) && $kostums->count() > 0)
                <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($kostums as $kostum)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2">
                        <div class="h-48 overflow-hidden bg-gray-100 flex items-center justify-center">
                        @if($kostum->image)
                            <img src="{{ asset('kostum/' . $kostum->image) }}"
                                alt="{{ $kostum->nama_kostum }}"
                                class="h-full w-full object-cover">
                        @else
                            <img src="{{ asset('default/no-image.png') }}"
                                alt="No Image"
                                class="h-full w-full object-cover opacity-50">
                        @endif
                    </div>

                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ $kostum->nama_kostum }}</h3>
                            <div class="space-y-4 mb-2">
                                <p class="text-gray-600"><span class="font-semibold">Ukuran:</span> {{ $kostum->ukuran }}</p>
                                <p class="text-1xl font-bold text-red-600">Rp {{ number_format($kostum->harga_sewa, 0, ',', '.') }}/Hari</p>
                                <p class="text-sm text-gray-500">Stok: {{ $kostum->stok }} unit</p>
                            </div>
                            <span class="inline-block px-3 py-3 text-xs font-semibold rounded-full {{ $kostum->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($kostum->status) }}
                            </span>
                            @if($kostum->stok > 0 && $kostum->status == 'tersedia')

                                    <form class="add-to-cart-form" data-id="{{ $kostum->id }}">
                                    @csrf
                                    <input type="hidden" name="kostum_id" value="{{ $kostum->id }}">
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="button"
                                            class="addToCartBtn w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center gap-2">
                                        Tambah Keranjang
                                    </button>
                                </form>

                            @else
                                <button disabled
                                    class="w-full bg-gray-400 text-white py-2 rounded-lg mt-4 cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500">Tidak ada kostum tersedia</p>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-red-600 to-red-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Siap Bergabung dengan Kami?</h2>
            <p class="text-xl mb-8 text-red-100">Daftar sekarang dan dapatkan kelas !</p>
            
            <a href="https://api.whatsapp.com/send?phone=6285113660105&text=Hallo%20Saya%20ingin%20daftar%20kelas"
            target="_blank"
            class="bg-white text-red-700 px-10 py-4 rounded-full font-semibold hover:bg-red-50 transition duration-300 shadow-lg inline-block">
                Daftar Sekarang
            </a>
        </div>
    </section>
@endsection
