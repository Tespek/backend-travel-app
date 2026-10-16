<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            
            // Data Penumpang
            $table->string('passenger_name');
            $table->string('passenger_phone');
            $table->string('passenger_email')->nullable();
            $table->string('seat_number')->nullable();
            
            // Penjemputan
            $table->enum('pickup_type', ['pool', 'door_to_door'])->default('pool');
            $table->foreignId('pickup_point_id')->nullable()->constrained('operator_pickup_points')->onDelete('set null');
            $table->text('pickup_address')->nullable();
            $table->decimal('pickup_latitude', 10, 7)->nullable();
            $table->decimal('pickup_longitude', 10, 7)->nullable();
            
            // Tujuan
            $table->foreignId('destination_id')->constrained('operator_pickup_points')->onDelete('cascade');
            $table->text('destination_address')->nullable();
            
            // Pembayaran
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_gateway_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Status Penjemputan
            $table->enum('pickup_status', [
                'waiting', 'driver_assigned', 'on_the_way', 
                'arrived', 'picked_up', 'completed', 'cancelled'
            ])->default('waiting');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Pembatalan
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};