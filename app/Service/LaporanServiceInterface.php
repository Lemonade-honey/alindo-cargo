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
    public function statistikLaporanInvoiceBulan(\Illuminate\Database\Eloquent\Collection $invoices): array;

    /**
     * data invoice bulanan
     */
    public function dataInvoiceBulan(\Illuminate\Database\Eloquent\Collection $invoices): \Illuminate\Database\Eloquent\Collection;
}