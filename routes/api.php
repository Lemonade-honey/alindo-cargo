<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// route API Vendor
Route::prefix("vendors")->group(function(){
    Route::get("/kota/{id}", function($id){
        $data = \App\Models\vendor\VendorDetail::with('vendor')->where("id_kota", $id)->get();
        
        $vendors = [];

        foreach($data as $vendor){
            $vendors[] = [
                "id" => $vendor->id,
                "name" => $vendor->vendor->nama . " - Rp. " . number_format($vendor->harga)
            ];
        }

        return response()->json($vendors);
    });
});