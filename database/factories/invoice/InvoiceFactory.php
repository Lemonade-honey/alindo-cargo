<?php

namespace Database\Factories\invoice;

use App\Models\invoice\Invoice;
use App\Models\invoice\InvoiceCost;
use App\Models\invoice\InvoiceData;
use App\Models\invoice\InvoicePerson;
use App\Models\invoice\InvoiceTracking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\invoice\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $asalKota = "Yogyakarta - Base";
        $tujuan = fake()->city();
        $status = "proses"; // selesai,batal
        $keterangan = 'ini data dummy';
        $history = [
            "action" => "POST",
            "keterangan" => "dummy faker data",
            "person" => "dummy",
            "date" => now()
        ];

        return [
            "asal" => $asalKota,
            "tujuan" => $tujuan,
            "status" => $status,
            "keterangan" => $keterangan,
            "history" => [$history]
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            InvoiceData::factory()->create(['id_invoice' => $invoice->id]);
            InvoiceCost::factory()->create(['id_invoice' => $invoice->id]);
            InvoicePerson::factory()->create(['id_invoice' => $invoice->id]);
            InvoiceTracking::factory()->create(['id_invoice' => $invoice->id]);
        });
    }
}
