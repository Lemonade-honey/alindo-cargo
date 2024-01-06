<?php

namespace Database\Factories\invoice;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\invoice\InvoiceTracking>
 */
class InvoiceTrackingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trackingDefault = [
            "status" => "Diterima",
            "location" => "YOGYAKARTA",
            "deskripsi" => "Paket disetorkan di Gudang Alindo Cargo Yogyakarta"
        ];

        return [
            "tracking" => [$trackingDefault]
        ];
    }
}
