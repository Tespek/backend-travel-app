<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Operator;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Tampilkan seluruh transaksi pemesanan di semua operator travel.
     */
    public function index(Request $request)
    {
        $operatorId = $request->query('operator_id');
        $paymentStatus = $request->query('payment_status');
        $search = $request->query('search');

        $query = Booking::query()
            ->with(['operator', 'schedule.route.originCity', 'schedule.route.destinationCity', 'driver']);

        if ($operatorId) {
            $query->where('operator_id', $operatorId);
        }

        if ($paymentStatus && in_array($paymentStatus, ['pending', 'paid', 'failed', 'expired'])) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        $operators = Operator::orderBy('name')->get();

        $counts = [
            'all'     => Booking::count(),
            'paid'    => Booking::where('payment_status', 'paid')->count(),
            'pending' => Booking::where('payment_status', 'pending')->count(),
        ];

        return view('super-admin.bookings.index', compact('bookings', 'operators', 'counts', 'operatorId', 'paymentStatus', 'search'));
    }

    /**
     * Tampilkan detail transaksi pemesanan.
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'operator',
            'user',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.vehicle',
            'driver',
            'pickupPoint',
            'destination',
            'statusLogs.user'
        ]);

        return view('super-admin.bookings.show', compact('booking'));
    }
}
