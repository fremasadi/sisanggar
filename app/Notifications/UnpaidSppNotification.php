<?php

namespace App\Notifications;

use App\Models\SppTagihan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnpaidSppNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly SppTagihan $tagihan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Tagihan SPP Belum Dibayar',
            'message' => $this->message(),
            'spp_tagihan_id' => $this->tagihan->id,
            'bulan_tagihan' => $this->tagihan->bulan_tagihan?->format('Y-m'),
            'nominal' => $this->tagihan->nominal,
        ];
    }

    public function message(): string
    {
        $bulan = $this->tagihan->bulan_tagihan?->translatedFormat('F Y') ?? 'bulan ini';
        $nominal = number_format($this->tagihan->nominal, 0, ',', '.');
        return "Tagihan SPP Anda untuk bulan {$bulan} sebesar Rp {$nominal} belum dibayar. Harap segera melunasinya.";
    }
}

