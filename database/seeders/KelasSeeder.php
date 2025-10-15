<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            'X 1', 'X 2', 'X 3', 'X 4', 'X 5',
            'XI MIPA 1', 'XI MIPA 2', 'XI IPS 1', 'XI IPS 2',
            'XII MIPA 1', 'XII MIPA 2', 'XII IPS 1', 'XII IPS 2',
        ];

        foreach ($kelas as $k) {
            Kelas::firstOrCreate(['nama_kelas' => $k]);
        }
    }
}