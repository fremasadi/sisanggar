<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'transaksi_id',
        'order_id',
        'gross_amount',
        'transaction_id',
        'transaction_status',
        'transaction_time',
        'settlement_time',
        'payment_type',
        'fraud_status',
        'bank',
        'va_number',
        'payment_url',
        'midtrans_response',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'midtrans_response' => 'array',
    ];

    /**
     * Relasi ke booking utama
     */
    public function booking()
    {
        return $this->belongsTo(BookingKostum::class, 'transaksi_id');
    }

    /**
     * Get all bookings dengan order_id yang sama
     */
    public function allBookings()
    {
        return BookingKostum::where('order_id', $this->order_id)
            ->with('details.kostum')
            ->get();
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess()
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'expire', 'cancel', 'failure']);
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Pembayaran Berhasil',
            'capture' => 'Pembayaran Berhasil',
            'deny' => 'Pembayaran Ditolak',
            'expire' => 'Pembayaran Kadaluarsa',
            'cancel' => 'Pembayaran Dibatalkan',
            'failure' => 'Pembayaran Gagal',
        ];

        return $labels[$this->transaction_status] ?? 'Status Tidak Diketahui';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'pending' => 'warning',
            'settlement' => 'success',
            'capture' => 'success',
            'deny' => 'danger',
            'expire' => 'secondary',
            'cancel' => 'danger',
            'failure' => 'danger',
        ];

        return $classes[$this->transaction_status] ?? 'secondary';
    }
}