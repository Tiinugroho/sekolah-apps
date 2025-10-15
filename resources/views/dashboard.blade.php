@extends('manajemen.partials.master') {{-- Sesuaikan dengan nama layout utama Anda --}}

{{-- Judul halaman dinamis berdasarkan peran --}}
@section('title')
    @if(Auth::user()->role == 'super_admin')
        Dashboard Super Admin
    @elseif(in_array(Auth::user()->role, ['kepala_sekolah', 'tata_usaha']))
        Dashboard Monitoring
    @elseif(Auth::user()->role == 'guru')
        Dashboard Guru
    @elseif(Auth::user()->role == 'murid')
        Dashboard Siswa
    @endif
@endsection

@section('content')
<div class="container-fluid">

    @if(in_array(Auth::user()->role, ['super_admin', 'kepala_sekolah', 'tata_usaha']))
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Monitoring Sekolah</h1>
        </div>

        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Guru</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahGuru ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Siswa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahSiswa ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahKelas ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-school fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jumlah Staf</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahStaf ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Selamat Datang di Sistem Penilaian Siswa</h6>
                    </div>
                    <div class="card-body">
                        <p>Anda login sebagai <strong>{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</strong>. Anda dapat memonitor data-data penting sekolah melalui menu di samping.</p>
                        @if (Auth::user()->role == 'super_admin')
                            <p>Anda memiliki akses penuh untuk mengelola semua data master, termasuk pengguna, kelas, dan jadwal.</p>
                            <a href="{{ route('manajemen.users.index') }}" class="btn btn-primary">Kelola Pengguna &rarr;</a>
                        @else
                            <p>Anda memiliki akses untuk melihat (read-only) data-data sekolah.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(Auth::user()->role == 'guru')
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Guru</h1>
        </div>
        <div class="alert alert-success">
            Selamat Datang, <strong>{{ Auth::user()->name }}</strong>!
        </div>

        <div class="row">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Kelas Diajar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahKelasDiajar ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($kelasWali)
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Anda Wali Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelasWali->nama_kelas }} ({{ $kelasWali->students_count }} Siswa)</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Akses Cepat</h6>
            </div>
            <div class="card-body">
                <p>Gunakan menu di samping atau tombol di bawah untuk memulai pekerjaan Anda.</p>
                <a href="{{ route('guru.nilai.pilihJadwal') }}" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Mulai Input Nilai</a>
                @if ($kelasWali)
                <a href="{{ route('guru.walas.index') }}" class="btn btn-info"><i class="fas fa-chalkboard-teacher mr-2"></i>Buka Menu Wali Kelas</a>
                @endif
            </div>
        </div>
    @endif


    @if(Auth::user()->role == 'murid')
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Siswa</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Selamat Datang, {{ $user->name }}!
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Lengkap:</strong><br>{{ $user->name }}</p>
                        <p><strong>NISN:</strong><br>{{ $user->nisn ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Kelas:</strong><br>{{ $user->kelas->nama_kelas ?? 'Belum terdaftar di kelas' }}</p>
                        <p><strong>Wali Kelas:</strong><br>{{ $user->kelas->waliKelas->name ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>
                <hr>
                <p>Silakan gunakan tombol di bawah ini untuk melihat laporan hasil belajar (rapor) Anda.</p>
                <a href="{{ route('siswa.raport.show') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-book-open mr-2"></i> Lihat Rapor Saya
                </a>
            </div>
        </div>
    @endif

</div>
@endsection