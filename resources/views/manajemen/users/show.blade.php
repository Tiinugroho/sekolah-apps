@extends('manajemen.partials.master')
@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Pengguna: {{ $user->name }}</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
                <tr><th>Nama</th><td>{{ $user->name }}</td></tr>
                <tr><th>Role</th><td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td></tr>
                @if($user->nip)<tr><th>NIP</th><td>{{ $user->nip }}</td></tr>@endif
                @if($user->nisn)<tr><th>NISN</th><td>{{ $user->nisn }}</td></tr>@endif
                @if($user->kelas)<tr><th>Kelas</th><td>{{ $user->kelas->nama_kelas }}</td></tr>@endif
                <tr><th>Email</th><td>{{ $user->email ?? '-' }}</td></tr>
            </table>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection