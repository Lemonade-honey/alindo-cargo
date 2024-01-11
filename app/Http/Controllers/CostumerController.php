<?php

namespace App\Http\Controllers;

use App\Models\costumer\Costumer;
use App\Models\costumer\CostumerTetap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CostumerController extends Controller
{
    public function index(){

        $costumers = Costumer::get();
        $hubungan = CostumerTetap::with("costumer1", "costumer2")->get();

        $costumers->each(function($costumer){
            $costumer->alamat = strlen($costumer->alamat) > 50 ? substr($costumer->alamat, 0, 50) ."..." : $costumer->alamat;
        });

        return view("costumer.index", compact("costumers", "hubungan"));
    }

    public function createCostumerPost(Request $request){
        $request->validate([
            "name" => ["required", "min:5", "max:255"],
            "kontak" => ["required", "numeric", "min:6", "max:255"],
            "alamat" => ["required"]
        ]);

        try{

            $costumer = Costumer::create([
                "name" => $request->input("name"),
                "kontak" => $request->input("kontak"),
                "alamat" => $request->input("alamat")
            ]);

            Log::info("costumer $costumer->name berhasil dibuat", [
                "user" => auth()->user()->email
            ]);

            return redirect(url()->previous())->with("success", "costumer berhasil dibuat");
        } catch(Throwable $th){
            Log::error("costumer gagal dibuat baru", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "costumer gagal dibuat");
        }
    }

    public function edit($id){
        $costumer = Costumer::findOrFail($id);

        return view("costumer.edit", compact("costumer"));
    }

    public function editPost($id, Request $request){

        $request->validate([
            "name" => ["required", "min:5", "max:255"],
            "kontak" => ["required", "numeric", "min:6", "max:255"],
            "alamat" => ["required"]
        ]);

        $costumer = Costumer::findOrFail($id);

        try {
            $costumer->name = $request->input("name");
            $costumer->kontak = $request->input("kontak");
            $costumer->alamat = $request->input("alamat");
            $costumer->save();

            Log::info("costumer $costumer->name, $costumer->kontak berhasil diupdate", [
                "user" => auth()->user()->email
            ]);

            return back()->with("error", "costumer berhasil diupdate");
        } catch (Throwable $th) {
            Log::error("costumer gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "costumer gagal diupdate");
        }

    }

    public function costumerDelete($id){
        $costumer = Costumer::findOrFail($id);

        try{
            $name = $costumer->name;
            $costumer->delete();

            Log::info("costumer $name berhasil dihapus", [
                "user" => auth()->user()->email
            ]);

            return redirect(url()->previous())->with("success", "costumer berhasil dihapus");
        } catch(Throwable $th){
            Log::error("costumer gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "costumer gagal dihapus");
        }
    }

    // relasi antar costumer
    public function langgananCreatePost(Request $request){
        $request->validate([
            "costumer1" => ["required", "exists:costumers,id"],
            "costumer2" => ["required", "exists:costumers,id", "different:costumer1"],
            "hubungan" => ["required", "max:25", "min:4", "unique:costumer_tetaps,nama"]
        ]);

        try{

            $hubungan = CostumerTetap::create([
                "nama" => $request->input("hubungan"),
                "costumer_1" => $request->input("costumer1"),
                "costumer_2" => $request->input("costumer2")
            ]);

            Log::info("costumer $hubungan berhasil dibuat", [
                "user" => auth()->user()->email
            ]);

            return redirect(url()->previous())->with("success", "hubungan berhasil dibuat");
        } catch(Throwable $th){
            Log::error("costumer hubungan gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "hubungan gagal dibuat");
        }
    }

    public function langgananCreateDelete($id){
        $hubungan = CostumerTetap::findOrFail($id);

        try{
            $nama = $hubungan->nama;
            $hubungan->delete();

            Log::info("costumer hubungan $nama berhasil dihapus", [
                "user" => auth()->user()->email
            ]);

            return redirect(url()->previous())->with("success", "hubungan berhasil dihapus");
        } catch(Throwable $th){
            Log::error("costumer hubungan gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "hubungan gagal dihapus");
        }
    }
}
