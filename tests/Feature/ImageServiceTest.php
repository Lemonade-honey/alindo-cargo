<?php

namespace Tests\Feature;

use App\Service\ImageGenServiceInteface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    private ImageGenServiceInteface $imageService;

    protected function setUp(): void{
        parent::setUp();

        $this->imageService = $this->app->make(ImageGenServiceInteface::class);
    }

    public function test_setUp(){
        self::assertTrue(true);
    }

    // test buat barcode
    public function testCreateBarCode(){
        $filename = $this->imageService->barCode('123456');

        self::assertTrue(true);

        self::assertTrue(file_exists(public_path("temp/$filename")));

        self::assertTrue(unlink(public_path("temp/$filename")));
    }

    // test buat qrcode
    public function testCreateQrCode(){
        $filename = $this->imageService->qrCode('123456');

        self::assertTrue((file_exists(public_path("temp/$filename"))));

        self::assertTrue(unlink(public_path("temp/$filename")));
    }
}
