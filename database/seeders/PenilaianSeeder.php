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
        

        // Ambil semua jadwal yang telah dibuat
        $allJadwals = Jadwal::with('kelas.students')->get();
        if ($allJadwals->isEmpty()) {
            $this->command->warn('Tidak ada jadwal untuk diisi data penilaian.');
            return;
        }

        foreach ($allJadwals as $jadwal) {
            // 1. Buat 2 contoh Lingkup Materi untuk setiap jadwal
            $lm1 = LingkupMateri::create([
                'jadwal_id' => $jadwal->id,
                'nama_lingkup' => 'Lingkup Materi 1',
                'urutan' => 1
            ]);
            $lm2 = LingkupMateri::create([
                'jadwal_id' => $jadwal->id,
                'nama_lingkup' => 'Lingkup Materi 2',
                'urutan' => 2
            ]);

            // 2. Buat 2 contoh TP untuk setiap Lingkup Materi
            $tp1 = TujuanPembelajaran::create(['lingkup_materi_id' => $lm1->id, 'kode_tp' => 'TP1', 'deskripsi' => 'Deskripsi untuk TP1']);
            $tp2 = TujuanPembelajaran::create(['lingkup_materi_id' => $lm1->id, 'kode_tp' => 'TP2', 'deskripsi' => 'Deskripsi untuk TP2']);
            $tp3 = TujuanPembelajaran::create(['lingkup_materi_id' => $lm2->id, 'kode_tp' => 'TP3', 'deskripsi' => 'Deskripsi untuk TP3']);
            $tps = [$tp1, $tp2, $tp3];

            // 3. Ambil semua siswa dari kelas pada jadwal ini
            $students = $jadwal->kelas->students;
            if ($students->isEmpty()) continue;

            // 4. Isi nilai acak untuk setiap siswa
            foreach ($students as $student) {
                // Isi Nilai Harian (per TP)
                foreach ($tps as $tp) {
                    Nilai::create([
                        'student_id' => $student->id,
                        'tujuan_pembelajaran_id' => $tp->id,
                        'nilai' => rand(70, 98)
                    ]);
                }
                // Isi Nilai Sumatif
                NilaiSumatif::create([
                    'student_id' => $student->id,
                    'jadwal_id' => $jadwal->id,
                    'jenis' => 'mid',
                    'nilai' => rand(75, 95)
                ]);
                NilaiSumatif::create([
                    'student_id' => $student->id,
                    'jadwal_id' => $jadwal->id,
                    'jenis' => 'uas',
                    'nilai' => rand(72, 96)
                ]);
            }
        }
        $this->command->info('Struktur penilaian dan nilai contoh berhasil dibuat untuk semua jadwal.');
    }
}