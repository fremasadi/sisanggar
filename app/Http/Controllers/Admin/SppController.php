<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppTagihan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('bulan_tagihan', now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

        $tagihans = SppTagihan::with('peserta')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('peserta', function ($pesertaQuery) use ($request) {
                    $pesertaQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->whereDate('bulan_tagihan', $monthDate)
            ->latest()
            ->paginate(10);

        $pesertaCount = User::where('role', 'peserta')->where('status_aktif', true)->count();

        return view('admin.spp.index', compact('tagihans', 'selectedMonth', 'monthDate', 'pesertaCount'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'bulan_tagihan' => 'required|date_format:Y-m',
        ]);

        $monthDate = Carbon::createFromFormat('Y-m', $validated['bulan_tagihan'])->startOfMonth();
        $pesertas = User::where('role', 'peserta')->where('status_aktif', true)->get();
        $generated = 0;

        foreach ($pesertas as $peserta) {
            $tagihan = SppTagihan::updateOrCreate(
                [
                    'peserta_id' => $peserta->id,
                    'bulan_tagihan' => $monthDate->toDateString(),
                ],
                [
                    'dibuat_oleh' => auth()->id(),
                    'nominal' => 75000,
                    'status' => 'menunggu',
                ]
            );

            if ($tagihan->wasRecentlyCreated) {
                $generated++;
            }
        }

        return redirect()->route('admin.spp.index', ['bulan_tagihan' => $monthDate->format('Y-m')])
            ->with('success', $generated > 0
                ? "Tagihan SPP berhasil dibuat untuk {$generated} peserta."
                : 'Semua tagihan bulan tersebut sudah tersedia.');
    }
}
