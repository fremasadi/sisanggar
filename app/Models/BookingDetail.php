<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $table = 'booking_details';

    protected $fillable = [
        'booking_id',
        'kostum_id',
        'quantity',
        'harga_sewa',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'harga_sewa' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke BookingKostum
     */
    public function booking()
    {
        return $this->belongsTo(BookingKostum::class, 'booking_id');
    }

    /**
     * Relasi ke Kostum
     */
    public function kostum()
    {
        return $this->belongsTo(Kostum::class, 'kostum_id');
    }
}