<?php

use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Siswa\RaportController;
use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Guru\WaliKelasController;
use App\Http\Controllers\Admin\MataPelajaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Arahkan URL root langsung ke dashboard
Route::redirect('/', '/dashboard');

// Rute untuk otentikasi (login, logout, dll.) dari Laravel Breeze/UI
require __DIR__ . '/auth.php';

// Semua rute di dalam grup ini memerlukan login
Route::middleware(['auth'])->group(function () {
    // Rute dashboard utama yang akan mengarahkan berdasarkan peran
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================================================================
    // GRUP SUPER ADMIN (AKSES PENUH KE SEMUA MANAJEMEN)
    // =====================================================================
    Route::middleware(['role:super_admin'])
        ->prefix('super-admin')
        ->name('admin.')
        ->group(function () {
            // Kelola Pengguna (semua role)
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // Kelola Mata Pelajaran
            Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel.index');
            Route::get('/mapel/create', [MataPelajaranController::class, 'create'])->name('mapel.create');
            Route::post('/mapel', [MataPelajaranController::class, 'store'])->name('mapel.store');
            Route::get('/mapel/{mapel}/edit', [MataPelajaranController::class, 'edit'])->name('mapel.edit');
            Route::put('/mapel/{mapel}', [MataPelajaranController::class, 'update'])->name('mapel.update');
            Route::delete('/mapel/{mapel}', [MataPelajaranController::class, 'destroy'])->name('mapel.destroy');

            // Kelola Kelas
            Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
            Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
            Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
            Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
            Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
            Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');

            // Kelola Jadwal
            Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
            Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
            Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
            Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
            Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
            Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
            // (Super admin juga bisa mengakses rute manajemen di bawah ini,
            // namun rute ini dibuat untuk kejelasan peran)
        });

    // =====================================================================
    // GRUP TATA USAHA / STAFF (MANAJEMEN DATA SEKOLAH)
    // =====================================================================
    Route::middleware(['role:tata_usaha'])
        ->prefix('manajemen')
        ->name('manajemen.')
        ->group(function () {
            // Kelola Pengguna (Guru & Siswa)
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // Kelola Mata Pelajaran
            Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel.index');
            Route::get('/mapel/create', [MataPelajaranController::class, 'create'])->name('mapel.create');
            Route::post('/mapel', [MataPelajaranController::class, 'store'])->name('mapel.store');
            Route::get('/mapel/{mapel}/edit', [MataPelajaranController::class, 'edit'])->name('mapel.edit');
            Route::put('/mapel/{mapel}', [MataPelajaranController::class, 'update'])->name('mapel.update');
            Route::delete('/mapel/{mapel}', [MataPelajaranController::class, 'destroy'])->name('mapel.destroy');

            // Kelola Kelas
            Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
            Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
            Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
            Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
            Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
            Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');

            // Kelola Jadwal
            Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
            Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
            Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
            Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
            Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
            Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
        });

    // =====================================================================
    // GRUP KEPALA SEKOLAH (HANYA MONITORING / READ-ONLY)
    // =====================================================================
    Route::middleware(['role:kepala_sekolah'])
        ->prefix('monitoring')
        ->name('monitoring.')
        ->group(function () {
            // Melihat Data Pengguna
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

            // Melihat Data Mata Pelajaran
            Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel.index');
            Route::get('/mapel/{mapel}', [MataPelajaranController::class, 'show'])->name('mapel.show');

            // Melihat Data Kelas
            Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
            Route::get('/kelas/{kela}', [KelasController::class, 'show'])->name('kelas.show');

            // Melihat Data Jadwal
            Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
            Route::get('/jadwal/{jadwal}', [JadwalController::class, 'show'])->name('jadwal.show');
        });

    // =====================================================================
    // GRUP GURU (Fungsional)
    // =====================================================================
    Route::middleware(['role:guru'])
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {
            Route::get('/pilih-jadwal', [NilaiController::class, 'pilihJadwal'])->name('nilai.pilihJadwal');
            Route::get('/input-nilai/{jadwal}', [NilaiController::class, 'formInputNilai'])->name('nilai.form');
            Route::post('/simpan-nilai/{jadwal}', [NilaiController::class, 'simpanNilai'])->name('nilai.simpan');

            // Route model binding {student:id} agar otomatis mengambil data User
            Route::get('/wali-kelas/raport/{student:id}', [WaliKelasController::class, 'showStudentRaport'])->name('walas.raport.show');

            Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('walas.index');

            // ==> TAMBAHKAN RUTE-RUTE INI UNTUK SETUP PENILAIAN <==
            Route::get('/penilaian/{jadwal}/setup', [PenilaianController::class, 'setup'])->name('penilaian.setup');
            Route::post('/penilaian/{jadwal}/lingkup', [PenilaianController::class, 'storeLingkup'])->name('penilaian.storeLingkup');
            Route::delete('/lingkup/{lingkupMateri}', [PenilaianController::class, 'destroyLingkup'])->name('penilaian.destroyLingkup');
            Route::post('/lingkup/{lingkupMateri}/tp', [PenilaianController::class, 'storeTp'])->name('penilaian.storeTp');
            Route::delete('/tp/{tp}', [PenilaianController::class, 'destroyTp'])->name('penilaian.destroyTp');
        });

    // =====================================================================
    // GRUP SISWA (Fungsional)
    // =====================================================================
    Route::middleware(['role:murid'])
        ->prefix('siswa')
        ->name('siswa.')
        ->group(function () {
            Route::get('/raport', [RaportController::class, 'show'])->name('raport.show');
        });
});
