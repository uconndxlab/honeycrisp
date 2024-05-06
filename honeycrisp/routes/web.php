<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentAccountController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    // facilities.index controller
    return redirect()->route('facilities.index');

});


Route::resource('facilities', FacilityController::class);
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::get('orders/create/{facilityAbbreviation}', [OrderController::class, 'create']);

Route::resource('payment-accounts', PaymentAccountController::class);


/**
 * User Routes
 */
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'submitLogin'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('users', UserController::class);

// register routes
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'submitRegister'])->name('register.submit');


