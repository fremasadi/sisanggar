<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-fan"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SIM-STARKSI</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if(Auth::user()->role == 'admin')

        <hr class="sidebar-divider">

        {{-- Group: Manajemen Anggota --}}
        <div class="sidebar-heading">Anggota & Pelatih</div>

        <li class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Pengguna</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/pelatih*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pelatih.index') }}">
                <i class="fas fa-fw fa-user-tie"></i>
                <span>Pelatih</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        {{-- Group: Inventaris --}}
        <div class="sidebar-heading">Inventaris</div>

        <li class="nav-item {{ request()->is('admin/kostum*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.kostum.index') }}">
                <i class="fas fa-fw fa-tshirt"></i>
                <span>Kostum</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        {{-- Group: Operasional --}}
        <div class="sidebar-heading">Operasional</div>

        <li class="nav-item {{ request()->is('admin/booking*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.booking.index') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Booking</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/kelompok*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.kelompok.index') }}">
                <i class="fas fa-fw fa-layer-group"></i>
                <span>Kelompok</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/presensi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.presensi.index') }}">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Presensi</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/spp*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.spp.index') }}">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>SPP </span>
            </a>
        </li>

    @elseif(Auth::user()->role == 'pelatih')
        <hr class="sidebar-divider">

        <div class="sidebar-heading">Pelatih</div>

        <li class="nav-item {{ request()->is('pelatih/kelompok*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pelatih.kelompok.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Peserta Binaan</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('pelatih/presensi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pelatih.presensi.index') }}">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Presensi Peserta</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('pelatih/ujian*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pelatih.ujian.index') }}">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Nilai Ujian</span>
            </a>
        </li>

    @elseif(Auth::user()->role == 'peserta')
        <hr class="sidebar-divider">

        <div class="sidebar-heading">Peserta</div>

        <li class="nav-item {{ request()->is('peserta/spp*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('peserta.spp.index') }}">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Tagihan SPP</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('peserta/kelompok*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('peserta.kelompok.show') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Kelompok Saya</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('peserta/presensi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('peserta.presensi.index') }}">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Presensi Saya</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('peserta/ujian*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('peserta.ujian.index') }}">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Ujian Saya</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggle -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
