<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class KotaController extends Controller
{
    public function index(){
        $kotas = Kota::get();

        return view("kota.index", compact("kotas"));
    }

    public function createPost(Request $request){
        $request->validate([
            "nama" => ["required", "unique:kotas,kota"],
            "harga" => ["required", "numeric"]
        ]);

        try{
            $kota = Kota::create([
                "kota" => $request->input("nama"),
                "harga" => $request->input("harga")
            ]);

            Log::info("kota berhasil dibuat. kota: " . $kota->kota, [
                "user" => "email",
            ]);
            
            return back()->with("success", "Berhasil menambahkan data kota baru");
        } catch(Throwable $th){
            Log::error("Kota gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "Gagal membuat data kota baru");
        }
    }

    public function detail($id){
        $kota = Kota::findOrFail($id);

        return view("kota.detail", compact("kota"));
    }

    public function detailPost($id, Request $request){
        $request->validate([
            "nama" => ["required", "unique:kotas,kota," . $id],
            "harga" => ["required", "numeric"]
        ]);

        $kota = Kota::findOrFail($id);

        try{
            $kota->kota = $request->input("nama");
            $kota->harga = $request->input("harga");
            $kota->save();

            Log::notice("kota di update", [
                "user" => "email"
            ]);

            return back()->with("success", "kota berhasil diupdate");
        } catch(Throwable $th){
            Log::error("kota gagal diupdate", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "kota gagal diupdate");
        }
    }

    public function delete($id){
        $kota = Kota::findOrFail($id);

        try{
            $kota->delete();

            Log::notice("kota berhasil dihapus", [
                "user" => "email"
            ]);

            return redirect()->route('kota')->with("success", "Kota berhasil dihapus");
        } catch(Throwable $th){
            Log::error("kota gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with('error', "gagal menghapus kota");
        }
    }
}
