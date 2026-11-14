<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Ambil daftar booking milik user yang login
    public function index(Request $request)
    {
        $bookings = Booking::with(['schedule.route', 'schedule.vehicle'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id'             => $booking->id,
                    'booking_code'   => $booking->booking_code,
                    'passenger_name' => $booking->passenger_name,
                    'seat_number'    => $booking->seat_number,
                    'total_price'    => $booking->total_price,
                    'payment_status' => $booking->payment_status,
                    'payment_method' => $booking->payment_method,
                    'pickup_status'  => $booking->pickup_status,
                    'created_at'     => $booking->created_at,
                    'schedule' => [
                        'departure_date' => $booking->schedule->departure_date,
                        'departure_time' => $booking->schedule->departure_time,
                        'route' => [
                            'origin'      => $booking->schedule->route->origin ?? $booking->schedule->route->origin_name ?? '-',
                            'destination' => $booking->schedule->route->destination ?? $booking->schedule->route->destination_name ?? '-',
                            'price'       => $booking->schedule->route->price,
                        ],
                        'vehicle' => [
                            'brand'        => $booking->schedule->vehicle->brand ?? '-',
                            'plate_number' => $booking->schedule->vehicle->plate_number ?? '-',
                        ],
                    ],
                ];
            });

        return response()->json(['data' => $bookings]);
    }

    // Buat booking baru
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id'      => 'required|exists:schedules,id',
            'passenger_name'   => 'required|string|max:255',
            'passenger_phone'  => 'nullable|string|max:20',
            'seat_number'      => 'required|integer',
            'pickup_type'      => 'required|in:terminal,pickup',
            'pickup_address'   => 'nullable|string',
            'total_price'      => 'required|numeric',
            'payment_method'   => 'required|in:bca,qris,ewallet',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Cek kursi tidak sudah dipesan
        $seatTaken = Booking::where('schedule_id', $request->schedule_id)
            ->where('seat_number', $request->seat_number)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->exists();

        if ($seatTaken) {
            return response()->json(['message' => 'Kursi sudah dipesan.'], 422);
        }

        $booking = Booking::create([
            'booking_code'   => 'JK-' . strtoupper(Str::random(8)),
            'user_id'        => $request->user()->id,
            'schedule_id'    => $request->schedule_id,
            'operator_id'    => $schedule->route->operator_id,
            'passenger_name' => $request->passenger_name,
            'passenger_phone'=> $request->passenger_phone,
            'seat_number'    => $request->seat_number,
            'pickup_type'    => $request->pickup_type,
            'pickup_address' => $request->pickup_address,
            'total_price'    => $request->total_price,
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at'        => now(),
            'pickup_status'  => 'waiting',
        ]);

        // Kurangi available_seats
        $schedule->decrement('available_seats');

        return response()->json([
            'message' => 'Booking berhasil',
            'data'    => $booking,
        ], 201);
    }

    // Detail booking
    public function show(Request $request, $id)
    {
        $booking = Booking::with(['schedule.route', 'schedule.vehicle'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $booking]);
    }
    public function bookedSeats($scheduleId)
    {
        $seats = Booking::where('schedule_id', $scheduleId)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->pluck('seat_number')
            ->toArray();

        return response()->json(['data' => $seats]);
    }
    public function driverPassengers($scheduleId)
    {
        $bookings = Booking::where('schedule_id', $scheduleId)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->get(['id', 'passenger_name', 'passenger_phone', 'seat_number',
                    'pickup_type', 'pickup_address', 'pickup_latitude',
                    'pickup_longitude', 'pickup_status']);

        return response()->json(['data' => $bookings]);
    }
    public function driverSchedule(Request $request)
    {
        $user = $request->user();

        $schedule = \App\Models\Schedule::with(['route', 'vehicle'])
            ->whereHas('bookings', function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                ->whereIn('payment_status', ['pending', 'paid']);
            })
            ->whereDate('departure_date', today())
            ->first();

        if (!$schedule) {
            // Kalau tidak ada berdasarkan driver_id, ambil jadwal hari ini milik operator
            $schedule = \App\Models\Schedule::with(['route', 'vehicle'])
                ->whereDate('departure_date', today())
                ->where('status', 'open')
                ->first();
        }

        if (!$schedule) {
            return response()->json(['data' => null]);
        }

        $passengerCount = \App\Models\Booking::where('schedule_id', $schedule->id)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->count();

        return response()->json([
            'data' => [
                'id'               => $schedule->id,
                'departure_date'   => $schedule->departure_date,
                'departure_time'   => $schedule->departure_time,
                'origin'           => $schedule->route->origin ?? '-',
                'destination'      => $schedule->route->destination ?? '-',
                'vehicle_brand'    => $schedule->vehicle->brand ?? '-',
                'vehicle_capacity' => $schedule->vehicle->capacity ?? 0,
                'passenger_count'  => $passengerCount,
            ]
        ]);
    }
    public function driverScheduleHistory(Request $request)
    {
        $user = $request->user();

        $schedules = \App\Models\Schedule::with(['route', 'vehicle'])
            ->whereHas('bookings', function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                ->where('pickup_status', 'completed');
            })
            ->orderBy('departure_date', 'desc')
            ->get()
            ->map(function ($s) {
                $count = \App\Models\Booking::where('schedule_id', $s->id)
                    ->where('pickup_status', 'completed')->count();
                return [
                    'id'              => $s->id,
                    'departure_date'  => $s->departure_date,
                    'departure_time'  => $s->departure_time,
                    'origin'          => $s->route->origin ?? '-',
                    'destination'     => $s->route->destination ?? '-',
                    'vehicle_brand'   => $s->vehicle->brand ?? '-',
                    'passenger_count' => $count,
                ];
            });
        return response()->json(['data' => $schedules]);
    }

    public function updatePickupStatus(Request $request, $id)
    {
        $request->validate([
            'pickup_status' => 'required|in:waiting,boarded,completed',
        ]);

        Booking::findOrFail($id)->update([
            'pickup_status' => $request->pickup_status,
        ]);

        return response()->json(['message' => 'Status updated']);
    }
}
