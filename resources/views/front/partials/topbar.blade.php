<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Sanggar Tari Kembang Sore</h1>
                    <p class="text-xs text-gray-600">Indonesia</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">Beranda</a>

                <a href="{{ route('home') }}#tentang" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">Tentang</a>

                <a href="{{ route('home') }}#kostum" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">Kostum</a>

                <a href="{{ route('home') }}#kontak" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">Kontak</a>

                
                @if (Route::has('login'))
        <!-- Cart Icon -->
        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center">
            <svg class="w-6 h-6 text-gray-700 hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            @php
                $cart = session()->get('cart', []);
                $cartCount = array_sum(array_column($cart, 'quantity'));
            @endphp
            @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $cartCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('guest-booking.history') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">
            Cek Booking
        </a>

    @auth
        <!-- User Dropdown -->
        <div class="relative inline-block text-left">
            <!-- Avatar -->
            @php
                $name = Auth::user()->name;
                $initials = collect(explode(' ', $name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->join('');
            @endphp

            <button id="userMenuBtn" 
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-red-600 text-white font-bold hover:bg-red-700 transition">
                {{ $initials }}
            </button>

            <!-- Dropdown -->
            <div id="userMenuDropdown"
                class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                
                @if(Auth::user()->role === 'peserta')
                    <a href="{{ route('peserta.spp.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Tagihan SPP
                    </a>
                    <a href="{{ route('peserta.kelompok.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Kelompok
                    </a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('payment.history') }}" 
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Riwayat
                    </a>
                @endif

                @if(Auth::user()->role === 'pengunjung')
                    <a href="{{ route('payment.history') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Riwayat Saya
                    </a>
                @endif

                <a href="{{ route('guest-booking.history') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    Cek Booking Tamu
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Script Dropdown -->
        <script>
            document.addEventListener('click', function(e) {
                const btn = document.getElementById('userMenuBtn');
                const dropdown = document.getElementById('userMenuDropdown');

                if (!btn || !dropdown) {
                    return;
                }

                if (btn.contains(e.target)) {
                    dropdown.classList.toggle('hidden');
                } else if (!dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>

    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium transition duration-300">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition duration-300 font-medium">
                            Daftar
                        </a>
                    @endif
    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-3">
                <a href="#" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Beranda</a>
                <a href="#tentang" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Tentang</a>
                <a href="#kostum" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Kostum</a>
                <a href="#kontak" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Kontak</a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Keranjang</a>
                <a href="{{ route('guest-booking.history') }}" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">Cek Booking</a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition duration-300 font-medium text-center">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium py-2 transition duration-300">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition duration-300 font-medium text-center">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Close mobile menu after clicking
                document.getElementById('mobile-menu').classList.add('hidden');
            }
        });
    });
</script>
