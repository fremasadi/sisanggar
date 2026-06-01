<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiburKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'jadwal_kelompok_id',
        'tanggal',
        'judul',
        'alasan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKelompok::class, 'jadwal_kelompok_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
