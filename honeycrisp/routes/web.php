<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentAccountController;

Route::get('/', function () {
    // facilities.index controller
    return redirect()->route('facilities.index');

});


Route::resource('facilities', FacilityController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::get('orders/create/{facilityAbbreviation}', [OrderController::class, 'create']);

Route::resource('payment-accounts', PaymentAccountController::class);
