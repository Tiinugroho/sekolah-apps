<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSumatif extends Model
{
    use HasFactory;

    protected $table = 'nilai_sumatifs';

    protected $fillable = ['student_id', 'jadwal_id', 'jenis', 'nilai'];

    /**
     * Mendefinisikan relasi many-to-one ke model User (siswa).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Mendefinisikan relasi many-to-one ke model Jadwal.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}