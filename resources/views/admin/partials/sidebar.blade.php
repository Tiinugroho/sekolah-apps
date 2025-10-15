<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SISPENSI</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- ======================================================== --}}
    {{-- MENU UNTUK TATA USAHA (STAFF) & SUPER ADMIN --}}
    {{-- ======================================================== --}}
    @if (in_array(Auth::user()->role, ['tata_usaha', 'super_admin']))
        <div class="sidebar-heading">
            Manajemen Sekolah
        </div>

        {{-- Super Admin memiliki rute 'admin.*', Tata Usaha memiliki 'manajemen.*'. Kita sesuaikan linknya. --}}
        @php
            $prefix = Auth::user()->role == 'super_admin' ? 'admin' : 'manajemen';
        @endphp

        <li class="nav-item {{ request()->routeIs($prefix . '.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($prefix . '.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs($prefix . '.mapel.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($prefix . '.mapel.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kelola Mapel</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs($prefix . '.kelas.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($prefix . '.kelas.index') }}">
                <i class="fas fa-fw fa-chalkboard"></i>
                <span>Kelola Kelas</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs($prefix . '.jadwal.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($prefix . '.jadwal.index') }}">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Kelola Jadwal</span>
            </a>
        </li>
    @endif

    {{-- ======================================================== --}}
    {{-- MENU KHUSUS KEPALA SEKOLAH (MONITORING) --}}
    {{-- ======================================================== --}}
    @if (Auth::user()->role == 'kepala_sekolah')
        <div class="sidebar-heading">
            Monitoring
        </div>
        <li class="nav-item {{ request()->routeIs('monitoring.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('monitoring.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Lihat Data Pengguna</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('monitoring.mapel.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('monitoring.mapel.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Lihat Data Mapel</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('monitoring.kelas.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('monitoring.kelas.index') }}">
                <i class="fas fa-fw fa-chalkboard"></i>
                <span>Lihat Data Kelas</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('monitoring.jadwal.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('monitoring.jadwal.index') }}">
                <i class="fas fa-fw fa-calendar-check"></i>
                <span>Lihat Data Jadwal</span>
            </a>
        </li>
    @endif

    {{-- ======================================================== --}}
    {{-- MENU KHUSUS GURU --}}
    {{-- ======================================================== --}}
    @if (Auth::user()->role == 'guru')
        <div class="sidebar-heading">
            Fungsional Guru
        </div>
        <li class="nav-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.nilai.pilihJadwal') }}">
                <i class="fas fa-fw fa-edit"></i>
                <span>Input Nilai</span>
            </a>
        </li>
        @if (Auth::user()->kelasWali)
            <li class="nav-item {{ request()->routeIs('guru.walas.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('guru.walas.index') }}">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Menu Wali Kelas</span>
                </a>
            </li>
        @endif
    @endif

    {{-- ======================================================== --}}
    {{-- MENU KHUSUS MURID --}}
    {{-- ======================================================== --}}
    @if (Auth::user()->role == 'murid')
        <div class="sidebar-heading">
            Menu Siswa
        </div>
        <li class="nav-item {{ request()->routeIs('siswa.raport.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('siswa.raport.show') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Lihat Rapor</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
