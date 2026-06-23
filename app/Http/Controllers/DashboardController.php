<?php

namespace App\Http\Controllers;

use App\Models\BookingKostum;
use App\Models\SppTagihan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return view('dashboard', [
                'isAdminDashboard' => false,
            ]);
        }

        $months = collect(range(5, 0))->map(fn ($offset) => now()->startOfMonth()->subMonths($offset))
            ->push(now()->startOfMonth())
            ->values();

        $bookingRevenue = BookingKostum::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month_key, SUM(total_biaya) as total')
            ->whereIn('status', ['dibayar', 'diambil', 'selesai'])
            ->whereBetween('created_at', [
                $months->first()->copy()->startOfMonth(),
                $months->last()->copy()->endOfMonth(),
            ])
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $sppRevenue = SppTagihan::query()
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month_key, SUM(nominal) as total')
            ->where('status', 'dibayar')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [
                $months->first()->copy()->startOfMonth(),
                $months->last()->copy()->endOfMonth(),
            ])
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $chartLabels = [];
        $chartBooking = [];
        $chartSpp = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $chartLabels[]  = $month->translatedFormat('M Y');
            $chartBooking[] = (float) ($bookingRevenue[$key] ?? 0);
            $chartSpp[]     = (float) ($sppRevenue[$key] ?? 0);
        }

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd   = now()->endOfMonth();

        $pendapatanBooking = BookingKostum::whereIn('status', ['dibayar', 'diambil', 'selesai'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_biaya');

        $pendapatanSpp = SppTagihan::where('status', 'dibayar')
            ->whereBetween('paid_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('nominal');

        return view('dashboard', [
            'isAdminDashboard'  => true,
            'totalPeserta'      => User::where('role', 'peserta')->count(),
            'pesertaAktif'      => User::where('role', 'peserta')->where('status_aktif', true)->count(),
            'totalPelatih'      => User::where('role', 'pelatih')->count(),
            'pendapatanBooking' => $pendapatanBooking,
            'pendapatanSpp'     => $pendapatanSpp,
            'pendapatanBulanIni'=> $pendapatanBooking + $pendapatanSpp,
            'chartLabels'       => $chartLabels,
            'chartBooking'      => $chartBooking,
            'chartSpp'          => $chartSpp,
        ]);
    }
}
