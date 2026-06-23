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
            'hasil' => 'required|in:menunggu,lulus,mengulang,tidak_lulus',
            'catatan' => 'nullable|string',
        ]);

        // Cegah pengguna memasukkan nilai jika statusnya 'menunggu'
        if ($request->hasil === 'menunggu' && $request->filled('nilai')) {
            return back()->with('error', 'Untuk status Menunggu, nilai harus dikosongkan.');
        }

        // Tentukan aturan validasi rentang nilai
        $aturanNilai = 'nullable';
        if ($request->hasil === 'lulus') {
            $aturanNilai = 'required|integer|min:72|max:100';
        } elseif ($request->hasil === 'mengulang') {
            $aturanNilai = 'required|integer|min:60|max:71';
        } elseif ($request->hasil === 'tidak_lulus') {
            $aturanNilai = 'required|integer|min:0|max:59';
        }

        $validatedNilai = $request->validate([
            'nilai' => $aturanNilai,
        ], [
            'nilai.required' => 'Nilai wajib diisi untuk status ujian yang dipilih.',
            'nilai.min' => 'Angka yang dimasukkan kurang dari batas minimal status terpilih.',
            'nilai.max' => 'Angka yang dimasukkan melebihi batas maksimal status terpilih.',
        ]);

        // Paksa nilai menjadi null jika statusnya menunggu
        if ($request->hasil === 'menunggu') {
            $validatedNilai['nilai'] = null;
        }

        $hasil->update(array_merge($validated, $validatedNilai));

        return back()->with('success', 'Nilai ujian peserta berhasil diperbarui.');
    }
}