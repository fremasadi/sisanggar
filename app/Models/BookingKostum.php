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
        'nama_pemesan',
        'no_hp_pemesan',
        'no_hp_pemesan_normalized',
        'tipe_booking',
        'verification_status',
        'verified_at',
        'verified_by',
        'verification_notes',
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
        'verified_at' => 'datetime',
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

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
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

    public function getPemesanNamaAttribute()
    {
        return $this->pengunjung->name ?? $this->nama_pemesan ?? '-';
    }

    public function getPemesanNoHpAttribute()
    {
        return $this->pengunjung->no_hp ?? $this->no_hp_pemesan ?? '-';
    }
}
