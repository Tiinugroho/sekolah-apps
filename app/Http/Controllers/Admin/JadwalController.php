<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    private $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function index()
    {
        $jadwals = Jadwal::with('kelas', 'teacher', 'mataPelajaran')->latest()->paginate(10);
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $data = [
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'gurus' => User::where('role', 'guru')->orderBy('name')->get(),
            'mapels' => MataPelajaran::orderBy('nama_mapel')->get(),
            'days' => $this->days,
        ];
        return view('admin.jadwal.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'teacher_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_ajaran' => 'required|string|max:9',
            'semester' => 'required|integer|in:1,2',
            'kktp' => 'required|integer|min:0|max:100',
            'hari' => 'required|in:' . implode(',', $this->days),
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // LOGIKA ANTI-TABRAKAN
        if ($this->isScheduleConflict($validated)) {
            return back()->withInput()->with('error', 'Jadwal bertabrakan! Guru atau Kelas sudah memiliki jadwal di waktu yang sama.');
        }

        Jadwal::create($validated);
        return redirect()->route('manajemen.jadwal.index')->with('success', 'Jadwal berhasil dibuat.');
    }
    
    public function show(Jadwal $jadwal)
    {
        $jadwal->load('kelas', 'teacher', 'mataPelajaran');
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $data = [
            'jadwal' => $jadwal,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'gurus' => User::where('role', 'guru')->orderBy('name')->get(),
            'mapels' => MataPelajaran::orderBy('nama_mapel')->get(),
            'days' => $this->days,
        ];
        return view('admin.jadwal.edit', $data);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            // ... (validasi sama seperti di store)
        ]);

        // LOGIKA ANTI-TABRAKAN (dengan pengecualian untuk jadwal yang sedang diedit)
        if ($this->isScheduleConflict($validated, $jadwal->id)) {
            return back()->withInput()->with('error', 'Jadwal bertabrakan! Guru atau Kelas sudah memiliki jadwal di waktu yang sama.');
        }

        $jadwal->update($validated);
        return redirect()->route('manajemen.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        if ($jadwal->lingkupMateris()->exists()) {
             return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki data penilaian.');
        }
        $jadwal->delete();
        return redirect()->route('manajemen.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Helper function untuk memeriksa tabrakan jadwal.
     */
    private function isScheduleConflict(array $data, int $exceptId = null): bool
    {
        $query = Jadwal::where('hari', $data['hari'])
            ->where(function ($q) use ($data) {
                $q->where('jam_mulai', '<', $data['jam_selesai'])
                  ->where('jam_selesai', '>', $data['jam_mulai']);
            })
            ->where(function ($q) use ($data) {
                $q->where('teacher_id', $data['teacher_id'])
                  ->orWhere('kelas_id', $data['kelas_id']);
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}