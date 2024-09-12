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
            $table->boolean('can_reserve')->default(false);
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->dateTime('reservation_start');
            $table->dateTime('reservation_end');
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->enum('status', ['pending', 'active', 'cancelled', 'complete'])->default('pending');
            $table->text('notes')->nullable();
        });

        Schema::create('schedule_rules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('time_of_day_start')->default("08:00"); // Defaults to 8AM
            $table->string('time_of_day_end')->default("17:00"); // Defaults to 5PM
            $table->enum('day', ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('schedule_rules');

        Schema::dropIfExists('reservation');

        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('can_reserve');
        });
    }
};
