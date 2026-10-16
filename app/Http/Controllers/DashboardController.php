<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Schedule;
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
            $stats = [
                'total_operators'   => Operator::count(),
                'pending_operators' => Operator::where('verification_status', 'pending')->count(),
                'total_users'       => User::count(),
                'total_bookings'    => Booking::count(),
            ];

            $pendingOperators = Operator::where('verification_status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $recentOperators = Operator::latest()->take(5)->get();

            return view('dashboard', compact('stats', 'pendingOperators', 'recentOperators'));
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
            ];

            $upcomingSchedules = Schedule::with(['route.originCity', 'route.destinationCity', 'vehicle'])
                ->whereHas('route', fn($q) => $q->where('operator_id', $operatorId))
                ->where('departure_date', '>=', now()->toDateString())
                ->orderBy('departure_date')
                ->orderBy('departure_time')
                ->take(6)
                ->get();

            return view('dashboard', compact('operator', 'stats', 'upcomingSchedules'));
        }

        return view('dashboard');
    }
}
