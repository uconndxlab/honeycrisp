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
        Schema::create('orders', function (Blueprint $table) {


            

            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');


            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('payment_account')->nullable();

            $table->text('status')->enum('quote', 'pending', 'approved', 'in_progress', 'canceled', 'reconciled', 'invoice', 'sent_to_kfs', 'archived');
            
            $table->json('tags')->nullable();
            $table->decimal('total', 10, 2)->nullable();

            $table->text('price_group')->enum('internal', 'external_nonprofit', 'external_forprofit');
            $table->text('company_name')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('payment_account')->references('id')->on('payment_accounts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
