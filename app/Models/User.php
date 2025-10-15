<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'nip', 'nisn', 'role', 'golongan', 'jabatan_guru', 'password', 'kelas_id',
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'walikelas_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'teacher_id');
    }
}