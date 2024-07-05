<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentAccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    // facilities.index controller
    return redirect()->route('facilities.index');

});


Route::resource('facilities', FacilityController::class);

Route::resource('products', ProductController::class);
Route::get('products/create/{facilityAbbreviation}', [ProductController::class, 'create']);

Route::resource('orders', OrderController::class);
Route::post('orders/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
Route::post('orders/remove-item', [OrderController::class, 'removeItem'])->name('orders.remove-item');
Route::post('ordres/import-csv', [OrderController::class, 'importCsv'])->name('orders.import-csv');
Route::get('orders/{order}/sendToCustomer', [OrderController::class, 'sendToCustomer'])->name('orders.sendToCustomer');

Route::get('orders/create/{facilityAbbreviation}', [OrderController::class, 'create']);

Route::resource('ledgers', LedgerController::class);

Route::resource('payment-accounts', PaymentAccountController::class);

Route::resource('categories', CategoryController::class);
Route::get('categories/create/{facilityAbbreviation}', [CategoryController::class, 'create']);

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


