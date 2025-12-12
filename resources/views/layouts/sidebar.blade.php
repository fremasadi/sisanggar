<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

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

    <hr class="sidebar-divider">

    {{-- ✅ Hanya tampil jika role adalah admin --}}
@if(Auth::user()->role == 'admin')

    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <!-- Manajemen Pengguna -->
    <li class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-users-cog"></i>
            <span>Manajemen Pengguna</span>
        </a>
    </li>

    <!-- Manajemen Pelatih -->
    <li class="nav-item {{ request()->is('admin/pelatih*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.pelatih.index') }}">
            <i class="fas fa-user-tie"></i>
            <span>Manajemen Pelatih</span>
        </a>
    </li>

    <!-- Manajemen Kostum -->
    <li class="nav-item {{ request()->is('admin/kostum*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.kostum.index') }}">
            <i class="fas fa-tshirt"></i>
            <span>Manajemen Kostum</span>
        </a>
    </li>

    <!-- Jadwal Latihan -->
    <li class="nav-item {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-calendar"></i>
            <span>Jadwal Latihan</span>
        </a>
    </li>

    <!-- Keuangan -->
    <li class="nav-item {{ request()->is('admin/keuangan*') ? 'active' : '' }}">
        <a class="nav-link" href="#">
            <i class="fas fa-money-bill-wave"></i>
            <span>Keuangan</span>
        </a>
    </li>

@endif


    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End of Sidebar -->
