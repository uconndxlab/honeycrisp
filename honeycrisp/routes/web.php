<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentAccountController;

Route::get('/', function () {
    return view('facilities.index');
});


Route::resource('facilities', FacilityController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::resource('payment-accounts', PaymentAccountController::class);

