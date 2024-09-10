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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            

            // foreign key to the products table so we can look up the product details later, but product_id can be null if we're adding a custom item
            $table->unsignedBigInteger('product_id')->nullable();

            $table->unsignedBigInteger('order_id');
            $table->string('description');
            $table->string('name');
            
            $table->decimal('quantity', 8, 1);

            // price is stored in cents
            $table->integer('price');

            // Define foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
