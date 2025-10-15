<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $query = User::with('kelas')->get();

        // PERBAIKAN LOGIKA: Tata usaha tetap tidak boleh melihat super_admin.
        // Ini adalah praktik keamanan yang baik untuk melindungi akun utama.
        if (Auth::user()->role == 'tata_usaha') {
            $query->where('role', '!=', 'super_admin');
        }

        $users = $query;
        // PERBAIKAN PATH VIEW: Menggunakan path 'manejemen.users.index' untuk konsistensi.
        return view('manajemen.users.index', compact('users'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('manajemen.users.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'nip' => ['nullable', 'string', 'max:255', 'unique:users'],
            'nisn' => ['nullable', 'string', 'max:255', 'unique:users'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'nip' => $request->nip,
            'nisn' => $request->nisn,
            'golongan' => $request->golongan,
            'jabatan_guru' => $request->jabatan_guru,
            'kelas_id' => $request->role === 'murid' ? $request->kelas_id : null,
            'password' => Hash::make($request->password),
        ]);
        
        // PERBAIKAN REDIRECT: Disederhanakan menjadi satu rute.
        return redirect()->route('manajemen.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('manajemen.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Keamanan: Tata Usaha tidak boleh mengedit Super Admin
        if (Auth::user()->role == 'tata_usaha' && $user->role == 'super_admin') {
            abort(403, 'AKSES DITOLAK');
        }

        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('manajemen.users.edit', compact('user', 'kelas'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([ /* ... validasi tetap sama ... */ ]);

        $userData = $request->except(['password', 'password_confirmation']);
        $userData['kelas_id'] = $request->role === 'murid' ? $request->kelas_id : null;
        $user->update($userData);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('manajemen.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // PERBAIKAN KEAMANAN: Tambahkan pengecekan agar tata_usaha tidak bisa menghapus super_admin.
        if (Auth::user()->role == 'tata_usaha' && $user->role == 'super_admin') {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk menghapus Super Admin.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('manajemen.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}