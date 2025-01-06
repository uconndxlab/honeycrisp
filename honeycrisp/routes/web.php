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
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;


Route::get('/', function () {
    // facilities.index controller
    return redirect()->route('orders.index');
})->name('home');


Route::resource('facilities', FacilityController::class);



Route::get('/products/{product}/', [ProductController::class, 'show'])->name('products.show');

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
    Route::post('/orders/bulkUpdate', [OrderController::class, 'bulkUpdate'])->name('orders.bulkUpdate');

    Route::resource('categories', CategoryController::class);


    Route::resource('orders', OrderController::class);
    Route::post('orders/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
    Route::post('orders/remove-item', [OrderController::class, 'removeItem'])->name('orders.remove-item');
    Route::post('orders/import-csv', [OrderController::class, 'importCsv'])->name('orders.import-csv');
    Route::get('orders/{order}/sendToCustomer', [OrderController::class, 'sendToCustomer'])->name('orders.sendToCustomer');
    Route::get('orders/{order}/financialFiles', [OrderController::class, 'financialFiles'])->name('orders.financialFiles');
    
    Route::get('/facilities/{facilityAbbreviation}/exportInvoices', [FacilityController::class, 'exportInvoices'])->name('facilities.exportInvoices');
    
    //orders.financialFiles.download
    Route::get('orders/{order}/financialFiles/download', [OrderController::class, 'downloadFinancialFile'])->name('orders.financialFiles.download');


    Route::resource('payment-accounts', PaymentAccountController::class);
    Route::get('payment-accounts/{paymentAccount}/authorizedUsers', [PaymentAccountController::class, 'authorizedUsers'])->name('payment-accounts.authorizedUsers');
    Route::post('payment-accounts/{paymentAccount}/add-authorized-user', [PaymentAccountController::class, 'addAuthorizedUser'])->name('payment-accounts.authorizedUsers.store');
    //payment-accounts.authorized-users.destroy
    Route::delete('payment-accounts/{paymentAccount}/authorized-users/{user}', [PaymentAccountController::class, 'removeAuthorizedUser'])->name('payment-accounts.authorizedUsers.destroy');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('products/create/{facilityAbbreviation}', [ProductController::class, 'create'])->name('products.create');


    Route::get('categories/create/{facilityAbbreviation}', [CategoryController::class, 'create']);
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    

    Route::get('price-groups/create/{product}', [PriceGroupController::class, 'create'])->name('price-groups.create');
    Route::post('price-groups', [PriceGroupController::class, 'store'])->name('price-groups.store');
    
    Route::get('price-groups/{priceGroup}/edit', [PriceGroupController::class, 'edit'])->name('price-groups.edit');
    Route::delete('price-groups/{priceGroup}', [PriceGroupController::class, 'destroy'])->name('price-groups.destroy');
    Route::put('price-groups/{priceGroup}', [PriceGroupController::class, 'update'])->name('price-groups.update');


    Route::get('orders/create/{facilityAbbreviation}', [OrderController::class, 'create']);

    Route::get('reservations/create/{facilityAbbreviation}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::get('reservations/create/product/{product}', [ReservationController::class, 'createForProduct'])->name('reservations.create.product');
    Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

});

// register routes
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'submitRegister'])->name('register.submit');




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


/** 
 * Emails
 */


Route::get('/mail/orderCreated', function () {
    $order = \App\Models\Order::find(4); // Replace with an actual order ID
    
    if (!$order || !$order->customer || !$order->customer->email) {
        return response()->json(['error' => 'Order or customer email not found.'], 404);
    }

    try {
        // Send the email
        Mail::to($order->customer->email)->send(new OrderCreated($order));
        
        return response()->json(['message' => 'Email sent successfully to ' . $order->customer->email], 200);
    } catch (\Exception $e) {
        // Catch and display any errors
        return response()->json([
            'error' => 'Failed to send email.',
            'details' => $e->getMessage()
        ], 500);
    }

});
