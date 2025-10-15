<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\LingkupMateri;
use App\Models\Nilai;
use App\Models\NilaiSumatif;
use App\Models\TujuanPembelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {

        $allJadwals = Jadwal::with('kelas.students')->get();
        if ($allJadwals->isEmpty()) {
            $this->command->warn('Tidak ada jadwal untuk diisi data penilaian.');
            return;
        }

        foreach ($allJadwals as $jadwal) {
            $allTps = [];
            // PERBAIKAN: Buat 6 Lingkup Materi
            for ($i = 1; $i <= 6; $i++) {
                $lm = LingkupMateri::create([
                    'jadwal_id' => $jadwal->id,
                    'nama_lingkup' => 'Lingkup Materi ' . $i,
                    'urutan' => $i
                ]);

                // PERBAIKAN: Buat 4 TP untuk setiap Lingkup Materi
                for ($j = 1; $j <= 4; $j++) {
                    $tp = TujuanPembelajaran::create([
                        'lingkup_materi_id' => $lm->id,
                        'kode_tp' => 'TP' . $j,
                        'deskripsi' => "Deskripsi untuk TP{$j} pada LM{$i}"
                    ]);
                    $allTps[] = $tp;
                }
            }

            $students = $jadwal->kelas->students;
            if ($students->isEmpty()) continue;

            foreach ($students as $student) {
                // Isi Nilai Harian (per TP)
                foreach ($allTps as $tp) {
                    Nilai::create([
                        'student_id' => $student->id,
                        'tujuan_pembelajaran_id' => $tp->id,
                        'nilai' => rand(70, 98)
                    ]);
                }
                // Isi Nilai Sumatif
                NilaiSumatif::create(['student_id' => $student->id, 'jadwal_id' => $jadwal->id, 'jenis' => 'mid', 'nilai' => rand(75, 95)]);
                NilaiSumatif::create(['student_id' => $student->id, 'jadwal_id' => $jadwal->id, 'jenis' => 'uas', 'nilai' => rand(72, 96)]);
            }
        }
        $this->command->info('Struktur penilaian (6 LM x 4 TP) dan nilai contoh berhasil dibuat.');
    }
}