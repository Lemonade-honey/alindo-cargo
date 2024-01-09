<?php

namespace App\Service\Impl;
use App\Service\InvoiceServiceInterface;

class InvoiceServiceImp implements InvoiceServiceInterface{

    public function createFormSetting(\App\Http\Requests\RequestInvoiceCreate $requestInvoiceCreate): array | null{
        $formSettings = [
            "form-lock" => $requestInvoiceCreate->has("form-lock") ? true : false,
            "member-id" => $requestInvoiceCreate->input("member-id") ?? null
        ];

        return $formSettings;
    }

    public function createBiayalainnya(\App\Http\Requests\RequestInvoiceCreate $requestInvoiceCreate): array | null{
        $biayaLainnya = [];

        if($requestInvoiceCreate->has('ket_lainnya')){
            foreach ($requestInvoiceCreate->input("ket_lainnya") as $key => $value) {
                array_push($biayaLainnya, [
                    'keterangan' => $value,
                    'harga' => $requestInvoiceCreate->input("ket_biaya")[$key]
                ]);
            }
        }

        return $biayaLainnya;
    }

    public function expired($invoice, int $expDays = 14): bool{

        $date = new \DateTime($invoice->created_at);
        
        $expiredObj = $date->modify("+$expDays days");
        $expired = $expiredObj->format('Y-m-d');

        if(date('Y-m-d') > $expired && $invoice->status == 'proses'){
            return true;
        }

        return false;
    }

    public function addHistory(string $action, string $keterangan, ?array $history = null): array{

        if($history !== null){
            $oldHistory = $history;

            array_push($oldHistory, [
                "action" => strtoupper($action),
                "keterangan" => $keterangan,
                "person" => "nama user",
                "date" => date("H:i:s d M Y")
            ]);

            return $oldHistory;
        }

        return $history = [
            [
                "action" => strtoupper($action),
                "keterangan" => $keterangan,
                "person" => "nama user",
                "date" => date("H:i:s d M Y")
            ]
        ];;
    }

    public function addTracking(string $status, string $location, string $deskripsi, ?array $tracking = null): array{
        
        if($tracking !== null){
            array_push($tracking, [
                "status" => $status,
                "location" => $location,
                "deskripsi" => $deskripsi,
                "person" => "nama user",
                "date" => date("H:i:s d M Y")
            ]);

            return $tracking;
        }

        return $tracking = [
            [
                "status" => $status,
                "location" => $location,
                "deskripsi" => $deskripsi,
                "person" => "nama user",
                "date" => date("H:i:s d M Y")
            ]
        ];
    }

    public function listVendor(\App\Models\invoice\Invoice $invoice): array{

        (int) $totalBiayaVendor = 0;

        foreach($invoice->invoiceVendors as $vendor){
            $vendors[] = [
                "id" => $vendor->id,
                "nama_vendor" => $vendor->vendor->nama,
                "kota_vendor" => $vendor->kota->kota,
                "harga_vendor" => $vendor->harga,
                "total_vendor" => ($invoice->invoiceData->berat * $vendor->harga)
            ];

            $totalBiayaVendor += ($invoice->invoiceData->berat * $vendor->harga);
        }

        return ["vendors" => $vendors ?? [], "total-harga" => $totalBiayaVendor];
    }
}