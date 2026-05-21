<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'judul',
        'image',
        'status_aktif',
        'urutan',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
