<?php

namespace App\Http\Controllers;

use App\Models\BookingKostum;
use App\Models\BookingDetail;
use App\Models\Pembayaran;
use App\Models\Kostum;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Process checkout dari cart
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_pengambilan',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            // Calculate duration
            $tanggalAmbil = new \Carbon\Carbon($request->tanggal_pengambilan);
            $tanggalKembali = new \Carbon\Carbon($request->tanggal_pengembalian);
            $durasi = $tanggalAmbil->diffInDays($tanggalKembali);

            // Generate unique order_id
            $orderId = 'KOSTUM-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // Create main booking (ambil item pertama untuk backward compatibility)
            $firstItem = reset($cart);
            
            $booking = BookingKostum::create([
                'id_kostum' => $firstItem['kostum_id'],
                'id_pengunjung' => Auth::id(),
                'order_id' => $orderId,
                'tanggal_booking' => now(),
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status' => 'menunggu',
                'total_biaya' => $request->total_biaya,
            ]);

            // Create booking details untuk setiap item
            foreach ($cart as $item) {
                // Subtotal = harga_sewa per hari × quantity
                $subtotal = $item['harga_sewa'] * $item['quantity'];
                
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'kostum_id' => $item['kostum_id'],
                    'quantity' => $item['quantity'],
                    'harga_sewa' => $item['harga_sewa'],
                    'subtotal' => $subtotal, // per hari
                ]);
            }

            // Customer Details
            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->no_telepon ?? '08123456789',
            ];

            // Item Details untuk Midtrans
            // PENTING: Midtrans perlu total per item (harga × quantity × durasi)
            $itemDetails = [];
            foreach ($cart as $item) {
                // Hitung total untuk item ini: (harga_sewa × quantity) × durasi
                $totalPerItem = ($item['harga_sewa'] * $item['quantity']) * $durasi;
                
                $itemDetails[] = [
                    'id' => $item['kostum_id'],
                    'price' => (int) $totalPerItem, // Total untuk item ini
                    'quantity' => 1, // Set 1 karena price sudah total
                    'name' => 'Sewa ' . $item['nama_kostum'] . ' (' . $item['ukuran'] . ') - ' . $item['quantity'] . ' pcs × ' . $durasi . ' hari',
                ];
            }

            // Create transaction via Midtrans Service
            $result = $this->midtrans->createTransaction(
                $orderId,
                (int) $request->total_biaya,
                $customerDetails,
                $itemDetails
            );

            if (!$result['success']) {
                DB::rollBack();
                return back()->with('error', 'Gagal membuat pembayaran: ' . ($result['message'] ?? 'Unknown error'));
            }

            // Create Pembayaran record
            $pembayaran = Pembayaran::create([
                'transaksi_id' => $booking->id,
                'order_id' => $orderId,
                'gross_amount' => $request->total_biaya,
                'transaction_status' => 'pending',
                'payment_url' => $result['snap_token'],
                'midtrans_response' => json_encode($result),
            ]);

            DB::commit();

            // Clear cart and calculation
            session()->forget([
                'cart',
                'tanggal_pengambilan',
                'tanggal_pengembalian',
                'durasi',
                'total_biaya'
            ]);

            // Redirect ke halaman payment
            return redirect()->route('payment.show', $booking->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Checkout Error: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show payment page
     */
    public function show(BookingKostum $booking)
    {
        // Cek kepemilikan booking
        if ($booking->id_pengunjung !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pembayaran = $booking->pembayaran;

        // Jika belum ada pembayaran
        if (!$pembayaran) {
            return redirect()->route('cart.index')
                ->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Get latest status from Midtrans
        $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);
        
        if ($statusResult['success']) {
            $this->updatePaymentStatus($pembayaran, $statusResult['data']);
            $pembayaran->refresh();
        }

        // Tetap tampilkan payment page (tidak redirect ke success)
        return view('front.payment.show', compact('booking', 'pembayaran'));
    }

    /**
     * Payment Finish (User redirected here after payment)
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return redirect()->route('home')->with('error', 'Pembayaran tidak ditemukan');
        }

        // Get latest status
        $statusResult = $this->midtrans->getTransactionStatus($orderId);
        
        if ($statusResult['success']) {
            $this->updatePaymentStatus($pembayaran, $statusResult['data']);
        }

        return redirect()->route('payment.show', $pembayaran->transaksi_id);
    }

    /**
     * Handle Midtrans callback/notification
     */
    public function callback(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            // Verify signature
            if ($hashed !== $request->signature_key) {
                Log::warning('Invalid Midtrans signature', ['order_id' => $request->order_id]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Find pembayaran
            $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();
            
            if (!$pembayaran) {
                Log::error('Payment not found for callback', ['order_id' => $request->order_id]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            $this->updatePaymentStatus($pembayaran, $request->all());

            return response()->json(['message' => 'Callback processed']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage(), [
                'order_id' => $request->order_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus(BookingKostum $booking)
    {
        try {
            $pembayaran = $booking->pembayaran;

            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pembayaran tidak ditemukan',
                ], 404);
            }

            // Get latest status from Midtrans
            $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);
            
            Log::info('Check Payment Status', [
                'order_id' => $pembayaran->order_id,
                'current_status' => $pembayaran->transaction_status,
                'midtrans_result' => $statusResult
            ]);
            
            if ($statusResult['success']) {
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
            
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show user's booking history
     */
    public function history()
    {
        $bookings = BookingKostum::with(['details.kostum', 'pembayaran'])
            ->where('id_pengunjung', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front.payment.history', compact('bookings'));
    }

    /**
     * Update Payment Status (Private Method)
     */
    private function updatePaymentStatus($pembayaran, $data)
    {
        $transactionStatus = $data->transaction_status ?? $data['transaction_status'] ?? 'pending';
        $fraudStatus = $data->fraud_status ?? $data['fraud_status'] ?? null;
        $paymentType = $data->payment_type ?? $data['payment_type'] ?? null;

        $updateData = [
            'transaction_id' => $data->transaction_id ?? $data['transaction_id'] ?? null,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'midtrans_response' => is_array($data) ? $data : json_decode(json_encode($data), true),
        ];

        // Bank info for VA
        if (isset($data->va_numbers) || isset($data['va_numbers'])) {
            $vaNumbers = $data->va_numbers ?? $data['va_numbers'];
            if (!empty($vaNumbers)) {
                $vaNumber = is_array($vaNumbers) ? $vaNumbers[0] : $vaNumbers[0];
                $updateData['bank'] = $vaNumber->bank ?? $vaNumber['bank'] ?? null;
                $updateData['va_number'] = $vaNumber->va_number ?? $vaNumber['va_number'] ?? null;
            }
        }

        // Transaction time
        if (isset($data->transaction_time) || isset($data['transaction_time'])) {
            $updateData['transaction_time'] = $data->transaction_time ?? $data['transaction_time'];
        }

        // Settlement time & Update Booking Status
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $updateData['settlement_time'] = now();
            
            $booking = $pembayaran->booking;
            
            // Update booking status
            $booking->update(['status' => 'dibayar']);

            // Update stok kostum (kurangi)
            foreach ($booking->details as $detail) {
                $kostum = $detail->kostum;
                if ($kostum && $kostum->stok >= $detail->quantity) {
                    $kostum->decrement('stok', $detail->quantity);
                }
            }

            Log::info('Payment successful', [
                'order_id' => $pembayaran->order_id,
                'booking_id' => $booking->id
            ]);
        }

        // Jika pembayaran gagal/expired/cancel
        if (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $booking = $pembayaran->booking;
            $booking->update(['status' => 'dibatalkan']);

            Log::info('Payment failed/cancelled', [
                'order_id' => $pembayaran->order_id,
                'status' => $transactionStatus
            ]);
        }

        $pembayaran->update($updateData);
    }
}