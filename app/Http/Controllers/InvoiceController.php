<?php

namespace App\Http\Controllers;

use App\Models\invoice\Invoice;
use App\Service\InvoiceServiceInterface;
use Illuminate\Http\Request;

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
            if($this->invoiceService->invoiceExpired($invoice)){
                $invoice->status = "warning";
            }
        });

        return view("invoice.index", compact("invoices"));
    }

    /**
     * GET detail invoice yang ditargetkan
     */
    public function detail($invoice){

        $invoice = Invoice::with("invoiceData", "invoicePerson", "invoiceCost")->where("invoice", $invoice)->first();

        return view("invoice.detail", compact("invoice"));
    }
}
