<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "invoice",
            "invoice-kelola",
            "invoice-status",
            "invoice-pembayaran",
            "invoice-tracking",
            "invoice-delete",

            "vendor",
            "vendor-kelola", // crud

            "kota",
            "kota-kelola",

            "laporan",
            "laporan-kelola",

            "user",
            "user-kelola",

            "role",
            "role-kelola",

            "costumer",
            "costumer-kelola",

            "chart-statistik-invoice"
        ];

        foreach($permissions as $permission){
            Permission::create(["name" => $permission]);
        }
    }
}
