<?php

namespace App\Service\Impl;
use App\Service\PdfServiceInterface;

class PdfServiceImpl implements PdfServiceInterface{

    protected $imageService;

    public function __construct(ImageGenServiceImpl $imageGenServiceImpl){
        $this->imageService = $imageGenServiceImpl;
    }

    public function cetakResiInvoice(\App\Models\invoice\Invoice $invoice): void{
        try{

            $pdf = new \FPDF('P', 'mm', [120, 100]);
            $pdf->SetMargins( 5, .5, 5);
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false);


            // define lebar
            $w = [
                'full' => 90, // full width
                'half' => 50,
                'data' => 45
            ];

            // set Logo
            $pdf->Image('logo.png', 28, 8, 42, null, "PNG");
            $pdf->SetY(18);

            // set nomer resi
            $pdf->SetFont('Arial','B', 10);
            $pdf->Cell($w['full'], 8, "No Invoice $invoice->invoice", 1, 2, 'C');
            $pdf->Cell($w['full'], 20, '', 1, 2, 'C');
            $barcode = $this->imageService->barCode($invoice->invoice);
            $pdf->Image("temp/$barcode", 15, 29, 70, 14, 'PNG');

            // set data pengirim
            $pdf->Cell($w['data'], 20, '', 1);
            $pdf->SetFont('Arial','B', 6);
            $pdf->Text(7, 50, 'Pengirim');

            // pengirim
            $pdf->SetFont('Arial','', 6);
            $pdf->Text(10, 53, $invoice->invoicePerson->pengirim . ',');
            $pdf->Text(10, 56, 'Kota Yogyakarta Gamping' . ',');
            $pdf->Text(10, 58, "Tlp. " . $invoice->invoicePerson->kontak_pengirim);

            // penerima
            $pdf->Cell($w['data'], 20, '', 1);
            $pdf->SetFont('Arial','B', 6);
            $pdf->Text(53, 50, 'Penerima');

            // penerima
            $pdf->SetFont('Arial','', 6);
            $pdf->Text(53, 53, $invoice->invoicePerson->penerima . ',');
            if(strlen($invoice->invoicePerson->alamat) > 35){
                // dibagi kedalam array tiap 35 length
                $i = 0;
                $chunks = str_split($invoice->invoicePerson->alamat, 40);
                foreach($chunks as $item){
                    $pdf->Text(53, (56 + $i), $item);
                    $i = $i + 2;
                }
                $pdf->Text(53, (56 + $i), 'Tlp. ' . $invoice->invoicePerson->kontak_penerima);
            }else{
                $pdf->Text(53, 56, $invoice->invoicePerson->alamat . ',');
                $pdf->Text(53, 58, 'Tlp. ' . $invoice->invoicePerson->kontak_penerima);
            }
            $pdf->Ln();

            // qr code
            $pdf->Cell( 30, 30, null, 1);
            $qrCode = $this->imageService->qrCode($invoice->invoice);
            $pdf->Image("temp/$qrCode", 8, 69, 25, null, "PNG");

            // keterangan barang
            $pdf->Cell( 60, 30, null, 1);
            $pdf->Text(38, 72, 'Deskripsi Barang');
            $pdf->Text( 40, 75, $invoice->invoiceData->kategori);

            // intruksi khusus
            $pdf->SetFont('Arial','B', 6);
            $pdf->Text(38, 80, 'Intruksi Khusus');
            $pdf->SetFont('Arial','', 6);
            $pdf->Text(38, 83, '');
            $pdf->Ln();

            // Koli
            $pdf->Cell($w['full'], 11, '', 1);
            $pdf->SetFont('Arial','B', 10);
            $pdf->Text(8, 103, 'Koli No : ');

            unlink("temp/$barcode");
            unlink("temp/$qrCode");

            // output
            ob_end_clean();
            header('Content-Type: application/pdf');
            $pdf->Output();

        } catch(\Exception $exception){
            throw $exception;
        }
    }
}