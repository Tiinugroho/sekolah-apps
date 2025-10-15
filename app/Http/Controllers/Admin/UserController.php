<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $query = User::with('kelas')->latest();

        // Tata usaha tidak bisa melihat/mengelola super admin
        if (Auth::user()->role == 'tata_usaha') {
            $query->where('role', '!=', 'super_admin');
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.users.create', compact('kelas'));
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

        return redirect()->route(Auth::user()->role == 'super_admin' ? 'admin.users.index' : 'manajemen.users.index')
                         ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.users.edit', compact('user', 'kelas'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'nip' => ['nullable', 'string', 'max:255', 'unique:users,nip,'.$user->id],
            'nisn' => ['nullable', 'string', 'max:255', 'unique:users,nisn,'.$user->id],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = $request->except(['password', 'password_confirmation']);
        $userData['kelas_id'] = $request->role === 'murid' ? $request->kelas_id : null;
        $user->update($userData);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route(Auth::user()->role == 'super_admin' ? 'admin.users.index' : 'manajemen.users.index')
                         ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Keamanan: Pastikan user tidak bisa menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route(Auth::user()->role == 'super_admin' ? 'admin.users.index' : 'manajemen.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}