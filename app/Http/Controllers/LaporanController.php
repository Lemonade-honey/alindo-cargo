<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class LaporanController extends Controller
{
    private \App\Service\LaporanServiceInterface $laporanService;
    private \App\Service\SpreadsheetServiceInterface $spreadSheetService;

    public function __construct(\App\Service\LaporanServiceInterface $laporanServiceInterface, \App\Service\SpreadsheetServiceInterface $spreadsheetServiceInterface){
        $this->laporanService = $laporanServiceInterface;
        $this->spreadSheetService = $spreadsheetServiceInterface;
    }

    public function index(){
        $laporans = Laporan::get();

        return view("laporan.index", compact("laporans"));
    }

    public function createPost(Request $request){
        $request->validate([
            "tanggal" => ["required", "date"]
        ]);

        if(Laporan::where("tanggal", date('Y-m-d', strtotime($request->input("tanggal"))))->count() > 0){
            return redirect()->back()->with("error", "tanggal sudah pernah dibuat");
        }

        try{
            $laporan = Laporan::create([
                "tanggal" => date('Y-m-d', strtotime($request->input("tanggal")))
            ]);

            Log::info("laporan tanggal $laporan->tanggal berhasil dibuat", [
                "user" => "email"
            ]);

            return redirect()->back()->with("success", "laporan berhasil dibuat");
        } catch(Throwable $th){

            Log::error("laporan gagal dibuat", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect()->back()->with("error", "laporan gagal dibuat");
        }
    }

    public function detail($tanggal){
        $laporan = Laporan::where("tanggal", date('Y-m-d', strtotime($tanggal)))->first();

        abort_if(!$laporan, 404);

        // cache data
        $key = "laporan-" . date('F Y', strtotime($laporan->tanggal));
        $cacheFile = Cache::get($key);

        if($cacheFile){

            return view("laporan.detail", [
                "laporan" => $cacheFile,
                "tanggal" => $tanggal
            ])->with('info', 'menggunakan cache');
        }

        $invoices = \App\Models\invoice\Invoice::with("invoicePerson", "invoiceData", "invoiceCost", "invoiceVendors")->whereMonth("created_at", date('m', strtotime($laporan->tanggal)))
        ->whereYear("created_at", date('Y', strtotime($laporan->tanggal)))
        ->orderBy("id","desc")
        ->get();

        $laporan->invoices = $this->laporanService->dataInvoiceBulan($invoices);

        $laporan->statistik = $this->laporanService->statistikLaporanInvoiceBulan($invoices);

        Cache::put($key, $laporan, 60); // 60 menit

        return view("laporan.detail", compact("laporan", "tanggal"))->with('info', 'tanpa cache');
    }

    public function deleteLaporan($tanggal){
        $laporan = Laporan::where("tanggal", date('Y-m-d', strtotime($tanggal)))->first();

        abort_if(!$laporan, 404);

        try{

            $nama = date('F Y', strtotime($laporan->tanggal));
            $laporan->delete();

            Log::info("laporan $nama berhasil dihapus", [
                "user" => "email"
            ]);

            return redirect()->route('laporan')->with("success", "laporan berhasil di hapus");
        } catch(Throwable $th){
            Log::error("laporan gagal dihapus", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "laporan gagal di hapus");
        }
    }

    public function downloadLaporanSpreadsheet($tanggal){
        $laporan = Laporan::where("tanggal", date('Y-m-d', strtotime($tanggal)))->first();

        abort_if(!$laporan, 404);

        $invoices = \App\Models\invoice\Invoice::whereMonth("created_at", date('m', strtotime($laporan->tanggal)))
        ->whereYear("created_at", date('Y', strtotime($laporan->tanggal)))
        ->orderBy("id","desc")
        ->limit(100)
        ->get();
        
        try{

            $filename = $this->spreadSheetService->createSpreadsheet($this->spreadSheetService->laporanToSpreadsheet($invoices, $tanggal), date('F Y', strtotime($tanggal)));

            if(file_exists(public_path("temp/$filename"))){

                readfile(public_path("temp/$filename"));
                unlink(public_path("temp/$filename"));

                Log::info("laporan berhasil dicetak dan dihapus", [
                    "user" => "email"
                ]);

                return redirect(url()->previous())->with("success", "laporan berhasil di download");
            }

            Log::warning("laporan $laporan->tanggal berhasil dicetak, tapi gagal dihapus", [
                "user" => "email",
                "laporan" => date('F Y', strtotime($tanggal))
            ]);

            return redirect(url()->previous())->with("warning", "laporan gagal di download, namun tersimpan di storage temporary. hubungi admin !");
        } catch(Throwable $th){
            Log::error("laporan gagal dicetak ke spreadsheet", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "laporan gagal di download");
        }
    }
}
