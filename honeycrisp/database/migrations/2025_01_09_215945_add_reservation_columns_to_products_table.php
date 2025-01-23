<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('reservation_interval')->default("30"); // Defaults to 30 minutes
            $table->integer('minimum_reservation_time')->default(30); // Defaults to 30 minutes
            $table->integer('maximum_reservation_time')->default(120); // Defaults to 120 minutes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['reservation_interval', 'minimum_reservation_time', 'maximum_reservation_time']);
        });
    }
};
