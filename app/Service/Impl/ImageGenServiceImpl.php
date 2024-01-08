<?php

namespace App\Service\Impl;
use App\Service\ImageGenServiceInteface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ImageGenServiceImpl implements ImageGenServiceInteface{

    protected string $temp = "temp/";

    public function barCode(string $dataEncrypt): string{
        // define
        $generator = new BarcodeGeneratorPNG();

        $date = date("His");
        //  format file name
        $filename = "$dataEncrypt-barcode-$date.png";

        // // save
        if(!file_put_contents(public_path($this->temp) . $filename, $generator->getBarcode($dataEncrypt, $generator::TYPE_CODE_128, 3, 50))){
            // jika gagal
            throw new \Exception("Gagal dalam pembuatan barcode, class => " . get_class() . ", function => " . __FUNCTION__);
        }

        // return file name
        return $filename;
    }

    public function qrCode(string $dataEncrypt): string{

        $qrCode = QrCode::create($dataEncrypt);

        $writer = new PngWriter;

        $result = $writer->write($qrCode);

        $date = date('His');

        $filename = "$dataEncrypt-qrcode-$date.png";

        $result->saveToFile(public_path($this->temp) . $filename);

        if(!file_exists(public_path($this->temp) . $filename)){
            throw new \Exception("Gagal dalam pembuatan file qrcode, class => " . get_class() . ", function => " . __FUNCTION__);
        }

        return $filename;
    }
}