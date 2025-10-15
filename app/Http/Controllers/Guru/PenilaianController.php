<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\LingkupMateri;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman setup untuk Lingkup Materi dan Tujuan Pembelajaran.
     */
    public function setup(Jadwal $jadwal)
    {
        // Keamanan: Pastikan guru yang mengakses adalah guru yang berhak
        if ($jadwal->teacher_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Eager load relasi untuk ditampilkan di view
        $jadwal->load('lingkupMateris.tujuanPembelajarans');

        return view('guru.penilaian.setup', compact('jadwal'));
    }

    /**
     * Menyimpan Lingkup Materi baru.
     */
    public function storeLingkup(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->teacher_id !== Auth::id()) abort(403);

        $request->validate(['nama_lingkup' => 'required|string|max:255']);

        $jadwal->lingkupMateris()->create($request->only('nama_lingkup'));

        return back()->with('success', 'Lingkup Materi berhasil ditambahkan.');
    }

    /**
     * Menghapus Lingkup Materi.
     */
    public function destroyLingkup(LingkupMateri $lingkupMateri)
    {
        // Pastikan guru yang menghapus adalah pemilik jadwal terkait
        if ($lingkupMateri->jadwal->teacher_id !== Auth::id()) abort(403);

        // Keamanan: Jangan hapus jika sudah ada TP di dalamnya
        if ($lingkupMateri->tujuanPembelajarans()->exists()) {
            return back()->with('error', 'Hapus dulu semua Tujuan Pembelajaran di dalamnya.');
        }

        $lingkupMateri->delete();
        return back()->with('success', 'Lingkup Materi berhasil dihapus.');
    }

    /**
     * Menyimpan Tujuan Pembelajaran (TP) baru.
     */
    public function storeTp(Request $request, LingkupMateri $lingkupMateri)
    {
        if ($lingkupMateri->jadwal->teacher_id !== Auth::id()) abort(403);

        $request->validate([
            'kode_tp' => 'required|string|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        $lingkupMateri->tujuanPembelajarans()->create($request->only(['kode_tp', 'deskripsi']));
        return back()->with('success', 'Tujuan Pembelajaran berhasil ditambahkan.');
    }

    /**
     * Menghapus Tujuan Pembelajaran (TP).
     */
    public function destroyTp(TujuanPembelajaran $tp)
    {
        if ($tp->lingkupMateri->jadwal->teacher_id !== Auth::id()) abort(403);
        
        // Cek jika sudah ada nilai terkait
        if (\App\Models\Nilai::where('tujuan_pembelajaran_id', $tp->id)->exists()) {
            return back()->with('error', 'TP tidak bisa dihapus karena sudah memiliki nilai.');
        }

        $tp->delete();
        return back()->with('success', 'Tujuan Pembelajaran berhasil dihapus.');
    }
}