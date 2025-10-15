<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Spesialisasi Guru (Satu Guru -> Satu Mapel Utama)
        // [cite_start]// Data ini kita ambil dari SK yang Anda berikan. [cite: 78, 79]
        $teacherSpecializations = [
            'PAI'   => User::where('nip', '197707172014061003')->first(), // Ahmad Syamsudin
            'BING'  => User::where('nip', '197102152005012005')->first(), // Susanti, S.Pd
            'MTK'   => User::where('nip', '198302152023212022')->first(), // Nina Herlina
            'BIO'   => User::where('nip', '197104162007011006')->first(), // Muhsinin, S.Si
            'KIM'   => User::where('nip', '19871025202421003')->first(), // Erna Setyaningsih
            'FIS'   => User::where('nip', '198003302023211005')->first(), // Didik Puji
            'EKO'   => User::where('nip', '197106122007012008')->first(), // Leni Lestari
            'GEO'   => User::where('nip', '199008202024212045')->first(), // Herniati Monika
            'SEJ'   => User::where('nip', '197706302008012009')->first(), // Leni Kusmawati
            'SOS'   => User::where('nip', '199507202020122011')->first(), // Yulita Hutahaean
            'PKN'   => User::where('nip', '197709222023212007')->first(), // Sulis Setiowati
            'PJOK'  => User::where('nip', '198106202010012018')->first(), // Yuni Isminingsih
            'INF'   => User::where('nip', '198306202011021001')->first(), // Muhibud, S. Kom
            'SENI'  => User::where('nip', '198106032010011016')->first(), // Roni Sarwani
            'BINDO' => User::where('nip', '198302152023212022')->first(), // Nina Herlina (bisa mengajar lebih dari 1)
        ];

        // 2. Definisikan Kurikulum per Tingkatan/Jurusan
        $kurikulumUmum = ['PAI', 'PKN', 'BINDO', 'MTK', 'SEJ', 'BING', 'SENI', 'PJOK'];
        $kurikulumX = ['INF', 'BIO', 'KIM', 'FIS']; // Mapel dasar untuk kelas X
        $kurikulumMIPA = ['BIO', 'FIS', 'KIM']; // Peminatan MIPA
        $kurikulumIPS = ['GEO', 'EKO', 'SOS']; // Peminatan IPS

        // 3. Definisikan Slot Waktu & Pelacak
        $timeSlots = [
            ['mulai' => '07:30:00', 'selesai' => '09:30:00'],
            ['mulai' => '10:00:00', 'selesai' => '12:00:00'],
            ['mulai' => '13:00:00', 'selesai' => '15:00:00'],
        ];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $scheduleTracker = [];

        // 4. Proses Utama: Loop untuk setiap kelas
        $allKelas = Kelas::all();
        foreach ($allKelas as $kelas) {
            $mapelsUntukKelas = $kurikulumUmum;
            $namaKelas = $kelas->nama_kelas;

            // Tentukan mapel tambahan berdasarkan nama kelas
            if (Str::startsWith($namaKelas, 'X ')) {
                $mapelsUntukKelas = array_merge($mapelsUntukKelas, $kurikulumX);
            } elseif (Str::contains($namaKelas, 'MIPA')) {
                $mapelsUntukKelas = array_merge($mapelsUntukKelas, $kurikulumMIPA);
            } elseif (Str::contains($namaKelas, 'IPS')) {
                $mapelsUntukKelas = array_merge($mapelsUntukKelas, $kurikulumIPS);
            }

            // Buat jadwal untuk setiap mapel yang relevan
            foreach ($mapelsUntukKelas as $kodeMapel) {
                $mapel = MataPelajaran::where('kode_mapel', $kodeMapel)->first();
                $guru = $teacherSpecializations[$kodeMapel] ?? null;

                if (!$mapel || !$guru) {
                    $this->command->warn("Melewati mapel '{$kodeMapel}' untuk kelas {$namaKelas}. Guru/Mapel tidak ditemukan.");
                    continue;
                }

                $this->findAndCreateSlot($guru, $kelas, $mapel, $days, $timeSlots, $scheduleTracker);
            }
        }
        $this->command->info('Jadwal pelajaran berdasarkan kurikulum berhasil dibuat tanpa tabrakan.');
    }

    /**
     * Helper function untuk mencari slot kosong dan membuat jadwal.
     */
    private function findAndCreateSlot($guru, $kelas, $mapel, $days, $timeSlots, &$scheduleTracker)
    {
        foreach ($days as $day) {
            foreach ($timeSlots as $index => $slot) {
                $isTeacherAvailable = !isset($scheduleTracker[$day][$index]['teachers'][$guru->id]);
                $isClassAvailable = !isset($scheduleTracker[$day][$index]['classes'][$kelas->id]);

                if ($isTeacherAvailable && $isClassAvailable) {
                    Jadwal::create([
                        'kelas_id' => $kelas->id,
                        'teacher_id' => $guru->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'kktp' => 75,
                        'tahun_ajaran' => '2024/2025',
                        'semester' => 2, // Sesuai SK [cite: 13]
                        'hari' => $day,
                        'jam_mulai' => $slot['mulai'],
                        'jam_selesai' => $slot['selesai'],
                    ]);

                    $scheduleTracker[$day][$index]['teachers'][$guru->id] = true;
                    $scheduleTracker[$day][$index]['classes'][$kelas->id] = true;
                    return; // Slot ditemukan, keluar dari fungsi
                }
            }
        }
        $this->command->warn("Tidak ditemukan slot kosong untuk {$guru->name} di kelas {$kelas->nama_kelas} untuk mapel {$mapel->kode_mapel}.");
    }
}