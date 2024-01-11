<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kota;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(config("app.env") == "local"){
            Kota::factory(5)->create();
            $this->call(InvoiceSeeder::class);
        }

        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
    }
}
