<?php

namespace Tests\Feature\invoice;

use App\Service\InvoiceServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    private InvoiceServiceInterface $invoiceService;

    protected function setUp(): void{
        parent::setUp();

        $this->invoiceService = $this->app->make(InvoiceServiceInterface::class);
    }

    /**
     * Test Class Invoice Service
     */
    public function testClassService(){
        $this->assertTrue(true);
    }

    // membuat data history baru
    public function testNewHistory(){

        $history = $this->invoiceService->addHistory("post", "ini keterangan test unit");

        $this->assertCount(1, $history);

        $this->assertCount(4, $history[0]);

        $oldHistory = [
            [
                "action" => "post",
                "keterangan" => "old keterangan",
                "person" => "test case",
                "data" => now()
            ]
        ];

        
        $history = $this->invoiceService->addHistory("post", "ini keterangan test unit", $oldHistory);

        $this->assertCount(2, $history);
    }

    // membuat tracking baru
    public function testNewTracking(){

        $tracking = $this->invoiceService->addTracking("diterima", "Yogyakarta", "ini deskripsi");

        $this->assertCount(1, $tracking);

        $oldTracking = [
            [
                "status" => "diterima",
                "location" => "yogyakarta",
                "deskripsi" => "deskripsi",
                "person" => "dummy"
            ]
        ];

        $tracking = $this->invoiceService->addTracking("diterima", "Yogyakarta", "ini deskripsi", $oldTracking);

        $this->assertCount(2, $tracking);

    }
}
