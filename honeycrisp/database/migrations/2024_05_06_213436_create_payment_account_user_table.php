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
        Schema::create('payment_account_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_account_id')->constrained();
            $table->foreignId('user_id')->constrained();


            $table->enum('role', ['owner', 'fiscal_officer', 'account_manager', 'authorized_user'])
                ->default('authorized_user');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_account_user');
    }
};
