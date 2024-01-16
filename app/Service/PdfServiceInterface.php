<?php

namespace App\Service;

use App\Models\invoice\Invoice;

interface PdfServiceInterface{

    /**
     * Service Cetak Resi Invoice
     */
    public function cetakResiInvoice(Invoice $invoice): void;

    /**
     * Service Cetak Invoice Costumer
     */
    public function cetakCostumerInvoice(Invoice $invoice);
}