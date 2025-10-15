<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua kelas.
     */
    public function index()
    {
        // PERBAIKAN 1: Menggunakan withCount untuk efisiensi.
        $kelasList = Kelas::with('waliKelas')->withCount('students')->latest()->paginate(10);
        return view('admin.kelas.index', compact('kelasList'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('admin.kelas.create', compact('gurus'));
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
        
        // Redirect dinamis berdasarkan peran
        $routeName = Auth::user()->role == 'super_admin' ? 'admin.kelas.index' : 'manajemen.kelas.index';
        return redirect()->route($routeName)->with('success', 'Kelas berhasil dibuat.');
    }

    /**
     * Menampilkan detail satu kelas.
     */
    public function show(Kelas $kela)
    {
        // load() sudah benar untuk halaman detail
        $kela->load('students', 'waliKelas');
        return view('admin.kelas.show', compact('kela'));
    }
    
    /**
     * Menampilkan form untuk mengedit kelas.
     */
    public function edit(Kelas $kela)
    {
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        return view('admin.kelas.edit', compact('kela', 'gurus'));
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
        
        $routeName = Auth::user()->role == 'super_admin' ? 'admin.kelas.index' : 'manajemen.kelas.index';
        return redirect()->route($routeName)->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas dari database.
     */
    public function destroy(Kelas $kela)
    {
        // PERBAIKAN 2: Pengecekan keamanan sebelum menghapus.
        if ($kela->students()->count() > 0) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.');
        }

        $kela->delete();
        
        $routeName = Auth::user()->role == 'super_admin' ? 'admin.kelas.index' : 'manajemen.kelas.index';
        return redirect()->route($routeName)->with('success', 'Kelas berhasil dihapus.');
    }
}