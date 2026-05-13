<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKelompok;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class JadwalKelompokController extends Controller
{
    public function store(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $kelompok->jadwals()->create($validated);

        return redirect()->route('admin.kelompok.show', $kelompok)->with('success', 'Jadwal kelompok berhasil ditambahkan.');
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
