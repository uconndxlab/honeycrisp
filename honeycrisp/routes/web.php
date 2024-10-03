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
use App\Http\Controllers\PriceGroupController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ScheduleRuleController;
use App\Http\Controllers\ReservationController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    // facilities.index controller
    return redirect()->route('orders.index');
})->name('home');


Route::resource('facilities', FacilityController::class);

Route::resource('products', ProductController::class);
Route::get('products/create/{facilityAbbreviation}', [ProductController::class, 'create']);



Route::get('orders/create/{facilityAbbreviation}', [OrderController::class, 'create']);

Route::resource('ledgers', LedgerController::class);


Route::resource('categories', CategoryController::class);
Route::get('categories/create/{facilityAbbreviation}', [CategoryController::class, 'create']);

/**
 * User Routes
 */
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'submitLogin'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/login/cas', [LoginController::class, 'casLogin'])->name('cas.login');
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    // /orders/export with a request full of filters
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::resource('orders', OrderController::class);
    Route::post('orders/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
    Route::post('orders/remove-item', [OrderController::class, 'removeItem'])->name('orders.remove-item');
    Route::post('ordres/import-csv', [OrderController::class, 'importCsv'])->name('orders.import-csv');
    Route::get('orders/{order}/sendToCustomer', [OrderController::class, 'sendToCustomer'])->name('orders.sendToCustomer');
    Route::resource('payment-accounts', PaymentAccountController::class);
    Route::get('payment-accounts/{paymentAccount}/authorizedUsers', [PaymentAccountController::class, 'authorizedUsers'])->name('payment-accounts.authorizedUsers');
    Route::post('payment-accounts/{paymentAccount}/add-authorized-user', [PaymentAccountController::class, 'addAuthorizedUser'])->name('payment-accounts.authorizedUsers.store');
    //payment-accounts.authorized-users.destroy
    Route::delete('payment-accounts/{paymentAccount}/authorized-users/{user}', [PaymentAccountController::class, 'removeAuthorizedUser'])->name('payment-accounts.authorizedUsers.destroy');
    
});

// register routes
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'submitRegister'])->name('register.submit');


Route::get('price-groups/create/{product}', [PriceGroupController::class, 'create'])->name('price-groups.create');
Route::post('price-groups', [PriceGroupController::class, 'store'])->name('price-groups.store');

Route::get('price-groups/{priceGroup}/edit', [PriceGroupController::class, 'edit'])->name('price-groups.edit');
Route::delete('price-groups/{priceGroup}', [PriceGroupController::class, 'destroy'])->name('price-groups.destroy');
Route::put('price-groups/{priceGroup}', [PriceGroupController::class, 'update'])->name('price-groups.update');

/**
 * System exports
 */
Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
Route::get('exports/download/{export}', [ExportController::class, 'download'])->name('exports.download');
Route::get('exports/{id}', [ExportController::class, 'show'])->name('exports.show');

/**
 * Schedule Rules & Reservations
 */
Route::get('schedule-rules/create', [ScheduleRuleController::class, 'createForm'])->name('schedule-rules.create');
Route::post('schedule-rules/create', [ScheduleRuleController::class, 'store'])->name('schedule-rules.store');
