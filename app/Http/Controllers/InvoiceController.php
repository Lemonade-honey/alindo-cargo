<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestInvoiceCreate;
use App\Models\invoice\Invoice;
use App\Models\invoice\InvoiceCost;
use App\Models\invoice\InvoiceData;
use App\Models\invoice\InvoicePerson;
use App\Models\invoice\InvoiceTracking;
use App\Models\Kota;
use App\Service\InvoiceServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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

        $invoices = Invoice::with("invoicePerson", "invoiceData", "invoiceCost")->orderBy("id", "desc")->paginate(10);

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
        $hubungan = \App\Models\costumer\CostumerTetap::with("costumer1", "costumer2")->get();

        return view("invoice.create", compact("kotas", "hubungan"));
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

            return redirect()->route("invoice.detail", ["resi" => $invoice->resi])->with("success", "Invoice berhasil dibuat");
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
    public function edit($resi){
        $invoice = Invoice::with("invoicePerson", "invoiceData", "invoiceCost")->where("resi", $resi)->first();

        abort_if(!$invoice, 404, "data not found");

        $kotas = Kota::get();

        $hubungan = \App\Models\costumer\CostumerTetap::with("costumer1", "costumer2")->get();

        return view("invoice.edit", compact("invoice", "kotas", "hubungan"));
    }

    /**
     * POST edit invoice
     */
    public function editPost($resi, RequestInvoiceCreate $request){
        // validasi inputan
        $request->validated();

        $invoice = Invoice::where("resi", $resi)->first();

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

            return redirect()->route("invoice.detail", ["resi" => $invoice->resi])->with("success", "invoice berhasil diupdate");
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
    public function detail($resi){

        $invoice = Invoice::with("invoiceData", "invoiceCost", "invoicePerson", "invoiceVendors.vendor",
        "invoiceVendors.kota", "invoiceTracking")->where("resi", $resi)->first();

        $invoice->vendors = $this->invoiceService->listVendor($invoice);

        return view("invoice.detail", compact("invoice"));
    }

    /**
     * GET delete invoice
     * 
     * path untuk menghapus invoice
     */
    public function deleteInvoice($resi){
        $invoice = Invoice::where("resi", $resi)->first();

        abort_if(!$invoice, 404, "data not found");

        try{
            $invoiceKode = $invoice->invoice;
            $invoice->delete();

            Log::info("berhasil menghapus invoice: $invoiceKode", [
                "user" => "email"
            ]);

            return redirect()->route('invoice')->with("success", "berhasil menghapus invoice: $invoiceKode");
        } catch (Throwable $th){
            Log::error("gagal hapus invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal menghapus invoice");
        }
    }

    /**
     * Set Status Invoice
     */
    public function setStatusInvoice($resi, Request $request){
        $request->validate([
            "status" => ["required", "in:proses,selesai,batal"],
            "keterangan" => ["nullable", "max:255"]
        ]);

        $invoice = Invoice::where("resi", $resi)->first();

        abort_if(!$invoice, 404, "data not found");

        try{
            $invoice->status = $request->input("status");
            $invoice->keterangan = $request->input("keterangan");
            $invoice->history = $this->invoiceService->addHistory("update", "update status invoice to " . $request->input("status"), $invoice->history);
            $invoice->save();

            Log::info("berhasil update status invoice: " . $invoice->invoice . " ke " . $request->input("status"), [
                "user" => "email user"
            ]);

            return back()->with("success", "status invoice berhasil diubah");
        } catch(Throwable $th){
            Log::error("gagal update status invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return back()->with("error", 'gagal mengubah status');
        }
    }

    /**
     * SET Vendor Invoice
     */
    public function vendorInvoice($resi){

        $invoice = Invoice::with("invoiceData", "invoiceVendors.vendor", "invoiceVendors.kota")->where("resi", $resi)->first();

        abort_if(!$invoice, 404);

        $invoice->vendors = $this->invoiceService->listVendor($invoice);

        $kota = Kota::orderBy("kota")->get();

        return view("invoice.vendor", compact("invoice", "kota"));
    }

    public function vendorInvoicePost($resi, Request $request){

        $invoice = Invoice::where("resi", $resi)->first();

        abort_if(!$invoice, 404, "Invoice Not Found");

        $request->validate([
            "kota-vendor" => "required",
            "id-vendor" => ["required",
                // validasi data di table relasi
                Rule::unique("vendor_invoices", "id_vendor_details")->where(function($query) use ($request, $invoice){
                    return $query->where('id_vendor_details', '=', $request->input("id-vendor"))
                    ->where('id_invoice', '=', $invoice->id);
                })
            ]
        ],[
            "id-vendor.unique" => "Vendor sudah terdaftar di list."
        ]);

        try{
            DB::beginTransaction();

            \App\Models\vendor\VendorInvoice::create([
                "id_invoice" => $invoice->id,
                "id_vendor_details" => $request->input("id-vendor")
            ]);

            $invoice->history = $this->invoiceService->addHistory("update", "tambah vendor pada invoice", $invoice->history);
            $invoice->save();

            DB::commit();

            Log::info("invoice vendor berhasil ditambahkan. invoice: " . $invoice->invoice, [
                "user" => "email"
            ]);

            return back()->with("success", "vendor berhasil ditambahkan");
        } catch (Throwable $th){
            DB::rollBack();
            Log::error("invoice vendor gagal ditambahkan. invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            dd($th);
            return back()->with("error", "vendor gagal ditambahkan");
        }
    }

    public function vendorInvoiceDelete($resi, $idVendorInvoice){

        $invoice = Invoice::where("resi", $resi)->first();

        $vendorInvoice = \App\Models\vendor\VendorInvoice::with("invoice")->where("id_invoice", $invoice->id)
        ->where("id_vendor_details", $idVendorInvoice)->first();

        abort_if(!$vendorInvoice || !$invoice, 404);

        try{
            DB::beginTransaction();

            // update history invoice
            $vendorInvoice->invoice()->update([
                "history" => $this->invoiceService->addHistory("delete", "hapus invoice vendor. vendor-id: " . $vendorInvoice->id_vendor_details, $vendorInvoice->invoice->history)
            ]);

            // delete relasi
            $vendorInvoice->delete();

            DB::commit();

            Log::info("invoice vendor dihapus", [
                "user" => "email"
            ]);

            return redirect(url()->previous())->with("success", "berhasil menghapus vendor invoice");
        } catch(Throwable $th){
            DB::rollBack();

            Log::error("invoice vendor gagal dihapus. invoice: $invoice->invoice", [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            dd($th);

            return redirect(url()->previous())->with("error", "gagal menghapus vendor");
        }
    }
}
