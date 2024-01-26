<?php

namespace Database\Factories\invoice;

use App\Models\invoice\InvoiceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\invoice\InvoiceData>
 */
class InvoiceDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formSetting = [
            "form-lock" => fake()->boolean(70),
            "member-id" => fake()->boolean() ? null : fake()->numberBetween(1, 60)
        ];

        $berat = fake()->numberBetween(60, 120);
        $hargaKg = fake()->numberBetween(1000, 12000);
        $kategori = "Dummy Data";
        $pemeriksaan = fake()->boolean();

        return [
            "form_setting" => $formSetting,
            "berat" => $berat,
            "harga_kg" => $hargaKg,
            "koli" => fake()->boolean() ? 1 : fake()->numberBetween(2, 4),
            "kategori" => $kategori,
            "pemeriksaan" => $pemeriksaan
        ];
    }

    public function configure(){
        return $this->afterCreating(function (InvoiceData $invoiceData){
            \Database\Factories\invoice\InvoiceCostFactory::new([
                'id_invoice' => $invoiceData->id_invoice,
                'biaya_kirim' => $invoiceData->berat * $invoiceData->harga_kg,
                'biaya_total' => $invoiceData->berat * $invoiceData->harga_kg
            ])->create();
        });
    }
}
