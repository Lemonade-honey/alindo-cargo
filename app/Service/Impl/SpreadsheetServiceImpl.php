<?php

namespace App\Service\Impl;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetServiceImpl implements \App\Service\SpreadsheetServiceInterface{

    private $laporanService;

    public function __construct(LaporanServiceImpl $laporanServiceImpl){
        $this->laporanService = $laporanServiceImpl;
    }

    public function laporanToSpreadsheet($invoices, string $tanggalLaporan): Spreadsheet
    {
        $spreadSheet = new Spreadsheet;
        $activeWorksheet = $spreadSheet->getActiveSheet();

        // set column deminsion
        $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('D')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('E')->setAutoSize(true);

        // // set Header
        $activeWorksheet->setCellValue( 'A1', 'Rekap Laporan Bulan ' . date('F, Y', strtotime($tanggalLaporan)));
        $activeWorksheet->mergeCells("A1:S1");
        $activeWorksheet->getStyle('A1')->getFont()->setSize(20);
        $activeWorksheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // // set nama kolom pada posisi row 1, sebagai judul column
        $activeWorksheet->setCellValue('A2', 'No');
        $activeWorksheet->setCellValue('B2', 'Invoice');
        $activeWorksheet->setCellValue('C2', 'Tanggal');
        $activeWorksheet->setCellValue('D2', 'Status');
        $activeWorksheet->setCellValue('E2', 'Tujuan');
        $activeWorksheet->setCellValue('F2', 'Berat');
        $activeWorksheet->setCellValue('G2', 'Harga/kg');
        $activeWorksheet->setCellValue('H2', 'Kategori');
        $activeWorksheet->setCellValue('I2', 'Pemeriksaan');
        $activeWorksheet->setCellValue('J2', 'Pengirim');
        $activeWorksheet->setCellValue('K2', 'Penerima');
        $activeWorksheet->setCellValue('L2', 'Kontak Penerima');
        $activeWorksheet->setCellValue('M2', 'Alamat Penerima');
        $activeWorksheet->setCellValue('N2', 'Biaya Total');
        $activeWorksheet->setCellValue('O2', 'Status Pembayaran');
        $activeWorksheet->setCellValue('P2', 'Metode Pembayaran');
        $activeWorksheet->setCellValue('Q2', 'Vendor');
        $activeWorksheet->setCellValue('R2', 'Vendor Total');
        $activeWorksheet->setCellValue('S2', 'Profit');
        
        $i = 2;

        // olah data terlebih dahulu, agar dapat profit dan total harga vendor
        $invoices = $this->laporanService->dataInvoiceBulan($invoices);

        foreach($invoices as $key => $value){
            $i++;
            $activeWorksheet->setCellValue('A'. $i, $key + 1);
            $activeWorksheet->setCellValue('B'. $i, $value->invoice);
            $activeWorksheet->setCellValue('C'. $i, date('H:i, d-m-Y', strtotime($value->created_at)));
            $activeWorksheet->setCellValue('D'. $i, $value->status);
            $activeWorksheet->setCellValue('E'. $i, $value->tujuan);
            $activeWorksheet->setCellValue('F'. $i, $value->invoiceData->berat);
            $activeWorksheet->setCellValue('G'. $i, $value->invoiceData->harga_kg);
            $activeWorksheet->setCellValue('H'. $i, $value->invoiceData->kategori);
            $activeWorksheet->setCellValue('I'. $i, $value->invoiceData->pemeriksaan);
            $activeWorksheet->setCellValue('J'. $i, $value->invoicePerson->pengirim);
            $activeWorksheet->setCellValue('K'. $i, $value->invoicePerson->penerima);
            $activeWorksheet->setCellValue('L'. $i, $value->invoicePerson->kontak_penerima);
            $activeWorksheet->setCellValue('M'. $i, $value->invoicePerson->alamat);
            $activeWorksheet->setCellValue('N'. $i, $value->invoiceCost->biaya_total);
            $activeWorksheet->setCellValue('O'. $i, $value->invoiceCost->status);
            $activeWorksheet->setCellValue('P'. $i, $value->invoiceCost->metode);
            $activeWorksheet->setCellValue('Q'. $i, $value->invoiceVendors->count());
            $activeWorksheet->setCellValue('R'. $i, $value->total_harga_vendor);
            $activeWorksheet->setCellValue('S'. $i, $value->profit);
        }

        return $spreadSheet;
    }

    public function createSpreadsheet(Spreadsheet $spreadsheet, string $namaLaporan): string
    {
        $filename = $namaLaporan . ".xlsx";

        $dirFile = public_path("temp/$filename");

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($dirFile);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($dirFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($dirFile));

        return $filename;
    }
}