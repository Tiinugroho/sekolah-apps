@extends('admin.partials.master')
@section('title', 'Atur Struktur Penilaian')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Atur Struktur Penilaian</h1>
        <p class="mb-4">
            Kelas: <strong>{{ $jadwal->kelas->nama_kelas }}</strong> <br>
            Mata Pelajaran: <strong>{{ $jadwal->mataPelajaran->nama_mapel }}</strong>
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Lingkup Materi Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.penilaian.storeLingkup', $jadwal) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_lingkup">Nama Lingkup Materi</label>
                        <input type="text" class="form-control @error('nama_lingkup') is-invalid @enderror"
                            id="nama_lingkup" name="nama_lingkup" placeholder="Contoh: Lingkup Materi 1 - Aljabar" required>
                        @error('nama_lingkup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Lingkup Materi & Tujuan Pembelajaran</h6>
            </div>
            <div class="card-body">
                @forelse($jadwal->lingkupMateris as $lm)
                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="m-0">{{ $lm->nama_lingkup }}</h6>
                            <form action="{{ route('guru.penilaian.destroyLingkup', $lm) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus lingkup materi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        <div class="card-body">
                            <h6>Tujuan Pembelajaran (TP):</h6>
                            <ul class="list-group list-group-flush mb-3">
                                @forelse($lm->tujuanPembelajarans as $tp)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><strong>{{ $tp->kode_tp }}:</strong> {{ $tp->deskripsi }}</span>
                                        <form action="{{ route('guru.penilaian.destroyTp', $tp) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus TP ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger"><i
                                                    class="fas fa-times"></i></button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="list-group-item">Belum ada TP.</li>
                                @endforelse
                            </ul>

                            <h6>Tambah TP Baru:</h6>
                            <form action="{{ route('guru.penilaian.storeTp', $lm) }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <input type="text" name="kode_tp" class="form-control"
                                            placeholder="Kode (cth: TP1)" required>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="deskripsi" class="form-control"
                                            placeholder="Deskripsi Tujuan Pembelajaran">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success btn-block">Tambah TP</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Belum ada lingkup materi yang dibuat untuk jadwal ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
