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
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('account_name');
            $table->string('account_number');

            // fiscal_manager   
            $table->foreignId('fiscal_manager_id')->constrained('users');

            $table->foreignId('fiscal_officer_id')->constrained('users');

            
            
            //expiration date
            $table->date('expiration_date');
            $table->string('account_status')->default('active');
            $table->string('account_type');


        });

        // can have many users
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_accounts');
    }
};
