<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\PresensiDetail;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $presensis = PresensiDetail::with(['presensi.kelompok'])
            ->where('peserta_id', auth()->id())
            ->when($request->filled('status_kehadiran'), function ($query) use ($request) {
                $query->where('status_kehadiran', $request->status_kehadiran);
            })
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $query->whereHas('presensi', function ($presensiQuery) use ($request) {
                    $presensiQuery->whereMonth('tanggal_presensi', date('m', strtotime($request->bulan . '-01')))
                        ->whereYear('tanggal_presensi', date('Y', strtotime($request->bulan . '-01')));
                });
            })
            ->whereHas('presensi')
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('peserta.presensi.index', compact('presensis'));
    }
}
