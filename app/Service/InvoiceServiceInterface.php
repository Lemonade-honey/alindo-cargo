<?php

namespace App\Service;

use App\Http\Requests\RequestInvoiceCreate;
use App\Models\invoice\Invoice;

interface InvoiceServiceInterface{

    /**
     * Invoice Handle Create Input Form Settings
     */
    public function createFormSetting(RequestInvoiceCreate $requestInvoiceCreate): array | null;

    /**
     * Invoice Handle Create Input Biaya lainnya
     */
    public function createBiayalainnya(RequestInvoiceCreate $requestInvoiceCreate): array | null;

    /**
     * Invoice Expired Logic
     * 
     * default expired 21 hari
     * @return true jika expired
     * @return false jika belum expired
     */
    public function expired(Invoice $invoice, int $expDays = 21): bool;

    /**
     * Invoice History Service
     */
    public function addHistory(string $action, string $keterangan, ?array $history = null): array;

    /**
     * Invoice Tracking Service
     */
    public function addTracking(string $status, string $location, string $deskripsi, ?array $tracking = null): array;
}