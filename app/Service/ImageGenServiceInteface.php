<?php

namespace App\Service;

interface ImageGenServiceInteface{
    /**
     * Generate BarCode
     * 
     * @return string filepath gambarnya
     */
    public function barCode(string $dataEncrypt): string;

    /**
     * Generate QrCode
     * 
     * @return string filepath gambarnya
     */
    public function qrCode(string $dataEncrypt): string; 
}