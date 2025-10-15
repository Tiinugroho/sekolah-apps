@extends('admin.partials.master')
@section('title', 'Detail Kelas')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Detail Kelas: {{ $kela->nama_kelas }}</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header">Informasi Kelas</div>
                    <div class="card-body">
                        <p><strong>Nama Kelas:</strong><br>{{ $kela->nama_kelas }}</p>
                        <p><strong>Wali Kelas:</strong><br>{{ $kela->waliKelas->name ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header">Daftar Siswa</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kela->students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->nisn }}</td>
                                        <td>{{ $student->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('monitoring.kelas.index') }}" class="btn btn-secondary">Kembali ke Daftar Kelas</a>
    </div>
@endsection
