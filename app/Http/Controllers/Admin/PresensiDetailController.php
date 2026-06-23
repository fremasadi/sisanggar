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
    public function updateBulk(Request $request, \App\Models\Presensi $presensi)
    {
        $validated = $request->validate([
            'details' => 'required|array',
            'details.*.id' => 'required|exists:presensi_details,id',
            'details.*.status_kehadiran' => 'required|in:hadir,izin,sakit,alpa',
            'details.*.catatan' => 'nullable|string',
        ]);

        foreach ($validated['details'] as $detailData) {
            PresensiDetail::where('id', $detailData['id'])
                ->where('presensi_id', $presensi->id)
                ->update([
                    'status_kehadiran' => $detailData['status_kehadiran'],
                    'catatan' => $detailData['catatan'],
                ]);
        }

        return back()->with('success', 'Semua status presensi berhasil diperbarui.');
    }
}
