<?php

namespace Database\Factories\invoice;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\invoice\InvoiceCost>
 */
class InvoiceCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $biaya_lainnya = fake()->boolean() ? null : [
            [
                "keteragan" => "pickup",
                "harga" => 10000
            ]
        ];

        return [
            "biaya_kirim" => 900000,
            "biaya_lainnya" => $biaya_lainnya,
            "biaya_total" => 1000000,
            "status" => "belum bayar",
            "deskripsi" => "data dummy"
        ];
    }
}
