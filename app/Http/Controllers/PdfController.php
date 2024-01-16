<?php

namespace App\Http\Controllers;

use App\Models\invoice\Invoice;
use App\Service\PdfServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    protected PdfServiceInterface $pdfService;
    public function __construct(PdfServiceInterface $pdfServiceInterface){
        $this->pdfService = $pdfServiceInterface;
    }

    public function cetakResiInvoice($invoice){
        $invoice = Invoice::where("invoice", $invoice)->first();

        abort_if(!$invoice, 404, "data not found");

        try{
            // cetak pdf
            return $this->pdfService->cetakResiInvoice($invoice);
            
        } catch (\Throwable $th){

            Log::error("gagal mencetak invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal dalam mencetak invoice");
        }
    }

    public function cetakCostumerInvoice($invoice){
        $invoice = Invoice::with("invoicePerson", "invoiceCost", "invoiceData")->where("invoice", $invoice)->first();

        abort_if(!$invoice, 404, "data not found");

        try{
            // cetak pdf
            return $this->pdfService->cetakCostumerInvoice($invoice);
            
        } catch (\Throwable $th){

            Log::error("gagal mencetak invoice: " . $invoice->invoice, [
                "class" => get_class(),
                "function" => __FUNCTION__,
                "massage" => $th->getMessage()
            ]);

            return redirect(url()->previous())->with("error", "gagal dalam mencetak invoice");
        }
    }
}
