<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Title -->
    <h5 class="font-weight-bold text-danger mt-2">Sanggar Tari Kembang Sore</h5>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        @auth
            @php
                $unreadNotifications = Auth::user()->unreadNotifications()->latest()->take(5)->get();
            @endphp

            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    @if($unreadNotifications->count())
                        <span class="badge badge-danger badge-counter">{{ $unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">Notifikasi</h6>
                    @forelse($unreadNotifications as $notification)
                        <div class="dropdown-item d-flex align-items-center">
                            <div class="mr-3">
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-calendar-times text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                                <span class="font-weight-bold">{{ $notification->data['message'] ?? 'Ada notifikasi baru.' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="dropdown-item text-center small text-gray-500">Belum ada notifikasi baru.</div>
                    @endforelse
                    @if($unreadNotifications->count())
                        <form action="{{ route('notifications.read-all') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-center small text-gray-500">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
            </li>
        @endauth

        <!-- Divider -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- User Info -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                <i class="fas fa-user-circle fa-lg text-gray-600"></i>
            </a>

            <!-- Dropdown -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
