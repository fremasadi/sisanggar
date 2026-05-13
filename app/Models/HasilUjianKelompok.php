<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjianKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_kelompok_id',
        'peserta_id',
        'hasil',
        'nilai',
        'catatan',
        'promoted_at',
    ];

    protected $casts = [
        'promoted_at' => 'datetime',
    ];

    public function ujianKelompok()
    {
        return $this->belongsTo(UjianKelompok::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}
