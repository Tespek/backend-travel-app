<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->string('plate_number')->unique();
            $table->string('brand');
            $table->integer('capacity');
            $table->enum('status', ['available', 'maintenance', 'inactive'])->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};