<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['nama_mapel' => 'Pendidikan Seni', 'kode_mapel' => 'SENI'],
            ['nama_mapel' => 'Biologi', 'kode_mapel' => 'BIO'],
            ['nama_mapel' => 'Bahasa Inggris', 'kode_mapel' => 'BING'],
            ['nama_mapel' => 'Pendidikan Agama Islam', 'kode_mapel' => 'PAI'],
            ['nama_mapel' => 'Ekonomi', 'kode_mapel' => 'EKO'],
            ['nama_mapel' => 'Sejarah', 'kode_mapel' => 'SEJ'],
            ['nama_mapel' => 'PJOK', 'kode_mapel' => 'PJOK'],
            ['nama_mapel' => 'Informatika', 'kode_mapel' => 'INF'],
            ['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK'],
            ['nama_mapel' => 'Kimia', 'kode_mapel' => 'KIM'],
            ['nama_mapel' => 'Budaya Melayu Riau', 'kode_mapel' => 'BMR'],
            ['nama_mapel' => 'PKN', 'kode_mapel' => 'PKN'],
            ['nama_mapel' => 'Sosiologi', 'kode_mapel' => 'SOS'],
            ['nama_mapel' => 'Bimbingan Konseling', 'kode_mapel' => 'BK'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BINDO'],
            ['nama_mapel' => 'Geografi', 'kode_mapel' => 'GEO'],
            ['nama_mapel' => 'PKWU', 'kode_mapel' => 'PKWU'],
            ['nama_mapel' => 'Fisika', 'kode_mapel' => 'FIS'],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::firstOrCreate(['kode_mapel' => $mapel['kode_mapel']], $mapel);
        }
    }
}