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
            "deskripsi" => "Paket disetorkan di Gudang Alindo Cargo Yogyakarta",
            "person" => fake()->name(),
            "date" => now()
        ];

        $tracking2 = [
            [
                "status" => "Diterima",
                "location" => "YOGYAKARTA",
                "deskripsi" => "Paket diterima oleh staff",
                "person" => fake()->name(),
                "date" => now()
            ],
            [
                "status" => "Diterima",
                "location" => "YOGYAKARTA",
                "deskripsi" => "Paket diterima di Gudang Alindo Cargo Yogyakarta",
                "person" => fake()->name(),
                "date" => now()->addHours(2)
            ]
        ];

        return [
            "tracking" => fake()->boolean() ? [$trackingDefault] : $tracking2
        ];
    }
}
