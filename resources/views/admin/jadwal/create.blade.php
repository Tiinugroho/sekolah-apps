@extends('admin.partials.master')
@section('title', 'Buat Jadwal Baru')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Buat Jadwal Baru</h1>
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('manajemen.jadwal.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror" id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', date('Y').'/'.(date('Y')+1)) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                                <option value="1" @selected(old('semester') == 1)>1 (Ganjil)</option>
                                <option value="2" @selected(old('semester') == 2)>2 (Genap)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kelas_id">Kelas</label>
                    <select class="form-control" name="kelas_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k) <option value="{{ $k->id }}" @selected(old('kelas_id') == $k->id)>{{ $k->nama_kelas }}</option> @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="hari">Hari</label>
                            <select class="form-control" name="hari" required>
                                @foreach($days as $day) <option value="{{ $day }}" @selected(old('hari') == $day)>{{ $day }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="mata_pelajaran_id">Mata Pelajaran</label>
                    <select class="form-control" name="mata_pelajaran_id" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapels as $mapel) <option value="{{ $mapel->id }}" @selected(old('mata_pelajaran_id') == $mapel->id)>{{ $mapel->nama_mapel }}</option> @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="teacher_id">Guru Pengampu</label>
                    <select class="form-control" name="teacher_id" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $guru) <option value="{{ $guru->id }}" @selected(old('teacher_id') == $guru->id)>{{ $guru->name }}</option> @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="kktp">KKTP</label>
                    <input type="number" class="form-control" id="kktp" name="kktp" value="{{ old('kktp', 75) }}" required min="0" max="100">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('manajemen.jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection