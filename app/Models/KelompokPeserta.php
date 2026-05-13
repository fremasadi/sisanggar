<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPeserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'peserta_id',
        'tanggal_masuk',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }
}
