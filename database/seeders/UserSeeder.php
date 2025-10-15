<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. BUAT PENGGUNA DENGAN PERAN TETAP (ADMIN, KEPSEK, GURU, STAFF)
        $this->createStaticUsers();

        // 2. TETAPKAN WALI KELAS BERDASARKAN DATA SK
        $this->assignWaliKelas();

        // 3. PERBAIKAN: BUAT 18 MURID UNTUK SETIAP KELAS YANG ADA
        $this->createStudentsForEachClass(18);
    }

    /**
     * Membuat pengguna statis seperti Super Admin, Kepala Sekolah, Staff, dan Guru.
     */
    private function createStaticUsers(): void
    {
        // Super Admin
        User::firstOrCreate(['nip' => '000000000000000000'], [
            'name' => 'Super Administrator',
            'email' => 'admin@sman2tapung.sch.id',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        // Kepala Sekolah
        User::firstOrCreate(['nip' => '1981010772006051001'], [
            'name' => 'Dr. M.Hendra Yunal, S.Pd.I.M.Si',
            'role' => 'kepala_sekolah',
            'golongan' => 'IV/b',
            'jabatan_guru' => 'Pembina Tk.I',
            'password' => Hash::make('password123'),
        ]);

        // Staff Tata Usaha
        User::firstOrCreate(['nip' => '197405022014062002'], [
            'name' => 'Yeni Yusefa',
            'role' => 'tata_usaha',
            'golongan' => 'II/c',
            'password' => Hash::make('password123'),
        ]);
        
        // Guru-guru (berdasarkan data SK)
        $gurus = [
            ['name' => 'Roni Sarwani, M.Pd', 'nip' => '198106032010011016'],
            ['name' => 'Muhsinin, S.Si', 'nip' => '197104162007011006'],
            ['name' => 'Ahmad Syamsudin, M.Pd.I', 'nip' => '197707172014061003'],
            ['name' => 'Susanti, S.Pd', 'nip' => '197102152005012005'],
            ['name' => 'Leni Lestari, S.Pd', 'nip' => '197106122007012008'],
            ['name' => 'Leni Kusmawati, S.Pd.', 'nip' => '197706302008012009'],
            ['name' => 'Yuni Isminingsih, S.Pd', 'nip' => '198106202010012018'],
            ['name' => 'Nina Herlina Afandi, S.Pd', 'nip' => '198302152023212022'],
            ['name' => 'Herniati Monika Manulang, S.Pd', 'nip' => '199008202024212045'],
            ['name' => 'Didik Puji Sutriyono, S.Si, M.Si', 'nip' => '198003302023211005'],
            ['name' => 'Tri Agustina, S.Kom', 'nip' => '199508142023212032'],
            ['name' => 'Rita Marya Fasya, S.Si', 'nip' => '197809142014062003'],
            ['name' => 'Debby Atria, S.Pd', 'nip' => '199702072024212035'],
            ['name' => 'Maharani, S.Pd', 'nip' => '199305032023212043'],
            ['name' => 'Erna Setyaningsih, S.Pd', 'nip' => '19871025202421003'],
            // Tambahkan NIP unik untuk guru yang tidak ada NIP-nya di SK
            ['name' => 'Endah Neneng Kristiana, SE', 'nip' => '199201012023012001'],
        ];
        foreach ($gurus as $guru) {
            User::firstOrCreate(['nip' => $guru['nip']], [
                'name' => $guru['name'],
                'role' => 'guru',
                'password' => Hash::make('password123'),
            ]);
        }
    }

    /**
     * Menetapkan Wali Kelas ke kelas yang sesuai.
     */
    private function assignWaliKelas(): void
    {
        $penugasan = [
            '198302152023212022' => 'X 1',
            '199008202024212045' => 'X 2',
            '199201012023012001' => 'X 3',
            '198003302023211005' => 'X 4',
            '199508142023212032' => 'X 5',
            '197809142014062003' => 'XI MIPA 1',
            '197106122007012008' => 'XI MIPA 2',
            '199702072024212035' => 'XI IPS 1',
            '199305032023212043' => 'XI IPS 2',
            '198106202010012018' => 'XII MIPA 1',
            '197102152005012005' => 'XII IPA 2',
            '197706302008012009' => 'XII IPS 2',
        ];

        foreach ($penugasan as $nipGuru => $namaKelas) {
            $guru = User::where('nip', $nipGuru)->first();
            $kelas = Kelas::where('nama_kelas', $namaKelas)->first();
            if ($guru && $kelas) {
                $kelas->update(['walikelas_id' => $guru->id]);
            }
        }
    }

    /**
     * Membuat sejumlah siswa untuk setiap kelas yang ada di database.
     */
    private function createStudentsForEachClass(int $numberOfStudents): void
    {
        $allKelas = Kelas::all();

        foreach ($allKelas as $kelas) {
            for ($i = 1; $i <= $numberOfStudents; $i++) {
                // Buat nama dan nisn yang unik
                $studentName = 'Siswa ' . $kelas->nama_kelas . ' ' . $i;
                $studentNisn = '00' . ($kelas->id * 100 + $i); // Contoh: 00101, 00102, 00201, dst.

                User::firstOrCreate(['nisn' => $studentNisn], [
                    'name' => $studentName,
                    'role' => 'murid',
                    'password' => Hash::make('password123'),
                    'kelas_id' => $kelas->id,
                ]);
            }
        }
    }
}