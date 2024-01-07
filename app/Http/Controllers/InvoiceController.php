<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestInvoiceCreate;
use App\Models\invoice\Invoice;
use App\Models\invoice\InvoiceCost;
use App\Models\invoice\InvoiceData;
use App\Models\invoice\InvoicePerson;
use App\Models\invoice\InvoiceTracking;
use App\Models\Kota;
use App\Models\member\RelasiMember;
use App\Service\InvoiceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InvoiceController extends Controller
{

    protected $invoiceService;
    public function __construct(InvoiceServiceInterface $invoiceServiceInterface){
        $this->invoiceService = $invoiceServiceInterface;
    }

    /**
     * GET List all invoice
     * 
     * menggunakan livewire untuk data tablenya
     */
    public function index(){

        $invoices = Invoice::with("invoicePerson", "invoiceCost")->orderBy("id", "desc")->paginate(10);

        // set every invoice expired date
        $invoices->map(function($invoice){
            if($this->invoiceService->expired($invoice)){
                $invoice->status = "warning";
            }
        });

        return view("invoice.index", compact("invoices"));
    }

    /**
     * GET Create Invoice
     */
    public function create(){
        $kotas = Kota::get();

        $members = RelasiMember::get();

        return view("invoice.create", compact("kotas", "members"));
    }

    /**
     * POST Create Invoice
     */
    public function createPost(RequestInvoiceCreate $request){
        // validasi inputan
        $request->validated();

        try{
            DB::beginTransaction();

            $invoice = Invoice::create([
                "asal"=> $request->input("kota-asal"),
                "tujuan"=> $request->input("kota-tujuan"),
                "status"=> "proses",
                "history" => $this->invoiceService->addHistory("post", "membuat invoice baru")
            ]);

            InvoiceData::create([
                "id_invoice" => $invoice->id,
                "form_setting"=> $this->invoiceService->createFormSetting($request),
                "berat" => $request->input("berat"),
                "harga_kg" => $request->input("harga/kg"),
                "koli" => $request->input("koli"),
                "kategori"=> $request->input("keterangan-barang"),
                "pemeriksaan"=> $request->input("pemeriksaan"),
            ]);

            InvoicePerson::create([
                "id_invoice"=> $invoice->id,
                "pengirim"=> $request->input("nama-pengirim"),
                "kontak_pengirim"=> $request->input("kontak-pengirim"),
                "penerima"=> $request->input("nama-penerima"),
                "kontak_penerima"=> $request->input("kontak-penerima"),
                "alamat"=> $request->input("alamat-penerima"),
            ]);

            InvoiceCost::create([
                "id_invoice"=> $invoice->id,
                "biaya_kirim"=> $request->input("biaya-kirim"),
                "biaya_lainnya"=> $this->invoiceService->createBiayalainnya($request),
                "biaya_total"=> $request->input("total-biaya"),
            ]);

            InvoiceTracking::create([
                "id_invoice"=> $invoice->id,
                "tracking" => $this->invoiceService->addTracking("Diterima", "Yogyakarta - Base", "paket diterima oleh Alindo Cargo")
            ]);

            DB::commit();

            Log::info("berhasil membuat invoice: $invoice->invoice", [
                "user" => "user email"
            ]);

            return redirect()->route("invoice.detail", ["invoice" => $invoice->invoice])->with("success", "Invoice berhasil dibuat");
        } catch(Throwable $th){
            DB::rollBack();

            Log::error("gagal dalam membuat invoice", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "gagal dalam membuat invoice. Coba lagi");
        }
    }

    /**
     * GET edit invoice
     */
    public function edit($invoice){
        $invoice = Invoice::where("invoice", $invoice)->first();

        abort_if(!$invoice, 404, "data not found");

        $kotas = Kota::get();

        $members = RelasiMember::get();

        return view("invoice.edit", compact("invoice", "kotas", "members"));
    }

    /**
     * POST edit invoice
     */
    public function editPost($invoice, RequestInvoiceCreate $request){
        // validasi inputan
        $request->validated();

        $invoice = Invoice::where("invoice", $invoice)->first();

        abort_if(!$invoice, 404, "data not found");

        try {
            DB::beginTransaction();

            $invoice->update([
                "asal"=> $request->input("kota-asal"),
                "tujuan"=> $request->input("kota-tujuan"),
                "history" => $this->invoiceService->addHistory("update", "update data invoice", $invoice->history)
            ]);

            $invoice->invoiceData()->update([
                "form_setting" => $this->invoiceService->createFormSetting($request),
                "berat" => $request->input("berat"),
                "harga_kg" => $request->input("harga/kg"),
                "koli" => $request->input("koli"),
                "kategori"=> $request->input("keterangan-barang"),
                "pemeriksaan"=> $request->input("pemeriksaan"),
            ]);

            $invoice->invoicePerson()->update([
                "pengirim"=> $request->input("nama-pengirim"),
                "kontak_pengirim"=> $request->input("kontak-pengirim"),
                "penerima"=> $request->input("nama-penerima"),
                "kontak_penerima"=> $request->input("kontak-penerima"),
                "alamat"=> $request->input("alamat-penerima"),
            ]);

            $invoice->invoiceCost()->update([
                "biaya_kirim"=> $request->input("biaya-kirim"),
                "biaya_lainnya"=> $this->invoiceService->createBiayalainnya($request),
                "biaya_total"=> $request->input("total-biaya"),
            ]);

            DB::commit();

            Log::info("berhasil update data invoice: " . $invoice->invoice, [
                "user" => "email"
            ]);

            return redirect()->route("invoice.detail", ["invoice" => $invoice->invoice])->with("success", "invoice berhasil diupdate");
        } catch (Throwable $th) {
            DB::rollBack();

            Log::error("gagal edit invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", "gagal dalam update data invoice. Coba lagi nanti");
        }
    }

    /**
     * GET detail invoice yang ditargetkan
     */
    public function detail($invoice){

        $invoice = Invoice::where("invoice", $invoice)->first();

        return view("invoice.detail", compact("invoice"));
    }
}
