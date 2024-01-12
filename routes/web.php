<?php

use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RoleController;
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

Route::middleware("guest")->group(function(){
    Route::get('/login', [DashboardController::class, "login"])->name("login");
    Route::post('/login', [DashboardController::class, "loginPost"]);
});

Route::middleware("auth")->group(function(){
    Route::prefix("dashboard")->group(function(){
        Route::get("/", function(){
            return view("dashboard");
        })->name("dashboard");

        Route::get("/logout", [DashboardController::class, "logout"])->name("logout");
    
        // Route Path Invoice
        Route::prefix("invoice")->group(function(){
            Route::get("/", [InvoiceController::class, "index"])->middleware('permission:invoice')->name("invoice");
    
            Route::get("/create", [InvoiceController::class, "create"])->middleware('permission:invoice')->name("invoice.create");
            Route::post("/create", [InvoiceController::class, "createPost"])->middleware('permission:invoice');
            
            Route::get("/{invoice}", [InvoiceController::class, "detail"])->name("invoice.detail");
            Route::get("{invoice}/cetakResi", [PdfController::class, "cetakResiInvoice"])->name("invoice.cetak.resi");

            Route::middleware('permission:invoice-kelola')->group(function(){
                // edit invoice
                Route::get("/{invoice}/edit", [InvoiceController::class, "edit"])->name("invoice.edit");
                Route::post("/{invoice}/edit", [InvoiceController::class, "editPost"]);

                // vendor relation
                Route::get("/{invoice}/vendors", [InvoiceController::class, "vendorInvoice"])->name("invoice.vendor");
                Route::post("/{invoice}/vendors", [InvoiceController::class, "vendorInvoicePost"]);
        
                Route::get("/{invoice}/vendors/delete/{id}", [InvoiceController::class, "vendorInvoiceDelete"])->name("invoice.vendor.delete");
            });

            // pembayaran route
            Route::middleware("permission:invoice-pembayaran")->group(function(){
                Route::get("/{invoice}/pembayaran", [PaymentController::class, "paymentInvoice"])->name("invoice.pembayaran");
                Route::post("/{invoice}/pembayaran", [PaymentController::class, "paymentInvoicePost"]);
            });
    
    
            // set status invoice
            Route::post("/{invoice}/update/status", [InvoiceController::class, "setStatusInvoice"])->middleware("permission:invoice-status")->name("invoice.status");

            // delete invoice
            Route::get("/{invoice}/delete/invoice", [InvoiceController::class, "deleteInvoice"])->middleware("permission:invoice-delete")->name("invoice.delete");
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
    
        Route::prefix("laporan")->group(function(){
            Route::get("/", [LaporanController::class, "index"])->name("laporan");
            Route::post("/", [LaporanController::class, "createPost"]);
            Route::get("/detail/{tanggal}", [LaporanController::class, "detail"])->name("laporan.detail");
            Route::get("/detail/{tanggal}/download", [LaporanController::class, "downloadLaporanSpreadsheet"])->name("laporan.detail.download");
    
            Route::get("/detail/{tanggal}/deletelaporan", [LaporanController::class, "deleteLaporan"])->name("laporan.delete");
        });
    
        Route::prefix("roles")->group(function(){
            Route::get("/", [RoleController::class, "index"])->name("role");
            Route::get("/buat", [RoleController::class, "create"])->name("role.create");
            Route::post("/buat", [RoleController::class, "createPost"]);
            Route::get("/detail/{role}", [RoleController::class, "detail"])->name("role.detail");
    
            Route::post("/detail/{role}/addUser", [RoleController::class, "addUserToRole"])->name("role.user.add");
            Route::get("/detail/{role}/deleteUser/{id}", [RoleController::class, "deleteUserToRole"])->name("role.user.delete");
    
            Route::post("/detail/{role}/updateNama", [RoleController::class, "updateNamaRolePost"])->name("role.nama.post");
            Route::post("/detail/{role}/updatePermission", [RoleController::class, "updatePermissionRolePost"])->name("role.permission.post");
            Route::get("/detail/{role}/deleteRole", [RoleController::class, "deleteRole"])->name("role.delete");
        });

        Route::prefix("/costumer")->group(function(){
            Route::get("/", [CostumerController::class, "index"])->name("costumer");
            Route::post("/createCostumer", [CostumerController::class, "createCostumerPost"])->name("costumer.create.post");
            Route::get("/{id}/edit", [CostumerController::class, "edit"])->name("costumer.edit");
            Route::post("/{id}/edit", [CostumerController::class, "editPost"]);
            Route::get("/{id}/deleteCostumer", [CostumerController::class, "costumerDelete"])->name("costumer.delete");

            Route::post("/createHubungan", [CostumerController::class, "langgananCreatePost"])->name("langganan.create.post");
            Route::get("/{id}/deleteHubungan", [CostumerController::class, "langgananCreateDelete"])->name("langganan.delete");
        });
    
        Route::prefix("view")->group(function(){
            Route::get("/transaksi/{file}", [PaymentController::class, "viewTransaksi"])->name("view.transaksi");
        });
    });
    
});