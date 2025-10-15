<?php

namespace App\Http\Controllers\Guru;

use App\Exports\NilaiExport;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Maatwebsite\Excel\Facades\Excel;

class NilaiExportController extends Controller
{
    public function export(Jadwal $jadwal)
    {
        $fileName = 'Nilai ' . $jadwal->mataPelajaran->nama_mapel . ' - ' . $jadwal->kelas->nama_kelas . '.xlsx';
        return Excel::download(new NilaiExport($jadwal), $fileName);
    }
}