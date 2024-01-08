<?php

namespace App\Http\Controllers;

use App\Models\vendor\Vendor;
use App\Models\vendor\VendorDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class VendorController extends Controller
{
    public function index(){
        $vendors = Vendor::with("wilayah")->get();

        return view("vendor.index", compact("vendors"));
    }

    public function createPost(Request $request){
        $request->validate([
            "nama" => ["required", "unique:vendors,nama"],
            "deskripsi" => ["nullable", "max:255"]
        ]);

        try{
            $vendor = Vendor::create([
                "nama" => $request->input("nama"),
                "deskripsi" => $request->input("deskripsi")
            ]);

            Log::info("vendor baru berhasil dibuat. vendor: " . $vendor->nama, [
                "user" => "email"
            ]);

            return redirect()->route("vendor.detail", ["id" => $vendor->id])->with("success", "berhasil membuat vendor: ");
        } catch(Throwable $th){

            Log::error("vendor baru gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "gagal membuat vendor baru. Coba lagi nanti");
        }
    }

    public function detail($id){
        $vendor = Vendor::with("wilayah")->findOrFail($id);

        $kotas = \App\Models\Kota::orderBy("kota", "desc")->get();

        return view("vendor.detail", compact("vendor", "kotas"));
    }

    public function detailPost($id, Request $request){

        $request->validate([
            "nama" => ["required", "unique:vendors,nama," . $id],
            "deskripsi" => ["nullable", "max:255"]
        ]);

        $vendor = Vendor::findOrFail($id);

        try{
            $vendor->nama = $request->input("nama");
            $vendor->deskripsi = $request->input("deskripsi");
            $vendor->save();

            Log::notice("Vendor Update data. vendor: " . $vendor->id, [
                "user" => "email"
            ]);

            return back()->with("success", "Vendor berhasil diupdate");
        } catch(Throwable $th){
            
            Log::error("vendor update gagal. vendor:" . $vendor->id, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("success", "Vendor berhasil diupdate");
        }
    }

    public function delete($id){
        $vendor = Vendor::findOrFail($id);

        try{
            $oldVendor = $vendor->nama;
            $vendor->delete();

            Log::notice("Vendor berhasil dihapus. vendor: $oldVendor", [
                "user" => "email"
            ]);
            return redirect()->route('vendor')->with("success", "Vendor berhasil di hapus");
        } catch(Throwable $th){
            Log::error("vendor gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "vendor gagal dihapus");
        }
    }

    /**
     * Create new wilayah vendor
     */
    public function wilayahPost(Request $request, $id){
        $request->validate([
            "kota" => ["required", "numeric", "exists:kotas,id",
                Rule::unique("vendor_details", "id_kota")->where(function($query) use ($request, $id){
                    return $query->where('id_kota', '=', $request->input("kota"))
                    ->where('id_vendor', '=', $id);
                })
            ],
            "harga" => ["required", "numeric"]
        ],[
            "id_kota.unique" => "Kota sudah terisi"
        ]);

        $vendor = Vendor::findOrFail($id);

        try{
            VendorDetail::create([
                "id_vendor" => $vendor->id,
                "id_kota" => $request->input("kota"),
                "harga" => $request->input("harga"),
            ]);
    
            Log::notice("Vendor Wilayah berhasil ditambahkan. Vendor: " . $vendor->nama, [
                "user" => "email"
            ]);

            return back()->with("success", "wilayah vendor berhasil ditambahkan");
        } catch(Throwable $th){

            Log::error("vendor wilayah gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "error" => $th->getMessage()
            ]);

            return back()->with("error", "wilayah vendor gagal ditambahkan");
        }
    }

    public function wilayahDetail($id, $idGabung){
        $wilayahVendor = VendorDetail::with("kota", "vendor")->findOrFail($idGabung);

        $kotas = \App\Models\Kota::get();

        return view("vendor.wilayah", compact("wilayahVendor", "kotas"));
    }

    public function wilayahDetailPost($id, $idGabung, Request $request){
        $request->validate([
            "kota" => ["required", "numeric", "exists:kotas,id",
                Rule::unique("vendor_details", "id_kota")
                ->ignore($idGabung)
                ->where(function($query) use ($request, $id){
                    return $query->where('id_kota', '=', $request->input("kota"))
                    ->where('id_vendor', '=', $id);
                })
            ],
            "harga" => ["required", "numeric"],
            "deskripsi" => ["nullable", "max:255"]
        ],[
            "kota.unique" => "Wilayah sudah terisi"
        ]);

        $vendorDetail = VendorDetail::findOrFail($idGabung);

        abort_if(!$vendorDetail, 404);

        try{

            $vendorDetail->id_kota = $request->input("kota");
            $vendorDetail->harga = $request->input("harga");
            $vendorDetail->deskripsi = $request->input("deskripsi") ?? null;
            $vendorDetail->save();

            Log::notice("vendor wilayah berhasil diupdate. vendor: $id", [
                "user" => "email"
            ]);

            return back()->with("success", "wilayah vendor berhasil diupdate");
        } catch(Throwable $th){

            Log::error("vendor wilayah gagal di update", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "error" => $th->getMessage()
            ]);

            return back()->with("error", "gagal update wilayah vendor");
        }
    }

    public function wilayahDetailDelete($id, $idGabung){
        $wilayahVendor = VendorDetail::with("kota", "vendor")->findOrFail($idGabung);

        try{

            $idVendor = $wilayahVendor->id_vendor;
            $wilayahVendor->delete();

            Log::notice("vendor wilayah berhasil diupdate. vendor: $idGabung", [
                "user" => "email"
            ]);

            return redirect()->route("vendor.detail", ['id' => $idVendor])->with("success", "wilayah berhasil dihapus");
        } catch(Throwable $th){

            Log::error("vendor wilayah gagal di hapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "error" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal hapus wilayah vendor");
        }
    }
}
