<?php

namespace App\Notifications;

use App\Models\LiburKelompok;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LiburKelompokNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LiburKelompok $libur)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->libur->loadMissing(['kelompok', 'jadwal']);

        return [
            'title' => 'Latihan diliburkan',
            'message' => $this->message(),
            'libur_kelompok_id' => $this->libur->id,
            'kelompok_id' => $this->libur->kelompok_id,
            'kelompok' => $this->libur->kelompok?->nama_kelompok,
            'tanggal' => $this->libur->tanggal?->toDateString(),
            'status' => $this->libur->status,
        ];
    }

    public function message(): string
    {
        $this->libur->loadMissing(['kelompok', 'jadwal']);

        $tanggal = $this->libur->tanggal?->format('d/m/Y');
        $kelompok = $this->libur->kelompok?->nama_kelompok ?? 'kelompok';
        $jam = $this->libur->jadwal
            ? ' pukul ' . substr($this->libur->jadwal->jam_mulai, 0, 5) . '-' . substr($this->libur->jadwal->jam_selesai, 0, 5)
            : '';
        $alasan = $this->libur->alasan ? ' Alasan: ' . $this->libur->alasan : '';

        return "Latihan {$kelompok} pada {$tanggal}{$jam} diliburkan.{$alasan}";
    }
}
