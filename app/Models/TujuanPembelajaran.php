<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    use HasFactory;
    protected $fillable = ['lingkup_materi_id', 'kode_tp', 'deskripsi'];

    public function lingkupMateri() { return $this->belongsTo(LingkupMateri::class); }
}