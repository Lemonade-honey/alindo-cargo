<?php

use App\Http\Controllers\InvoiceController;
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
    return view('index');
});

Route::prefix("dashboard")->group(function(){
    Route::get("/", function(){
        return view("dashboard");
    });

    // Route Path Invoice
    Route::prefix("invoice")->group(function(){
        Route::get("/", [InvoiceController::class, "index"])->name("invoice");
        Route::get("/{invoice}", [InvoiceController::class, "detail"])->name("invoice.detail");
    });
});
