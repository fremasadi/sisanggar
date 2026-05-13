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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelatih');
    }

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class, 'pelatih_id', 'id_pelatih');
    }
}
