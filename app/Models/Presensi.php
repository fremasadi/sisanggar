<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'tanggal_presensi',
        'judul_pertemuan',
        'materi',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_presensi' => 'date',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function details()
    {
        return $this->hasMany(PresensiDetail::class)->with('peserta');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
