<?php

namespace App\Exports;

use App\Models\Jadwal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NilaiExport implements FromView
{
    protected $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function view(): View
    {
        // Logika pengambilan data sama seperti di NilaiController
        $jadwal = $this->jadwal->load('kelas.students', 'lingkupMateris.tujuanPembelajarans');
        $studentIds = $jadwal->kelas->students->pluck('id');
        $tpIds = $jadwal->lingkupMateris->flatMap->tujuanPembelajarans->pluck('id');

        $existingNilaiTp = \App\Models\Nilai::whereIn('student_id', $studentIds)
            ->whereIn('tujuan_pembelajaran_id', $tpIds)->get()
            ->keyBy(fn($item) => $item->student_id . '-' . $item->tujuan_pembelajaran_id);

        $existingNilaiSumatif = \App\Models\NilaiSumatif::whereIn('student_id', $studentIds)
            ->where('jadwal_id', $jadwal->id)->get()
            ->groupBy('student_id')->map(fn($items) => $items->keyBy('jenis'));

        return view('guru.nilai.export', [
            'jadwal' => $jadwal,
            'existingNilaiTp' => $existingNilaiTp,
            'existingNilaiSumatif' => $existingNilaiSumatif
        ]);
    }
}