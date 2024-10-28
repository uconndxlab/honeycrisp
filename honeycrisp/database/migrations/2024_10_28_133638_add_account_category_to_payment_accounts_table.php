<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->string('account_category')->nullable()->after('existing_column'); // Replace 'existing_column' with the column after which you want this field.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->dropColumn('account_category');
        });
    }
};
