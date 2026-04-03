<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingKostum;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingKostum::with(['pengunjung', 'details.kostum', 'pembayaran']);

        if ($request->search) {
            $query->where('order_id', 'like', '%' . $request->search . '%')
                ->orWhereHas('pengunjung', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(BookingKostum $booking)
    {
        $booking->load(['pengunjung', 'details.kostum', 'pembayaran']);

        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, BookingKostum $booking)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dibayar,diambil,selesai,dibatalkan',
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', 'Status booking berhasil diperbarui.');
    }
}
