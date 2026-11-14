<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = 'SB-Mid-server-dcsQ7KAnzXw6BSFKx2u6dl6Z';
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Buat booking + generate Snap Token Midtrans
     */
    public function createTransaction(Request $request)
    {
        $request->validate([
            'schedule_id'      => 'required|exists:schedules,id',
            'passengers'       => 'required|array|min:1',
            'passengers.*.name'      => 'required|string|max:255',
            'passengers.*.phone'     => 'nullable|string|max:20',
            'passengers.*.seat'      => 'required|integer',
            'passengers.*.address'   => 'nullable|string',
            'payment_method'   => 'required|in:bca,qris,ewallet,midtrans',
        ]);

        $schedule = Schedule::with('route')->findOrFail($request->schedule_id);
        $user     = $request->user();
        $passengers = $request->passengers;
        $adminFee = 3000;
        $pricePerTicket = $schedule->route->price;
        $totalPrice = (count($passengers) * $pricePerTicket) + $adminFee;

        // Cek semua kursi tidak dipesan
        foreach ($passengers as $p) {
            $seatTaken = Booking::where('schedule_id', $request->schedule_id)
                ->where('seat_number', $p['seat'])
                ->whereIn('payment_status', ['pending', 'paid'])
                ->exists();

            if ($seatTaken) {
                return response()->json([
                    'message' => "Kursi {$p['seat']} sudah dipesan."
                ], 422);
            }
        }

        // Buat booking dengan status pending
        $bookingCode = 'JK-' . strtoupper(Str::random(8));
        $bookings = [];

        foreach ($passengers as $index => $p) {
            $booking = Booking::create([
                'booking_code'    => $bookingCode . '-' . ($index + 1),
                'user_id'         => $user->id,
                'schedule_id'     => $request->schedule_id,
                'operator_id'     => $schedule->route->operator_id,
                'passenger_name'  => $p['name'],
                'passenger_phone' => $p['phone'] ?? null,
                'seat_number'     => $p['seat'],
                'pickup_type'     => !empty($p['address']) ? 'door_to_door' : 'pool',
                'pickup_address'  => $p['address'] ?? null,
                'total_price'     => $pricePerTicket + ($index === 0 ? $adminFee : 0),
                'payment_status'  => 'pending',
                'payment_method'  => 'midtrans',
                'destination_id'  => null,
                'pickup_status'   => 'waiting',
            ]);
            $bookings[] = $booking;
        }

        // Generate Snap Token Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $bookingCode,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '',
            ],
            'item_details' => array_map(function ($p, $index) use ($pricePerTicket, $adminFee) {
                return [
                    'id'       => 'SEAT-' . $p['seat'],
                    'price'    => $pricePerTicket + ($index === 0 ? $adminFee : 0),
                    'quantity' => 1,
                    'name'     => 'Tiket Kursi ' . $p['seat'] . ' - ' . $p['name'],
                ];
            }, $passengers, array_keys($passengers)),
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan booking_code ke semua booking
        foreach ($bookings as $booking) {
            $booking->update(['payment_gateway_ref' => $bookingCode]);
        }

        return response()->json([
            'snap_token'   => $snapToken,
            'booking_code' => $bookingCode,
            'total_price'  => $totalPrice,
            'booking_ids'  => array_map(fn($b) => $b->id, $bookings),
        ]);
    }

    /**
     * Update status pembayaran setelah Midtrans callback
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'booking_code'       => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $status = $request->transaction_status;
        $paymentStatus = match ($status) {
            'capture', 'settlement' => 'paid',
            'pending'               => 'pending',
            default                 => 'failed',
        };

        // Ambil semua booking dengan payment_gateway_ref ini
        $bookings = Booking::where('payment_gateway_ref', $request->booking_code)->get();

        foreach ($bookings as $booking) {
            $booking->update([
                'payment_status' => $paymentStatus,
                'paid_at'        => $paymentStatus === 'paid' ? now() : null,
            ]);

            // Kurangi available_seats per booking jika paid
            if ($paymentStatus === 'paid') {
                Schedule::find($booking->schedule_id)?->decrement('available_seats');
            }
        }

        return response()->json([
            'message' => 'Status updated',
            'updated' => $bookings->count(),
        ]);
    }
}
