<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "name" => "super admin account",
                "email" => "alindo@alindocargo.com",
                "email_verified_at" => now(),
                "password" => "4lindoCargo@jaya4ever"
            ]
        ];

        foreach ($data as $key => $value) {
            \App\Models\User::create($value);
        }
    }
}
