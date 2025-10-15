<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\NilaiSumatif;
use App\Models\User; // Pastikan User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function index()
    {
        $guru = Auth::user();
        $kelas = $guru->kelasWali()->with('students')->first();
        return view('guru.walikelas.index', compact('kelas'));
    }

    public function showStudentRaport(User $student, Request $request)
    {
        $guru = Auth::user();
        $kelasWali = $guru->kelasWali;

        if (!$kelasWali || $student->kelas_id !== $kelasWali->id) {
            abort(403, 'AKSES DITOLAK.');
        }

        // PERBAIKAN: Eager load relasi siswa sebelum memanggil helper
        $student->load('kelas.waliKelas');
        
        $raportData = $this->getRaportDataForStudent($student, $request);

        return view('siswa.raport.show', $raportData);
    }

    private function getRaportDataForStudent(User $student, Request $request): array
    {
        // Logika di sini sekarang sama persis dengan yang ada di RaportController
        $latestJadwal = Jadwal::where('kelas_id', $student->kelas_id)->latest('tahun_ajaran')->latest('semester')->first();
        
        $tahun_ajaran = $request->input('tahun_ajaran', $latestJadwal->tahun_ajaran ?? null);
        $semester = $request->input('semester', $latestJadwal->semester ?? null);
        
        $jadwals = Jadwal::where('kelas_id', $student->kelas_id)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('semester', $semester)
            ->with('mataPelajaran', 'teacher')
            ->get();

        $dataRapor = [];
        if ($jadwals->isNotEmpty()) {
            foreach ($jadwals as $jadwal) {
                $idTp = $jadwal->lingkupMateris()->with('tujuanPembelajarans')->get()->pluck('tujuanPembelajarans.*.id')->flatten();
                $avgNilaiHarian = Nilai::where('student_id', $student->id)->whereIn('tujuan_pembelajaran_id', $idTp)->avg('nilai');
                $nilaiSumatifs = NilaiSumatif::where('student_id', $student->id)->where('jadwal_id', $jadwal->id)->pluck('nilai', 'jenis');
                
                $NH = $avgNilaiHarian ?? 0;
                $NM = $nilaiSumatifs->get('mid', 0);
                $NUS = $nilaiSumatifs->get('uas', 0);
                $nilaiAkhir = ($NH * 0.5) + ($NM * 0.2) + ($NUS * 0.3);

                $dataRapor[] = [
                    'mapel' => $jadwal->mataPelajaran->nama_mapel,
                    'guru' => $jadwal->teacher->name,
                    'kktp' => $jadwal->kktp,
                    'nilai_akhir' => round($nilaiAkhir),
                    'predikat' => $nilaiAkhir >= $jadwal->kktp ? 'Tercapai' : 'Belum Tercapai',
                ];
            }
        }

        // PERBAIKAN: Logika pengambilan nama dan NIP Wali Kelas yang lebih aman
        $waliKelas = $student->kelas->waliKelas ?? null;

        return [
            'user' => $student,
            'dataRapor' => $dataRapor,
            'waliKelas' => $waliKelas ? $waliKelas->name : 'Belum Ditentukan',
            'nipWaliKelas' => $waliKelas ? 'NIP. ' . $waliKelas->nip : '-',
            'listTahunAjaran' => Jadwal::where('kelas_id', $student->kelas_id)->distinct()->pluck('tahun_ajaran'),
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
        ];
    }
}