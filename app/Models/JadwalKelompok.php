<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'catatan',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}
