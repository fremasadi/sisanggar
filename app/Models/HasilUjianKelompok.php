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

    public const HASIL_OPTIONS = [
        'menunggu' => 'Menunggu',
        'lulus' => 'Lulus - Nilai minimal kelulusan adalah 72',
        'mengulang' => 'Mengulang - Peserta dengan nilai 60-71 wajib mengulang',
        'tidak_lulus' => 'Tidak Lulus - Peserta dengan nilai 0-59 dinyatakan tidak lulus',
    ];

    public const HASIL_LABELS = [
        'menunggu' => 'Menunggu',
        'lulus' => 'Lulus',
        'mengulang' => 'Mengulang',
        'tidak_lulus' => 'Tidak Lulus',
    ];

    public function ujianKelompok()
    {
        return $this->belongsTo(UjianKelompok::class);
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    public function getHasilLabelAttribute(): string
    {
        return self::HASIL_LABELS[$this->hasil] ?? ucfirst((string) $this->hasil);
    }
}
