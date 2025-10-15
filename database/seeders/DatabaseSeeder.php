<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Urutan ini sangat penting
            MataPelajaranSeeder::class,
            KelasSeeder::class,
            UserSeeder::class,
            JadwalSeeder::class,
            PenilaianSeeder::class, // Jalankan ini setelah JadwalSeeder
        ]);
    }
}