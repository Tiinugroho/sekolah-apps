@extends('admin.partials.master')
@section('title', 'Data Pengguna')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
            @if (in_array(Auth::user()->role, ['super_admin', 'tata_usaha']))
                <a href="{{ route(Auth::user()->role == 'super_admin' ? 'admin.users.create' : 'manajemen.users.create') }}"
                    class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna
                </a>
            @endif
        </div>
        {{-- Session Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP / NISN</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nip ?? ($user->nisn ?? '-') }}</td>
                                    <td><span
                                            class="badge badge-info">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $showRoute =
                                                Auth::user()->role == 'kepala_sekolah'
                                                    ? route('monitoring.users.show', $user)
                                                    : (Auth::user()->role == 'super_admin'
                                                        ? route('admin.users.show', $user)
                                                        : '#');
                                            $editRoute =
                                                Auth::user()->role == 'super_admin'
                                                    ? route('admin.users.edit', $user)
                                                    : route('manajemen.users.edit', $user);
                                            $deleteRoute =
                                                Auth::user()->role == 'super_admin'
                                                    ? route('admin.users.destroy', $user)
                                                    : route('manajemen.users.destroy', $user);
                                        @endphp
                                        @if ($showRoute != '#')
                                            <a href="{{ $showRoute }}" class="btn btn-sm btn-info"
                                                title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        @endif
                                        @if (in_array(Auth::user()->role, ['super_admin', 'tata_usaha']))
                                            <a href="{{ $editRoute }}" class="btn btn-sm btn-warning" title="Edit"><i
                                                    class="fas fa-edit"></i></a>
                                            @if (Auth::id() !== $user->id)
                                                <form action="{{ $deleteRoute }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin hapus?')" title="Hapus"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data kosong.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
