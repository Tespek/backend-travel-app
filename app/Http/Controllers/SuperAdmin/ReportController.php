<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Operator;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan laporan performa bisnis dan transaksi tiket platform.
     */
    public function index(Request $request)
    {
        $operatorId = $request->query('operator_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Booking::where('payment_status', 'paid');

        if ($operatorId) {
            $query->where('operator_id', $operatorId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalGross = (clone $query)->sum('total_price');
        $totalTickets = (clone $query)->count();
        $totalPlatformFee = round($totalGross * 0.05); // 5% komisi platform
        $totalNetOperators = $totalGross - $totalPlatformFee;

        // Riwayat Transaksi Global (Pagination)
        $globalTransactions = (clone $query)
            ->with(['operator', 'user', 'schedule.route.originCity', 'schedule.route.destinationCity'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 6 Bulan Terakhir Komisi Platform (Bar Chart)
        $chartMonths = [];
        $chartFees = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->copy()->subMonths($i);
            $chartMonths[] = $dt->isoFormat('MMM Y');
            $grossMonth = Booking::where('payment_status', 'paid')
                ->whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->sum('total_price');
            $chartFees[] = round($grossMonth * 0.05);
        }

        // Statistik per operator
        $operatorReports = Operator::withCount([
            'bookings as paid_bookings_count' => function ($q) {
                $q->where('payment_status', 'paid');
            },
            'vehicles',
            'routes'
        ])
        ->withSum(['bookings as total_revenue' => function ($q) {
            $q->where('payment_status', 'paid');
        }], 'total_price')
        ->get();

        $operators = Operator::orderBy('name')->get();

        return view('super-admin.reports.index', compact(
            'totalGross',
            'totalTickets',
            'totalPlatformFee',
            'totalNetOperators',
            'globalTransactions',
            'chartMonths',
            'chartFees',
            'operatorReports',
            'operators',
            'operatorId',
            'startDate',
            'endDate'
        ));
    }
}
