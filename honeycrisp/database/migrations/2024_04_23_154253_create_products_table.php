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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('description');
            $table->string('unit')->nullable();
            $table->string('unit_price_internal')->nullable();
            $table->string('unit_price_external_nonprofit')->nullable();
            $table->string('unit_price_external_forprofit')->nullable();
            $table->string("recharge_account")->nullable();
            
            // requires approval?
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            

            $table->string('image_url')->nullable();
            $table->json('tags')->nullable();

            // category_id
            $table->foreignId('category_id')->nullable()->constrained();

            

            $table->foreignId('facility_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
