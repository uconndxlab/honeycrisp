<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
            return redirect('/facilities');
});

Route::resource(
    'facilities',
    'App\Http\Controllers\FacilityController'
);

Route::resource(
    'facilities.services',
    'App\Http\Controllers\FacilityServiceController'
);


// services resource
Route::resource(
    'services',
    'App\Http\Controllers\ServiceController'
);