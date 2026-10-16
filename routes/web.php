<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminTravel\RouteController;
use App\Http\Controllers\AdminTravel\ScheduleController;
use App\Http\Controllers\AdminTravel\DriverController;
use App\Http\Controllers\AdminTravel\OperatorProfileController;
use App\Http\Controllers\AdminTravel\VehicleController;
use App\Http\Controllers\AdminTravel\PickupPointController;
use App\Http\Controllers\AdminTravel\BookingController as AdminBookingController;
use App\Http\Controllers\SuperAdmin\OperatorController;
use App\Http\Controllers\SuperAdmin\CityController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\BookingController as SuperAdminBookingController;
use App\Http\Controllers\SuperAdmin\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile (bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Travel Routes
|--------------------------------------------------------------------------
| Semua route di sini dilindungi auth + role admin_travel
| Multi-tenant: setiap query difilter by operator_id di dalam Controller
*/
Route::middleware(['auth', 'role:admin_travel'])
    ->prefix('admin-travel')
    ->name('admin-travel.')
    ->group(function () {

        // Profil & Unggah Dokumen KTP/SIUP Travel
        Route::get('operator-profile', [OperatorProfileController::class, 'show'])->name('operator-profile.show');
        Route::patch('operator-profile', [OperatorProfileController::class, 'update'])->name('operator-profile.update');

        // Armada Kendaraan
        Route::resource('vehicles', VehicleController::class)->except(['show']);

        // Titik Penjemputan Penumpang
        Route::resource('pickup-points', PickupPointController::class)->except(['show']);

        // Rute Perjalanan
        Route::resource('routes', RouteController::class)->except(['show']);

        // Jadwal Keberangkatan
        Route::resource('schedules', ScheduleController::class)->except(['show']);

        // Manajemen Driver
        Route::resource('drivers', DriverController::class)
            ->except(['show'])
            ->parameters(['drivers' => 'driver']); // binding key: /drivers/{driver}

        // Pemesanan & Penugasan Driver
        Route::post('bookings/{booking}/assign-driver', [AdminBookingController::class, 'assignDriver'])->name('bookings.assign-driver');
        Route::post('bookings/{booking}/update-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        // Operator Travel & Verifikasi Dokumen KTP/SIUP
        Route::post('operators/{operator}/status', [OperatorController::class, 'updateStatus'])->name('operators.status');
        Route::resource('operators', OperatorController::class);

        // Kota & Wilayah Operasional
        Route::resource('cities', CityController::class)->except(['show']);

        // Manajemen User Seluruh Platform
        Route::resource('users', UserController::class);

        // Semua Pemesanan di Seluruh Operator
        Route::resource('bookings', SuperAdminBookingController::class)->only(['index', 'show']);

        // Laporan & Statistik Platform
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });

require __DIR__.'/auth.php';



