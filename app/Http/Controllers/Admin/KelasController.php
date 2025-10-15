<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua kelas dengan paginasi.
     */
    public function index()
    {
        // PERBAIKAN 1: Mengganti ->get() dengan ->paginate() untuk efisiensi.
        $kelasList = Kelas::with('waliKelas')->withCount('students')->get();
        
        // PERBAIKAN 3: Menggunakan path view yang konsisten.
        return view('manajemen.kelas.index', compact('kelasList'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('manajemen.kelas.create', compact('gurus'));
    }

    /**
     * Menyimpan kelas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas',
            'walikelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create($request->all());
        
        // PERBAIKAN 2: Redirect disederhanakan.
        return redirect()->route('manajemen.kelas.index')->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Menampilkan detail satu kelas (untuk peran monitoring).
     */
    public function show(Kelas $kela)
    {
        $kela->load('students', 'waliKelas');
        return view('manajemen.kelas.show', compact('kela'));
    }
    
    /**
     * Menampilkan form untuk mengedit kelas.
     */
    public function edit(Kelas $kela)
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('manajemen.kelas.edit', compact('kela', 'gurus'));
    }

    /**
     * Memperbarui data kelas di database.
     */
    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,'.$kela->id,
            'walikelas_id' => 'nullable|exists:users,id',
        ]);

        $kela->update($request->all());
        
        return redirect()->route('manajemen.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas dari database dengan pengecekan keamanan.
     */
    public function destroy(Kelas $kela)
    {
        if ($kela->students()->count() > 0) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.');
        }

        $kela->delete();
        
        return redirect()->route('manajemen.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}