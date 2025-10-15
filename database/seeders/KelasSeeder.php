<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            'X MIPA 1', 'X MIPA 2', 'X IPS 1', 'X IPS 2',
            'XI MIPA 1', 'XI MIPA 2', 'XI IPS 1', 'XI IPS 2',
            'XII MIPA 1', 'XII MIPA 2', 'XII IPS 1', 'XII IPS 2',
        ];

        foreach ($kelas as $k) {
            Kelas::firstOrCreate(['nama_kelas' => $k]);
        }
    }
}