<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\invoice\Invoice;
use App\Models\Kota;
use App\Models\member\Member;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Kota::factory(5)->create();
        Invoice::factory(300)->create();
    }
}
