@extends('manajemen.partials.master')
@section('title', 'Input Nilai')

@push('styles')
<style>
    .table-nilai { font-size: 0.8rem; }
    .table-nilai td .form-control { width: 65px; height: 30px; padding: .25rem .5rem; text-align: center; }
    .table-nilai th { vertical-align: middle !important; text-align: center; }
    .table-nilai thead th { white-space: nowrap; }
    .table-nilai .student-name { min-width: 200px; text-align: left !important; }
    .form-control:disabled { background-color: #e9ecef; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Form Input Nilai</h1>
            <p class="mb-0">Kelas: <strong>{{ $jadwal->kelas->nama_kelas }}</strong> | Mapel: <strong>{{ $jadwal->mataPelajaran->nama_mapel }}</strong></p>
        </div>
        <div>
            <a href="{{ route('guru.nilai.export', $jadwal) }}" class="btn btn-sm btn-success shadow-sm"><i class="fas fa-file-excel fa-sm"></i> Cetak ke Excel</a>
        </div>
    </div>

    <form action="{{ route('guru.nilai.simpan', $jadwal) }}" method="POST">
        @csrf
        <div class="card shadow mb-4">
            <div class="card-body">
                @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-nilai">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2" class="student-name">Nama Siswa</th>
                                @for ($i = 1; $i <= 6; $i++)
                                    <th colspan="4">Lingkup Materi {{ $i }}</th>
                                @endfor
                                <th colspan="5">Rekap Sumatif</th>
                            </tr>
                            <tr>
                                @for ($i = 1; $i <= 6; $i++)
                                    @for ($j = 1; $j <= 4; $j++)
                                        <th>TP{{ $j }}</th>
                                    @endfor
                                @endfor
                                <th>Rata2 Harian</th>
                                <th>MID (20%)</th>
                                <th>UAS (30%)</th>
                                <th>Nilai Rapor</th>
                                <th>Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal->kelas->students as $student)
                                <tr class="student-row" data-student-id="{{ $student->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="student-name">{{ $student->name }}</td>
                                    {{-- Kolom Input Nilai Harian (TP) --}}
                                    @php $tp_count = 0; @endphp
                                    @foreach($jadwal->lingkupMateris as $lm)
                                        @foreach($lm->tujuanPembelajarans as $tp)
                                            <td>
                                                @php $nilai_tp = $existingNilaiTp->get($student->id . '-' . $tp->id); @endphp
                                                <input type="number" name="nilai_tp[{{ $student->id }}][{{ $tp->id }}]" class="form-control tp-input" value="{{ $nilai_tp->nilai ?? '' }}" min="0" max="100">
                                            </td>
                                            @php $tp_count++; @endphp
                                        @endforeach
                                    @endforeach
                                    
                                    {{-- Kalkulasi & Rekap --}}
                                    @php
                                        $nilai_mid = $existingNilaiSumatif->get($student->id)?->get('mid');
                                        $nilai_uas = $existingNilaiSumatif->get($student->id)?->get('uas');
                                    @endphp
                                    <td><input type="text" class="form-control rata-rata-harian" disabled></td>
                                    <td><input type="number" name="nilai_sumatif[{{ $student->id }}][mid]" class="form-control mid-input" value="{{ $nilai_mid->nilai ?? '' }}" min="0" max="100"></td>
                                    <td><input type="number" name="nilai_sumatif[{{ $student->id }}][uas]" class="form-control uas-input" value="{{ $nilai_uas->nilai ?? '' }}" min="0" max="100"></td>
                                    <td><input type="text" class="form-control nilai-rapor" disabled></td>
                                    <td><input type="text" class="form-control predikat" disabled></td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ 24 + 7 }}">Tidak ada siswa di kelas ini.</td></tr>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kktp = {{ $jadwal->kktp ?? 75 }};

    function calculateRow(row) {
        const tpInputs = row.querySelectorAll('.tp-input');
        const midInput = row.querySelector('.mid-input');
        const uasInput = row.querySelector('.uas-input');
        
        const rataRataHarianCell = row.querySelector('.rata-rata-harian');
        const nilaiRaporCell = row.querySelector('.nilai-rapor');
        const predikatCell = row.querySelector('.predikat');

        let totalTp = 0;
        let countTp = 0;
        tpInputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                totalTp += value;
                countTp++;
            }
        });

        const rataRataHarian = countTp > 0 ? totalTp / countTp : 0;
        rataRataHarianCell.value = rataRataHarian.toFixed(2);

        const nilaiMid = parseFloat(midInput.value) || 0;
        const nilaiUas = parseFloat(uasInput.value) || 0;

        // Formula: Harian 50%, MID 20%, UAS 30%
        const nilaiAkhir = (rataRataHarian * 0.5) + (nilaiMid * 0.2) + (nilaiUas * 0.3);
        nilaiRaporCell.value = Math.round(nilaiAkhir);

        predikatCell.value = Math.round(nilaiAkhir) >= kktp ? 'Tercapai' : 'Belum';
    }

    // Hitung saat halaman dimuat
    document.querySelectorAll('.student-row').forEach(calculateRow);

    // Tambahkan event listener untuk menghitung ulang saat nilai diubah
    document.querySelectorAll('.tp-input, .mid-input, .uas-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.student-row');
            calculateRow(row);
        });
    });
});
</script>
@endpush