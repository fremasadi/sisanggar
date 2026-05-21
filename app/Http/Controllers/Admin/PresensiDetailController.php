<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiDetail;
use Illuminate\Http\Request;

class PresensiDetailController extends Controller
{
    public function update(Request $request, PresensiDetail $detail)
    {
        $validated = $request->validate([
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        $detail->update($validated);

        return back()->with('success', 'Status presensi peserta berhasil diperbarui.');
    }
}
