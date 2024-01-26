<?php

namespace Database\Factories\invoice;

use App\Models\invoice\Invoice;
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
        $status = fake()->boolean() ? "proses" : "selesai";
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
            \App\Models\invoice\InvoiceData::factory()->create(['id_invoice' => $invoice->id]);
            \App\Models\invoice\InvoicePerson::factory()->create(['id_invoice' => $invoice->id]);
            \App\Models\invoice\InvoiceTracking::factory()->create(['id_invoice' => $invoice->id]);
        });
    }
}
