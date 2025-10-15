@extends('admin.partials.master')
@section('title', 'Pilih Jadwal Mengajar')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Pilih Jadwal Mengajar</h1>
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Jadwal Anda</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tahun Ajaran/Semester</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->tahun_ajaran }} / Semester {{ $jadwal->semester }}</td>
                                    <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                    <td>
                                        {{-- PERBAIKAN: TAMBAHKAN TOMBOL BARU --}}
                                        <a href="{{ route('guru.penilaian.setup', $jadwal) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-cog mr-1"></i> Atur Penilaian
                                        </a>
                                        <a href="{{ route('guru.nilai.form', $jadwal) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit mr-1"></i> Input Nilai
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Anda belum memiliki jadwal mengajar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
