<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PickupStatusLog;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    private function authorizeBooking(Booking $booking): void
    {
        if ($booking->operator_id !== $this->operatorId()) {
            abort(403, 'Anda tidak memiliki akses ke data pemesanan ini.');
        }
    }

    /**
     * Tampilkan daftar pemesanan tiket travel milik operator saat ini.
     */
    public function index(Request $request)
    {
        $paymentStatus = $request->query('payment_status');
        $pickupStatus = $request->query('pickup_status');
        $search = $request->query('search');

        $query = Booking::where('operator_id', $this->operatorId())
            ->with(['schedule.route.originCity', 'schedule.route.destinationCity', 'driver', 'pickupPoint', 'destination']);

        if ($paymentStatus && in_array($paymentStatus, ['pending', 'paid', 'failed', 'expired'])) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($pickupStatus && in_array($pickupStatus, ['waiting', 'driver_assigned', 'on_the_way', 'arrived', 'picked_up', 'completed', 'cancelled'])) {
            $query->where('pickup_status', $pickupStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all'       => Booking::where('operator_id', $this->operatorId())->count(),
            'paid'      => Booking::where('operator_id', $this->operatorId())->where('payment_status', 'paid')->count(),
            'pending'   => Booking::where('operator_id', $this->operatorId())->where('payment_status', 'pending')->count(),
            'completed' => Booking::where('operator_id', $this->operatorId())->where('pickup_status', 'completed')->count(),
        ];

        return view('admin-travel.bookings.index', compact('bookings', 'counts', 'paymentStatus', 'pickupStatus', 'search'));
    }

    /**
     * Tampilkan detail pemesanan, tiket, status pembayaran, dan tracking driver.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'schedule.route.originCity',
            'schedule.route.destinationCity',
            'schedule.vehicle',
            'driver',
            'pickupPoint',
            'destination',
            'statusLogs.user'
        ]);

        $availableDrivers = User::where('role', 'driver')
            ->where('operator_id', $this->operatorId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin-travel.bookings.show', compact('booking', 'availableDrivers'));
    }

    /**
     * Tugaskan driver ke pemesanan tertentu.
     */
    public function assignDriver(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::where('role', 'driver')
            ->where('operator_id', $this->operatorId())
            ->findOrFail($validated['driver_id']);

        $booking->update([
            'driver_id'     => $driver->id,
            'pickup_status' => $booking->pickup_status === 'waiting' ? 'driver_assigned' : $booking->pickup_status,
        ]);

        // Catat di pickup status log
        PickupStatusLog::create([
            'booking_id' => $booking->id,
            'user_id'    => auth()->id(),
            'status'     => 'driver_assigned',
            'note'       => "Driver {$driver->name} ditugaskan oleh Admin Travel.",
        ]);

        return back()->with('success', "Driver {$driver->name} berhasil ditugaskan.");
    }

    /**
     * Update status pembayaran atau penjemputan manual oleh Admin Travel.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'payment_status' => 'nullable|in:pending,paid,failed,expired',
            'pickup_status'  => 'nullable|in:waiting,driver_assigned,on_the_way,arrived,picked_up,completed,cancelled',
            'note'           => 'nullable|string|max:500',
        ]);

        if (!empty($validated['payment_status'])) {
            $booking->payment_status = $validated['payment_status'];
            if ($validated['payment_status'] === 'paid' && !$booking->paid_at) {
                $booking->paid_at = now();
            }
        }

        if (!empty($validated['pickup_status'])) {
            $oldStatus = $booking->pickup_status;
            $booking->pickup_status = $validated['pickup_status'];

            if ($validated['pickup_status'] === 'cancelled' && !$booking->cancelled_at) {
                $booking->cancelled_at = now();
                $booking->cancellation_reason = $validated['note'] ?? 'Dibatalkan oleh Admin Travel.';
            }

            // Log perubahan status penjemputan
            if ($oldStatus !== $validated['pickup_status']) {
                PickupStatusLog::create([
                    'booking_id' => $booking->id,
                    'user_id'    => auth()->id(),
                    'status'     => $validated['pickup_status'],
                    'note'       => $validated['note'] ?? "Status diubah menjadi {$validated['pickup_status']} oleh Admin.",
                ]);
            }
        }

        $booking->save();

        return back()->with('success', 'Status pemesanan berhasil diperbarui.');
    }
}
