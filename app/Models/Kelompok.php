<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelompok',
        'level_urutan',
        'pelatih_id',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function pelatih()
    {
        return $this->belongsTo(User::class, 'pelatih_id');
    }

    public function anggota()
    {
        return $this->hasMany(KelompokPeserta::class);
    }

    public function jadwals()
    {
        return $this->hasMany(JadwalKelompok::class)->orderBy('hari')->orderBy('jam_mulai');
    }

    public function ujians()
    {
        return $this->hasMany(UjianKelompok::class)->latest('tanggal_ujian');
    }
}
