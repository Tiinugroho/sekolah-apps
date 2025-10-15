<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * @var string
     */
    protected $table = 'mata_pelajarans';

    /**
     * Atribut yang dapat diisi secara massal.
     * @var array
     */
    protected $fillable = ['nama_mapel', 'kode_mapel'];

    /**
     * Mendefinisikan relasi one-to-many ke model Jadwal.
     * Satu mata pelajaran bisa ada di banyak jadwal.
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}