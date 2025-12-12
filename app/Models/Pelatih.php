<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelatih'; // PK bukan id biasa
    public $incrementing = false; // karena id_pelatih = FK dari users.id
    protected $keyType = 'int';

    protected $fillable = [
        'id_pelatih',
        'bidang_tari',
        'jadwal_tetap',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelatih');
    }
}
