<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'walikelas_id'];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'walikelas_id');
    }

    public function students()
    {
        return $this->hasMany(User::class, 'kelas_id')->where('role', 'murid');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}