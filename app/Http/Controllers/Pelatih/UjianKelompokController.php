<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\UjianKelompok;
use Illuminate\Http\Request;

class UjianKelompokController extends Controller
{
    public function index(Request $request)
    {
        $ujians = UjianKelompok::with(['kelompok', 'kelompokTujuan'])
            ->whereHas('kelompok', function ($query) {
                $query->where('pelatih_id', auth()->id());
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('nama_ujian', 'like', '%' . $request->search . '%')
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($request) {
                            $kelompokQuery->where('nama_kelompok', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest('tanggal_ujian')
            ->paginate(10);

        return view('pelatih.ujian.index', compact('ujians'));
    }

    public function show(UjianKelompok $ujian)
    {
        $this->authorizeUjian($ujian);

        $ujian->load(['kelompok', 'kelompokTujuan', 'hasils.peserta']);

        return view('pelatih.ujian.show', compact('ujian'));
    }

    private function authorizeUjian(UjianKelompok $ujian): void
    {
        $ujian->loadMissing('kelompok');
        abort_unless($ujian->kelompok?->pelatih_id === auth()->id(), 403, 'Anda tidak berhak mengakses ujian ini.');
    }
}
