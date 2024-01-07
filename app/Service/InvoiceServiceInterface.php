<?php

namespace App\Service;

use App\Models\invoice\Invoice;

interface InvoiceServiceInterface{
    /**
     * Invoice Expired Logic
     * 
     * default expired 21 hari
     * @return true jika expired
     * @return false jika belum expired
     */
    public function invoiceExpired(Invoice $invoice, int $expDays = 21): bool;
}