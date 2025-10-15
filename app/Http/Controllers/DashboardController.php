<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menyiapkan dan menampilkan data dashboard yang sesuai berdasarkan peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $viewData = [];

        switch ($role) {
            case 'super_admin':
            case 'tata_usaha':
            case 'kepala_sekolah':
                $viewData['jumlahGuru'] = User::where('role', 'guru')->count();
                $viewData['jumlahSiswa'] = User::where('role', 'murid')->count();
                $viewData['jumlahKelas'] = Kelas::count();
                $viewData['jumlahStaf'] = User::whereIn('role', ['tata_usaha', 'kepala_sekolah', 'super_admin'])->count();
                break;

            case 'guru':
                $viewData['jumlahKelasDiajar'] = Jadwal::where('teacher_id', $user->id)->distinct('kelas_id')->count();
                $viewData['kelasWali'] = $user->kelasWali()->withCount('students')->first();
                break;

            case 'murid':
                $user->load('kelas.waliKelas');
                $viewData['user'] = $user;
                break;
        }

        return view('dashboard', $viewData);
    }
}