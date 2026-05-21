<?php

namespace App\Http\Controllers;

use App\Models\BookingDetail;
use App\Models\BookingKostum;
use App\Models\Pembayaran;
use App\Models\SppTagihan;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_pengambilan',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();

        try {
            $tanggalAmbil = Carbon::parse($request->tanggal_pengambilan);
            $tanggalKembali = Carbon::parse($request->tanggal_pengembalian);
            $durasi = $tanggalAmbil->diffInDays($tanggalKembali);
            $orderId = 'KOSTUM-' . now()->format('YmdHis') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
            $booking = BookingKostum::create([
                'id_pengunjung' => Auth::id(),
                'order_id' => $orderId,
                'nama_pemesan' => Auth::user()->name,
                'no_hp_pemesan' => Auth::user()->no_hp,
                'no_hp_pemesan_normalized' => $this->normalizePhone(Auth::user()->no_hp),
                'tipe_booking' => 'online',
                'verification_status' => 'confirmed',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'tanggal_booking' => now(),
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status' => 'menunggu',
                'total_biaya' => $request->total_biaya,
            ]);

            foreach ($cart as $item) {
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'kostum_id' => $item['kostum_id'],
                    'quantity' => $item['quantity'],
                    'harga_sewa' => $item['harga_sewa'],
                'subtotal' => $item['harga_sewa'] * $item['quantity'],
                ]);
            }

            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->no_hp ?? '08123456789',
            ];

            $itemDetails = [];
            foreach ($cart as $item) {
                $itemDetails[] = [
                    'id' => $item['kostum_id'],
                    'price' => (int) (($item['harga_sewa'] * $item['quantity']) * $durasi),
                    'quantity' => 1,
                    'name' => 'Sewa ' . $item['nama_kostum'] . ' (' . $item['ukuran'] . ') - ' . $item['quantity'] . ' pcs x ' . $durasi . ' hari',
                ];
            }

            $result = $this->midtrans->createTransaction(
                $orderId,
                (int) $request->total_biaya,
                $customerDetails,
                $itemDetails
            );

            if (!($result['success'] ?? false)) {
                DB::rollBack();

                return back()->with('error', 'Gagal membuat pembayaran: ' . ($result['message'] ?? 'Unknown error'));
            }

            Pembayaran::create([
                'transaksi_id' => $booking->id,
                'order_id' => $orderId,
                'gross_amount' => $request->total_biaya,
                'transaction_status' => 'pending',
                'payment_url' => $result['snap_token'],
                'midtrans_response' => $result,
            ]);

            DB::commit();

            session()->forget([
                'cart',
                'tanggal_pengambilan',
                'tanggal_pengembalian',
                'durasi',
                'total_biaya',
            ]);

            return redirect()->route('payment.show', $booking)->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment checkout error', ['message' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(BookingKostum $booking)
    {
        if ($booking->id_pengunjung !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['details.kostum', 'pembayaran']);
        $pembayaran = $booking->pembayaran;

        if (!$pembayaran) {
            return redirect()->route('cart.index')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);

        if ($statusResult['success'] ?? false) {
            $this->updatePaymentStatus($pembayaran, $statusResult['data']);
            $pembayaran->refresh();
            $booking->refresh();
        }

        return view('front.payment.show', compact('booking', 'pembayaran'));
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        $statusResult = $this->midtrans->getTransactionStatus($orderId);
        $statusPayload = ($statusResult['success'] ?? false) ? $statusResult['data'] : ['order_id' => $orderId];

        if ($pembayaran = Pembayaran::where('order_id', $orderId)->first()) {
            if ($statusResult['success'] ?? false) {
                $this->updatePaymentStatus($pembayaran, $statusPayload);
            }

            return redirect()->route('payment.show', $pembayaran->transaksi_id);
        }

        if ($tagihan = SppTagihan::where('order_id', $orderId)->first()) {
            if ($statusResult['success'] ?? false) {
                $this->updateSppStatus($tagihan, $statusPayload);
            }

            return redirect()->route('peserta.spp.show', $tagihan)->with('success', 'Status pembayaran SPP berhasil diperbarui.');
        }

        return redirect()->route('home')->with('error', 'Pembayaran tidak ditemukan.');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('home')->with('error', 'Pembayaran belum diselesaikan.');
    }

    public function error(Request $request)
    {
        return redirect()->route('home')->with('error', 'Terjadi masalah saat proses pembayaran.');
    }

    public function callback(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Invalid Midtrans signature', ['order_id' => $request->order_id]);

                return response()->json(['message' => 'Invalid signature'], 403);
            }

            if ($pembayaran = Pembayaran::where('order_id', $request->order_id)->first()) {
                $this->updatePaymentStatus($pembayaran, $request->all());

                return response()->json(['message' => 'Booking callback processed']);
            }

            if ($tagihan = SppTagihan::where('order_id', $request->order_id)->first()) {
                $this->updateSppStatus($tagihan, $request->all());

                return response()->json(['message' => 'SPP callback processed']);
            }

            Log::error('Payment not found for callback', ['order_id' => $request->order_id]);

            return response()->json(['message' => 'Payment not found'], 404);
        } catch (\Throwable $e) {
            Log::error('Midtrans callback error', [
                'order_id' => $request->order_id,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function checkStatus(BookingKostum $booking)
    {
        if ($booking->id_pengunjung !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pembayaran = $booking->pembayaran;

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan',
            ], 404);
        }

        $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);

        if ($statusResult['success'] ?? false) {
            $this->updatePaymentStatus($pembayaran, $statusResult['data']);
            $pembayaran->refresh();

            return response()->json([
                'success' => true,
                'status' => $pembayaran->transaction_status,
                'message' => $pembayaran->getStatusLabel(),
                'is_paid' => $pembayaran->isSuccess(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengecek status: ' . ($statusResult['message'] ?? 'Unknown error'),
        ], 500);
    }

    public function history()
    {
        $bookings = BookingKostum::with(['details.kostum', 'pembayaran'])
            ->where('id_pengunjung', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('front.payment.history', compact('bookings'));
    }

    private function updatePaymentStatus(Pembayaran $pembayaran, array|object $data): void
    {
        $transactionStatus = $this->readValue($data, 'transaction_status', 'pending');
        $updateData = [
            'transaction_id' => $this->readValue($data, 'transaction_id'),
            'transaction_status' => $transactionStatus,
            'fraud_status' => $this->readValue($data, 'fraud_status'),
            'payment_type' => $this->readValue($data, 'payment_type'),
            'transaction_time' => $this->readValue($data, 'transaction_time'),
            'midtrans_response' => is_array($data) ? $data : json_decode(json_encode($data), true),
        ];

        [$bank, $vaNumber] = $this->extractBankAndVaNumber($data);
        $updateData['bank'] = $bank;
        $updateData['va_number'] = $vaNumber;

        $booking = $pembayaran->booking()->with('details.kostum')->first();

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            $updateData['settlement_time'] = now();

            if ($booking && $booking->status !== 'dibayar') {
                $booking->update(['status' => 'dibayar']);
            }
        }

        if (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'], true) && $booking) {
            $booking->update(['status' => 'dibatalkan']);
        }

        $pembayaran->update($updateData);
    }

    private function updateSppStatus(SppTagihan $tagihan, array|object $data): void
    {
        $transactionStatus = $this->readValue($data, 'transaction_status', 'pending');
        [$bank, $vaNumber] = $this->extractBankAndVaNumber($data);

        $status = match ($transactionStatus) {
            'settlement', 'capture' => 'dibayar',
            'cancel' => 'dibatalkan',
            'deny', 'expire', 'failure' => 'gagal',
            default => 'menunggu',
        };

        $tagihan->update([
            'transaction_id' => $this->readValue($data, 'transaction_id'),
            'transaction_status' => $transactionStatus,
            'payment_type' => $this->readValue($data, 'payment_type'),
            'bank' => $bank,
            'va_number' => $vaNumber,
            'transaction_time' => $this->readValue($data, 'transaction_time'),
            'settlement_time' => in_array($transactionStatus, ['settlement', 'capture'], true) ? now() : $tagihan->settlement_time,
            'paid_at' => in_array($transactionStatus, ['settlement', 'capture'], true) ? now() : $tagihan->paid_at,
            'status' => $status,
            'midtrans_response' => is_array($data) ? $data : json_decode(json_encode($data), true),
        ]);
    }

    private function extractBankAndVaNumber(array|object $data): array
    {
        $bank = $this->readValue($data, 'bank');
        $vaNumber = $this->readValue($data, 'va_number');
        $vaNumbers = $this->readValue($data, 'va_numbers');

        if (is_array($vaNumbers) && !empty($vaNumbers)) {
            $first = $vaNumbers[0];
            $bank = is_array($first) ? ($first['bank'] ?? $bank) : ($first->bank ?? $bank);
            $vaNumber = is_array($first) ? ($first['va_number'] ?? $vaNumber) : ($first->va_number ?? $vaNumber);
        }

        $permataVa = $this->readValue($data, 'permata_va_number');
        if (!$vaNumber && $permataVa) {
            $bank = $bank ?: 'permata';
            $vaNumber = $permataVa;
        }

        return [$bank, $vaNumber];
    }

    private function readValue(array|object $data, string $key, mixed $default = null): mixed
    {
        if (is_array($data)) {
            return $data[$key] ?? $default;
        }

        return $data->{$key} ?? $default;
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        return $digits;
    }
}
