<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $kelompoks = Kelompok::withCount(['anggota', 'jadwals', 'ujians', 'presensis'])
            ->where('pelatih_id', auth()->id())
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama_kelompok', 'like', '%' . $request->search . '%');
            })
            ->orderBy('level_urutan')
            ->paginate(10);

        return view('pelatih.kelompok.index', compact('kelompoks'));
    }

    public function show(Kelompok $kelompok)
    {
        $this->authorizeKelompok($kelompok);

        $kelompok->load([
            'anggota.peserta',
        ]);

        return view('pelatih.kelompok.show', compact('kelompok'));
    }

    private function authorizeKelompok(Kelompok $kelompok): void
    {
        abort_unless($kelompok->pelatih_id === auth()->id(), 403, 'Anda tidak berhak mengakses kelompok ini.');
    }
}
