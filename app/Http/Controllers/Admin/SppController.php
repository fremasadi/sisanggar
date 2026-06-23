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
            // GANTI updateOrCreate MENJADI firstOrCreate
            $tagihan = SppTagihan::firstOrCreate(
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
                ? "Tagihan SPP berhasil dibuat untuk {$generated} peserta baru."
                : 'Semua tagihan bulan tersebut sudah tersedia.');
    }

    public function notify(Request $request)
    {
        $validated = $request->validate([
            'bulan_tagihan' => 'required|date_format:Y-m',
        ]);

        $monthDate = Carbon::createFromFormat('Y-m', $validated['bulan_tagihan'])->startOfMonth();

        $tagihans = SppTagihan::with('peserta')
            ->whereDate('bulan_tagihan', $monthDate)
            ->where('status', 'menunggu')
            ->get();

        if ($tagihans->isEmpty()) {
            return back()->with('error', 'Tidak ada tagihan yang menunggu pembayaran untuk bulan ini.');
        }

        $targets = [];
        foreach ($tagihans as $tagihan) {
            $peserta = $tagihan->peserta;
            if (!empty($peserta->no_hp)) {
                $targets[] = $peserta->no_hp . '|' . $peserta->name;
            }
        }

        if (empty($targets)) {
            return back()->with('error', 'Tidak ada peserta dengan nomor HP yang valid untuk dikirimkan notifikasi.');
        }

        $bulan = $monthDate->translatedFormat('F Y');
        $message = "Halo {name},\n\nIni adalah pengingat dari *Sanggar Tari* bahwa tagihan SPP Anda untuk bulan *{$bulan}* sebesar *Rp 75.000* telah mendekati tenggat waktu pembayaran.\n\nSilakan segera login ke aplikasi untuk melakukan pembayaran.\n\nTerima kasih dan salam budaya!";

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post(env('FONNTE_ENDPOINT', 'https://api.fonnte.com/send'), [
            'target' => implode(',', $targets),
            'message' => $message,
            'countryCode' => '62',
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['status']) && $responseData['status'] == true) {
                return back()->with('success', 'Notifikasi deadline berhasil dikirimkan ke ' . count($targets) . ' peserta melalui WhatsApp.');
            }
        }

        return back()->with('error', 'Gagal mengirim notifikasi. Pastikan Fonnte sudah dikonfigurasi dengan benar di environment.');
    }
}
