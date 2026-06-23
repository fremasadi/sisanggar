<?php

namespace App\Http\Controllers;

use App\Models\BookingDetail;
use App\Models\BookingKostum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestBookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:30',
            'tanggal_pengambilan' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_pengambilan',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $orderId = 'MANUAL-' . now()->format('YmdHis') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
            $booking = BookingKostum::create([
                'id_pengunjung' => null,
                'order_id' => $orderId,
                'nama_pemesan' => $request->nama,
                'no_hp_pemesan' => $request->no_hp,
                'no_hp_pemesan_normalized' => $this->normalizePhone($request->no_hp),
                'tipe_booking' => 'manual',
                'verification_status' => 'pending',
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

            DB::commit();

            session()->forget([
                'cart',
                'tanggal_pengambilan',
                'tanggal_pengembalian',
                'durasi',
                'total_biaya',
            ]);

            return redirect()->route('guest-booking.history')
                ->with('success', 'Booking berhasil dikirim. Diharapkan untuk ke sanggar untuk melakukan pengukuran kostum.')
                ->withInput([
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Booking gagal disimpan: ' . $e->getMessage());
        }
    }

    public function historyForm()
    {
        return view('front.booking.history', [
            'bookings' => collect(),
            'searched' => false,
        ]);
    }

    public function historySearch(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:30',
        ]);

        $bookings = BookingKostum::with(['details.kostum', 'pembayaran'])
            ->where(function ($query) use ($request) {
                $query->where('nama_pemesan', $request->nama)
                    ->orWhereHas('pengunjung', function ($pengunjungQuery) use ($request) {
                        $pengunjungQuery->where('name', $request->nama);
                    });
            })
            ->where(function ($query) use ($request) {
                $query->where('no_hp_pemesan_normalized', $this->normalizePhone($request->no_hp))
                    ->orWhereHas('pengunjung', function ($pengunjungQuery) use ($request) {
                        $pengunjungQuery->where('no_hp', $request->no_hp);
                    });
            })
            ->latest()
            ->get();

        return view('front.booking.history', [
            'bookings' => $bookings,
            'searched' => true,
        ]);
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
