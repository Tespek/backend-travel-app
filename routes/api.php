<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;


/*
|--------------------------------------------------------------------------
| API Routes (Public - Tidak butuh login)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/cities', [CityController::class, 'index']);
Route::get('/schedules/search', [ScheduleController::class, 'search']);

// Booking
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);


/*
|--------------------------------------------------------------------------
| API Routes (Protected - Butuh Login / Bearer Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout & Get Profile (Bisa diakses semua role yang sudah login)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Contoh Route Khusus Customer
    Route::middleware('role:customer')->prefix('customer')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Halo Customer, ini dashboard kamu!']);
        });
    });

    // Contoh Route Khusus Driver
    Route::middleware('role:driver')->prefix('driver')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Halo Driver, ini dashboard kamu!']);
        });
    });

    // Contoh Route Khusus Admin Travel
    Route::middleware('role:admin_travel')->prefix('admin-travel')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Halo Admin Travel, ini dashboard kamu!']);
        });
    });

});