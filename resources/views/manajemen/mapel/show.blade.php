@extends('manajemen.partials.master')
@section('title', 'Detail Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Mata Pelajaran</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 25%;">Nama Mata Pelajaran</th>
                    <td>{{ $mapel->nama_mapel }}</td>
                </tr>
                <tr>
                    <th>Kode Mata Pelajaran</th>
                    <td>{{ $mapel->kode_mapel }}</td>
                </tr>
            </table>
            <a href="{{ route('monitoring.mapel.index') }}" class="btn btn-secondary">Kembali ke Daftar Mapel</a>
        </div>
    </div>
</div>
@endsection