@extends('manajemen.partials.master')
@section('title', 'Dashboard Wali Kelas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Wali Kelas</h1>

    @if ($kelas)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Kelas Perwalian: {{ $kelas->nama_kelas }}
                </h6>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Daftar Siswa</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas->students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->nisn ?? '-' }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>
                                        {{-- PERBAIKAN: Tambahkan link ke rapor siswa --}}
                                        <a href="{{ route('guru.walas.raport.show', $student) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye fa-sm"></i> Lihat Rapor
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada siswa di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Anda saat ini tidak ditugaskan sebagai Wali Kelas.
        </div>
    @endif
</div>
@endsection