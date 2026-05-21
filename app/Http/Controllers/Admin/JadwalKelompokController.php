<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKelompok;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class JadwalKelompokController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = JadwalKelompok::with('kelompok')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('hari', 'like', '%' . $request->search . '%')
                        ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($request) {
                            $kelompokQuery->where('nama_kelompok', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->when($request->filled('kelompok_id'), function ($query) use ($request) {
                $query->where('kelompok_id', $request->kelompok_id);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        $kelompoks = Kelompok::where('status_aktif', true)->orderBy('level_urutan')->orderBy('nama_kelompok')->get();

        return view('admin.jadwal-kelompok.index', compact('jadwals', 'kelompoks'));
    }

    public function store(Request $request, Kelompok $kelompok)
    {
        $this->createJadwal($request, $kelompok);

        return redirect()->route('admin.jadwal-kelompok.index')->with('success', 'Jadwal kelompok berhasil ditambahkan.');
    }

    public function storeFromIndex(Request $request)
    {
        $validated = $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id',
        ]);

        $kelompok = Kelompok::findOrFail($validated['kelompok_id']);
        $this->createJadwal($request, $kelompok);

        return redirect()->route('admin.jadwal-kelompok.index')->with('success', 'Jadwal kelompok berhasil ditambahkan.');
    }

    private function createJadwal(Request $request, Kelompok $kelompok): JadwalKelompok
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        return $kelompok->jadwals()->create($validated);
    }

    public function update(Request $request, JadwalKelompok $jadwal)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($validated);

        return back()->with('success', 'Jadwal kelompok berhasil diperbarui.');
    }

    public function destroy(JadwalKelompok $jadwal)
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal kelompok berhasil dihapus.');
    }
}
