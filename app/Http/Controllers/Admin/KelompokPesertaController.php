<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\KelompokPeserta;
use Illuminate\Http\Request;

class KelompokPesertaController extends Controller
{
    public function store(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:users,id',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'catatan' => 'nullable|string',
        ]);

        $existing = KelompokPeserta::where('peserta_id', $validated['peserta_id'])
            ->where('status', 'aktif')
            ->first();

        if ($existing) {
            return back()->with('error', 'Peserta tersebut masih terdaftar aktif di kelompok lain.');
        }

        $kelompok->anggota()->create($validated);

        return redirect()->route('admin.kelompok.show', $kelompok)->with('success', 'Peserta berhasil ditambahkan ke kelompok.');
    }

    public function update(Request $request, KelompokPeserta $anggota)
    {
        $validated = $request->validate([
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'catatan' => 'nullable|string',
        ]);

        $anggota->update($validated);

        return back()->with('success', 'Status anggota kelompok berhasil diperbarui.');
    }

    public function destroy(KelompokPeserta $anggota)
    {
        $anggota->delete();

        return back()->with('success', 'Anggota kelompok berhasil dihapus.');
    }
}
