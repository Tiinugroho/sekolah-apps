@extends('manajemen.partials.master')
@section('title', 'Tambah Kelas Baru')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Kelas Baru</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('manajemen.kelas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" required placeholder="Contoh: X 1 atau XII IPA 2">
                    @error('nama_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="walikelas_id">Wali Kelas</label>
                    <select class="form-control" id="walikelas_id" name="walikelas_id">
                        <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" @selected(old('walikelas_id') == $guru->id)>{{ $guru->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('manajemen.kelas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection