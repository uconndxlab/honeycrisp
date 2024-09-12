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

            $table->text('status')->enum('quote', 'pending', 'approved', 'in_progress', 'canceled', 'reconciled', 'invoice', 'sent_to_kfs', 'archived');
            
            $table->json('tags')->nullable();
            $table->decimal('total', 10, 2)->nullable();

            $table->text('price_group')->enum('internal', 'external_nonprofit', 'external_forprofit');
            $table->text('company_name')->nullable();

            $table->text('mailing_address')->nullable();
            $table->text('purchase_order_number')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('facility_id')->constrained('facilities');
            
            // payment_account_id is optional and can be null, foreign key to payment_accounts table
            $table->foreignId('payment_account_id')->nullable()->constrained('payment_accounts');



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
