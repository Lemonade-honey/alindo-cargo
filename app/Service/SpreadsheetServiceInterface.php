<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface SpreadsheetServiceInterface{
    /**
     * Laporan Invoice ke Spreadsheet
     */
    public function laporanToSpreadsheet(\App\Models\invoice\Invoice $invoices, string $tanggalLaporan): Spreadsheet;

    /**
     * Create File Spreadsheet\
     * 
     * @return string filename
     */
    public function createSpreadsheet(Spreadsheet $spreadsheet, string $namaLaporan): string;
}