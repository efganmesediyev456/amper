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

Route::redirect('/', 'admin');

Route::get('test', function(){
    $service  = new App\Services\Api\Products\ProductMonthPrice;
    dd($service->monthlyPayment(1000,12, 12));
});
