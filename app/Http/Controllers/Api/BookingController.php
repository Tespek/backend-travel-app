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
}
