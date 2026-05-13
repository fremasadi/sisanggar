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
            'kelompok.ujians.kelompokTujuan',
        ])
            ->where('peserta_id', auth()->id())
            ->where('status', 'aktif')
            ->latest('tanggal_masuk')
            ->first();

        return view('peserta.kelompok.show', compact('anggota'));
    }
}
