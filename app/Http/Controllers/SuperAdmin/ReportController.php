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

        $totalRevenue = (clone $query)->sum('total_price');
        $totalTickets = (clone $query)->count();

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

        return view('super-admin.reports.index', compact('totalRevenue', 'totalTickets', 'operatorReports', 'operators', 'operatorId', 'startDate', 'endDate'));
    }
}
