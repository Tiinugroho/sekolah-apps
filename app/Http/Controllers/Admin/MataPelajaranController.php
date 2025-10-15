<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan daftar semua mata pelajaran.
     */
    public function index()
    {
        $mapels = MataPelajaran::get();
        return view('manajemen.mapel.index', compact('mapels'));
    }

    /**
     * Menampilkan form untuk membuat mata pelajaran baru.
     */
    public function create()
    {
        return view('manajemen.mapel.create');
    }

    /**
     * Menyimpan mata pelajaran baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel',
        ]);

        MataPelajaran::create($request->all());
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu mata pelajaran (untuk Kepala Sekolah).
     */
    public function show(MataPelajaran $mapel)
    {
        return view('manajemen.mapel.show', compact('mapel'));
    }

    /**
     * Menampilkan form untuk mengedit mata pelajaran.
     */
    public function edit(MataPelajaran $mapel)
    {
        return view('manajemen.mapel.edit', compact('mapel'));
    }

    /**
     * Memperbarui data mata pelajaran di database.
     */
    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
        ]);

        $mapel->update($request->all());
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran dari database.
     */
    public function destroy(MataPelajaran $mapel)
    {
        // Keamanan: Cek apakah mapel sudah digunakan di jadwal
        if ($mapel->jadwals()->exists()) {
            return back()->with('error', 'Mata Pelajaran tidak dapat dihapus karena sudah digunakan dalam jadwal.');
        }

        $mapel->delete();
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}