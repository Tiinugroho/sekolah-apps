@extends('admin.partials.master')
@section('title', 'Kelola Data Jadwal')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Jadwal Pelajaran</h1>
        @if(in_array(Auth::user()->role, ['tata_usaha', 'super_admin']))
            <a href="{{ route('manajemen.jadwal.create') }}" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm"></i> Buat Jadwal Baru</a>
        @endif
    </div>
    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari & Jam</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td>{{ $loop->iteration + $jadwals->firstItem() - 1 }}</td>
                                <td>
                                    <strong>{{ $jadwal->hari }}</strong><br>
                                    <small>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</small>
                                </td>
                                <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                <td>{{ $jadwal->teacher->name }}</td>
                                <td>
                                    @if(Auth::user()->role == 'kepala_sekolah')
                                        <a href="{{ route('monitoring.jadwal.show', $jadwal) }}" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    @else
                                        <a href="{{ route('manajemen.jadwal.edit', $jadwal) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('manajemen.jadwal.destroy', $jadwal) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus jadwal ini?')" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Belum ada data jadwal.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection