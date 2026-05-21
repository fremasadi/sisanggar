<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\HasilUjianKelompok;
use Illuminate\Http\Request;

class HasilUjianKelompokController extends Controller
{
    public function update(Request $request, HasilUjianKelompok $hasil)
    {
        $hasil->loadMissing('ujianKelompok.kelompok');

        abort_unless($hasil->ujianKelompok?->kelompok?->pelatih_id === auth()->id(), 403, 'Anda tidak berhak mengubah nilai ujian ini.');

        $validated = $request->validate([
            'hasil' => 'required|in:menunggu,lulus,tidak_lulus',
            'nilai' => 'nullable|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $hasil->update($validated);

        return back()->with('success', 'Nilai ujian peserta berhasil diperbarui.');
    }
}
