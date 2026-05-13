<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingKostum;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingKostum::with(['pengunjung', 'details.kostum', 'pembayaran', 'verifiedBy']);

        if ($request->search) {
            $query->where(function ($bookingQuery) use ($request) {
                $bookingQuery->where('order_id', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pemesan', 'like', '%' . $request->search . '%')
                    ->orWhere('no_hp_pemesan', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pengunjung', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('no_hp', 'like', '%' . $request->search . '%');
                    });
                });
            }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        $bookings = $query->latest()->paginate(10);

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(BookingKostum $booking)
    {
        $booking->load(['pengunjung', 'details.kostum', 'pembayaran', 'verifiedBy']);

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

    public function updateVerification(Request $request, BookingKostum $booking)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:pending,confirmed,rejected',
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        $validated['verified_at'] = $validated['verification_status'] === 'pending' ? null : now();
        $validated['verified_by'] = $validated['verification_status'] === 'pending' ? null : auth()->id();

        if ($validated['verification_status'] === 'rejected' && $booking->status !== 'selesai') {
            $validated['status'] = 'dibatalkan';
        }

        $booking->update($validated);

        return redirect()->route('admin.booking.show', $booking)
            ->with('success', 'Status verifikasi booking berhasil diperbarui.');
    }
}
