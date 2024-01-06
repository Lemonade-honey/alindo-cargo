<?php

namespace Database\Factories\invoice;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\invoice\InvoicePerson>
 */
class InvoicePersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "pengirim" => fake()->name(),
            "kontak_pengirim" => fake()->phoneNumber(),
            "penerima" => fake()->name(),
            "kontak_penerima" => fake()->phoneNumber(),
            "alamat" => fake()->address()
        ];
    }
}
