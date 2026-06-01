<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelompok',
        'jalur_tingkatan',
        'tingkat_nomor',
        'level_urutan',
        'pelatih_id',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'tingkat_nomor' => 'integer',
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

    public function liburs()
    {
        return $this->hasMany(LiburKelompok::class)->latest('tanggal');
    }

    public function liburAktifs()
    {
        return $this->hasMany(LiburKelompok::class)
            ->where('status', 'aktif')
            ->orderBy('tanggal');
    }

    public function ujians()
    {
        return $this->hasMany(UjianKelompok::class)->latest('tanggal_ujian');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class)->latest('tanggal_presensi');
    }

    public function nextKelompokInTrack()
    {
        if (!$this->jalur_tingkatan || !$this->tingkat_nomor) {
            return null;
        }

        return static::query()
            ->where('jalur_tingkatan', $this->jalur_tingkatan)
            ->where('tingkat_nomor', $this->tingkat_nomor + 1)
            ->where('status_aktif', true)
            ->orderBy('level_urutan')
            ->first();
    }

    public function getLabelTingkatanAttribute(): string
    {
        if ($this->jalur_tingkatan && $this->tingkat_nomor) {
            return trim($this->jalur_tingkatan . ' ' . $this->tingkat_nomor);
        }

        if ($this->jalur_tingkatan) {
            return $this->jalur_tingkatan;
        }

        return $this->nama_kelompok;
    }
}
