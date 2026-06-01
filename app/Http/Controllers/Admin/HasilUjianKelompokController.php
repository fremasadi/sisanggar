<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilUjianKelompok;
use Illuminate\Http\Request;

class HasilUjianKelompokController extends Controller
{
    public function update(Request $request, HasilUjianKelompok $hasil)
    {
        $validated = $request->validate([
            'hasil' => 'required|in:menunggu,lulus,mengulang,tidak_lulus',
            'nilai' => 'nullable|integer|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $hasil->update($validated);

        return back()->with('success', 'Hasil ujian berhasil diperbarui.');
    }
}
