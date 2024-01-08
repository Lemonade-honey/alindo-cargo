<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
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

        Route::get("/create", [InvoiceController::class, "create"])->name("invoice.create");
        Route::post("/create", [InvoiceController::class, "createPost"]);

        Route::get("/{invoice}/edit", [InvoiceController::class, "edit"])->name("invoice.edit");
        Route::post("/{invoice}/edit", [InvoiceController::class, "editPost"]);

        Route::get("/{invoice}", [InvoiceController::class, "detail"])->name("invoice.detail");

        Route::get("{invoice}/cetakResi", [PdfController::class, "cetakResiInvoice"])->name("invoice.cetak.resi");

        Route::get("/{invoice}/delete/invoice", [InvoiceController::class, "deleteInvoice"])->name("invoice.delete");
        Route::post("/{invoice}/update/status", [InvoiceController::class, "setStatusInvoice"])->name("invoice.status");

        Route::get("/{invoice}/pembayaran", [PaymentController::class, "paymentInvoice"])->name("invoice.pembayaran");
        Route::post("/{invoice}/pembayaran", [PaymentController::class, "paymentInvoicePost"]);
    });

    Route::prefix("view")->group(function(){
        Route::get("/transaksi/{file}", [PaymentController::class, "viewTransaksi"])->name("view.transaksi");
    });
});
