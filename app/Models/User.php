<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    public function pelatih()
    {
        return $this->hasOne(Pelatih::class, 'id_pelatih');
    }

    public function bookings()
    {
        return $this->hasMany(BookingKostum::class, 'id_pengunjung');
    }

    public function sppTagihans()
    {
        return $this->hasMany(SppTagihan::class, 'peserta_id');
    }

    public function kelompokPesertas()
    {
        return $this->hasMany(KelompokPeserta::class, 'peserta_id');
    }

    public function activeKelompokPeserta()
    {
        return $this->hasOne(KelompokPeserta::class, 'peserta_id')
            ->where('status', 'aktif')
            ->latestOfMany('tanggal_masuk');
    }

    public function hasilUjianKelompoks()
    {
        return $this->hasMany(HasilUjianKelompok::class, 'peserta_id');
    }

    public function presensiDetails()
    {
        return $this->hasMany(PresensiDetail::class, 'peserta_id');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'peserta_id');
    }

    public function uploadedSertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'uploaded_by');
    }
}
