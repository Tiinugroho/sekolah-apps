@extends('admin.partials.master')
@section('title', 'Detail Jadwal')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Jadwal Pelajaran</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
                <tr><th style="width: 25%;">Tahun Ajaran/Semester</th><td>{{ $jadwal->tahun_ajaran }} / Semester {{ $jadwal->semester }}</td></tr>
                <tr><th>Hari</th><td>{{ $jadwal->hari }}</td></tr>
                <tr><th>Jam Mengajar</th><td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td></tr>
                <tr><th>Kelas</th><td>{{ $jadwal->kelas->nama_kelas }}</td></tr>
                <tr><th>Mata Pelajaran</th><td>{{ $jadwal->mataPelajaran->nama_mapel }}</td></tr>
                <tr><th>Guru Pengampu</th><td>{{ $jadwal->teacher->name }}</td></tr>
                <tr><th>KKTP</th><td>{{ $jadwal->kktp }}</td></tr>
            </table>
            <a href="{{ route('monitoring.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection