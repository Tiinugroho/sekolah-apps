<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Support\Str;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan pemetaan NIP guru ke kode mapel yang mereka ampu
        $teacherNipToSubject = [
            '197707172014061003' => 'PAI',   // Ahmad Syamsudin
            '197102152005012005' => 'BING',  // Susanti, S.Pd
            '196803232014062002' => 'MTK',   // PERBAIKAN: Sri Susanti. STP sekarang mengajar MTK
            '197104162007011006' => 'BIO',   // Muhsinin, S.Si
            '19871025202421003' => 'KIM',   // Erna Setyaningsih
            '198003302023211005' => 'FIS',   // Didik Puji
            '197106122007012008' => 'EKO',   // Leni Lestari
            '199008202024212045' => 'GEO',   // Herniati Monika
            '197706302008012009' => 'SEJ',   // Leni Kusmawati
            '199507202020122011' => 'SOS',   // Yulita Hutahaean
            '197709222023212007' => 'PKN',   // Sulis Setiowati
            '198106202010012018' => 'PJOK',  // Yuni Isminingsih
            '198306202011021001' => 'INF',   // Muhibud, S. Kom
            '198106032010011016' => 'SENI',  // Roni Sarwani
            '198302152023212022' => 'BINDO', // PERBAIKAN: Nina Herlina sekarang hanya mengajar BINDO
        ];

        // 2. Bangun peta spesialisasi dan buat guru dummy jika diperlukan
        $allMapels = MataPelajaran::all();
        $teacherSpecializations = [];
        foreach ($allMapels as $mapel) {
            $nip = array_search($mapel->kode_mapel, $teacherNipToSubject);
            $guru = $nip ? User::where('nip', $nip)->first() : null;

            // PERBAIKAN: Jika tidak ada guru asli untuk mapel ini, buat guru dummy
            if (!$guru) {
                $this->command->warn("Tidak ada guru asli untuk mapel {$mapel->kode_mapel}. Membuat guru dummy...");
                $dummyNip = 'DUMMY' . $mapel->id;
                $guru = User::firstOrCreate(['nip' => $dummyNip], [
                    'name' => 'Guru Dummy ' . $mapel->nama_mapel,
                    'role' => 'guru',
                    'password' => Hash::make('password123'),
                ]);
            }
            $teacherSpecializations[$mapel->kode_mapel] = $guru;
        }

        // 3. Definisikan Kurikulum, Slot Waktu, dan Pelacak
        $kurikulumUmum = ['PAI', 'PKN', 'BINDO', 'MTK', 'SEJ', 'BING', 'SENI', 'PJOK'];
        $kurikulumMIPA = ['BIO', 'FIS', 'KIM'];
        $kurikulumIPS = ['GEO', 'EKO', 'SOS'];
        $timeSlots = [
            ['mulai' => '07:30', 'selesai' => '09:00'], ['mulai' => '09:00', 'selesai' => '10:30'],
            ['mulai' => '11:00', 'selesai' => '12:30'], ['mulai' => '13:30', 'selesai' => '15:00'],
        ];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $scheduleTracker = [];

        // 4. Proses Utama (Looping tidak diubah, logikanya sudah benar)
        $allKelas = Kelas::all()->shuffle(); // Shuffle untuk variasi
        foreach ($allKelas as $kelas) {
            $mapelsUntukKelas = $kurikulumUmum;
            $namaKelas = $kelas->nama_kelas;

            if (Str::contains($namaKelas, 'MIPA')) {
                $mapelsUntukKelas = array_merge($mapelsUntukKelas, $kurikulumMIPA);
            } elseif (Str::contains($namaKelas, 'IPS')) {
                $mapelsUntukKelas = array_merge($mapelsUntukKelas, $kurikulumIPS);
            }
            if (Str::startsWith($namaKelas, 'X ')) {
                $mapelsUntukKelas[] = 'INF';
            }
            
            foreach (array_unique($mapelsUntukKelas) as $kodeMapel) {
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

    private function findAndCreateSlot($guru, $kelas, $mapel, $days, $timeSlots, &$scheduleTracker)
    {
        // Fungsi helper ini tidak perlu diubah, logikanya sudah benar
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
                        'semester' => 2,
                        'hari' => $day,
                        'jam_mulai' => $slot['mulai'],
                        'jam_selesai' => $slot['selesai'],
                    ]);
                    $scheduleTracker[$day][$index]['teachers'][$guru->id] = true;
                    $scheduleTracker[$day][$index]['classes'][$kelas->id] = true;
                    return;
                }
            }
        }
        $this->command->warn("Tidak ditemukan slot kosong untuk {$guru->name} di kelas {$kelas->nama_kelas} untuk mapel {$mapel->kode_mapel}.");
    }
}