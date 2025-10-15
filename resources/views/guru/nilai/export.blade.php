<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Siswa</th>
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
            <th>MID</th>
            <th>UAS</th>
            <th>Nilai Rapor</th>
            <th>Predikat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwal->kelas->students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->name }}</td>
                @php
                    $totalTp = 0; $countTp = 0;
                @endphp
                @foreach($jadwal->lingkupMateris as $lm)
                    @foreach($lm->tujuanPembelajarans as $tp)
                        @php
                            $nilai_tp_obj = $existingNilaiTp->get($student->id . '-' . $tp->id);
                            $nilai_tp = $nilai_tp_obj ? $nilai_tp_obj->nilai : null;
                            if(is_numeric($nilai_tp)) { $totalTp += $nilai_tp; $countTp++; }
                        @endphp
                        <td>{{ $nilai_tp }}</td>
                    @endforeach
                @endforeach
                @php
                    $rataRataHarian = $countTp > 0 ? $totalTp / $countTp : 0;
                    $nilai_mid = $existingNilaiSumatif->get($student->id)?->get('mid')?->nilai ?? 0;
                    $nilai_uas = $existingNilaiSumatif->get($student->id)?->get('uas')?->nilai ?? 0;
                    $nilaiAkhir = ($rataRataHarian * 0.5) + ($nilai_mid * 0.2) + ($nilai_uas * 0.3);
                @endphp
                <td>{{ number_format($rataRataHarian, 2) }}</td>
                <td>{{ $nilai_mid }}</td>
                <td>{{ $nilai_uas }}</td>
                <td>{{ round($nilaiAkhir) }}</td>
                <td>{{ round($nilaiAkhir) >= $jadwal->kktp ? 'Tercapai' : 'Belum Tercapai' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>