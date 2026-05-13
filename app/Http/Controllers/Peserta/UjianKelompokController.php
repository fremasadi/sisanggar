<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\HasilUjianKelompok;

class UjianKelompokController extends Controller
{
    public function index()
    {
        $hasils = HasilUjianKelompok::with([
            'ujianKelompok.kelompok',
            'ujianKelompok.kelompokTujuan',
        ])
            ->where('peserta_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('peserta.ujian.index', compact('hasils'));
    }
}
