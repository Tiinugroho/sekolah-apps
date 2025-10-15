<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::latest()->paginate(10);
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel',
        ]);

        MataPelajaran::create($request->all());
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function show(MataPelajaran $mapel)
    {
        return view('admin.mapel.show', compact('mapel'));
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
        ]);

        $mapel->update($request->all());
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('manajemen.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}