<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\NilaiSumatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function pilihJadwal()
    {
        $jadwals = Jadwal::where('teacher_id', Auth::id())
            ->with('kelas', 'mataPelajaran')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
            
        return view('guru.nilai.pilih_jadwal', compact('jadwals'));
    }

    public function formInputNilai(Jadwal $jadwal)
    {
        if ($jadwal->teacher_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $jadwal->load('kelas.students', 'lingkupMateris.tujuanPembelajarans');

        // === PERBAIKAN UTAMA: AMBIL NILAI YANG SUDAH ADA ===
        $studentIds = $jadwal->kelas->students->pluck('id');
        $tpIds = $jadwal->lingkupMateris->flatMap->tujuanPembelajarans->pluck('id');

        // Ambil nilai harian dalam format yang mudah diakses di view: [student_id][tp_id] => nilai
        $existingNilaiTp = Nilai::whereIn('student_id', $studentIds)
            ->whereIn('tujuan_pembelajaran_id', $tpIds)
            ->get()
            ->keyBy(fn($item) => $item->student_id . '-' . $item->tujuan_pembelajaran_id);

        // Ambil nilai sumatif dalam format: [student_id][jenis] => nilai
        $existingNilaiSumatif = NilaiSumatif::whereIn('student_id', $studentIds)
            ->where('jadwal_id', $jadwal->id)
            ->get()
            ->groupBy('student_id')
            ->map(fn($items) => $items->keyBy('jenis'));

        return view('guru.nilai.form_input', compact('jadwal', 'existingNilaiTp', 'existingNilaiSumatif'));
    }

    public function simpanNilai(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->teacher_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Logika simpan nilai tetap sama, `updateOrCreate` sudah menangani kasus update.
        foreach ($request->input('nilai_tp', []) as $studentId => $tps) {
            foreach ($tps as $tpId => $nilai) {
                if (is_numeric($nilai)) {
                    Nilai::updateOrCreate(
                        ['student_id' => $studentId, 'tujuan_pembelajaran_id' => $tpId],
                        ['nilai' => $nilai]
                    );
                }
            }
        }

        foreach ($request->input('nilai_sumatif', []) as $studentId => $sumatifs) {
            if (isset($sumatifs['mid']) && is_numeric($sumatifs['mid'])) {
                NilaiSumatif::updateOrCreate(
                    ['student_id' => $studentId, 'jadwal_id' => $jadwal->id, 'jenis' => 'mid'],
                    ['nilai' => $sumatifs['mid']]
                );
            }
            if (isset($sumatifs['uas']) && is_numeric($sumatifs['uas'])) {
                NilaiSumatif::updateOrCreate(
                    ['student_id' => $studentId, 'jadwal_id' => $jadwal->id, 'jenis' => 'uas'],
                    ['nilai' => $sumatifs['uas']]
                );
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan atau diperbarui!');
    }
}