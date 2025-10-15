<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $fillable = ['kelas_id', 'teacher_id', 'mata_pelajaran_id', 'kktp', 'tahun_ajaran', 'semester','hari','jam_mulai','jam_selesai'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function lingkupMateris() { return $this->hasMany(LingkupMateri::class); }
}