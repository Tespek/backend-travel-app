<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->enum('status', ['pending', 'completed', 'refunded'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['operator_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};