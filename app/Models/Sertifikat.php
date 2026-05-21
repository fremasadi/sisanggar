<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_id',
        'uploaded_by',
        'nama_sertifikat',
        'tanggal_terbit',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'catatan',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'file_size' => 'integer',
    ];

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
