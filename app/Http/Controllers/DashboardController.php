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
        $chartTotals = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $chartLabels[] = $month->translatedFormat('M Y');
            $chartTotals[] = (float) ($bookingRevenue[$key] ?? 0) + (float) ($sppRevenue[$key] ?? 0);
        }

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $bookingMonthRevenue = BookingKostum::whereIn('status', ['dibayar', 'diambil', 'selesai'])
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_biaya');

        $sppMonthRevenue = SppTagihan::where('status', 'dibayar')
            ->whereBetween('paid_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('nominal');

        return view('dashboard', [
            'isAdminDashboard' => true,
            'totalPeserta' => User::where('role', 'peserta')->count(),
            'pesertaAktif' => User::where('role', 'peserta')->where('status_aktif', true)->count(),
            'totalPelatih' => User::where('role', 'pelatih')->count(),
            'pendapatanBulanIni' => $bookingMonthRevenue + $sppMonthRevenue,
            'chartLabels' => $chartLabels,
            'chartTotals' => $chartTotals,
        ]);
    }
}
