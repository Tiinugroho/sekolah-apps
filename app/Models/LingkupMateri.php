<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LingkupMateri extends Model
{
    use HasFactory;
    protected $fillable = ['jadwal_id', 'nama_lingkup', 'urutan'];

    public function jadwal() { return $this->belongsTo(Jadwal::class); }
    public function tujuanPembelajarans() { return $this->hasMany(TujuanPembelajaran::class); }
}