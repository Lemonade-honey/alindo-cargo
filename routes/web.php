<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
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

        // vendor relation
        Route::get("/{invoice}/vendors", [InvoiceController::class, "vendorInvoice"])->name("invoice.vendor");
        Route::post("/{invoice}/vendors", [InvoiceController::class, "vendorInvoicePost"]);

        Route::get("/{invoice}/vendors/delete/{id}", [InvoiceController::class, "vendorInvoiceDelete"])->name("invoice.vendor.delete");
    });

    Route::prefix("vendors")->group(function(){
        Route::get("/", [VendorController::class, "index"])->name("vendor");
        Route::post("/", [VendorController::class, "createPost"]);

        Route::get("/{id}", [VendorController::class, "detail"])->name("vendor.detail");
        Route::post("/{id}", [VendorController::class, "detailPost"]);
        Route::post("/{id}/wilayahPost", [VendorController::class, "wilayahPost"])->name("vendor.wilayah.post");

        Route::get("/{id}/deteleVendor", [VendorController::class, "delete"])->name("vendor.delete");

        Route::get("/{id}/wilayah/{id_gabung}", [VendorController::class, "wilayahDetail"])->name("vendor.wilayah.detail");
        Route::post("/{id}/wilayah/{id_gabung}", [VendorController::class, "wilayahDetailPost"]);

        Route::get("/{id}/wilayah/{id_gabung}/deleteWilayah", [VendorController::class, "wilayahDetailDelete"])->name("vendor.wilayah.delete");
    });

    Route::prefix("kota")->group(function(){
        Route::get("/", [KotaController::class, "index"])->name("kota");
        Route::post("/", [KotaController::class, "createPost"])->name("kota.create.post");

        Route::get("/{id}/detail", [KotaController::class, "detail"])->name("kota.detail");
        Route::post("/{id}/detail", [KotaController::class, "detailPost"]);

        Route::get("/{id}/deleteKota", [KotaController::class, "delete"])->name("kota.delete");
    });

    Route::prefix("users")->group(function(){
        Route::get("/", [UserController::class, "index"])->name("user");
        Route::post("/", [UserController::class, "createPost"]);
        Route::get("/detail/{uid}", [UserController::class, "detail"])->name("user.detail");
        
        Route::post("/detail/{uid}/akunPost", [UserController::class, "updateAkunPost"])->name("user.akun.post");
        Route::post("/detail/{uid}/passwordReset", [UserController::class, "updatePasswordPost"])->name("user.password.post");
        Route::post("/detail/{uid}/akunBlock", [UserController::class, "updateBlockPost"])->name("user.block.post");
        
        Route::get("/{id}/deleteUser", [UserController::class, "deleteUser"])->name("user.delete");
    });

    Route::prefix("view")->group(function(){
        Route::get("/transaksi/{file}", [PaymentController::class, "viewTransaksi"])->name("view.transaksi");
    });
});
