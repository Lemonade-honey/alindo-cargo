<?php

namespace App\Http\Controllers;

use App\Models\invoice\Invoice;
use App\Service\InvoiceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{

    private InvoiceServiceInterface $invoiceService;

    public function __construct(InvoiceServiceInterface $invoiceServiceInterface){
        $this->invoiceService = $invoiceServiceInterface;
    }

    public function paymentInvoice($invoice){
        $invoice = Invoice::with('invoiceCost')->where("invoice", $invoice)->first();

        return view("invoice.pembayaran", compact("invoice"));
    }

    public function paymentInvoicePost($invoice, Request $request){
        $request->validate([
            "metode" => ["required", "in:cash,kartu,transfer"],
            "status" => ["required", "in:belum bayar,lunas,belum lunas"],
            "deskripsi" => ["required", "max:255"],
            "bukti" => ["nullable", "mimes:png,jpeg,jpg,pdf,zip", "max:6000"]
        ]);

        $invoice = Invoice::with('invoiceCost')->where("invoice", $invoice)->first();

        abort_if(!$invoice, 404, "data not found");

        try{
            DB::beginTransaction();

            if($request->has('bukti')){
                $type = $request->file('bukti')->getClientOriginalExtension();
                $filename = $invoice->invoice . "_" . date('dmy') . "_" . Str::random() . "." . $type;

                if(Storage::exists("transaksi/" . $invoice->invoiceCost->bukti)){
                    // delete old file
                    Storage::delete("transaksi/" . $invoice->invoiceCost->bukti);
                }

                Storage::putFileAs("transaksi", $request->file('bukti'), $filename);

                $invoice->InvoiceCost->update([
                    "metode" => $request->input("metode"),
                    "status" => $request->input("status"),
                    "deskripsi" => $request->input("deskripsi"),
                    "bukti" => $filename
                ]);
            }else{
                $invoice->InvoiceCost->update([
                    "metode" => $request->input("metode"),
                    "status" => $request->input("status"),
                    "deskripsi" => $request->input("deskripsi")
                ]);
            }

            $invoice->history = $this->invoiceService->addHistory('update', 'set pembayaran invoice ke-' . $request->input('status'), $invoice->history);
            $invoice->save();

            DB::commit();

            Log::info("pembayaran update pada invoice: " . $invoice->invoice . " status ke-" . $request->input("status"), [
                "user" => "email"
            ]);

            return back()->with("success", "berhasil update pembayaran invoice");
        } catch(Throwable $th){
            DB::rollBack();
            Log::error("gagal update pembayaran invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "gagal dalam update pembayaran");
        }
    }

    // liat foto bukti transaksi
    public function viewTransaksi($filename){
        $file = storage_path("app/transaksi/$filename");

        return response()->file($file);
    }
}
