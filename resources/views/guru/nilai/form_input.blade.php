@extends('admin.partials.master')
@section('title', 'Input Nilai')

@push('styles')
<style>
    /* Membuat input lebih kecil agar pas di tabel */
    .table td .form-control {
        width: 65px;
        height: 30px;
        padding: 0.25rem 0.5rem;
        text-align: center;
    }
    .table th {
        vertical-align: middle !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Form Input Nilai</h1>
    <p class="mb-4">
        Kelas: <strong>{{ $jadwal->kelas->nama_kelas }}</strong> <br>
        Mata Pelajaran: <strong>{{ $jadwal->mataPelajaran->nama_mapel }}</strong> <br>
        Tahun Ajaran: <strong>{{ $jadwal->tahun_ajaran }} / Semester {{ $jadwal->semester }}</strong>
    </p>

    <form action="{{ route('guru.nilai.simpan', $jadwal) }}" method="POST">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai Sumatif</h6>
            </div>
            <div class="card-body">
                @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 0.8rem;">
                        <thead class="thead-light">
                            {{-- Header Tabel Sesuai Excel --}}
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center" style="min-width: 200px;">Nama Siswa</th>
                                @if($jadwal->lingkupMateris->isNotEmpty())
                                    @foreach($jadwal->lingkupMateris as $lm)
                                        <th colspan="{{ $lm->tujuanPembelajarans->count() > 0 ? $lm->tujuanPembelajarans->count() : 1 }}" class="text-center">{{ $lm->nama_lingkup }}</th>
                                    @endforeach
                                @else
                                    <th class="text-center">Nilai Harian</th>
                                @endif
                                <th colspan="2" class="text-center align-middle">Rekap Sumatif</th>
                            </tr>
                            <tr>
                                @if($jadwal->lingkupMateris->isNotEmpty())
                                    @foreach($jadwal->lingkupMateris as $lm)
                                        @forelse($lm->tujuanPembelajarans as $tp)
                                            <th class="text-center" title="{{ $tp->deskripsi }}">{{ $tp->kode_tp }}</th>
                                        @empty
                                            <th class="text-center">-</th>
                                        @endforelse
                                    @endforeach
                                @else
                                    <th class="text-center">-</th>
                                @endif
                                <th class="text-center" style="min-width: 65px;">MID</th>
                                <th class="text-center" style="min-width: 65px;">UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal->kelas->students as $student)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    {{-- Kolom Input Nilai Harian (TP) --}}
                                    @if($jadwal->lingkupMateris->isNotEmpty())
                                        @foreach($jadwal->lingkupMateris as $lm)
                                            @forelse($lm->tujuanPembelajarans as $tp)
                                                <td>
                                                    <input type="number" name="nilai_tp[{{ $student->id }}][{{ $tp->id }}]" class="form-control" min="0" max="100">
                                                </td>
                                            @empty
                                                <td></td>
                                            @endforelse
                                        @endforeach
                                    @else
                                        <td></td>
                                    @endif

                                    {{-- Kolom Input Nilai Sumatif (MID & UAS) --}}
                                    <td><input type="number" name="nilai_sumatif[{{ $student->id }}][mid]" class="form-control" min="0" max="100"></td>
                                    <td><input type="number" name="nilai_sumatif[{{ $student->id }}][uas]" class="form-control" min="0" max="100"></td>
                                </tr>
                            @empty
                                <tr><td colspan="100%" class="text-center">Tidak ada siswa di kelas ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan Semua Nilai</button>
                 <a href="{{ route('guru.nilai.pilihJadwal') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </form>
</div>
@endsection