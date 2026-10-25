<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('origin', 100)->nullable()->after('operator_id');
            $table->string('destination', 100)->nullable()->after('origin');
            $table->unsignedBigInteger('origin_city_id')->nullable()->change();
            $table->unsignedBigInteger('destination_city_id')->nullable()->change();
        });

        // Backfill origin and destination from existing cities for existing routes
        $routes = DB::table('routes')->get();
        foreach ($routes as $route) {
            $originName = $route->origin_city_id
                ? DB::table('cities')->where('id', $route->origin_city_id)->value('name')
                : null;
            $destinationName = $route->destination_city_id
                ? DB::table('cities')->where('id', $route->destination_city_id)->value('name')
                : null;

            DB::table('routes')->where('id', $route->id)->update([
                'origin' => $originName ?? 'Kota Asal',
                'destination' => $destinationName ?? 'Kota Tujuan',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn(['origin', 'destination']);
            $table->unsignedBigInteger('origin_city_id')->nullable(false)->change();
            $table->unsignedBigInteger('destination_city_id')->nullable(false)->change();
        });
    }
};
