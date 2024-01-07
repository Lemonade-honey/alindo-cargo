<?php

namespace App\Http\Controllers;

use App\Models\invoice\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * GET List all invoice
     * 
     * menggunakan livewire untuk data tablenya
     */
    public function index(){

        $invoices = Invoice::with("invoicePerson", "invoiceCost")->orderBy("id", "desc")->paginate(10);

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
