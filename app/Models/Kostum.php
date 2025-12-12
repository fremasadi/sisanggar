<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kostum extends Model
{
    use HasFactory;

    protected $table = 'kostums';

    protected $fillable = [
        'nama_kostum',
        'ukuran',
        'harga_sewa',
        'stok',
        'status',
        'image',
    ];
}
