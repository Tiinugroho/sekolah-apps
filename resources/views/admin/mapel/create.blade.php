@extends('admin.partials.master')
@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Mata Pelajaran Baru</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('manajemen.mapel.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_mapel">Nama Mata Pelajaran</label>
                    <input type="text" class="form-control @error('nama_mapel') is-invalid @enderror" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}" required>
                    @error('nama_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="kode_mapel">Kode Mata Pelajaran</label>
                    <input type="text" class="form-control @error('kode_mapel') is-invalid @enderror" id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel') }}" required placeholder="Contoh: MTK, BIND, EKO">
                    @error('kode_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('manajemen.mapel.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection