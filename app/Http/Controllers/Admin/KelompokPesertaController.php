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
            'peserta_ids' => 'required|array|min:1',
            'peserta_ids.*' => 'exists:users,id',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'catatan' => 'nullable|string',
        ]);

        $existing = KelompokPeserta::with('peserta')
            ->whereIn('peserta_id', $validated['peserta_ids'])
            ->where('status', 'aktif')
            ->get();

        if ($existing->isNotEmpty()) {
            $names = $existing
                ->pluck('peserta.name')
                ->filter()
                ->implode(', ');

            return back()->withInput()->with(
                'error',
                'Peserta berikut masih terdaftar aktif di kelompok lain: ' . $names
            );
        }

        $payload = collect($validated['peserta_ids'])
            ->unique()
            ->map(fn ($pesertaId) => [
                'peserta_id' => $pesertaId,
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

        $kelompok->anggota()->createMany($payload->all());

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
