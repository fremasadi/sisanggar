<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\SppTagihan;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index()
    {
        $tagihans = SppTagihan::where('peserta_id', auth()->id())
            ->latest('bulan_tagihan')
            ->paginate(10);

        return view('peserta.spp.index', compact('tagihans'));
    }

    public function show(SppTagihan $tagihan)
    {
        abort_unless($tagihan->peserta_id === auth()->id(), 403);

        return view('peserta.spp.show', compact('tagihan'));
    }

    public function pay(SppTagihan $tagihan, MidtransService $midtrans)
    {
        abort_unless($tagihan->peserta_id === auth()->id(), 403);

        if ($tagihan->status === 'dibayar') {
            return redirect()->route('peserta.spp.show', $tagihan)->with('success', 'Tagihan ini sudah dibayar.');
        }

        if (!$tagihan->order_id || in_array($tagihan->status, ['gagal', 'dibatalkan'], true)) {
            $tagihan->forceFill([
                'order_id' => 'SPP-' . $tagihan->id . '-' . now()->format('YmdHis'),
                'status' => 'menunggu',
            ])->save();
        }

        $user = auth()->user();
        $result = $midtrans->createTransaction(
            $tagihan->order_id,
            (int) $tagihan->nominal,
            [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp ?? '08123456789',
            ],
            [[
                'id' => 'SPP-' . $tagihan->id,
                'price' => (int) $tagihan->nominal,
                'quantity' => 1,
                'name' => 'SPP ' . $tagihan->bulan_tagihan->translatedFormat('F Y'),
            ]]
        );

        if (!($result['success'] ?? false)) {
            return back()->with('error', 'Gagal membuat pembayaran SPP: ' . ($result['message'] ?? 'Unknown error'));
        }

        $tagihan->update([
            'payment_token' => $result['snap_token'],
            'midtrans_response' => $result,
        ]);

        return redirect()->route('peserta.spp.show', $tagihan)->with('success', 'Token pembayaran berhasil dibuat.');
    }
}
