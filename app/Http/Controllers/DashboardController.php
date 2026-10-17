<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama sesuai role user.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            $totalGrossRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
            // Platform fee dari transaksi completed atau estimasi komisi 5%
            $totalPlatformFee = Transaction::where('status', 'completed')->sum('platform_fee');
            if ($totalPlatformFee == 0 && $totalGrossRevenue > 0) {
                $totalPlatformFee = $totalGrossRevenue * 0.05;
            }
            $totalOperatorNet = $totalGrossRevenue - $totalPlatformFee;

            $stats = [
                'total_platform_fee'  => $totalPlatformFee,
                'total_gross_revenue' => $totalGrossRevenue,
                'total_operator_net'  => $totalOperatorNet,
                'total_operators'     => Operator::count(),
                'verified_operators'  => Operator::where('verification_status', 'verified')->count(),
                'pending_operators'   => Operator::where('verification_status', 'pending')->count(),
                'rejected_operators'  => Operator::where('verification_status', 'rejected')->count(),
                'total_users'         => User::count(),
                'total_bookings'      => Booking::count(),
                'paid_bookings'       => Booking::where('payment_status', 'paid')->count(),
            ];

            // 6 Bulan Terakhir untuk Dual Line Chart (Platform Fee vs Operator Net)
            $chartMonths = [];
            $platformRevenue = [];
            $operatorRevenue = [];
            for ($i = 5; $i >= 0; $i--) {
                $dt = now()->copy()->subMonths($i);
                $chartMonths[] = $dt->isoFormat('MMM Y');

                $grossMonth = Booking::where('payment_status', 'paid')
                    ->whereYear('created_at', $dt->year)
                    ->whereMonth('created_at', $dt->month)
                    ->sum('total_price');

                $feeMonth = Transaction::where('status', 'completed')
                    ->whereYear('created_at', $dt->year)
                    ->whereMonth('created_at', $dt->month)
                    ->sum('platform_fee');

                if ($feeMonth == 0 && $grossMonth > 0) {
                    $feeMonth = $grossMonth * 0.05;
                }

                $platformRevenue[] = round($feeMonth);
                $operatorRevenue[] = round($grossMonth - $feeMonth);
            }

            // Status Verifikasi Operator untuk Pie / Doughnut Chart
            $operatorStatusData = [
                'verified' => Operator::where('verification_status', 'verified')->count(),
                'pending'  => Operator::where('verification_status', 'pending')->count(),
                'rejected' => Operator::where('verification_status', 'rejected')->count(),
            ];

            // 5 Operator Terbaru yang Menunggu Persetujuan (Pending)
            $pendingOperators = Operator::where('verification_status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            // 5 Operator Terdaftar Terbaru
            $recentOperators = Operator::latest()->take(5)->get();

            return view('super-admin.dashboard', compact(
                'stats',
                'pendingOperators',
                'recentOperators',
                'chartMonths',
                'platformRevenue',
                'operatorRevenue',
                'operatorStatusData'
            ));
        }

        if ($user->role === 'admin_travel') {
            $operatorId = $user->operator_id;
            $operator = Operator::find($operatorId);

            $stats = [
                'total_vehicles'  => Vehicle::where('operator_id', $operatorId)->count(),
                'total_routes'    => Route::where('operator_id', $operatorId)->where('is_active', true)->count(),
                'total_drivers'   => User::where('role', 'driver')->where('operator_id', $operatorId)->count(),
                'total_schedules' => Schedule::whereHas('route', fn($q) => $q->where('operator_id', $operatorId))->count(),
                'total_bookings'  => Booking::where('operator_id', $operatorId)->count(),
                'total_revenue'   => Booking::where('operator_id', $operatorId)->where('payment_status', 'paid')->sum('total_price'),
            ];

            // 6 Bulan Terakhir untuk Line Chart
            $chartMonths = [];
            $chartBookings = [];
            for ($i = 5; $i >= 0; $i--) {
                $dt = now()->copy()->subMonths($i);
                $chartMonths[] = $dt->isoFormat('MMM Y');
                $chartBookings[] = Booking::where('operator_id', $operatorId)
                    ->whereYear('created_at', $dt->year)
                    ->whereMonth('created_at', $dt->month)
                    ->count();
            }

            // Status Pembayaran untuk Doughnut Chart
            $paymentStats = [
                'paid'      => Booking::where('operator_id', $operatorId)->where('payment_status', 'paid')->count(),
                'pending'   => Booking::where('operator_id', $operatorId)->where('payment_status', 'pending')->count(),
                'cancelled' => Booking::where('operator_id', $operatorId)->whereIn('payment_status', ['cancelled', 'expired', 'failed'])->count(),
            ];

            // 5 Pemesanan Terbaru
            $recentBookings = Booking::with(['schedule.route.originCity', 'schedule.route.destinationCity', 'driver'])
                ->where('operator_id', $operatorId)
                ->latest()
                ->take(5)
                ->get();

            // Jadwal Keberangkatan Terdekat
            $upcomingSchedules = Schedule::with(['route.originCity', 'route.destinationCity', 'vehicle'])
                ->whereHas('route', fn($q) => $q->where('operator_id', $operatorId))
                ->where('departure_date', '>=', now()->toDateString())
                ->orderBy('departure_date')
                ->orderBy('departure_time')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'operator',
                'stats',
                'upcomingSchedules',
                'recentBookings',
                'chartMonths',
                'chartBookings',
                'paymentStats'
            ));
        }

        return view('dashboard');
    }
}
