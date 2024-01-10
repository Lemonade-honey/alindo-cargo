<?php

namespace App\Service\Impl;

use App\Service\LaporanServiceInterface;

class LaporanServiceImpl implements LaporanServiceInterface{

    private InvoiceServiceImp $invoiceService;

    public function __construct(InvoiceServiceImp $invoiceServiceImp){
        $this->invoiceService = $invoiceServiceImp;
    }

    public function statistikLaporanInvoiceBulan($invoices): array
    {
        $statistik = [
            "target-tanggal" => date('F, Y', strtotime($invoices->pluck("created_at")->first())),

            "total-invoice" => $invoices->count(),

            "total-warning" => $invoices->sum(function($invoice){
                return $invoice->status == 'warning';
            }),

            "total-harga-invoice" => $invoices->sum(function($invoice){
                // jika belum bayar, maka akan dikasih nilai 0
                return $invoice->invoiceCost->status != 'belum bayar' ? $invoice->invoiceCost->biaya_total : 0;
            }),

            "total-harga-vendor" => $invoices->sum(function($invoice){
                return $invoice->invoiceCost->status != 'belum bayar' ? $invoice->total_harga_vendor : 0;
            })
        ];

        $statistik["total-profit"] = (int) $statistik["total-harga-invoice"] - $statistik["total-harga-vendor"];

        return $statistik;
    }

    public function dataInvoiceBulan($invoices): \Illuminate\Database\Eloquent\Collection{
        $invoices = $invoices->each(function($invoice){
            // status invoice
            if($this->invoiceService->expired($invoice)){
                $invoice->status = 'warning';
            }

            // vendor logic
            (int) $totalHargaVendor = 0;
            foreach($invoice->invoiceVendors as $vendor){
                $totalHargaVendor += $vendor->harga * $invoice->invoiceData->berat;
            }

            // save data into collection invoice
            $invoice->total_harga_vendor = $totalHargaVendor;

            // profit logic
            $invoice->profit = $invoice->invoiceCost->status != 'belum bayar' ? (int) ($invoice->invoiceCost->biaya_total - $invoice->total_harga_vendor) : 0;
        });

        return $invoices;
    }
}