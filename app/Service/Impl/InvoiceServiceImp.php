<?php

namespace App\Service\Impl;
use App\Service\InvoiceServiceInterface;

class InvoiceServiceImp implements InvoiceServiceInterface{
    public function invoiceExpired($invoice, int $expDays = 14): bool{

        $date = new \DateTime($invoice->created_at);
        
        $expiredObj = $date->modify("+$expDays days");
        $expired = $expiredObj->format('Y-m-d');

        if(date('Y-m-d') > $expired && $invoice->status == 'proses'){
            return true;
        }

        return false;
    }
}