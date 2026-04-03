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

        <li class="nav-item disabled">
            <a class="nav-link text-muted" style="pointer-events: none; opacity: 0.5;" href="#">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Jadwal Latihan</span>
            </a>
        </li>

        <li class="nav-item disabled">
            <a class="nav-link text-muted" style="pointer-events: none; opacity: 0.5;" href="#">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Keuangan</span>
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
