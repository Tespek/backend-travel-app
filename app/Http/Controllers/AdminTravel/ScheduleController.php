<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    public function index()
    {
        $schedules = Schedule::with(['route.originCity', 'route.destinationCity', 'vehicle'])
            ->whereHas('route', fn($q) => $q->where('operator_id', $this->operatorId()))
            ->latest('departure_date')
            ->paginate(15);

        return view('admin-travel.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('operator_id', $this->operatorId())
            ->where('is_active', true)
            ->get();

        $vehicles = Vehicle::where('operator_id', $this->operatorId())
            ->where('status', 'available')
            ->get();

        return view('admin-travel.schedules.create', compact('routes', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id'       => ['required', 'exists:routes,id', function ($attr, $val, $fail) {
                if (!Route::where('id', $val)->where('operator_id', $this->operatorId())->exists()) {
                    $fail('Rute tidak valid.');
                }
            }],
            'vehicle_id'     => ['required', 'exists:vehicles,id', function ($attr, $val, $fail) {
                if (!Vehicle::where('id', $val)->where('operator_id', $this->operatorId())->exists()) {
                    $fail('Kendaraan tidak valid.');
                }
            }],
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required|date_format:H:i',
            'available_seats'=> 'required|integer|min:1|max:100',
            'status'         => 'required|in:open,full,cancelled,completed',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin-travel.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('operator_id', $this->operatorId())
            ->where('is_active', true)
            ->get();

        $vehicles = Vehicle::where('operator_id', $this->operatorId())->get();

        return view('admin-travel.schedules.edit', compact('schedule', 'routes', 'vehicles'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $validated = $request->validate([
            'route_id'       => ['required', 'exists:routes,id', function ($attr, $val, $fail) {
                if (!Route::where('id', $val)->where('operator_id', $this->operatorId())->exists()) {
                    $fail('Rute tidak valid.');
                }
            }],
            'vehicle_id'     => ['required', 'exists:vehicles,id', function ($attr, $val, $fail) {
                if (!Vehicle::where('id', $val)->where('operator_id', $this->operatorId())->exists()) {
                    $fail('Kendaraan tidak valid.');
                }
            }],
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'available_seats'=> 'required|integer|min:0|max:100',
            'status'         => 'required|in:open,full,cancelled,completed',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin-travel.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        if ($schedule->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return redirect()->route('admin-travel.schedules.index')
                ->with('error', 'Jadwal tidak bisa dihapus karena masih ada pemesanan aktif.');
        }

        $schedule->delete();

        return redirect()->route('admin-travel.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    private function authorizeSchedule(Schedule $schedule): void
    {
        if ($schedule->route->operator_id !== $this->operatorId()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
