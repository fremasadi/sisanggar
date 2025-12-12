<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingKostum extends Model
{
    use HasFactory;

    protected $table = 'booking_kostums';

    protected $fillable = [
        'id_pengunjung',
        'order_id',
        'tanggal_booking',
        'tanggal_pengambilan',
        'tanggal_pengembalian',
        'status',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_booking' => 'datetime',
        'tanggal_pengambilan' => 'date',
        'tanggal_pengembalian' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Relasi ke kostum (untuk backward compatibility)
     * Gunakan booking details untuk data lengkap
     */
    public function kostum()
    {
        return $this->belongsTo(Kostum::class, 'id_kostum');
    }

    /**
     * Relasi ke user (pengunjung)
     */
    public function pengunjung()
    {
        return $this->belongsTo(User::class, 'id_pengunjung');
    }

    /**
     * Relasi ke booking details (multiple items)
     */
    public function details()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id');
    }

    /**
     * Relasi ke pembayaran (via order_id)
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'order_id', 'order_id');
    }

    /**
     * Get all items/kostum dalam booking ini
     */
    public function items()
    {
        return $this->details()->with('kostum');
    }

    /**
     * Get total quantity semua item
     */
    public function getTotalItemsAttribute()
    {
        return $this->details->sum('quantity');
    }

    /**
     * Check if booking is paid
     */
    public function isPaid()
    {
        return in_array($this->status, ['dibayar', 'diambil', 'selesai']);
    }
}