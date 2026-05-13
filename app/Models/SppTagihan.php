<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppTagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_id',
        'dibuat_oleh',
        'bulan_tagihan',
        'nominal',
        'status',
        'order_id',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'bank',
        'va_number',
        'payment_token',
        'transaction_time',
        'settlement_time',
        'paid_at',
        'midtrans_response',
        'notes',
    ];

    protected $casts = [
        'bulan_tagihan' => 'date',
        'nominal' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'paid_at' => 'datetime',
        'midtrans_response' => 'array',
    ];

    public function peserta()
    {
        return $this->belongsTo(User::class, 'peserta_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function isPaid(): bool
    {
        return $this->status === 'dibayar';
    }
}
