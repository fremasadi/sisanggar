<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\PresensiDetail;
use Illuminate\Http\Request;

class PresensiDetailController extends Controller
{
    public function update(Request $request, PresensiDetail $detail)
    {
        $detail->loadMissing('presensi.kelompok');

        abort_unless($detail->presensi?->kelompok?->pelatih_id === auth()->id(), 403, 'Anda tidak berhak mengubah presensi ini.');

        $validated = $request->validate([
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        $detail->update($validated);

        return back()->with('success', 'Presensi peserta berhasil diperbarui.');
    }
}
