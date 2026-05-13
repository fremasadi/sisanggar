<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'kelompok_tujuan_id',
        'nama_ujian',
        'tanggal_ujian',
        'jam_mulai',
        'lokasi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function kelompokTujuan()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_tujuan_id');
    }

    public function hasils()
    {
        return $this->hasMany(HasilUjianKelompok::class)->with('peserta');
    }
}
