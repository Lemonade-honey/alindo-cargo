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

    // test logic expired
    public function testExpired(){
        $invoice = new \App\Models\invoice\Invoice();
        $invoice->status = 'proses';
        $invoice->created_at = "12-12-2023";

        self::assertTrue($this->invoiceService->expired($invoice));

        $invoice->status = 'selesai';
        self::assertFalse($this->invoiceService->expired($invoice));

        $invoice->status = 'batal';
        self::assertFalse($this->invoiceService->expired($invoice));

        $invoice->status = 'proses';
        $invoice->created_at = now();
        self::assertFalse($this->invoiceService->expired($invoice));
    }

    // membuat data history baru
    public function testHistory(){

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
    public function testTracking(){

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
