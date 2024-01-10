<?php

namespace App\Service;

interface LaporanServiceInterface{
    /**
     * statistik laporan
     * 
     * data statistik untuk sebuah laporan
     * @return array
     *  ["total-harga-invoice-"]
     *  ["total-harga-vendor"]
     *  ["total-invoice"]
     *  ["total-warning-invoice"]
     */
    public function statistikLaporanInvoiceBulan(\App\Models\invoice\Invoice $invoices): array;

    /**
     * data invoice bulanan
     */
    public function dataInvoiceBulan(\App\Models\invoice\Invoice $invoices);
}