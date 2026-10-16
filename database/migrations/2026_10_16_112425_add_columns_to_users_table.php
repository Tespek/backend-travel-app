<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['super_admin', 'admin_travel', 'customer', 'driver'])
                      ->default('customer')->after('password');
            }
            
            if (!Schema::hasColumn('users', 'operator_id')) {
                $table->unsignedBigInteger('operator_id')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'banned'])->default('active')->after('operator_id');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom ada sebelum menghapusnya
            if (Schema::hasColumn('users', 'avatar')) $table->dropColumn('avatar');
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'status')) $table->dropColumn('status');
            if (Schema::hasColumn('users', 'operator_id')) $table->dropColumn('operator_id');
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
        });
    }
};