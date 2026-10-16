<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Operator;
use App\Models\City;
use App\Models\OperatorPickupPoint;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@travel.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Operator Travel
        $operator = Operator::create([
            'name' => 'Travel Jaya Abadi',
            'email' => 'admin@traveljaya.com',
            'owner_name' => 'Budi Santoso',
            'phone' => '081234567891',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
            'verification_status' => 'verified',
        ]);

        // Admin Travel
        User::create([
            'name' => 'Admin Travel Jaya',
            'email' => 'admin@traveljaya.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'role' => 'admin_travel',
            'operator_id' => $operator->id,
            'status' => 'active',
        ]);

        // Kota
        $jakarta = City::create([
            'operator_id' => $operator->id,
            'name' => 'Jakarta',
        ]);

        $bandung = City::create([
            'operator_id' => $operator->id,
            'name' => 'Bandung',
        ]);

        // Pool Penjemputan
        OperatorPickupPoint::create([
            'operator_id' => $operator->id,
            'city_id' => $jakarta->id,
            'name' => 'Pool Gambir',
            'address' => 'Jl. Medan Merdeka Barat, Jakarta Pusat',
            'latitude' => -6.1754,
            'longitude' => 106.8272,
        ]);

        OperatorPickupPoint::create([
            'operator_id' => $operator->id,
            'city_id' => $bandung->id,
            'name' => 'Pool Cicaheum',
            'address' => 'Jl. Raya Cicaheum, Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);

        // Armada
        $vehicle = Vehicle::create([
            'operator_id' => $operator->id,
            'plate_number' => 'B 1234 XYZ',
            'brand' => 'Toyota Hiace',
            'capacity' => 14,
            'status' => 'available',
        ]);

        // Rute
        $route = Route::create([
            'operator_id' => $operator->id,
            'origin_city_id' => $jakarta->id,
            'destination_city_id' => $bandung->id,
            'price' => 150000,
            'estimated_duration' => '3 jam',
        ]);

        // Jadwal
        Schedule::create([
            'route_id' => $route->id,
            'vehicle_id' => $vehicle->id,
            'departure_date' => now()->addDay(),
            'departure_time' => '08:00',
            'available_seats' => 14,
            'status' => 'open',
        ]);
    }
}