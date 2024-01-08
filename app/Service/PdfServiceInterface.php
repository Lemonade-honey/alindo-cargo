<?php

namespace App\Service;

use App\Models\invoice\Invoice;

interface PdfServiceInterface{

    /**
     * Service Cetak Resi Invoice
     */
    public function cetakResiInvoice(Invoice $invoice): void;
}