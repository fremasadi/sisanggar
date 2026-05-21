<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'presensi_id',
        'peserta_id',
        'status_kehadiran',
        'catatan',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}
