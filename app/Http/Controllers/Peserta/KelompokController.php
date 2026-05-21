<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\KelompokPeserta;

class KelompokController extends Controller
{
    public function show()
    {
        $anggota = KelompokPeserta::with([
            'kelompok.pelatih',
            'kelompok.jadwals',
            'kelompok.presensis',
            'kelompok.ujians.kelompokTujuan',
            'presensiDetails.presensi',
        ])
            ->where('peserta_id', auth()->id())
            ->where('status', 'aktif')
            ->latest('tanggal_masuk')
            ->first();

        return view('peserta.kelompok.show', compact('anggota'));
    }
}
