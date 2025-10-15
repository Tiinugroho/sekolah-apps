<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = ['student_id', 'tujuan_pembelajaran_id', 'nilai'];

    /**
     * Mendefinisikan relasi many-to-one ke model User (siswa).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Mendefinisikan relasi many-to-one ke model TujuanPembelajaran.
     */
    public function tujuanPembelajaran()
    {
        return $this->belongsTo(TujuanPembelajaran::class);
    }
}