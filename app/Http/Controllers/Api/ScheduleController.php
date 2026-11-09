<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Mencari jadwal berdasarkan kota asal, kota tujuan, dan tanggal.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'origin_city_id' => ['required', 'integer'],
            'destination_city_id' => ['required', 'integer'],
            'departure_date' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        // Ambil nama kota dari city_id
        $originCity = \App\Models\City::find($request->origin_city_id);
        $destinationCity = \App\Models\City::find($request->destination_city_id);

        if (! $originCity || ! $destinationCity) {
            return response()->json(['data' => []]);
        }

        $schedules = Schedule::with([
            'route.originCity',
            'route.destinationCity',
            'vehicle',
        ])
            ->open()
            ->whereDate('departure_date', $request->departure_date)
            ->whereHas('route', function ($query) use ($request, $originCity, $destinationCity) {
                $query->where(function ($q) use ($request, $originCity, $destinationCity) {
                    // Cari berdasarkan city_id (rute lama/seeder)
                    $q->where('origin_city_id', $request->origin_city_id)
                      ->where('destination_city_id', $request->destination_city_id);
                })->orWhere(function ($q) use ($originCity, $destinationCity) {
                    // Cari berdasarkan teks origin/destination (rute dari admin travel)
                    $q->where('origin', 'like', '%'.$originCity->name.'%')
                      ->where('destination', 'like', '%'.$destinationCity->name.'%');
                });
            })
            ->orderBy('departure_time')
            ->get()
            ->map(function (Schedule $schedule) {
                return [
                    'id' => $schedule->id,
                    'departure_date' => $schedule->departure_date->format('Y-m-d'),
                    'departure_time' => $schedule->departure_time,
                    'available_seats' => $schedule->available_seats,
                    'status' => $schedule->status,
                    'route' => [
                        'id' => $schedule->route->id,
                        'origin' => $schedule->route->origin_name,
                        'destination' => $schedule->route->destination_name,
                        'price' => $schedule->route->price,
                        'estimated_duration' => $schedule->route->estimated_duration,
                    ],
                    'vehicle' => [
                        'id' => $schedule->vehicle->id,
                        'brand' => $schedule->vehicle->brand ?? '-',
                        'plate_number' => $schedule->vehicle->plate_number ?? '-',
                        'capacity' => $schedule->vehicle->capacity,
                    ],
                ];
            });

        return response()->json([
            'data' => $schedules,
        ]);
    }
}
