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

    public function cetakCostumerInvoice(\App\Models\invoice\Invoice $invoice){

        $pdf = new \FPDF('P', 'mm', [120, 100]);
        $pdf->SetMargins( 5, .5, 5);
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        $pdf->Image('logo.png', 5, 6, 45, null, "png");
        $pdf->SetFont('Arial','B', 12);
        $pdf->SetXY(55, 5);

        // barcode
        $pdf->Cell(40, 8, $invoice->invoice, 1, 0, 'C');
        $barcode = $this->imageService->barCode($invoice->invoice);
        $pdf->Image("temp/$barcode", 55, 15, 40, 10, "PNG");
        $pdf->Ln(20);

        // tgl invoice
        $pdf->Cell(35, 8, '', 1);
        $pdf->SetFont('Arial','', 8);
        $pdf->Text(6, 30, 'Tanggal: ' . date('d F Y', strtotime($invoice->created_at)));
        $pdf->Ln(10);

        $pdf->Cell(45, 15, '', 1); // pengirim
        $pdf->Text(6, 40, 'Pengirim');
        $pdf->Text(6, 45, $invoice->invoicePerson->pengirim);

        $pdf->Cell(45, 35, '', 1); // alamat penerima
        $pdf->SetFont('Arial','B', 8);
        $pdf->Text(52, 40, 'Alamat penerima');
        $alamat = $invoice->invoicePerson->alamat;
        $pdf->SetFont('Arial','', 8);
        if(strlen($alamat) > 29){
            $i = 0;
            $chunks = str_split($alamat, 29);
            foreach($chunks as $item){
                $pdf->Text(52, (44 + $i), $item);
                $i = $i + 3;
            }
        }else{
            $pdf->Text(52, 44, $alamat);
        }

        $pdf->Ln();

        $pdf->SetY(50);
        $pdf->Cell(45, 20, '', 1); // penerima
        $pdf->Text(6, 55, 'Penerima');
        $pdf->Text(6, 60, $invoice->invoicePerson->penerima . ",");
        $pdf->Text(6, 63, $invoice->invoicePerson->kontak_penerima);
        $pdf->Text(6, 66, $invoice->tujuan);

        $pdf->Ln();
        $pdf->SetFont('Arial','B', 8);
        $pdf->Cell(22.5, 7, 'QTY', 1, 0, 'C');
        $pdf->Cell(22.5, 7, 'Berat', 1, 0, 'C');
        $pdf->Cell(45, 7, 'Isi barang', 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetFont('Arial','', 8);
        $pdf->Cell(22.5, 25, $invoice->invoiceData->koli, 1, 0, 'C');
        $pdf->Cell(22.5, 25, $invoice->invoiceData->berat . ' Kg', 1, 0, 'C');
        $pdf->Cell(45, 25, $invoice->invoiceData->kategori, 1, 0, 'C');

        unlink("temp/$barcode");
        $pdf->Output();
    }
}